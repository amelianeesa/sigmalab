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
use App\Http\Controllers\MonitoringRuanganController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\EvaluasiKalibrasiController;
use App\Http\Controllers\PerbaikanAlatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\KelolaUserController;
use App\Http\Controllers\VerifikasiMutuController;
use App\Http\Controllers\QcInhouseController;
use App\Http\Controllers\QcHarianController;
use App\Http\Controllers\ParameterToleransiController;

// Guest/Public
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process')->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/public/alat/{kode_alat}', [AlatController::class, 'inputKalibrasiByKode'])->where('kode_alat', '.*')->name('alat.public-scan');


// Wajib Login
Route::middleware(['auth'])->group(function () {

    // Dashboard & Role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/switch-role', [RoleSwitcherController::class, 'switchRole'])->name('switch-role');

    // Evaluasi Kalibrasi
    Route::resource('evaluasi-kalibrasi', EvaluasiKalibrasiController::class);

    // SDM dan Kompetensi
    Route::prefix('sdm')->name('sdm.')->group(function () {
        Route::get('/', [SdmController::class, 'index'])->name('index');
        Route::get('/create', [SdmController::class, 'create'])->name('create');
        Route::post('/', [SdmController::class, 'store'])->name('store');
        Route::post('/kategori', [SdmController::class, 'storeKategori'])->name('kategori.store');
        Route::delete('/kategori/{kode}', [SdmController::class, 'destroyKategori'])->name('kategori.destroy');

        Route::get('/competency-matrix', [SdmController::class, 'competencyMatrix'])->name('competency-matrix');
        Route::get('/competency-matrix/pdf', [SdmController::class, 'competencyMatrixPdf'])->name('competency-matrix.pdf');

        Route::get('/{id}/edit', [SdmController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SdmController::class, 'update'])->name('update');
        Route::delete('/{id}', [SdmController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/aktifkan', [SdmController::class, 'activate'])->name('activate');
        Route::delete('/{id}/permanen', [SdmController::class, 'forceDestroy'])->name('force-destroy');
        
        Route::post('/{id}/akun', [SdmController::class, 'storeAkun'])->name('akun.store');

        Route::get('/{id}/kompetensi', [SdmController::class, 'kompetensiDetail'])->name('kompetensi.detail');
        Route::post('/{id}/kompetensi', [SdmController::class, 'storeKompetensi'])->name('kompetensi.store');
        Route::put('/{id}/kompetensi/{kompetensiId}', [SdmController::class, 'updateKompetensi'])->name('kompetensi.update');
        Route::delete('/{id}/kompetensi/{kompetensiId}', [SdmController::class, 'destroyKompetensi'])->name('kompetensi.destroy');
        Route::get('/{id}/kompetensi/{kompetensiId}/file', [SdmController::class, 'showKompetensiFile'])->name('kompetensi.file');
        Route::post('/{id}/kompetensi/{kompetensiId}/file', [SdmController::class, 'uploadKompetensiFile'])->name('kompetensi.file.upload');
        Route::get('/{id}/cv', [SdmController::class, 'showCv'])->name('cv');
    });

    // Manajemen Alat dan Peerbaikan
    Route::post('/alat/parse-sertifikat', [AlatController::class, 'parseSertifikat'])->name('alat.parse-sertifikat');
    Route::resource('alat', AlatController::class);

    Route::prefix('alat/{id}')->name('alat.')->group(function () {
        Route::get('input-kalibrasi', [AlatController::class, 'inputKalibrasi'])->name('input-kalibrasi');
        Route::post('input-kalibrasi', [AlatController::class, 'storeInputKalibrasi'])->name('store-input-kalibrasi');
        
        Route::get('pemeliharaan', [AlatController::class, 'pemeliharaanBulanan'])->name('pemeliharaan');
        Route::post('pemeliharaan/update', [AlatController::class, 'updatePemeliharaanHarian'])->name('pemeliharaan.update');
        Route::get('item-pemeliharaan', [AlatController::class, 'editItemPemeliharaan'])->name('item-pemeliharaan.edit');
        Route::post('item-pemeliharaan', [AlatController::class, 'updateItemPemeliharaan'])->name('item-pemeliharaan.update');
        
        Route::get('pemeliharaan/pdf', [AlatController::class, 'exportPemeliharaanPdf'])->name('pemeliharaan.pdf');
        Route::get('pemeliharaan/excel', [AlatController::class, 'exportPemeliharaanExcel'])->name('pemeliharaan.excel');
        
        Route::get('export-pdf', [AlatController::class, 'exportPdf'])->name('export-pdf');
        Route::get('export-excel', [AlatController::class, 'exportExcel'])->name('export-excel');
        Route::get('export-word', [AlatController::class, 'exportWord'])->name('export-word');

        // Perbaikan Alat
        Route::post('perbaikan', [PerbaikanAlatController::class, 'store'])->name('perbaikan.store');
        Route::put('perbaikan/{riwayat_perbaikan_id}', [PerbaikanAlatController::class, 'update'])->name('perbaikan.update');
    });

    // Barang dan Pengadaan
    Route::get('barang/cetak-periode', [BarangController::class, 'printPeriode'])->name('barang.cetak-periode');
    Route::resource('barang', BarangController::class);
    Route::post('/barang/{id}/pengeluaran', [BarangController::class, 'storePengeluaran'])->name('barang.pengeluaran');

    Route::get('/pengadaan/export-pdf', [PengadaanController::class, 'exportPdf'])->name('pengadaan.exportPdf');
    Route::resource('pengadaan', PengadaanController::class);
    Route::post('/pengadaan/{id}/approve', [PengadaanController::class, 'approve'])->name('pengadaan.approve');
    Route::post('/pengadaan/{id}/terima', [PengadaanController::class, 'konfirmasiTerima'])->name('pengadaan.konfirmasiTerima');
    Route::put('/pengadaan/{id}/update-progres', [PengadaanController::class, 'updateProgres'])->name('pengadaan.update-progres');
    Route::put('/pengadaan/{id}/batal-progres', [PengadaanController::class, 'batalProgres'])->name('pengadaan.batal-progres');

    // Monitoring Ruangan
    Route::prefix('inventori')->name('inventori.')->group(function () {
        Route::get('/monitoring-ruangan', [MonitoringRuanganController::class, 'index'])->name('monitoring.index');
        Route::post('/monitoring-ruangan/update', [MonitoringRuanganController::class, 'updateBaris'])->name('monitoring.updateBaris');
        Route::post('/monitoring-ruangan/update-persyaratan', [MonitoringRuanganController::class, 'updatePersyaratan'])->name('monitoring.updatePersyaratan');
        Route::get('/monitoring-ruangan/export-pdf', [MonitoringRuanganController::class, 'exportPdf'])->name('monitoring.exportPdf');
        Route::post('/monitoring-ruangan/kalibrasi/{alatId}', [MonitoringRuanganController::class, 'storeTitikKalibrasi'])->name('monitoring.storeKalibrasi');
        Route::delete('/monitoring-ruangan/kalibrasi-item/{id}', [MonitoringRuanganController::class, 'destroyTitikKalibrasi'])->name('monitoring.destroyKalibrasi');
        Route::post('/monitoring-ruangan/upload-referensi', [MonitoringRuanganController::class, 'uploadReferensi'])->name('monitoring.uploadReferensi');
        Route::put('/monitoring-ruangan/referensi/{id}', [MonitoringRuanganController::class, 'updateReferensi'])->name('monitoring.updateReferensi');
        Route::delete('/monitoring-ruangan/referensi/{id}', [MonitoringRuanganController::class, 'destroyReferensi'])->name('monitoring.destroyReferensi');
    });

    // Parameter Uji CRM
    Route::resource('parameter-uji', ParameterUjiController::class);
    Route::get('/parameter-uji/{parameter_uji}/calculate-stats', [ParameterUjiController::class, 'calculateHistoricalStats'])->name('parameter-uji.calculate-stats');
    Route::get('/parameter-uji/{parameter_uji}/control-chart', [ParameterUjiController::class, 'controlChart'])->name('parameter-uji.control-chart');
    Route::match(['get', 'post'], 'parameter-uji/{parameter_uji}/cetak', [ParameterUjiController::class, 'cetakControlChart'])->name('parameter-uji.cetak-control-chart');

    
    Route::post('/parameter-uji/store-crm', [ParameterUjiController::class, 'storeCrmKatalog'])->name('parameter-uji.store-crm');
    // CRM Katalog CRUD
    Route::resource('crm-katalog', \App\Http\Controllers\CrmKatalogController::class);
    Route::post('crm-katalog/{id}/sertifikat', [\App\Http\Controllers\CrmKatalogController::class, 'storeSertifikat'])->name('crm-katalog.store-sertifikat');
    Route::delete('crm-katalog/{id}/sertifikat/{sertifikat_id}', [\App\Http\Controllers\CrmKatalogController::class, 'destroySertifikat'])->name('crm-katalog.destroy-sertifikat');
    Route::get('crm-katalog/{id}/verifikasi-administratif', [\App\Http\Controllers\CrmKatalogController::class, 'verifikasiAdministratifForm'])->name('crm-katalog.verifikasi-administratif.form');
    Route::post('crm-katalog/{id}/verifikasi-administratif', [\App\Http\Controllers\CrmKatalogController::class, 'verifikasiAdministratifStore'])->name('crm-katalog.verifikasi-administratif.store');
    Route::get('crm-katalog/{id}/verifikasi-teknis', [\App\Http\Controllers\CrmKatalogController::class, 'verifikasiTeknisForm'])->name('crm-katalog.verifikasi-teknis.form');
    Route::post('crm-katalog/{id}/verifikasi-teknis', [\App\Http\Controllers\CrmKatalogController::class, 'verifikasiTeknisStore'])->name('crm-katalog.verifikasi-teknis.store');

    // Pengujian Harian QC CRM
    Route::resource('qc-crm', \App\Http\Controllers\QcCrmController::class);
    Route::get('api/crm-katalog/{id}/parameters', [\App\Http\Controllers\QcCrmController::class, 'apiGetParameters'])->name('api.crm-katalog.parameters');

    // QC Uji Banding

    Route::get('qc-uji-banding/master-toleransi', [ParameterToleransiController::class, 'index'])->name('master.toleransi.index');
    Route::post('qc-uji-banding/master-toleransi', [ParameterToleransiController::class, 'store'])->name('master.toleransi.store');
    Route::put('qc-uji-banding/master-toleransi/{id}', [ParameterToleransiController::class, 'update'])->name('master.toleransi.update');
    Route::delete('qc-uji-banding/master-toleransi/{id}', [ParameterToleransiController::class, 'destroy'])->name('master.toleransi.destroy');
    Route::post('qc-uji-banding/draft', [\App\Http\Controllers\QcUjiBandingController::class, 'storeDraft'])->name('qc-uji-banding.draft.store');
    Route::resource('qc-uji-banding', \App\Http\Controllers\QcUjiBandingController::class);
    Route::get('qc-uji-banding/{id}/print-pdf', [\App\Http\Controllers\QcUjiBandingController::class, 'printPdf'])->name('qc-uji-banding.printPdf');
    Route::get('qc-uji-banding/{id}/investigasi/{param_id}', [\App\Http\Controllers\QcUjiBandingController::class, 'investigasi'])->name('qc-uji-banding.investigasi');
    Route::put('qc-uji-banding/{id}/investigasi/{param_id}', [\App\Http\Controllers\QcUjiBandingController::class, 'storeInvestigasi'])->name('qc-uji-banding.store-investigasi');
    Route::get('qc-uji-banding/{id}/cetak/pdf/{param_id}', [\App\Http\Controllers\QcUjiBandingController::class, 'cetakLksPdf'])->name('qc-uji-banding.cetak.pdf');
    Route::get('qc-uji-banding/{id}/cetak/word/{param_id}', [\App\Http\Controllers\QcUjiBandingController::class, 'cetakLksWord'])->name('qc-uji-banding.cetak.word');
    Route::get('qc-uji-banding/{id}/evaluasi', [\App\Http\Controllers\QcUjiBandingController::class, 'evaluasiForm'])->name('qc-uji-banding.evaluasi.form');
    Route::post('qc-uji-banding/{id}/evaluasi', [\App\Http\Controllers\QcUjiBandingController::class, 'evaluasiStore'])->name('qc-uji-banding.evaluasi.store');
    Route::get('qc-uji-banding/{id}/ringkasan', [\App\Http\Controllers\QcUjiBandingController::class, 'ringkasanUnjukKerja'])->name('qc-uji-banding.ringkasan');

    // AFT Kalibrasi Endpoints
    Route::post('aft-kalibrasi', [\App\Http\Controllers\QcUjiBandingController::class, 'aftKalibrasiStore'])->name('aft.kalibrasi.store');
    Route::get('aft-kalibrasi', [\App\Http\Controllers\QcUjiBandingController::class, 'aftKalibrasiList'])->name('aft.kalibrasi.list');
    Route::get('aft-kalibrasi/{id}', [\App\Http\Controllers\QcUjiBandingController::class, 'aftKalibrasiShow'])->name('aft.kalibrasi.show');

    // Library Digital
    Route::middleware('modul:library_manage,lihat')->group(function () {
        Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
        Route::get('/library/export/pdf', [LibraryController::class, 'exportPdf'])->name('library.export.pdf');
        Route::get('/library/{id}/versions/{versionId}/download', [LibraryController::class, 'downloadVersion'])->name('library.version.download');
        Route::get('/library/{id}', [LibraryController::class, 'show'])->name('library.show');
        Route::get('/library/{id}/download', [LibraryController::class, 'download'])->name('library.download');
        Route::get('/library/{id}/preview', [LibraryController::class, 'preview'])->name('library.preview');
    });

    Route::middleware('modul:library_manage,tambah_ubah')->group(function () {
        Route::get('/library/create', [LibraryController::class, 'create'])->name('library.create');
        Route::get('/library/arsip', [LibraryController::class, 'archive'])->name('library.archive');
        Route::post('/library', [LibraryController::class, 'store'])->name('library.store');
        Route::get('/library/{id}/edit', [LibraryController::class, 'edit'])->name('library.edit');
        Route::put('/library/{id}', [LibraryController::class, 'update'])->name('library.update');
        Route::delete('/library/{id}', [LibraryController::class, 'destroy'])->name('library.destroy');
        Route::patch('/library/{id}/aktifkan', [LibraryController::class, 'activate'])->name('library.activate');
        Route::get('/library/{id}/revisi', [LibraryController::class, 'createRevision'])->name('library.revision.create');
        Route::post('/library/{id}/revisi', [LibraryController::class, 'storeRevision'])->name('library.revision.store');
    });

    // Kegiatan, Uji Hasil, Tindak Lanjut
    Route::resource('kegiatan', KegiatanController::class);
    Route::post('/kegiatan/{id}/unlock', [KegiatanController::class, 'unlock'])->name('kegiatan.unlock');

    Route::resource('hasil-uji', HasilUjiController::class);
    Route::post('/hasil-uji/{id}/retest', [HasilUjiController::class, 'retest'])->name('hasil-uji.retest');
    Route::post('/hasil-uji/{id}/override', [HasilUjiController::class, 'overrideWestgard'])->name('hasil-uji.override');
    Route::post('/hasil-uji/{id}/override-evaluasi', [HasilUjiController::class, 'overrideEvaluasi'])->name('hasil-uji.override-evaluasi');

    Route::resource('tindak-lanjut', RiwayatTindakLanjutController::class)->except(['destroy']);
    Route::post('/tindak-lanjut/{id}/komentar', [RiwayatTindakLanjutController::class, 'storeKomentar'])->name('tindak-lanjut.komentar.store');

    // Reporting dan Audit Log
    Route::get('reporting', [ReportingController::class, 'index'])->name('reporting.index');
    Route::get('reporting/pdf', [ReportingController::class, 'exportPdf'])->name('reporting.pdf');

    Route::middleware('modul:audit_log,lihat')->group(function () {
        Route::get('audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');
        Route::get('audit-log/{id}', [AuditLogController::class, 'show'])->name('audit-log.show');
    });

    // Hak Akses dan Manajemen Pengguna
    Route::middleware('modul:manajemen_pengguna,lihat')->group(function () {
        Route::get('hak-akses', [HakAksesController::class, 'index'])->name('hak-akses.index');
        Route::post('hak-akses', [HakAksesController::class, 'update'])->name('hak-akses.update');
        Route::get('kelola-user', [KelolaUserController::class, 'index'])->name('kelola-user.index');
    });

    Route::middleware('modul:manajemen_pengguna,tambah_ubah')->group(function () {
        Route::post('kelola-user', [KelolaUserController::class, 'store'])->name('kelola-user.store');
        Route::put('kelola-user/{id}', [KelolaUserController::class, 'update'])->name('kelola-user.update');
        Route::delete('kelola-user/{id}', [KelolaUserController::class, 'destroy'])->name('kelola-user.destroy');
    });

    // Notifikasi
    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [NotifikasiController::class, 'index'])->name('index');
        Route::get('/{id}/klik', [NotifikasiController::class, 'klikNotifikasi'])->name('klik');
        Route::post('/{id}/read', [NotifikasiController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotifikasiController::class, 'markAllAsRead'])->name('read-all');
        Route::get('/unread-count', [NotifikasiController::class, 'getUnreadCount'])->name('unread-count');
    });

    //QC
    Route::get('verifikasi-mutu', [VerifikasiMutuController::class, 'index'])->name('verifikasi-mutu.index');

    Route::prefix('qc-inhouse')->name('qc-inhouse.')->group(function () {
        Route::get('/', [QcInhouseController::class, 'index'])->name('index');
        Route::get('/create', [QcInhouseController::class, 'create'])->name('create');
        Route::post('/', [QcInhouseController::class, 'store'])->name('store');
        Route::get('/{id}', [QcInhouseController::class, 'show'])->name('show');
        
        // Preparasi
        Route::get('/{id}/preparasi', [QcInhouseController::class, 'showPreparasi'])->name('preparasi');
        Route::post('/{id}/preparasi', [QcInhouseController::class, 'storePreparasi'])->name('preparasi.store');
        Route::get('/{id}/cetak-label', [QcInhouseController::class, 'cetakLabel'])->name('cetak-label');
        
        // Uji Homogenitas
        Route::get('/{id}/instruksi-homogenitas', [QcInhouseController::class, 'showInstruksiHomogenitas'])->name('instruksi-homogenitas');
        Route::get('/{id}/homogenitas', [QcInhouseController::class, 'showHomogenitas'])->name('homogenitas');
        Route::post('/{id}/homogenitas', [QcInhouseController::class, 'storeHomogenitas'])->name('homogenitas.store');
        
        // Penetapan Nilai Target
        Route::get('/{id}/penetapan-target', [QcInhouseController::class, 'showPenetapanTarget'])->name('penetapan-target');
        Route::post('/{id}/penetapan-target', [QcInhouseController::class, 'storePenetapanTarget'])->name('penetapan-target.store');
        
        // Uji Stabilitas
        Route::get('/{id}/stabilitas', [QcInhouseController::class, 'showStabilitas'])->name('stabilitas');
        Route::post('/{id}/stabilitas', [QcInhouseController::class, 'storeStabilitas'])->name('stabilitas.store');

        // Aktivasi
        Route::post('/{id}/aktifkan', [QcInhouseController::class, 'aktifkan'])->name('aktifkan');
    });

    Route::prefix('qc-harian')->name('qc-harian.')->group(function () {
        Route::get('/', [QcHarianController::class, 'index'])->name('index');
        Route::get('/create', [QcHarianController::class, 'create'])->name('create');
        Route::post('/', [QcHarianController::class, 'store'])->name('store');
        Route::get('/{parameter_uji_id}/chart', [QcHarianController::class, 'chart'])->name('chart');
        Route::get('/{id}/investigasi', [QcHarianController::class, 'investigasi'])->name('investigasi');
        Route::post('/{id}/investigasi', [QcHarianController::class, 'storeInvestigasi'])->name('investigasi.store');
    });

});