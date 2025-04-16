<?php

use App\Http\Controllers\Login;
use App\Http\Controllers\Register;

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('landing');
});

Route::prefix('/login')->group(function()
{
    Route::get('/', [Login::class, 'index'])->name("login.index");
    Route::post('/store', [Login::class, 'login'])->name("login.login");
    
});
Route::prefix('/register')->group(function()
{
    Route::get('/', [Register::class, 'index'])->name("register.index");
    Route::post('/store', [Register::class, 'store'])->name("register.store");
});

Route::get('/testdb', function(){
    return view('testdb');
});

