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

    //Biddings
    public function bids_index(){
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle'=>'Biddings',
            
        ];

        $bids = DB::table('procurement_bids', 'pb')
            ->join('procurement_requests as pr', 'pr.id', '=', 'pb.prc_request_id')
            ->join('vendors as v', 'v.id', '=', 'pb.supplier_id')
            ->select(
                'pb.id as PBID',
                    'pb.agreement_text',
                    'pb.offer_price',
                    'pb.status',
                    'pb.created_at',
                'pr.id as PRID',
                    'pr.request_number',
                    'pr.subject',
                    'pr.subject_type',
                    'pr.quantity',
                    'pr.unit',
                    'pr.due_date',
                'v.id as VID',
                    'v.name',
                    'v.contact'
            )->where('pb.status', 'Pending')
            ->get();
        //dd($bids);
        return view('procurement.procurement.phase-two.bids', $viewdata)->with('bids', $bids);
    }
    public function bids_update(Request $request){
        //dd($request);
        $request->validate([
            'procurement_bid_id' => 'required|integer|exists:procurement_bids,id',
            'procurement_request_id' => 'required|integer|exists:procurement_bids,prc_request_id',
        ]);
        DB::transaction(function()use($request){
            if($request->btn == 'accept'){
                DB::table('procurement_bids')
                    ->where('id', $request->procurement_bid_id)
                    ->where('prc_request_id', $request->procurement_request_id)
                    ->update(['status' => 'Accept',]);

                DB::table('procurement_bids')
                    ->where('id', '!=',$request->procurement_bid_id)
                    ->where('prc_request_id', $request->procurement_request_id)
                    ->update(['status' => 'Cancel']);
                
                $bidRow = DB::table('procurement_bids')->where('id', $request->procurement_bid_id)
                    ->select('offer_price')
                    ->first();
                //dd($bidRow);
                DB::table('procurement_invoices')->insert([
                    'prc_bid_id'     => $request->procurement_bid_id,
                    'invoice_amount' => $bidRow->offer_price,
                    'invoice_date'   => now(),
                    'due_date'       => now()->addDays(15),
                    'invoice_status' => 'Pending',
                    'payment_status' => 'Unpaid',
                    'created_at'     => now(),
                    'updated_at'     => now()
                ]);
            }else{
                DB::table('procurement_bids')
                    ->where('id', $request->procurement_bid_id)
                    ->where('prc_request_id', $request->procurement_request_id)
                    ->update(['status' => 'Cancel']);
            }
        });
        return back();
    }

    public function invoice_index(){
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle'=>'Bills and Receipt',
        ];

        $invoices = DB::table('procurement_invoices as i')
        ->join('procurement_bids as b', 'i.prc_bid_id', '=', 'b.id')
        ->join('procurement_requests as r', 'b.prc_request_id', '=', 'r.id')
        ->select(
            'i.*',
            'b.offer_price as bid_offer_price',
            'b.agreement_text',
            'b.status as bid_status',
            'r.request_number',
            'r.subject',
            'r.description',
            'r.subject_type',
            'r.quantity',
            'r.unit',
            'r.due_date as request_due_date'
        )
        ->get();
        //dd($invoices);
        return view('procurement.procurement.phase-three.invoice', $viewdata)->with('invoices', $invoices);
    }

    public function invoice_update(Request $request, $id){
        $validated = $request->validate([
            'invoice_status' => 'required|in:Pending,Sent,Received,Approved,Disputed,Cancelled,Closed'
        ]);

        DB::table('procurement_invoices')
            ->where('id', $id)
            ->update(['invoice_status' => $validated['invoice_status']]);

        return back()->with('success', 'Invoice status updated successfully.');
    }

    public function invoice_markAsPaid(Request $request, $id){
        $invoice = DB::table('procurement_invoices')->where('id', $id)->first();

        if (!$invoice) {
            return back()->with('error', 'Invoice not found.');
        }

        // Update payment status
        DB::table('procurement_invoices')
            ->where('id', $id)
            ->update([
                'payment_status' => 'Paid',
                'invoice_status' => 'Closed'
            ]);

        // Generate receipt
        $receiptNumber = 'RCPT-' . now()->format('YmdHis') . '-' . $id;

        DB::table('procurement_receipts')->insert([
            'invoice_id'    => $id,
            'receipt_number'=> $receiptNumber,
            'payment_date'  => now(),
            'amount'        => $invoice->invoice_amount,
            'created_at'    => now(),
            'updated_at'    => now()
        ]);

        return back()->with('success', 'Invoice marked as Paid and receipt generated.');
    }

    public function receipts_index(){
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle'=>'Bills and Receipt',
        ];

        $receipts = DB::table('procurement_receipts as pr')
            ->join('procurement_invoices as pi', 'pr.invoice_id', '=', 'pi.id')
            ->join('procurement_bids as pb', 'pi.prc_bid_id', '=', 'pb.id')
            ->join('procurement_requests as pq', 'pb.prc_request_id', '=', 'pq.id')
            ->select(
                'pr.id as receipt_id',
                'pr.receipt_number',
                'pr.payment_date',
                'pr.amount',
                'pi.invoice_amount',
                'pi.invoice_date',
                'pi.payment_status',
                'pq.request_number',
                'pq.subject',
                'pb.offer_price'
            )
            ->orderBy('pr.created_at', 'desc')
            ->get();
        return view('procurement.procurement.phase-three.receipts', $viewdata)
            ->with('receipts', $receipts);
    }

    public function receipts_show($id)
    {
        $receipt = DB::table('procurement_receipts as r')
            ->join('procurement_invoices as i', 'r.invoice_id', '=', 'i.id')
            ->join('procurement_bids as b', 'i.prc_bid_id', '=', 'b.id')
            ->join('procurement_requests as p', 'b.prc_request_id', '=', 'p.id')
            ->select(
                'r.*',
                'i.invoice_number',
                'i.invoice_amount',
                'b.offer_price',
                'p.request_number',
                'p.subject',
                'p.description'
            )
            ->where('r.id', $id)
            ->first();

        if (!$receipt) {
            return back()->with('error', 'Receipt not found.');
        }

        return view('receipts.show', compact('receipt'));
    }
}
