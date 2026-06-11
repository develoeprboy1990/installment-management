<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SuperAdminMiddleware
 * 
 * Simple check: user logged in hai aur SuperAdmin role raktha hai.
 * Spatie Permission cache bypass karta hai direct DB check se.
 */
class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Not logged in
        if (!$user) {
            return redirect()->route('login');
        }

        // Check SuperAdmin role (direct, no cache issue)
        if (!$user->hasRole('SuperAdmin')) {
            abort(403, 'Access denied. SuperAdmin only area.');
        }

        return $next($request);
    }
}
