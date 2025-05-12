<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetDashboard extends Controller
{
    public function index(){
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle' => 'Dashboard'
        ];

        return view('asset.dashboard.index', $viewdata);
    }
}
