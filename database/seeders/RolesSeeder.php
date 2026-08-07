<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Admin Lab',
            'Koordinator Laboratorium',
            'Analis',
            'HR & GA',
            'Kabid Inspeksi dan Solusi Perdagangan',
            'Kabid Dukungan Bisnis',
            'Admin Aplikasi'
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['nama_role' => $role]
            );
        }
    }
}
