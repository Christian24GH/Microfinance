<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\TimeAttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\TimeEntryController;
use App\Http\Controllers\OvertimePolicyController;
use App\Http\Controllers\OvertimeRecordController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TimesheetController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\HRController;
use App\Http\Controllers\ClaimTypeController;

// Pang landing page
Route::get('/', [PagesController::class, 'landing'])->name('landing');

// Pang login
Route::get('/login', [LoginController::class, 'index'])->name('login.index');
Route::post('/login', [LoginController::class, 'login'])->name(name: 'login.login');

// Pang logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Pang register
Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Pang testing at welcome
Route::get('/testdb', [PagesController::class, 'testdb'])->name('testdb');
Route::get('/welcome', [PagesController::class, 'welcome'])->name('welcome');

// Route pag successful login (dashboard/test app)
Route::get('/testapp', [HRController::class, 'dashboard'])->middleware('auth')->name('dashboard');

// Landing page route (for logout redirection)
Route::get('/landing', function () {
    return view('landing');
});

// ✅ Timesheet routes
Route::prefix('timesheet')->middleware('auth')->group(function () {
    Route::get('/employee', [EmployeeController::class, 'index'])->name('employee.index');
    Route::get('/approval', function () {
        return view('testapp.timesheet.approval');
    })->name('timesheet.approval');

    Route::get('/workflow', function () {
        return view('testapp.timesheet.workflow');
    })->name('timesheet.workflow');
});

// Time Entry Routes
Route::prefix('timesheet')->group(function () {
    Route::get('/employee/{employeeId}/entries', [TimeEntryController::class, 'index']);
    Route::post('/employee/{employeeId}/entries', [TimeEntryController::class, 'store']);
    Route::get('/employee/{employeeId}/entries/{timeEntryId}', [TimeEntryController::class, 'show']);
    Route::put('/employee/{employeeId}/entries/{timeEntryId}', [TimeEntryController::class, 'update']);
    Route::delete('/employee/{employeeId}/entries/{timeEntryId}', [TimeEntryController::class, 'destroy']);
    Route::get('/stats', [TimeEntryController::class, 'getStats']);
});

// Claims routes
Route::prefix('claims')->middleware('auth')->group(function () {
    Route::get('/claim-submission', [ClaimController::class, 'submission'])->name('claims.claim_submission');
    Route::get('/payment', [ClaimController::class, 'payment'])->name('claims.payment');
    Route::get('/approval', [ClaimController::class, 'approval'])->name('claims.approval');
    Route::get('/reporting', [ClaimController::class, 'reporting'])->name('claims.reporting');
});

// Time and Attendance
Route::middleware('auth')->group(function () {
    Route::get('/timeattendance/clocking', [TimeAttendanceController::class, 'clocking'])->name('timeattendance.clocking');
    Route::post('/timeattendance/clocking/store', [TimeAttendanceController::class, 'storeClocking'])->name('timeattendance.clocking.store');
    Route::get('/timeattendance/clocking/records', [TimeAttendanceController::class, 'getClockingRecords'])->name('timeattendance.clocking.records');
    Route::get('/timeattendance/attendance', [TimeAttendanceController::class, 'attendance'])->name('timeattendance.attendance');
    Route::get('/timeattendance/overtime', [TimeAttendanceController::class, 'overtime'])->name('timeattendance.overtime');
});

// Leave Management
Route::middleware('auth')->group(function () {
    Route::get('/leave/request', [LeaveController::class, 'index'])->name('leave.leave_request');
    Route::get('/leave/approval', [LeaveController::class, 'approval'])->name('leave.leave_approval');
    Route::get('/leave/history', [LeaveController::class, 'history'])->name('leave.leave_history');
    Route::post('/leave/request/submit', [\App\Http\Controllers\LeaveController::class, 'submit'])->middleware('auth')->name('leave.request.submit');
    Route::post('/leave/approve/{id}', [\App\Http\Controllers\LeaveController::class, 'approve'])->name('leave.approve');
    Route::post('/leave/reject/{id}', [\App\Http\Controllers\LeaveController::class, 'reject'])->name('leave.reject');
});

