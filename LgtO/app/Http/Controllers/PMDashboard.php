<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PMDashboard extends Controller
{
    public function pm_dashboard_index(){
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle' => 'Dashboard',
        ];
        return view('pm.dashboard.index', $viewdata);
    }
}
