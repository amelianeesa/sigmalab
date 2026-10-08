<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qc_crms', function (Blueprint $table) {
            // Menambahkan 2 kolom baru tepat setelah kolom status_evaluasi
            $table->enum('status_investigasi', ['aman', 'menunggu_investigasi', 'selesai_investigasi'])
                  ->default('aman')
                  ->after('status_evaluasi');
                  
            $table->text('catatan_investigasi')->nullable()->after('status_investigasi');
        });
    }

    public function down(): void
    {
        Schema::table('qc_crms', function (Blueprint $table) {
            // Menghapus kolom jika sewaktu-waktu dilakukan rollback
            $table->dropColumn(['status_investigasi', 'catatan_investigasi']);
        });
    }
};