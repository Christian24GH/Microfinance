<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WRHController extends Controller
{
    public function warehouse_index() {
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle' => 'Warehouse Management'
        ];
        $warehouses = DB::table('warehouse', 'w')
            ->join('accounts as a', 'a.id', '=', 'w.manager_id')
            ->get(['w.*', 'a.fullname']);
        return view('wrh.phaseone.warehouse', $viewdata)
            ->with('warehouse', $warehouses);
    }
    
    public function warehouse_store(Request $request) {
        $validated = $request->validate([
            'name'=> 'required|string|unique:warehouse,name',
            'manager' => 'required|exists:accounts,id',
            'location' => 'required|string',
            'capacity' => 'required|integer'
        ]);

        DB::table('warehouse')->insert([
            'name' => $validated['name'],
            'manager_id' => $validated['manager'],
            'capacity' => $validated['capacity'],
            'location' => $validated['location'],
            'created_at' => now(), 'updated_at' => now()
        ]);
        return back();
    }

    public function warehouse_update(Request $request, $id) {
        $validated = $request->validate([
            'name' => 'required|string|unique:warehouse,name',
            'manager_id' => 'required|exists:accounts,id',
            'location' => 'required|string'
        ]);

        DB::table('warehouse')->where('warehouse_id', $id)->update([
            'name' => $validated['name'],
            'manager_id' => $validated['name'],
            'location' => $validated['location'],
            'updated_at' => now()
        ]);
        return back();
    }

    public function warehouse_destroy($id) {
        DB::table('warehouse')->delete($id);
        return back();
    }

    public function inventory_index() {
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle' => 'Inventory Management'
            
        ];
        
        $inventory = DB::table('inventory')
            ->join('warehouse', 'inventory.warehouse_id', '=', 'warehouse.warehouse_id')
            ->leftJoin('shipment', 'inventory.shipment_id', '=', 'shipment.shipment_id')
            ->select('inventory.*', 'warehouse.name as warehouse_name', 'shipment.tracking_no')
            ->get();

        return view('wrh.phaseone.inventory', $viewdata)
            ->with('inventory', $inventory);
    }

    public function inventory_store(Request $request) {
        $validated = $request->validate([
            'item_name' => 'required|string',
            'quantity' => 'required|numeric',
            'warehouse_id' => 'required|exists:warehouse,warehouse_id',
            'shipment_id' => 'nullable|exists:shipment,shipment_id'
        ]);

        DB::table('inventory')->insert([
            'item_name' => $validated['item_name'],
            'quantity' => $validated['quantity'],
            'warehouse_id' => $validated['warehouse_id'],
            'shipment_id' => $validated['shipment_id'] ?? null,
            'created_at' => now(), 'updated_at' => now()
        ]);
        return back();
    }

    public function inventory_update(Request $request, $id) {
        $validated = $request->validate([
            'item_name' => 'required|string',
            'quantity' => 'required|numeric',
            'warehouse_id' => 'required|exists:warehouse,warehouse_id',
            'shipment_id' => 'nullable|exists:shipment,shipment_id'
        ]);

        DB::table('inventory')->where('inventory_id', $id)->update([
            'item_name' => $validated['item_name'],
            'quantity' => $validated['quantity'],
            'warehouse_id' => $validated['warehouse_id'],
            'shipment_id' => $validated['shipment_id'] ?? null,
            'updated_at' => now()
        ]);
        return back();
    }

    public function inventory_destroy($id) {
        DB::table('inventory')->delete($id);
        return back();
    }

    public function shipment_index() {
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle' => 'Shipments'
        ];
        
        $shipments = DB::table('shipment')->get();
        return view('wrh.phasethree.shipment', $viewdata)
            ->with('shipments', $shipments);
    }

    public function shipment_store(Request $request) {
        $validated = $request->validate([
            'tracking_no' => 'required|string',
            'status' => 'required|string'
        ]);

        DB::table('shipment')->insert([
            'tracking_no' => $validated['tracking_no'],
            'status' => $validated['status'],
            'created_at' => now(), 'updated_at' => now()
        ]);
        return back();
    }

    public function shipment_update(Request $request, $id) {
        $validated = $request->validate([
            'tracking_no' => 'required|string',
            'status' => 'required|string'
        ]);

        DB::table('shipment')->where('shipment_id', $id)->update([
            'tracking_no' => $validated['tracking_no'],
            'status' => $validated['status'],
            'updated_at' => now()
        ]);
        return back();
    }

    public function shipment_destroy($id) {
        DB::table('shipment')->delete($id);
        return back();
    }

    public function dockschedule_index() {
        $schedules = DB::table('dock_schedule')
            ->join('warehouse', 'dock_schedule.warehouse_id', '=', 'warehouse.warehouse_id')
            ->select('dock_schedule.*', 'warehouse.name as warehouse_name')
            ->get();
        return view('dock_schedule', compact('schedule'));
    }

    public function dockschedule_store(Request $request) {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouse,warehouse_id',
            'schedule_time' => 'required|date'
        ]);

        DB::table('dock_schedule')->insert([
            'warehouse_id' => $validated['warehouse_id'],
            'schedule_time' => $validated['schedule_time'],
            'created_at' => now(), 'updated_at' => now()
        ]);
        return back();
    }

    public function dockschedule_update(Request $request, $id) {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouse,warehouse_id',
            'schedule_time' => 'required|date'
        ]);

        DB::table('dock_schedule')->where('schedule_id', $id)->update([
            'warehouse_id' => $validated['warehouse_id'],
            'schedule_time' => $validated['schedule_time'],
            'updated_at' => now()
        ]);
        return back();
    }

    public function dockschedule_destroy($id) {
        DB::table('dock_schedule')->delete($id);
        return back();
    }

    public function order_index() {
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle' => 'Orders'
        ];
        
        $orders = DB::table('order')->get();
        return view('wrh.phasethree.order', $viewdata)
            ->with('orders', $orders);
    }

    public function order_store(Request $request) {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventory,inventory_id',
            'warehouse_id' => 'required|exists:warehouse,warehouse_id',
            'shipment_id' => 'nullable|exists:shipment,shipment_id',
            'order_status' => 'required|string',
            'quantity' => 'required|numeric',
            'order_date' => 'required|date'
        ]);

        DB::table('order')->insert([
            'inventory_id' => $validated['inventory_id'],
            'warehouse_id' => $validated['warehouse_id'],
            'shipment_id' => $validated['shipment_id'],
            'order_status' => $validated['order_status'],
            'quantity' => $validated['quantity'],
            'order_date' => $validated['order_date'],
            'created_at' => now(), 'updated_at' => now()
        ]);
        return back();
    }

    public function order_update(Request $request, $id) {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventory,inventory_id',
            'warehouse_id' => 'required|exists:warehouse,warehouse_id',
            'shipment_id' => 'nullable|exists:shipment,shipment_id',
            'order_status' => 'required|string',
            'quantity' => 'required|numeric',
            'order_date' => 'required|date'
        ]);

        DB::table('order')->where('order_id', $id)->update([
            'inventory_id' => $validated['inventory_id'],
            'warehouse_id' => $validated['warehouse_id'],
            'shipment_id' => $validated['shipment_id'],
            'order_status' => $validated['order_status'],
            'quantity' => $validated['quantity'],
            'order_date' => $validated['order_date'],
            'updated_at' => now()
        ]);
        return back();
    }

    public function order_destroy($id) {
        DB::table('order')->delete($id);
        return back();
    }

    public function dockschedules_index() {
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle' => 'Dock Schedules'
        ];

        $schedules = DB::table('dockschedule')
            ->join('warehouse', 'dockschedule.warehouse_id', '=', 'warehouse.warehouse_id')
            ->select('dock_schedule.*', 'warehouse.name as warehouse_name')
            ->get();

        return view('wrh.phasetwo.dock_schedule', $viewdata)
            ->with('schedules', $schedules);
    }

    public function supplier_index() {
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle' => 'Vendors'
        ];
        $suppliers = DB::table('vendors')->get();
        return view('wrh.phasetwo.supplier', $viewdata)
            ->with('supplier', $suppliers);
    }

    public function supplier_store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'contact' => 'required|string'
        ]);

        DB::table('supplier')->insert([
            'name' => $validated['name'],
            'contact' => $validated['contact'],
            'created_at' => now(), 'updated_at' => now()
        ]);
        return back();
    }

    public function supplier_update(Request $request, $id) {
        $validated = $request->validate([
            'name' => 'required|string',
            'contact' => 'required|string'
        ]);

        DB::table('supplier')->where('supplier_id', $id)->update([
            'name' => $validated['name'],
            'contact' => $validated['contact'],
            'updated_at' => now()
        ]);
        return back();
    }

    public function supplier_destroy($id) {
        DB::table('supplier')->delete($id);
        return back();
    }

    public function qualitycheck_index() {
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle' => 'Quality Checking'
        ];

        $checks = DB::table('quality_check')
            ->join('inventory', 'quality_check.inventory_id', '=', 'inventory.inventory_id')
            ->select('quality_check.*', 'inventory.item_name')
            ->get();
        return view('wrh.phasetwo.quality_check', $viewdata)
            ->with('check', $checks);
    }
    
    public function qualitycheck_store(Request $request) {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventory,inventory_id',
            'status' => 'required|string',
            'checked_at' => 'required|date'
        ]);

        DB::table('quality_check')->insert([
            'inventory_id' => $validated['inventory_id'],
            'status' => $validated['status'],
            'checked_at' => $validated['checked_at'],
            'created_at' => now(), 'updated_at' => now()
        ]);
        return back();
    }

    public function qualitycheck_update(Request $request, $id) {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventory,inventory_id',
            'status' => 'required|string',
            'checked_at' => 'required|date'
        ]);

        DB::table('quality_check')->where('check_id', $id)->update([
            'inventory_id' => $validated['inventory_id'],
            'status' => $validated['status'],
            'checked_at' => $validated['checked_at'],
            'updated_at' => now()
        ]);
        return back();
    }

    public function qualitycheck_destroy($id) {
        DB::table('quality_check')->delete($id);
        return back();
    }

    public function lastmile_index() {
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle' => 'Deliveries'
        ];
        $deliveries = DB::table('lastmile_delivery')
            ->join('shipment', 'lastmile_delivery.shipment_id', '=', 'shipment.shipment_id')
            ->select('lastmile_delivery.*', 'shipment.tracking_no')
            ->get();
        return view('wrh.phasethree.lastmile_delivery', $viewdata)
            ->with('delivery', $deliveries);
    }

    public function lastmile_store(Request $request) {
        $validated = $request->validate([
            'shipment_id' => 'required|exists:shipment,shipment_id',
            'status' => 'required|string',
            'delivery_date' => 'required|date'
        ]);

        DB::table('lastmile_delivery')->insert([
            'shipment_id' => $validated['shipment_id'],
            'status' => $validated['status'],
            'delivery_date' => $validated['delivery_date'],
            'created_at' => now(), 'updated_at' => now()
        ]);
        return back();
    }

    public function lastmile_update(Request $request, $id) {
        $validated = $request->validate([
            'shipment_id' => 'required|exists:shipment,shipment_id',
            'status' => 'required|string',
            'delivery_date' => 'required|date'
        ]);

        DB::table('lastmile_delivery')->where('delivery_id', $id)->update([
            'shipment_id' => $validated['shipment_id'],
            'status' => $validated['status'],
            'delivery_date' => $validated['delivery_date'],
            'updated_at' => now()
        ]);
        return back();
    }

    public function lastmile_destroy($id) {
        DB::table('lastmile_delivery')->delete($id);
        return back();
    }

    public function rfid_tag_index() {
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle' => 'RFID'
        ];
        $rfids = DB::table('rfid_tag')
            ->join('inventory', 'rfid_tag.inventory_id', '=', 'inventory.inventory_id')
            ->select('rfid_tag.*', 'inventory.item_name')
            ->get();
        return view('wrh.phasefour.rfid', $viewdata)
            ->with('rfid', $rfids);
    }

    public function rfid_tag_store(Request $request) {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventory,inventory_id',
            'tag_uid' => 'required|string|unique:rfid_tag,tag_uid'
        ]);

        DB::table('rfid_tag')->insert([
            'inventory_id' => $validated['inventory_id'],
            'tag_uid' => $validated['tag_uid'],
            'created_at' => now(), 'updated_at' => now()
        ]);
        return back();
    }

    public function rfid_tag_update(Request $request, $id) {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventory,inventory_id',
            'tag_uid' => 'required|string'
        ]);

        DB::table('rfid_tag')->where('tag_id', $id)->update([
            'inventory_id' => $validated['inventory_id'],
            'tag_uid' => $validated['tag_uid'],
            'updated_at' => now()
        ]);
        return back();
    }

    public function rfid_tag_destroy($id) {
        DB::table('rfid_tag')->delete($id);
        return back();
    }

    public function report_index() {
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle' => 'Reports'
        ];
        $reports = DB::table('wrh_report')
            ->join('warehouse', 'wrh_report.warehouse_id', '=', 'warehouse.warehouse_id')
            ->select('wrh_report.*', 'warehouse.name as warehouse_name')
            ->get();
        return view('wrh.lastphase.report', $viewdata)
            ->with('report', $reports);
    }
    
    public function wrh_report_store(Request $request) {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouse,warehouse_id',
            'report_date' => 'required|date',
            'content' => 'required|string'
        ]);

        DB::table('wrh_report')->insert([
            'warehouse_id' => $validated['warehouse_id'],
            'report_date' => $validated['report_date'],
            'content' => $validated['content'],
            'created_at' => now(), 'updated_at' => now()
        ]);
        return back();
    }

    public function wrh_report_update(Request $request, $id) {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouse,warehouse_id',
            'report_date' => 'required|date',
            'content' => 'required|string'
        ]);

        DB::table('wrh_report')->where('report_id', $id)->update([
            'warehouse_id' => $validated['warehouse_id'],
            'report_date' => $validated['report_date'],
            'content' => $validated['content'],
            'updated_at' => now()
        ]);
        return back();
    }

    public function report_destroy($id) {
        DB::table('wrh_report')->delete($id);
        return back();
    }


}
