<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SweetAlert2\Laravel\Swal;

class AssignmentController extends Controller
{
    public function index(){
        $viewdata = $this->init();
        $viewdata += ['pageTitle'=>'Maintenance Tasks'];
        $id = session('user')->id;
        $technician = DB::table('accounts')->where('id', $id)->first(['id']);

        if($technician){
            $tasks = DB::table('maintenance', 'm')
                ->join('work_orders as wo', 'wo.id', '=', 'm.work_order_id')
                ->join('assets as a', 'a.id', '=', 'wo.asset_id')
                ->join('schedules as s', 's.id', '=', 'wo.schedule_id')
                ->where('m.technicians_id', $technician->id)
                ->whereNotIn('wo.status', ['pending', 'completed', 'cancelled'])
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

    public function reportgen(Request $request){
        //dd($request);
        $request->validate([
            'task_id' => 'required',
            'work_order_id' => 'required',
            'description'=>'required|string|min:1'
        ]);
       
        DB::transaction(function()use($request){
            DB::table('maintenance_logs')->insert([
                'maintenance_id' => $request->task_id,
                'description' => $request->description,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if(isset($request->LoggedAsComplete)){
                DB::table('work_orders')->where('id', $request->work_order_id)->update([
                    'status' => 'completed'
                ]);
            }
        });
        Swal::fire([
            'title' => 'Report Submitted!',
            'icon' => 'success',
            'confirmButtonText' => 'Ok'
        ]);
        return back();
    }
}
