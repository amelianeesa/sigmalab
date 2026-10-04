@extends('layouts.app')
@section('title', 'Penetapan Target - QC In-House')

@section('content')
<style>
    .dashboard-container {
        padding: 0 20px !important;
        margin-top: -8px !important;
        font-size: 0.78rem;
        color: #000000;
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

    .dashboard-container .card {
        border-radius: 0.5rem;
    }

    .card-body {
        padding: 12px !important;
    }

    .page-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 2px;
        color: #000000;
    }

    .page-subtitle {
        font-size: 0.72rem;
        margin-bottom: 10px;
        line-height: 1.4;
        color: #000000;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 1px;
        color: #000000;
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

    .param-tabs {
        display: flex;
        flex-wrap: nowrap;
        gap: 6px;
        margin-bottom: 10px;
        padding-bottom: 4px;
        overflow-x: auto;
        border-bottom: 0;
        scrollbar-width: thin;
    }

    .param-tabs .nav-link {
        padding: 0.3rem 0.85rem;
        font-size: 0.76rem;
        font-weight: 700;
        white-space: nowrap;
        color: #000000;
        background-color: #ffffff;
        border: 1px solid #cfd6df;
        border-radius: 4px;
    }

    .param-tabs .nav-link:hover {
        background-color: rgba(27, 49, 82, 0.08);
        color: #1b3152;
    }

    .param-tabs .nav-link.active {
        background-color: #1b3152;
        border-color: #1b3152;
        color: #ffffff;
    }

    .stat-tile {
        height: 100%;
        padding: 10px 12px;
        text-align: center;
        background-color: #f8fafc;
        border: 1px solid #dee2e6;
        border-radius: 6px;
    }

    .stat-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #000000;
    }

    .stat-value {
        margin: 2px 0;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.2;
        color: #1b3152;
    }

    .stat-note {
        font-size: 0.66rem;
        line-height: 1.3;
        color: #000000;
    }

    .limit-tile {
        height: 100%;
        padding: 10px 12px;
        border-radius: 6px;
        border: 1px solid transparent;
        border-left-width: 4px;
    }

    .limit-tile.limit-danger {
        background-color: #fdecea;
        border-color: #f5c2c7;
        border-left-color: #dc3545;
    }

    .limit-tile.limit-warning {
        background-color: #fff8e6;
        border-color: #ffe69c;
        border-left-color: #f0ad4e;
    }

    .limit-title {
        margin-bottom: 6px;
        padding-bottom: 4px;
        font-size: 0.74rem;
        font-weight: 700;
        color: #000000;
        border-bottom: 1px solid rgba(0, 0, 0, 0.12);
    }

    .limit-row {
        display: flex;
        justify-content: space-between;
        gap: 8px;
    }

    .limit-row .limit-name {
        font-size: 0.66rem;
        color: #000000;
    }

    .limit-row .limit-num {
        font-size: 1rem;
        font-weight: 700;
        color: #000000;
        line-height: 1.3;
    }

    .base-note {
        margin: 10px 0 0 0;
        font-size: 0.68rem;
        font-style: italic;
        text-align: center;
        color: #000000;
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

    .btn-kembali {
        background-color: #6c757d !important;
        border: 1px solid #6c757d !important;
        color: #ffffff !important;
    }

    .btn-kembali:hover,
    .btn-kembali:focus,
    .btn-kembali:active {
        background-color: #ffffff !important;
        border-color: #6c757d !important;
        color: #6c757d !important;
    }

    .submit-wrap {
        align-items: stretch !important;
    }

    .submit-wrap .btn {
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        line-height: 1.3;
        white-space: nowrap;
    }

    .sop-card {
        background-color: #1b3152;
        color: #ffffff;
        border: 0 !important;
    }

    .sop-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 12px 4px 12px;
        cursor: default;
    }

    .sop-card .sop-title {
        font-size: 0.88rem;
        font-weight: 700;
        margin-bottom: 0;
        color: #ffffff;
    }

    .sop-chevron {
        display: none;
        color: #ffffff;
        font-size: 0.8rem;
        transition: transform 0.2s ease;
    }

    .sop-header[aria-expanded="true"] .sop-chevron {
        transform: rotate(180deg);
    }

    .sop-content {
        padding: 6px 12px 12px 12px;
    }

    .sop-card p {
        font-size: 0.78rem;
        line-height: 1.5;
        color: #e8eef7;
    }

    .sop-card strong {
        color: #ffffff;
    }

    .sop-card .sop-note {
        background-color: #ffffff;
        color: #1b3152;
        border-radius: 6px;
        padding: 8px 10px;
        font-size: 0.76rem;
        font-weight: 600;
        line-height: 1.45;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
    }

    .sop-card .sop-note strong {
        color: #1b3152;
    }

    @media (min-width: 992px) {
        .sop-collapse.collapse:not(.show) {
            display: block;
        }
    }

    @media (max-width: 991.98px) {
        .sop-header {
            cursor: pointer;
            padding: 10px 12px;
        }

        .sop-chevron {
            display: inline-block;
        }

        .sop-content {
            padding-top: 2px;
        }
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

        .card-body {
            padding: 10px !important;
        }

        .stat-value {
            font-size: 1.3rem;
        }

        .sop-card p {
            font-size: 0.82rem;
        }

        .submit-wrap {
            flex-direction: column-reverse;
        }

        .submit-wrap .btn {
            width: 100%;
            min-height: 40px;
            font-size: 0.85rem;
        }
    }
</style>

