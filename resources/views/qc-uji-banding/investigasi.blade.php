@extends('layouts.app')
@section('title', 'Investigasi - QC Uji Banding')

@section('content')
<style>
    .dashboard-container {
        padding: 0 20px !important;
        margin-top: -8px !important;
    }

    .dashboard-container nav[aria-label="breadcrumb"],
    .dashboard-container > nav {
        margin: 0 !important;
        padding: 0 !important;
    }

    .dashboard-container .breadcrumb {
        margin: 0 0 6px 0 !important;
        padding: 0 !important;
        font-size: 0.75rem !important;
        line-height: 1.4;
        flex-wrap: wrap;
        align-items: center;
        background: transparent !important;
    }

    .dashboard-container .breadcrumb .breadcrumb-item,
    .dashboard-container .breadcrumb .breadcrumb-item a {
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        color: #0d6efd !important;
        text-decoration: none;
    }

    .dashboard-container .breadcrumb .breadcrumb-item a:hover {
        color: #0a58ca !important;
        text-decoration: underline;
    }

    .dashboard-container .breadcrumb .breadcrumb-item.active {
        color: #000000 !important;
        font-weight: 700 !important;
    }

    .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item {
        padding-left: 0.4rem;
    }

    .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        color: #6c757d !important;
        padding-right: 0.4rem;
        font-weight: 400;
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu {
        min-width: 190px;
        padding: 4px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item {
        color: #000000 !important;
        font-size: 0.78rem !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        background-color: transparent;
        padding: 7px 12px;
        border-radius: 5px;
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:hover,
    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:focus,
    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:active,
    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item.active {
        background-color: rgba(27, 49, 82, 0.15) !important;
        color: #000000 !important;
        text-decoration: none !important;
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item.active {
        font-weight: 700 !important;
    }

    .card-body {
        padding: 12px !important;
    }

    .card-header {
        padding: 10px 14px !important;
    }

    .card-header h5 {
        font-size: 0.9rem !important;
        font-weight: 700;
        margin: 0;
    }

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

    .btn-outline-corporate {
        color: #1b3152 !important;
        border-color: #1b3152 !important;
    }

    .btn-outline-corporate:hover,
    .btn-outline-corporate:focus {
        background-color: #1b3152 !important;
        color: #ffffff !important;
    }

    .lks-card {
        border-left-width: 4px !important;
        border-left-style: solid !important;
        transition: box-shadow 0.2s ease;
    }

    .lks-card:hover {
        box-shadow: 0 6px 18px rgba(27, 49, 82, 0.12) !important;
    }

    .lks-card {
        border-left-color: #1b3152 !important;
    }

    .lks-hint {
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 8px;
    }

    .lks-textarea {
        font-size: 0.82rem !important;
        resize: vertical;
    }

    .lks-textarea:focus {
        border-color: #1b3152;
        box-shadow: 0 0 0 0.2rem rgba(27, 49, 82, 0.15);
    }

    .lks-summary {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 6px;
    }

    .lks-chip {
        background: #eaf0f8;
        color: #1b3152;
        border-radius: 999px;
        padding: 3px 10px;
        font-size: 0.72rem;
        font-weight: 600;
    }

    @media (max-width: 767.98px) {
        .dashboard-container {
            padding: 0 10px !important;
        }

        .dashboard-container .breadcrumb,
        .dashboard-container .breadcrumb .breadcrumb-item,
        .dashboard-container .breadcrumb .breadcrumb-item a {
            font-size: 0.72rem !important;
        }

        .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item {
            padding-left: 0.3rem;
        }

        .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            padding-right: 0.3rem;
        }

        .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item {
            font-size: 0.85rem !important;
            padding: 10px 14px;
        }

        .form-actions .btn {
            flex: 1 1 0;
        }
    }
</style>

<div class="container-fluid dashboard-container pb-4" style="font-size: 0.82rem;">
    <x-qc-breadcrumb active="Uji Banding">
        <li class="breadcrumb-item"><a href="{{ route('qc-uji-banding.show', $program->id) }}">{{ $program->nama_program }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('qc-uji-banding.ringkasan', $program->id) }}">Ringkasan Unjuk Kerja</a></li>
        <li class="breadcrumb-item active" aria-current="page">Lembar Ketidaksesuaian</li>
    </x-qc-breadcrumb>

    <div class="mb-3">
        <h5 class="fw-bold mb-1" style="font-size: 1.1rem;">
            <i class="fas fa-file-alt me-2" style="color: #1b3152;"></i>Lembar Ketidaksesuaian (LKS) / CAPA
        </h5>
        <p class="text-muted mb-0" style="font-size: 0.78rem;">
            Investigasi untuk parameter <span class="fw-bold text-dark">{{ $parameter->parameterUji->nama_parameter }}</span> pada Uji Banding <span class="fw-bold text-dark">{{ $program->nama_program }}</span>.
        </p>
        <div class="lks-summary">
            <span class="lks-chip">Nilai Lab: {{ $parameter->nilai_akhir }}</span>
            <span class="lks-chip">Z-Score: {{ $parameter->z_score ?? '-' }}</span>
        </div>
    </div>

    <form action="{{ route('qc-uji-banding.store-investigasi', [$program->id, $parameter->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm mb-3 lks-card">
            <div class="card-header text-white" style="background-color: #1b3152;">
                <h5><i class="fas fa-search me-1"></i> Bagian 1: Analisis Akar Masalah</h5>
            </div>
            <div class="card-body">
                <p class="lks-hint">Jelaskan secara detail faktor utama yang menyebabkan hasil lab ({{ $parameter->nilai_akhir }}) menyimpang dari Z-Score/Target Vendor (Z: {{ $parameter->z_score ?? '-' }}).</p>
                <textarea name="akar_masalah" class="form-control lks-textarea" rows="5" required placeholder="Contoh: Kondisi alat belum dikalibrasi ulang, suhu ruangan tidak stabil...">{{ $parameter->akar_masalah }}</textarea>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3 lks-card">
            <div class="card-header text-white" style="background-color: #1b3152;">
                <h5><i class="fas fa-tools me-1"></i> Bagian 2: Tindakan Perbaikan</h5>
            </div>
            <div class="card-body">
                <p class="lks-hint">Tindakan langsung yang diambil untuk mengoreksi ketidaksesuaian saat ini.</p>
                <textarea name="tindakan_perbaikan" class="form-control lks-textarea" rows="4" required placeholder="Contoh: Melakukan kalibrasi ulang pada timbangan, memanaskan oven lebih lama...">{{ $parameter->tindakan_perbaikan }}</textarea>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3 lks-card">
            <div class="card-header text-white" style="background-color: #1b3152;">
                <h5><i class="fas fa-shield-alt me-1"></i> Bagian 3: Tindakan Pencegahan</h5>
            </div>
            <div class="card-body">
                <p class="lks-hint">Tindakan proaktif untuk mencegah masalah yang sama terulang di masa depan.</p>
                <textarea name="tindakan_pencegahan" class="form-control lks-textarea" rows="4" placeholder="Contoh: Membuat jadwal kalibrasi internal setiap 2 minggu...">{{ $parameter->tindakan_pencegahan }}</textarea>
            </div>
        </div>

        <div class="form-actions d-flex flex-row justify-content-end gap-2">
            <a href="{{ route('qc-uji-banding.show', $program->id) }}" class="btn btn-outline-corporate btn-sm py-1.5 px-3 shadow-sm fw-semibold" style="font-size: 0.8rem;">Batal</a>
            <button type="submit" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold" style="font-size: 0.8rem;">
                <i class="fas fa-save me-1"></i> Simpan LKS
            </button>
        </div>
    </form>
</div>
@endsection