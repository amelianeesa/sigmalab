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
use App\Http\Controllers\NotifikasiController;

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
    Route::get('/parameter-uji/{parameter_uji}/calculate-stats', [ParameterUjiController::class, 'calculateHistoricalStats'])->name('parameter-uji.calculate-stats');
    Route::get('/parameter-uji/{parameter_uji}/control-chart', [ParameterUjiController::class, 'controlChart'])->name('parameter-uji.control-chart');
    Route::match(['get', 'post'], 'parameter-uji/{parameter_uji}/cetak', [ParameterUjiController::class, 'cetakControlChart'])->name('parameter-uji.cetak-control-chart');
    Route::resource('parameter-uji', ParameterUjiController::class);
    Route::post('/parameter-uji/crm-katalog', [ParameterUjiController::class, 'storeCrmKatalog'])->name('parameter-uji.store-crm');
    Route::delete('/parameter-uji/crm-katalog/{id}', [ParameterUjiController::class, 'destroyCrmKatalog'])->name('parameter-uji.destroy-crm');
    Route::resource('kegiatan', KegiatanController::class);
    Route::post('/kegiatan/{id}/unlock', [KegiatanController::class, 'unlock'])->name('kegiatan.unlock');
    Route::resource('hasil-uji', HasilUjiController::class);
    Route::post('/hasil-uji/{id}/retest', [HasilUjiController::class, 'retest'])->name('hasil-uji.retest');
    Route::post('/hasil-uji/{id}/override', [HasilUjiController::class, 'overrideWestgard'])->name('hasil-uji.override');
    Route::resource('tindak-lanjut', RiwayatTindakLanjutController::class)->except(['destroy']);
    Route::post('/tindak-lanjut/{id}/komentar', [RiwayatTindakLanjutController::class, 'storeKomentar'])->name('tindak-lanjut.komentar.store');
    
    Route::prefix('alat/{id}')->controller(AlatController::class)->name('alat.')->group(function () {
        Route::get('input-kalibrasi', 'inputKalibrasi')->name('input-kalibrasi');
        Route::post('input-kalibrasi', 'storeInputKalibrasi')->name('store-input-kalibrasi');
        Route::get('pemeliharaan', 'pemeliharaanBulanan')->name('pemeliharaan');
        Route::post('pemeliharaan/update', 'updatePemeliharaanHarian')->name('pemeliharaan.update');
        Route::get('item-pemeliharaan', 'editItemPemeliharaan')->name('item-pemeliharaan.edit');
        Route::post('item-pemeliharaan', 'updateItemPemeliharaan')->name('item-pemeliharaan.update');
        Route::post('perbaikan', 'storePerbaikan')->name('perbaikan.store');
        Route::put('perbaikan/{perbaikan_id}', 'updatePerbaikan')->name('perbaikan.update');
    });
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

    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{id}/read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('/notifikasi/read-all', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.read-all');
    Route::get('/notifikasi/unread-count', [NotifikasiController::class, 'getUnreadCount'])->name('notifikasi.unread-count');

    // Inhouse Control
        Route::post('/hasil-uji/{id}/override-evaluasi', [HasilUjiController::class, 'overrideEvaluasi'])->name('hasil-uji.override-evaluasi');

    // =============================================
    // Verifikasi Mutu — Portal & QC In-House
    // =============================================
    Route::get('verifikasi-mutu', [\App\Http\Controllers\VerifikasiMutuController::class, 'index'])->name('verifikasi-mutu.index');

    Route::prefix('qc-inhouse')->name('qc-inhouse.')->group(function () {
        Route::get('/', [\App\Http\Controllers\QcInhouseController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\QcInhouseController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\QcInhouseController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\QcInhouseController::class, 'show'])->name('show');
        
        // Tahap 2: Preparasi
        Route::get('/{id}/preparasi', [\App\Http\Controllers\QcInhouseController::class, 'showPreparasi'])->name('preparasi');
        Route::post('/{id}/preparasi', [\App\Http\Controllers\QcInhouseController::class, 'storePreparasi'])->name('preparasi.store');
        Route::get('/{id}/cetak-label', [\App\Http\Controllers\QcInhouseController::class, 'cetakLabel'])->name('cetak-label');
        
        // Tahap 3: Uji Homogenitas
        Route::get('/{id}/instruksi-homogenitas', [\App\Http\Controllers\QcInhouseController::class, 'showInstruksiHomogenitas'])->name('instruksi-homogenitas');
        Route::get('/{id}/homogenitas', [\App\Http\Controllers\QcInhouseController::class, 'showHomogenitas'])->name('homogenitas');
        Route::post('/{id}/homogenitas', [\App\Http\Controllers\QcInhouseController::class, 'storeHomogenitas'])->name('homogenitas.store');
        
        // Tahap 4: Penetapan Nilai Target
        Route::get('/{id}/penetapan-target', [\App\Http\Controllers\QcInhouseController::class, 'showPenetapanTarget'])->name('penetapan-target');
        Route::post('/{id}/penetapan-target', [\App\Http\Controllers\QcInhouseController::class, 'storePenetapanTarget'])->name('penetapan-target.store');
        
        // Tahap 5: Uji Stabilitas
        Route::get('/{id}/stabilitas', [\App\Http\Controllers\QcInhouseController::class, 'showStabilitas'])->name('stabilitas');
        Route::post('/{id}/stabilitas', [\App\Http\Controllers\QcInhouseController::class, 'storeStabilitas'])->name('stabilitas.store');

        // Tahap 6: Aktivasi
        Route::post('/{id}/aktifkan', [\App\Http\Controllers\QcInhouseController::class, 'aktifkan'])->name('aktifkan');
    });

    // =============================================
    // Verifikasi Mutu - Pengujian Harian QC
    // =============================================
    Route::prefix('qc-harian')->name('qc-harian.')->group(function () {
        Route::get('/', [\App\Http\Controllers\QcHarianController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\QcHarianController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\QcHarianController::class, 'store'])->name('store');
        Route::get('/{parameter_uji_id}/chart', [\App\Http\Controllers\QcHarianController::class, 'chart'])->name('chart');
        Route::get('/{id}/investigasi', [\App\Http\Controllers\QcHarianController::class, 'investigasi'])->name('investigasi');
        Route::post('/{id}/investigasi', [\App\Http\Controllers\QcHarianController::class, 'storeInvestigasi'])->name('investigasi.store');
    });
});