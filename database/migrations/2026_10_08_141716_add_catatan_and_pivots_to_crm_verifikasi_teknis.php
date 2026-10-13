<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan kolom catatan di tabel utama
        Schema::table('crm_verifikasi_teknis', function (Blueprint $table) {
            if (!Schema::hasColumn('crm_verifikasi_teknis', 'catatan')) {
                $table->text('catatan')->nullable()->after('status_evaluasi');
            }
        });

        // 2. Pivot Personil / Analis
        Schema::create('crm_verif_teknis_personil', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crm_verifikasi_teknis_id');
            $table->unsignedBigInteger('personil_id');
            $table->string('peran')->nullable(); // misal: "Analis Utama", "Pendamping"
            $table->timestamps();
        });

        // 3. Pivot Alat
        Schema::create('crm_verif_teknis_alat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crm_verifikasi_teknis_id');
            $table->unsignedBigInteger('alat_id');
            $table->timestamps();
        });

        // 4. Pivot Bahan / Barang
        Schema::create('crm_verif_teknis_bahan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crm_verifikasi_teknis_id');
            $table->unsignedBigInteger('barang_id');
            $table->integer('qty')->default(1); // jumlah pemakaian
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_verif_teknis_bahan');
        Schema::dropIfExists('crm_verif_teknis_alat');
        Schema::dropIfExists('crm_verif_teknis_personil');
        
        Schema::table('crm_verifikasi_teknis', function (Blueprint $table) {
            if (Schema::hasColumn('crm_verifikasi_teknis', 'catatan')) {
                $table->dropColumn('catatan');
            }
        });
    }
};