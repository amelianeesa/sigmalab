@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Header -->
    <div class="card mb-4 rounded-3 shadow-sm border-0">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">Dashboard</h2>
                <p class="mb-0 text-muted small">Pilih salah satu modul untuk mengelola data.</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:44px;height:44px;font-weight:700;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U',0,2)) }}
                </div>
                <div class="text-end">
                    <div class="fw-bold">{{ Auth::user()->name ?? 'Pengguna' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modul tiles -->
    <div class="row g-4">

        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border border-200">
                    <div class="card-body">
                        <div class="mb-3" style="font-size:22px">⛭</div>
                        <h5 class="fw-bold text-dark">Aset & Kalibrasi</h5>
                        <p class="text-muted small">Kelola data alat laboratorium dan jadwal kalibrasi berkala.</p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <span class="text-primary">Buka Modul →</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border border-200">
                    <div class="card-body">
                        <div class="mb-3" style="font-size:22px">▣</div>
                        <h5 class="fw-bold text-dark">QC & Parameter Uji</h5>
                        <p class="text-muted small">Kontrol kualitas pengujian dan daftar parameter uji lab.</p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <span class="text-primary">Buka Modul →</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border border-200">
                    <div class="card-body">
                        <div class="mb-3" style="font-size:22px">◔</div>
                        <h5 class="fw-bold text-dark">Inventori Bahan</h5>
                        <p class="text-muted small">Pengelolaan stok bahan kimia, reagen, dan bahan pendukung.</p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <span class="text-primary">Buka Modul →</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- SDM tile: lebih besar dan terhubung ke fitur SDM -->
        <div class="col-12 col-sm-12 col-md-8 col-lg-6">
            <a href="{{ route('sdm.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border-3" style="border-color:#5b21b6;">
                    <div class="card-body">
                        <div class="mb-3 text-3xl" style="font-size:28px;color:#5b21b6">👤</div>
                        <h4 class="fw-bold" style="color:#4c1d95;">SDM</h4>
                        <p class="text-muted small">Kelola data personil, kompetensi, sertifikasi, dan hak akses.</p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <span class="text-primary">Buka Modul SDM →</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-3 border border-200">
                    <div class="card-body">
                        <div class="mb-3" style="font-size:22px">📋</div>
                        <h5 class="fw-bold text-dark">Audit Log</h5>
                        <p class="text-muted small">Rekam jejak aktivitas sistem dan histori perubahan data.</p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <span class="text-primary">Buka Modul →</span>
                    </div>
                </div>
            </a>
        </div>

    </div>

</div>
@endsection