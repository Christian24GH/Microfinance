<?php

use App\Http\Controllers\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TimesheetController;
use App\Http\Controllers\ClockingController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\OvertimePolicyController;
use App\Http\Controllers\OvertimeRecordController;
use App\Http\Controllers\ClaimTypeController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ClaimAttachmentController;
use App\Http\Controllers\ReimbursementController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LeaveRequestController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user());
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::get('/employees/{employee}/time-entries', [EmployeeController::class, 'timeEntries']);
    Route::get('/employees/{id}', [EmployeeController::class, 'show']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
    Route::get('/employees/export', [EmployeeController::class, 'export']);
    Route::get('/employees/{employeeId}/time-entries', [EmployeeController::class, 'timeEntries']);
});

Route::get('/fetch-token', function (\Illuminate\Http\Request $request) {
    $sid = $request->query('sid');
    $token = Cache::get("session:$sid");

    if (!$token) {
        return response()->json(['error' => 'Invalid session ID'], 401);
    }

    return response()->json(['token' => $token]);
});

Route::middleware('auth:sanctum')->post('/logout', [Login::class, 'logout'])->name("login.out");

// Timesheet Approval Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/timesheets/pending', [TimesheetController::class, 'getPendingTimesheets']);
    Route::get('/timesheets/stats', [TimesheetController::class, 'getTimesheetStats']);
    Route::get('/timesheets/{id}', [TimesheetController::class, 'getTimesheet']);
    Route::post('/timesheets/{id}/approve', [TimesheetController::class, 'approveTimesheet']);
    Route::post('/timesheets/{id}/reject', [TimesheetController::class, 'rejectTimesheet']);
});

// Clocking Management API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/clocking', [ClockingController::class, 'index']);
    Route::post('/clocking', [ClockingController::class, 'store']);
    Route::get('/clocking/{id}', [ClockingController::class, 'show']);
    Route::put('/clocking/{id}', [ClockingController::class, 'update']);
    Route::delete('/clocking/{id}', [ClockingController::class, 'destroy']);
});

// Attendance Tracking API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index']);
    Route::post('/attendance', [AttendanceController::class, 'store']);
    Route::get('/attendance/{id}', [AttendanceController::class, 'show']);
    Route::put('/attendance/{id}', [AttendanceController::class, 'update']);
    Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy']);
});

// Overtime Policy Routes
Route::prefix('overtime-policies')->group(function () {
    Route::get('/', [OvertimePolicyController::class, 'index']);
    Route::post('/', [OvertimePolicyController::class, 'store']);
    Route::get('/{uuid}', [OvertimePolicyController::class, 'show']);
    Route::put('/{uuid}', [OvertimePolicyController::class, 'update']);
    Route::delete('/{uuid}', [OvertimePolicyController::class, 'destroy']);
    Route::patch('/{uuid}/toggle-status', [OvertimePolicyController::class, 'toggleStatus']);
});

// Overtime Record Routes
Route::prefix('overtime-records')->group(function () {
    Route::get('/', [OvertimeRecordController::class, 'index']);
    Route::post('/', [OvertimeRecordController::class, 'store']);
    Route::get('/{uuid}', [OvertimeRecordController::class, 'show']);
    Route::put('/{uuid}', [OvertimeRecordController::class, 'update']);
    Route::delete('/{uuid}', [OvertimeRecordController::class, 'destroy']);
    Route::patch('/{uuid}/approve', [OvertimeRecordController::class, 'approve']);
    Route::patch('/{uuid}/reject', [OvertimeRecordController::class, 'reject']);
});

// Claim Types
Route::apiResource('claim-types', ClaimTypeController::class);

