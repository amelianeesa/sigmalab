@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Dashboard</h2>
            <p class="text-muted mb-0">Pilih modul laboratorium untuk mengelola data.</p>
        </div>

        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width:44px;height:44px;">
                {{ strtoupper(substr(Auth::user()->name ?? 'U',0,1)) }}
            </div>
            <div class="me-3 text-end">
                <div class="fw-semibold">{{ Auth::user()->name ?? 'Pengguna' }}</div>
                <small class="text-muted">Login sebagai {{ strtoupper(Auth::user()->role ?? 'USER') }}</small>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-secondary btn-sm">Logout</button>
            </form>
        </div>
    </div>

    <!-- Grid Modul (tanpa KPI) -->
    <div class="row g-4">

        <div class="col-12 col-md-6 col-lg-4">
            <a href="#" class="text-decoration-none text-reset">
                <div class="card h-100 shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <div class="mb-3" style="font-size:22px">⛭</div>
                        <h5 class="card-title">Aset & Kalibrasi</h5>
                        <p class="text-muted small">Kelola data alat dan jadwal kalibrasi</p>
                        <div class="mt-3 text-primary fw-semibold">Buka Modul →</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <a href="#" class="text-decoration-none text-reset">
                <div class="card h-100 shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <div class="mb-3" style="font-size:22px">▣</div>
                        <h5 class="card-title">QC & Parameter Uji</h5>
                        <p class="text-muted small">Kontrol kualitas pengujian dan parameter</p>
                        <div class="mt-3 text-primary fw-semibold">Buka Modul →</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <a href="#" class="text-decoration-none text-reset">
                <div class="card h-100 shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <div class="mb-3" style="font-size:22px">◔</div>
                        <h5 class="card-title">Inventori Bahan</h5>
                        <p class="text-muted small">Stok bahan kimia, reagen, dan pendukung</p>
                        <div class="mt-3 text-primary fw-semibold">Buka Modul →</div>
                    </div>
                </div>
            </a>
        </div>

        <!-- SDM: tile lebih besar dan linked ke fitur SDM -->
        <div class="col-12 col-md-12 col-lg-6">
            <a href="{{ route('sdm.index') }}" class="text-decoration-none text-reset">
                <div class="card h-100 shadow-sm rounded-3 border-2 border-primary">
                    <div class="card-body">
                        <div class="mb-3 text-primary" style="font-size:26px">👤</div>
                        <h4 class="card-title text-primary">SDM</h4>
                        <p class="text-muted small">Kelola personil, kompetensi, sertifikasi, dan hak akses</p>
                        <div class="mt-3 text-primary fw-semibold">Buka Modul →</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <a href="#" class="text-decoration-none text-reset">
                <div class="card h-100 shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <div class="mb-3" style="font-size:22px">📋</div>
                        <h5 class="card-title">Audit Log</h5>
                        <p class="text-muted small">Rekam jejak aktivitas sistem</p>
                        <div class="mt-3 text-primary fw-semibold">Buka Modul →</div>
                    </div>
                </div>
            </a>
        </div>

    </div>

</div>
@endsection