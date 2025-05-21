<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\ClockingRecord;
use App\Models\LeaveRequest;
use App\Models\Workflow;
use App\Models\WorkflowStage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Timesheet;

class HRController extends Controller
{
    // Show the unified HR dashboard
    public function dashboard()
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        $employees = Employee::with('department', 'position')->get();
        $recentClockings = ClockingRecord::with('employee')->latest()->limit(5)->get();
        $recentLeaves = LeaveRequest::with('employee', 'leaveType')->latest()->limit(5)->get();
        $pendingLeaves = LeaveRequest::with('employee', 'leaveType')->where('status', 'pending')->latest()->limit(5)->get();

        // Real-time attendance status for each employee (latest clock-in/out)
        $attendanceStatus = [];
        foreach ($employees as $emp_record) {
            $in = ClockingRecord::where('employee_id', $emp_record->id)
                ->where('clocking_type', 'IN')
                ->orderBy('clocking_time', 'desc')
                ->first();
            $out = ClockingRecord::where('employee_id', $emp_record->id)
                ->where('clocking_type', 'OUT')
                ->orderBy('clocking_time', 'desc')
                ->first();
            $attendanceStatus[$emp_record->id] = [
                'name' => $emp_record->name,
                'date_in' => $in ? $in->clocking_time->toDateString() : null,
                'time_in' => $in ? $in->clocking_time->toTimeString() : null,
                'date_out' => $out ? $out->clocking_time->toDateString() : null,
                'time_out' => $out ? $out->clocking_time->toTimeString() : null,
                'status' => $out && $out->clocking_time > ($in ? $in->clocking_time : now()->subYears(100)) ? 'OUT' : ($in ? 'IN' : 'N/A'),
            ];
        }

        // --- New Timesheet Data ---
        $currentTimesheet = null;
        if ($employee) {
            $currentPeriodStart = Carbon::now()->startOfWeek();
            // Assuming timesheets are created for the week, we look for one starting this week.
            $currentTimesheet = Timesheet::where('employee_id', $employee->id)
                                         ->where('start_date', $currentPeriodStart->toDateString())
                                         ->first();
        }

        $timesheetsPendingApproval = Timesheet::with('employee')
                                           ->where('status', 'pending') // Assuming 'pending' means needs manager approval
                                           ->orderBy('start_date', 'desc')
                                           ->take(5) // Limit for dashboard display
                                           ->get();
        // --- End New Timesheet Data ---

        return view('hr.dashboard', compact(
            'user',
            'employee',
            'employees',
            'recentClockings',
            'recentLeaves',
            'pendingLeaves',
            'attendanceStatus',
            'currentTimesheet',
            'timesheetsPendingApproval'
        ));
    }

    // Clock in
    public function clockIn(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        if (!$employee) {
            return response()->json(['status' => 'error', 'message' => 'Employee not found'], 404);
        }
        $now = Carbon::now();
        $record = ClockingRecord::create([
            'employee_id' => $employee->id,
            'clock_in' => $now,
        ]);
        return response()->json(['status' => 'success', 'record' => $record]);
    }

    // Clock out
    public function clockOut(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        if (!$employee) {
            return response()->json(['status' => 'error', 'message' => 'Employee not found'], 404);
        }
        $record = ClockingRecord::where('employee_id', $employee->id)->whereNull('clock_out')->latest()->first();
        if (!$record) {
            return response()->json(['status' => 'error', 'message' => 'No active clock-in found'], 404);
        }
        $record->clock_out = Carbon::now();
        $record->save();
        return response()->json(['status' => 'success', 'record' => $record]);
    }

    // Request leave
    public function requestLeave(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);
        $leave = LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);
        return response()->json(['status' => 'success', 'leave' => $leave]);
    }

    // Approve leave
    public function approveLeave($id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->status = 'approved';
        $leave->approved_by = Auth::user()->id;
        $leave->approved_at = now();
        $leave->save();
        return response()->json(['status' => 'success', 'leave' => $leave]);
    }

    // Reject leave
    public function rejectLeave($id, Request $request)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->status = 'rejected';
        $leave->rejection_reason = $request->input('rejection_reason', '');
        $leave->save();
        return response()->json(['status' => 'success', 'leave' => $leave]);
    }

    // API endpoint for real-time dashboard attendance polling
    public function attendanceStatusJson()
    {
        $employees = Employee::with('department', 'position')->get();
        $attendanceStatus = [];
        foreach ($employees as $emp_record) {
            $in = ClockingRecord::where('employee_id', $emp_record->id)
                ->where('clocking_type', 'IN')
                ->orderBy('clocking_time', 'desc')
                ->first();
            $out = ClockingRecord::where('employee_id', $emp_record->id)
                ->where('clocking_type', 'OUT')
                ->orderBy('clocking_time', 'desc')
                ->first();
            $attendanceStatus[] = [
                'name' => $emp_record->name,
                'date_in' => $in ? $in->clocking_time->toDateString() : null,
                'time_in' => $in ? $in->clocking_time->toTimeString() : null,
                'date_out' => $out ? $out->clocking_time->toDateString() : null,
                'time_out' => $out ? $out->clocking_time->toTimeString() : null,
                'status' => $out && $out->clocking_time > ($in ? $in->clocking_time : now()->subYears(100)) ? 'OUT' : ($in ? 'IN' : 'N/A'),
            ];
        }
        return response()->json($attendanceStatus);
    }
}
