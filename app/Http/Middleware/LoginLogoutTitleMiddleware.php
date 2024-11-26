<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginLogoutTitleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->is('/')) {
            view()->share('pageTitle', 'Login | Loghub - PT TATI ');
        }
        elseif($request->is('login')) {
        view()->share('pageTitle', 'Login | Loghub - PT TATI ');
        }
        elseif ($request->is('logout')) {
            view()->share('pageTitle2', 'Logout | Loghub - PT TATI ');
        }

        return $next($request);
    }
}
