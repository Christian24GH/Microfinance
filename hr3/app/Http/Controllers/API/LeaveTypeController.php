<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $types = LeaveType::where('is_active', true)->get();
        return response()->json(['status' => 'success', 'leave_types' => $types]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_days' => 'required|integer|min:0',
            'requires_approval' => 'boolean',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $type = LeaveType::create($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Leave type created successfully',
            'leave_type' => $type
        ]);
    }

    public function show($id)
    {
        $type = LeaveType::findOrFail($id);
        return response()->json(['status' => 'success', 'leave_type' => $type]);
    }

    public function update(Request $request, $id)
    {
        $type = LeaveType::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_days' => 'required|integer|min:0',
            'requires_approval' => 'boolean',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $type->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Leave type updated successfully',
            'leave_type' => $type
        ]);
    }

    public function destroy($id)
    {
        $type = LeaveType::findOrFail($id);

        // Check if there are any active leave requests for this type
        if ($type->leaveRequests()->where('status', 'pending')->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete leave type with pending requests'
            ], 422);
        }

        $type->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Leave type deleted successfully'
        ]);
    }
}
