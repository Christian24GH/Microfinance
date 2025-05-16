<?php

use Illuminate\Http\Request;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


Route::post('/procurement/return_vendor', function(Request $request){
    //dd($request);
    
    $validated = $request->validate([
        'prc_request_id'=>['required', 'exists:procurement_requests,id'],
        'supplier_id'=>['required', 'exists:vendors,id'],
        'agreement_text'=>['nullable', 'string', 'min:1'],
        'offer_price'=>['required', 'decimal:0,2'],
    ]);
    /*
    DB::transaction(function () use($validated){
        DB::table('procurement_bids')
            ->insert([
                'prc_request_id'=>$validated['prc_request_id'],
                'supplier_id'=>$validated['supplier_id'],
                'agreement_text'=>$validated['agreement_text'],
                'offer_price'=>$validated['offer_price'],
                'created_at'=>now(),
                'updated_at'=>now()
            ]);
    });*/
    return back();
});


Route::get('/vendor/approved_procurement/store', function(){

    $approved = DB::table('procurement_requests')->where('status', 'approved')->get();;
    //dd($approved);

    //approved_procurements = ['prc_request_id', 'supplier_id', 'offer_price', 'agreement_text'];
    /*$result = DB::connection('logistic_pgsql')
        ->select("SELECT column_name, data_type, is_nullable, character_maximum_length
              FROM information_schema.columns
              WHERE table_name = ''");
    */
    DB::transaction(function () use($approved){
        foreach($approved as $row){
            
            if(DB::connection('logistic_pgsql')->table('prc_approved_requests')
            ->where('request_number', $row->request_number)
            ->exists()) continue;

            DB::connection('logistic_pgsql')->table('prc_approved_requests')->insert([
                'request_number' => $row->request_number,
                'requested_by'   => $row->requested_by,
                'subject'        => $row->subject,
                'description'    => $row->description,
                'subject_type'   => $row->subject_type,
                'quantity'       => $row->quantity,
                'unit'           => $row->unit,
                'due_date'       => $row->due_date,
                'status'         => $row->status,
                'created_at'     => $row->created_at,
                'updated_at'     => $row->updated_at,
            ]);
        }
    });

    $tables = DB::connection('logistic_pgsql')
        ->select("SELECT * FROM prc_approved_requests");

    return $tables;
});