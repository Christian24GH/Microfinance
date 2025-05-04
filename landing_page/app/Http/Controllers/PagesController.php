<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    public function landing()
    {
        return view('landing');
    }

    public function login()
    {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    public function dashboard()
    {
        return view('testdb'); // Assuming ito yung dashboard mo
    }
}
