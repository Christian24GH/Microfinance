<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MRODashboard extends Controller
{
    public function dashboard(){
        $viewdata = $this->init();
        $viewdata += ['pageTitle' => 'Dashboard'];
        return view("mro.dashboard", $viewdata);
    }
}
