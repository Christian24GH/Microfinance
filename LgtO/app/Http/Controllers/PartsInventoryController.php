<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enums\ServerStatus;
use Illuminate\Validation\Rule;
use SweetAlert2\Laravel\Swal;

class PartsInventoryController extends Controller
{
    public function index(){
        $viewdata = app(MROController::class)->init();
        $partsInventory = DB::table('parts_inventory')->get();
        return view("mro.inventory.index", $viewdata)
            ->with('partsInventory', $partsInventory);
    }

    public function update(Request $request){ 
        $validation = $request->validate([
            'id' => 'required',
            'status' => [
                'required',
                Rule::in('active','discontinued')
            ],
            'reorder_level' => ['integer', 'required']
        ]);
        //dd($validation);
        try{
            DB::transaction(function () use($validation){
                DB::table('parts_inventory')
                    ->where('id', $validation['id'])
                    ->update([
                        'status'=>$validation['status'],
                        'reorder_level'=>$validation['reorder_level']
                    ]);
            });

        }catch(\Exception $e){
            Swal::fire([
                'title' => 'Failed to Update!',
                'message'=> $e,
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
        DB::table('parts_inventory')->delete($id);
        Swal::fire([
            'title' => 'Record Deleted!',
            'icon' => 'success',
            'confirmButtonText' => 'Ok'
        ]);
        return back();
    }

    #parts-request
    public function refill(Request $request){
        //write a function that refills this asset
    }
}
