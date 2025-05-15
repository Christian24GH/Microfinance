<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function init(){
        $user = session()->get('user');
        $role = $user->role;
        $title = '';
        switch($role){
            case 'Maintenance Admin':
                $title = 'Maintenance Repair and Overhaul';
                break;
            case 'Procurement Administrator':
                $title = 'Procurement';
                break;
            case 'Project Manager':
                $title = 'Project Management';
                break;
            case 'Asset Admin':
                $title = 'Asset Management';
                break;
            case 'Warehouse Manager':
                $title = 'Warehousing';
                break;
            default:
                $role = '';
                $title = '';
                break;
        }
        $viewdata = [
            'title'=> $title,
            'id' => $user->id,
            'fullname'=> $user->fullname,
            'email'=> $user->email,
            'role'=>$role
        ];
        return $viewdata;
    }
}
