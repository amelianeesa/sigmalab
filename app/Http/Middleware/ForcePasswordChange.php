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
            !$request->routeIs('profil.index', 'profil.password.update', 'logout')
        ) {
            return redirect()->route('profil.index')->with('force_password', true);
        }

        return $next($request);
    }
}