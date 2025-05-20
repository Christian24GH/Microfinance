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

    return;
});


Route::get('/vendor/vendor_offers', function(){
    //prc_vendor_offers = id, request_number, vendor_id, agreement_text, offer_price, vendor_name, status
    $prc_vendor_offers = DB::connection('logistic_pgsql')
        ->table("prc_vendor_offers", 'pb')
        ->join('prc_approved_requests as pr', 'pr.id', '=', 'pb.prc_request_id')
        ->join('market_userinfo as v', 'v.id', '=', 'pb.vendor_id')
        ->select(
            'pb.id as PBID',
                    'pb.agreement_text',
                    'pb.offer_price',
                    'pb.status',
            'pr.id as PRID',
                'pr.request_number',
                'pr.subject',
                'pr.subject_type',
                'pr.quantity',
                'pr.unit',
                'pr.due_date',
                'pr.created_at',
            'v.id as vendor_id',
                'v.name as vendor_name',
                'v.email'
        )->where('pb.status', 'pending')
        ->get();
    
    return $prc_vendor_offers;
});

Route::post('/vendor/vendor_offers/status', function(Request $request){

    DB::connection('logistic_pgsql')
        ->table('prc_vendor_offers')
        ->where('id', $request->row_id)
        ->update([
            'status' => $request->status
        ]);

    return;
});
