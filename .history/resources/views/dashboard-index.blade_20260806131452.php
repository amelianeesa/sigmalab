@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Header Selamat Datang -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-2 border-bottom">
        <div>
            <h3 class="fw-bold text-dark mb-1">Dashboard Utama</h3>
            <p class="text-muted small mb-0">Sistem Integrasi Laboratorium — PT Sucofindo Cabang Cilacap</p>
        </div>
        <div class="mt-3 mt-md-0 text-muted small bg-white px-3 py-2 rounded-2 border shadow-sm">
            <span class="fw-semibold text-dark">Status Sistem:</span> <span class="text-success fw-bold">● Normal</span>
        </div>
    </div>

    <!-- Layout Utama: 2 Kolom (Kiri: Grid Modul, Kanan: Aktivitas & Informasi) -->
    <div class="row g-4">
        
        <!-- KOLOM KIRI: MODUL SISTEM (Lebar 8) -->
        <div class="col-12 col-lg-8">
            <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 11px; letter-spacing: 0.5px;">Modul Layanan & Operasional</h6>
            
            <div class="row g-3">

                <!-- Aset & Kalibrasi -->
                <div class="col-12 col-sm-6">
                    <a href="#" class="text-decoration-none">
                        <div class="card h-100 shadow-sm rounded-3 border-0 corporate-card">
                            <div class="card-body p-3.5">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 rounded-2 bg-light text-primary fs-4">⛭</div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">Aset & Kalibrasi</h6>
                                        <p class="text-muted small mb-0" style="font-size: 12px;">Alat lab & jadwal kalibrasi.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- QC & Parameter Uji -->
                <div class="col-12 col-sm-6">
                    <a href="#" class="text-decoration-none">
                        <div class="card h-100 shadow-sm rounded-3 border-0 corporate-card">
                            <div class="card-body p-3.5">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 rounded-2 bg-light text-primary fs-4">▣</div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">QC & Parameter Uji</h6>
                                        <p class="text-muted small mb-0" style="font-size: 12px;">Kontrol kualitas & parameter.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Inventori Bahan -->
                <div class="col-12 col-sm-6">
                    <a href="#" class="text-decoration-none">
                        <div class="card h-100 shadow-sm rounded-3 border-0 corporate-card">
                            <div class="card-body p-3.5">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 rounded-2 bg-light text-primary fs-4">◔</div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">Inventori Bahan</h6>
                                        <p class="text-muted small mb-0" style="font-size: 12px;">Stok bahan kimia & reagen.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Audit Log -->
                <div class="col-12 col-sm-6">
                    <a href="#" class="text-decoration-none">
                        <div class="card h-100 shadow-sm rounded-3 border-0 corporate-card">
                            <div class="card-body p-3.5">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-2 rounded-2 bg-light text-primary fs-4">📋</div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">Audit Log</h6>
                                        <p class="text-muted small mb-0" style="font-size: 12px;">Rekam jejak aktivitas sistem.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- SDM (Highlighted sebagai Modul Utama yang Menonjol tapi Tetap Formal) -->
                <div class="col-12">
                    <a href="{{ route('sdm.index') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm rounded-3 border-0 corporate-card sdm-featured" style="background-color: #1e293b;">
                            <div class="card-body p-4 text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-3 rounded-2 bg-white bg-opacity-10 fs-3">👤</div>
                                        <div>
                                            <span class="badge bg-primary bg-opacity-25 text-light mb-1" style="font-size: 10px;">Modul Utama</span>
                                            <h5 class="fw-bold mb-1 text-white">Sumber Daya Manusia (SDM)</h5>
                                            <p class="text-light text-opacity-75 small mb-0" style="font-size: 13px;">Kelola data personil, kompetensi, sertifikasi, riwayat pelatihan, dan hak akses.</p>
                                        </div>
                                    </div>
                                    <div class="fs-4 text-white text-opacity-50 ps-3">
                                        &rarr;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>

        <!-- KOLOM KANAN: PANEL AKTIVITAS & INFORMASI (Lebar 4) -->
        <div class="col-12 col-lg-4">
            
            <!-- Aktivitas Terbaru -->
            <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 11px; letter-spacing: 0.5px;">Aktivitas Terbaru</h6>
            <div class="card shadow-sm rounded-3 border-0 mb-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <span class="fw-bold text-dark small">Log Sistem Terkini</span>
                        <span class="text-muted" style="font-size: 11px;">10 aktivitas terakhir</span>
                    </div>

                    <!-- List Timeline Sederhana ala Enterprise -->
                    <div class="activity-feed ps-2">
                        <div class="mb-3 position-relative ps-3 border-start border-2 border-primary">
                            <div class="fw-semibold text-dark" style="font-size: 13px;">Pembaruan data personil SDM</div>
                            <div class="text-muted" style="font-size: 11px;">15 menit lalu • oleh Admin</div>
                        </div>
                        <div class="mb-2 position-relative ps-3 border-start border-2 border-secondary border-opacity-25">
                            <div class="fw-semibold text-dark" style="font-size: 13px;">Penambahan inventori reagen baru</div>
                            <div class="text-muted" style="font-size: 11px;">2 jam lalu • oleh Teknisi</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi / Pengumuman Singkat -->
            <div class="card shadow-sm rounded-3 border-0 bg-light">
                <div class="card-body p-3">
                    <h6 class="fw-bold text-dark small mb-2">📌 Pengumuman Internal</h6>
                    <p class="text-muted mb-0" style="font-size: 12px; line-height: 1.5;">
                        Pastikan seluruh data sertifikasi analis diperbarui sebelum audit mutu laboratorium periode ini berakhir.
                    </p>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- CSS Pendukung untuk Efek Clean & Professional -->
<style>
    .corporate-card {
        background-color: #ffffff;
        border: 1px solid #e2e8f0 !important;
        transition: all 0.2s ease-in-out;
    }
    .corporate-card:hover {
        border-color: #cbd5e1 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transform: translateY(-2px);
    }
    .sdm-featured {
        border: 1px solid #0f172a !important;
    }
    .sdm-featured:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(15, 23, 42, 0.15) !important;
    }
</style>
@endsection