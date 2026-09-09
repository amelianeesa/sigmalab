<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('library_documents')->onDelete('cascade');
            $table->string('version_number');
            $table->integer('revisi_ke')->default(0);
            $table->string('judul');
            $table->string('file_path');
            $table->string('file_name');
            $table->text('catatan_revisi')->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->date('tanggal_berlaku')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('users_id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_document_versions');
    }
};
