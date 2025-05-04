<?php

use App\Http\Controllers\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user());
});


Route::get('/fetch-token', function (\Illuminate\Http\Request $request) {
    $sid = $request->query('sid');
    $token = Cache::get("session:$sid");

    if (!$token) {
        return response()->json(['error' => 'Invalid session ID'], 401);
    }

    return response()->json(['token' => $token]);
});

Route::middleware('auth:sanctum')->post('/logout', [Login::class, 'logout'])->name("login.out");