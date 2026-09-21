<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    public function handle($request, Closure $next)
    {
        if (
            Auth::check() &&
            Auth::user()->must_change_password &&
            !$request->routeIs('password.force-change', 'password.force-change.update', 'logout')
        ) {
            return redirect()->route('password.force-change');
        }

        return $next($request);
    }
}