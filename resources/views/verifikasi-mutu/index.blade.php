@extends('layouts.app')
@section('title', 'Daftar - Verifikasi Mutu')

@section('content')
<style>
    /* Warna Tombol Corporate Blue */
    .btn-corporate-blue {
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
        color: #ffffff !important;
    }
    .btn-corporate-blue:hover, 
    .btn-corporate-blue:focus, 
    .btn-corporate-blue:active {
        background-color: #14253e !important;
        border-color: #14253e !important;
        color: #ffffff !important;
    }
    
    /* Animasi Kartu Melayang */
    .hover-lift {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.08) !important;
    }
</style>

<div class="container-fluid px-4 pb-5">
        <div class="mb-2 mt-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Verifikasi Mutu</li>
        </ol>
    </div>
    
    <h1 class="mb-2 fw-bold text-dark">Portal Verifikasi Mutu (QC)</h1>
    <p class="text-muted mb-3">Silakan pilih pilar Quality Control yang ingin Anda kelola.</p>
    
    <div class="mb-4">
        <a href="{{ route('parameter-uji.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="fas fa-cogs me-1"></i> Master Parameter Uji
        </a>
    </div>

    <!-- 3 Pilar QC Card (Corporate Design) -->
    <div class="row g-3 mb-4">
        
        <!-- Kartu 1: QC In-House -->
        <div class="col-12 col-md-4">
            <div class="card h-100 shadow-sm border-0 hover-lift" style="cursor: pointer;" ondblclick="window.location='{{ route('qc-inhouse.index') }}'">
                <div class="card-body text-center p-3">
                    <div class="rounded-circle d-inline-flex p-3 mb-2" style="background-color: rgba(27, 49, 82, 0.08);">
                        <i class="fas fa-vial fa-2x" style="color: #1b3152;"></i>
                    </div>
                    <h6 class="card-title fw-bold mb-1" style="font-size: 0.9rem;">QC In-House</h6>
                    <p class="card-text text-muted mb-3" style="font-size: 0.68rem;">Penyiapan Uji Homogenitas, Stabilitas, & Target dari sampel internal.</p>
                    <a href="{{ route('qc-inhouse.index') }}" class="btn btn-corporate-blue btn-sm rounded-pill px-3 py-1 shadow-sm" style="font-size: 0.72rem;">Masuk <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                <div class="card-footer bg-white border-top-0 text-center pb-2 pt-0">
                    <span class="badge bg-light border" style="font-size: 0.65rem; color: #1b3152; border-color: #1b3152 !important;">{{ $inhouseCount ?? 0 }} Sampel Aktif</span>
                </div>
            </div>
        </div>

        <!-- Kartu 2: QC CRM -->
        <div class="col-12 col-md-4">
            <div class="card h-100 shadow-sm border-0 hover-lift" style="cursor: pointer;" ondblclick="window.location='{{ route('qc-crm.index') }}'">
                <div class="card-body text-center p-3">
                    <div class="rounded-circle d-inline-flex p-3 mb-2" style="background-color: rgba(27, 49, 82, 0.08);">
                        <i class="fas fa-certificate fa-2x" style="color: #1b3152;"></i>
                    </div>
                    <h6 class="card-title fw-bold mb-1" style="font-size: 0.9rem;">QC CRM</h6>
                    <p class="card-text text-muted mb-3" style="font-size: 0.68rem;">Pengujian berdasarkan sertifikat (True Value) Certified Reference Material.</p>
                    <a href="{{ route('qc-crm.index') }}" class="btn btn-corporate-blue btn-sm rounded-pill px-3 py-1 shadow-sm" style="font-size: 0.72rem;">Masuk <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                <div class="card-footer bg-white border-top-0 text-center pb-2 pt-0">
                    <span class="badge bg-light text-muted border" style="font-size: 0.65rem;">{{ \App\Models\QcCrm::count() ?? 0 }} Kegiatan</span>
                </div>
            </div>
        </div>

        <!-- Kartu 3: QC Uji Banding -->
        <div class="col-12 col-md-4">
            <div class="card h-100 shadow-sm border-0 hover-lift" style="cursor: pointer;" ondblclick="window.location='{{ route('qc-uji-banding.index') }}'">
                <div class="card-body text-center p-3">
                    <div class="rounded-circle d-inline-flex p-3 mb-2" style="background-color: rgba(27, 49, 82, 0.08);">
                        <i class="fas fa-globe fa-2x" style="color: #1b3152;"></i>
                    </div>
                    <h6 class="card-title fw-bold mb-1" style="font-size: 0.9rem;">QC Uji Banding</h6>
                    <p class="card-text text-muted mb-3" style="font-size: 0.68rem;">Komparasi hasil uji lab dengan vendor / Interlaboratory.</p>
                    <a href="{{ route('qc-uji-banding.index') }}" class="btn btn-corporate-blue btn-sm rounded-pill px-3 py-1 shadow-sm" style="font-size: 0.72rem;">Masuk <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                <div class="card-footer bg-white border-top-0 text-center pb-2 pt-0">
                    <span class="badge bg-light text-muted border" style="font-size: 0.65rem;">{{ \App\Models\QcUjiBanding::count() ?? 0 }} Program Terdaftar</span>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection