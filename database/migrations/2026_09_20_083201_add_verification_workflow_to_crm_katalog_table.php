<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_katalog', function (Blueprint $table) {
            $table->string('coa_file')->nullable()->after('tanggal_expired');

            $table->enum('status', [
                'menunggu_verifikasi',
                'menunggu_verifikasi_teknis',
                'aktif',
                'ditolak',
            ])->default('menunggu_verifikasi')->after('coa_file');

            $table->json('verifikasi_administratif_checklist')->nullable()->after('status');
            $table->text('verifikasi_administratif_catatan')->nullable()->after('verifikasi_administratif_checklist');
            $table->unsignedBigInteger('verifikasi_administratif_oleh')->nullable()->after('verifikasi_administratif_catatan');
            $table->timestamp('verifikasi_administratif_at')->nullable()->after('verifikasi_administratif_oleh');

            $table->foreign('verifikasi_administratif_oleh')->references('personil_id')->on('personil')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('crm_katalog', function (Blueprint $table) {
            $table->dropForeign(['verifikasi_administratif_oleh']);
            $table->dropColumn([
                'coa_file',
                'status',
                'verifikasi_administratif_checklist',
                'verifikasi_administratif_catatan',
                'verifikasi_administratif_oleh',
                'verifikasi_administratif_at',
            ]);
        });
    }
};