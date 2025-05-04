<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    public function index(){
        $viewdata = app(MROController::class)->init();
        
        $id = session('user')->id;
        $technician = DB::table('technicians')->where('account_id', $id)->first(['id']);

        if($technician){
            $tasks = DB::table('maintenance', 'm')
                ->join('work_orders as wo', 'wo.id', '=', 'm.work_order_id')
                ->join('assets as a', 'a.id', '=', 'wo.asset_id')
                ->join('schedules as s', 's.id', '=', 'wo.schedule_id')
                ->where('m.technicians_id', $technician->id)
                ->select(
                    'm.id', 'wo.id as work_order_id', 
                    'a.asset_tag', 's.maintenance_date', 
                    'wo.maintenance_type', 'wo.description',
                    'wo.location', 'wo.priority'
                    )
                ->get();

            //dd($tasks);
        }else{
            $tasks = collect();
        }

        return view("mro.assignment.index", $viewdata)
            ->with('tasks', $tasks);
    }
}
