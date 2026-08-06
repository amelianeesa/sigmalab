@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 sdm-theme">

    <!-- Header opsional -->
    <div class="mb-4 text-center">
        <h2 class="fw-bold mb-1">Dashboard Utama</h2>
        <p class="text-muted small">Pilih salah satu modul di bawah ini untuk mengelola data sistem laboratorium.</p>
    </div>

    <!-- Modul Tiles (Grid 3 kolom simetris) -->
    <div class="row g-4 justify-content-center">

        <!-- Aset & Kalibrasi -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border border-light-subtle transition-card">
                    <div class="card-body p-4">
                        <div class="mb-3 module-icon text-primary">⛭</div>
                        <h5 class="fw-bold text-dark mb-2">Aset & Kalibrasi</h5>
                        <p class="text-muted small mb-0">Kelola data alat laboratorium dan jadwal kalibrasi berkala.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- QC & Parameter Uji -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border border-light-subtle transition-card">
                    <div class="card-body p-4">
                        <div class="mb-3 module-icon text-primary">▣</div>
                        <h5 class="fw-bold text-dark mb-2">QC & Parameter Uji</h5>
                        <p class="text-muted small mb-0">Kontrol kualitas pengujian dan daftar parameter uji lab.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Inventori Bahan -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border border-light-subtle transition-card">
                    <div class="card-body p-4">
                        <div class="mb-3 module-icon text-primary">◔</div>
                        <h5 class="fw-bold text-dark mb-2">Inventori Bahan</h5>
                        <p class="text-muted small mb-0">Pengelolaan stok bahan kimia, reagen, dan bahan pendukung.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Audit Log -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border border-light-subtle transition-card">
                    <div class="card-body p-4">
                        <div class="mb-3 module-icon text-primary">📋</div>
                        <h5 class="fw-bold text-dark mb-2">Audit Log</h5>
                        <p class="text-muted small mb-0">Rekam jejak aktivitas sistem dan histori perubahan data.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- SDM Tile (Menu Utama) -->
        <div class="col-12 col-sm-6 col-md-6 col-lg-4">
            <a href="{{ route('sdm.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border-2 transition-card sdm-highlight" style="background-color: var(--sdm-50);">
                    <div class="card-body p-4">
                        <div class="mb-3 module-icon sdm-icon">👤</div>
                        <h5 class="fw-bold mb-2 sdm-text">SDM</h5>
                        <p class="text-muted small mb-0">Kelola personil, kompetensi, sertifikasi, dan hak akses.</p>
                    </div>
                </div>
            </a>
        </div>

    </div>

</div>

<!-- CSS tambahan untuk efek transisi -->
<style>
    .transition-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .transition-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important;
    }
    .module-icon { 
        font-size: 28px; 
    }
</style>
@endsection