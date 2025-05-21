<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Employee;
use App\Models\Schedule;
use App\Events\ShiftUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        $activeEmployees = Employee::where('status', 'active')->count();
        $todaySchedules = Schedule::whereDate('date', Carbon::today())->count();
        $pendingAssignments = Schedule::where('status', 'pending')->count();

        return view('testapp.shift.index', compact(
            'shifts',
            'activeEmployees',
            'todaySchedules',
            'pendingAssignments'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $shift = Shift::create($request->all());
        event(new ShiftUpdated($shift));

        return response()->json([
            'message' => 'Shift created successfully',
            'shift' => $shift
        ]);
    }

    public function edit($id)
    {
        $shift = Shift::findOrFail($id);
        return response()->json($shift);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $shift = Shift::findOrFail($id);
        $shift->update($request->all());
        event(new ShiftUpdated($shift));

        return response()->json([
            'message' => 'Shift updated successfully',
            'shift' => $shift
        ]);
    }

    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);
        $shift->delete();
        event(new ShiftUpdated($shift));

        return response()->json([
            'message' => 'Shift deleted successfully'
        ]);
    }

    public function getStats()
    {
        $stats = [
            'total_shifts' => Shift::count(),
            'active_employees' => Employee::where('status', 'active')->count(),
            'today_schedules' => Schedule::whereDate('date', Carbon::today())->count(),
            'pending_assignments' => Schedule::where('status', 'pending')->count()
        ];

        return response()->json($stats);
    }

    public function getEmployeeShifts($employeeId)
    {
        $schedules = Schedule::with('shift')
            ->where('employee_id', $employeeId)
            ->whereDate('date', '>=', Carbon::today())
            ->orderBy('date')
            ->get();

        return response()->json($schedules);
    }
}
