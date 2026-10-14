<?php

namespace App\Http\Middleware;

use App\Support\Workspace;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menentukan workspace aktif di SETIAP request dari data user di database.
 * - User LAB / NON_LAB  : dikunci ke workspace-nya, tidak bisa diganti.
 * - User ALL            : memakai pilihan di session (default LAB).
 */
class ResolveWorkspace
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $milikUser = $user->workspace ?: Workspace::LAB;

            if ($milikUser === Workspace::ALL) {
                $pilihan = session('workspace');
                if (!in_array($pilihan, [Workspace::LAB, Workspace::NON_LAB], true)) {
                    session(['workspace' => Workspace::LAB]);
                }
            } else {
                session(['workspace' => $milikUser]);
            }
        }

        return $next($request);
    }
}
