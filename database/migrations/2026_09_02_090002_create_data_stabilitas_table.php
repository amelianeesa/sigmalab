<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_stabilitas', function (Blueprint $table) {
            $table->id('data_stabilitas_id');
            $table->unsignedBigInteger('sampel_inhouse_parameter_id');
            $table->integer('nomor_pengujian'); // urutan tes stabilitas (misal: 1, 2, 3...)
            $table->integer('nomor_botol_fisik')->nullable(); // botol mana yang diambil
            
            $table->json('data_mentah')->nullable(); 
            $table->decimal('nilai_d1', 12, 6)->nullable();
            $table->decimal('nilai_d2', 12, 6)->nullable();
            $table->decimal('mean_pengujian', 12, 6)->nullable(); 
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('sampel_inhouse_parameter_id', 'fk_stabilitas_param')->references('id')->on('sampel_inhouse_parameter')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_stabilitas');
    }
};
