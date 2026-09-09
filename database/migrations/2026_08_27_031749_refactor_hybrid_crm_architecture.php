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
        // 1. Create crm_katalog and crm_sertifikat tables
        Schema::create('crm_katalog', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk', 150);
            $table->string('nomor_lot', 50)->unique();
            $table->date('tanggal_expired')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('crm_sertifikat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crm_katalog_id')->constrained('crm_katalog')->onDelete('cascade');
            $table->unsignedBigInteger('parameter_uji_id');
            $table->foreign('parameter_uji_id')->references('parameter_uji_id')->on('parameter_uji')->onDelete('cascade');
            $table->decimal('cert_value', 12, 4);
            $table->decimal('cert_u', 12, 4);
            $table->timestamps();
            
            // A parameter can only appear once in a specific catalog/lot
            $table->unique(['crm_katalog_id', 'parameter_uji_id']);
        });

        // 2. Modify parameter_uji
        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->dropColumn(['cert_value', 'cert_u', 'recovery_batas_bawah', 'recovery_batas_atas']);
        });

        // 3. Modify kegiatan
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->dropColumn('metode_verifikasi');
            $table->boolean('is_inhouse')->default(true)->after('tanggal_kegiatan');
            $table->boolean('is_crm')->default(false)->after('is_inhouse');
            $table->foreignId('crm_katalog_id')->nullable()->constrained('crm_katalog')->nullOnDelete()->after('is_crm');
        });

        // 4. Modify hasil_uji
        Schema::table('hasil_uji', function (Blueprint $table) {
            $table->dropColumn('recovery_percentage');
            $table->enum('jenis_kontrol', ['in_house', 'crm'])->default('in_house')->after('parameter_uji_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_uji', function (Blueprint $table) {
            $table->dropColumn('jenis_kontrol');
            $table->decimal('recovery_percentage', 8, 2)->nullable();
        });

        Schema::table('kegiatan', function (Blueprint $table) {
            $table->dropForeign(['crm_katalog_id']);
            $table->dropColumn(['is_inhouse', 'is_crm', 'crm_katalog_id']);
            $table->enum('metode_verifikasi', ['in_house', 'crm'])->default('in_house');
        });

        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->decimal('cert_value', 12, 4)->nullable();
            $table->decimal('cert_u', 12, 4)->nullable();
            $table->decimal('recovery_batas_bawah', 8, 2)->nullable();
            $table->decimal('recovery_batas_atas', 8, 2)->nullable();
        });

        Schema::dropIfExists('crm_sertifikat');
        Schema::dropIfExists('crm_katalog');
    }
};