<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PRCAcquisitionController extends Controller
{
    public function request_index(){
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle'=>'Request Management',
            'hasBtn' => 'procurementRequestModal'
        ];

        $request = DB::table('procurement_requests')->get();
        return view('procurement.procurement.phase-one.request-management', $viewdata)
            ->with('requests', $request);
    }

    public function request_store(Request $request){
        //dd($request);
        $validated = $request->validate([
            'requested_by' => ['required',],
            'subject' => ['required', 'string'],
            'description' => ['string'],
            'subject_type' => ['required'],
            'quantity' => ['required'],
            'unit' => ['required'],
            'dueDate' => ['required'],
        ]);
        DB::transaction(function () use($validated){
            DB::table('procurement_requests')->insert([
                'request_number'=> 'PR-'.time(),
                'requested_by' => $validated['requested_by'],
                'subject' => $validated['subject'],
                'description'=>$validated['description'],
                'quantity'=> $validated['quantity'],
                'unit'=>$validated['unit'],
                'due_date'=>$validated['dueDate'],
                'status'=> 'Pending',
                'created_at'=>now(),
                'updated_at'=>now()
            ]);
        });
        return back();
    }

    public function request_update(Request $request){
        //dd($request);
        $validated = $request->validate([
            'description'   =>  ['nullable', 'string', 'min:1'],
            'subject_type'  =>  ['nullable', 'string'],
            'quantity'      =>  ['nullable', 'numeric'],
            'unit'          =>  ['nullable', 'string'],
            'status'        =>  ['nullable', 'string'],
            'dueDate'       =>  ['nullable', 'date'],
        ]);
        //dd($validated);
        try{
            DB::transaction(function () use($validated, $request){
                DB::table('procurement_requests')
                    ->where('id', $request->id)
                    ->update([
                        'description'=>$validated['description'],
                        'subject_type' => $validated['subject_type'],
                        'quantity'=> $validated['quantity'],
                        'unit'=>$validated['unit'],
                        'status'=>$validated['status'],
                        'due_date'=>$validated['dueDate'],
                        'updated_at'=>now()
                ]);
            });
        }catch(\Exception $e){
            dd($e);
        }
        return back();
    }

    public function request_destroy($id){
        DB::table('procurement_requests')->delete($id);
        return back();
    }
}
