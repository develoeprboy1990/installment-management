<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\AuthenticationException;

class RedirectIfUnauthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            // Redirect to login if session expired or user not authenticated
            return redirect()->route('login');
        }

        return $next($request);
    }
}

