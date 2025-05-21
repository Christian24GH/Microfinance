<?php

namespace App\Http\Controllers;

use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReimbursementController extends Controller
{
    public function index()
    {
        $reimbursements = Reimbursement::with('claim')->orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'success', 'reimbursements' => $reimbursements]);
    }

    public function show($id)
    {
        $reimbursement = Reimbursement::with('claim')->findOrFail($id);
        return response()->json(['status' => 'success', 'reimbursement' => $reimbursement]);
    }

    public function update(Request $request, $id)
    {
        $reimbursement = Reimbursement::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,paid,failed',
            'notes' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }
        $reimbursement->update($request->only(['status', 'notes']));
        return response()->json(['status' => 'success', 'reimbursement' => $reimbursement]);
    }
}
