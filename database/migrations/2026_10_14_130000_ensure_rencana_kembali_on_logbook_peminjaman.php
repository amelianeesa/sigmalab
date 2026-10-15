<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('logbook_peminjaman', 'tanggal_kembali')) {
            Schema::table('logbook_peminjaman', function (Blueprint $table) {
                $table->date('tanggal_kembali')->nullable()->after('tanggal');
            });
        }

        if (!Schema::hasColumn('logbook_peminjaman', 'rencana_kembali')) {
            Schema::table('logbook_peminjaman', function (Blueprint $table) {
                $table->date('rencana_kembali')->nullable()->after('tanggal');
            });

            // Data lama yang belum dikembalikan: isi tanggal_kembali sebenarnya adalah rencana kembali.
            DB::table('logbook_peminjaman')
                ->where('status', '!=', 'dikembalikan')
                ->whereNotNull('tanggal_kembali')
                ->update([
                    'rencana_kembali' => DB::raw('tanggal_kembali'),
                    'tanggal_kembali' => null,
                ]);
        }
    }

    public function down(): void
    {
    }
};