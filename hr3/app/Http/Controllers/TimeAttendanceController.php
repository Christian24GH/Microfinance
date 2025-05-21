<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClockingRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TimeAttendanceController extends Controller
{
    public function clocking()
    {
        $employees = \App\Models\Employee::where('status', 'active')->get();
        return view('testapp.timeattendance.clocking', compact('employees'));
    }

    // Stubs for future modules
    public function attendance()
    {
        return view('testapp.timeattendance.attendance');
    }

    public function overtime()
    {
        return view('testapp.timeattendance.overtime');
    }

    public function storeClocking(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'clocking_type' => 'required|in:IN,OUT,BREAK_START,BREAK_END',
            'clocking_time' => 'required|date',
        ]);
        $employee = \App\Models\Employee::where('id', $validated['employee_id'])->where('status', 'active')->first();
        if (!$employee) {
            return response()->json(['status' => 'error', 'message' => 'Employee not found or inactive'], 404);
        }
        $record = ClockingRecord::create([
            'id' => Str::uuid(),
            'employee_id' => $employee->id,
            'clocking_type' => $validated['clocking_type'],
            'clocking_time' => Carbon::parse($validated['clocking_time']),
            'device_id' => $request->input('device_id'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'timezone' => $request->input('timezone', 'UTC'),
        ]);
        // Trigger dashboard update event for real-time dashboard
        $stats = [
            'total_employees' => \App\Models\Employee::count(),
            'active_today' => \App\Models\Employee::whereHas('timeEntries', function($q) {
                $q->whereDate('date', now()->toDateString());
            })->count(),
            'total_hours_today' => \App\Models\TimeEntry::whereDate('date', now()->toDateString())->sum('total_hours'),
            'pending_approvals' => \App\Models\Timesheet::where('status', 'pending')->count(),
        ];
        event(new \App\Events\DashboardStatsUpdated($stats));
        return response()->json(['status' => 'success', 'message' => 'Clocking recorded successfully.', 'data' => $record]);
    }

    public function getClockingRecords(Request $request)
    {
        $records = \App\Models\ClockingRecord::with('employee')->orderBy('clocking_time', 'desc')->limit(20)->get();
        return response()->json(['status' => 'success', 'data' => $records]);
    }
}
