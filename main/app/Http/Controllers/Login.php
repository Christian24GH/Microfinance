<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;

class Login extends Controller
{
    public function index(){
        return view('login');
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email|min:10|exists:Accounts,email',
            'password' => 'required|string'
        ]);
        
         // Find user
        $user = Accounts::where('email', $request->email)->first();

        // Check password directly (plain text compare)
        if ($user->password !== $request->password) {
            return back()->withErrors(['password' => 'Invalid credentials']);
        }

        // Fetch role
        $role = DB::table('roles')->where('id', $user->role_id)->value('role');

        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;

        $sessionKey = Str::uuid()->toString();

        Cache::put("microfinance_cache_session:$sessionKey", $token, now()->addMinutes(60));
        
        switch($role){

            case 'Employee':
                return response()->redirectTo("http://localhost/dashboard/Microfinance/testapp/index.php?sid=$sessionKey");
                break;
                
            //Logistics 1
            case 'Maintenance Admin':
            case 'Technician':
            case 'Procurement Administrator':
            case 'Asset Admin':
            case 'Project Manager':
            case 'Warehouse Manager':
                return response()->redirectTo("http://localhost/dashboard/Microfinance/log1/public/?sid=$sessionKey");
                break;

            //HR 2
            case 'HR Administrator':
                return response()->redirectTo("http://localhost/dashboard/Microfinance/hr2/hrms/Dashboard.php?sid=$sessionKey");
                break;
            //Core 2
            case 'Communication Officer':
                return response()->redirectTo("http://localhost/dashboard/Microfinance/core2/index.php?sid=$sessionKey");
                break;
            //Core 1
            case 'Loan Officer':
                return response()->redirectTo("http://localhost/dashboard/Microfinance/core1/dashboard.php?sid=$sessionKey");
                break;
            //Financial
            case 'Finance Officer':
                return response()->redirectTo("http://localhost/dashboard/Microfinance/financial/index.php?sid=$sessionKey");
                break;

            case 'Client':
                return response()->redirectTo("http://localhost/dashboard/Microfinance/core1/index.php?sid=$sessionKey");
                break;

            case 'Logistic2 Admin':
                return response()->redirectTo("http://localhost/dashboard/Microfinance/log2/template/admin.php?sid=$sessionKey");
                break;
            
            case 'Logistic2 User':
                return response()->redirectTo("http://localhost/dashboard/Microfinance/log2/template/user.php?sid=$sessionKey");
                break;
                
            default:
                return back()->with(['fail' =>'Invalid Role']);
                break;
        }
    }
    
    public function logout(Request $request)
    {
        //dd($request);
        $data = json_decode($request->getContent(), true);
        $sid = $data['sid'] ?? null;
        $token = $request->bearerToken();
        //dd($sid);
        if ($token) {
            $accessToken = PersonalAccessToken::findToken($token);
            $accessToken?->delete();
        }

        if ($sid) {
            Cache::forget("microfinance_cache_session:$sid");
        }

        return response()->json([
            'status' => 200,
            'message' => 'Logout attempt completed.',
            'sid_forget' => $sid ? true : false
        ]);
    }
}
