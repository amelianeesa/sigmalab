<?php

namespace App\Policies;

use App\Models\Kegiatan;
use App\Models\User;
use App\Enums\PeranPengguna;
use App\Policies\Concerns\ChecksDomainAccess;
use Illuminate\Auth\Access\HandlesAuthorization;

class KegiatanPolicy
{
    use HandlesAuthorization, ChecksDomainAccess;

    public function __construct()
    {
        $this->kodeModul = 'proses_hasil';
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Kegiatan $kegiatan): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $this->hasFullAccess($user, 'tambah_ubah');
    }

    public function update(User $user, Kegiatan $kegiatan): bool
    {
        if (in_array($kegiatan->status_kegiatan, ['selesai', 'dibatalkan'])) {
            return false;
        }

        return $this->hasFullAccess($user, 'tambah_ubah');
    }

    public function delete(User $user, Kegiatan $kegiatan): bool
    {
        // Hanya boleh dihapus jika masih draft dan belum ada hasil uji sama sekali
        if ($kegiatan->status_kegiatan !== 'draft' || $kegiatan->hasilUji()->count() > 0) {
            return false;
        }

        return $this->hasFullAccess($user, 'tambah_ubah');
    }
}
