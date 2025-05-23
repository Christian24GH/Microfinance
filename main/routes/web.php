<?php

use App\Http\Controllers\Landing;
use App\Http\Controllers\Login;
use App\Http\Controllers\Register;

use Illuminate\Support\Facades\Route;


Route::get('/', [Landing::class, 'index'])->name('landing');

Route::prefix('/login')->group(function()
{
    Route::get('/', [Login::class, 'index'])->name("login.index");
    Route::post('/store', [Login::class, 'login'])->name("login.login");
    
});
Route::prefix('/register')->group(function()
{
    Route::get('/', [Register::class, 'client_register_index'])->name("client.register.index");
    Route::post('/store', [Register::class, 'client_register_store'])->name("client.register.store");
});


Route::get('/testdb', function(){
    return view('testdb');
});

