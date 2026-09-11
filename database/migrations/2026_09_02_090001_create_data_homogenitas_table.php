<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_homogenitas', function (Blueprint $table) {
            $table->id('data_homogenitas_id');
            $table->unsignedBigInteger('sampel_inhouse_parameter_id');
            $table->integer('nomor_sampel'); // 1-10
            $table->integer('nomor_botol_fisik')->nullable(); // dari tabel acak
            $table->integer('urutan_instrumen_d1')->nullable();
            $table->integer('urutan_instrumen_d2')->nullable();
            
            $table->json('data_mentah')->nullable(); // {M1_D1, A_D1, M3_D1, M1_D2, A_D2, M3_D2, ...}
            $table->decimal('nilai_d1', 12, 6)->nullable(); // M% Dish 1 (calculated)
            $table->decimal('nilai_d2', 12, 6)->nullable(); // M% Dish 2 (calculated)
            $table->decimal('mean_sampel', 12, 6)->nullable(); // (D1+D2)/2
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('sampel_inhouse_parameter_id', 'fk_homogen_param')->references('id')->on('sampel_inhouse_parameter')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_homogenitas');
    }
};
