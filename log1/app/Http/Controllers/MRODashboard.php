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
            ->join('employee_info as e', 'e.id', '=', 'u.employee_id')
            ->join('maintenance as ma', 'ma.work_order_id', '=', 'wo.id')
            ->join('accounts as u2', 'u2.id', '=', 'ma.technicians_id')
            ->join('employee_info as e2', 'e2.id', '=', 'u2.employee_id')
            ->select(
                'wo.*',
                'a.asset_tag',
                'a.category',
                's.maintenance_date',
                DB::raw("CONCAT(e.firstname, ' ', COALESCE(e.middlename, ''), ' ', e.lastname) as fullname"),
                DB::raw("CONCAT(e2.firstname, ' ', COALESCE(e2.middlename, ''), ' ', e2.lastname) as assigned_to")

            )
            ->where('wo.status', 'in_progress')
            ->limit(5)
            ->get();

        //dd($workOrders);
        return view("mro.dashboard", $viewdata)
            ->with('Requests', $workOrdersCount)
            ->with('Logs', $Logs)
            ->with('WorkOrders', $workOrders);
    }
}
