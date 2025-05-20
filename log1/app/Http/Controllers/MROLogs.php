<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MROLogs extends Controller
{
    public function log(){
        $viewdata = $this->init();
        $viewdata += ['pageTitle' => 'Maintenance Logs'];

        $logs = DB::table('maintenance_logs as ml')
            ->join('maintenance as m', 'm.id', '=', 'ml.maintenance_id')
            ->join('accounts as a', 'a.id', '=', 'm.technicians_id')
            ->join('employee_info as e', 'e.id', '=', 'a.employee_id')
            ->select(
                'ml.*',
                'm.id as mID',
                DB::raw("CONCAT(e.firstname, ' ', COALESCE(e.middlename, ''), ' ', e.lastname) as fullname")
            )
            ->orderBy('ml.created_at', 'desc')
            ->get();

        return view("mro.log", $viewdata)
            ->with('logs', $logs);
    }
}
