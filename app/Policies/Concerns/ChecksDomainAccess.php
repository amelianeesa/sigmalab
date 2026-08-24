<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait ChecksDomainAccess
{
    protected string $kodeModul;

    public function hasFullAccess(User $user, string $minLevel = 'tambah_ubah'): bool
    {
        if (empty($this->kodeModul)) {
            throw new \Exception("Properti \$kodeModul harus di-set pada class " . static::class);
        }

        /** @var \App\Services\PermissionService $permissionService */
        $permissionService = app(\App\Services\PermissionService::class);
        
        return $permissionService->userHasAccess($user, $this->kodeModul, $minLevel);
    }
}
