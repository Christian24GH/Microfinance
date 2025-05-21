<?php

namespace App\Http\Controllers;

use App\Models\TimeEntry;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Events\TimeEntryCreated;
use App\Events\TimeEntryUpdated;
use App\Events\TimeEntryDeleted;
use App\Events\DashboardStatsUpdated;

class TimeEntryController extends Controller
{
    public function index($employeeId)
    {
        try {
            $employee = Employee::findOrFail($employeeId);
            $timeEntries = $employee->timeEntries()
                ->orderBy('date', 'desc')
                ->orderBy('time_in', 'desc')
                ->get()
                ->map(function ($entry) {
                    return [
                        'id' => $entry->id,
                        'date' => $entry->formatted_date,
                        'time_in' => $entry->formatted_time_in,
                        'time_out' => $entry->formatted_time_out,
                        'total_hours' => $entry->total_hours,
                        'status' => $entry->status,
                        'notes' => $entry->notes
                    ];
                });

            return response()->json([
                'status' => 'success',
                'time_entries' => $timeEntries
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch time entries: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request, $employeeId)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time_in' => 'required|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i|after:time_in',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $employee = Employee::findOrFail($employeeId);

            // Check for duplicate entry
            $existingEntry = TimeEntry::where('employee_id', $employeeId)
                ->where('date', $request->date)
                ->where('time_in', $request->date . ' ' . $request->time_in)
                ->first();

            if ($existingEntry) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'A time entry already exists for this date and time'
                ], 422);
            }

            // Calculate total hours if time_out is provided
            $totalHours = 0;
            if ($request->time_out) {
                $timeIn = Carbon::parse($request->date . ' ' . $request->time_in);
                $timeOut = Carbon::parse($request->date . ' ' . $request->time_out);
                $totalHours = round($timeOut->diffInSeconds($timeIn) / 3600, 2);
            }

            // Create time entry
            $timeEntry = new TimeEntry([
                'employee_id' => $employeeId,
                'date' => $request->date,
                'time_in' => $request->date . ' ' . $request->time_in,
                'time_out' => $request->time_out ? $request->date . ' ' . $request->time_out : null,
                'total_hours' => $totalHours,
                'status' => 'pending',
                'notes' => $request->notes
            ]);

            $employee->timeEntries()->save($timeEntry);

            // Broadcast the event
            broadcast(new TimeEntryCreated($timeEntry))->toOthers();

            // Broadcast dashboard stats update
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
                'message' => 'Time entry added successfully',
                'time_entry' => [
                    'id' => $timeEntry->id,
                    'date' => $timeEntry->formatted_date,
                    'time_in' => $timeEntry->formatted_time_in,
                    'time_out' => $timeEntry->formatted_time_out,
                    'total_hours' => $timeEntry->total_hours,
                    'status' => $timeEntry->status,
                    'notes' => $timeEntry->notes
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add time entry: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($employeeId, $timeEntryId)
    {
        try {
            $timeEntry = TimeEntry::where('employee_id', $employeeId)
                ->where('id', $timeEntryId)
                ->firstOrFail();

            return response()->json([
                'status' => 'success',
                'time_entry' => [
                    'id' => $timeEntry->id,
                    'date' => $timeEntry->date->format('Y-m-d'),
                    'time_in' => $timeEntry->time_in_time,
                    'time_out' => $timeEntry->time_out_time,
                    'total_hours' => $timeEntry->total_hours,
                    'status' => $timeEntry->status,
                    'notes' => $timeEntry->notes
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch time entry: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $employeeId, $timeEntryId)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time_in' => 'required|date_format:H:i',
            'time_out' => 'nullable|date_format:H:i|after:time_in',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $timeEntry = TimeEntry::where('employee_id', $employeeId)
                ->where('id', $timeEntryId)
                ->firstOrFail();

            // Check for duplicate entry (excluding current entry)
            $existingEntry = TimeEntry::where('employee_id', $employeeId)
                ->where('date', $request->date)
                ->where('time_in', $request->date . ' ' . $request->time_in)
                ->where('id', '!=', $timeEntryId)
                ->first();

            if ($existingEntry) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'A time entry already exists for this date and time'
                ], 422);
            }

            // Calculate total hours if time_out is provided
            $totalHours = null;
            if ($request->time_out) {
                $timeIn = Carbon::parse($request->date . ' ' . $request->time_in);
                $timeOut = Carbon::parse($request->date . ' ' . $request->time_out);
                $totalHours = round($timeOut->diffInSeconds($timeIn) / 3600, 2);
            }

            $timeEntry->update([
                'date' => $request->date,
                'time_in' => $request->date . ' ' . $request->time_in,
                'time_out' => $request->time_out ? $request->date . ' ' . $request->time_out : null,
                'total_hours' => $totalHours,
                'notes' => $request->notes
            ]);

            // Broadcast the event
            broadcast(new TimeEntryUpdated($timeEntry))->toOthers();

            // Broadcast dashboard stats update
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
                'message' => 'Time entry updated successfully',
                'time_entry' => [
                    'id' => $timeEntry->id,
                    'date' => $timeEntry->formatted_date,
                    'time_in' => $timeEntry->formatted_time_in,
                    'time_out' => $timeEntry->formatted_time_out,
                    'total_hours' => $timeEntry->total_hours,
                    'status' => $timeEntry->status,
                    'notes' => $timeEntry->notes
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update time entry: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($employeeId, $timeEntryId)
    {
        try {
            $timeEntry = TimeEntry::where('employee_id', $employeeId)
                ->where('id', $timeEntryId)
                ->firstOrFail();

            $timeEntry->delete();

            // Broadcast the event
            broadcast(new TimeEntryDeleted($timeEntry))->toOthers();

            // Broadcast dashboard stats update
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
                'message' => 'Time entry deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete time entry: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStats()
    {
        try {
            $today = now()->format('Y-m-d');

            $activeEmployees = TimeEntry::where('date', $today)
                ->distinct('employee_id')
                ->count('employee_id');

            $totalHoursToday = TimeEntry::where('date', $today)
                ->whereNotNull('total_hours')
                ->sum('total_hours');

            // Count all timesheets with status 'pending'
            $pendingApprovals = \App\Models\Timesheet::where('status', 'pending')->count();

            return response()->json([
                'status' => 'success',
                'active_employees' => $activeEmployees,
                'total_hours_today' => number_format($totalHoursToday, 2),
                'pending_approvals' => $pendingApprovals
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get stats: ' . $e->getMessage()
            ], 500);
        }
    }
}
