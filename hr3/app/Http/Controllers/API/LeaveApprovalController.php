<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveApproval;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveApprovalController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;

        // Get leave requests that need approval
        $approvals = LeaveRequest::with(['employee', 'leaveType'])
            ->whereHas('leaveType', function ($query) {
                $query->where('requires_approval', true);
            })
            ->where('status', 'pending')
            ->where(function ($query) use ($employee) {
                // If user is a manager, show requests from their team
                if ($employee->isManager()) {
                    $query->whereHas('employee', function ($q) use ($employee) {
                        $q->where('department_id', $employee->department_id);
                    });
                }
                // If user is HR, show all requests
                if ($employee->isHR()) {
                    $query->where('id', '>', 0);
                }
            })
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'leave_approvals' => $approvals
        ]);
    }

    public function show($id)
    {
        $approval = LeaveRequest::with(['employee', 'leaveType', 'approver'])
            ->findOrFail($id);

        $this->authorize('view', $approval);

        return response()->json([
            'status' => 'success',
            'leave_approval' => $approval
        ]);
    }

    public function update(Request $request, $id)
    {
        $approval = LeaveRequest::findOrFail($id);
        $this->authorize('approve', $approval);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $employee = Auth::user()->employee;

        if ($request->status === 'approved') {
            $approval->update([
                'status' => 'approved',
                'approved_by' => $employee->id,
                'approved_at' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Leave request approved successfully'
            ]);
        } else {
            $approval->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Leave request rejected successfully'
            ]);
        }
    }
}
