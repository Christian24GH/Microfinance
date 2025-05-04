<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class Register extends Controller
{
    public function index(){
        return view('register');
    }

    public function store(Request $request){
        //dd($request->all());
        $request->validate([
            'email' => 'email|unique:Accounts,email',
            'fullname' => 'required',
            'password' => 'required',
            'confirmpassword' => 'required'
        ]);

        if($request->input('password') !== $request->input('confirmpassword')){
            return back()->withErrors(['passwordmatch'=>'Password didn\'t match!'])->withInput();
        }

        try{
            DB::transaction(function() use($request){
                Accounts::create([
                    'fullname' => $request->input('fullname'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'role' => 'Client'
                ]);
            });

            return back()->with(['success' =>'Account Successfully Created']);
        }
        catch(\Exception $e){
            return back()->with('errors', $e)->withInput();
        }
    }
}
