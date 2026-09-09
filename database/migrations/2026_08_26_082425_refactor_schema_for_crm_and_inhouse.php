<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('parameter_uji', function (Blueprint $table) {
            if (Schema::hasColumn('parameter_uji', 'jenis_kontrol')) {
                $table->dropColumn('jenis_kontrol');
            }
            $table->decimal('cert_value', 10, 4)->nullable()->after('sd');
            $table->decimal('cert_u', 10, 4)->nullable()->after('cert_value');
            $table->decimal('recovery_batas_bawah', 10, 4)->nullable()->after('cert_u');
            $table->decimal('recovery_batas_atas', 10, 4)->nullable()->after('recovery_batas_bawah');
        });

        Schema::table('kegiatan', function (Blueprint $table) {
            if (Schema::hasColumn('kegiatan', 'jenis_kegiatan')) {
                $table->string('jenis_kegiatan')->nullable()->change();
            }
            $table->enum('metode_verifikasi', ['in_house', 'crm'])->nullable()->after('jenis_kegiatan');
        });

        Schema::table('hasil_uji', function (Blueprint $table) {
            $table->decimal('recovery_percentage', 10, 4)->nullable()->after('z_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_uji', function (Blueprint $table) {
            $table->dropColumn('recovery_percentage');
        });

        Schema::table('kegiatan', function (Blueprint $table) {
            $table->dropColumn('metode_verifikasi');
        });

        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->dropColumn(['cert_value', 'cert_u', 'recovery_batas_bawah', 'recovery_batas_atas']);
            $table->enum('jenis_kontrol', ['in_house', 'crm'])->default('in_house');
        });
    }
};
