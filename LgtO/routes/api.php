<?php

use Illuminate\Http\Request;
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
    });
    return back();
});