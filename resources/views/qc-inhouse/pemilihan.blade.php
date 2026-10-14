@extends('layouts.app')
@section('title', 'Pemilihan Sampel - QC In-House')

@section('content')
<style>
    .dashboard-container {
        padding: 0 20px !important;
        margin-top: -8px !important;
        font-size: 0.78rem;
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

    .card-body {
        padding: 12px !important;
    }

    .page-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .page-subtitle {
        font-size: 0.72rem;
        margin-bottom: 10px;
        line-height: 1.4;
        color: #6c757d;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 1px;
    }

    .section-note {
        font-size: 0.68rem;
        margin-bottom: 8px;
        line-height: 1.4;
        color: #6c757d;
    }

    .step-badge {
        background-color: #1b3152;
        color: #ffffff !important;
        font-size: 0.68rem;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 4px;
        margin-right: 6px;
    }

    .icon-corporate {
        color: #1b3152;
    }

    .form-label {
        font-size: 0.74rem;
        font-weight: 600;
        margin-bottom: 3px;
        color: #000000;
    }

    .form-control {
        font-size: 0.78rem;
        padding-top: 0.28rem;
        padding-bottom: 0.28rem;
        color: #000000;
    }

    .form-control[readonly] {
        background-color: #f1f4f8;
        color: #000000;
        font-weight: 600;
    }

    .input-group-text {
        font-size: 0.75rem;
        background-color: #e9ecef;
        color: #495057;
        font-weight: 600;
    }

    .fc-box {
        background-color: #f8f9fb;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 8px 12px;
    }

    .fc-title {
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 1px;
        color: #000000;
    }

    .fc-note {
        font-size: 0.68rem;
        color: #6c757d;
        margin-bottom: 0;
    }

    #fc_display {
        font-size: 1.15rem;
    }

    .sop-card {
        background-color: #1b3152;
        color: #ffffff;
        border: 0 !important;
        border-radius: 0.5rem;
    }

    .sop-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 12px 4px 12px;
    }

    .sop-card .sop-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 0;
    }

    .sop-content {
        padding: 6px 12px 12px 12px;
    }

    .sop-card p {
        font-size: 0.76rem;
        line-height: 1.5;
        color: #e8eef7;
    }

    .sop-card strong {
        color: #ffffff;
    }

    .btn-kembali {
        background-color: #6c757d !important;
        border: 1px solid #6c757d !important;
        color: #ffffff !important;
    }

    .btn-kembali:hover {
        background-color: #5c636a !important;
        color: #ffffff !important;
    }

    .btn-corporate-blue {
        background-color: #1b3152 !important;
        border: 1px solid #1b3152 !important;
        color: #ffffff !important;
    }

    .btn-corporate-blue:hover {
        background-color: #14253e !important;
        color: #ffffff !important;
    }
</style>

