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

// Guest / Auth Routes
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process')->middleware('throttle:5,1');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Public Routes
Route::get('/public/alat/{kode_alat}', [AlatController::class, 'publicScan'])->name('alat.public-scan');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::post('/switch-role', [RoleSwitcherController::class, 'switchRole'])->name('switch-role');

    // SDM & Kompetensi
    Route::get('/sdm', [SdmController::class, 'index'])->name('sdm.index');
    Route::get('/sdm/create', [SdmController::class, 'create'])->name('sdm.create');
    Route::post('/sdm', [SdmController::class, 'store'])->name('sdm.store');
    Route::post('/sdm/kategori', [SdmController::class, 'storeKategori'])->name('sdm.kategori.store');
    Route::delete('/sdm/kategori/{kode}', [SdmController::class, 'destroyKategori'])->name('sdm.kategori.destroy');

    // NOTE: harus didaftarkan sebelum '/sdm/{id}/edit' agar path literal ini
    // tidak pernah ditangkap sebagai parameter {id}.
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
    Route::get('barang/cetak-periode', [BarangController::class, 'printPeriode'])->name('barang.cetak-periode');
    Route::resource('barang', BarangController::class);
    Route::get('/pengadaan/export-pdf', [PengadaanController::class, 'exportPdf'])->name('pengadaan.pdf');
    Route::resource('pengadaan', PengadaanController::class);
    Route::post('/pengadaan/{id}/approve', [PengadaanController::class, 'approve'])->name('pengadaan.approve');
    Route::resource('parameter-uji', ParameterUjiController::class);
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
    // Reporting
    Route::get('reporting', [ReportingController::class, 'index'])->name('reporting.index');
    Route::get('reporting/pdf', [ReportingController::class, 'exportPdf'])->name('reporting.pdf');

    // Audit Log
    Route::middleware('modul:audit_log,lihat')->group(function () {
        Route::get('audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');
        Route::get('audit-log/{id}', [AuditLogController::class, 'show'])->name('audit-log.show');
    });

    // Manajemen Hak Akses
    Route::middleware('modul:manajemen_pengguna,lihat')->group(function () {
        Route::get('hak-akses', [HakAksesController::class, 'index'])->name('hak-akses.index');
        Route::post('hak-akses', [HakAksesController::class, 'update'])->name('hak-akses.update');
    });
});