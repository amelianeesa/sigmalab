<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SdmController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ParameterUjiController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\HasilUjiController;
use App\Http\Controllers\RiwayatTindakLanjutController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\RoleSwitcherController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\HakAksesController;

Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process')->middleware('throttle:5,1');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/public/alat/{kode_alat}', [AlatController::class, 'inputKalibrasiByKode'])->where('kode_alat', '.*')->name('alat.public-scan');

Route::get('/alat/{id}/input-kalibrasi', [AlatController::class, 'inputKalibrasi'])->name('alat.input-kalibrasi');

// unduh informasi dan riwayat kalibrasi alat
Route::get('/alat/{id}/export-pdf', [AlatController::class, 'exportPdf'])->name('alat.export-pdf');
Route::get('/alat/{id}/export-excel', [AlatController::class, 'exportExcel'])->name('alat.export-excel');
Route::get('/alat/{id}/export-word', [AlatController::class, 'exportWord'])->name('alat.export-word');

// ini harus login dulu
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/switch-role', [RoleSwitcherController::class, 'switchRole'])->name('switch-role');

    // SDM & Kompetensi
    Route::get('/sdm', [SdmController::class, 'index'])->name('sdm.index');
    Route::get('/sdm/create', [SdmController::class, 'create'])->name('sdm.create');
    Route::post('/sdm', [SdmController::class, 'store'])->name('sdm.store');
    Route::post('/sdm/kategori', [SdmController::class, 'storeKategori'])->name('sdm.kategori.store');
    Route::delete('/sdm/kategori/{kode}', [SdmController::class, 'destroyKategori'])->name('sdm.kategori.destroy');

    Route::get('/sdm/competency-matrix', [SdmController::class, 'competencyMatrix'])->name('sdm.competency-matrix');
    Route::get('/sdm/competency-matrix/pdf', [SdmController::class, 'competencyMatrixPdf'])->name('sdm.competency-matrix.pdf');

    Route::get('/sdm/{id}/edit', [SdmController::class, 'edit'])->name('sdm.edit');
    Route::put('/sdm/{id}', [SdmController::class, 'update'])->name('sdm.update');
    Route::delete('/sdm/{id}', [SdmController::class, 'destroy'])->name('sdm.destroy');
    Route::patch('/sdm/{id}/aktifkan', [SdmController::class, 'activate'])->name('sdm.activate');
    Route::delete('/sdm/{id}/permanen', [SdmController::class, 'forceDestroy'])->name('sdm.force-destroy');
    Route::post('/sdm/{id}/akun', [App\Http\Controllers\SdmController::class, 'storeAkun'])
    ->name('sdm.akun.store');

    Route::get('/sdm/{id}/kompetensi', [SdmController::class, 'kompetensiDetail'])->name('sdm.kompetensi.detail');
    Route::post('/sdm/{id}/kompetensi', [SdmController::class, 'storeKompetensi'])->name('sdm.kompetensi.store');
    Route::put('/sdm/{id}/kompetensi/{kompetensiId}', [SdmController::class, 'updateKompetensi'])->name('sdm.kompetensi.update');
    Route::delete('/sdm/{id}/kompetensi/{kompetensiId}', [SdmController::class, 'destroyKompetensi'])->name('sdm.kompetensi.destroy');
    Route::get('/sdm/{id}/kompetensi/{kompetensiId}/file', [SdmController::class, 'showKompetensiFile'])->name('sdm.kompetensi.file');
    Route::post('/sdm/{id}/kompetensi/{kompetensiId}/file', [SdmController::class, 'uploadKompetensiFile'])->name('sdm.kompetensi.file.upload');
    Route::get('/sdm/{id}/cv', [SdmController::class, 'showCv'])->name('sdm.cv');

    // Resources
    Route::post('/alat/parse-sertifikat', [AlatController::class, 'parseSertifikat'])->name('alat.parse-sertifikat');
    Route::resource('alat', AlatController::class);
   
    // alat
    Route::post('/alat/{id}/input-kalibrasi', [AlatController::class, 'storeInputKalibrasi'])->name('alat.store-input-kalibrasi');

    Route::get('/alat/{id}/pemeliharaan', [AlatController::class, 'pemeliharaanBulanan'])->name('alat.pemeliharaan');
    Route::post('/alat/{id}/pemeliharaan/update', [AlatController::class, 'updatePemeliharaanHarian'])->name('alat.pemeliharaan.update');
    Route::get('/alat/{id}/item-pemeliharaan', [AlatController::class, 'editItemPemeliharaan'])->name('alat.item-pemeliharaan.edit');
    Route::post('/alat/{id}/item-pemeliharaan', [AlatController::class, 'updateItemPemeliharaan'])->name('alat.item-pemeliharaan.update');
    Route::post('/alat/{id}/perbaikan', [AlatController::class, 'storePerbaikan'])->name('alat.perbaikan.store');
    Route::put('/alat/{id}/perbaikan/{perbaikan_id}', [AlatController::class, 'updatePerbaikan'])->name('alat.perbaikan.update');
    Route::get('/alat/{id}/pemeliharaan/pdf', [AlatController::class, 'exportPemeliharaanPdf'])->name('alat.pemeliharaan.pdf');
    Route::get('/alat/{id}/pemeliharaan/excel', [AlatController::class, 'exportPemeliharaanExcel'])->name('alat.pemeliharaan.excel');
    
    // barang dan pengadaan
    Route::get('barang/cetak-periode', [BarangController::class, 'printPeriode'])->name('barang.cetak-periode');
    Route::resource('barang', BarangController::class);
    Route::get('/pengadaan/export-pdf', [PengadaanController::class, 'exportPdf'])->name('pengadaan.pdf');
    Route::resource('pengadaan', PengadaanController::class);
    Route::post('/pengadaan/{id}/approve', [PengadaanController::class, 'approve'])->name('pengadaan.approve');
    Route::post('/pengadaan/{id}/terima', [PengadaanController::class, 'konfirmasiTerima'])->name('pengadaan.terima');
    Route::post('/barang/{id}/pengeluaran', [BarangController::class, 'storePengeluaran'])->name('barang.pengeluaran');

    Route::resource('parameter-uji', ParameterUjiController::class);

    Route::middleware('modul:library_manage,tambah_ubah')->group(function () {
        Route::get('/library/create', [\App\Http\Controllers\LibraryController::class, 'create'])->name('library.create');
        Route::get('/library/arsip', [\App\Http\Controllers\LibraryController::class, 'archive'])->name('library.archive');
    });

    Route::middleware('modul:library_manage,lihat')->group(function () {
        Route::get('/library', [\App\Http\Controllers\LibraryController::class, 'index'])->name('library.index');
        Route::get('/library/export/pdf', [\App\Http\Controllers\LibraryController::class, 'exportPdf'])->name('library.export.pdf');
        Route::get('/library/{id}/versions/{versionId}/download', [\App\Http\Controllers\LibraryController::class, 'downloadVersion'])->name('library.version.download');
        Route::get('/library/{id}', [\App\Http\Controllers\LibraryController::class, 'show'])->name('library.show');
        Route::get('/library/{id}/download', [\App\Http\Controllers\LibraryController::class, 'download'])->name('library.download');
        Route::get('/library/{id}/preview', [\App\Http\Controllers\LibraryController::class, 'preview'])->name('library.preview');

    });

    Route::middleware('modul:library_manage,tambah_ubah')->group(function () {
        Route::post('/library', [\App\Http\Controllers\LibraryController::class, 'store'])->name('library.store');
        Route::get('/library/{id}/edit', [\App\Http\Controllers\LibraryController::class, 'edit'])->name('library.edit');
        Route::put('/library/{id}', [\App\Http\Controllers\LibraryController::class, 'update'])->name('library.update');
        Route::delete('/library/{id}', [\App\Http\Controllers\LibraryController::class, 'destroy'])->name('library.destroy');
        Route::patch('/library/{id}/aktifkan', [\App\Http\Controllers\LibraryController::class, 'activate'])->name('library.activate');
        Route::get('/library/{id}/revisi', [\App\Http\Controllers\LibraryController::class, 'createRevision'])->name('library.revision.create');
        Route::post('/library/{id}/revisi', [\App\Http\Controllers\LibraryController::class, 'storeRevision'])->name('library.revision.store');
    });

    Route::resource('kegiatan', KegiatanController::class);
    Route::resource('hasil-uji', HasilUjiController::class)->only(['store', 'show']);
    Route::resource('tindak-lanjut', RiwayatTindakLanjutController::class)->only(['index', 'create', 'store', 'show']);
    
    Route::get('/alat/{id}/input-kalibrasi', [AlatController::class, 'inputKalibrasi'])->name('alat.input-kalibrasi');
    Route::post('/alat/{id}/input-kalibrasi', [AlatController::class, 'storeInputKalibrasi'])->name('alat.store-input-kalibrasi');

    Route::get('/alat/{id}/pemeliharaan', [AlatController::class, 'pemeliharaanBulanan'])->name('alat.pemeliharaan');
    Route::post('/alat/{id}/pemeliharaan/update', [AlatController::class, 'updatePemeliharaanHarian'])->name('alat.pemeliharaan.update');
    Route::get('/alat/{id}/item-pemeliharaan', [AlatController::class, 'editItemPemeliharaan'])->name('alat.item-pemeliharaan.edit');
    Route::post('/alat/{id}/item-pemeliharaan', [AlatController::class, 'updateItemPemeliharaan'])->name('alat.item-pemeliharaan.update');
    Route::post('/alat/{id}/perbaikan', [AlatController::class, 'storePerbaikan'])->name('alat.perbaikan.store');
    Route::put('/alat/{id}/perbaikan/{perbaikan_id}', [AlatController::class, 'updatePerbaikan'])->name('alat.perbaikan.update');
  
    Route::get('reporting', [ReportingController::class, 'index'])->name('reporting.index');
    Route::get('reporting/pdf', [ReportingController::class, 'exportPdf'])->name('reporting.pdf');

    Route::middleware('modul:audit_log,lihat')->group(function () {
        Route::get('audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');
        Route::get('audit-log/{id}', [AuditLogController::class, 'show'])->name('audit-log.show');
    });

    Route::middleware('modul:manajemen_pengguna,lihat')->group(function () {
        Route::get('hak-akses', [HakAksesController::class, 'index'])->name('hak-akses.index');
        Route::post('hak-akses', [HakAksesController::class, 'update'])->name('hak-akses.update');
        Route::get('kelola-user', [\App\Http\Controllers\KelolaUserController::class, 'index'])->name('kelola-user.index');
    });

    Route::middleware('modul:manajemen_pengguna,tambah_ubah')->group(function () {
    Route::post('kelola-user', [\App\Http\Controllers\KelolaUserController::class, 'store'])->name('kelola-user.store');
    Route::put('kelola-user/{id}', [\App\Http\Controllers\KelolaUserController::class, 'update'])->name('kelola-user.update');
    Route::delete('kelola-user/{id}', [\App\Http\Controllers\KelolaUserController::class, 'destroy'])->name('kelola-user.destroy');
});
});
