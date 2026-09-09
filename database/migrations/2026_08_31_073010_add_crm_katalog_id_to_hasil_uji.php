<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hasil_uji', function (Blueprint $table) {
            $table->unsignedBigInteger('crm_katalog_id')->nullable()->after('status_berketerimaan');
            $table->foreign('crm_katalog_id')->references('id')->on('crm_katalog')->onDelete('set null');
        });

        // Retroactive script: Copy crm_katalog_id from kegiatan to hasil_uji where jenis_kontrol = 'crm'
        DB::table('hasil_uji')
            ->join('kegiatan', 'hasil_uji.kegiatan_id', '=', 'kegiatan.kegiatan_id')
            ->where('hasil_uji.jenis_kontrol', 'crm')
            ->whereNotNull('kegiatan.crm_katalog_id')
            ->update([
                'hasil_uji.crm_katalog_id' => DB::raw('kegiatan.crm_katalog_id')
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_uji', function (Blueprint $table) {
            $table->dropForeign(['crm_katalog_id']);
            $table->dropColumn('crm_katalog_id');
        });
    }
};
