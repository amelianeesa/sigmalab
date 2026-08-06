<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait ChecksDomainAccess
{
    /**
     * TODO: Ganti isi method ini kalau sistem hak_akses (RBAC granular full/tambah_ubah/lihat) 
     * milik Orang 3 sudah selesai -- semua Policy yang pakai trait ini otomatis ikut ter-update, 
     * tidak perlu edit satu-satu file Policy.
     */
    public function hasFullAccess(User $user, array $allowedRoles): bool
    {
        if (!$user->role) {
            return false;
        }

        return in_array($user->role->nama_role, $allowedRoles);
    }
}