// Claims
Route::get('claims', [ClaimController::class, 'index']);
Route::post('claims', [ClaimController::class, 'store']);
Route::get('claims/{id}', [ClaimController::class, 'show']);
Route::put('claims/{id}', [ClaimController::class, 'update']);
Route::delete('claims/{id}', [ClaimController::class, 'destroy']);
Route::post('claims/{id}/approve', [ClaimController::class, 'approve']);
Route::post('claims/{id}/reject', [ClaimController::class, 'reject']);
Route::post('claims/{id}/pay', [ClaimController::class, 'pay']);
Route::post('claims/{id}/reimburse', [ClaimController::class, 'reimburseClaim']);

// Claim Attachments
Route::get('claims/{claimId}/attachments', [ClaimAttachmentController::class, 'index']);
Route::post('claims/{claimId}/attachments', [ClaimAttachmentController::class, 'store']);
Route::delete('claim-attachments/{id}', [ClaimAttachmentController::class, 'destroy']);

// Reimbursements
Route::get('reimbursements', [ReimbursementController::class, 'index']);
Route::get('reimbursements/{id}', [ReimbursementController::class, 'show']);
Route::put('reimbursements/{id}', [ReimbursementController::class, 'update']);

// Claims Management Routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Claims
    Route::get('/claims/pending', [ClaimController::class, 'getPendingClaims']);
    Route::get('/claims/stats', [ClaimController::class, 'getClaimStats']);
    Route::get('/claims/{id}', [ClaimController::class, 'getClaim']);
    Route::post('/claims/{id}/approve', [ClaimController::class, 'approveClaim']);
    Route::post('/claims/{id}/reject', [ClaimController::class, 'rejectClaim']);
    Route::get('/claims/report', [ClaimController::class, 'getReport']);
    Route::get('/claims/export', [ClaimController::class, 'exportReport']);

    // Payments
    Route::get('/payments/pending', [PaymentController::class, 'getPendingPayments']);
    Route::get('/payments/stats', [PaymentController::class, 'getPaymentStats']);
    Route::get('/payments/{id}', [PaymentController::class, 'getPayment']);
    Route::post('/payments/{id}/process', [PaymentController::class, 'processPayment']);
});

// Leave Management API
Route::middleware(['auth:sanctum'])->group(function () {
    // Leave Types
    Route::get('/leave-types', [\App\Http\Controllers\Api\LeaveTypeController::class, 'index']);
    Route::post('/leave-types', [\App\Http\Controllers\Api\LeaveTypeController::class, 'store']);
    Route::get('/leave-types/{id}', [\App\Http\Controllers\Api\LeaveTypeController::class, 'show']);
    Route::put('/leave-types/{id}', [\App\Http\Controllers\Api\LeaveTypeController::class, 'update']);
    Route::delete('/leave-types/{id}', [\App\Http\Controllers\Api\LeaveTypeController::class, 'destroy']);

    // Leave Requests
    Route::get('/leave-requests', [LeaveRequestController::class, 'index']);
    Route::post('/leave-requests', [LeaveRequestController::class, 'store']);
    Route::get('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'show']);
    Route::post('/leave-requests/{leaveRequest}/cancel', [LeaveRequestController::class, 'cancel']);
    Route::post('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve']);
    Route::post('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject']);

    // Leave Approvals
    Route::get('/leave-approvals', [\App\Http\Controllers\Api\LeaveApprovalController::class, 'index']);
    Route::get('/leave-approvals/{id}', [\App\Http\Controllers\Api\LeaveApprovalController::class, 'show']);
    Route::put('/leave-approvals/{id}', [\App\Http\Controllers\Api\LeaveApprovalController::class, 'update']);
});

// Workflow Management API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/workflows', [\App\Http\Controllers\WorkflowController::class, 'index']);
    Route::post('/workflows', [\App\Http\Controllers\WorkflowController::class, 'store']);
    Route::put('/workflows/{id}', [\App\Http\Controllers\WorkflowController::class, 'update']);
    Route::delete('/workflows/{id}', [\App\Http\Controllers\WorkflowController::class, 'destroy']);
});
