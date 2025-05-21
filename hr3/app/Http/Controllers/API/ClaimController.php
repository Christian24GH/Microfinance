<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Events\ClaimStatusUpdated;
use App\Events\ClaimCreated;
use App\Events\ClaimUpdated;
use App\Events\ClaimDeleted;
use App\Events\DashboardStatsUpdated;

class ClaimController extends Controller
{
    public function getPendingClaims()
    {
        try {
            $claims = Claim::with(['employee', 'items'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'claims' => $claims
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve pending claims'
            ], 500);
        }
    }

    public function getClaimStats()
    {
        try {
            $today = now()->startOfDay();

            $stats = [
                'pending_count' => Claim::where('status', 'pending')->count(),
                'approved_today' => Claim::where('status', 'approved')
                    ->whereDate('approved_at', $today)
                    ->count(),
                'rejected_today' => Claim::where('status', 'rejected')
                    ->whereDate('rejected_at', $today)
                    ->count()
            ];

            return response()->json([
                'status' => 'success',
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve claim statistics'
            ], 500);
        }
    }

    public function getClaim($id)
    {
        try {
            $claim = Claim::with(['employee', 'items'])
                ->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'claim' => $claim
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Claim not found'
            ], 404);
        }
    }

    public function approveClaim($id)
    {
        try {
            DB::beginTransaction();

            $claim = Claim::findOrFail($id);

            if ($claim->status !== 'pending') {
                throw new \Exception('Claim is not in pending status');
            }

            $claim->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id()
            ]);

            // Create payment record
            Payment::create([
                'claim_id' => $claim->id,
                'amount' => $claim->total_amount,
                'status' => 'pending',
                'created_by' => auth()->id()
            ]);

            DB::commit();

            // Broadcast claim status update
            broadcast(new ClaimStatusUpdated($claim))->toOthers();

            return response()->json([
                'status' => 'success',
                'message' => 'Claim approved successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function rejectClaim(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'reason' => 'required|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $claim = Claim::findOrFail($id);

            if ($claim->status !== 'pending') {
                throw new \Exception('Claim is not in pending status');
            }

            $claim->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
                'rejection_reason' => $request->reason
            ]);

            DB::commit();

            // Broadcast claim status update
            broadcast(new ClaimStatusUpdated($claim))->toOthers();

            return response()->json([
                'status' => 'success',
                'message' => 'Claim rejected successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getReport(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'claim_type' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = Claim::with(['employee', 'items'])
                ->whereBetween('created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);

            if ($request->claim_type) {
                $query->where('claim_type', $request->claim_type);
            }

            $claims = $query->get();

            // Calculate statistics
            $stats = [
                'total_claims' => $claims->count(),
                'approved_claims' => $claims->where('status', 'approved')->count(),
                'rejected_claims' => $claims->where('status', 'rejected')->count(),
                'total_amount' => $claims->sum('total_amount')
            ];

            // Prepare chart data
            $charts = [
                'by_type' => [
                    'labels' => $claims->pluck('claim_type')->unique()->values(),
                    'data' => $claims->groupBy('claim_type')
                        ->map(function ($group) {
                            return $group->count();
                        })->values()
                ],
                'by_status' => [
                    'labels' => ['Approved', 'Pending', 'Rejected'],
                    'data' => [
                        $claims->where('status', 'approved')->count(),
                        $claims->where('status', 'pending')->count(),
                        $claims->where('status', 'rejected')->count()
                    ]
                ]
            ];

            return response()->json([
                'status' => 'success',
                'stats' => $stats,
                'charts' => $charts,
                'claims' => $claims
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate report'
            ], 500);
        }
    }

    public function exportReport(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'claim_type' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = Claim::with(['employee', 'items'])
                ->whereBetween('created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);

            if ($request->claim_type) {
                $query->where('claim_type', $request->claim_type);
            }

            $claims = $query->get();

            // Generate CSV
            $filename = 'claims_report_' . now()->format('Y-m-d_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($claims) {
                $file = fopen('php://output', 'w');

                // Add headers
                fputcsv($file, [
                    'Employee ID',
                    'Employee Name',
                    'Claim Type',
                    'Amount',
                    'Status',
                    'Date',
                    'Approved/Rejected At',
                    'Notes'
                ]);

                // Add data
                foreach ($claims as $claim) {
                    fputcsv($file, [
                        $claim->employee->employee_id,
                        $claim->employee->name,
                        $claim->claim_type,
                        $claim->total_amount,
                        $claim->status,
                        $claim->created_at->format('Y-m-d'),
                        $claim->approved_at ? $claim->approved_at->format('Y-m-d H:i:s') :
                            ($claim->rejected_at ? $claim->rejected_at->format('Y-m-d H:i:s') : ''),
                        $claim->rejection_reason ?? ''
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to export report'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required|exists:employees,id',
                'claim_type' => 'required|string',
                'total_amount' => 'required|numeric|min:0',
                'items' => 'required|array',
                'items.*.description' => 'required|string',
                'items.*.amount' => 'required|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $claim = Claim::create([
                'employee_id' => $request->employee_id,
                'claim_type' => $request->claim_type,
                'total_amount' => $request->total_amount,
                'status' => 'pending',
                'created_by' => auth()->id()
            ]);

            // Create claim items
            foreach ($request->items as $item) {
                $claim->items()->create([
                    'description' => $item['description'],
                    'amount' => $item['amount']
                ]);
            }

            DB::commit();

            event(new ClaimCreated($claim));

            $stats = [
                'total_employees' => \App\Models\Employee::count(),
                'active_today' => \App\Models\Employee::whereHas('timeEntries', function($q) {
                    $q->whereDate('date', now()->toDateString());
                })->count(),
                'total_hours_today' => \App\Models\TimeEntry::whereDate('date', now()->toDateString())->sum('total_hours'),
                'pending_approvals' => \App\Models\Timesheet::where('status', 'pending')->count(),
            ];
            event(new DashboardStatsUpdated($stats));

            return response()->json([
                'status' => 'success',
                'message' => 'Claim created successfully',
                'claim' => $claim->load('items')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create claim: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'claim_type' => 'required|string',
                'total_amount' => 'required|numeric|min:0',
                'items' => 'required|array',
                'items.*.description' => 'required|string',
                'items.*.amount' => 'required|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $claim = Claim::findOrFail($id);

            if ($claim->status !== 'pending') {
                throw new \Exception('Cannot update claim that is not in pending status');
            }

            $claim->update([
                'claim_type' => $request->claim_type,
                'total_amount' => $request->total_amount,
                'updated_by' => auth()->id()
            ]);

            // Delete existing items
            $claim->items()->delete();

            // Create new items
            foreach ($request->items as $item) {
                $claim->items()->create([
                    'description' => $item['description'],
                    'amount' => $item['amount']
                ]);
            }

            DB::commit();

            event(new ClaimUpdated($claim));

            $stats = [
                'total_employees' => \App\Models\Employee::count(),
                'active_today' => \App\Models\Employee::whereHas('timeEntries', function($q) {
                    $q->whereDate('date', now()->toDateString());
                })->count(),
                'total_hours_today' => \App\Models\TimeEntry::whereDate('date', now()->toDateString())->sum('total_hours'),
                'pending_approvals' => \App\Models\Timesheet::where('status', 'pending')->count(),
            ];
            event(new DashboardStatsUpdated($stats));

            return response()->json([
                'status' => 'success',
                'message' => 'Claim updated successfully',
                'claim' => $claim->load('items')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update claim: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $claim = Claim::findOrFail($id);
        $claimId = $claim->id;
        $claim->delete();
        event(new ClaimDeleted($claimId));
        $stats = [
            'total_employees' => \App\Models\Employee::count(),
            'active_today' => \App\Models\Employee::whereHas('timeEntries', function($q) {
                $q->whereDate('date', now()->toDateString());
            })->count(),
            'total_hours_today' => \App\Models\TimeEntry::whereDate('date', now()->toDateString())->sum('total_hours'),
            'pending_approvals' => \App\Models\Timesheet::where('status', 'pending')->count(),
        ];
        event(new DashboardStatsUpdated($stats));
        // ... return response ...
    }

    public function reimburseClaim($id)
    {
        try {
            DB::beginTransaction();

            $claim = Claim::findOrFail($id);

            if ($claim->status !== 'approved') {
                throw new \Exception('Only approved claims can be reimbursed');
            }

            $claim->update([
                'status' => 'reimbursed',
                'reimbursed_at' => now(),
            ]);

            DB::commit();

            // Broadcast claim status update
            broadcast(new ClaimStatusUpdated($claim))->toOthers();

            return response()->json([
                'status' => 'success',
                'message' => 'Claim marked as reimbursed'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
