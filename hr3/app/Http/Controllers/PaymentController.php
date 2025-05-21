<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function processing()
    {
        return view('testapp.claims.payment');
    }
}
