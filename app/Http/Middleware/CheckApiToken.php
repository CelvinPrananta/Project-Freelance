<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Token_Key');

        if ($token != env('TOKEN_KEY')) {
            return response()->json([
                'msg' => 'Token Invalid' . $token . '-' . env('TOKEN_KEY'),
            ], 401);
        }

        return $next($request);
    }
}
