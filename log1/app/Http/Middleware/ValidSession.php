<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use SweetAlert2\Laravel\Swal;
class ValidSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!session('user')){
            return redirect()->away('http://localhost/dashboard/Microfinance/main/public/login');
        }
        return $next($request);
    }
}
