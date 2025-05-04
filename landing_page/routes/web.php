<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

// Pang landing page
Route::get('/', [PagesController::class, 'landing'])->name('landing');

// Pang login
Route::get('/login', [LoginController::class, 'index'])->name('login.index');
Route::post('/login', [LoginController::class, 'login'])->name('login.login');

// Pang logout (idagdag natin to)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Pang register
Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Pang testing at welcome
Route::get('/testdb', [PagesController::class, 'testdb'])->name('testdb');
Route::get('/welcome', [PagesController::class, 'welcome'])->name('welcome');

// Route pag successful login (dashboard/test app)
Route::get('/test-app', function () {
    return view('test-app'); // << ito yung gusto mong puntahan after login
})->middleware('auth');

// Landing page route (for logout redirection)
Route::get('/landing', function () {
    return view('landing'); // << para dito ka bumabalik after logout
});
