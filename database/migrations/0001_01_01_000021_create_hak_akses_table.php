<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hak_akses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('modul_id');
            $table->enum('level_akses', ['full', 'tambah_ubah', 'lihat']);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['role_id', 'modul_id']);
            $table->foreign('role_id')->references('roles_id')->on('roles')->cascadeOnDelete();
            $table->foreign('modul_id')->references('modul_id')->on('modul')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hak_akses');
    }
};
