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
        $warehouses = DB::table('warehouse as w')
            ->join('accounts as a', 'a.id', '=', 'w.manager_id')
            ->join('employee_info as e', 'e.id', '=', 'a.employee_id')
            ->join('roles as r', 'r.id', '=', 'a.role_id')
            ->where('r.role', 'Warehouse Manager')
            ->get([
                'w.*',
                DB::raw("CONCAT(e.firstname, ' ', COALESCE(e.middlename, ''), ' ', e.lastname) as fullname"),
                'r.role as manager_role'
            ]);

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
        
        $inventory = DB::table('inventory', 'i')
            ->join('shipment as s', 's.shipment_id', '=', 'i.shipment_id')
            ->join('warehouse as w', 's.warehouse_id', '=', 'w.warehouse_id')
            ->get(['i.*', 'w.name as warehouse_name', 's.tracking_no']);

        return view('wrh.phaseone.inventory', $viewdata)
            ->with('inventory', $inventory);
    }

    public function inventory_store(Request $request) {
        $validated = $request->validate([
            'shipment_id' => 'nullable|exists:shipment,shipment_id'
        ]);

        $order = DB::table('shipment')->where('shipment_id', $validated['shipment_id'])->first(['order_id']);
        

        $invoice = DB::table('order')->where('order_id', $order->order_id)->first(['invoice_id']);
        $asset = DB::table('procurement_invoices')->where('id', $invoice->invoice_id)->first(['prc_approved_request_id']);

        $request = DB::connection('logistic_pgsql')
            ->table('prc_approved_requests')->where('id', $asset->prc_approved_request_id)
            ->first(['subject', 'quantity']);

        DB::table('inventory')->insert([
            'item_name' => $request->subject,
            'quantity' => $request->quantity,
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
        
        $shipments = DB::table('shipment', 's')
            ->join('warehouse as w', 'w.warehouse_id', '=', 's.warehouse_id')
            ->get(['s.*', 'w.name as warehouse']);

        return view('wrh.phasethree.shipment', $viewdata)
            ->with('shipments', $shipments);
    }

    public function shipment_store(Request $request) {
        $validated = $request->validate([
            'order_id' => 'required|exists:order,order_id',
            'warehouse_id' => 'required|exists:warehouse,warehouse_id',
            'ship_date' => 'required',
            'carrier' => 'required',
            'tracking_no' => 'required|string|unique:shipment,tracking_no',
            'delivery_status' => 'required|string'
        ]);

        DB::table('shipment')->insert([
            'warehouse_id' => $validated['warehouse_id'],
            'order_id' => $validated['order_id'],
            'ship_date' => $validated['ship_date'],
            'carrier' => $validated['carrier'],
            'tracking_no' => $validated['tracking_no'],
            'delivery_status' => $validated['delivery_status'],
            'created_at' => now(), 'updated_at' => now()
        ]);
        return back();
    }

    public function shipment_update(Request $request, $id) {
        //dd($request);
        $validated = $request->validate([
            'status' => 'required|string',
            
        ]);
        DB::transaction(function()use($validated, $id){
            $order_id = DB::table('shipment')->where('shipment_id', $id)->first(['order_id']);
            DB::table('shipment')->where('shipment_id', $id)->update([
                'delivery_status' => $validated['status'] == 'delivered' ? 'delivered' : 'cancelled',
                'updated_at' => now()
            ]);
            //dd($order_id);
            DB::table('order')->where('order_id', $order_id->order_id)->update([
                'status' => $validated['status'] == 'delivered' ? 'received': 'cancelled',
                'updated_at' => now()
            ]);
        });
        return back();
    }

    public function shipment_destroy($id) {
        DB::table('shipment')->delete($id);
        return back();
    }

    public function dockschedule_index() {
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle' => 'Dock Schedules'
        ];
        $schedules = DB::table('dockschedule')
            ->join('warehouse', 'dockschedule.warehouse_id', '=', 'warehouse.warehouse_id')
            ->select('dockschedule.*', 'warehouse.name as warehouse_name')
            ->get();
        return view('wrh.phasetwo.dock_schedule', $viewdata)
            ->with('schedules', $schedules);
    }

    public function dockschedule_store(Request $request) {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouse,warehouse_id',
            'timeslot' => 'required|date'
        ]);

        DB::table('dockschedule')->insert([
            'warehouse_id' => $validated['warehouse_id'],
            'timeslot' => $validated['timeslot'],
            'created_at' => now(), 'updated_at' => now()
        ]);
        return back();
    }

    public function dockschedule_update(Request $request, $id) {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouse,warehouse_id',
            'timeslot' => 'required|date'
        ]);

        DB::table('dockschedule')->where('schedule_id', $id)->update([
            'warehouse_id' => $validated['warehouse_id'],
            'timeslot' => $validated['timeslot'],
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
        $suppliers = DB::connection('logistic_pgsql')
            ->table('market_userinfo')->where('activation_status', 'active')->get();

        return view('wrh.phasethree.order', $viewdata)
            ->with('orders', $orders)
            ->with('suppliers', $suppliers);
    }

    public function order_store(Request $request) {
        foreach ($request->supplier as $supplierJson) {
            $supplier = json_decode($supplierJson, true);
            $supplier_id = $supplier['id'];
            $vendor_name = $supplier['name'];
        }
        $validated = $request->validate([
            'status' => 'required|string',
            'total_amount' => 'required|numeric',
            'order_date' => 'required|date'
        ]);

        DB::table('order')->insert([
            'supplier_id' => $supplier_id,
            'vendor_name' => $vendor_name,
            'order_date' => $validated['order_date'],
            'status' => $validated['status'],
            'total_amount' => $validated['total_amount'],
            'created_at' => now(), 
            'updated_at' => now()
        ]);
        return back();
    }

    public function order_update(Request $request, $id) {
        //dd($request);
        $validated = $request->validate([
            'status' => 'required|string',
        ]);
        DB::transaction(function()use($validated, $id){
            DB::table('order')->where('order_id', $id)->update([
                'status' => $validated['status'],
                'updated_at' => now()
            ]);

        });
        return back();
    }

    public function order_destroy($id) {
        DB::table('order')->delete($id);
        return back();
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
            ->with('qualityCheck', $checks);
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
