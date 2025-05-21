<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Events\EmployeeCreated;
use App\Events\EmployeeUpdated;
use App\Events\EmployeeDeleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'position']);

        // Apply filters
        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Apply search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortField = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        // If paginated (for admin panel), return paginated
        if ($request->has('page')) {
            $employees = $query->paginate(10);
            return response()->json([
                'status' => 'success',
                'employees' => $employees
            ]);
        }
        // Otherwise, return flat array
        $employees = $query->get();
        return response()->json([
            'status' => 'success',
            'employees' => $employees
        ]);
    }

    public function show($id)
    {
        $employee = Employee::with(['department', 'position'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'employee' => $employee
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|unique:employees',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone' => 'required|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $employee = Employee::create($request->all());

            // Broadcast the event
            event(new EmployeeCreated($employee));

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Employee created successfully',
                'employee' => $employee
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|unique:employees,employee_id,' . $id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $id,
            'phone' => 'required|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $employee->update($request->all());

            // Broadcast the event
            event(new EmployeeUpdated($employee));

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Employee updated successfully',
                'employee' => $employee
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        DB::beginTransaction();
        try {
            // Check for dependencies
            if ($employee->timeEntries()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete employee with existing time entries'
                ], 422);
            }

            $employeeId = $employee->id;
            $employee->forceDelete();

            // Broadcast the event
            event(new EmployeeDeleted($employeeId));

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Employee deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function export()
    {
        $employees = Employee::with(['department', 'position'])->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="employees.csv"',
        ];

        $callback = function() use ($employees) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['Employee ID', 'Name', 'Email', 'Phone', 'Department', 'Position', 'Status']);

            // Add data
            foreach ($employees as $employee) {
                fputcsv($file, [
                    $employee->employee_id,
                    $employee->name,
                    $employee->email,
                    $employee->phone,
                    $employee->department->name ?? 'N/A',
                    $employee->position->name ?? 'N/A',
                    $employee->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

