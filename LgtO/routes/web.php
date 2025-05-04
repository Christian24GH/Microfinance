<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\MROController;
use App\Http\Controllers\PartsInventoryController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\WorkOrders;
use App\Http\Middleware\ValidSession;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::get('/', [SessionController::class, 'auth']);
Route::post('/logout', [SessionController::class, 'logout']);

Route::middleware([ValidSession::class])->prefix('/mro')->group(function ()
{
    Route::get('/dashboard', [MROController::class, 'dashboard'])->name('mro.dashboard');
    Route::get('/logs', [MROController::class, 'log'])->name('mro.logs'); 
    Route::get('/inventory', [MROController::class, 'inventory'])->name('mro.inventory');
    
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