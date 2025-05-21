<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function request()
    {
        return view('testapp.leave.request');
    }

    public function approval()
    {
        $leaveRequests = \App\Models\LeaveRequest::with(['employee', 'leaveType', 'approver'])->orderBy('created_at', 'desc')->get();
        return view('testapp.leave.approval', compact('leaveRequests'));
    }

    public function history()
    {
        return view('testapp.leave.history');
    }

    public function index(Request $request)
    {
        // Get all active employees
        $employees = \App\Models\Employee::where('status', 'active')->get();
        $leaveTypes = \App\Models\LeaveType::all();
        $leaveRequests = \App\Models\LeaveRequest::orderBy('created_at', 'desc')->get();
        return view('testapp.leave.request', compact('leaveTypes', 'leaveRequests', 'employees'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);
        $employee = \App\Models\Employee::find($validated['employee_id']);
        if (!$employee || !\App\Models\Timesheet::where('employee_id', $employee->id)->exists()) {
            return redirect()->back()->withErrors(['Employee must have a timesheet to file a leave request.']);
        }
        $start = \Carbon\Carbon::parse($validated['start_date']);
        $end = \Carbon\Carbon::parse($validated['end_date']);
        $total_days = $start->diffInDays($end) + 1;
        \App\Models\LeaveRequest::create([
            'employee_id' => $validated['employee_id'],
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $total_days,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);
        return redirect()->back()->with('status', 'Leave request submitted successfully!');
    }

    public function approve($id)
    {
        $leave = \App\Models\LeaveRequest::findOrFail($id);
        $employee = \App\Models\Employee::where('user_id', auth()->user()->id)->first();
        $leave->status = 'approved';
        $leave->approved_by = $employee ? $employee->id : null;
        $leave->approved_at = now();
        $leave->save();
        return redirect()->back()->with('status', 'Leave approved!');
    }

    public function reject(Request $request, $id)
    {
        $leave = \App\Models\LeaveRequest::findOrFail($id);
        $leave->status = 'rejected';
        $leave->rejection_reason = $request->input('rejection_reason', '');
        $leave->save();
        return redirect()->back()->with('status', 'Leave rejected!');
    }
}
