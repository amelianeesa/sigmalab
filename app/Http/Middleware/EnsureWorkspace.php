<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pemakaian: ->middleware('workspace:LAB') atau 'workspace:NON_LAB'.
 * Menolak di sisi server, jadi mengetik URL langsung tetap tidak bisa tembus.
 */
class EnsureWorkspace
{
    public function handle(Request $request, Closure $next, string ...$allowed): Response
    {
        if (!in_array(session('workspace'), $allowed, true)) {
            abort(403, 'Menu ini tidak tersedia di workspace Anda saat ini.');
        }

        return $next($request);
    }
}
