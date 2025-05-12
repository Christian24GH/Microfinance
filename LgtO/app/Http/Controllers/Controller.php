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
            case 'ProjectManager':
                $roleFormat = 'Project Manager';
                $title = 'Project Management';
                break;
            case 'AssetAdmin':
                $roleFormat = 'Asset Admin';
                $title = 'Asset Management';
                break;
            case 'WarehouseManager':
                $roleFormat = 'Warehouse Manager';
                $title = 'Warehousing';
                break;
            default:
                $roleFormat = '';
                $title = '';
                break;
        }
        $viewdata = [
            'title'=> $title,
            'id' => $user->id,
            'fullname'=> $user->fullname,
            'email'=> $user->email,
            'role'=>$roleFormat
        ];
        return $viewdata;
    }
}
