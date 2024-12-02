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
            view()->share('pageTitle', 'Welcome to the Application | Loghub - PT TATI ');
        } else if($request->is('login')) {
            view()->share('pageTitle2', 'Login Application | Loghub - PT TATI ');
        } else if($request->is('register')) {
            view()->share('pageTitle3', 'Registration Application | Loghub - PT TATI ');
        }

        return $next($request);
    }
}