<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Landing extends Controller
{
    public function index(){
        return view('landing');
    }

    public function vendor_landing(){
        return redirect()->away('https://admin-domain.onrender.com/');
    }
}
