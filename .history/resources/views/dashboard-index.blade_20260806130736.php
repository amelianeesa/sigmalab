@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Header Dashboard -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Dashboard Utama</h2>
        <p class="text-muted small mb-0">Selamat datang kembali! Pilih modul di bawah atau pantau aktivitas terbaru sistem laboratorium.</p>
    </div>

    <!-- Layout 2 Kolom: Kiri untuk Grid Modul, Kanan untuk Aktivitas Terbaru -->
    <div class="row g-4">
        
        <!-- KOLOM KIRI: KOTAK MODUL (Lebar 8 Kolom) -->
        <div class="col-12 col-lg-8">
            <h5 class="fw-bold text-dark mb-3">Modul Sistem</h5>
            <div class="row g-3">

                <!-- Aset & Kalibrasi -->
                <div class="col-12 col-sm-6">
                    <a href="#" class="text-decoration-none">
                        <div class="card h-100 shadow-sm rounded-3 border border-light-subtle transition-card">
                            <div class="card-body p-3">
                                <div class="mb-2 module-icon text-primary" style="font-size: 24px;">⛭</div>
                                <h6 class="fw-bold text-dark mb-1">Aset & Kalibrasi</h6>
                                <p class="text-muted small mb-0" style="font-size: 12px;">Kelola alat dan jadwal kalibrasi.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- QC & Parameter Uji -->
                <div class="col-12 col-sm-6">
                    <a href="#" class="text-decoration-none">
                        <div class="card h-100 shadow-sm rounded-3 border border-light-subtle transition-card">
                            <div class="card-body p-3">
                                <div class="mb-2 module-icon text-primary" style="font-size: 24px;">▣</div>
                                <h6 class="fw-bold text-dark mb-1">QC & Parameter Uji</h6>
                                <p class="text-muted small mb-0" style="font-size: 12px;">Kontrol kualitas dan parameter uji.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Inventori Bahan -->
                <div class="col-12 col-sm-6">
                    <a href="#" class="text-decoration-none">
                        <div class="card h-100 shadow-sm rounded-3 border border-light-subtle transition-card">
                            <div class="card-body p-3">
                                <div class="mb-2 module-icon text-primary" style="font-size: 24px;">◔</div>
                                <h6 class="fw-bold text-dark mb-1">Inventori Bahan</h6>
                                <p class="text-muted small mb-0" style="font-size: 12px;">Stok bahan kimia dan reagen.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Audit Log -->
                <div class="col-12 col-sm-6">
                    <a href="#" class="text-decoration-none">
                        <div class="card h-100 shadow-sm rounded-3 border border-light-subtle transition-card">
                            <div class="card-body p-3">
                                <div class="mb-2 module-icon text-primary" style="font-size: 24px;">📋</div>
                                <h6 class="fw-bold text-dark mb-1">Audit Log</h6>
                                <p class="text-muted small mb-0" style="font-size: 12px;">Rekam jejak aktivitas sistem.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- SDM Tile (Menu Utama - Full width di baris bawah grid kiri) -->
                <div class="col-12">
                    <a href="{{ route('sdm.index') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm rounded-3 border-2 transition-card" style="background-color: #faf5ff; border-color: #d8b4fe !important;">
                            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="fs-2" style="color: #6b21a8;">👤</div>
                                    <div>
                                        <h6 class="fw-bold mb-1" style="color: #581c87;">Modul SDM (Sumber Daya Manusia)</h6>
                                        <p class="text-muted small mb-0" style="font-size: 12px;">Kelola personil, kompetensi, sertifikasi, dan hak akses.</p>
                                    </div>
                                </div>
                                <span class="fw-bold" style="color: #6b21a8;">&rarr;</span>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        </div>

        <!-- KOLOM KANAN: PANEL AKTIVITAS TERBARU & INFO (Lebar 4 Kolom) -->
        <div class="col-12 col-lg-4">
            <h5 class="fw-bold text-dark mb-3">Aktivitas Terbaru</h5>
            <div class="card shadow-sm rounded-3 border border-light-subtle mb-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-dark" style="font-size: 14px;">Log Sistem</span>
                        <span class="text-muted" style="font-size: 11px;">10 terakhir, semua role</span>
                    </div>

                    <!-- List Aktivitas -->
                    <div class="border-start ps-3 ms-2 py-1" style="border-color: #dee2e6 !important;">
                        
                        <div class="mb-3 position-relative">
                            <div class="fw-semibold text-dark" style="font-size: 13px;">Admin memperbarui data SDM</div>
                            <div class="text-muted" style="font-size: 11px;">15 menit yang lalu • oleh Admin</div>
                        </div>

                        <div class="mb-2 position-relative">
                            <div class="fw-semibold text-dark" style="font-size: 13px;">Penambahan alat lab baru</div>
                            <div class="text-muted" style="font-size: 11px;">2 jam yang lalu • oleh Teknisi</div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Kartu Informasi Tambahan -->
            <div class="card shadow-sm rounded-3 border border-light-subtle bg-light">
                <div class="card-body p-3">
                    <h6 class="fw-bold text-dark mb-2" style="font-size: 14px;">💡 Informasi Laboratorium</h6>
                    <p class="text-muted mb-0" style="font-size: 12px;">
                        Pastikan melakukan pengecekan jadwal kalibrasi alat secara berkala pada modul Aset & Kalibrasi.
                    </p>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- CSS Tambahan untuk Efek Animasi Kartu -->
<style>
    .transition-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .transition-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.08) !important;
    }
</style>
@endsection