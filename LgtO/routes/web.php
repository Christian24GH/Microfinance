<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\MRODashboard;
use App\Http\Controllers\MROLogs;
use App\Http\Controllers\PartsInventoryController;
use App\Http\Controllers\PRCAcquisitionController;
use App\Http\Controllers\PRCDashboard;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\WorkOrders;
use App\Http\Middleware\ValidSession;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::get('/', [SessionController::class, 'auth']);
Route::post('/logout', [SessionController::class, 'logout']);

Route::middleware([ValidSession::class])->prefix('/mro')->group(function ()
{
    Route::get('/dashboard', [MRODashboard::class, 'dashboard'])->name('mro.dashboard');
    Route::get('/logs', [MROLogs::class, 'log'])->name('mro.logs');
    
    #WorkOrder
    Route::get('/workOrder', [WorkOrders::class, 'index'])->name('mro.workorder.index');
    Route::post('/workOrder/store', [WorkOrders::class, 'store'])->name('mro.workorder.store');
    Route::post('/workOrder', [WorkOrders::class, 'update'])->name('mro.workorder.update');
    Route::delete('/workOrder/{id}', [WorkOrders::class, 'destroy'])->name('mro.workorder.destroy');
    
    #Tasks Assignment
    Route::get('/tasks', [WorkOrders::class, 'task'])->name('mro.task');
    Route::get('/tasks/{id}', [WorkOrders::class, 'getTechnicians'])->name('mro.task.getTechnicians');
    Route::post('/tasks/save', [WorkOrders::class, 'save'])->name('mro.task.save');

    #Parts Inventory
    Route::get('/parts-inventory', [PartsInventoryController::class, 'index'])->name('mro.inventory.index');
    Route::post('/parts-inventory', [PartsInventoryController::class, 'update'])->name('mro.inventory.update');
    Route::delete('/parts-inventory/{id}', [PartsInventoryController::class, 'destroy'])->name('mro.inventory.destroy');
    Route::post('/parts-inventory/refill', [PartsInventoryController::class, 'refill'])->name('mro.inventory.refill');

    #Assigned Tasks for Technicians
    Route::get('/assignment', [AssignmentController::class, 'index'])->name('mro.assignment.index');
});

Route::middleware([ValidSession::class])->prefix('/procurement')->group(function () {
    //Dashboard
    Route::get('/dashboard', [PRCDashboard::class, 'index'])->name('prc.dashboard.index');

    //Request-Management
    Route::get('/request-management', [PRCAcquisitionController::class, 'request_index'])->name('prc.request.index');
    Route::post('/request-management/store', [PRCAcquisitionController::class, 'request_store'])->name('prc.request.store');
    Route::post('/request-management', [PRCAcquisitionController::class, 'request_update'])->name('prc.request.update');
    Route::delete('/request-management/{id}', [PRCAcquisitionController::class, 'request_destroy'])->name('prc.request.destroy');
    
    //Bidding


});