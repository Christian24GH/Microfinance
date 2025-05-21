<?php

namespace App\Http\Controllers;

use App\Models\ClockingRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;

class ClockingController extends Controller
{
    // POST /api/clocking
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'clocking_time' => 'required|date',
            'clocking_type' => 'required|in:IN,OUT,BREAK_START,BREAK_END',
            'device_id' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'timezone' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }
        $data = $validator->validated();
        $data['id'] = Str::uuid();
        $data['timezone'] = $data['timezone'] ?? 'UTC';
        $data['clocking_time'] = Carbon::parse($data['clocking_time'], $data['timezone'])->setTimezone('UTC');
        $record = ClockingRecord::create($data);
        return response()->json([
            'status' => 'success',
            'message' => Lang::get('Clocking recorded successfully.'),
            'data' => $record
        ]);
    }

    // GET /api/clocking
    public function index(Request $request)
    {
        $query = ClockingRecord::query();
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->has('from')) {
            $query->where('clocking_time', '>=', Carbon::parse($request->from));
        }
        if ($request->has('to')) {
            $query->where('clocking_time', '<=', Carbon::parse($request->to));
        }
        $records = $query->orderBy('clocking_time', 'desc')->paginate(50);
        return response()->json([
            'status' => 'success',
            'data' => $records
        ]);
    }

    // GET /api/clocking/{id}
    public function show($id)
    {
        $record = ClockingRecord::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $record
        ]);
    }

    // PUT /api/clocking/{id}
    public function update(Request $request, $id)
    {
        $record = ClockingRecord::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'clocking_time' => 'sometimes|date',
            'clocking_type' => 'sometimes|in:IN,OUT,BREAK_START,BREAK_END',
            'device_id' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'timezone' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }
        $data = $validator->validated();
        if (isset($data['clocking_time']) && isset($data['timezone'])) {
            $data['clocking_time'] = Carbon::parse($data['clocking_time'], $data['timezone'])->setTimezone('UTC');
        }
        $record->update($data);
        return response()->json([
            'status' => 'success',
            'message' => Lang::get('Clocking record updated.'),
            'data' => $record
        ]);
    }

    // DELETE /api/clocking/{id}
    public function destroy($id)
    {
        $record = ClockingRecord::findOrFail($id);
        $record->delete();
        return response()->json([
            'status' => 'success',
            'message' => Lang::get('Clocking record deleted.')
        ]);
    }
}