<div class="container-fluid dashboard-container">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $batch->sampel_inhouse_id) }}">{{ $batch->nama_sampel }}</a></li>
        <li class="breadcrumb-item active">Tahap 1: Pemilihan Sampel</li>
    </x-qc-breadcrumb>

    <div class="row g-2">
        <div class="col-xl-9 col-lg-8 order-2 order-lg-1">
            <div class="card shadow-sm border-0 mb-2">
                <div class="card-body">
                    <h5 class="page-title text-dark"><i class="fas fa-clipboard-list icon-corporate me-2"></i>Tahap 1: Screening & Pemilihan Sampel</h5>
                    <p class="page-subtitle">Spesifikasi kandidat sampel standar batubara hasil screening awal yang menjadi acuan pengujian homogenitas dan stabilitas.</p>

                    <div class="mb-3">
                        <h6 class="section-title text-dark border-bottom pb-2 mb-2"><span class="step-badge">Identitas</span>Informasi Sampel Batubara</h6>
                        <div class="row g-2">
                            <div class="col-12 col-md-4">
                                <label class="form-label">Nama Sampel</label>
                                <input type="text" class="form-control" value="{{ $batch->nama_sampel }}" readonly>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label">Jenis Batubara</label>
                                <input type="text" class="form-control text-uppercase" value="{{ str_replace('_', ' ', $batch->jenis_batubara) }}" readonly>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label">Dibuat Oleh</label>
                                <input type="text" class="form-control" value="{{ $batch->pembuat->username ?? '-' }} ({{ $batch->tanggal_pemilihan ? $batch->tanggal_pemilihan->format('d/m/Y') : '-' }})" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 mb-2 border-bottom pb-1">
                        <h6 class="section-title text-dark"><i class="fas fa-flask icon-corporate me-2"></i>Data Analisis Pemilihan (Screening Kasar)</h6>
                        <p class="section-note">Hukum Fisika Batubara: TM > MAD dan Total Proksimat (MAD + Ash + VM) &lt; 100%.</p>
                    </div>

                    <div class="row g-2">
                        <div class="col-12 col-md-6 col-xl-4">
                            <label class="form-label">Total Moisture (TM)</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" value="{{ isset($screening['tm']) ? number_format($screening['tm'], 2) : '-' }}" readonly>
                                <span class="input-group-text">% AR</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xl-4">
                            <label class="form-label">Moisture in Analysis (MAD)</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" value="{{ isset($screening['mad']) ? number_format($screening['mad'], 2) : '-' }}" readonly>
                                <span class="input-group-text">% ADB</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xl-4">
                            <label class="form-label">Ash Content</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" value="{{ isset($screening['ash']) ? number_format($screening['ash'], 2) : '-' }}" readonly>
                                <span class="input-group-text">% ADB</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xl-4">
                            <label class="form-label">Volatile Matter (VM)</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" value="{{ isset($screening['vm']) ? number_format($screening['vm'], 2) : '-' }}" readonly>
                                <span class="input-group-text">% ADB</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xl-4">
                            <label class="form-label">Total Sulfur (TS)</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" value="{{ isset($screening['ts']) ? number_format($screening['ts'], 2) : '-' }}" readonly>
                                <span class="input-group-text">% ADB</span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-xl-4">
                            <label class="form-label">Gross Calorific Value (GCV)</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" value="{{ isset($screening['gcv']) ? number_format($screening['gcv'], 0, ',', '.') : '-' }}" readonly>
                                <span class="input-group-text">Kcal/kg</span>
                            </div>
                        </div>
                    </div>

                    <div class="fc-box mt-3">
                        <div class="row align-items-center g-2">
                            <div class="col-8 col-md-9">
                                <h6 class="fc-title">Fixed Carbon (FC) Kalkulasi Keseimbangan</h6>
                                <p class="fc-note">Formula: 100 - (MAD + Ash + VM)</p>
                            </div>
                            <div class="col-4 col-md-3 text-end">
                                <h4 class="fw-bold text-success mb-0" id="fc_display">
                                    {{ isset($screening['fc']) ? number_format($screening['fc'], 2) : '-' }} %
                                </h4>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center gap-2 mt-3 pt-2 border-top">
                        <a href="{{ route('qc-inhouse.show', $batch->sampel_inhouse_id) }}" class="btn btn-kembali btn-sm py-1.5 px-3 shadow-sm fw-semibold">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Detail
                        </a>

                        @if(!in_array($batch->status, ['pemilihan_sampel']))
                            <a href="{{ route('qc-inhouse.preparasi', $batch->sampel_inhouse_id) }}" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold">
                                Lihat Tahap 2: Preparasi <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4 order-1 order-lg-2">
            <div class="card sop-card shadow-sm mb-2">
                <div class="sop-header">
                    <h6 class="sop-title"><i class="fas fa-info-circle me-1"></i> Panduan Pemilihan Sampel</h6>
                </div>
                <div class="sop-content">
                    <p class="mb-2"><strong>Screening Awal:</strong> Memastikan kandidat batubara memiliki karakteristik analitis yang memenuhi rentang baku mutu ISO/ASTM.</p>
                    <p class="mb-0"><strong>Keseimbangan:</strong> Nilai Total Moisture (TM) harus selalu lebih besar dari Moisture in Analysis (MAD) karena mengukur kadar air total termasuk air permukaan.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection