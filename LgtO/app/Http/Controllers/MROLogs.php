<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MROLogs extends Controller
{
    public function log(){
        $viewdata = $this->init();
        $viewdata += ['pageTitle' => 'Maintenance Logs'];

        $logs = DB::table('maintenance_logs', 'ml')
            ->join('maintenance as m', 'm.id', '=', 'ml.maintenance_id')
            ->join('accounts as a', 'a.id', '=', 'm.technicians_id')
            ->select('ml.*', 'a.fullname', 'm.id as mID')
            ->orderBy('created_at', 'desc')
            ->get();

        return view("mro.log", $viewdata)
            ->with('logs', $logs);
    }
}
