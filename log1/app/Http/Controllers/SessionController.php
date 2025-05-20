<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SessionController extends Controller
{
    public function auth(Request $request){
        $sid = $request->query('sid');

        if(!$sid)  return redirect()->away('http://localhost/dashboard/Microfinance/main/public/login');

        $response = Http::get('http://localhost/dashboard/Microfinance/main/public/api/fetch-token', [
            'sid' => $sid,
        ]);
        
        if(!$response->ok())  return redirect()->away('http://localhost/dashboard/Microfinance/main/public/login');

        $data = json_decode($response);
        $token = $data->token;
        $user = Http::withToken($token)->get('http://localhost/dashboard/Microfinance/main/public/api/user');
        $currentUser = json_decode($user);

        if(!$currentUser->id)  return redirect()->away('http://localhost/dashboard/Microfinance/main/public/login');

        //your custom redirection logic here
        //stores to session for later use
        $role = DB::table('roles')->where('id', $currentUser->role_id)->value('role');
        $employee = DB::table('employee_info')
            ->where('id', $currentUser->employee_id)
            ->first(['firstname', 'middlename', 'lastname']);
        
        $fullname = "$employee->lastname, $employee->firstname $employee->middlename";
        // assign role to your user object
        $currentUser->role = $role;
        $currentUser->fullname = $fullname;

        // put the object in session
        session(['user' => $currentUser, 'token' => $token, 'sid' => $sid]);

        //dd(session('user'));
        
        //dd($fullname);
        switch(session('user')->role){
            case 'Technician':
                return redirect()->route("mro.assignment.index");
            case 'Maintenance Admin':
                return redirect()->route("mro.dashboard");

            case 'Procurement Administrator':
                return redirect()->route("prc.dashboard.index");

            case 'Asset Admin':
                return redirect()->route("asset.dashboard.index");

            case 'Project Manager':
                return redirect()->route("pm.dashboard.index");
            
            case 'Warehouse Manager':
                return redirect()->route("warehouse.index");
        }
    }

    public function logout(){
        $token = session('token');
        $sid = session('sid');
        //dd($token, $sid);
        if(!$token)  return redirect()->away('http://localhost/dashboard/Microfinance/main/public/login');
        if(!$sid)  return redirect()->away('http://localhost/dashboard/Microfinance/main/public/login');

        $response = Http::withToken($token)->post('http://localhost/dashboard/Microfinance/main/public/api/logout', [
            'sid'=>$sid,
        ]);
        $data = $response->json();
        session()->flush();
        return redirect()->away('http://localhost/dashboard/Microfinance/main/public/login');
    }
}
