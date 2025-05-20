<?php

use App\Http\Controllers\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user();

    // Get role name
    $role = DB::table('roles')->where('id', $user->role_id)->value('role');

    // Build fullname
    if ($user->employee_id) {
        $employee = DB::table('employee_info')->where('id', $user->employee_id)->first();
        $fullname = trim($employee->firstname . ' ' . ($employee->middlename ? $employee->middlename . ' ' : '') . $employee->lastname);
    } elseif ($user->client_id) {
        $client = DB::table('client_info')->where('id', $user->client_id)->first();
        $fullname = trim($client->firstname . ' ' . ($client->middlename ? $client->middlename . ' ' : '') . $client->lastname);
    } else {
        $fullname = '';
    }

    return response()->json([
        'id'       => $user->id,
        'email'    => $user->email,
        'role_id'   => $user->role_id,
        'employee_id'   => $user->employee_id,
        'client_id'   => $user->client_id,
        'role'     => $role,
        'fullname' => $fullname,
    ]);
});


Route::get('/fetch-token', function (Request $request) {
    //dd($request);
    $sid = $request->query('sid');
    $token = Cache::get("microfinance_cache_session:$sid");

    //return response()->json(['message', "sid=$sid\ntoken=$token"]);

    if (!$token) {
        return response()->json(['error' => 'Invalid session ID'], 401);
    }

    return response()->json(['token' => $token]);
});

Route::middleware('auth:sanctum')->post('/logout', [Login::class, 'logout'])->name("login.out");