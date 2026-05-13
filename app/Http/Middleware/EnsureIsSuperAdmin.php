<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('admin')->check() && auth('admin')->user()->role === 'super_admin') {
            return $next($request);
        }

        return redirect()->route('admin.dashboard')->with('error', 'Unauthorized access.');
    }
}
