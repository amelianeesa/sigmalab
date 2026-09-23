<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Mengubah kolom status ENUM agar mencakup status baru
        DB::statement("ALTER TABLE permintaan_pengadaan MODIFY COLUMN status ENUM('diajukan', 'menunggu_koordinator', 'menunggu_ga', 'disetujui', 'diproses', 'selesai', 'ditolak') NOT NULL DEFAULT 'menunggu_koordinator'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE permintaan_pengadaan MODIFY COLUMN status ENUM('diajukan', 'disetujui', 'diproses', 'selesai', 'ditolak') NOT NULL DEFAULT 'diajukan'");
    }
};