<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttachTokenFromCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the request has our 'auth_token' cookie and no Authorization header yet
        if ($request->hasCookie('auth_token') && !$request->headers->has('Authorization')) {
            $token = $request->cookie('auth_token');
            // Inject it as a Bearer token so auth:sanctum can read it
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        return $next($request);
    }
}