<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Personil;
use App\Models\Kegiatan;
use App\Models\ParameterUji;
use App\Models\HasilUji;
use App\Models\RiwayatTindakLanjut;
use App\Enums\PeranPengguna;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =============================================
        // 1. ROLES
        // =============================================
        $rolesToInsert = [
            PeranPengguna::ANALIS->value,
            PeranPengguna::KOORDINATOR_LAB->value,
            PeranPengguna::ADMIN_LAB->value,
            PeranPengguna::DEVELOPER->value,
        ];

        foreach ($rolesToInsert as $roleName) {
            Role::firstOrCreate(['nama_role' => $roleName]);
        }

        // =============================================
        // 2. USERS (untuk Role Switcher)
        // =============================================
        $usersToInsert = [
            ['username' => 'analis_tester', 'email' => 'analis@test.com', 'role' => PeranPengguna::ANALIS->value],
            ['username' => 'koordinator_tester', 'email' => 'koor@test.com', 'role' => PeranPengguna::KOORDINATOR_LAB->value],
            ['username' => 'adminlab_tester', 'email' => 'adminlab@test.com', 'role' => PeranPengguna::ADMIN_LAB->value],
            ['username' => 'developer_tester', 'email' => 'dev@test.com', 'role' => PeranPengguna::DEVELOPER->value],
        ];

        foreach ($usersToInsert as $u) {
            $role = Role::where('nama_role', $u['role'])->first();
            if ($role) {
                User::firstOrCreate(
                    ['username' => $u['username']],
                    [
                        'email' => $u['email'],
                        'password' => Hash::make('password'),
                        'role_id' => $role->roles_id,
                        'status_aktif' => true,
                    ]
                );
            }
        }

        // =============================================
        // 3. PERSONIL (dummy untuk dipakai di kegiatan)
        // =============================================
        $personil1 = Personil::firstOrCreate(
            ['no_induk' => 'P-001'],
            ['nama' => 'Budi Santoso', 'jabatan' => 'Analis Kimia', 'unit_kerja' => 'Lab Kimia', 'status_aktif' => true]
        );
        $personil2 = Personil::firstOrCreate(
            ['no_induk' => 'P-002'],
            ['nama' => 'Siti Rahayu', 'jabatan' => 'Analis Fisika', 'unit_kerja' => 'Lab Fisika', 'status_aktif' => true]
        );
        $personil3 = Personil::firstOrCreate(
            ['no_induk' => 'P-003'],
            ['nama' => 'Andi Wijaya', 'jabatan' => 'Teknisi Lab', 'unit_kerja' => 'Lab Kimia', 'status_aktif' => true]
        );

        // =============================================
        // 4. PARAMETER UJI
        // =============================================
        $paramKadarAir = ParameterUji::firstOrCreate(
            ['nama_parameter' => 'Kadar Air'],
            [
                'satuan' => '%', 'nilai_acuan' => 10.00,
                'batas_bawah' => 0.00, 'batas_atas' => 15.00,
                'metode_kriteria' => 'SNI 01-2891-1992',
                'rumus_kalkulasi' => '(W1 - W2) / W * 100',
                'status_aktif' => true,
            ]
        );
        $paramPh = ParameterUji::firstOrCreate(
            ['nama_parameter' => 'pH'],
            [
                'satuan' => '-', 'nilai_acuan' => 7.00,
                'batas_bawah' => 6.50, 'batas_atas' => 8.50,
                'metode_kriteria' => 'SNI 06-6989.11-2004',
                'rumus_kalkulasi' => null,
                'status_aktif' => true,
            ]
        );
        $paramSulfur = ParameterUji::firstOrCreate(
            ['nama_parameter' => 'Total Sulfur'],
            [
                'satuan' => '%', 'nilai_acuan' => 0.50,
                'batas_bawah' => 0.00, 'batas_atas' => 1.00,
                'metode_kriteria' => 'ASTM D4294',
                'rumus_kalkulasi' => null,
                'status_aktif' => true,
            ]
        );

        // =============================================
        // 5. KEGIATAN + ATTACH ALAT & PERSONIL
        // =============================================
        $user = User::where('username', 'koordinator_tester')->first();
        $userId = $user ? $user->users_id : 1;

        $kegiatan1 = Kegiatan::firstOrCreate(
            ['kode_sampel' => 'SMP-2026-001'],
            [
                'jenis_kegiatan' => 'pengujian',
                'tanggal_kegiatan' => '2026-08-01',
                'status_kegiatan' => 'berjalan',
                'dibuat_oleh' => $userId,
            ]
        );

        $kegiatan2 = Kegiatan::firstOrCreate(
            ['kode_sampel' => 'SMP-2026-002'],
            [
                'jenis_kegiatan' => 'kalibrasi',
                'tanggal_kegiatan' => '2026-08-05',
                'status_kegiatan' => 'draft',
                'dibuat_oleh' => $userId,
            ]
        );

        // Attach personil
        if ($kegiatan1->personilTerlibat()->count() === 0) {
            $kegiatan1->personilTerlibat()->attach([
                $personil1->personil_id => ['peran' => 'Analis Utama'],
                $personil2->personil_id => ['peran' => 'Analis Pendamping'],
            ]);
        }

        if ($kegiatan2->personilTerlibat()->count() === 0) {
            $kegiatan2->personilTerlibat()->attach([
                $personil3->personil_id => ['peran' => 'Teknisi Kalibrasi'],
            ]);
        }

        // =============================================
        // 6. HASIL UJI (2 inlier, 1 outlier)
        // =============================================
        $hasilUji1 = HasilUji::firstOrCreate(
            ['kegiatan_id' => $kegiatan1->kegiatan_id, 'parameter_uji_id' => $paramKadarAir->parameter_uji_id],
            ['nilai_hasil' => 8.50, 'status_berketerimaan' => 'inlier', 'diinput_oleh' => $userId, 'created_at' => now()]
        );
        $hasilUji2 = HasilUji::firstOrCreate(
            ['kegiatan_id' => $kegiatan1->kegiatan_id, 'parameter_uji_id' => $paramPh->parameter_uji_id],
            ['nilai_hasil' => 7.20, 'status_berketerimaan' => 'inlier', 'diinput_oleh' => $userId, 'created_at' => now()]
        );
        $hasilUjiOutlier = HasilUji::firstOrCreate(
            ['kegiatan_id' => $kegiatan1->kegiatan_id, 'parameter_uji_id' => $paramSulfur->parameter_uji_id],
            ['nilai_hasil' => 1.85, 'status_berketerimaan' => 'outlier', 'diinput_oleh' => $userId, 'created_at' => now()]
        );

        // =============================================
        // 7. RIWAYAT TINDAK LANJUT (untuk outlier)
        // =============================================
        RiwayatTindakLanjut::firstOrCreate(
            ['hasil_uji_id' => $hasilUjiOutlier->hasil_uji_id, 'ditindaklanjuti_oleh' => $userId],
            [
                'status_tindak_lanjut' => 'dalam_investigasi',
                'catatan_investigasi' => 'Nilai Total Sulfur melebihi batas atas (1.00%). Sedang dilakukan pengecekan ulang terhadap instrumen dan sampel.',
                'created_at' => now(),
            ]
        );

        $this->command->info('DummyDataSeeder berhasil dijalankan! (Roles, Users, Personil, Parameter, Kegiatan, Hasil Uji, Tindak Lanjut)');
    }
}
