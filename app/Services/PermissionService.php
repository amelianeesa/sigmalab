<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    protected const LEVELS = [
        'lihat' => 1,
        'tambah_ubah' => 2,
        'full' => 3,
    ];

    /**
     * Check if user has access to a specific module at a minimum level.
     */
    public function userHasAccess(User $user, string $kodeModul, string $minLevel = 'lihat'): bool
    {
        if (!$user->role_id) {
            return false;
        }

        $matrix = $this->getAccessMatrix();

        // Check if role has access to this module
        if (!isset($matrix[$user->role_id][$kodeModul])) {
            return false;
        }

        $userLevel = $matrix[$user->role_id][$kodeModul];

        $userLevelValue = self::LEVELS[$userLevel] ?? 0;
        $minLevelValue = self::LEVELS[$minLevel] ?? 99; // Default to high value if unknown

        return $userLevelValue >= $minLevelValue;
    }

    /**
     * Get the complete access matrix, cached permanently.
     * Format: [role_id][kode_modul] => level_akses
     */
    protected function getAccessMatrix(): array
    {
        return Cache::rememberForever('hak_akses_matrix', function () {
            $aksesList = DB::table('hak_akses')
                ->join('modul', 'hak_akses.modul_id', '=', 'modul.modul_id')
                ->select('hak_akses.role_id', 'modul.kode_modul', 'hak_akses.level_akses')
                ->get();

            $matrix = [];
            foreach ($aksesList as $akses) {
                $matrix[$akses->role_id][$akses->kode_modul] = $akses->level_akses;
            }

            return $matrix;
        });
    }
}
