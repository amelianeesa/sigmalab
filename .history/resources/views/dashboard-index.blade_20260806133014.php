@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <span class="badge rounded-pill bg-sdm-600 bg-opacity-10 text-sdm-600 mb-2">Dashboard Utama</span>
            <h2 class="fw-bold text-dark mb-1">Sistem Laboratorium PT Sucofindo</h2>
            <p class="text-muted mb-0">Akses cepat ke modul penting untuk pengelolaan personil, aset, inventori, kontrol kualitas, dan audit log.</p>
        </div>
        <div class="text-md-end">
            <small class="text-muted">Tampilan dirancang agar profesional, komunikatif, dan mudah digunakan.</small>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <a href="{{ route('sdm.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm module-card border-start border-4 border-sdm-600">
                    <div class="card-body d-flex gap-3 p-4">
                        <div class="module-icon bg-sdm-50 text-sdm-600">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-1 text-dark">SDM</h5>
                            <p class="text-muted small mb-2">Kelola personil, kompetensi, sertifikasi, dan hak akses.</p>
                            <span class="badge rounded-pill bg-sdm-600 bg-opacity-10 text-sdm-600">Akses SDM</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm module-card">
                    <div class="card-body d-flex gap-3 p-4">
                        <div class="module-icon bg-primary bg-opacity-15 text-primary">
                            <i class="bi bi-gear-wide-connected"></i>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-1 text-dark">Aset & Kalibrasi</h5>
                            <p class="text-muted small mb-0">Kelola data alat laboratorium dan jadwal kalibrasi.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm module-card">
                    <div class="card-body d-flex gap-3 p-4">
                        <div class="module-icon bg-info bg-opacity-15 text-info">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-1 text-dark">Inventori Bahan</h5>
                            <p class="text-muted small mb-0">Pengelolaan stok bahan kimia, reagen, dan bahan pendukung.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm module-card">
                    <div class="card-body d-flex gap-3 p-4">
                        <div class="module-icon bg-warning bg-opacity-15 text-warning">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-1 text-dark">QC & Parameter Uji</h5>
                            <p class="text-muted small mb-0">Kontrol kualitas pengujian dan daftar parameter uji lab.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm module-card">
                    <div class="card-body d-flex gap-3 p-4">
                        <div class="module-icon bg-success bg-opacity-15 text-success">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-1 text-dark">Audit Log</h5>
                            <p class="text-muted small mb-0">Rekam jejak aktivitas sistem dan histori perubahan data.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
    .module-card {
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .module-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 18px 48px rgba(15,35,59,.08);
    }
    .module-icon {
        width: 54px;
        height: 54px;
        min-width: 54px;
        border-radius: 18px;
        display: grid;
        place-items: center;
        font-size: 1.35rem;
    }
    .sdm-card {
        background-color: #faf5ff;
    }
    .text-purple {
        color: #7e22ce;
    }
    .border-sdm-600 {
        border-color: var(--sdm-600) !important;
    }
    .bg-sdm-50 {
        background-color: var(--sdm-50) !important;
    }
    .text-sdm-600 {
        color: var(--sdm-600) !important;
    }
</style>
@endsection