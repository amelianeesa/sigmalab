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
        Schema::table('qc_uji_bandings', function (Blueprint $table) {
            $table->string('status')->default('completed')->after('id');
            $table->json('draft_data')->nullable()->after('status');
            
            // Make existing columns nullable since drafts might not have them yet
            $table->string('nama_program')->nullable()->change();
            $table->string('penyelenggara')->nullable()->change();
            $table->date('tanggal_terima')->nullable()->change();
            $table->date('tanggal_uji')->nullable()->change();
            $table->string('kode_sampel')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qc_uji_bandings', function (Blueprint $table) {
            $table->string('status')->default('completed')->after('id');
            $table->json('draft_data')->nullable()->after('status');
            
            // Make existing columns nullable since drafts might not have them yet
            $table->string('nama_program')->nullable()->change();
            $table->string('penyelenggara')->nullable()->change();
            $table->date('tanggal_terima')->nullable()->change();
            $table->date('tanggal_uji')->nullable()->change();
            $table->string('kode_sampel')->nullable()->change();
        });
    }
};
