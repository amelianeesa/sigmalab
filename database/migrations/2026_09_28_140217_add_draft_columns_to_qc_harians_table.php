<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qc_harians', function (Blueprint $table) {
            // Kolom penanda apakah ini data draft atau sudah selesai dievaluasi
            $table->string('status_pengujian')->default('selesai')->after('status_investigasi');
            // ID kelompok untuk mengikat beberapa parameter dalam 1 kali input draft
            $table->string('draft_group_id')->nullable()->after('status_pengujian');
        });
    }

    public function down(): void
    {
        Schema::table('qc_harians', function (Blueprint $table) {
            $table->dropColumn(['status_pengujian', 'draft_group_id']);
        });
    }
};