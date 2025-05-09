<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SweetAlert2\Laravel\Swal;
class WorkOrders extends Controller
{
    public function index(){
        $viewdata = $this->init();
        $assets = DB::table('assets')->get(['id', 'asset_tag']);
        $workOrders = DB::table('work_orders')
                ->join('schedules', 'schedules.id', '=', 'work_orders.schedule_id', 'inner')
                ->join('assets', 'assets.id', '=', 'work_orders.asset_id')
                ->get(['id'=>'work_orders.id', 'priority'=>'work_orders.priority', 'description'=>'work_orders.description', 'status'=>'work_orders.status',
                    'maintenance_type'=>'work_orders.maintenance_type', 'location'=>'work_orders.location', 'maintenance_date'=>'schedules.maintenance_date',
                    'asset_id'=>'work_orders.asset_id',
                ]);
        //dd($workOrders);
        $viewdata += [
            'pageTitle'=>'Work Orders',
            'hasBtn' => 'addWork',
        ];
        return view("mro.workorder.index", $viewdata)->with('assets', $assets)->with('workOrders', $workOrders);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'startdate' => 'required',
            'priority'=> 'required',
            'maintenance_type'=> 'required',
            'location'=> 'required',
            'status'=> 'required',
            'asset'=> 'required',
            'description'=>'string'
        ]);
        
        //dd($validated);
        DB::transaction(function () use ($validated, $request){
            $id = DB::table('schedules')->insertGetId([
                'maintenance_date'=>$validated['startdate'],
            ]);
            DB::table('work_orders')->insert([
                'priority'=>$validated['priority'],
                'description'=>$request->input('description'),
                'maintenance_type'=>$validated['maintenance_type'],
                'location'=>$validated['location'],
                'status'=>$validated['status'],
                'created_at'=>now(),
                'updated_at'=>now(),
                'asset_id'=>$validated['asset'],
                'schedule_id'=>$id,
            ]);
        });

        Swal::fire([
            'title' => 'Work Added!',
            'icon' => 'success',
            'confirmButtonText' => 'Ok'
        ]);

        return back();
    }

    public function update(Request $request){
        
        $validated = $request->validate([
            'startdate' => 'required|date',
            'priority'=> 'required',
            'maintenance_type'=> 'required',
            'location'=> 'required',
            'status'=> 'required',
            'asset_tag'=> 'required',
            'description'=>'string',
            'id'=>'required',
        ]);
        try{
            DB::transaction(function () use($validated){
                $schedule_id = DB::table('work_orders')->where('id', $validated['id'])->get('schedule_id')->first();
                DB::table('schedules')->where('id', $schedule_id->schedule_id)->update([
                    'maintenance_date'=>$validated['startdate'],
                ]);
    
                DB::table('work_orders')->where('id', $validated['id'])->update([
                    'asset_id'=> $validated['asset_tag'],
                    'priority'=> $validated['priority'],
                    'description'=> $validated['description'],
                    'maintenance_type'=> $validated['maintenance_type'],
                    'location'=> $validated['location'],
                    'status'=> $validated['status'],
                    'updated_at'=> now(),
                ]);
            });
        }catch(\Exception $e){
            Swal::fire([
                'title' => "Oops...",
                'text' => 'Failed to update the record!',
                'icon' => 'error',
                'confirmButtonText' => 'Ok'
            ]);
            return back();
        }
        
        Swal::fire([
            'title' => 'Record Updated!',
            'icon' => 'success',
            'confirmButtonText' => 'Ok'
        ]);

        return back();
    }

    public function destroy($id){
        //dd($id);
        try{
            DB::transaction(function () use ($id){
                $schedule_id = DB::table('work_orders')->where('id', $id)->get('schedule_id')->first();
                DB::table('schedules')->delete($schedule_id->schedule_id);
                DB::table('work_orders')->delete($id);
            });
        }catch(\Exception $e){
            Swal::fire([
                'title' => "Oops...",
                'text' => 'Failed to delete the record!',
                'icon' => 'error',
                'confirmButtonText' => 'Ok'
            ]);
            return back()->with('response', 'Failed to delete the record'.$e);
        }
        Swal::fire([
            'title' => 'Record Deleted!',
            'icon' => 'success',
            'confirmButtonText' => 'Ok'
        ]);
        return back();
    }

    public function save(Request $request){
        $technicians = $request->technicians;

        //dd($technicians);
        DB::transaction(function () use ($request, $technicians){
            DB::table('maintenance')->where('work_order_id', $request->workid)->delete();
            if(empty($technicians)){
                return;
            }
            foreach($technicians as $technician){
                DB::table('maintenance')->insert([
                    'work_order_id'=>$request->workid,
                    'technicians_id'=>$technician,
                ]);
            };
        });
        Swal::fire([
            'title' => 'Record Updated!',
            'icon' => 'success',
            'confirmButtonText' => 'Ok'
        ]);
        return back();
    }

    public function task(){

        $viewdata = $this->init();
        $viewdata += ['pageTitle'=>'Tasks',];
        $workOrders = DB::table('work_orders')
            ->where('status', 'pending')
            ->get();

        $assets = DB::table('assets')->get();
        //$maintenance = DB::table('maintenance')->get();
        $technicians = DB::table('technicians')
            ->join('accounts', 'accounts.id', '=', 'technicians.account_id')
            ->get(['technicians.*', 'accounts.fullname']);
        
        return view("mro.workorder.task", $viewdata)
            ->with('workOrders', $workOrders)
            ->with('technicians', $technicians)
            ->with('assets', $assets);
    }

    public function getTechnicians($id){
        $all = DB::table('technicians')
            ->join('accounts', 'accounts.id', '=', 'technicians.account_id')
            ->get(['technicians.*', 'accounts.fullname']);

        $assigned = DB::table('maintenance', 'm')
            ->where('work_order_id', $id)
            ->rightJoin('technicians as t', 't.id', '=', 'm.technicians_id')
            ->join('accounts as a', 'a.id', '=', 't.account_id')
            ->select(['m.*', 't.status', 'a.fullname'])
            ->get();
        
        
        return response()->json([
            'assigned' => $assigned,
            'all' => $all
        ]);
    }
}
