<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\PermissionService;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleAccess
{
    public function __construct(protected PermissionService $permissionService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $kodeModul, string $minLevel = 'lihat'): Response
    {
        $user = $request->user();

        if (!$user || !$this->permissionService->userHasAccess($user, $kodeModul, $minLevel)) {
            abort(403, "Anda tidak memiliki akses yang cukup untuk mengakses modul ini.");
        }

        return $next($request);
    }
}
