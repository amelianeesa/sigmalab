<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Mengirim notifikasi ke daftar role tertentu.
     *
     * @param string $pesan Pesan notifikasi
     * @param string $jenis_notifikasi Tipe notifikasi (default: 'qc')
     * @param array $roles Array dari nama role, e.g. ['analis', 'koordinator_lab']
     * @return void
     */
    public function notifyRoles(string $pesan, string $jenis_notifikasi = 'qc', array $roles = [], array $excludeUserIds = []): void
    {
        if (empty($roles)) {
            return;
        }

        $query = User::whereHas('role', function ($q) use ($roles) {
            $q->whereIn('nama_role', $roles);
        })->where('status_aktif', true);

        if (!empty($excludeUserIds)) {
            $query->whereNotIn('users_id', $excludeUserIds);
        }

        $targetUsers = $query->pluck('users_id');

        $notifData = $targetUsers->map(function ($userId) use ($pesan, $jenis_notifikasi) {
            return [
                'users_id'          => $userId,
                'jenis_notifikasi'  => $jenis_notifikasi,
                'pesan'             => $pesan,
                'is_read'           => false,
                'created_at'        => now(),
            ];
        })->toArray();

        if (!empty($notifData)) {
            DB::table('notifikasi')->insert($notifData);
        }
    }
}
