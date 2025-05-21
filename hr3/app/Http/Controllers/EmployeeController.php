<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\TimeEntry;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Events\EmployeeCreated;
use App\Events\EmployeeUpdated;
use App\Events\EmployeeDeleted;
use App\Events\DashboardStatsUpdated;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['department', 'position'])->get();
        $departments = Department::where('status', 'active')->get();
        $positions = Position::where('status', 'active')->get();

        // If the request expects JSON (API call), return a flat array
        if (request()->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'employees' => $employees
            ]);
        }

        // Otherwise, return the Blade view
        return view('testapp.timesheet.employee', compact('employees', 'departments', 'positions'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        try {
            $employee = Employee::create($request->validated());
            // Auto-create a pending timesheet for the new employee
            $startDate = now()->startOfWeek()->format('Y-m-d');
            $endDate = now()->endOfWeek()->format('Y-m-d');
            \App\Models\Timesheet::create([
                'employee_id' => $employee->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_hours' => 0,
                'status' => 'pending',
            ]);
            event(new EmployeeCreated($employee));
            $stats = [
                'total_employees' => Employee::count(),
                'active_today' => Employee::whereHas('timeEntries', function($q) {
                    $q->whereDate('date', now()->toDateString());
                })->count(),
                'total_hours_today' => \App\Models\TimeEntry::whereDate('date', now()->toDateString())->sum('total_hours'),
                'pending_approvals' => \App\Models\Timesheet::where('status', 'pending')->count(),
            ];
            event(new DashboardStatsUpdated($stats));
            return (new EmployeeResource($employee->load(['department', 'position'])))->additional([
                'status' => 'success',
                'message' => 'Employee added successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add employee: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $employee = Employee::with(['department', 'position'])->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'employee' => $employee
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found'
            ], 404);
        }
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->update($request->validated());
            event(new EmployeeUpdated($employee));
            $stats = [
                'total_employees' => Employee::count(),
                'active_today' => Employee::whereHas('timeEntries', function($q) {
                    $q->whereDate('date', now()->toDateString());
                })->count(),
                'total_hours_today' => \App\Models\TimeEntry::whereDate('date', now()->toDateString())->sum('total_hours'),
                'pending_approvals' => \App\Models\Timesheet::where('status', 'pending')->count(),
            ];
            event(new DashboardStatsUpdated($stats));
            return (new EmployeeResource($employee->load(['department', 'position'])))->additional([
                'status' => 'success',
                'message' => 'Employee updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update employee: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->forceDelete();
            event(new EmployeeDeleted($employee->id));
            $stats = [
                'total_employees' => Employee::count(),
                'active_today' => Employee::whereHas('timeEntries', function($q) {
                    $q->whereDate('date', now()->toDateString());
                })->count(),
                'total_hours_today' => \App\Models\TimeEntry::whereDate('date', now()->toDateString())->sum('total_hours'),
                'pending_approvals' => \App\Models\Timesheet::where('status', 'pending')->count(),
            ];
            event(new DashboardStatsUpdated($stats));
            return response()->json([
                'status' => 'success',
                'message' => 'Employee deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete employee: ' . $e->getMessage()
            ], 500);
        }
    }

    public function stats()
    {
        $totalEmployees = Employee::count();
        $activeToday = Employee::whereHas('timeEntries', function($q) {
            $q->whereDate('date', now()->toDateString());
        })->count();
        $totalHoursToday = \App\Models\TimeEntry::whereDate('date', now()->toDateString())->sum('total_hours');
        $pendingApprovals = \App\Models\Timesheet::where('status', 'pending')->count();
        return response()->json([
            'status' => 'success',
            'total_employees' => $totalEmployees,
            'active_today' => $activeToday,
            'total_hours_today' => $totalHoursToday,
            'pending_approvals' => $pendingApprovals
        ]);
    }
}