<div class="container-fluid dashboard-container">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $batch->sampel_inhouse_id) }}">{{ $batch->nama_sampel }}</a></li>
        <li class="breadcrumb-item active">Tahap 4: Penetapan Target</li>
    </x-qc-breadcrumb>

    <div class="row g-2">
        <div class="col-xl-9 col-lg-8 order-2 order-lg-1">
            <div class="card shadow-sm border-0 mb-2">
                <div class="card-body">
                    <h5 class="page-title"><i class="fas fa-bullseye icon-corporate me-2"></i>Tahap 4: Penetapan Nilai Target</h5>
                    <p class="page-subtitle">Sesuai prosedur operasional standar, Nilai Target (Mean) dan Simpangan Baku (Standar Deviasi) diwarisi otomatis secara presisi dari kalkulasi <strong>Global Mean</strong> dan <strong>Global Standard Deviation</strong> dari {{ $batch->jumlah_botol * 2 }} titik uji Homogenitas (Tahap 3) yang telah lolos validasi statistik.</p>

                    @if($errors->any())
                        <div class="alert alert-danger py-2 px-3 mb-2 rounded-3" style="font-size: 0.75rem;">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('qc-inhouse.penetapan-target.store', $batch->sampel_inhouse_id) }}" method="POST">
                        @csrf

                        <ul class="nav nav-pills param-tabs" id="paramTabs" role="tablist">
                            @foreach($batch->parameters as $idx => $param)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $idx === 0 ? 'active' : '' }}" id="tab-{{ $param->id }}" data-bs-toggle="pill" data-bs-target="#pane-{{ $param->id }}" type="button" role="tab">
                                        {{ $param->parameterUji->nama_parameter }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content" id="paramTabsContent">
                            @foreach($batch->parameters as $idx => $param)
                                @php
                                    $pid = $param->id;
                                    $rawMean = (float) ($param->mean_target ?? $param->mean_global);
                                    $rawSd = (float) ($param->sd_target ?? $param->sd_global);

                                    $ucl = round($rawMean + (3 * $rawSd), 2);
                                    $lcl = round($rawMean - (3 * $rawSd), 2);
                                    $uwl = round($rawMean + (2 * $rawSd), 2);
                                    $lwl = round($rawMean - (2 * $rawSd), 2);

                                    $mean = round($rawMean, 2);
                                    $sd = round($rawSd, 2);
                                @endphp
                                <div class="tab-pane fade {{ $idx === 0 ? 'show active' : '' }}" id="pane-{{ $pid }}" role="tabpanel">
                                    <h6 class="section-title border-bottom pb-2 mb-2"><span class="step-badge">{{ $idx + 1 }}</span>Statistika Nilai Target : {{ $param->parameterUji->nama_parameter }}</h6>

                                    <div class="row g-2">
                                        <div class="col-6 col-xl-3">
                                            <div class="stat-tile">
                                                <div class="stat-label">Target Mean</div>
                                                <div class="stat-value">{{ number_format($mean, 2) }}</div>
                                                <div class="stat-note">Ditarik dari Grand Mean Homogenitas</div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-xl-3">
                                            <div class="stat-tile">
                                                <div class="stat-label">Target Standard Deviation</div>
                                                <div class="stat-value">{{ number_format($sd, 2) }}</div>
                                                <div class="stat-note">Ditarik dari Global SD Homogenitas</div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-3">
                                            <div class="limit-tile limit-danger">
                                                <div class="limit-title">Control Limit (+/- 3 SD)</div>
                                                <div class="limit-row">
                                                    <div>
                                                        <div class="limit-name">UCL (+3 SD)</div>
                                                        <div class="limit-num">{{ number_format($ucl, 2) }}</div>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="limit-name">LCL (-3 SD)</div>
                                                        <div class="limit-num">{{ number_format($lcl, 2) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-xl-3">
                                            <div class="limit-tile limit-warning">
                                                <div class="limit-title">Warning Limit (+/- 2 SD)</div>
                                                <div class="limit-row">
                                                    <div>
                                                        <div class="limit-name">UWL (+2 SD)</div>
                                                        <div class="limit-num">{{ number_format($uwl, 2) }}</div>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="limit-name">LWL (-2 SD)</div>
                                                        <div class="limit-num">{{ number_format($lwl, 2) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="base-note">Angka-angka ini akan digunakan sebagai patokan dasar (baseline) untuk Uji Stabilitas bulanan dan Quality Control harian.</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="submit-wrap d-flex justify-content-end align-items-center gap-2 mt-3">
                            <a href="{{ route('qc-inhouse.show', $batch->sampel_inhouse_id) }}" class="btn btn-kembali btn-sm py-1.5 px-3 shadow-sm fw-semibold">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            @if($batch->status === 'penetapan_target')
                            <button type="submit" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold">
                                <i class="fas fa-check-double me-1"></i> Sahkan Semua Nilai Target & Lanjut
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4 order-1 order-lg-2">
            <div class="card sop-card shadow-sm mb-2">
                <div class="sop-header" role="button" data-bs-toggle="collapse" data-bs-target="#sopBody" aria-expanded="false" aria-controls="sopBody">
                    <h6 class="sop-title"><i class="fas fa-info-circle me-1"></i> Pengesahan Otomatis</h6>
                    <i class="fas fa-chevron-down sop-chevron"></i>
                </div>
                <div id="sopBody" class="collapse sop-collapse">
                    <div class="sop-content">
                        <p class="mb-2"><strong>Tidak perlu input manual.</strong> Silakan tinjau rentang kendali (Control Limits) pada setiap parameter lalu klik <strong>"Sahkan Semua Nilai Target & Lanjut"</strong>.</p>
                        <p class="sop-note mb-0"><strong>Note:</strong> Nilai yang disahkan menjadi baseline Uji Stabilitas dan QC harian.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection