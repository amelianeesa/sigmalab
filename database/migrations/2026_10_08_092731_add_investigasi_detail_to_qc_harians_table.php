<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qc_harians', function (Blueprint $table) {
            $table->string('akar_masalah')->nullable()->after('catatan_investigasi');
            $table->text('tindakan_perbaikan')->nullable()->after('akar_masalah');
            $table->boolean('is_retest')->default(false)->after('tindakan_perbaikan');
        });
    }

    public function down(): void
    {
        Schema::table('qc_harians', function (Blueprint $table) {
            $table->dropColumn(['akar_masalah', 'tindakan_perbaikan', 'is_retest']);
        });
    }
};