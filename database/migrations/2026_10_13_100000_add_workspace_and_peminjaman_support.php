<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tahap 1 fitur Non-Lab. Semua perubahan bersifat TAMBAHAN dengan default 'LAB',
 * jadi user & data lama tidak berubah perilakunya.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1) Workspace pada user: LAB (default) | NON_LAB | ALL
        Schema::table('users', function (Blueprint $table) {
            $table->string('workspace', 10)->default('LAB')->after('role_id');
        });

        // 2) Data yang dipisah per workspace
        foreach (['alat', 'barang', 'permintaan_pengadaan'] as $tbl) {
            Schema::table($tbl, function (Blueprint $table) {
                $table->string('workspace', 10)->default('LAB')->index();
            });
        }

        // 3) Alat: sub alat + penanda boleh dipinjam
        Schema::table('alat', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('alat_id')->index();
            $table->boolean('dapat_dipinjam')->default(false);
        });

        // 4) Notifikasi: tambah jenis 'peminjaman'
        DB::statement("ALTER TABLE notifikasi MODIFY COLUMN jenis_notifikasi ENUM('kalibrasi','qc','stok','sertifikasi','perbaikan','peminjaman') NOT NULL");

        // 5) Tabel peminjaman
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id('peminjaman_id');
            $table->string('kode', 30)->unique();
            $table->unsignedBigInteger('peminjam_id');
            $table->string('tujuan', 255);
            $table->date('tgl_pinjam');
            $table->date('tgl_rencana_kembali');
            $table->date('tgl_kembali_aktual')->nullable();
            $table->string('status', 20)->default('diajukan')->index(); // diajukan|disetujui|dipinjam|dikembalikan|ditolak|dibatalkan
            $table->unsignedBigInteger('disetujui_oleh')->nullable();
            $table->timestamp('tgl_keputusan')->nullable();
            $table->text('catatan_approval')->nullable();
            $table->string('workspace', 10)->default('NON_LAB')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('peminjam_id')->references('users_id')->on('users');
            $table->foreign('disetujui_oleh')->references('users_id')->on('users');
        });

        Schema::create('peminjaman_item', function (Blueprint $table) {
            $table->id('peminjaman_item_id');
            $table->unsignedBigInteger('peminjaman_id');
            $table->unsignedBigInteger('alat_id');
            $table->string('kondisi_keluar', 20)->default('baik');
            $table->string('kondisi_kembali', 20)->nullable();
            $table->text('catatan_kembali')->nullable();
            $table->timestamps();

            $table->foreign('peminjaman_id')->references('peminjaman_id')->on('peminjaman')->cascadeOnDelete();
            $table->foreign('alat_id')->references('alat_id')->on('alat');
        });

        // 6) Seed: role Non-Lab, modul peminjaman, hak akses
        $now = now();

        if (!DB::table('roles')->where('nama_role', 'Non-Lab')->exists()) {
            DB::table('roles')->insert(['nama_role' => 'Non-Lab', 'created_at' => $now, 'updated_at' => $now]);
        }

        if (!DB::table('modul')->where('kode_modul', 'peminjaman')->exists()) {
            DB::table('modul')->insert([
                'kode_modul' => 'peminjaman', 'nama_modul' => 'Peminjaman Alat',
                'urutan' => 0, 'status_aktif' => 1, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        $modulId = DB::table('modul')->where('kode_modul', 'peminjaman')->value('modul_id');
        $akses = [
            'Non-Lab' => 'tambah_ubah',
            'GA' => 'full',
            'Admin Aplikasi' => 'full',
            'HR' => 'lihat',
            'Kabid Dukungan Bisnis' => 'lihat',
            'Kabid Inspeksi dan Solusi Perdagangan' => 'lihat',
        ];
        foreach ($akses as $namaRole => $level) {
            $roleId = DB::table('roles')->where('nama_role', $namaRole)->value('roles_id');
            if ($roleId && !DB::table('hak_akses')->where('role_id', $roleId)->where('modul_id', $modulId)->exists()) {
                DB::table('hak_akses')->insert([
                    'role_id' => $roleId, 'modul_id' => $modulId, 'level_akses' => $level,
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }

        // 7) Role lintas workspace dibuat 'ALL' (bisa diubah per user di Kelola User)
        DB::table('users')
            ->whereIn('role_id', DB::table('roles')->whereIn('nama_role', [
                'HR', 'GA', 'Admin Aplikasi', 'Kabid Dukungan Bisnis', 'Kabid Inspeksi dan Solusi Perdagangan',
            ])->pluck('roles_id'))
            ->update(['workspace' => 'ALL']);

        \Illuminate\Support\Facades\Cache::forget('hak_akses_matrix');
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_item');
        Schema::dropIfExists('peminjaman');

        Schema::table('alat', function (Blueprint $table) {
            $table->dropColumn(['parent_id', 'dapat_dipinjam']);
        });
        foreach (['alat', 'barang', 'permintaan_pengadaan'] as $tbl) {
            Schema::table($tbl, function (Blueprint $table) {
                $table->dropColumn('workspace');
            });
        }
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('workspace');
        });

        DB::statement("ALTER TABLE notifikasi MODIFY COLUMN jenis_notifikasi ENUM('kalibrasi','qc','stok','sertifikasi','perbaikan') NOT NULL");
        \Illuminate\Support\Facades\Cache::forget('hak_akses_matrix');
    }
};