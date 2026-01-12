<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutOnExit
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Force logout on any new request from dashboard page
        if ($request->routeIs('dashboard')) {
            Auth::logout();
        }

        return $response;
    }
}
