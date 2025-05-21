<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRecord;
use App\Models\Employee;
use App\Models\OvertimePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Requests\StoreOvertimeRequest;
use App\Http\Resources\OvertimeRecordResource;
use App\Services\OvertimeService;

class OvertimeRecordController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = OvertimeRecord::with(['employee', 'policy']);

            // Apply filters
            if ($request->has('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            if ($request->has('date_from')) {
                $query->where('date', '>=', $request->date_from);
            }
            if ($request->has('date_to')) {
                $query->where('date', '<=', $request->date_to);
            }

            $records = $query->orderBy('date', 'desc')->get();

            return response()->json([
                'status' => 'success',
                'records' => $records
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch overtime records: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreOvertimeRequest $request, OvertimeService $service)
    {
        try {
            $totalHours = $service->calculateTotalHours($request->date, $request->start_time, $request->end_time);
            [$valid, $message] = $service->validatePolicyConstraints($request->policy_id, $totalHours);
            if (!$valid) {
                return response()->json(['status' => 'error', 'message' => $message], 422);
            }
            $compensation = $service->calculateCompensation($request->employee_id, $request->policy_id, $totalHours);
            $record = OvertimeRecord::create([
                'uuid' => Str::uuid(),
                'employee_id' => $request->employee_id,
                'policy_id' => $request->policy_id,
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'total_hours' => $totalHours,
                'reason' => $request->reason,
                'tasks_completed' => $request->tasks_completed,
                'compensation_amount' => $compensation,
                'status' => $request->status,
                'approved_by' => null,
                'approved_at' => null,
                'rejected_by' => null,
                'rejected_at' => null,
                'rejection_reason' => null
            ]);
            return (new OvertimeRecordResource($record))->additional([
                'status' => 'success',
                'message' => 'Overtime record created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create overtime record: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($uuid)
    {
        try {
            $record = OvertimeRecord::with(['employee', 'policy'])
                ->where('uuid', $uuid)
                ->firstOrFail();

            return response()->json([
                'status' => 'success',
                'record' => $record
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Overtime record not found'
            ], 404);
        }
    }

    public function update(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'required|string',
            'tasks_completed' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $record = OvertimeRecord::where('uuid', $uuid)->firstOrFail();

            // Only allow updates if status is pending
            if ($record->status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot update overtime record that is not pending'
                ], 422);
            }

            // Calculate new total hours
            $start = Carbon::parse($record->date . ' ' . $request->start_time);
            $end = Carbon::parse($record->date . ' ' . $request->end_time);
            $totalHours = round($end->diffInSeconds($start) / 3600, 2);

            // Get policy
            $policy = OvertimePolicy::findOrFail($record->policy_id);

            // Validate against policy constraints
            if ($totalHours < $policy->minimum_hours) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Overtime hours must be at least {$policy->minimum_hours} hours"
                ], 422);
            }

            if ($totalHours > $policy->maximum_hours) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Overtime hours cannot exceed {$policy->maximum_hours} hours"
                ], 422);
            }

            // Calculate new compensation
            $employee = Employee::findOrFail($record->employee_id);
            $hourlyRate = $employee->hourly_rate ?? 0;
            $compensation = $totalHours * $hourlyRate * $policy->rate_multiplier;

            $record->update([
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'total_hours' => $totalHours,
                'reason' => $request->reason,
                'tasks_completed' => $request->tasks_completed,
                'compensation_amount' => $compensation
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Overtime record updated successfully',
                'record' => $record
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update overtime record: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($uuid)
    {
        try {
            $record = OvertimeRecord::where('uuid', $uuid)->firstOrFail();

            // Only allow deletion if status is pending
            if ($record->status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete overtime record that is not pending'
                ], 422);
            }

            $record->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Overtime record deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete overtime record: ' . $e->getMessage()
            ], 500);
        }
    }

    public function approve(Request $request, $uuid)
    {
        try {
            $record = OvertimeRecord::where('uuid', $uuid)->firstOrFail();

            if ($record->status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Overtime record is not pending approval'
                ], 422);
            }

            $record->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Overtime record approved successfully',
                'record' => $record
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to approve overtime record: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $record = OvertimeRecord::where('uuid', $uuid)->firstOrFail();

            if ($record->status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Overtime record is not pending approval'
                ], 422);
            }

            $record->update([
                'status' => 'rejected',
                'rejected_by' => auth()->id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->rejection_reason
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Overtime record rejected successfully',
                'record' => $record
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to reject overtime record: ' . $e->getMessage()
            ], 500);
        }
    }
}
