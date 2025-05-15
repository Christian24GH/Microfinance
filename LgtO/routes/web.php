<?php

use App\Http\Controllers\AssetDashboard;
use App\Http\Controllers\AssetsAssets;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\MRODashboard;
use App\Http\Controllers\MROLogs;
use App\Http\Controllers\PartsInventoryController;
use App\Http\Controllers\PMDashboard;
use App\Http\Controllers\PMProjects;
use App\Http\Controllers\PMProjectTeamController;
use App\Http\Controllers\PRCAcquisitionController;
use App\Http\Controllers\PRCDashboard;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\WorkOrders;
use App\Http\Controllers\WRHController;
use App\Http\Controllers\WRHDashboard;
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
    Route::post('/assignment/reportgen', [AssignmentController::class, 'reportgen'])->name('mro.assignment.reportgen');
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
    Route::get('/bid-management', [PRCAcquisitionController::class, 'bids_index'])->name('prc.bid.index');
    Route::post('/bid-management', [PRCAcquisitionController::class, 'bids_update'])->name('prc.bid.update');

    //Document
    Route::get('/invoice-management', [PRCAcquisitionController::class, 'invoice_index'])->name('prc.invoice.index');
    Route::post('/invoice-management/{id}', [PRCAcquisitionController::class, 'invoice_update'])->name('prc.invoice.update');
    Route::post('/invoice-management/markAsPaid/{id}', [PRCAcquisitionController::class, 'invoice_markAsPaid'])->name('prc.invoice.markAsPaid');

    Route::get('/receipt-management', [PRCAcquisitionController::class, 'receipts_index'])->name('prc.receipt.index');
    Route::get('/receipt-management/{id}', [PRCAcquisitionController::class, 'receipts_show'])->name('prc.receipt.show');
});

Route::middleware([ValidSession::class])->prefix('/pm')->group(function () {
    //Dashboard
    Route::get('/dashboard', [PMDashboard::class, 'pm_dashboard_index'])->name('pm.dashboard.index');

    //Projects
    Route::get('/projects', [PMProjects::class, 'index'])->name('pm.projects.index');
    Route::post('/projects/store', [PMProjects::class, 'store'])->name('pm.projects.store');
     Route::post('/projects/{id}', [PMProjects::class, 'destroy'])->name('pm.projects.destroy');

    Route::get('/teams', [PMProjectTeamController::class, 'index'])->name('pm.teams.index');
    Route::post('/teams/store', [PMProjectTeamController::class, 'store'])->name('pm.teams.store');
    Route::delete('/teams/{id}', [PMProjectTeamController::class, 'destroy'])->name('pm.teams.destroy');
});

Route::middleware([ValidSession::class])->prefix('/warehouse')->group(function () {
    //Dashboard
    Route::get('/dashboard', [WRHDashboard::class, 'index'])->name('wrh.dashboard.index');

    //
    Route::get('/warehouse', [WRHController::class, 'warehouse_index'])->name('wrh.warehouse.index');
    Route::get('/inventory', [WRHController::class, 'inventory_index'])->name('wrh.inventory.index');
    Route::get('/shipment', [WRHController::class, 'shipment_index'])->name('wrh.shipment.index');
    Route::get('/dockschedules', [WRHController::class, 'dockschedules_index'])->name('wrh.dock_schedules.index');
    //Route::get('/dockschedules', [WRHController::class, 'dockschedules_index'])->name('wrh.order.index');
    Route::get('/supplier', [WRHController::class, 'supplier_index'])->name('wrh.supplier.index');
    Route::get('/qualitycheck', [WRHController::class, 'qualitycheck_index'])->name('wrh.quality_check.index');
    Route::get('/lastmile', [WRHController::class, 'lastmile_index'])->name('wrh.lastmile_delivery.index');
    Route::get('/rfid', [WRHController::class, 'rfid_index'])->name('wrh.rfid_tag.index');
    Route::get('/report', [WRHController::class, 'report_index'])->name('wrh.wrh_report.index');
});

//Asset
Route::middleware([ValidSession::class])->prefix('/asset-management')->group(function () {
    //Dashboard
    Route::get('/dashboard', [AssetDashboard::class, 'index'])->name('asset.dashboard.index');

    //assetsx
    Route::get('/assets', [AssetsAssets::class, 'index'])->name('asset.asset.index');
    Route::post('/assets-store', [AssetsAssets::class, 'store'])->name('asset.asset.store');
    Route::post('/assets-update', [AssetsAssets::class, 'update'])->name('asset.asset.update');
    Route::post('/assets-delete', [AssetsAssets::class, 'destroy'])->name('asset.asset.destroy');
});
