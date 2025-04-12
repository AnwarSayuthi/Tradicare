<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        if (auth()->user()->role !== 'customer') {
            return redirect('/')->with('error', 'Access denied. Customer privileges required.');
        }

        return $next($request);
    }
}
