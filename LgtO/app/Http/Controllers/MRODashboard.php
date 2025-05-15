<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MRODashboard extends Controller
{
    public function dashboard(){
        $viewdata = $this->init();
        $viewdata += ['pageTitle' => 'Dashboard'];
        $workOrdersCount = DB::table('work_orders')->where('status', 'pending')->count();
        $Logs = DB::table('maintenance_logs')->count();
        $workOrders = DB::table('work_orders as wo')
            ->join('assets as a', 'a.id', '=', 'wo.asset_id')
            ->join('schedules as s', 's.id', '=', 'wo.schedule_id')
            ->join('accounts as u', 'u.id', '=', 'wo.created_by')
            ->join('maintenance as ma', 'ma.work_order_id', '=', 'wo.id')
            ->join('accounts as u2', 'u2.id', '=', 'ma.technicians_id')
            ->select('wo.*', 'a.asset_tag', 'a.category','s.maintenance_date', 'u.fullname', 'u2.fullname as assigned_to')
            ->where('wo.status', 'in_progress')->limit(5)->get();
        //dd($workOrders);
        return view("mro.dashboard", $viewdata)
            ->with('Requests', $workOrdersCount)
            ->with('Logs', $Logs)
            ->with('WorkOrders', $workOrders);
    }
}
