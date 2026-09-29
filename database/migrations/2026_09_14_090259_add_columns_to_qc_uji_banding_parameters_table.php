<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qc_uji_banding_parameters', function (Blueprint $table) {
            // Cek jika kolom alat_id belum ada, baru dibuat
            if (!Schema::hasColumn('qc_uji_banding_parameters', 'alat_id')) {
                $table->unsignedBigInteger('alat_id')->nullable();
            }
            
            // Cek jika kolom metode_uji belum ada, baru dibuat
            if (!Schema::hasColumn('qc_uji_banding_parameters', 'metode_uji')) {
                $table->string('metode_uji')->nullable();
            }
            
            // Cek jika kolom uncertainty_lab belum ada, baru dibuat
            if (!Schema::hasColumn('qc_uji_banding_parameters', 'uncertainty_lab')) {
                $table->decimal('uncertainty_lab', 10, 4)->nullable()->comment('Expanded uncertainty (U95%) dari Lab');
            }

            // Membangun Foreign Key dengan menunjuk ke nama tabel dan primary key yang BENAR
            $table->foreign('alat_id')
                  ->references('alat_id')
                  ->on('alat')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('qc_uji_banding_parameters', function (Blueprint $table) {
            $table->dropForeign(['alat_id']);
            $table->dropColumn(['alat_id', 'metode_uji', 'uncertainty_lab']);
        });
    }
};