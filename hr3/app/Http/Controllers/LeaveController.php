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
        return view('testapp.leave.approval');
    }

    public function history()
    {
        return view('testapp.leave.history');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $employee = \App\Models\Employee::where('user_id', $user->id)->first();
        $canFileLeave = true;
        $errorMsg = null;
        if (!$employee) {
            $canFileLeave = false;
            $errorMsg = 'You must be registered as an employee to file a leave request.';
        } elseif (!\App\Models\Timesheet::where('employee_id', $employee->id)->exists()) {
            $canFileLeave = false;
            $errorMsg = 'You must have a timesheet before you can file a leave request.';
        }
        $leaveTypes = \App\Models\LeaveType::all();
        $leaveRequests = $employee ? \App\Models\LeaveRequest::where('employee_id', $employee->id)->orderBy('created_at', 'desc')->get() : collect();
        $employees = \App\Models\Employee::where('status', 'active')->get();
        return view('testapp.leave.request', compact('leaveTypes', 'leaveRequests', 'employee', 'employees', 'canFileLeave', 'errorMsg'));
    }

    public function submit(Request $request)
    {
        $user = auth()->user();
        $employee = \App\Models\Employee::where('user_id', $user->id)->first();
        if (!$employee) {
            $msg = ['You must be registered as an employee to file a leave request.'];
            if ($request->expectsJson()) {
                return response()->json(['status'=>'error','message'=>$msg[0]], 422);
            }
            return redirect()->back()->withErrors($msg);
        }
        if (!\App\Models\Timesheet::where('employee_id', $employee->id)->exists()) {
            $msg = ['You must have a timesheet before you can file a leave request.'];
            if ($request->expectsJson()) {
                return response()->json(['status'=>'error','message'=>$msg[0]], 422);
            }
            return redirect()->back()->withErrors($msg);
        }
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);
        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);
        $total_days = $start->diffInDays($end) + 1;
        $leave = \App\Models\LeaveRequest::create([
            'employee_id' => $validated['employee_id'],
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $total_days,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);
        if ($request->expectsJson()) {
            return response()->json(['status'=>'success','leave_request'=>$leave]);
        }
        return redirect()->back()->with('status', 'Leave request submitted successfully!');
    }
}
