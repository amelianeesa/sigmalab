<?php

namespace App\Policies;

use App\Models\HasilUji;
use App\Models\User;
use App\Enums\PeranPengguna;
use App\Policies\Concerns\ChecksDomainAccess;
use Illuminate\Auth\Access\HandlesAuthorization;

class HasilUjiPolicy
{
    use HandlesAuthorization, ChecksDomainAccess;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, HasilUji $hasilUji): bool
    {
        return true;
    }

    /**
     * Analis juga boleh input hasil uji (insert only).
     */
    public function create(User $user): bool
    {
        return $this->hasFullAccess($user, [
            PeranPengguna::ANALIS->value,
            PeranPengguna::KOORDINATOR_LAB->value,
            PeranPengguna::ADMIN_APLIKASI->value,
            PeranPengguna::DEVELOPER->value,
        ]);
    }

    // Tidak ada update & delete — insert only
}
