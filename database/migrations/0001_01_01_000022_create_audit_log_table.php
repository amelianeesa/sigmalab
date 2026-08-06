<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_log', function (Blueprint $table) {
            $table->id('audit_log_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('aksi', ['create', 'update', 'delete']);
            $table->string('entitas', 50);
            $table->unsignedBigInteger('entitas_id');
            $table->timestamp('waktu')->useCurrent();
            $table->text('nilai_sebelum')->nullable();
            $table->text('nilai_sesudah')->nullable();

            $table->foreign('user_id')->references('users_id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_log');
    }
};
