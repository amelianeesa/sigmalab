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
        Schema::create('roles', function (Blueprint $table) {
            $table->id('roles_id');
            $table->string('nama_role', 50);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('modul', function (Blueprint $table) {
            $table->id('modul_id');
            $table->string('kode_modul', 50);
            $table->string('nama_modul', 100);
            $table->integer('urutan')->default(0);
            $table->boolean('status_aktif')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique('kode_modul');
        });

        Schema::create('personil', function (Blueprint $table) {
            $table->id('personil_id');
            $table->string('nama', 100);
            $table->string('jabatan', 100)->nullable();
            $table->string('unit_kerja', 100)->nullable();
            $table->string('no_induk', 50);
            $table->string('file_cv', 255)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique('no_induk');
        });

        Schema::create('kompetensi_personil', function (Blueprint $table) {
            $table->id('kompetensi_personil_id');
            $table->unsignedBigInteger('personil_id');
            $table->string('jenis_sertifikasi', 100);
            $table->string('no_sertifikasi', 100)->nullable();
            $table->string('file_sertifikat', 255)->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->date('tanggal_berakhir')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('personil_id')->references('personil_id')->on('personil')->cascadeOnDelete();
        });

        Schema::create('barang', function (Blueprint $table) {
            $table->id('barang_id');
            $table->string('nama_barang', 100);
            $table->string('satuan', 20);
            $table->string('kode_barang', 50);
            $table->decimal('minimal_stok', 12, 4)->default(0.0000);
            $table->enum('kondisi', ['baik', 'rusak'])->default('baik');
            $table->date('tgl_exp')->nullable();
            $table->decimal('harga_rata', 12, 4)->default(0.0000);
            $table->decimal('saldo_akhir', 12, 4)->default(0.0000);
            $table->timestamp('qr_dicetak_pada')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique('kode_barang');
        });

        Schema::create('alat', function (Blueprint $table) {
            $table->id('alat_id');
            $table->string('kode_alat', 50);
            $table->string('nama_alat', 100);
            $table->string('merk_tipe', 100)->nullable();
            $table->string('no_seri', 100)->nullable();
            $table->string('warna', 30)->nullable();
            $table->string('ukuran', 50)->nullable();
            $table->enum('kondisi_barang', ['baik', 'rusak'])->default('baik');
            $table->enum('status_barang', ['terpakai', 'idle'])->default('idle');
            $table->string('unit_kerja_pemilik', 100)->nullable();
            $table->timestamp('qr_dicetak_pada')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique('kode_alat');
        });

        Schema::create('parameter_uji', function (Blueprint $table) {
            $table->id('parameter_uji_id');
            $table->string('nama_parameter', 50);
            $table->string('satuan', 20);
            $table->decimal('nilai_acuan', 12, 4);
            $table->decimal('batas_bawah', 12, 4);
            $table->decimal('batas_atas', 12, 4);
            $table->string('metode_kriteria', 50)->nullable();
            $table->text('rumus_kalkulasi')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id('users_id');
            $table->unsignedBigInteger('personil_id')->nullable();
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->unsignedBigInteger('role_id');
            $table->boolean('status_aktif')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('personil_id')->references('personil_id')->on('personil')->nullOnDelete();
            $table->foreign('role_id')->references('roles_id')->on('roles');
        });

        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id('kegiatan_id');
            $table->enum('jenis_kegiatan', ['pengujian', 'kalibrasi']);
            $table->string('kode_sampel', 50)->nullable();
            $table->date('tanggal_kegiatan');
            $table->enum('status_kegiatan', ['draft', 'berjalan', 'selesai', 'dibatalkan'])->default('draft');
            $table->unsignedBigInteger('dibuat_oleh');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('dibuat_oleh')->references('users_id')->on('users');
        });

        Schema::create('kegiatan_alat', function (Blueprint $table) {
            $table->unsignedBigInteger('kegiatan_id');
            $table->unsignedBigInteger('alat_id');

            $table->primary(['kegiatan_id', 'alat_id']);
            $table->foreign('kegiatan_id')->references('kegiatan_id')->on('kegiatan')->cascadeOnDelete();
            $table->foreign('alat_id')->references('alat_id')->on('alat');
        });

        Schema::create('kegiatan_personil', function (Blueprint $table) {
            $table->unsignedBigInteger('kegiatan_id');
            $table->unsignedBigInteger('personil_id');
            $table->string('peran', 50);

            $table->primary(['kegiatan_id', 'personil_id']);
            $table->foreign('kegiatan_id')->references('kegiatan_id')->on('kegiatan')->cascadeOnDelete();
            $table->foreign('personil_id')->references('personil_id')->on('personil');
        });

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

        Schema::create('riwayat_kalibrasi', function (Blueprint $table) {
            $table->id('riwayat_kalibrasi_id');
            $table->unsignedBigInteger('alat_id');
            $table->enum('jenis_kalibrasi', ['internal', 'eksternal']);
            $table->string('no_sertifikat', 100)->nullable();
            $table->date('tgl_kalibrasi');
            $table->date('tgl_akhir');
            $table->string('lembaga_kalibrasi', 150)->nullable();
            $table->string('range_kapasitas', 100)->nullable();
            $table->string('faktor_koreksi', 100)->nullable();
            $table->enum('signifikan', ['ya', 'tidak'])->default('tidak');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('alat_id')->references('alat_id')->on('alat');
        });

        Schema::create('riwayat_pelatihan', function (Blueprint $table) {
            $table->id('riwayat_pelatihan_id');
            $table->unsignedBigInteger('personil_id');
            $table->string('nama_pelatihan', 150);
            $table->string('penyelenggara', 150)->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('file_sertifikat', 255)->nullable();
            $table->enum('status_pelaksanaan', ['direncanakan', 'berlangsung', 'selesai'])->default('direncanakan');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('personil_id')->references('personil_id')->on('personil')->cascadeOnDelete();
        });

        Schema::create('riwayat_tindak_lanjut', function (Blueprint $table) {
            $table->id('riwayat_tindak_lanjut_id');
            $table->unsignedBigInteger('hasil_uji_id');
            $table->enum('status_tindak_lanjut', ['belum_ditindaklanjuti', 'dalam_investigasi', 'selesai'])->default('belum_ditindaklanjuti');
            $table->text('catatan_investigasi')->nullable();
            $table->unsignedBigInteger('ditindaklanjuti_oleh');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('hasil_uji_id')->references('hasil_uji_id')->on('hasil_uji');
            $table->foreign('ditindaklanjuti_oleh')->references('users_id')->on('users');
        });

        Schema::create('permintaan_pengadaan', function (Blueprint $table) {
            $table->id('permintaan_id');
            $table->unsignedBigInteger('barang_id');
            $table->decimal('jumlah_diminta', 12, 4);
            $table->text('alasan')->nullable();
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak', 'diproses', 'selesai'])->default('diajukan');
            $table->unsignedBigInteger('diajukan_oleh');
            $table->unsignedBigInteger('disetujui_oleh')->nullable();
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_keputusan')->nullable();
            $table->text('catatan_approval')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('barang_id')->references('barang_id')->on('barang');
            $table->foreign('diajukan_oleh')->references('users_id')->on('users');
            $table->foreign('disetujui_oleh')->references('users_id')->on('users')->nullOnDelete();
        });

        Schema::create('transaksi_barang', function (Blueprint $table) {
            $table->id('transaksi_id');
            $table->unsignedBigInteger('barang_id');
            $table->unsignedBigInteger('kegiatan_id')->nullable();
            $table->decimal('jumlah_penerimaan', 12, 4)->default(0.0000);
            $table->decimal('jumlah_pengeluaran', 12, 4)->default(0.0000);
            $table->decimal('harga', 12, 4);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('barang_id')->references('barang_id')->on('barang');
            $table->foreign('kegiatan_id')->references('kegiatan_id')->on('kegiatan')->nullOnDelete();
        });

        Schema::create('laporan_stok_bulanan', function (Blueprint $table) {
            $table->id('laporan_id');
            $table->unsignedBigInteger('barang_id');
            $table->string('periode', 7);
            $table->decimal('saldo_awal', 12, 4)->default(0.0000);
            $table->decimal('saldo_akhir', 12, 4)->default(0.0000);
            $table->decimal('harga_rata_rata', 12, 4)->default(0.0000);
            $table->decimal('nilai', 14, 2)->default(0.00);
            $table->enum('status', ['draft', 'disahkan'])->default('draft');
            $table->unsignedBigInteger('disiapkan_oleh');
            $table->unsignedBigInteger('disetujui_oleh')->nullable();
            $table->date('tgl_approval')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('barang_id')->references('barang_id')->on('barang');
            $table->foreign('disiapkan_oleh')->references('users_id')->on('users');
            $table->foreign('disetujui_oleh')->references('users_id')->on('users')->nullOnDelete();
            $table->unique(['barang_id', 'periode']);
        });

        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('notifikasi_id');
            $table->unsignedBigInteger('users_id');
            $table->enum('jenis_notifikasi', ['kalibrasi', 'qc', 'stok', 'sertifikasi']);
            $table->text('pesan');
            $table->boolean('is_read')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('users_id')->references('users_id')->on('users')->cascadeOnDelete();
        });

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

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_log');
        Schema::dropIfExists('hak_akses');
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('laporan_stok_bulanan');
        Schema::dropIfExists('transaksi_barang');
        Schema::dropIfExists('permintaan_pengadaan');
        Schema::dropIfExists('riwayat_tindak_lanjut');
        Schema::dropIfExists('riwayat_pelatihan');
        Schema::dropIfExists('riwayat_kalibrasi');
        Schema::dropIfExists('hasil_uji');
        Schema::dropIfExists('kegiatan_personil');
        Schema::dropIfExists('kegiatan_alat');
        Schema::dropIfExists('kegiatan');
        Schema::dropIfExists('users');
        Schema::dropIfExists('parameter_uji');
        Schema::dropIfExists('alat');
        Schema::dropIfExists('barang');
        Schema::dropIfExists('personil');
        Schema::dropIfExists('modul');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
    }
};
