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
        Schema::create('hasil_uji', function (Blueprint $table) {
            $table->id('hasil_uji_id');
            $table->unsignedBigInteger('kegiatan_id');
            $table->unsignedBigInteger('parameter_uji_id');
            $table->decimal('nilai_hasil', 12, 4);
            $table->enum('status_berketerimaan', ['inlier', 'outlier']);
            $table->unsignedBigInteger('diinput_oleh');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('kegiatan_id')->references('kegiatan_id')->on('kegiatan');
            $table->foreign('parameter_uji_id')->references('parameter_uji_id')->on('parameter_uji');
            $table->foreign('diinput_oleh')->references('users_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_uji');
    }
};
