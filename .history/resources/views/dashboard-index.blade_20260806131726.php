@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Header Sederhana -->
    <div class="mb-4">
        <h3 class="fw-bold text-dark mb-1">Dashboard Utama</h3>
        <p class="text-muted small mb-0">Pilih modul di bawah ini untuk mengelola data sistem laboratorium.</p>
    </div>

    <!-- Grid Modul & Aktivitas (Total 6 slot: 5 modul + 1 panel aktivitas) -->
    <div class="row g-3">

        <!-- SDM (Modul Utama) -->
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

        <!-- AKTIVITAS TERBARU (Ditaruh tepat di sebelah SDM agar formasi grid 3x2 rapi) -->
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 shadow-sm rounded-3 border border-light-subtle">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-dark" style="font-size: 14px;">Aktivitas Terbaru</span>
                        <span class="text-muted" style="font-size: 11px;">10 terakhir</span>
                    </div>

                    <div class="border-start ps-3 ms-1 py-1" style="border-color: #dee2e6 !important;">
                        <div class="mb-2 position-relative">
                            <div class="fw-semibold text-dark" style="font-size: 12px;">Admin memperbarui data SDM</div>
                            <div class="text-muted" style="font-size: 10px;">15 menit lalu • oleh Admin</div>
                        </div>

                        <div class="position-relative">
                            <div class="fw-semibold text-dark" style="font-size: 12px;">Penambahan alat lab baru</div>
                            <div class="text-muted" style="font-size: 10px;">2 jam lalu • oleh Teknisi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aset & Kalibrasi -->
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

        <!-- QC & Parameter Uji -->
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

        <!-- Inventori Bahan -->
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

        <!-- Audit Log -->
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

<!-- CSS Styling Sederhana -->
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