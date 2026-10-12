<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_verifikasi_teknis', function (Blueprint $table) {
            $table->enum('status_investigasi', ['aman', 'menunggu_investigasi', 'selesai_investigasi'])
                  ->default('aman')
                  ->after('status_evaluasi');
            $table->string('akar_masalah')->nullable()->after('status_investigasi');
            $table->text('catatan_investigasi')->nullable()->after('akar_masalah');
            $table->text('tindakan_perbaikan')->nullable()->after('catatan_investigasi');
        });
    }

    public function down(): void
    {
        Schema::table('crm_verifikasi_teknis', function (Blueprint $table) {
            $table->dropColumn(['status_investigasi', 'akar_masalah', 'catatan_investigasi', 'tindakan_perbaikan']);
        });
    }
};