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

    public function __construct()
    {
        $this->kodeModul = 'tindak_lanjut';
    }

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
        return $this->hasFullAccess($user, 'tambah_ubah');
    }

    // Tidak ada update & delete — insert only
}
