<?php

namespace App\Policies;

use App\Models\RiwayatTindakLanjut;
use App\Models\User;
use App\Enums\PeranPengguna;
use App\Policies\Concerns\ChecksDomainAccess;
use Illuminate\Auth\Access\HandlesAuthorization;

class RiwayatTindakLanjutPolicy
{
    use HandlesAuthorization, ChecksDomainAccess;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, RiwayatTindakLanjut $riwayatTindakLanjut): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $this->hasFullAccess($user, [
            PeranPengguna::KOORDINATOR_LAB->value,
            PeranPengguna::ADMIN_APLIKASI->value,
            PeranPengguna::DEVELOPER->value,
        ]);
    }

    // Tidak ada update & delete — insert only
}
