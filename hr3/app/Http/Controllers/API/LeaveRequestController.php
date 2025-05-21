<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Events\LeaveRequestCreated;
use App\Events\LeaveRequestUpdated;
use App\Events\LeaveRequestDeleted;
use App\Events\DashboardStatsUpdated;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;
        $leaveRequests = LeaveRequest::with(['leaveType'])
            ->where('employee_id', $employee->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'leave_requests' => $leaveRequests
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $employee = Auth::user()->employee;
        $leaveType = LeaveType::findOrFail($request->leave_type_id);

        // Calculate total days
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        // Check if employee has enough leave days
        $usedDays = LeaveRequest::where('employee_id', $employee->id)
            ->where('leave_type_id', $leaveType->id)
            ->where('status', 'approved')
            ->sum('total_days');

        if (($usedDays + $totalDays) > $leaveType->default_days) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient leave days available'
            ], 422);
        }

        $leaveRequest = LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'status' => $leaveType->requires_approval ? 'pending' : 'approved'
        ]);

        if (!$leaveType->requires_approval) {
            $leaveRequest->update([
                'approved_by' => $employee->id,
                'approved_at' => now()
            ]);
        }

        event(new LeaveRequestCreated($leaveRequest));
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
            'message' => 'Leave request submitted successfully',
            'leave_request' => $leaveRequest->load('leaveType')
        ]);
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $this->authorize('view', $leaveRequest);

        return response()->json([
            'status' => 'success',
            'leave_request' => $leaveRequest->load(['leaveType', 'employee', 'approver'])
        ]);
    }

    public function update(Request $request, $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }
        $totalDays = (strtotime($request->end_date) - strtotime($request->start_date)) / 86400 + 1;
        $leaveRequest->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason
        ]);
        event(new LeaveRequestUpdated($leaveRequest));
        $stats = [
            'total_employees' => \App\Models\Employee::count(),
            'active_today' => \App\Models\Employee::whereHas('timeEntries', function($q) {
                $q->whereDate('date', now()->toDateString());
            })->count(),
            'total_hours_today' => \App\Models\TimeEntry::whereDate('date', now()->toDateString())->sum('total_hours'),
            'pending_approvals' => \App\Models\Timesheet::where('status', 'pending')->count(),
        ];
        event(new DashboardStatsUpdated($stats));
        return response()->json(['status' => 'success', 'leave_request' => $leaveRequest]);
    }

    public function destroy($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequestId = $leaveRequest->id;
        $leaveRequest->delete();
        event(new LeaveRequestDeleted($leaveRequestId));
        $stats = [
            'total_employees' => \App\Models\Employee::count(),
            'active_today' => \App\Models\Employee::whereHas('timeEntries', function($q) {
                $q->whereDate('date', now()->toDateString());
            })->count(),
            'total_hours_today' => \App\Models\TimeEntry::whereDate('date', now()->toDateString())->sum('total_hours'),
            'pending_approvals' => \App\Models\Timesheet::where('status', 'pending')->count(),
        ];
        event(new DashboardStatsUpdated($stats));
        return response()->json(['status' => 'success', 'message' => 'Leave request deleted']);
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        $this->authorize('approve', $leaveRequest);

        if ($leaveRequest->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only pending leave requests can be approved'
            ], 422);
        }

        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::user()->employee->id,
            'approved_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Leave request approved successfully'
        ]);
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('reject', $leaveRequest);

        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($leaveRequest->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only pending leave requests can be rejected'
            ], 422);
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Leave request rejected successfully'
        ]);
    }

    public function cancel(LeaveRequest $leaveRequest)
    {
        $this->authorize('cancel', $leaveRequest);

        if ($leaveRequest->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only pending leave requests can be cancelled'
            ], 422);
        }

        $leaveRequest->update(['status' => 'cancelled']);

        return response()->json([
            'status' => 'success',
            'message' => 'Leave request cancelled successfully'
        ]);
    }
}
