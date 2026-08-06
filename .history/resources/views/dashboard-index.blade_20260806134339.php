@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Dashboard Utama</h2>
        <p class="text-muted mb-0">Pilih fitur yang ingin Anda gunakan untuk mengelola sistem laboratorium.</p>
    </div>

    <div class="row g-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <a href="{{ route('sdm.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm module-card border-start border-4 border-sdm-600">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="module-icon bg-icon text-sdm-600">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-1 text-dark">SDM</h5>
                            <p class="text-muted small mb-2">Kelola personil, kompetensi, sertifikasi, dan hak akses.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm module-card border-start border-4 border-sdm-600">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="module-icon bg-icon text-sdm-600">
                            <i class="bi bi-gear-wide-connected"></i>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-1 text-dark">Alat dan Kalibrasi</h5>
                            <p class="text-muted small mb-0">Kelola data alat laboratorium dan jadwal kalibrasi.</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm module-card border-start border-4 border-sdm-600">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="module-icon bg-icon text-sdm-600">
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
    </div>

    <div class="row justify-content-center g-4 mt-1">
        <div class="col-12 col-sm-6 col-lg-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm module-card border-start border-4 border-sdm-600">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="module-icon bg-icon text-sdm-600">
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
                <div class="card h-100 border-0 shadow-sm module-card border-start border-4 border-sdm-600">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="module-icon bg-icon text-sdm-600">
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
        border-radius: 14px;
        display: grid;
        place-items: center;
        font-size: 1.35rem;
        border: 1px solid rgba(29,76,122,.16);
        box-shadow: inset 0 1px 0 rgba(255,255,255,.75);
    }
    .bg-icon {
        background-color: #eef4fb !important;
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