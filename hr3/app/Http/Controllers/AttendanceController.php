<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // GET /api/attendance
    public function index(Request $request)
    {
        $query = AttendanceRecord::query();
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->has('from')) {
            $query->where('attendance_date', '>=', $request->from);
        }
        if ($request->has('to')) {
            $query->where('attendance_date', '<=', $request->to);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        $records = $query->orderBy('attendance_date', 'desc')->paginate(50);
        return response()->json([
            'status' => 'success',
            'data' => $records
        ]);
    }

    // POST /api/attendance
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:PRESENT,ABSENT,LATE,ON_LEAVE,HOLIDAY',
            'hours_worked' => 'nullable|numeric',
            'late_minutes' => 'nullable|integer',
            'shift_id' => 'nullable|exists:shifts,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }
        $data = $validator->validated();
        $data['id'] = Str::uuid();
        $record = AttendanceRecord::create($data);
        return response()->json([
            'status' => 'success',
            'message' => Lang::get('Attendance record created.'),
            'data' => $record
        ]);
    }

    // GET /api/attendance/{id}
    public function show($id)
    {
        $record = AttendanceRecord::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $record
        ]);
    }

    // PUT /api/attendance/{id}
    public function update(Request $request, $id)
    {
        $record = AttendanceRecord::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:PRESENT,ABSENT,LATE,ON_LEAVE,HOLIDAY',
            'hours_worked' => 'nullable|numeric',
            'late_minutes' => 'nullable|integer',
            'shift_id' => 'nullable|exists:shifts,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }
        $data = $validator->validated();
        $record->update($data);
        return response()->json([
            'status' => 'success',
            'message' => Lang::get('Attendance record updated.'),
            'data' => $record
        ]);
    }

    // DELETE /api/attendance/{id}
    public function destroy($id)
    {
        $record = AttendanceRecord::findOrFail($id);
        $record->delete();
        return response()->json([
            'status' => 'success',
            'message' => Lang::get('Attendance record deleted.')
        ]);
    }
}
