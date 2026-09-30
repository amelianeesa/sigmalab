<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sampel_inhouse', function (Blueprint $table) {
            $table->text('akar_masalah')->nullable()->after('status');
            $table->text('tindakan_perbaikan')->nullable()->after('akar_masalah');
            $table->dateTime('tanggal_investigasi')->nullable()->after('tindakan_perbaikan');
            $table->unsignedBigInteger('diinvestigasi_oleh')->nullable()->after('tanggal_investigasi');
        });
    }

    public function down(): void
    {
        Schema::table('sampel_inhouse', function (Blueprint $table) {
            $table->dropColumn([
                'akar_masalah', 
                'tindakan_perbaikan', 
                'tanggal_investigasi', 
                'diinvestigasi_oleh'
            ]);
        });
    }
};