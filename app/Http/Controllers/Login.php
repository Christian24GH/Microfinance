<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;

class Login extends Controller
{
    public function index(){
        return view('login');
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email|exists:Accounts,email',
            'password' => 'required|string'
        ]);

        $rememberme = $request->filled('remember');
        
        if (!Auth::attempt($request->only('email', 'password'), $rememberme)) {
            return back()->with(['fail' =>'Invalid Email or Password']);   
        }

        $user = Accounts::where('email', $request->input('email'))->first();

        $token = $user->createToken('auth_token')->plainTextToken;

        $sessionKey = Str::uuid()->toString();
        Cache::put("session:$sessionKey", $token, now()->addMinutes(60)); 

        switch($user->role){
            case 'EMPLOYEE':
                return response()->redirectTo("http://localhost/dashboard/Microfinance/testapp/index.php?sid=$sessionKey");
                break;
            default:
                return back()->with(['fail' =>'Invalid Role']);
                break;
        }
    }
    
    public function logout(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $sid = $data['sid'] ?? null;
        $token = $request->bearerToken();
        //dd($sid);
        if ($token) {
            $accessToken = PersonalAccessToken::findToken($token);
            $accessToken?->delete();
        }

        if ($sid) {
            Cache::forget("session:$sid");
        }

        return response()->json([
            'status' => 200,
            'message' => 'Logout attempt completed.',
            'sid_forget' => $sid ? true : false
        ]);
    }
}
