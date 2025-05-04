<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MROController extends Controller
{
    public function init(){
        $user = session()->get('user');
        $role = '';
        $user->role == "MaintenanceAdmin" ? $role = "Maintenance Admin" : '';

        $viewdata = [
            'title'=> 'Maintenance Repair and Overhaul',
            'role'=> 'Maintenance Admin',
            'id' => $user->id,
            'fullname'=> $user->fullname,
            'email'=> $user->email,
            'role'=>$role
        ];
        return $viewdata;
    }

    public function dashboard(){
        $viewdata = $this->init();
       
        return view("mro.dashboard", $viewdata);
    }

    public function log(){
        $viewdata = $this->init();

        return view("mro.log", $viewdata);
    }

    

    

    
}

