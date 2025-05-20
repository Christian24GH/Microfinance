<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use SweetAlert2\Laravel\Swal;

class PRCAcquisitionController extends Controller
{
    public function request_index(){
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle'=>'Request Management',
            'hasBtn' => 'procurementRequestModal'
        ];

        $request = DB::table('procurement_requests')->where('status', 'Pending')->get();
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
        Swal::fire([
            'title' => 'Procurement Request Submitted',
            'icon' => 'success',
            'confirmButtonText' => 'Ok'
        ]);
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
            if($validated['status'] == 'Approved'){
                Http::get('http://localhost/dashboard/Microfinance/log1/public/api/vendor/approved_procurement/store');
            }
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

        $bids = Http::get('http://localhost/dashboard/Microfinance/log1/public/api/vendor/vendor_offers');
        
        return view('procurement.procurement.phase-two.bids', $viewdata)->with('bids', collect($bids->json()));
    }
    public function bids_update(Request $request){
        //dd($request);
        $request->validate([
            'procurement_bid_id' => 'required|integer',
            'procurement_request_id' => 'required|integer',
        ]);
        DB::transaction(function()use($request){
            if($request->btn == 'accept'){
                DB::connection('logistic_pgsql')
                    ->table('prc_vendor_offers')
                    ->where('id', $request->procurement_bid_id)
                    ->where('prc_request_id', $request->procurement_request_id)
                    ->update(['status' => 'approved',]);

                DB::connection('logistic_pgsql')
                    ->table('prc_vendor_offers')
                    ->where('id', '!=',$request->procurement_bid_id)
                    ->where('prc_request_id', $request->procurement_request_id)
                    ->update(['status' => 'rejected']);
                
                $bidRow = DB::connection('logistic_pgsql')
                    ->table('prc_vendor_offers')
                        ->where('id', $request->procurement_bid_id)
                        ->select('offer_price', 'vendor_id')
                        ->first();
        
                
                $vendor = DB::connection('logistic_pgsql')
                    ->table('market_userinfo')
                    ->where('id', $bidRow->vendor_id)
                    ->first(['id', 'name']);

                //dd($vendor);
                $row = DB::connection('logistic_pgsql')
                    ->table('l2_vendor_history')
                    ->insert([
                        'vendor_id' => $vendor->id,
                        'event_type' => 'Offer Accepted'
                    ]);
                
                $invoice = DB::table('procurement_invoices')->insertGetId([
                    'prc_bid_id'     => $request->procurement_bid_id,
                    'prc_approved_request_id' => $request->procurement_request_id,
                    'invoice_amount' => $bidRow->offer_price,
                    'invoice_date'   => now(),
                    'due_date'       => now()->addDays(15),
                    'invoice_status' => 'Pending',
                    'payment_status' => 'Unpaid',
                    'created_at'     => now(),
                    'updated_at'     => now()
                ]);

                DB::table('order')->insert([
                    'supplier_id' => $vendor->id,
                    'vendor_name' => $vendor->name,
                    'invoice_id' => $invoice,
                    'order_date' => now(),
                    'status' => 'Pending',
                    'total_amount' => $bidRow->offer_price,
                    'created_at' => now(), 
                    'updated_at' => now()
                ]);
            }else{

                DB::connection('logistic_pgsql')
                    ->table('prc_vendor_offers')
                    ->where('id', $request->procurement_bid_id)
                    ->where('prc_request_id', $request->procurement_request_id)
                    ->update(['status' => 'rejected']);

            }
        });

        return back();
    }

    public function invoice_index(){
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle'=>'Bills and Receipt',
        ];
        $invoices = DB::table("procurement_invoices")->get();

        $vendorOffers = DB::connection('logistic_pgsql')
            ->table("prc_vendor_offers")
            ->get()
            ->keyBy('id');

        $approvedRequests = DB::connection('logistic_pgsql')
            ->table("prc_approved_requests")
            ->get()
            ->keyBy('id');

        $results = $invoices->map(function ($invoice) use ($vendorOffers, $approvedRequests) {
            $bid = $vendorOffers->get($invoice->prc_bid_id);
            $request = $approvedRequests->get($invoice->prc_approved_request_id);

            return (object) [
                'id'              => $invoice->id,
                'bid_offer_price'  => $bid?->offer_price,
                'agreement_text'  => $bid?->agreement_text,
                'bid_status'      => $bid?->status,
                'request_number'  => $request?->request_number,
                'subject'         => $request?->subject,
                'description'     => $request?->description,
                'subject_type'    => $request?->subject_type,
                'quantity'        => $request?->quantity,
                'unit'            => $request?->unit,
                'request_due_date'=> $request?->due_date,
                'payment_status'  => $invoice?->payment_status,
                'invoice_status'  => $invoice?->invoice_status,
                'invoice_amount'  => $invoice?->invoice_amount,
                'invoice_date'  => $invoice?->created_at,
                'due_date'  => $invoice?->due_date,
            ];
        });
        //dd($results);
        return view('procurement.procurement.phase-three.invoice', $viewdata)->with('invoices', $results);
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

        $receipts = DB::table('procurement_receipts as r')
            ->join('procurement_invoices as i', 'r.invoice_id', '=', 'i.id')
            ->select(
                'r.*',
                'r.receipt_number',
                'i.invoice_amount',
                'i.invoice_date',
                'i.prc_bid_id',
                'i.payment_status',
                'i.prc_approved_request_id'
            )->get();
        //dd($receipts);
        $vendorOffers = DB::connection('logistic_pgsql')
            ->table("prc_vendor_offers")
            ->get()
            ->keyBy('id');

        $approvedRequests = DB::connection('logistic_pgsql')
            ->table("prc_approved_requests")
            ->get()
            ->keyBy('id');
        
        $result = $receipts->map(function($receipt) use($vendorOffers, $approvedRequests){
            $bid = $vendorOffers->get($receipt->prc_bid_id);
            $request = $approvedRequests->get($receipt->prc_approved_request_id);

            return (object) [
                'receipt_id' => $receipt?->id,
                'receipt_number' => $receipt?->receipt_number,
                'payment_date' => $receipt?->payment_date,
                'amount' => $receipt?->amount,
                'invoice_amount' => $receipt?->invoice_amount,
                'invoice_date' => $receipt?->invoice_date,
                'payment_status' => $receipt?->payment_status,
                'request_number' => $request?->request_number,
                'subject' => $request?->subject,
                'offer_price' => $bid?->offer_price,
            ];
        });
        //dd($result);
        return view('procurement.procurement.phase-three.receipts', $viewdata)
            ->with('receipts', $result);
    }

    public function receipts_show($id)
    {
        $receipt = DB::table('procurement_receipts as r')
            ->join('procurement_invoices as i', 'r.invoice_id', '=', 'i.id')
            ->select(
                'r.*',
                'i.invoice_number',
                'i.invoice_amount',
            )
            ->where('r.id', $id)
            ->first();
        dd($receipt);
        $vendorOffers = DB::connection('logistic_pgsql')
            ->table("prc_vendor_offers")
            ->get()
            ->keyBy('id');

        $approvedRequests = DB::connection('logistic_pgsql')
            ->table("prc_approved_requests")
            ->get()
            ->keyBy('id');
        
            /*
        $results = $invoices->map(function($invoice) use($vendorOffers, $approvedRequests){
            $bid = $vendorOffers->get($invoice->prc_bid_id);
            $request = $approvedRequests->get($invoice->prc_approved_requests);

            return (object) [
                'id'              => $invoice->id,
                'bid_offer_price'  => $bid?->offer_price,
                'agreement_text'  => $bid?->agreement_text,
                'bid_status'      => $bid?->status,
                'request_number'  => $request?->request_number,
                'subject'         => $request?->subject,
                'description'     => $request?->description,
                'subject_type'    => $request?->subject_type,
                'quantity'        => $request?->quantity,
                'unit'            => $request?->unit,
                'request_due_date'=> $request?->due_date,
                'payment_status'  => $invoice?->payment_status,
                'invoice_number' => $invoice?->invoice_number,
                'invoice_status'  => $invoice?->invoice_status,
                'invoice_amount'  => $invoice?->invoice_amount,
                'invoice_date'  => $invoice?->created_at,
                'due_date'  => $invoice?->due_date,
            ];
        });
        
       */

        if (!$receipt) {
            return back()->with('error', 'Receipt not found.');
        }

        return view('receipts.show', compact('receipt'));
    }
}
