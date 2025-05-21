<?php

namespace App\Http\Controllers;

use App\Models\OvertimePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OvertimePolicyController extends Controller
{
    public function index()
    {
        try {
            $policies = OvertimePolicy::all();
            return response()->json([
                'status' => 'success',
                'policies' => $policies
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch overtime policies: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rate_multiplier' => 'required|numeric|min:1',
            'minimum_hours' => 'required|numeric|min:0',
            'maximum_hours' => 'required|numeric|min:0|gt:minimum_hours',
            'is_active' => 'boolean',
            'eligibility_criteria' => 'nullable|array',
            'approval_required' => 'boolean',
            'payment_frequency' => 'required|in:weekly,biweekly,monthly'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $policy = OvertimePolicy::create([
                'uuid' => Str::uuid(),
                'name' => $request->name,
                'description' => $request->description,
                'rate_multiplier' => $request->rate_multiplier,
                'minimum_hours' => $request->minimum_hours,
                'maximum_hours' => $request->maximum_hours,
                'is_active' => $request->is_active ?? true,
                'eligibility_criteria' => $request->eligibility_criteria,
                'approval_required' => $request->approval_required ?? true,
                'payment_frequency' => $request->payment_frequency
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Overtime policy created successfully',
                'policy' => $policy
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create overtime policy: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($uuid)
    {
        try {
            $policy = OvertimePolicy::where('uuid', $uuid)->firstOrFail();
            return response()->json([
                'status' => 'success',
                'policy' => $policy
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Overtime policy not found'
            ], 404);
        }
    }

    public function update(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rate_multiplier' => 'required|numeric|min:1',
            'minimum_hours' => 'required|numeric|min:0',
            'maximum_hours' => 'required|numeric|min:0|gt:minimum_hours',
            'is_active' => 'boolean',
            'eligibility_criteria' => 'nullable|array',
            'approval_required' => 'boolean',
            'payment_frequency' => 'required|in:weekly,biweekly,monthly'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $policy = OvertimePolicy::where('uuid', $uuid)->firstOrFail();
            $policy->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Overtime policy updated successfully',
                'policy' => $policy
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update overtime policy: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($uuid)
    {
        try {
            $policy = OvertimePolicy::where('uuid', $uuid)->firstOrFail();
            $policy->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Overtime policy deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete overtime policy: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus($uuid)
    {
        try {
            $policy = OvertimePolicy::where('uuid', $uuid)->firstOrFail();
            $policy->is_active = !$policy->is_active;
            $policy->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Overtime policy status updated successfully',
                'policy' => $policy
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update overtime policy status: ' . $e->getMessage()
            ], 500);
        }
    }
}
