<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Claim;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\ClaimType;

class ClaimController extends Controller
{
    public function getOrCreateEmployeeForUser($user)
    {
        $employee = Employee::where('email', $user->email)->first();
        if (!$employee) {
            $employee = Employee::create([
                'employee_id' => 'EMP' . str_pad(Employee::max('id') + 1, 3, '0', STR_PAD_LEFT),
                'name' => $user->name,
                'email' => $user->email,
                'phone' => null,
                'department_id' => 1, // Default department, adjust as needed
                'position_id' => 1,   // Default position, adjust as needed
                'status' => 'active',
            ]);
        }
        return $employee;
    }

    public function submission()
    {
        $employees = \App\Models\Employee::whereHas('timesheets')->get();
        $claims = Claim::with('employee')->orderBy('created_at', 'desc')->get();
        $claimTypes = ClaimType::where('is_active', true)->get();
        return view('testapp.claims.submission', compact('claims', 'claimTypes', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'claim_type_id' => 'required|integer|exists:claim_types,id',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|max:10',
            'description' => 'nullable|string',
        ]);
        $claimType = ClaimType::find($request->claim_type_id);
        $claim = Claim::create([
            'employee_id' => $request->employee_id,
            'claim_type_id' => $request->claim_type_id,
            'claim_type' => $claimType ? $claimType->name : null,
            'total_amount' => $request->amount,
            'status' => 'pending',
            'submitted_at' => now(),
            'notes' => $request->description,
        ]);
        return redirect()->back()->with('success', 'Claim submitted successfully!');
    }

    public function edit(Claim $claim)
    {
        $this->authorize('update', $claim);
        return view('testapp.claims.edit', compact('claim'));
    }

    public function update(Request $request, Claim $claim)
    {
        $this->authorize('update', $claim);
        $request->validate([
            'claim_type_id' => 'required|integer',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|max:10',
            'description' => 'nullable|string',
        ]);
        $claim->update($request->only(['claim_type_id', 'amount', 'currency', 'description']));
        return redirect()->route('claims.claim_submission')->with('success', 'Claim updated!');
    }

    public function destroy(Claim $claim)
    {
        $this->authorize('delete', $claim);
        $claim->delete();
        return redirect()->route('claims.claim_submission')->with('success', 'Claim deleted!');
    }

    public function approval()
    {
        return view('testapp.claims.approval');
    }

    public function reporting()
    {
        return view('testapp.claims.reporting');
    }

    public function approve(Claim $claim)
    {
        if ($claim->status !== 'pending') {
            return back()->with('error', 'Only pending claims can be approved.');
        }
        $claim->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);
        return back()->with('success', 'Claim approved!');
    }

    public function reject(Request $request, Claim $claim)
    {
        $request->validate(['rejection_reason' => 'required|string']);
        if ($claim->status !== 'pending') {
            return back()->with('error', 'Only pending claims can be rejected.');
        }
        $claim->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
            'rejection_reason' => $request->rejection_reason,
        ]);
        return back()->with('success', 'Claim rejected!');
    }

    public function reimburse(Claim $claim)
    {
        if ($claim->status !== 'approved') {
            return back()->with('error', 'Only approved claims can be reimbursed.');
        }
        $claim->update([
            'status' => 'reimbursed',
            'reimbursed_at' => now(),
        ]);
        return back()->with('success', 'Claim reimbursed!');
    }

    public function json($id)
    {
        $claim = \App\Models\Claim::with(['attachments', 'employee'])->find($id);
        if (!$claim) {
            return response()->json(['status' => 'error', 'message' => 'Claim not found'], 404);
        }
        return response()->json([
            'status' => 'success',
            'claim' => [
                'claim_type' => $claim->claim_type,
                'total_amount' => $claim->total_amount,
                'status' => $claim->status,
                'submitted_at' => $claim->submitted_at ? $claim->submitted_at->toDateString() : null,
                'description' => $claim->description,
                'employee_name' => $claim->employee ? $claim->employee->name : null,
                'employee_id' => $claim->employee ? $claim->employee->employee_id : null,
                'attachments' => $claim->attachments->map(function($a) {
                    return [
                        'filename' => $a->filename ?? basename($a->file_path),
                        'url' => $a->file_path ? asset('storage/'.$a->file_path) : '',
                    ];
                }),
            ]
        ]);
    }
}
