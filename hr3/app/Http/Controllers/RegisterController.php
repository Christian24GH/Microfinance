<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    // Show registration form
    public function index()
    {
        return view('auth.register');  // Make sure this matches your register.blade.php location
    }

    // Store the new user
    public function store(Request $request)
    {
        // Validate input fields
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed', // Ensures passwords match
        ]);

        // Create the user
        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password), // Hash the password before saving
        ]);

        // Redirect or success message
        return redirect()->route('login.index')->with('success', 'Account created successfully! You can now log in.');
    }
}