// Employee and Time Entry Routes
Route::middleware(['auth'])->group(function () {
    // Employee routes
    Route::get('/api/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::post('/api/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/api/employees/{id}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::put('/api/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/api/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    // Time entry routes
    Route::get('/api/employees/{employeeId}/time-entries', [TimeEntryController::class, 'index'])->name('time-entries.index');
    Route::post('/api/employees/{employeeId}/time-entries', [TimeEntryController::class, 'store'])->name('time-entries.store');
    Route::get('/api/employees/{employeeId}/time-entries/{timeEntryId}', [TimeEntryController::class, 'show'])->name('time-entries.show');
    Route::put('/api/employees/{employeeId}/time-entries/{timeEntryId}', [TimeEntryController::class, 'update'])->name('time-entries.update');
    Route::delete('/api/employees/{employeeId}/time-entries/{timeEntryId}', [TimeEntryController::class, 'destroy'])->name('time-entries.destroy');

    // Stats route
    Route::get('/api/employees/stats', [TimeEntryController::class, 'getStats'])->name('employees.stats');

    // Timesheet approval API endpoints
    Route::get('/api/timesheets/pending', [TimesheetController::class, 'getPendingTimesheets']);
    Route::get('/api/timesheets/stats', [TimesheetController::class, 'getTimesheetStats']);
    Route::get('/api/timesheets/{id}', [TimesheetController::class, 'getTimesheet']);
    Route::post('/api/timesheets/{id}/approve', [TimesheetController::class, 'approveTimesheet']);
    Route::post('/api/timesheets/{id}/reject', [TimesheetController::class, 'rejectTimesheet']);

    // Shift routes
    Route::get('/shift', [ShiftController::class, 'index'])->name('shift.index');
});

// Overtime Management Routes
Route::prefix('overtime')->group(function () {
    Route::get('/policies', [OvertimePolicyController::class, 'index'])->name('overtime.policies');
    Route::get('/records', [OvertimeRecordController::class, 'index'])->name('overtime.records');
});

// Claims and Payments Routes
Route::middleware(['auth'])->group(function () {
    // Claims
    Route::get('/claims/submission', [ClaimController::class, 'submission'])->name('claims.submission');
    Route::post('/claims/claim-submission', [ClaimController::class, 'store'])->name('claims.claim_store');
    Route::get('/claims/{claim}/edit', [ClaimController::class, 'edit'])->name('claims.edit');
    Route::put('/claims/{claim}', [ClaimController::class, 'update'])->name('claims.update');
    Route::delete('/claims/{claim}', [ClaimController::class, 'destroy'])->name('claims.destroy');
    Route::post('/claims/{claim}/approve', [ClaimController::class, 'approve'])->name('claims.approve');
    Route::post('/claims/{claim}/reject', [ClaimController::class, 'reject'])->name('claims.reject');
    Route::post('/claims/{claim}/reimburse', [ClaimController::class, 'reimburse'])->name('claims.reimburse');
    Route::get('/claims/{claim}/json', [ClaimController::class, 'json'])->name('claims.json');

    // Payments
    Route::get('/payments/processing', [PaymentController::class, 'processing'])->name('payments.processing');
});

// Shift routes
Route::middleware('auth')->group(function () {
    Route::get('/shift/schedule', function () {
        return view('testapp.shift.schedule');
    })->name('shift.schedule');
    Route::get('/shift/rotation', function () {
        return view('testapp.shift.rotation');
    })->name('shift.rotation');
    Route::get('/shift/assignment', function () {
        return view('testapp.shift.assignment');
    })->name('shift.assignment');
});

// System Overview Documentation
Route::get('/system-overview', function () {
    return view('system_overview');
})->name('system.overview');

// Shift Management Routes
Route::prefix('shifts')->group(function () {
    Route::get('/', [ShiftController::class, 'index'])->name('shift.index');
    Route::post('/', [ShiftController::class, 'store'])->name('shift.store');
    Route::get('/{id}/edit', [ShiftController::class, 'edit'])->name('shift.edit');
    Route::put('/{id}', [ShiftController::class, 'update'])->name('shift.update');
    Route::delete('/{id}', [ShiftController::class, 'destroy'])->name('shift.destroy');
    Route::get('/stats', [ShiftController::class, 'getStats'])->name('shift.stats');
    Route::get('/employee/{employeeId}/shifts', [ShiftController::class, 'getEmployeeShifts'])->name('shift.employee');
});

Route::get('/dashboard/attendance-status', [App\Http\Controllers\HRController::class, 'attendanceStatusJson']);

Route::get('/api/claim-types', [ClaimTypeController::class, 'index']);

// Prevent GET method not allowed error for reimburse
Route::get('/claims/{claim}/reimburse', function() {
    return redirect()->route('claims.claim_submission')->with('error', 'You cannot access reimburse directly. Please use the Reimburse button.');
});

// DEBUG: Test Blade parsing
Route::get('/test-blade', function() {
    return view('test');
});

Route::get('/leave/approve/{id}', function() {
    return redirect()->back()->with('error', 'Please use the Approve button, not the URL.');
});
