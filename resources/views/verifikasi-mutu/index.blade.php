@extends('layouts.app')
@section('title', 'Daftar - Verifikasi Mutu')

@section('content')
<div class="container-fluid px-4 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-1 mt-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Verifikasi Mutu</li>
        </ol>
        <a href="{{ route('parameter-uji.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="fas fa-cogs me-1"></i> Master Parameter Uji
        </a>
    </div>
    <h1 class="mb-4 fw-bold text-dark">Portal Verifikasi Mutu (QC)</h1>
    <p class="text-muted mb-4">Silakan pilih pilar Quality Control yang ingin Anda kelola.</p>

    <div class="row g-4">
        <!-- QC In-House -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 hover-lift" style="transition: transform 0.2s; cursor: pointer;" ondblclick="window.location='{{ route('qc-inhouse.index') }}'">
                <div class="card-body text-center p-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                        <i class="fas fa-vial fa-3x text-primary"></i>
                    </div>
                    <h4 class="card-title fw-bold">QC In-House</h4>
                    <p class="card-text text-muted small">Penyiapan Uji Homogenitas, Stabilitas, & Target dari sampel internal.</p>
                    <div class="mt-4">
                        <a href="{{ route('qc-inhouse.index') }}" class="btn btn-primary rounded-pill px-4">Masuk <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 text-center pb-3">
                    <span class="badge bg-light text-primary border border-primary">{{ $inhouseCount ?? 0 }} Sampel Aktif</span>
                </div>
            </div>
        </div>

        <!-- QC CRM -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 hover-lift" style="transition: transform 0.2s; cursor: pointer;" ondblclick="window.location='{{ route('qc-crm.index') }}'">
                <div class="card-body text-center p-4">
                    <div class="bg-purple bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3" style="background-color: rgba(111, 66, 193, 0.1);">
                        <i class="fas fa-certificate fa-3x text-purple" style="color: #6f42c1;"></i>
                    </div>
                    <h4 class="card-title fw-bold">QC CRM</h4>
                    <p class="card-text text-muted small">Kelola histori pengujian harian berdasarkan Sertifikat (True Value).</p>
                    <div class="mt-4">
                        <a href="{{ route('qc-crm.index') }}" class="btn rounded-pill px-4" style="background-color: #6f42c1; color: white;">Masuk <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 text-center pb-3">
                    <span class="badge bg-light text-purple border" style="color: #6f42c1; border-color: #6f42c1 !important;">
                        {{ \App\Models\QcCrm::count() }} Pengujian Harian
                    </span>
                </div>
            </div>
        </div>

        <!-- QC Uji Banding -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 hover-lift" style="transition: transform 0.2s; cursor: pointer;" ondblclick="window.location='{{ route('qc-uji-banding.index') }}'">
                <div class="card-body text-center p-4">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                        <i class="fas fa-globe fa-3x text-info"></i>
                    </div>
                    <h4 class="card-title fw-bold">QC Uji Banding</h4>
                    <p class="card-text text-muted small">Proficiency Test (Blind Test) dengan ekspor Lembar Ketidaksesuaian.</p>
                    <div class="mt-4">
                        <a href="{{ route('qc-uji-banding.index') }}" class="btn btn-info text-white rounded-pill px-4">Masuk <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 text-center pb-3">
                    <span class="badge bg-light text-info border border-info">
                        {{ \App\Models\QcUjiBanding::count() }} Program Terdaftar
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection
