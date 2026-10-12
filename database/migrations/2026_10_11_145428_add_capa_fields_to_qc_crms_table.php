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
        Schema::table('qc_crms', function (Blueprint $table) {
            $table->string('akar_masalah')->nullable()->after('status_investigasi');
            $table->text('tindakan_perbaikan')->nullable()->after('catatan_investigasi');
        });
    }

    public function down(): void
    {
        Schema::table('qc_crms', function (Blueprint $table) {
            $table->dropColumn(['akar_masalah', 'tindakan_perbaikan']);
        });
    }
};
