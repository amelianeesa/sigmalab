@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">Dashboard</h2>
                <p class="mb-0 text-muted small">Pilih salah satu modul untuk mengelola data operasional laboratorium.</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm" style="width:44px;height:44px;font-weight:700;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U',0,2)) }}
                </div>
                <div class="text-end d-none d-sm-block">
                    <div class="fw-bold">{{ Auth::user()->name ?? 'Pengguna' }}</div>
                    <div class="text-muted" style="font-size: 11px;">Admin Sistem</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modul Tiles -->
    <div class="row g-4">

        <!-- Aset & Kalibrasi -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border border-light-subtle transition-card">
                    <div class="card-body">
                        <div class="mb-3 text-primary" style="font-size:24px">⛭</div>
                        <h5 class="fw-bold text-dark mb-2">Aset & Kalibrasi</h5>
                        <p class="text-muted small mb-0">Kelola data alat laboratorium dan jadwal kalibrasi berkala.</p>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0 pb-3">
                        <span class="text-primary small fw-semibold">Buka Modul &rarr;</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- QC & Parameter Uji -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border border-light-subtle transition-card">
                    <div class="card-body">
                        <div class="mb-3 text-primary" style="font-size:24px">▣</div>
                        <h5 class="fw-bold text-dark mb-2">QC & Parameter Uji</h5>
                        <p class="text-muted small mb-0">Kontrol kualitas pengujian dan daftar parameter uji lab.</p>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0 pb-3">
                        <span class="text-primary small fw-semibold">Buka Modul &rarr;</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Inventori Bahan -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border border-light-subtle transition-card">
                    <div class="card-body">
                        <div class="mb-3 text-primary" style="font-size:24px">◔</div>
                        <h5 class="fw-bold text-dark mb-2">Inventori Bahan</h5>
                        <p class="text-muted small mb-0">Pengelolaan stok bahan kimia, reagen, dan bahan pendukung.</p>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0 pb-3">
                        <span class="text-primary small fw-semibold">Buka Modul &rarr;</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Audit Log -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border border-light-subtle transition-card">
                    <div class="card-body">
                        <div class="mb-3 text-primary" style="font-size:24px">📋</div>
                        <h5 class="fw-bold text-dark mb-2">Audit Log</h5>
                        <p class="text-muted small mb-0">Rekam jejak aktivitas sistem dan histori perubahan data.</p>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0 pb-3">
                        <span class="text-primary small fw-semibold">Buka Modul &rarr;</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- SDM Tile: Dibuat Full Lebar/Menarik Perhatian karena ini Modul Utama Kamu -->
        <div class="col-12">
            <a href="{{ route('sdm.index') }}" class="text-decoration-none">
                <div class="card shadow-sm rounded-3 border-2 transition-card bg-light-subtle" style="border-color:#5b21b6 !important;">
                    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center p-4">
                        <div class="d-flex align-items-start gap-3 mb-3 mb-md-0">
                            <div class="rounded-3 bg-white p-3 shadow-sm text-center" style="min-width: 60px; font-size: 28px;">
                                👤
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1" style="color:#4c1d95;">Modul Sumber Daya Manusia (SDM)</h4>
                                <p class="text-muted small mb-0">Kelola data master personil, kompetensi, sertifikasi, riwayat pelatihan, hingga pengaturan hak akses pengguna.</p>
                            </div>
                        </div>
                        <div class="btn btn-dark px-4 fw-semibold shadow-sm" style="background-color: #5b21b6; border-color: #5b21b6;">
                            Buka Modul SDM &rarr;
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>

</div>

<!-- Tambahan sedikit CSS untuk efek transisi card yang halus -->
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