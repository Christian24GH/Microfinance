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
        $warehouses = DB::table('warehouse')->get();
        return view('wrh.phaseone.warehouse', $viewdata)
            ->with('warehouse', $warehouses);
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

    public function shipment_index() {
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle' => 'Shipments'
        ];
        
        $shipments = DB::table('shipment')->get();
        return view('wrh.phasethree.shipment', $viewdata)
            ->with('shipments', $shipments);
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

    public function rfid_index() {
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
}
