<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function init(){
        $user = session()->get('user');
        $role = $user->role;
        $roleFormat = '';
        $title = '';
        switch($role){
            case 'MaintenanceAdmin':
                $roleFormat = 'Maintenance Admin';
                $title = 'Maintenance Repair and Overhaul';
                break;
            case 'ProcurementAdministrator':
                $roleFormat = 'Procurement Administrator';
                $title = 'Procurement';
                break;
            default:
                $roleFormat = '';
                $title = '';
                break;
        }
        $viewdata = [
            'title'=> $title,
            'role'=> 'Maintenance Admin',
            'id' => $user->id,
            'fullname'=> $user->fullname,
            'email'=> $user->email,
            'role'=>$roleFormat
        ];
        return $viewdata;
    }
}
