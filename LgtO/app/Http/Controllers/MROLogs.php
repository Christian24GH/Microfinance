<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MROLogs extends Controller
{
    public function log(){
        $viewdata = $this->init();
        $viewdata += ['pageTitle' => 'Maintenance Logs'];
        return view("mro.log", $viewdata);
    }
}
