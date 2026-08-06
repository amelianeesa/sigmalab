@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="mb-4">
        <h3 class="fw-bold text-dark mb-1">Dashboard Utama</h3>
        <p class="text-muted small mb-0">Pilih salah satu modul di bawah ini untuk mengelola data sistem laboratorium.</p>
    </div>

    <div class="row g-3">
        <div class="col-12 col-sm-6 col-lg-4">
            <a href="{{ route('sdm.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border transition-card sdm-card">
                    <div class="card-body p-3">
                        <div class="mb-2 fs-3 text-purple">👤</div>
                        <h6 class="fw-bold text-dark mb-1">SDM</h6>
                        <p class="text-muted small mb-0" style="font-size: 12px;">Kelola personil, kompetensi, sertifikasi, dan hak akses.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border border-light-subtle transition-card">
                    <div class="card-body p-3">
                        <div class="mb-2 fs-3 text-primary">⛭</div>
                        <h6 class="fw-bold text-dark mb-1">Aset & Kalibrasi</h6>
                        <p class="text-muted small mb-0" style="font-size: 12px;">Kelola data alat laboratorium dan jadwal kalibrasi.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border border-light-subtle transition-card">
                    <div class="card-body p-3">
                        <div class="mb-2 fs-3 text-primary">◔</div>
                        <h6 class="fw-bold text-dark mb-1">Inventori Bahan</h6>
                        <p class="text-muted small mb-0" style="font-size: 12px;">Pengelolaan stok bahan kimia, reagen, dan bahan pendukung.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border border-light-subtle transition-card">
                    <div class="card-body p-3">
                        <div class="mb-2 fs-3 text-primary">▣</div>
                        <h6 class="fw-bold text-dark mb-1">QC & Parameter Uji</h6>
                        <p class="text-muted small mb-0" style="font-size: 12px;">Kontrol kualitas pengujian dan daftar parameter uji lab.</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border border-light-subtle transition-card">
                    <div class="card-body p-3">
                        <div class="mb-2 fs-3 text-primary">📋</div>
                        <h6 class="fw-bold text-dark mb-1">Audit Log</h6>
                        <p class="text-muted small mb-0" style="font-size: 12px;">Rekam jejak aktivitas sistem dan histori perubahan data.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
    .transition-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .transition-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 .25rem .75rem rgba(0,0,0,.08) !important;
    }
    .sdm-card {
        background-color: #faf5ff;
        border-color: #e9d5ff !important;
    }
    .text-purple {
        color: #7e22ce;
    }
</style>
@endsection