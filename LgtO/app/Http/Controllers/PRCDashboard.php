<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PRCDashboard extends Controller
{
    public function index(){
        $data = $this->init();
        $data += ['pageTitle' => 'Dashboard'];

        $NewRequest = DB::table('procurement_requests')->where('created_at', '<', now())->count();
        $unpaidBills = DB::table('procurement_invoices')->where('payment_status', 'Unpaid')->count();
        $dailyAmounts = DB::table('procurement_receipts')
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->where('created_at', '>=', now()->subDays(30)->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        //dd($totalAmount);
        return view('procurement.dashboard.index', $data)
            ->with('NewRequest', $NewRequest)
            ->with('unpaidBills', $unpaidBills)
            ->with('dailyAmounts', $dailyAmounts);
    }
    
}
