<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Penanda bahwa tanggal dikembalikan sudah dikoreksi Koordinator.
 * Koreksi hanya diizinkan sekali; setelah terisi, tombol Koreksi hilang dan server menolak koreksi lagi.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logbook_peminjaman', function (Blueprint $table) {
            $table->timestamp('dikoreksi_pada')->nullable()->after('tgl_keputusan');
        });
    }

    public function down(): void
    {
        Schema::table('logbook_peminjaman', function (Blueprint $table) {
            $table->dropColumn('dikoreksi_pada');
        });
    }
};