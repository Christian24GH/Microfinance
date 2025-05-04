<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <<<<<< Idinagdag ko na

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login'); // Ito yung login.blade.php mo
    }

    public function login(Request $request)
    {
        // Totoong login logic na ito
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/testapp'); // <<< Mapupunta ka sa page mo
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    public function logout(Request $request) // <<< Dinagdagan ko ng (Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}

