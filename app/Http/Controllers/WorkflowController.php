<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use App\Models\WorkflowStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkflowController extends Controller
{
    public function index()
    {
        try {
            $workflows = Workflow::with(['stages' => function($query) {
                $query->orderBy('stage_order', 'asc');
            }])->get();

            return response()->json([
                'status' => 'success',
                'workflows' => $workflows
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load workflows: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|in:timesheet,leave,claim',
                'description' => 'nullable|string',
                'is_active' => 'boolean'
            ]);

            $workflow = Workflow::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Workflow created successfully',
                'workflow' => $workflow
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create workflow: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|in:timesheet,leave,claim',
                'description' => 'nullable|string',
                'is_active' => 'boolean'
            ]);

            $workflow = Workflow::findOrFail($id);
            $workflow->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Workflow updated successfully',
                'workflow' => $workflow
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update workflow: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $workflow = Workflow::findOrFail($id);
            $workflow->stages()->delete();
            $workflow->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Workflow deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete workflow: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeStage(Request $request)
    {
        try {
            $validated = $request->validate([
                'workflow_id' => 'required|exists:workflows,id',
                'name' => 'required|string|max:255',
                'stage_order' => 'required|integer|min:1',
                'approver_type' => 'required|string|in:role,employee,department',
                'approver_id' => 'required|integer',
                'description' => 'nullable|string',
                'is_final' => 'boolean'
            ]);

            $stage = WorkflowStage::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Stage created successfully',
                'stage' => $stage
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create stage: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStage(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'workflow_id' => 'required|exists:workflows,id',
                'name' => 'required|string|max:255',
                'stage_order' => 'required|integer|min:1',
                'approver_type' => 'required|string|in:role,employee,department',
                'approver_id' => 'required|integer',
                'description' => 'nullable|string',
                'is_final' => 'boolean'
            ]);

            $stage = WorkflowStage::findOrFail($id);
            $stage->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Stage updated successfully',
                'stage' => $stage
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update stage: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyStage($id)
    {
        try {
            $stage = WorkflowStage::findOrFail($id);
            $stage->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Stage deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete stage: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getApprovers($type)
    {
        try {
            $approvers = [];

            switch ($type) {
                case 'role':
                    $approvers = \App\Models\Role::select('id', 'name')->get();
                    break;
                case 'employee':
                    $approvers = \App\Models\Employee::select('id', 'name')->get();
                    break;
                case 'department':
                    $approvers = \App\Models\Department::select('id', 'name')->get();
                    break;
            }

            return response()->json([
                'status' => 'success',
                'approvers' => $approvers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load approvers: ' . $e->getMessage()
            ], 500);
        }
    }
}
