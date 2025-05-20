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

/*
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
*/
Route::middleware([ValidSession::class])->prefix('/warehouse')->group(function () {
    //Dashboard
    Route::get('/dashboard', [WRHDashboard::class, 'index'])->name('wrh.dashboard.index');

    // Warehouse
    Route::get('/warehouse', [WRHController::class, 'warehouse_index'])->name('warehouse.index');
    Route::post('/warehouse', [WRHController::class, 'warehouse_store'])->name('warehouse.store');
    Route::put('/warehouse/{id}', [WRHController::class, 'warehouse_update'])->name('warehouse.update');
    Route::delete('/warehouse/{id}', [WRHController::class, 'warehouse_destroy'])->name('warehouse.destroy');

    // Inventory
    Route::get('/inventory', [WRHController::class, 'inventory_index'])->name('wrh.inventory.index');
    Route::post('/inventory', [WRHController::class, 'inventory_store'])->name('inventory.store');
    Route::put('/inventory/{id}', [WRHController::class, 'inventory_update'])->name('inventory.update');
    Route::delete('/inventory/{id}', [WRHController::class, 'inventory_destroy'])->name('inventory.destroy');

    // Shipment
    Route::get('/shipment', [WRHController::class, 'shipment_index'])->name('shipment.index');
    Route::post('/shipment', [WRHController::class, 'shipment_store'])->name('shipment.store');
    Route::post('/shipment/{id}', [WRHController::class, 'shipment_update'])->name('shipment.update');
    Route::delete('/shipment/{id}', [WRHController::class, 'shipment_destroy'])->name('shipment.destroy');

    // Orders
    Route::get('/order', [WRHController::class, 'order_index'])->name('order.index');
    Route::post('/order', [WRHController::class, 'order_store'])->name('order.store');
    Route::post('/order/{id}', [WRHController::class, 'order_update'])->name('order.update');
    Route::delete('/order/{id}', [WRHController::class, 'order_destroy'])->name('order.destroy');

    // Dock Schedule
    Route::get('/dock_schedule', [WRHController::class, 'dockschedule_index'])->name('dockschedule.index');
    Route::post('/dock_schedule', [WRHController::class, 'dockschedule_store'])->name('dockschedule.store');
    Route::put('/dock_schedule/{id}', [WRHController::class, 'dockschedule_update'])->name('dockschedule.update');
    Route::delete('/dock_schedule/{id}', [WRHController::class, 'dockschedule_destroy'])->name('dockschedule.destroy');

    // Supplier
    Route::get('/supplier', [WRHController::class, 'supplier_index'])->name('supplier.index');
    Route::post('/supplier', [WRHController::class, 'supplier_store'])->name('supplier.store');
    Route::put('/supplier/{id}', [WRHController::class, 'supplier_update'])->name('supplier.update');
    Route::delete('/supplier/{id}', [WRHController::class, 'supplier_destroy'])->name('supplier.destroy');

    // Quality Check
    Route::get('/quality_check', [WRHController::class, 'qualitycheck_index'])->name('qualitycheck.index');
    Route::post('/quality_check', [WRHController::class, 'qualitycheck_store'])->name('quality_check.store');
    Route::put('/quality_check/{id}', [WRHController::class, 'qualitycheck_update'])->name('quality_check.update');
    Route::delete('/quality_check/{id}', [WRHController::class, 'qualitycheck_destroy'])->name('quality_check.destroy');

    // Last Mile Delivery
    Route::get('/lastmile_delivery', [WRHController::class, 'lastmile_index'])->name('lastmile.index');
    Route::post('/lastmile_delivery', [WRHController::class, 'lastmile_store'])->name('lastmile.store');
    Route::put('/lastmile_delivery/{id}', [WRHController::class, 'lastmile_update'])->name('lastmile.update');
    Route::delete('/lastmile_delivery/{id}', [WRHController::class, 'lastmile_destroy'])->name('lastmile.destroy');

    // RFID
    Route::get('/rfid_tag', [WRHController::class, 'rfid_tag_index'])->name('rfid_tag.index');
    Route::post('/rfid_tag', [WRHController::class, 'rfid_tag_store'])->name('rfid_tag.store');
    Route::put('/rfid_tag/{id}', [WRHController::class, 'rfid_tag_update'])->name('rfid_tag.update');
    Route::delete('/rfid_tag/{id}', [WRHController::class, 'rfid_tag_destroy'])->name('rfid_tag.destroy');

    // Reports
    Route::get('/wrh_report', [WRHController::class, 'wrh_report_index'])->name('wrh_report.index');
    Route::post('/wrh_report', [WRHController::class, 'wrh_report_store'])->name('wrh_report.store');
    Route::put('/wrh_report/{id}', [WRHController::class, 'wrh_report_update'])->name('wrh_report.update');
    Route::delete('/wrh_report/{id}', [WRHController::class, 'wrh_report_destroy'])->name('wrh_report.destroy');
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
