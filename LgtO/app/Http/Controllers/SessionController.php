<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SessionController extends Controller
{
    public function auth(Request $request){
        $sid = $request->query('sid');

        if(!$sid)  return redirect()->away('http://localhost/dashboard/Microfinance/landing_page/public/login');

        $response = Http::get('http://localhost/dashboard/Microfinance/landing_page/public/api/fetch-token', [
            'sid' => $sid,
        ]);
        
        if(!$response->ok())  return redirect()->away('http://localhost/dashboard/Microfinance/landing_page/public/login');

        $data = json_decode($response);
        $token = $data->token;
        $user = Http::withToken($token)->get('http://localhost/dashboard/Microfinance/landing_page/public/api/user');
        $currentUser = json_decode($user);

        if(!$currentUser->id)  return redirect()->away('http://localhost/dashboard/Microfinance/landing_page/public/login');

        //your custom redirection logic here
        //stores to session for later use
        session(['user'=>$currentUser, 'token'=>$token, 'sid' =>$sid]);
        return redirect()->route("mro.dashboard");
    }

    public function logout(){
        $token = session('token');
        $sid = session('sid');
        //dd($token, $sid);
        if(!$token)  return redirect()->away('http://localhost/dashboard/Microfinance/landing_page/public/login');
        if(!$sid)  return redirect()->away('http://localhost/dashboard/Microfinance/landing_page/public/login');

        $response = Http::withToken($token)->post('http://localhost/dashboard/Microfinance/landing_page/public/api/logout', [
            'sid'=>$sid,
        ]);
        $data = $response->json();
        session()->flush();
        return redirect()->away('http://localhost/dashboard/Microfinance/landing_page/public/login');
    }
}
