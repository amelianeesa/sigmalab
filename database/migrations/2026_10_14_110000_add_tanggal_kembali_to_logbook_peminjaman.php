<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Memisahkan RENCANA kembali (diisi saat mengajukan) dari TANGGAL KEMBALI sebenarnya.
 * Data uji coba lama: isi tanggal_kembali dipindah menjadi rencana_kembali,
 * dan tanggal_kembali dikosongkan karena sebelumnya belum dibedakan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logbook_peminjaman', function (Blueprint $table) {
            $table->date('rencana_kembali')->nullable()->after('tanggal');
        });

        DB::table('logbook_peminjaman')->whereNotNull('tanggal_kembali')->update([
            'rencana_kembali' => DB::raw('tanggal_kembali'),
            'tanggal_kembali' => null,
        ]);
    }

    public function down(): void
    {
        Schema::table('logbook_peminjaman', function (Blueprint $table) {
            $table->dropColumn('rencana_kembali');
        });
    }
};