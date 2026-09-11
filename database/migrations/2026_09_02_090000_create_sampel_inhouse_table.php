<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Angka Acak
        Schema::create('tabel_angka_acak', function (Blueprint $table) {
            $table->id();
            $table->integer('urutan');
            $table->integer('nomor_botol');
            $table->string('keterangan')->nullable();
        });

        // 2. Tabel Sampel Inhouse (Header)
        Schema::create('sampel_inhouse', function (Blueprint $table) {
            $table->id('sampel_inhouse_id');
            $table->string('nama_sampel', 150);
            $table->enum('jenis_batubara', ['lignite', 'sub_bituminous', 'bituminous', 'anthracite'])->nullable();
            $table->enum('metode_acuan', ['astm', 'iso'])->nullable();
            $table->string('kode_batch', 50)->nullable();
            $table->integer('jumlah_botol')->nullable();
            $table->integer('nomor_awal_botol')->default(1);
            $table->json('data_screening')->nullable();
            
            $table->json('data_pemilihan_sampel')->nullable();
            $table->json('data_equilibrium')->nullable();
            $table->boolean('bobot_konstan_tercapai')->default(false);
            $table->text('catatan_preparasi')->nullable();
            
            $table->enum('status', [
                'pemilihan_sampel', 'preparasi', 'uji_homogenitas', 'gagal_homogenitas', 
                'penetapan_target', 'uji_stabilitas', 'gagal_stabilitas', 'aktif', 'habis', 'investigasi'
            ])->default('pemilihan_sampel');

            $table->json('urutan_acak_instrumen')->nullable();

            $table->date('tanggal_pemilihan')->nullable();
            $table->date('tanggal_preparasi')->nullable();
            $table->date('tanggal_penetapan_target')->nullable();
            
            $table->unsignedBigInteger('dibuat_oleh');
            $table->unsignedBigInteger('dipreparasi_oleh')->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();

            $table->foreign('dibuat_oleh')->references('users_id')->on('users');
            $table->foreign('dipreparasi_oleh')->references('users_id')->on('users');
        });

        // 3. Tabel Sampel Inhouse Parameter (Detail)
        Schema::create('sampel_inhouse_parameter', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sampel_inhouse_id');
            $table->unsignedBigInteger('parameter_uji_id');
            $table->enum('status_parameter', ['draft', 'homogen', 'tidak_homogen', 'target_set', 'stabil', 'tidak_stabil'])->default('draft');
            
            $table->decimal('mean_global', 12, 6)->nullable();
            $table->decimal('sd_global', 12, 6)->nullable();
            $table->decimal('f_hitung', 12, 6)->nullable();
            $table->decimal('f_tabel', 12, 6)->nullable();
            
            $table->decimal('mean_target', 12, 6)->nullable();
            $table->decimal('sd_target', 12, 6)->nullable();
            
            $table->decimal('mean_stabilitas', 12, 6)->nullable();
            $table->decimal('sd_stabilitas', 12, 6)->nullable();
            $table->decimal('t_hitung', 12, 6)->nullable();
            $table->decimal('t_tabel', 12, 6)->nullable();
            
            $table->date('tanggal_homogenitas')->nullable();
            $table->date('tanggal_stabilitas')->nullable();

            $table->foreign('sampel_inhouse_id')->references('sampel_inhouse_id')->on('sampel_inhouse')->onDelete('cascade');
            $table->foreign('parameter_uji_id')->references('parameter_uji_id')->on('parameter_uji');
        });

        // 4. Tabel Penetapan Target
        Schema::create('data_penetapan_target', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sampel_inhouse_parameter_id');
            $table->integer('nomor_pengujian');
            $table->decimal('nilai_hasil', 12, 6)->nullable();
            $table->unsignedBigInteger('analis_id')->nullable();
            $table->date('tanggal_pengujian')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('sampel_inhouse_parameter_id', 'fk_target_param')->references('id')->on('sampel_inhouse_parameter')->onDelete('cascade');
            $table->foreign('analis_id')->references('users_id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_penetapan_target');
        Schema::dropIfExists('sampel_inhouse_parameter');
        Schema::dropIfExists('sampel_inhouse');
        Schema::dropIfExists('tabel_angka_acak');
    }
};
