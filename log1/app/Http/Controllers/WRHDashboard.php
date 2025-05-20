<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class WRHDashboard extends Controller
{
    public function index()
    {
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle' => 'Dashboard'
        ];
        return view('wrh.dashboard.dashboard', $viewdata)
        ->with('warehouseCount', DB::table('warehouse')->get()->count())
        ->with('inventoryCount', DB::table('inventory')->get()->count())
        ->with('shipmentCount', DB::table('shipment')->get()->count())
        ->with('orderCount', DB::table('order')->get()->count())
        ->with('dockScheduleCount', DB::table('dockschedule')->get()->count())
        ->with('supplierCount', DB::table('vendors')->get()->count())
        ->with('qualityCheckCount', DB::table('quality_check')->get()->count())
        ->with('deliveryCount', DB::table('lastmile_delivery')->get()->count())
        ->with('rfidCount', DB::table('rfid_tag')->get()->count())
        ->with('reportCount', DB::table('wrh_report')->get()->count())
        ->with('recentShipments', DB::table('shipment')->latest()->take(5)->get());
    }
}
