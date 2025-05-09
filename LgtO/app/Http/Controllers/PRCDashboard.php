<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PRCDashboard extends Controller
{
    public function index(){
        $data = $this->init();
        $data += ['pageTitle' => 'Dashboard'];
        return view('procurement.dashboard.index', $data);
    }

}
