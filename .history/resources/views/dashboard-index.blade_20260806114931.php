@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">


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

        <!-- SDM Tile: align with other tiles -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="{{ route('sdm.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border transition-card sdm-highlight" style="border-color:#5b21b6;">
                    <div class="card-body">
                        <div class="mb-3" style="font-size:24px;color:#5b21b6;">👤</div>
                        <h5 class="fw-bold text-dark mb-2" style="color:#4c1d95;">SDM</h5>
                        <p class="text-muted small mb-0">Kelola personil, kompetensi, sertifikasi, dan hak akses.</p>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0 pb-3">
                        <span class="text-primary small fw-semibold">Buka Modul SDM &rarr;</span>
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