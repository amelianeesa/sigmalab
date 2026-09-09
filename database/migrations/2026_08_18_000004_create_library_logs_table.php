<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('library_documents')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action');
            $table->text('keterangan')->nullable();
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('users_id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_logs');
    }
};
