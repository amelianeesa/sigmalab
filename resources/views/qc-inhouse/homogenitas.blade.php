@extends('layouts.app')
@section('title', 'Homogenitas - QC In-House')

@section('content')

<style>
    .hom-page {
        font-size: 0.8rem;
        color: #000;
    }

    .hom-page .hom-container {
        padding: 0 20px !important;
        margin-top: -8px !important;
        padding-bottom: 1.5rem !important;
    }

    .hom-page nav[aria-label="breadcrumb"],
    .hom-page .hom-container > nav {
        margin: 0 !important;
        padding: 0 !important;
    }

    .hom-page .breadcrumb {
        margin: 0 0 6px 0 !important;
        padding: 0 !important;
        font-size: 0.75rem !important;
        line-height: 1.4;
        flex-wrap: wrap;
        align-items: center;
        background: transparent !important;
    }

    .hom-page .breadcrumb .breadcrumb-item,
    .hom-page .breadcrumb .breadcrumb-item a {
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        color: #0d6efd !important;
        text-decoration: none;
    }

    .hom-page .breadcrumb .breadcrumb-item a:hover {
        color: #0a58ca !important;
        text-decoration: underline;
    }

    .hom-page .breadcrumb .breadcrumb-item.active {
        color: #000000 !important;
        font-weight: 700 !important;
    }

    .hom-page .breadcrumb .breadcrumb-item + .breadcrumb-item {
        padding-left: 0.4rem;
    }

    .hom-page .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        color: #6c757d !important;
        padding-right: 0.4rem;
        font-weight: 400;
    }

    .hom-page .breadcrumb .breadcrumb-item .dropdown-menu {
        min-width: 190px;
        padding: 4px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }

    .hom-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item {
        color: #000000 !important;
        font-size: 0.78rem !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        background-color: transparent;
        padding: 7px 12px;
        border-radius: 5px;
    }

    .hom-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:hover,
    .hom-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:focus,
    .hom-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:active,
    .hom-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item.active {
        background-color: rgba(26, 127, 196, 0.15) !important;
        color: #000000 !important;
        text-decoration: none !important;
    }

    .hom-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item.active {
        font-weight: 700 !important;
    }

    .hom-page .text-primary {
        color: var(--sl-primary) !important;
    }

    .hom-page .text-muted {
        color: #495057 !important;
    }

    .hom-page .hom-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #000;
    }

    .hom-page .hom-sub {
        font-size: 0.75rem;
        line-height: 1.5;
        color: #000 !important;
    }

    .hom-page .hom-head {
        margin-bottom: 0.75rem !important;
    }

    .hom-page .card {
        border-radius: 0.5rem;
        border-color: #dee2e6;
    }

    .hom-page .card-header {
        padding: 0.6rem 0.75rem 0 0.75rem !important;
        background-color: #ffffff;
    }

    .hom-page .card-footer {
        padding: 0.9rem 1rem !important;
        background-color: #f8fafc !important;
        border-top: 1px solid #dee2e6;
    }

    .hom-page .card-footer h5 {
        font-size: 0.95rem;
        color: var(--sl-primary) !important;
    }

    .hom-page .card-footer .small {
        font-size: 0.75rem !important;
    }

    .hom-page .nav-tabs {
        flex-wrap: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
        border-bottom: 1px solid #dee2e6;
        -webkit-overflow-scrolling: touch;
    }

    .hom-page .nav-tabs .nav-link {
        font-size: 0.78rem;
        padding: 0.45rem 0.9rem;
        color: #495057;
        white-space: nowrap;
        border-radius: 0.4rem 0.4rem 0 0;
    }

    .hom-page .nav-tabs .nav-link:hover {
        color: var(--sl-primary);
    }

    .hom-page .nav-tabs .nav-link.active {
        color: #ffffff;
        background-color: var(--sl-primary);
        border-color: var(--sl-primary);
    }

    .hom-page .alert-info {
        margin: 10px 12px !important;
        padding: 8px 12px;
        font-size: 0.75rem;
        line-height: 1.5;
        color: #000;
        background-color: rgba(26, 127, 196, 0.08);
        border: 0;
        border-left: 4px solid var(--sl-primary) !important;
        border-radius: 6px !important;
    }

    .hom-page .alert-info i {
        color: var(--sl-primary);
    }

    .hom-page .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.78rem;
        font-weight: 600;
        line-height: 1.3;
        padding: 0.38rem 0.85rem;
        border-radius: 0.375rem;
        transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
    }

    .hom-page .btn-sm {
        font-size: 0.75rem;
        padding: 0.3rem 0.7rem;
    }

    .hom-page .btn-lg {
        font-size: 0.88rem;
        padding: 0.55rem 1.5rem;
    }

    .hom-page .btn-primary {
        background-color: var(--sl-primary);
        border-color: var(--sl-primary);
        color: #ffffff;
    }

    .hom-page .btn-primary:hover,
    .hom-page .btn-primary:focus,
    .hom-page .btn-primary:active {
        background-color: #14253e;
        border-color: #14253e;
        color: #ffffff;
    }

    .hom-page .btn-outline-primary,
    .hom-page .btn-outline-dark {
        color: var(--sl-primary);
        border-color: var(--sl-primary);
        background-color: transparent;
    }

    .hom-page .btn-outline-primary:hover,
    .hom-page .btn-outline-primary:focus,
    .hom-page .btn-outline-primary:active,
    .hom-page .btn-outline-dark:hover,
    .hom-page .btn-outline-dark:focus,
    .hom-page .btn-outline-dark:active {
        background-color: var(--sl-primary);
        border-color: var(--sl-primary);
        color: #ffffff;
    }

    .hom-page .btn-outline-success {
        color: #198754;
        border-color: #198754;
        background-color: transparent;
    }

    .hom-page .btn-outline-success:hover,
    .hom-page .btn-outline-success:focus,
    .hom-page .btn-outline-success:active {
        background-color: #198754;
        border-color: #198754;
        color: #ffffff;
    }

    .hom-page .btn-outline-danger {
        color: #dc3545;
        border-color: #dc3545;
        background-color: transparent;
    }

    .hom-page .btn-outline-danger:hover,
    .hom-page .btn-outline-danger:focus,
    .hom-page .btn-outline-danger:active {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #ffffff;
    }

    .hom-page .btn-info {
        background-color: #3a5a8c;
        border-color: #3a5a8c;
        color: #ffffff !important;
    }

    .hom-page .btn-info:hover,
    .hom-page .btn-info:focus,
    .hom-page .btn-info:active {
        background-color: #2d4770;
        border-color: #2d4770;
        color: #ffffff !important;
    }

    .hom-page .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #ffffff;
    }

    .hom-page .btn-secondary:hover,
    .hom-page .btn-secondary:focus,
    .hom-page .btn-secondary:active {
        background-color: #565e64;
        border-color: #565e64;
        color: #ffffff;
    }

    .hom-page .form-label {
        font-size: 0.78rem;
    }

    .hom-page .form-control,
    .hom-page .form-select {
        font-size: 0.8rem;
    }

    .hom-page .form-check-input:checked {
        background-color: var(--sl-primary);
        border-color: var(--sl-primary);
    }

    .hom-page .form-check-input:focus,
    .hom-page .form-control:focus,
    .hom-page .form-select:focus {
        border-color: var(--sl-primary);
        box-shadow: 0 0 0 0.15rem rgba(26, 127, 196, 0.15);
    }

    .hom-page .tab-pane .table-responsive {
        padding: 0.6rem !important;
    }

    .hom-page .tab-pane > .px-3 {
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
    }

    .hom-page #parameterTabsContent [id^="summary_"] {
        font-size: 0.75rem;
        background-color: #f8fafc !important;
    }

    .hom-page .param-table {
        width: 100%;
        min-width: 560px;
        margin-bottom: 0;
        table-layout: fixed;
        font-size: 0.75rem;
        border-color: #cfd6df;
    }

    .hom-page .param-table[data-code="IM"] { min-width: 860px; }
    .hom-page .param-table[data-code="ASH"] { min-width: 1040px; }
    .hom-page .param-table[data-code="VM"] { min-width: 1240px; }
    .hom-page .param-table[data-code="TS"] { min-width: 620px; }
    .hom-page .param-table[data-code="CV"] { min-width: 1380px; }

    .hom-page .param-table thead {
        --bs-table-bg: var(--sl-primary);
        --bs-table-color: #ffffff;
        --bs-table-border-color: rgba(255, 255, 255, 0.25);
    }

    .hom-page .param-table thead th {
        background-color: var(--sl-primary) !important;
        color: #ffffff;
        font-size: 0.7rem;
        font-weight: 600;
        line-height: 1.25;
        padding: 0.4rem 0.3rem;
        vertical-align: middle;
        white-space: normal !important;
        border-color: rgba(255, 255, 255, 0.25);
    }

    .hom-page .param-table thead th.bg-warning {
        background-color: #3a5a8c !important;
    }

    .hom-page .param-table td {
        padding: 0.15rem 0.2rem;
        font-size: 0.75rem;
        vertical-align: middle;
        border-color: #dfe4ea;
    }

    .hom-page .param-table td.bg-warning {
        background-color: rgba(26, 127, 196, 0.07) !important;
    }

    .hom-page .param-table td.bg-light {
        font-size: 0.78rem;
    }

    .hom-page .param-table td .small {
        font-size: 0.68rem;
    }

    .hom-page .param-table input.form-control-sm {
        width: 100%;
        min-width: 0;
        font-size: 0.75rem;
        padding: 0.2rem 0.25rem;
        text-align: center;
        border-radius: 0.2rem;
    }

    .hom-page .param-table .out-diff,
    .hom-page .param-table .out-tol,
    .hom-page .param-table .out-avg-adb,
    .hom-page .param-table .out-avg-db,
    .hom-page .param-table .out-db-1,
    .hom-page .param-table .out-db-2 {
        font-size: 0.75rem;
    }

    .hom-page #anovaPreviewBoxes > .col-2,
    .hom-page #anovaPreviewBoxes > .col-4 {
        flex: 0 0 auto;
        width: 20%;
    }

    .hom-page #anovaPreviewBoxes .small {
        font-size: 0.7rem !important;
    }

    .hom-page #anovaPreviewBoxes h4 {
        font-size: 1rem;
        margin-bottom: 0;
    }

    .hom-page .modal-header {
        padding: 0.7rem 1rem;
    }

    .hom-page .modal-title {
        font-size: 0.95rem;
    }

    .hom-page .modal-header.bg-primary {
        background-color: var(--sl-primary) !important;
    }

    .hom-page .modal-footer {
        padding: 0.6rem 1rem;
    }

    .hom-page .modal-resource .nav-pills .nav-link {
        font-size: 0.8rem;
        padding: 0.65rem 0.5rem !important;
        color: #000 !important;
        border-radius: 0;
        background-color: transparent;
    }

    .hom-page .modal-resource .nav-pills .nav-link.active {
        color: var(--sl-primary) !important;
        background-color: rgba(26, 127, 196, 0.1);
        box-shadow: inset 0 -3px 0 var(--sl-primary);
    }

    .hom-page .modal-resource .modal-body > .tab-content {
        padding: 1rem !important;
    }

    .hom-page .modal-resource .table {
        font-size: 0.78rem;
    }

    .hom-page .modal-resource .table thead th {
        font-size: 0.72rem;
    }

    .hom-page .modal-resource .form-check {
        font-size: 0.78rem;
    }

    .hom-page .modal-resource .text-warning,
    .hom-page .modal-resource .text-success {
        color: var(--sl-primary) !important;
    }

    .hom-page #modalDetailAnova .modal-body {
        padding: 1rem !important;
    }

    .hom-page #modalDetailAnova table {
        font-size: 0.78rem;
    }

    .hom-page #modalDetailAnova h4 {
        font-size: 1.05rem;
    }

    .hom-page #modalDetailAnova .fs-5 {
        font-size: 0.95rem !important;
    }

    .hom-page #modalDetailAnova .fs-6 {
        font-size: 0.82rem !important;
    }

    .hom-page #modalDetailAnova .p-4 {
        padding: 1rem !important;
    }

    .hom-page #modalExport .modal-body {
        font-size: 0.8rem;
    }

    @media (max-width: 991.98px) {
        .hom-page .param-table thead th:first-child,
        .hom-page .param-table tbody tr.row-simplo > td:first-child {
            position: sticky;
            left: 0;
            z-index: 2;
        }

        .hom-page .param-table tbody tr.row-simplo > td:first-child {
            background-color: #f8f9fa !important;
        }
    }

    @media (max-width: 767.98px) {
        .hom-page .hom-container {
            padding: 0 10px !important;
            padding-bottom: 1.25rem !important;
        }

        .hom-page .breadcrumb,
        .hom-page .breadcrumb .breadcrumb-item,
        .hom-page .breadcrumb .breadcrumb-item a {
            font-size: 0.72rem !important;
        }

        .hom-page .breadcrumb .breadcrumb-item + .breadcrumb-item {
            padding-left: 0.3rem;
        }

        .hom-page .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            padding-right: 0.3rem;
        }

        .hom-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item {
            font-size: 0.85rem !important;
            padding: 10px 14px;
        }

        .hom-page .hom-title {
            font-size: 1rem;
        }

        .hom-page .hom-head .btn {
            width: 100%;
            min-height: 40px;
        }

        .hom-page .card-header {
            padding: 0.5rem 0.5rem 0 0.5rem !important;
        }

        .hom-page .alert-info {
            margin: 8px !important;
        }

        .hom-page .tab-pane .table-responsive {
            padding: 0.4rem !important;
        }

        .hom-page .tab-pane > .d-flex {
            flex-direction: column;
            align-items: stretch !important;
        }

        .hom-page .tab-pane > .d-flex > div,
        .hom-page .tab-pane > .d-flex > div .btn {
            width: 100%;
        }

        .hom-page .tab-pane > .d-flex > div.d-flex .btn {
            flex: 1 1 0;
        }

        .hom-page .card-footer {
            padding: 0.75rem !important;
        }

        .hom-page .card-footer > .d-flex .btn {
            width: 100%;
        }

        .hom-page #anovaPreviewBoxes > .col-2,
        .hom-page #anovaPreviewBoxes > .col-4 {
            width: 50%;
            margin-bottom: 0.5rem;
        }

        .hom-page #anovaPreviewBoxes > .col-4 {
            width: 100%;
        }

        .hom-page #btnSubmit {
            width: 100%;
            min-height: 44px;
        }

        .hom-page #modalDetailAnova .mb-3.w-25 {
            width: 100% !important;
        }

        .hom-page .modal-resource .modal-body > .tab-content {
            padding: 0.75rem !important;
        }

        .hom-page .modal-resource .nav-pills .nav-link {
            font-size: 0.72rem;
        }
    }

    @media print {
        .potong-halaman {
            page-break-before: always !important;
            break-before: page !important;
        }
    }

    .html2pdf__page-break {
        page-break-before: always !important;
    }

    input:invalid {
        box-shadow: none;
    }
</style>

<div class="hom-page">
<div class="container-fluid hom-container pb-4">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $batch->sampel_inhouse_id) }}">{{ $batch->nama_sampel }}</a></li>
        <li class="breadcrumb-item active">Tahap 3: Uji Homogenitas</li>
    </x-qc-breadcrumb>

        <div class="row mt-2">
            <div class="col-12">

                <div class="row align-items-center hom-head">
                    <div class="col-md-8 mb-2 mb-md-0">
                        <h3 class="hom-title mb-1">
                            <i class="fas fa-edit text-primary me-2"></i>Input Uji Homogenitas (Tahap 3)
                        </h3>
                        <p class="hom-sub text-muted mb-0">Metode Acuan: <strong>{{ strtoupper($batch->metode_acuan) }}</strong> | Jenis Batubara: <strong>{{ $batch->jenis_batubara }}</strong></p>
                    </div>

                    <div class="col-md-4 text-md-end">
                        <div class="d-grid d-md-block">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalExport">
                                <i class="fas fa-print me-2"></i>Cetak / Export
                            </button>
                        </div>
                    </div>

                </div>

            <form action="{{ route('qc-inhouse.homogenitas.store', $batch->sampel_inhouse_id) }}" method="POST" id="formHomogenitas">
                @csrf
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-white pt-3 border-bottom-0">
                        <ul class="nav nav-tabs card-header-tabs" id="parameterTabs" role="tablist">
                            @foreach($batch->parameters as $index => $param)
                                @php
                                    $rawCode = trim(strtoupper($param->parameterUji->nama_parameter));
                                    $code = in_array($rawCode, ['TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']) ? 'TS' : $rawCode;
                                @endphp
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold {{ $index === 0 ? 'active' : '' }}"
                                            id="tab-{{ $param->id }}"
                                            data-bs-toggle="tab"
                                            data-bs-target="#pane-{{ $param->id }}"
                                            type="button" role="tab"
                                            data-code="{{ $code }}">
                                        {{ $rawCode }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-body p-0">
                        @if(!$batchHasIm && $needsIm)
                            <div class="card border-warning mx-3 my-3 shadow-sm" style="background-color: #fffdf5;">
                                <div class="card-header bg-warning bg-opacity-25 py-2 px-3 d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-dark">
                                        <i class="fas fa-sliders-h me-1 text-warning"></i> Referensi Nilai Inherent Moisture (IM)
                                    </span>
                                    <span id="imRefBadge" class="badge {{ !empty($savedImReference['data']) ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ !empty($savedImReference['data']) ? 'Referensi IM Aktif' : 'Belum Diterapkan' }}
                                    </span>
                                </div>
                                <div class="card-body py-2 px-3">
                                    <p class="small text-muted mb-2">
                                        Batch ini tidak menguji parameter IM. Nilai acuan IM diperlukan untuk mengisi otomatis kolom <strong>IM</strong> &amp; menghitung <strong>VM%</strong> pada Volatile Matter, serta konversi basis kering (<strong>%db</strong>) pada Ash, VM, TS, dan CV.
                                    </p>
                                    <div class="row g-2 align-items-center">
                                        <div class="col-md-auto">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="radio_im_mode" id="radio_im_batch" value="batch" 
                                                    {{ ($savedImReference['mode'] ?? 'batch') === 'batch' ? 'checked' : '' }}>
                                                <label class="form-check-label small fw-bold" for="radio_im_batch">Pilih Batch QC Lain</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="radio_im_mode" id="radio_im_manual" value="manual"
                                                    {{ ($savedImReference['mode'] ?? '') === 'manual' ? 'checked' : '' }}>
                                                <label class="form-check-label small fw-bold" for="radio_im_manual">Nilai Tetap (Manual)</label>
                                            </div>
                                        </div>

                                        <div class="col-md-5 {{ ($savedImReference['mode'] ?? 'batch') === 'manual' ? 'd-none' : '' }}" id="col_select_batch">
                                            <div class="input-group input-group-sm">
                                                <select class="form-select form-select-sm" id="select_im_batch">
                                                    <option value="">-- Pilih Batch Acuan IM --</option>
                                                    @foreach($availableImBatches as $bIm)
                                                        <option value="{{ $bIm['id'] }}" 
                                                                {{ ($savedImReference['batch_id'] ?? null) == $bIm['id'] ? 'selected' : '' }}>
                                                            {{ $bIm['kode_batch'] }} - {{ $bIm['nama_sampel'] }} ({{ $bIm['tanggal'] }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="button" class="btn btn-outline-secondary" id="btn_preview_im" title="Lihat detail nilai botol batch ini">
                                                    <i class="fas fa-eye me-1"></i> Preview
                                                </button>
                                            </div>
                                            <div id="quick_preview_im_text" class="text-primary small mt-1 fw-semibold" style="font-size: 0.72rem;"></div>
                                        </div>

                                        <div class="col-md-3 {{ ($savedImReference['mode'] ?? 'batch') === 'manual' ? '' : 'd-none' }}" id="col_manual_im">
                                            <div class="input-group input-group-sm">
                                                <input type="number" step="0.01" class="form-control" id="input_manual_im_val" 
                                                    placeholder="Contoh: 14.50" value="{{ $savedImReference['manual_val'] ?? '' }}">
                                                <span class="input-group-text">% adb</span>
                                            </div>
                                        </div>

                                        <div class="col-md-auto">
                                            <button type="button" class="btn btn-primary btn-sm px-3" id="btn_apply_im">
                                                <i class="fas fa-check-circle me-1"></i> Terapkan ke Pengujian
                                            </button>
                                        </div>
                                    </div>

                                    <div id="imRefSummary" class="small mt-2 {{ !empty($savedImReference['data']) ? 'text-success fw-bold' : 'text-danger' }}">
                                        @if(!empty($savedImReference['data']))
                                            ✓ Referensi IM aktif: {{ $savedImReference['mode'] === 'batch' ? 'Menggunakan data dari Batch Terpilih' : 'Nilai tetap '.$savedImReference['manual_val'].'% adb' }}. Kolom IM &amp; %db otomatis terisi.
                                        @else
                                            ⚠️ Silakan pilih batch acuan atau masukkan nilai manual lalu klik "Terapkan ke Pengujian" agar perhitungan VM &amp; %db berjalan.
                                        @endif
                                    </div>

                                    <!-- Hidden Inputs yang ikut terkirim saat form disimpan -->
                                    <input type="hidden" name="referensi_im_mode" id="in_ref_im_mode" value="{{ $savedImReference['mode'] ?? '' }}">
                                    <input type="hidden" name="referensi_im_batch_id" id="in_ref_im_batch_id" value="{{ $savedImReference['batch_id'] ?? '' }}">
                                    <input type="hidden" name="referensi_im_manual" id="in_ref_im_manual" value="{{ $savedImReference['manual_val'] ?? '' }}">
                                    <input type="hidden" name="referensi_im_data" id="in_ref_im_data" value="{{ isset($savedImReference['data']) ? json_encode($savedImReference['data']) : '' }}">
                                </div>
                            </div>
                        @endif
                        <div class="alert alert-info m-3 rounded-0 border-start border-4 border-info">
                            <i class="fas fa-info-circle me-2"></i> Ketergantungan Data: Tab <strong>IM</strong> wajib diisi lebih dulu karena parameter lain membutuhkan nilai IM untuk konversi ke basis Dry Basis (db). Tab <strong>CV</strong> juga membutuhkan nilai dari tab <strong>TS</strong>.
                        </div>

                        @php

                            $rowCount = max(10, $batch->parameters->max(function ($p) {
                                return $p->dataHomogenitas->count();
                            }) ?? 0);
                        @endphp
                        <div class="tab-content" id="parameterTabsContent">
                            @foreach($batch->parameters as $index => $param)
                                @php
                                    $rawCode = trim(strtoupper($param->parameterUji->nama_parameter));
                                    $code = in_array($rawCode, ['TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']) ? 'TS' : $rawCode;
                                    $pid = $param->id;
                                @endphp
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="pane-{{ $pid }}" role="tabpanel">
                                    
                                    <!-- Tombol Buka Modal & Summary -->
                                    <div class="px-3 pt-3">
                                        @php
                                            $isLocked = !in_array($batch->status, ['uji_homogenitas', 'gagal_homogenitas']);
                                        @endphp

                                        <div class="d-flex flex-wrap gap-2 mb-2 d-print-none">
                                            @if(!$isLocked)
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalResource-{{ $pid }}">
                                                <i class="fas fa-users-cog me-1"></i> Pilih Personil, Alat & Bahan ({{ $code }})
                                            </button>
                                            @endif
                                            <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalRumus-{{ $pid }}">
                                                <i class="fas fa-calculator me-1"></i> Panduan Kolom & Rumus ({{ $code }})
                                            </button>
                                        </div>
                                        <div id="summary_{{ $pid }}" class="p-2 border rounded bg-light small d-none">
                                            <div class="fw-bold text-secondary mb-1">Informasi Pelaksanaan Pengujian:</div>
                                            <div class="summary-content text-dark"></div>
                                        </div>
                                    </div>

                                    @php
                                        $dhFirst = $param->dataHomogenitas->where('nomor_sampel', 1)->first();
                                        $mentahResource = $dhFirst ? $dhFirst->data_mentah : [];
                                    @endphp
                                    <div class="modal fade modal-resource" id="modalResource-{{ $pid }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title fw-bold"><i class="fas fa-box-open text-primary me-2"></i>Personil & Alat: {{ $code }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-0">
                                                    <ul class="nav nav-pills nav-justified mb-0 border-bottom" role="tablist">
                                                        <li class="nav-item" role="presentation">
                                                            <button class="nav-link active fw-bold text-dark py-3" data-bs-toggle="tab" data-bs-target="#tab-personil-{{ $pid }}" type="button" role="tab"><i class="fas fa-users text-primary me-2"></i>Personil</button>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <button class="nav-link fw-bold text-dark py-3" data-bs-toggle="tab" data-bs-target="#tab-alat-{{ $pid }}" type="button" role="tab"><i class="fas fa-tools text-primary me-2"></i>Alat</button>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <button class="nav-link fw-bold text-dark py-3" data-bs-toggle="tab" data-bs-target="#tab-bahan-{{ $pid }}" type="button" role="tab"><i class="fas fa-flask text-primary me-2"></i>Bahan / Reagen</button>
                                                        </li>
                                                    </ul>
                                                    
                                                    <fieldset {{ $isLocked ? 'disabled' : '' }}>
                                                        <div class="tab-content p-4">
                                                        
                                                            
                                                            @if(!$isLocked)
                                                            <div class="d-flex justify-content-end mb-3">
                                                                <button type="button" class="btn btn-sm btn-info text-white btn-copy-resource" data-pid="{{ $pid }}">
                                                                    <i class="fas fa-copy me-1"></i> Salin dari Parameter Sebelumnya
                                                                </button>
                                                            </div>
                                                            @endif

                                                            <!-- Tab Personil -->
                                                            <div class="tab-pane fade show active" id="tab-personil-{{ $pid }}" role="tabpanel">
                                                                <div class="table-responsive border rounded" style="max-height: 350px; overflow-y: auto;">
                                                                    <table class="table table-hover table-sm mb-0 text-nowrap">
                                                                        <thead class="table-light sticky-top">
                                                                            <tr><th width="5%" class="text-center">Pilih</th><th>Nama Personil</th><th>Peran / Tugas</th></tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($personilList as $personil)
                                                                            <tr>
                                                                                <td class="text-center align-middle">
                                                                                    <input class="form-check-input chk-personil" style="transform: scale(1.3);" type="checkbox" name="resource_{{ $pid }}[personil_ids][]" value="{{ $personil->personil_id }}" {{ in_array($personil->personil_id, $mentahResource['personil_ids'] ?? []) ? 'checked' : '' }}>
                                                                                </td>
                                                                                <td class="align-middle">{{ $personil->nama }}</td>
                                                                                <td><input type="text" class="form-control form-control-sm in-peran" name="resource_{{ $pid }}[personil_peran][{{ $personil->personil_id }}]" value="{{ $mentahResource['personil_peran'][$personil->personil_id] ?? 'Analis' }}" placeholder="Analis"></td>
                                                                            </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                            <!-- Tab Alat -->
                                                            <div class="tab-pane fade" id="tab-alat-{{ $pid }}" role="tabpanel">
                                                                <div class="row">
                                                                    @foreach($alatList as $alat)
                                                                    <div class="col-md-6 mb-3">
                                                                        <div class="form-check border p-2 rounded bg-light">
                                                                            <input class="form-check-input chk-alat ms-1" style="transform: scale(1.3);" type="checkbox" name="resource_{{ $pid }}[alat_ids][]" value="{{ $alat->alat_id }}" {{ in_array($alat->alat_id, $mentahResource['alat_ids'] ?? []) ? 'checked' : '' }}>
                                                                            <label class="form-check-label ms-2 cursor-pointer w-100">
                                                                                <strong>{{ $alat->nama_alat }}</strong> <br><small class="text-muted">({{ $alat->kode_alat }})</small>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>

                                                            <!-- Tab Bahan -->
                                                            <div class="tab-pane fade" id="tab-bahan-{{ $pid }}" role="tabpanel">
                                                                <div class="table-responsive border rounded" style="max-height: 350px; overflow-y: auto;">
                                                                    <table class="table table-hover table-sm mb-0 text-nowrap">
                                                                        <thead class="table-light sticky-top">
                                                                            <tr><th width="5%" class="text-center">Pilih</th><th>Nama Bahan</th><th>Sisa Stok</th><th width="30%">Jumlah Dipakai</th></tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($barangList as $barang)
                                                                            @php
                                                                                $saldoAkhir = ($barang->saldo_awal + $barang->penerimaan) - $barang->pengeluaran;
                                                                                $habis = $saldoAkhir <= 0;
                                                                            @endphp
                                                                            <tr class="{{ $habis ? 'table-danger' : '' }}">
                                                                                <td class="text-center align-middle">
                                                                                    <input class="form-check-input chk-bahan" style="transform: scale(1.3);" type="checkbox" name="resource_{{ $pid }}[barang_ids][]" value="{{ $barang->barang_id }}" data-nama="{{ $barang->nama_barang }}" {{ in_array($barang->barang_id, $mentahResource['barang_ids'] ?? []) ? 'checked' : '' }}>
                                                                                </td>
                                                                                <td class="align-middle">
                                                                                    <strong>{{ $barang->nama_barang }}</strong> 
                                                                                    @if($habis) <span class="badge bg-danger ms-1">Habis</span> @endif
                                                                                </td>
                                                                                <td class="align-middle {{ $habis ? 'text-danger fw-bold' : '' }}">{{ $saldoAkhir }} {{ $barang->satuan }}</td>
                                                                                <td>
                                                                                    <div class="input-group input-group-sm">
                                                                                        <input type="number" step="0.01" class="form-control in-qty" name="resource_{{ $pid }}[barang_jumlah][{{ $barang->barang_id }}]" placeholder="0" value="{{ $mentahResource['barang_jumlah'][$barang->barang_id] ?? '' }}">
                                                                                        <span class="input-group-text">{{ $barang->satuan }}</span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                               <div class="modal-footer bg-light">
                                                    @if($isLocked)
                                                        <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Tutup</button>
                                                    @else
                                                        <button type="button" class="btn btn-primary px-4 fw-bold" data-bs-dismiss="modal"><i class="fas fa-check me-1"></i> Simpan Pilihan</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @include('partials.modal-panduan-inhouse', ['pid' => $pid, 'code' => $code])

                                    <div class="table-responsive p-3">
                                        <fieldset class="border-0 p-0 m-0" {{ $isLocked ? 'disabled' : '' }}>
                                                <table class="table table-bordered table-sm align-middle text-center param-table text-nowrap" style="min-width: {{ in_array($code, ['TS']) ? '100%' : '1500px' }};" id="table-{{ $pid }}" data-pid="{{ $pid }}" data-code="{{ $code }}">
                                                <thead class="table-light">
                                                    @if($code === 'IM')
                                                        <tr>
                                                            <th width="10%">KODE SAMPEL</th>
                                                            <th>DISH NO.</th>
                                                            <th>M1</th>
                                                            <th>M2</th>
                                                            <th>M3</th>
                                                            <th>A</th>
                                                            <th>B</th>
                                                            <th class="bg-warning bg-opacity-25">M%</th>
                                                            <th colspan="2">ABSOLUTE DIFFERENCE</th>
                                                            <th>AVERAGE %</th>
                                                        </tr>
                                                    @elseif($code === 'ASH')
                                                        <tr>
                                                            <th width="10%">KODE SAMPEL</th>
                                                            <th>DISH NO.</th>
                                                            <th>M1</th>
                                                            <th>M2</th>
                                                            <th>M2-M1</th>
                                                            <th>M3</th>
                                                            <th>M3-M1</th>
                                                            <th class="bg-warning bg-opacity-25">ASH%</th>
                                                            <th colspan="2">ABSOLUTE DIFFERENCE</th>
                                                            <th style="min-width: 100px;">AVERAGE %adb</th>
                                                            <th style="min-width: 90px;">%db</th>
                                                            <th style="min-width: 90px;">db</th>
                                                        </tr>
                                                    @elseif($code === 'VM')
                                                        <tr>
                                                            <th width="10%">KODE SAMPEL</th>
                                                            <th>DISH NO.</th>
                                                            <th>M1</th>
                                                            <th>M2</th>
                                                            <th>M2-M1</th>
                                                            <th>M3</th>
                                                            <th>M2-M3</th>
                                                            <th>LOSS%</th>
                                                            <th>IM</th>
                                                            <th class="bg-warning bg-opacity-25">VM%</th>
                                                            <th colspan="2">ABSOLUTE DIFFERENCE</th>
                                                            <th style="min-width: 100px;">AVERAGE %adb</th>
                                                            <th style="min-width: 100px;">AVERAGE %db</th>
                                                            <th style="min-width: 90px;">%db</th>
                                                        </tr>
                                                    @elseif($code === 'TS')
                                                        <tr>
                                                            <th width="10%">KODE SAMPEL</th>
                                                            <th>DISH NO.</th>
                                                            <th>Massa sample</th>
                                                            <th class="bg-warning bg-opacity-25">TS (Adb)</th>
                                                            <th>Average % (adb)</th>
                                                            <th class="bg-warning bg-opacity-25">Average % (db)</th>
                                                        </tr>
                                                    @elseif($code === 'CV')
                                                        <tr>
                                                            <th width="8%">KODE SAMPEL</th>
                                                            <th width="8%">VESSEL ID</th>
                                                            <th>CALL ID</th>
                                                            <th>Weight of Crucible</th>
                                                            <th>Sample Mass</th>
                                                            <th>Primary Result (cal/g)</th>
                                                            <th style="min-width: 100px;">Ee</th>
                                                            <th style="min-width: 100px;">t</th>
                                                            <th>Volume of Titrant (ml)</th>
                                                            <th>Length of Fuse (cm)</th>
                                                            <th>Total TS</th>
                                                            <th class="bg-warning bg-opacity-25">Final Result (cal/g) adb</th>
                                                            <th>Average Result (cal/g), adb</th>
                                                            <th>Average Result (cal/g), db</th>
                                                            <th>%db</th>
                                                        </tr>
                                                    @elseif($code === 'AFT')
                                                        <tr>
                                                            <th width="15%">KODE SAMPEL</th>
                                                            <th width="15%">ATMOSFER</th>
                                                            <th class="bg-warning bg-opacity-25">IDT (°C)</th>
                                                            <th class="bg-warning bg-opacity-25">ST (°C)</th>
                                                            <th class="bg-warning bg-opacity-25">HT (°C)</th>
                                                            <th class="bg-warning bg-opacity-25">FT (°C)</th>
                                                        </tr>
                                                    @elseif($code === 'CHN')
                                                        <tr>
                                                            <th width="15%">KODE SAMPEL</th>
                                                            <th width="15%">WEIGHT (mg)</th>
                                                            <th class="bg-warning bg-opacity-25">N % db</th>
                                                            <th class="bg-warning bg-opacity-25">C % db</th>
                                                            <th class="bg-warning bg-opacity-25">H % db</th>
                                                        </tr>
                                                    @else
                                                        <tr>
                                                            <th>KODE SAMPEL</th>
                                                            <th>DISH NO.</th>
                                                            <th class="bg-warning bg-opacity-25">Hasil Uji (adb)</th>
                                                            <th>ABSOLUTE DIFFERENCE</th>
                                                            <th>AVERAGE % (adb)</th>
                                                        </tr>
                                                    @endif
                                                </thead>
                                                <tbody>
                                                    @for($i = 1; $i <= $rowCount; $i++)
                                                        @php 
                                                            $botolNomor = $tabelAcak[$i]->nomor_botol ?? $i; 
                                                            $dh = $param->dataHomogenitas->where('nomor_sampel', $i)->first();
                                                            $mentah = $dh ? $dh->data_mentah : [];
                                                        @endphp
                                                        
                                                        <tr class="row-simplo">
                                                            <td rowspan="2" class="fw-bold align-middle bg-light border-end">
                                                                {{ $i }}
                                                                <div class="small text-muted fw-normal">Botol {{ $botolNomor }}</div>
                                                                <input type="hidden" name="data_{{ $pid }}[{{ $i-1 }}][nomor_botol_fisik]" value="{{ $botolNomor }}">
                                                                <input type="hidden" class="in-db-1" name="data_{{ $pid }}[{{ $i-1 }}][nilai_db_1]" value="{{ $mentah['nilai_db_1'] ?? '' }}">
                                                                <input type="hidden" class="in-db-2" name="data_{{ $pid }}[{{ $i-1 }}][nilai_db_2]" value="{{ $mentah['nilai_db_2'] ?? '' }}">
                                                            </td>
                                                            @if(!in_array($code, ['AFT', 'CHN']))
                                                                <td><input type="text" class="form-control form-control-sm text-center fw-bold bg-light" name="data_{{ $pid }}[{{ $i-1 }}][mentah][dish_1]" value="{{ $mentah['dish_1'] ?? '' }}"></td>
                                                            @endif
                                                            
                                                            @if($code === 'IM')
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m1_1]" value="{{ $mentah['m1_1'] ?? '' }}"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-m2-1 bg-light border-0" readonly tabindex="-1"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m3_1]" value="{{ $mentah['m3_1'] ?? '' }}"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-a-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][a_1]" value="{{ $mentah['a_1'] ?? '' }}"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-b-1 bg-light border-0" readonly tabindex="-1"></td>
                                                                <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d1]" readonly tabindex="-1"></td>
                                                                <td rowspan="2" class="align-middle out-diff">-</td>
                                                                <td rowspan="2" class="align-middle out-tol fw-bold">-</td>
                                                                <td rowspan="2" class="align-middle out-avg-adb">-</td>
                                                            @elseif($code === 'ASH')
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m1_1]" value="{{ $mentah['m1_1'] ?? '' }}"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-m2-1 bg-light border-0" readonly tabindex="-1"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m2m1_1]" value="{{ $mentah['m2m1_1'] ?? '' }}"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m3_1]" value="{{ $mentah['m3_1'] ?? '' }}"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-m3m1-1 bg-light border-0" readonly tabindex="-1"></td>
                                                                <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d1]" readonly tabindex="-1"></td>
                                                                <td rowspan="2" class="align-middle out-diff">-</td>
                                                                <td rowspan="2" class="align-middle out-tol fw-bold">-</td>
                                                                <td rowspan="2" class="align-middle out-avg-adb">-</td>
                                                                <td rowspan="2" class="align-middle out-avg-db">-</td>
                                                                <td class="align-middle out-db-1 fw-bold text-success">-</td>
                                                            @elseif($code === 'VM')
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m1_1]" value="{{ $mentah['m1_1'] ?? '' }}"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-m2-1 bg-light border-0" readonly tabindex="-1"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m2m1_1]" value="{{ $mentah['m2m1_1'] ?? '' }}"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m3_1]" value="{{ $mentah['m3_1'] ?? '' }}"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-m2m3-1 bg-light border-0" readonly tabindex="-1"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-loss-1 bg-light border-0" readonly tabindex="-1"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-im-1 bg-light border-0 text-secondary" readonly tabindex="-1"></td>
                                                                <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d1]" readonly tabindex="-1"></td>
                                                                <td rowspan="2" class="align-middle out-diff">-</td>
                                                                <td rowspan="2" class="align-middle out-tol fw-bold">-</td>
                                                                <td rowspan="2" class="align-middle out-avg-adb">-</td>
                                                                <td rowspan="2" class="align-middle out-avg-db">-</td>
                                                                <td class="align-middle out-db-1 fw-bold text-success">-</td>
                                                            @elseif($code === 'TS')
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-massa-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][massa_1]" value="{{ $mentah['massa_1'] ?? '' }}"></td>
                                                                <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d1]" value="{{ $dh->nilai_d1 ?? '' }}"></td>
                                                                <td rowspan="2" class="align-middle out-avg-adb fw-bold">-</td>
                                                                <td rowspan="2" class="align-middle out-avg-db fw-bold text-success">-</td>
                                                            @elseif($code === 'CV')
                                                                <td><input type="text" class="form-control form-control-sm in-callid-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][callid_1]" value="{{ $mentah['callid_1'] ?? '' }}"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-weight-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][weight_1]" value="{{ $mentah['weight_1'] ?? '' }}"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-massa-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][massa_1]" value="{{ $mentah['massa_1'] ?? '' }}"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-primary-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][primary_1]" value="{{ $mentah['primary_1'] ?? '' }}"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-ee-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][ee_1]" value="{{ $mentah['ee_1'] ?? '' }}"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-t-1 bg-light border-0" readonly tabindex="-1"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-titrant-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][titrant_1]" value="{{ $mentah['titrant_1'] ?? '' }}"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-length-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][length_1]" value="{{ $mentah['length_1'] ?? '' }}"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-ts-1 bg-light border-0 text-secondary" readonly tabindex="-1"></td>
                                                                <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d1]" readonly tabindex="-1"></td>
                                                                <td rowspan="2" class="align-middle out-avg-adb fw-bold">-</td>
                                                                <td rowspan="2" class="align-middle out-avg-db fw-bold">-</td>
                                                                <td class="align-middle out-db-1 fw-bold text-success">-</td>
                                                            @elseif($code === 'AFT')
                                                                <td rowspan="2" class="align-middle">
                                                                    <select class="form-select form-select-sm text-center in-aft-atmosfer" name="data_{{ $pid }}[{{ $i-1 }}][mentah][atmosfer]">
                                                                        <option value="Reducing" {{ ($mentah['atmosfer'] ?? 'Reducing') === 'Reducing' ? 'selected' : '' }}>Reducing</option>
                                                                        <option value="Oxidizing" {{ ($mentah['atmosfer'] ?? '') === 'Oxidizing' ? 'selected' : '' }}>Oxidizing</option>
                                                                    </select>
                                                                </td>
                                                                <td><input type="number" step="1" class="form-control form-control-sm text-center in-aft-idt-1 fw-bold" name="data_{{ $pid }}[{{ $i-1 }}][mentah][idt_1]" value="{{ $mentah['idt_1'] ?? $mentah['idt'] ?? '' }}"></td>
                                                                <td><input type="number" step="1" class="form-control form-control-sm text-center in-aft-st-1 fw-bold text-primary" name="data_{{ $pid }}[{{ $i-1 }}][mentah][st_1]" value="{{ $mentah['st_1'] ?? $mentah['st'] ?? '' }}"></td>
                                                                <td><input type="number" step="1" class="form-control form-control-sm text-center in-aft-ht-1 fw-bold" name="data_{{ $pid }}[{{ $i-1 }}][mentah][ht_1]" value="{{ $mentah['ht_1'] ?? $mentah['ht'] ?? '' }}"></td>
                                                                <td><input type="number" step="1" class="form-control form-control-sm text-center in-aft-ft-1 fw-bold" name="data_{{ $pid }}[{{ $i-1 }}][mentah][ft_1]" value="{{ $mentah['ft_1'] ?? $mentah['ft'] ?? '' }}"></td>
                                                            @elseif($code === 'CHN')
                                                                <td><input type="number" step="0.0001" class="form-control form-control-sm text-center in-chn-weight-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][weight_1]" value="{{ $mentah['weight_1'] ?? $mentah['weight'] ?? '' }}"></td>
                                                                <td><input type="number" step="0.01" class="form-control form-control-sm text-center in-chn-n-1 fw-bold text-primary" name="data_{{ $pid }}[{{ $i-1 }}][mentah][n_1]" value="{{ $mentah['n_1'] ?? $mentah['n'] ?? '' }}"></td>
                                                                <td><input type="number" step="0.01" class="form-control form-control-sm text-center in-chn-c-1 fw-bold text-primary" name="data_{{ $pid }}[{{ $i-1 }}][mentah][c_1]" value="{{ $mentah['c_1'] ?? $mentah['c'] ?? '' }}"></td>
                                                                <td><input type="number" step="0.01" class="form-control form-control-sm text-center in-chn-h-1 fw-bold text-primary" name="data_{{ $pid }}[{{ $i-1 }}][mentah][h_1]" value="{{ $mentah['h_1'] ?? $mentah['h'] ?? '' }}"></td>
                                                            @else
                                                                <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d1]" value="{{ $dh->nilai_d1 ?? '' }}"></td>
                                                                <td rowspan="2" class="align-middle out-diff">-</td>
                                                                <td rowspan="2" class="align-middle out-avg-adb">-</td>
                                                            @endif
                                                        </tr>

                                                        <tr class="row-duplo">
                                                            @if(!in_array($code, ['AFT', 'CHN']))
                                                                <td><input type="text" class="form-control form-control-sm text-center fw-bold bg-light" name="data_{{ $pid }}[{{ $i-1 }}][mentah][dish_2]" value="{{ $mentah['dish_2'] ?? '' }}"></td>
                                                            @endif
                                                            
                                                            @if($code === 'IM')
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m1_2]" value="{{ $mentah['m1_2'] ?? '' }}"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-m2-2 bg-light border-0" readonly tabindex="-1"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m3_2]" value="{{ $mentah['m3_2'] ?? '' }}"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-a-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][a_2]" value="{{ $mentah['a_2'] ?? '' }}"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-b-2 bg-light border-0" readonly tabindex="-1"></td>
                                                                <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d2]" readonly tabindex="-1"></td>
                                                            @elseif($code === 'ASH')
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m1_2]" value="{{ $mentah['m1_2'] ?? '' }}"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-m2-2 bg-light border-0" readonly tabindex="-1"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m2m1_2]" value="{{ $mentah['m2m1_2'] ?? '' }}"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m3_2]" value="{{ $mentah['m3_2'] ?? '' }}"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-m3m1-2 bg-light border-0" readonly tabindex="-1"></td>
                                                                <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d2]" readonly tabindex="-1"></td>
                                                                <td class="align-middle out-db-2 fw-bold text-success">-</td>
                                                            @elseif($code === 'VM')
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m1_2]" value="{{ $mentah['m1_2'] ?? '' }}"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-m2-2 bg-light border-0" readonly tabindex="-1"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m2m1_2]" value="{{ $mentah['m2m1_2'] ?? '' }}"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m3_2]" value="{{ $mentah['m3_2'] ?? '' }}"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-m2m3-2 bg-light border-0" readonly tabindex="-1"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-loss-2 bg-light border-0" readonly tabindex="-1"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-im-2 bg-light border-0 text-secondary" readonly tabindex="-1"></td>
                                                                <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d2]" readonly tabindex="-1"></td>
                                                                <td class="align-middle out-db-2 fw-bold text-success">-</td>
                                                            @elseif($code === 'TS')
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-massa-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][massa_2]" value="{{ $mentah['massa_2'] ?? '' }}"></td>
                                                                <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d2]" value="{{ $dh->nilai_d2 ?? '' }}"></td>
                                                            @elseif($code === 'CV')
                                                                <td><input type="text" class="form-control form-control-sm in-callid-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][callid_2]" value="{{ $mentah['callid_2'] ?? '' }}"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-weight-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][weight_2]" value="{{ $mentah['weight_2'] ?? '' }}"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-massa-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][massa_2]" value="{{ $mentah['massa_2'] ?? '' }}"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-primary-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][primary_2]" value="{{ $mentah['primary_2'] ?? '' }}"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-ee-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][ee_2]" value="{{ $mentah['ee_2'] ?? '' }}"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-t-2 bg-light border-0" readonly tabindex="-1"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-titrant-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][titrant_2]" value="{{ $mentah['titrant_2'] ?? '' }}"></td>
                                                                <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-length-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][length_2]" value="{{ $mentah['length_2'] ?? '' }}"></td>
                                                                <td><input type="text" class="form-control form-control-sm in-ts-2 bg-light border-0 text-secondary" readonly tabindex="-1"></td>
                                                                <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d2]" readonly tabindex="-1"></td>
                                                                <td class="align-middle out-db-2 fw-bold text-success">-</td>
                                                            @elseif($code === 'AFT')
                                                                <td><input type="number" step="1" class="form-control form-control-sm text-center in-aft-idt-2 fw-bold" name="data_{{ $pid }}[{{ $i-1 }}][mentah][idt_2]" value="{{ $mentah['idt_2'] ?? '' }}"></td>
                                                                <td><input type="number" step="1" class="form-control form-control-sm text-center in-aft-st-2 fw-bold text-primary" name="data_{{ $pid }}[{{ $i-1 }}][mentah][st_2]" value="{{ $mentah['st_2'] ?? '' }}"></td>
                                                                <td><input type="number" step="1" class="form-control form-control-sm text-center in-aft-ht-2 fw-bold" name="data_{{ $pid }}[{{ $i-1 }}][mentah][ht_2]" value="{{ $mentah['ht_2'] ?? '' }}"></td>
                                                                <td><input type="number" step="1" class="form-control form-control-sm text-center in-aft-ft-2 fw-bold" name="data_{{ $pid }}[{{ $i-1 }}][mentah][ft_2]" value="{{ $mentah['ft_2'] ?? '' }}"></td>
                                                            @elseif($code === 'CHN')
                                                                <td><input type="number" step="0.0001" class="form-control form-control-sm text-center in-chn-weight-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][weight_2]" value="{{ $mentah['weight_2'] ?? '' }}"></td>
                                                                <td><input type="number" step="0.01" class="form-control form-control-sm text-center in-chn-n-2 fw-bold text-primary" name="data_{{ $pid }}[{{ $i-1 }}][mentah][n_2]" value="{{ $mentah['n_2'] ?? '' }}"></td>
                                                                <td><input type="number" step="0.01" class="form-control form-control-sm text-center in-chn-c-2 fw-bold text-primary" name="data_{{ $pid }}[{{ $i-1 }}][mentah][c_2]" value="{{ $mentah['c_2'] ?? '' }}"></td>
                                                                <td><input type="number" step="0.01" class="form-control form-control-sm text-center in-chn-h-2 fw-bold text-primary" name="data_{{ $pid }}[{{ $i-1 }}][mentah][h_2]" value="{{ $mentah['h_2'] ?? '' }}"></td>
                                                            @else
                                                                <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d2]" value="{{ $dh->nilai_d2 ?? '' }}"></td>
                                                            @endif
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                                @if(in_array($code, ['AFT', 'CHN']))
                                                <tfoot class="table-light border-top border-2">
                                                    @if($code === 'AFT')
                                                        <tr class="fw-bold bg-light">
                                                            <td colspan="2" class="text-end">Rata-rata (Mean):</td>
                                                            <td id="aft_mean_idt_{{ $pid }}">-</td>
                                                            <td id="aft_mean_st_{{ $pid }}" class="text-primary">-</td>
                                                            <td id="aft_mean_ht_{{ $pid }}">-</td>
                                                            <td id="aft_mean_ft_{{ $pid }}">-</td>
                                                        </tr>
                                                        <tr class="fw-bold bg-light">
                                                            <td colspan="2" class="text-end">Standar Deviasi (STDEV):</td>
                                                            <td id="aft_sd_idt_{{ $pid }}">-</td>
                                                            <td id="aft_sd_st_{{ $pid }}" class="text-primary">-</td>
                                                            <td id="aft_sd_ht_{{ $pid }}">-</td>
                                                            <td id="aft_sd_ft_{{ $pid }}">-</td>
                                                        </tr>
                                                    @elseif($code === 'CHN')
                                                        <tr class="fw-bold bg-light">
                                                            <td colspan="2" class="text-end">Rata-rata (Mean):</td>
                                                            <td id="chn_mean_n_{{ $pid }}" class="text-primary">-</td>
                                                            <td id="chn_mean_c_{{ $pid }}" class="text-primary">-</td>
                                                            <td id="chn_mean_h_{{ $pid }}" class="text-primary">-</td>
                                                        </tr>
                                                        <tr class="fw-bold bg-light">
                                                            <td colspan="2" class="text-end">Standar Deviasi (STDEV):</td>
                                                            <td id="chn_sd_n_{{ $pid }}" class="text-primary">-</td>
                                                            <td id="chn_sd_c_{{ $pid }}" class="text-primary">-</td>
                                                            <td id="chn_sd_h_{{ $pid }}" class="text-primary">-</td>
                                                        </tr>
                                                    @endif
                                                </tfoot>
                                                @endif
                                            </table>
                                        </fieldset>
                                    </div>
                                     @if(in_array($batch->status, ['uji_homogenitas', 'gagal_homogenitas']))
                                        <div class="px-3 pb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                                            <div class="d-flex flex-wrap gap-2">
                                                <button type="button" class="btn btn-outline-primary btn-sm btn-tambah-kemasan fw-bold text-nowrap" data-pid="{{ $pid }}" data-code="{{ $code }}">
                                                    <i class="fas fa-plus me-1"></i> Tambah Kemasan
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-hapus-kemasan fw-bold text-nowrap" data-pid="{{ $pid }}" data-code="{{ $code }}">
                                                    <i class="fas fa-trash me-1"></i> Hapus Kemasan
                                                </button>
                                             </div>
                                            <div>
                                                <button type="button" class="btn btn-outline-success btn-sm btn-save-sheet fw-bold text-nowrap w-100" data-pid="{{ $pid }}" data-code="{{ $code }}">
                                                    <i class="fas fa-save me-1"></i> Simpan Tabel {{ $code }} Saja
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="card-footer bg-light p-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold mb-1 text-primary">Live ANOVA Summary (Preview)</h5>
                                <p class="small text-muted mb-0">Klik tombol di sebelah kanan untuk melihat detail tabel perhitungan (Ai+Bi) seperti di Excel.</p>
                            </div>
                            <button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetailAnova">
                                <i class="fas fa-table me-2"></i>Lihat Detail Kalkulasi Statistik
                            </button>
                        </div>
                        <div class="row text-center mt-3" id="anovaPreviewBoxes">

                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 pt-2 border-top">
                    <a href="{{ route('qc-inhouse.show', $batch->sampel_inhouse_id) }}" class="btn btn-secondary px-4 shadow-sm fw-semibold">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Detail
                    </a>

                    <div class="d-flex align-items-center gap-2">
                        @if(in_array($batch->status, ['uji_homogenitas', 'gagal_homogenitas']))
                            <button type="submit" class="btn btn-primary px-4 shadow-sm fw-semibold" id="btnSubmit">
                                <i class="fas fa-check-double me-2"></i> Kunci Semua & Lanjut ke Penetapan Target
                            </button>
                        @elseif(!in_array($batch->status, ['pemilihan_sampel', 'preparasi', 'uji_homogenitas', 'gagal_homogenitas']))
                            <a href="{{ route('qc-inhouse.penetapan-target', $batch->sampel_inhouse_id) }}" class="btn btn-primary px-4 shadow-sm fw-semibold">
                                Lihat Tahap 4: Target <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        @endif
                    </div>
                </div>
    
                <div class="modal fade" id="modalPreviewImBatch" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content shadow border-0">
                            <div class="modal-header py-2 px-3 border-bottom text-white" style="background-color: #1b3152;">
                                <h6 class="modal-title fw-bold text-white mb-0">
                                    <i class="fas fa-table me-2"></i>Preview Data Mentah & Bench Sheet IM (Inherent Moisture)
                                </h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded border">
                                    <div>
                                        <div class="fw-bold fs-6 text-primary" id="prev_im_batch_name">-</div>
                                        <div class="small text-muted" id="prev_im_batch_date">-</div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success px-2 py-1" id="prev_im_status">-</span>
                                    </div>
                                </div>

                                <div class="row g-2 text-center mb-3">
                                    <div class="col-4">
                                        <div class="p-2 border rounded bg-white">
                                            <div class="small text-muted" style="font-size: 0.7rem;">RATA-RATA (MEAN)</div>
                                            <div class="fs-6 fw-bold text-primary" id="prev_im_mean">-</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 border rounded bg-white">
                                            <div class="small text-muted" style="font-size: 0.7rem;">STANDAR DEVIASI</div>
                                            <div class="fs-6 fw-bold text-dark" id="prev_im_sd">-</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 border rounded bg-white">
                                            <div class="small text-muted" style="font-size: 0.7rem;">JUMLAH BOTOL</div>
                                            <div class="fs-6 fw-bold text-dark" id="prev_im_count">-</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive border rounded" style="max-height: 420px; overflow-y: auto;">
                                    <table class="table table-sm table-bordered text-center align-middle mb-0 text-nowrap" style="font-size: 0.75rem;">
                                        <thead class="table-light sticky-top" style="z-index: 1;">
                                            <tr style="background-color: #f1f4f9;">
                                                <th width="12%">KODE SAMPEL</th>
                                                <th>DISH NO.</th>
                                                <th>M1 (g)</th>
                                                <th>M2 (g)</th>
                                                <th>M3 (g)</th>
                                                <th>A (g)</th>
                                                <th>B (g)</th>
                                                <th class="bg-warning bg-opacity-25">M%</th>
                                                <th>DIFF</th>
                                                <th>TOLERANSI</th>
                                                <th class="bg-light">AVERAGE %</th>
                                            </tr>
                                        </thead>
                                        <tbody id="prev_im_table_body">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer bg-light py-2 px-3 justify-content-between">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                <button type="button" class="btn btn-primary btn-sm px-3" id="btn_use_this_im_batch">
                                    <i class="fas fa-check-circle me-1"></i> Gunakan Batch Ini
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalExport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-print me-2"></i>Cetak / Export Hasil Uji</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <label class="form-label fw-bold">Pilih Format Cetak:</label>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportFormat" id="fmtExcel" value="excel" checked>
                            <label class="form-check-label" for="fmtExcel"><i class="fas fa-file-excel text-success me-1"></i> Excel</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportFormat" id="fmtPdf" value="pdf">
                            <label class="form-check-label" for="fmtPdf"><i class="fas fa-file-pdf text-danger me-1"></i> PDF</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportFormat" id="fmtWord" value="word">
                            <label class="form-check-label" for="fmtWord"><i class="fas fa-file-word text-primary me-1"></i> Word</label>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Bagian yang Dicetak:</label>
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportPart" id="partBoth" value="both" checked>
                            <label class="form-check-label" for="partBoth">Keduanya (Tabel Data Utama & Detail Perhitungan ANOVA)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportPart" id="partMain" value="main">
                            <label class="form-check-label" for="partMain">Tabel Data Utama Saja</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportPart" id="partAnova" value="anova">
                            <label class="form-check-label" for="partAnova">Detail Perhitungan ANOVA Saja</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Parameter (Bisa pilih lebih dari satu):</label>
                    <div class="form-check mb-2 pb-2 border-bottom">
                        <input class="form-check-input" type="checkbox" id="chkExportAll" checked>
                        <label class="form-check-label fw-bold" for="chkExportAll">Semua Parameter</label>
                    </div>
                    <div id="exportParamCheckboxes">
                        @foreach($batch->parameters as $param)
                            @php
                                $rawCode = trim(strtoupper($param->parameterUji->nama_parameter));
                                $code = in_array($rawCode, ['TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']) ? 'TS' : $rawCode;
                            @endphp
                            <div class="form-check">
                                <input class="form-check-input chk-export-param" type="checkbox" value="{{ $code }}" id="chkExport{{ $code }}" checked>
                                <label class="form-check-label" for="chkExport{{ $code }}">{{ $rawCode }}</label>
                            </div>
                        @endforeach
                    </div>
                    <small class="text-danger mt-2 d-block" id="exportWarning" style="display: none !important;"><i class="fas fa-exclamation-triangle"></i> Hanya parameter yang datanya sudah lengkap terisi yang akan dicetak.</small>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnExecuteExport"><i class="fas fa-download me-2"></i>Download</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetailAnova" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-excel text-success me-2"></i>Detail Perhitungan Uji Homogenitas Sampel Inhouse Standard</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">

                <div class="mb-3 w-25">
                    <label class="fw-bold form-label">Pilih Parameter:</label>
                    <select class="form-select" id="modalParamSelect">
                        @foreach($batch->parameters as $param)
                            @php
                                $rawCode = trim(strtoupper($param->parameterUji->nama_parameter));
                                $code = in_array($rawCode, ['TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']) ? 'TS' : $rawCode;
                            @endphp
                            <option value="{{ $code }}">{{ $rawCode }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-white p-4 border rounded shadow-sm">
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="fw-bold mb-0" style="width: 25%;">I. DATA:</h6>
                        <h6 class="fw-bold mb-0 text-start" style="width: 75%;">II. PERHITUNGAN:</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center align-middle" style="min-width: 900px; border-color: #212529;">
                            <thead class="table-light align-middle border-dark">
                                <tr>
                                    <th rowspan="2">Kode<br>Contoh</th>
                                    <th colspan="2" id="modalValParamName" class="border-end border-dark">PARAMETER</th>
                                    <th colspan="3">Untuk Perhitungan MSB</th>
                                    <th colspan="3">Untuk Perhitungan MSW</th>
                                </tr>
                                <tr>
                                    <th>A (simplo)</th>
                                    <th class="border-end border-dark">B (duplo)</th>
                                    <th>(Ai + Bi)</th>
                                    <th>(Ai + Bi) - Xab</th>
                                    <th>[(Ai + Bi) - Xab]&sup2;</th>
                                    <th>(Ai - Bi)</th>
                                    <th>(Ai - Bi) - Xab</th>
                                    <th>[(Ai - Bi) - Xab]&sup2;</th>
                                </tr>
                            </thead>
                            <tbody id="modalTableCombined"></tbody>
                            <tfoot class="bg-light border-dark" id="modalTableCombinedFoot"></tfoot>
                        </table>
                    </div>

                    <div class="row mt-4 align-items-center border-bottom pb-4 mb-4">
                        <div class="col-md-8">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="text-end align-middle fw-bold w-25">MSB =</td>
                                    <td class="text-center align-middle border-bottom border-dark" style="width: 250px;">
                                        E [ (Ai + Bi) - Xab ]&sup2;
                                    </td>
                                    <td class="align-middle text-center w-25" rowspan="2">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <span>=</span>
                                            <div class="d-flex flex-column text-center">
                                                <span class="border-bottom border-dark px-2" id="modalValMsbFormulaAtas">-</span>
                                                <span id="modalValMsbDivisor">18</span>
                                            </div>
                                            <span>=</span>
                                            <span class="fw-bold" id="modalValMsbLengkap">-</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center align-middle">2 . (n-1)</td>
                                </tr>
                            </table>

                            <table class="table table-borderless table-sm mt-3 mb-0">
                                <tr>
                                    <td class="text-end align-middle fw-bold w-25">MSW =</td>
                                    <td class="text-center align-middle border-bottom border-dark" style="width: 250px;">
                                        E [ (Ai - Bi) - Xab ]&sup2;
                                    </td>
                                    <td class="align-middle text-center w-25" rowspan="2">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <span>=</span>
                                            <div class="d-flex flex-column text-center">
                                                <span class="border-bottom border-dark px-2" id="modalValMswFormulaAtas">-</span>
                                                <span id="modalValMswDivisor">20</span>
                                            </div>
                                            <span>=</span>
                                            <span class="fw-bold" id="modalValMswLengkap">-</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center align-middle">2 . (n)</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-white border shadow-sm text-center h-100 d-flex flex-column justify-content-center mx-auto" style="max-width: 250px;">
                                <div class="fs-5 mb-3 fw-bold text-muted">
                                    SD<sub><small>sampel in-house</small></sub> = &radic;<span style="border-top: 1px solid black; padding-top: 2px;">&nbsp;&nbsp;<frac>&Sigma; (x - x&#772;)&sup2;<br><span style="border-top: 1px solid black; display:block">N - 1</span></frac>&nbsp;&nbsp;</span>
                                </div>
                                <div class="d-flex justify-content-between mt-3 px-3">
                                    <span class="fw-bold text-muted">SD =</span>
                                    <span class="fw-bold text-dark" id="modalValSd">-</span>
                                </div>
                                <div class="d-flex justify-content-between mt-1 px-3">
                                    <span class="fw-bold text-muted">mean =</span>
                                    <span class="fw-bold text-dark" id="modalValMean">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <table class="table table-bordered table-sm text-center fw-bold">
                                <tr>
                                    <td class="bg-light w-25">MSB</td>
                                    <td id="modalValMsb">-</td>
                                </tr>
                                <tr>
                                    <td class="bg-light">MSW</td>
                                    <td id="modalValMsw">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-7">
                            <div class="p-3 bg-light border rounded">
                                <h6 class="fw-bold mb-3">III. KESIMPULAN F-TEST</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-center">
                                        <div class="small text-muted">F Hitung</div>
                                        <h4 class="fw-bold text-primary mb-0" id="modalValFhitung">-</h4>
                                    </div>
                                    <div class="text-center">
                                        <h4 class="fw-bold mb-0 text-secondary" id="modalValKesimpulanOp">?</h4>
                                    </div>
                                    <div class="text-center">
                                        <div class="small text-muted" id="modalValFtabelLabel">F Tabel</div>
                                        <h4 class="fw-bold text-dark mb-0" id="modalValFtabel">-</h4>
                                    </div>
                                </div>
                                <hr>
                                <div class="fs-6">
                                    <div class="mb-1">a) <span id="modalValKesimpulanTextA">-</span></div>
                                    <div>b) Hal ini artinya, bahwa contoh tersebut <strong id="modalValKesimpulanTextB">-</strong></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>

const fTabelLookup = @json($fTabelLookup ?? []);

const botolAcakLookup = {
    @if(isset($tabelAcak))
        @foreach($tabelAcak as $key => $item)
            "{{ $key }}": "{{ $item->nomor_botol ?? $key }}",
        @endforeach
    @endif
};

function getFTabelForN(n) {
    if (fTabelLookup[n] !== undefined) return fTabelLookup[n];
    const keys = Object.keys(fTabelLookup).map(Number).sort((a,b) => a-b);
    if (keys.length === 0) return null;
    const minN = keys[0], maxN = keys[keys.length-1];
    if (n < minN) return fTabelLookup[minN];
    if (n > maxN) return fTabelLookup[maxN];

    let lower = null, upper = null;
    for (const k of keys) {
        if (k <= n) lower = k;
        if (k >= n && upper === null) upper = k;
    }
    if (lower !== null && upper !== null && lower !== upper) {
        const ratio = (n - lower) / (upper - lower);
        return fTabelLookup[lower] + (fTabelLookup[upper] - fTabelLookup[lower]) * ratio;
    }
    return fTabelLookup[maxN];
}

const State = {};
@foreach($batch->parameters as $param)
    @php
        $rawCode = trim(strtoupper($param->parameterUji->nama_parameter));
        $code = in_array($rawCode, ['TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']) ? 'TS' : $rawCode;
    @endphp
    State["{{ $code }}"] = {
        id: {{ $param->id }},
        ready: false,
        data: Array.from({length: {{ $rowCount }} }, () => ({
            simplo_adb: null, duplo_adb: null, avg_adb: null
        })),
        limit: {{ $tolerances[$param->id] ?? 0.09 }}
    };
@endforeach

@if(!$batchHasIm && !empty($savedImReference['data']))
    State["IM"] = {
        ready: true,
        is_reference: true,
        data: @json($savedImReference['data'])
    };
@endif

document.addEventListener('DOMContentLoaded', function() {

    const tablesByCode = {};
    document.querySelectorAll('.param-table').forEach(table => {
        const code = table.dataset.code;
        tablesByCode[code] = table;

        table.addEventListener('input', function(e) {
            if(e.target.tagName === 'INPUT') {
                processTable(code, table);
            }
        });
        table.addEventListener('change', function(e) {
            if(e.target.tagName === 'INPUT') {
                processTable(code, table);
            }
        });

        table.addEventListener('blur', function(e) {
            if(e.target.tagName === 'INPUT' && (e.target.classList.contains('in-hasil-1') || e.target.classList.contains('in-hasil-2'))) {
                if(e.target.value && !e.target.readOnly) {
                    let dec = 2;
                    if(code === 'CV') dec = 0;
                    else if(code === 'TS') dec = 4; 
                    
                    e.target.value = rnd(e.target.value, dec);
                }
            }
        }, true); 
    });

    function tambahKemasanKeTabel(code) {
        const table = tablesByCode[code];
        if(!table) return;

        const tbody = table.querySelector('tbody');
        const simploRows = tbody.querySelectorAll('.row-simplo');
        const duploRows = tbody.querySelectorAll('.row-duplo');

        const newIndex = simploRows.length;
        const newUrutan = newIndex + 1;

        const newBotolFisik = botolAcakLookup[newUrutan] !== undefined ? botolAcakLookup[newUrutan] : newUrutan;
        const cloneSimplo = simploRows[0].cloneNode(true);
        const cloneDuplo = duploRows[0].cloneNode(true);

        cloneSimplo.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, `[${newIndex}]`);
        });
        cloneDuplo.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, `[${newIndex}]`);
        });

        cloneSimplo.querySelectorAll('input').forEach(el => { if(el.type !== 'hidden') el.value = ''; });
        cloneDuplo.querySelectorAll('input').forEach(el => { if(el.type !== 'hidden') el.value = ''; });

        const inDb1 = cloneSimplo.querySelector('.in-db-1');
        const inDb2 = cloneSimplo.querySelector('.in-db-2');
        if(inDb1) inDb1.value = '';
        if(inDb2) inDb2.value = '';

        const hiddenBotol = cloneSimplo.querySelector('input[name*="[nomor_botol_fisik]"]');
        if (hiddenBotol) hiddenBotol.value = newBotolFisik;

        const labelCell = cloneSimplo.querySelector('td[rowspan="2"]');
        if (labelCell) {

            if (labelCell.firstChild && labelCell.firstChild.nodeType === Node.TEXT_NODE) {
                labelCell.firstChild.textContent = ' ' + newUrutan + ' ';
            }

            const subLabel = labelCell.querySelector('div.text-muted');
            if (subLabel) subLabel.textContent = 'Botol ' + newBotolFisik;
        }

        cloneSimplo.querySelectorAll('td.out-diff, td.out-tol, td.out-avg-adb, td.out-avg-db, td.out-db-1').forEach(td => td.textContent = '-');
        cloneDuplo.querySelectorAll('td.out-db-2').forEach(td => td.textContent = '-');

        tbody.appendChild(cloneSimplo);
        tbody.appendChild(cloneDuplo);

        State[code].data.push({ simplo_adb: null, duplo_adb: null, avg_adb: null });
    }

    function hapusKemasanDariTabel(code) {
        const table = tablesByCode[code];
        if(!table) return false;

        const tbody = table.querySelector('tbody');
        const simploRows = tbody.querySelectorAll('.row-simplo');
        const duploRows = tbody.querySelectorAll('.row-duplo');

        if (simploRows.length <= 3) return false;

        const lastIndex = simploRows.length - 1;
        tbody.removeChild(simploRows[lastIndex]);
        tbody.removeChild(duploRows[lastIndex]);

        State[code].data.pop();
        return true;
    }

    document.querySelectorAll('.btn-tambah-kemasan').forEach(btn => {
        btn.addEventListener('click', function() {
            Object.keys(tablesByCode).forEach(code => {
                tambahKemasanKeTabel(code);
            });
            executionOrder.forEach(c => {
                if(tablesByCode[c]) processTable(c, tablesByCode[c]);
            });
            Object.keys(tablesByCode).forEach(code => {
                if(!executionOrder.includes(code)) processTable(code, tablesByCode[code]);
            });
        });
    });

    document.querySelectorAll('.btn-hapus-kemasan').forEach(btn => {
        btn.addEventListener('click', function() {
            const anyTableCode = Object.keys(tablesByCode)[0];
            const anyTable = tablesByCode[anyTableCode];
            const currentCount = anyTable ? anyTable.querySelectorAll('.row-simplo').length : 0;

            if (currentCount <= 3) {
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'error',
                    title: 'Minimal harus ada 3 kemasan untuk uji homogenitas.',
                    showConfirmButton: false, timer: 2200
                });
                return;
            }

            Object.keys(tablesByCode).forEach(code => {
                hapusKemasanDariTabel(code);
            });
            executionOrder.forEach(c => {
                if(tablesByCode[c]) processTable(c, tablesByCode[c]);
            });
            Object.keys(tablesByCode).forEach(code => {
                if(!executionOrder.includes(code)) processTable(code, tablesByCode[code]);
            });
        });
    });

    const executionOrder = ['IM', 'ASH', 'VM', 'TS', 'CV'];
    executionOrder.forEach(code => {
        if(tablesByCode[code]) processTable(code, tablesByCode[code]);
    });

    Object.keys(tablesByCode).forEach(code => {
        if(!executionOrder.includes(code)) processTable(code, tablesByCode[code]);
    });

    setInterval(() => {
        const activeTab = document.querySelector('.nav-link.active');
        if(activeTab) {
            const code = activeTab.dataset.code;
            const table = document.querySelector(`.param-table[data-code="${code}"]`);
            if(table) processTable(code, table);
        }
    }, 500);

    function rnd(val, dec=4) {
        if(isNaN(val) || val === null || val === '') return '-';
        return Number(val).toFixed(dec);
    }

    function getVal(el) {
        if(!el || el.value === undefined || el.value === null || el.value === '') return NaN;

        let v = el.value.toString().replace(/[^\d.,\-]/g, '');
        v = v.replace(/,/g, '.');
        return parseFloat(v);
    }

    function processTable(code, table) {
        if(code === 'AFT') {
            const idtVals = [], stVals = [], htVals = [];
            const ftVals = [];
            const simploRows = table.querySelectorAll('.row-simplo');
            const duploRows = table.querySelectorAll('.row-duplo');

            let filledCount = 0;
            for(let i = 0; i < simploRows.length; i++) {
                const tr1 = simploRows[i];
                const tr2 = duploRows[i];

                const idt1 = getVal(tr1.querySelector('.in-aft-idt-1'));
                const st1  = getVal(tr1.querySelector('.in-aft-st-1'));
                const ht1  = getVal(tr1.querySelector('.in-aft-ht-1'));
                const ft1  = getVal(tr1.querySelector('.in-aft-ft-1'));

                const idt2 = getVal(tr2.querySelector('.in-aft-idt-2'));
                const st2  = getVal(tr2.querySelector('.in-aft-st-2'));
                const ht2  = getVal(tr2.querySelector('.in-aft-ht-2'));
                const ft2  = getVal(tr2.querySelector('.in-aft-ft-2'));

                if(!isNaN(idt1)) idtVals.push(idt1);
                if(!isNaN(idt2)) idtVals.push(idt2);
                if(!isNaN(st1)) stVals.push(st1);
                if(!isNaN(st2)) stVals.push(st2);
                if(!isNaN(ht1)) htVals.push(ht1);
                if(!isNaN(ht2)) htVals.push(ht2);
                if(!isNaN(ft1)) ftVals.push(ft1);
                if(!isNaN(ft2)) ftVals.push(ft2);

                if(!isNaN(st1) && !isNaN(st2)) {
                    filledCount++;
                }
            }

            const pid = table.dataset.pid;
            const calcStats = (arr) => {
                if(arr.length === 0) return { mean: '-', sd: '-' };
                const mean = arr.reduce((a, b) => a + b, 0) / arr.length;
                if(arr.length < 2) return { mean: mean.toFixed(1), sd: '0.0' };
                const variance = arr.reduce((a, b) => a + Math.pow(b - mean, 2), 0) / (arr.length - 1);
                return { mean: mean.toFixed(1), sd: Math.sqrt(variance).toFixed(1) };
            };

            const sIdt = calcStats(idtVals), sSt = calcStats(stVals), sHt = calcStats(htVals), sFt = calcStats(ftVals);

            document.getElementById(`aft_mean_idt_${pid}`).textContent = sIdt.mean;
            document.getElementById(`aft_sd_idt_${pid}`).textContent = sIdt.sd;
            document.getElementById(`aft_mean_st_${pid}`).textContent = sSt.mean;
            document.getElementById(`aft_sd_st_${pid}`).textContent = sSt.sd;
            document.getElementById(`aft_mean_ht_${pid}`).textContent = sHt.mean;
            document.getElementById(`aft_sd_ht_${pid}`).textContent = sHt.sd;
            document.getElementById(`aft_mean_ft_${pid}`).textContent = sFt.mean;
            document.getElementById(`aft_sd_ft_${pid}`).textContent = sFt.sd;

            if(State['AFT']) {
                State['AFT'].ready = (filledCount >= 3);
            }
            return;
        }

        if(code === 'CHN') {
            const nVals = [], cVals = [], hVals = [];
            const simploRows = table.querySelectorAll('.row-simplo');
            const duploRows = table.querySelectorAll('.row-duplo');

            let filledCount = 0;
            for(let i = 0; i < simploRows.length; i++) {
                const tr1 = simploRows[i];
                const tr2 = duploRows[i];

                const n1 = getVal(tr1.querySelector('.in-chn-n-1'));
                const c1 = getVal(tr1.querySelector('.in-chn-c-1'));
                const h1 = getVal(tr1.querySelector('.in-chn-h-1'));

                const n2 = getVal(tr2.querySelector('.in-chn-n-2'));
                const c2 = getVal(tr2.querySelector('.in-chn-c-2'));
                const h2 = getVal(tr2.querySelector('.in-chn-h-2'));

                if(!isNaN(n1)) nVals.push(n1);
                if(!isNaN(n2)) nVals.push(n2);
                if(!isNaN(c1)) cVals.push(c1);
                if(!isNaN(c2)) cVals.push(c2);
                if(!isNaN(h1)) hVals.push(h1);
                if(!isNaN(h2)) hVals.push(h2);

                if(!isNaN(c1) && !isNaN(c2)) {
                    filledCount++;
                }
            }

            const pid = table.dataset.pid;
            const calcStats = (arr) => {
                if(arr.length === 0) return { mean: '-', sd: '-' };
                const mean = arr.reduce((a, b) => a + b, 0) / arr.length;
                if(arr.length < 2) return { mean: mean.toFixed(2), sd: '0.00' };
                const variance = arr.reduce((a, b) => a + Math.pow(b - mean, 2), 0) / (arr.length - 1);
                return { mean: mean.toFixed(2), sd: Math.sqrt(variance).toFixed(2) };
            };

            const sN = calcStats(nVals), sC = calcStats(cVals), sH = calcStats(hVals);

            document.getElementById(`chn_mean_n_${pid}`).textContent = sN.mean;
            document.getElementById(`chn_sd_n_${pid}`).textContent = sN.sd;
            document.getElementById(`chn_mean_c_${pid}`).textContent = sC.mean;
            document.getElementById(`chn_sd_c_${pid}`).textContent = sC.sd;
            document.getElementById(`chn_mean_h_${pid}`).textContent = sH.mean;
            document.getElementById(`chn_sd_h_${pid}`).textContent = sH.sd;

            if(State['CHN']) {
                State['CHN'].ready = (filledCount >= 3);
            }
            return;
        }

        let isComplete = true;
        const simploRowsAll = table.querySelectorAll('.row-simplo');
        const duploRowsAll = table.querySelectorAll('.row-duplo');
        const rowCount = simploRowsAll.length;

        for(let i=0; i<rowCount; i++) {
            const tr1 = simploRowsAll[i];
            const tr2 = duploRowsAll[i];

            let val1 = null;
            let val2 = null;

            if(code === 'IM') {
                const m1_1 = getVal(tr1.querySelector('.in-m1-1'));
                const a_1 = getVal(tr1.querySelector('.in-a-1'));
                const m3_1 = getVal(tr1.querySelector('.in-m3-1'));

                if(!isNaN(m1_1) && !isNaN(a_1)) tr1.querySelector('.in-m2-1').value = rnd(m1_1 + a_1, 4);
                if(!isNaN(m1_1) && !isNaN(m3_1)) tr1.querySelector('.in-b-1').value = rnd(m3_1 - m1_1, 4);

                if(!isNaN(m1_1) && !isNaN(a_1) && !isNaN(m3_1)) {
                    val1 = ((a_1 - (m3_1 - m1_1)) / a_1) * 100;
                    tr1.querySelector('.in-hasil-1').value = rnd(val1, 2);
                } else { isComplete = false; tr1.querySelector('.in-hasil-1').value = ''; }

                const m1_2 = getVal(tr2.querySelector('.in-m1-2'));
                const a_2 = getVal(tr2.querySelector('.in-a-2'));
                const m3_2 = getVal(tr2.querySelector('.in-m3-2'));

                if(!isNaN(m1_2) && !isNaN(a_2)) tr2.querySelector('.in-m2-2').value = rnd(m1_2 + a_2, 4);
                if(!isNaN(m1_2) && !isNaN(m3_2)) tr2.querySelector('.in-b-2').value = rnd(m3_2 - m1_2, 4);

                if(!isNaN(m1_2) && !isNaN(a_2) && !isNaN(m3_2)) {
                    val2 = ((a_2 - (m3_2 - m1_2)) / a_2) * 100;
                    tr2.querySelector('.in-hasil-2').value = rnd(val2, 2);
                } else { isComplete = false; tr2.querySelector('.in-hasil-2').value = ''; }
            }

            else if(code === 'ASH') {
                const m1_1 = getVal(tr1.querySelector('.in-m1-1'));
                const m2m1_1 = getVal(tr1.querySelector('.in-m2m1-1'));
                const m3_1 = getVal(tr1.querySelector('.in-m3-1'));

                if(!isNaN(m1_1) && !isNaN(m2m1_1)) {
                    tr1.querySelector('.in-m2-1').value = rnd(m1_1 + m2m1_1, 4);
                } else {
                    tr1.querySelector('.in-m2-1').value = '';
                }

                if(!isNaN(m3_1) && !isNaN(m1_1)) tr1.querySelector('.in-m3m1-1').value = rnd(m3_1 - m1_1, 4);

                if(!isNaN(m1_1) && !isNaN(m2m1_1) && !isNaN(m3_1)) {
                    val1 = ((m3_1 - m1_1) / m2m1_1) * 100;
                    tr1.querySelector('.in-hasil-1').value = rnd(val1, 2);

                    const im_s = State['IM'] ? State['IM'].data[i].simplo_adb : null;
                    if(im_s !== null) {
                        tr1.querySelector('.out-db-1').textContent = rnd((100 / (100 - im_s)) * val1, 2);
                    } else {
                        tr1.querySelector('.out-db-1').textContent = '-';
                    }
                } else {
                    isComplete = false;
                    tr1.querySelector('.in-hasil-1').value = '';
                    if(tr1.querySelector('.out-db-1')) tr1.querySelector('.out-db-1').textContent = '-';
                }

                const m1_2 = getVal(tr2.querySelector('.in-m1-2'));
                const m2m1_2 = getVal(tr2.querySelector('.in-m2m1-2'));
                const m3_2 = getVal(tr2.querySelector('.in-m3-2'));

                if(!isNaN(m1_2) && !isNaN(m2m1_2)) {
                    tr2.querySelector('.in-m2-2').value = rnd(m1_2 + m2m1_2, 4);
                } else {
                    tr2.querySelector('.in-m2-2').value = '';
                }

                if(!isNaN(m3_2) && !isNaN(m1_2)) tr2.querySelector('.in-m3m1-2').value = rnd(m3_2 - m1_2, 4);

                if(!isNaN(m1_2) && !isNaN(m2m1_2) && !isNaN(m3_2)) {
                    val2 = ((m3_2 - m1_2) / m2m1_2) * 100;
                    tr2.querySelector('.in-hasil-2').value = rnd(val2, 2);

                    const im_d = State['IM'] ? State['IM'].data[i].duplo_adb : null;
                    if(im_d !== null) {
                        tr2.querySelector('.out-db-2').textContent = rnd((100 / (100 - im_d)) * val2, 2);
                    } else {
                        tr2.querySelector('.out-db-2').textContent = '-';
                    }
                } else {
                    isComplete = false;
                    tr2.querySelector('.in-hasil-2').value = '';
                    if(tr2.querySelector('.out-db-2')) tr2.querySelector('.out-db-2').textContent = '-';
                }
            }

            else if(code === 'VM') {
                const im_s = State['IM'] ? State['IM'].data[i].simplo_adb : null;
                const im_d = State['IM'] ? State['IM'].data[i].duplo_adb : null;

                tr1.querySelector('.in-im-1').value = rnd(im_s, 2);
                tr2.querySelector('.in-im-2').value = rnd(im_d, 2);

                const m1_1 = getVal(tr1.querySelector('.in-m1-1'));
                const m2m1_1 = getVal(tr1.querySelector('.in-m2m1-1'));
                const m3_1 = getVal(tr1.querySelector('.in-m3-1'));

                if(!isNaN(m1_1) && !isNaN(m2m1_1)) tr1.querySelector('.in-m2-1').value = rnd(m2m1_1 + m1_1, 4);
                if(!isNaN(m1_1) && !isNaN(m2m1_1) && !isNaN(m3_1)) {
                    const m2 = m2m1_1 + m1_1;
                    const m2m3 = m2 - m3_1;
                    const loss = (m2m3 / m2m1_1) * 100;
                    tr1.querySelector('.in-m2m3-1').value = rnd(m2m3, 4);
                    tr1.querySelector('.in-loss-1').value = rnd(loss, 2);

                    if(im_s !== null) {
                        val1 = loss - im_s;
                        tr1.querySelector('.in-hasil-1').value = rnd(val1, 2);

                        tr1.querySelector('.out-db-1').textContent = rnd((100 / (100 - im_s)) * val1, 2);
                    } else {
                        tr1.querySelector('.out-db-1').textContent = '-';
                    }
                } else {
                    isComplete = false;
                    tr1.querySelector('.in-hasil-1').value = '';
                    if(tr1.querySelector('.out-db-1')) tr1.querySelector('.out-db-1').textContent = '-';
                }

                const m1_2 = getVal(tr2.querySelector('.in-m1-2'));
                const m2m1_2 = getVal(tr2.querySelector('.in-m2m1-2'));
                const m3_2 = getVal(tr2.querySelector('.in-m3-2'));

                if(!isNaN(m1_2) && !isNaN(m2m1_2)) tr2.querySelector('.in-m2-2').value = rnd(m2m1_2 + m1_2, 4);
                if(!isNaN(m1_2) && !isNaN(m2m1_2) && !isNaN(m3_2)) {
                    const m2 = m2m1_2 + m1_2;
                    const m2m3 = m2 - m3_2;
                    const loss = (m2m3 / m2m1_2) * 100;
                    tr2.querySelector('.in-m2m3-2').value = rnd(m2m3, 4);
                    tr2.querySelector('.in-loss-2').value = rnd(loss, 2);

                    if(im_d !== null) {
                        val2 = loss - im_d;
                        tr2.querySelector('.in-hasil-2').value = rnd(val2, 2);

                        tr2.querySelector('.out-db-2').textContent = rnd((100 / (100 - im_d)) * val2, 2);
                    } else {
                        tr2.querySelector('.out-db-2').textContent = '-';
                    }
                } else {
                    isComplete = false;
                    tr2.querySelector('.in-hasil-2').value = '';
                    if(tr2.querySelector('.out-db-2')) tr2.querySelector('.out-db-2').textContent = '-';
                }
            }

            else if(code === 'TS') {
                val1 = getVal(tr1.querySelector('.in-hasil-1'));
                val2 = getVal(tr2.querySelector('.in-hasil-2'));
                const mass1 = getVal(tr1.querySelector('.in-massa-1'));
                const mass2 = getVal(tr2.querySelector('.in-massa-2'));
                if(isNaN(val1) || isNaN(val2)) isComplete = false;
            }

            else if(code === 'CV') {
                const ts_s = State['TS'] ? State['TS'].data[i].simplo_adb : null;
                const ts_d = State['TS'] ? State['TS'].data[i].duplo_adb : null;

                tr1.querySelector('.in-ts-1').value = rnd(ts_s, 2);
                tr2.querySelector('.in-ts-2').value = rnd(ts_d, 2);

                const mass1 = getVal(tr1.querySelector('.in-massa-1'));
                const pri1 = getVal(tr1.querySelector('.in-primary-1'));
                const ee1 = getVal(tr1.querySelector('.in-ee-1'));
                const tit1 = getVal(tr1.querySelector('.in-titrant-1'));
                const len1 = getVal(tr1.querySelector('.in-length-1'));

                if(!isNaN(mass1) && !isNaN(pri1) && !isNaN(ee1)) {
                    tr1.querySelector('.in-t-1').value = rnd((pri1 / ee1) * mass1, 4);
                }

                if(!isNaN(mass1) && !isNaN(pri1) && !isNaN(ee1) && !isNaN(tit1) && !isNaN(len1) && ts_s !== null) {
                    val1 = Math.round(((pri1) - (14.3 * 0.0699 * tit1) - (2.3 * len1) - (13.2 * ts_s * mass1)) / mass1);
                    tr1.querySelector('.in-hasil-1').value = val1;
                } else { isComplete = false; tr1.querySelector('.in-hasil-1').value = ''; }

                const mass2 = getVal(tr2.querySelector('.in-massa-2'));
                const pri2 = getVal(tr2.querySelector('.in-primary-2'));
                const ee2 = getVal(tr2.querySelector('.in-ee-2'));
                const tit2 = getVal(tr2.querySelector('.in-titrant-2'));
                const len2 = getVal(tr2.querySelector('.in-length-2'));

                if(!isNaN(mass2) && !isNaN(pri2) && !isNaN(ee2)) {
                    tr2.querySelector('.in-t-2').value = rnd((pri2 / ee2) * mass2, 4);
                }

                if(!isNaN(mass2) && !isNaN(pri2) && !isNaN(ee2) && !isNaN(tit2) && !isNaN(len2) && ts_d !== null) {
                    val2 = Math.round(((pri2) - (14.3 * 0.0699 * tit2) - (2.3 * len2) - (13.2 * ts_d * mass2)) / mass2);
                    tr2.querySelector('.in-hasil-2').value = val2;
                } else { isComplete = false; tr2.querySelector('.in-hasil-2').value = ''; }
            }

            if(val1 !== null && val2 !== null && !isNaN(val1) && !isNaN(val2)) {
                let dec = 2;
                if(code === 'CV') dec = 0;
                else if(code === 'TS') dec = 4; 

                val1 = parseFloat(rnd(val1, dec));
                val2 = parseFloat(rnd(val2, dec));
                
                State[code].data[i].simplo_adb = val1;
                State[code].data[i].duplo_adb = val2;

                let avg_adb = (val1 + val2) / 2.0;
                avg_adb = parseFloat(rnd(avg_adb, dec));
                State[code].data[i].avg_adb = avg_adb;

                if (code === 'ASH' || code === 'VM' || code === 'TS' || code === 'CV') {
                    const im_s = State['IM'] ? State['IM'].data[i].simplo_adb : null;
                    State[code].data[i].simplo_db = im_s !== null ? parseFloat(rnd((100 / (100 - im_s)) * val1, code === 'CV' ? 0 : 2)) : null;
                    const im_d = State['IM'] ? State['IM'].data[i].duplo_adb : null;
                    State[code].data[i].duplo_db = im_d !== null ? parseFloat(rnd((100 / (100 - im_d)) * val2, code === 'CV' ? 0 : 2)) : null;

                    if (code === 'TS' || code === 'CV') {
                        tr1.querySelector('.out-db-1') ? tr1.querySelector('.out-db-1').textContent = State[code].data[i].simplo_db ?? '-' : null;
                        tr2.querySelector('.out-db-2') ? tr2.querySelector('.out-db-2').textContent = State[code].data[i].duplo_db ?? '-' : null;
                    }
                    if(tr1.querySelector('.in-db-1')) tr1.querySelector('.in-db-1').value = State[code].data[i].simplo_db ?? '';
                    if(tr1.querySelector('.in-db-2')) tr1.querySelector('.in-db-2').value = State[code].data[i].duplo_db ?? '';
                }
                const diff = Math.abs(val1 - val2);

                tr1.querySelector('.out-diff') ? tr1.querySelector('.out-diff').textContent = rnd(diff, (code==='CV'?0:2)) : null;
                tr1.querySelector('.out-avg-adb') ? tr1.querySelector('.out-avg-adb').textContent = rnd(avg_adb, (code==='CV'?0:2)) : null;

                if(code === 'IM' || code === 'ASH' || code === 'VM' || code === 'TS') {
                    let isOk = false;
                    if(code === 'IM') {
                        const limit = 0.09 + (0.1 * avg_adb);
                        isOk = diff < limit;
                    } else if(code === 'ASH') {
                        isOk = diff < 0.22;
                    } else if(code === 'VM') {
                        isOk = diff < 1;
                    } else if(code === 'TS') {
                        isOk = diff < State[code].limit;
                    }
                    const outTolEl = tr1.querySelector('.out-tol');
                    if(outTolEl) {
                        outTolEl.textContent = isOk ? 'YES' : 'NO';
                        outTolEl.classList.remove('text-success', 'text-danger');
                        outTolEl.classList.add(isOk ? 'text-success' : 'text-danger');
                    }
                }

                if(code !== 'IM') {

                    const im_avg = State['IM'] ? State['IM'].data[i].avg_adb : null;
                    if(im_avg !== null) {
                        const avg_db = (100 / (100 - im_avg)) * avg_adb;
                        tr1.querySelector('.out-avg-db') ? tr1.querySelector('.out-avg-db').textContent = rnd(avg_db, (code==='CV'?0:2)) : null;
                    }
                }
            } else {
                State[code].data[i].simplo_adb = null;
                State[code].data[i].duplo_adb = null;
                State[code].data[i].avg_adb = null;

                tr1.querySelector('.out-diff') ? tr1.querySelector('.out-diff').textContent = '-' : null;
                tr1.querySelector('.out-tol') ? tr1.querySelector('.out-tol').textContent = '-' : null;
                tr1.querySelector('.out-avg-adb') ? tr1.querySelector('.out-avg-adb').textContent = '-' : null;
                tr1.querySelector('.out-avg-db') ? tr1.querySelector('.out-avg-db').textContent = '-' : null;
            }
        }

        State[code].ready = isComplete;

        updateDependencies();

        if(isComplete) {
            calculateAnovaPreview(code);
        } else {

            State[code].anova = null;
            if(document.querySelector('.nav-link.active') && document.querySelector('.nav-link.active').dataset.code === code) {
                renderPreview();
            }
        }
    }

    function updateDependencies() {
        const batchHasImTab = {{ $batchHasIm ? 'true' : 'false' }};
        const isImReady = batchHasImTab ? (State['IM'] ? State['IM'].ready : false) : true;
        const isTsReady = State['TS'] ? State['TS'].ready : true;

        if(State['TS']) {
            const pane = document.getElementById('pane-' + State['TS'].id);
            if(!isImReady) {
                pane.style.opacity = '0.4'; pane.style.pointerEvents = 'none';
            } else {
                pane.style.opacity = '1'; pane.style.pointerEvents = 'auto';
            }
        }

        ['ASH', 'VM'].forEach(c => {
            if(State[c]) {
                const pane = document.getElementById('pane-' + State[c].id);
                if(!isImReady) {
                    pane.style.opacity = '0.4'; pane.style.pointerEvents = 'none';
                } else {
                    pane.style.opacity = '1'; pane.style.pointerEvents = 'auto';

                }
            }
        });

        if(State['CV']) {
            const pane = document.getElementById('pane-' + State['CV'].id);
            if(!isImReady || !isTsReady) {
                pane.style.opacity = '0.4'; pane.style.pointerEvents = 'none';
            } else {
                pane.style.opacity = '1'; pane.style.pointerEvents = 'auto';
            }
        }
    }

    function calculateAnovaPreview(code) {

        let sumA = 0, sumB = 0;
        const n = State[code].data.length;
        let ai = [], bi = [];

        for(let i=0; i<n; i++) {
            let a = State[code].data[i].simplo_adb;
            let b = State[code].data[i].duplo_adb;

            if (code === 'ASH' || code === 'VM' || code === 'TS' || code === 'CV') {
                a = State[code].data[i].simplo_db;
                b = State[code].data[i].duplo_db;
            }

            if(a === null || b === null || a === undefined || b === undefined || isNaN(a) || isNaN(b)) return;
            ai.push(a); bi.push(b);
            sumA += a; sumB += b;
        }

        const grandMean = (sumA + sumB) / (2 * n);

        let sumSqBetween = 0;
        let sumSqWithin = 0;

        for(let i=0; i<n; i++) {
            let groupMean = (ai[i] + bi[i]) / 2;
            sumSqBetween += Math.pow(groupMean - grandMean, 2);
            sumSqWithin += Math.pow(ai[i] - groupMean, 2) + Math.pow(bi[i] - groupMean, 2);
        }

        let msbSum = 0;
        let mswSum = 0;
        for(let i=0; i<n; i++) {
            msbSum += Math.pow((ai[i]+bi[i]) - (sumA+sumB)/n, 2);
            mswSum += Math.pow((ai[i]-bi[i]) - (sumA-sumB)/n, 2);
        }
        let MSB = msbSum / (2 * (n-1));
        let MSW = mswSum / (2 * n);

        let F = MSB / MSW;
        let FTabel = getFTabelForN(n);
        let isHomogen = FTabel !== null ? (F < FTabel) : false;

        State[code].anova = { n, ai, bi, msbSum, mswSum, MSB, MSW, F, FTabel, isHomogen };
        renderPreview();
    }

    function renderPreview() {
        const activeTab = document.querySelector('.nav-link.active');
        if(!activeTab) return;
        const code = activeTab.dataset.code;

        const elParamName = document.getElementById('modalParamName');
        if (elParamName) elParamName.textContent = code;

        const previewDiv = document.getElementById('anovaPreviewBoxes');
        const nKemasan = State[code].data.length;
        if(State[code].ready && State[code].anova) {
            const a = State[code].anova;
            previewDiv.innerHTML = `
                <div class="col-2">
                    <div class="small text-muted">MSB</div>
                    <h4 class="fw-bold text-dark">${rnd(a.MSB)}</h4>
                </div>
                <div class="col-2">
                    <div class="small text-muted">MSW</div>
                    <h4 class="fw-bold text-dark">${rnd(a.MSW)}</h4>
                </div>
                <div class="col-2">
                    <div class="small text-muted">F-Hitung</div>
                    <h4 class="fw-bold text-primary">${rnd(a.F)}</h4>
                </div>
                <div class="col-2">
                    <div class="small text-muted">F-Tabel (v1=${nKemasan-1}, v2=${nKemasan})</div>
                    <h4 class="fw-bold text-dark">${a.FTabel !== null ? rnd(a.FTabel) : '-'}</h4>
                </div>
                <div class="col-4">
                    <div class="small text-muted">Keputusan</div>
                    <h4 class="fw-bold ${a.isHomogen ? 'text-success' : 'text-danger'}">
                        ${a.isHomogen ? '<i class="fas fa-check-circle me-1"></i> HOMOGEN' : '<i class="fas fa-times-circle me-1"></i> TIDAK'}
                    </h4>
                </div>
            `;
        } else {
            previewDiv.innerHTML = `<div class="col-12"><p class="text-muted fst-italic">Lengkapi ${nKemasan} kemasan (simplo & duplo) untuk melihat Live ANOVA.</p></div>`;
        }
    }

    const modalParamSelect = document.getElementById('modalParamSelect');
    modalParamSelect.addEventListener('change', renderModalAnova);

    document.getElementById('modalDetailAnova').addEventListener('show.bs.modal', function () {
        const activeTabCode = document.querySelector('.nav-link.active').dataset.code;
        modalParamSelect.value = activeTabCode;
        renderModalAnova();
    });

    function renderModalAnova() {
        const code = modalParamSelect.value;
        if(!State[code] || !State[code].ready || !State[code].anova) {
            document.getElementById('modalTableCombined').innerHTML = `<tr><td colspan="9" class="text-danger fw-bold py-3 text-center">Data parameter ${code} belum lengkap atau gagal dihitung karena ada data dependensi (IM/TS) yang belum lengkap.</td></tr>`;
            document.getElementById('modalTableCombinedFoot').innerHTML = '';
            document.getElementById('modalValMsbFormulaAtas').textContent = '-';
            document.getElementById('modalValMsbDivisor').textContent = '-';
            document.getElementById('modalValMsbLengkap').textContent = '-';
            document.getElementById('modalValMswFormulaAtas').textContent = '-';
            document.getElementById('modalValMswDivisor').textContent = '-';
            document.getElementById('modalValMswLengkap').textContent = '-';
            document.getElementById('modalValMean').textContent = '-';
            document.getElementById('modalValMsb').textContent = '-';
            document.getElementById('modalValMsw').textContent = '-';
            document.getElementById('modalValFhitung').textContent = '-';
            document.getElementById('modalValFtabel').textContent = '-';
            document.getElementById('modalValFtabelLabel').textContent = 'F Tabel';
            document.getElementById('modalValKesimpulanOp').textContent = '-';
            document.getElementById('modalValKesimpulanTextA').textContent = '-';
            document.getElementById('modalValKesimpulanTextB').innerHTML = '-';
            return;
        }

        const a = State[code].anova;
        const n = a.n;
        let sumA = 0, sumB = 0;
        let ai = a.ai, bi = a.bi;

        for(let i=0; i<n; i++) { sumA += ai[i]; sumB += bi[i]; }
        const Xab = (sumA + sumB) / n;
        const XabMinus = (sumA - sumB) / n;

        let htmlCombined = '';
        let decData = code === 'CV' ? 0 : 2;

        for(let i=0; i<n; i++) {
            let aib = ai[i] + bi[i];
            let aibX = aib - Xab;
            let aibX2 = Math.pow(aibX, 2);

            let amb = ai[i] - bi[i];
            let ambX = amb - XabMinus;
            let ambX2 = Math.pow(ambX, 2);

            htmlCombined += `<tr>
                <td class="fst-italic">${i+1}</td>
                <td>${rnd(ai[i], decData)}</td>
                <td class="border-end border-dark">${rnd(bi[i], decData)}</td>
                <td>${rnd(aib, decData)}</td>
                <td>${rnd(aibX, 3)}</td>
                <td>${rnd(aibX2, 4)}</td>
                <td>${rnd(amb, 2)}</td>
                <td>${rnd(ambX, 3)}</td>
                <td>${rnd(ambX2, 4)}</td>
            </tr>`;
        }

        document.getElementById('modalTableCombined').innerHTML = htmlCombined;

        document.getElementById('modalTableCombinedFoot').innerHTML = `
            <tr class="text-start">
                <td colspan="3" class="border-end border-dark fw-bold">Banyaknya Grup (n) =</td>
                <td class="text-center fw-bold">${n}</td>
                <td colspan="2"></td>
                <td class="text-center fw-bold">${n}</td>
                <td colspan="2"></td>
            </tr>
            <tr class="text-start">
                <td colspan="3" class="border-end border-dark fw-bold">Jumlah (&Sigma;) =</td>
                <td class="text-center fw-bold">${rnd(sumA + sumB, decData)}</td>
                <td></td>
                <td class="text-center fw-bold">${rnd(a.msbSum, 4)}</td>
                <td class="text-center fw-bold">${rnd(XabMinus * n, 1)}</td>
                <td></td>
                <td class="text-center fw-bold">${rnd(a.mswSum, 4)}</td>
            </tr>
            <tr class="text-start">
                <td colspan="3" class="border-end border-dark fw-bold">Rata-rata (X) =</td>
                <td class="text-center fw-bold">${rnd((sumA + sumB) / n, decData)}</td>
                <td colspan="2"></td>
                <td class="text-center fw-bold">${rnd(XabMinus, 2)}</td>
                <td colspan="2"></td>
            </tr>
        `;

        const paramNames = {
            'IM': 'Inherent Moisture',
            'ASH': 'Ash Content',
            'VM': 'Volatile Matter',
            'TS': 'Total Sulfur',
            'CV': 'Calorific Value'
        };
        document.getElementById('modalValParamName').textContent = paramNames[code] || code;

        document.getElementById('modalValMsbFormulaAtas').textContent = rnd(a.msbSum);
        document.getElementById('modalValMsbDivisor').textContent = 2 * (n - 1);
        document.getElementById('modalValMsbLengkap').textContent = rnd(a.MSB, 6);

        document.getElementById('modalValMswFormulaAtas').textContent = rnd(a.mswSum);
        document.getElementById('modalValMswDivisor').textContent = 2 * n;
        document.getElementById('modalValMswLengkap').textContent = rnd(a.MSW, 6);
        
        let N_total = 2 * n;
        let sumSqTotal = 0;
        let gMean = (sumA + sumB) / N_total;
        for (let i = 0; i < n; i++) {
            sumSqTotal += Math.pow(ai[i] - gMean, 2);
            sumSqTotal += Math.pow(bi[i] - gMean, 2);
        }
        let sdVal = N_total > 1 ? Math.sqrt(sumSqTotal / (N_total - 1)) : 0;
        document.getElementById('modalValSd').textContent = rnd(sdVal);
        document.getElementById('modalValMean').textContent = rnd((sumA+sumB)/(2*n), 2);

        document.getElementById('modalValMsb').textContent = rnd(a.MSB);
        document.getElementById('modalValMsw').textContent = rnd(a.MSW);

        const fHitungVal = rnd(a.F);
        const fTabelVal = a.FTabel !== null ? rnd(a.FTabel) : '-';
        document.getElementById('modalValFhitung').textContent = fHitungVal;
        document.getElementById('modalValFtabel').textContent = fTabelVal;
        document.getElementById('modalValFtabelLabel').textContent = `F Tabel (v1=${n-1}, v2=${n})`;

        const opText = a.isHomogen ? '<' : '>';
        document.getElementById('modalValKesimpulanOp').textContent = opText;

        document.getElementById('modalValKesimpulanTextA').textContent = `F hitung (${fHitungVal}) ${opText} F tabel (${fTabelVal})`;
        document.getElementById('modalValKesimpulanTextB').innerHTML = a.isHomogen ? '<span class="text-success">HOMOGEN</span>' : '<span class="text-danger">TIDAK HOMOGEN</span>';
    }

    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(btn => {
        btn.addEventListener('shown.bs.tab', function (e) {
            renderPreview();

            const newCode = e.target.dataset.code;
            const tbl = document.querySelector(`.param-table[data-code="${newCode}"]`);
            if (tbl) processTable(newCode, tbl);
        });
    });

    document.querySelectorAll('.btn-save-sheet').forEach(btn => {
        btn.addEventListener('click', function() {
            const pid = this.dataset.pid;
            const code = this.dataset.code;
            const form = document.getElementById('formHomogenitas');

            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
            this.disabled = true;

            const allForm = new FormData(form);
            const formData = new FormData();

            // formData.append('_token', document.querySelector('input[name="_token"]').value);
            // formData.append('is_draft', '1');

            // table.querySelectorAll('input').forEach(inp => {
            //     if (inp.name) {
            //         formData.append(inp.name, inp.value);

            
            for (let [k, v] of allForm.entries()) {
                if (k === '_token' || k.startsWith(`data_${pid}[`) || k.startsWith(`resource_${pid}[`)) {
                    formData.append(k, v);

                }
            }
            formData.append('is_draft', '1');

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan!',
                        text: 'Data Parameter ' + code + ' berhasil disimpan sementara.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error', data.message || 'Gagal menyimpan.', 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error');
            })
            .finally(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });

    const chkExportAll = document.getElementById('chkExportAll');
    const paramCheckboxes = document.querySelectorAll('.chk-export-param');

    chkExportAll.addEventListener('change', function() {
        paramCheckboxes.forEach(chk => chk.checked = this.checked);
    });
    paramCheckboxes.forEach(chk => {
        chk.addEventListener('change', function() {
            if(!this.checked) chkExportAll.checked = false;
            else if(document.querySelectorAll('.chk-export-param:checked').length === paramCheckboxes.length) chkExportAll.checked = true;
        });
    });

    document.getElementById('btnExecuteExport').addEventListener('click', function() {
        const format = document.querySelector('input[name="exportFormat"]:checked').value;
        const part = document.querySelector('input[name="exportPart"]:checked').value;
        const selectedParams = Array.from(paramCheckboxes).filter(chk => chk.checked).map(chk => chk.value);

        if(selectedParams.length === 0) {
            Swal.fire('Peringatan', 'Pilih minimal satu parameter untuk dicetak.', 'warning');
            return;
        }

        let allReady = true;
        let notReadyParams = [];
        selectedParams.forEach(code => {
            if(!State[code] || !State[code].ready) {
                allReady = false;
                notReadyParams.push(code);
            }
        });

        if(!allReady) {
            Swal.fire('Tidak Dapat Mencetak', 'Parameter berikut belum lengkap: ' + notReadyParams.join(', ') + '. Lengkapi semua data Simplo dan Duplo terlebih dahulu.', 'error');
            return;
        }

        const originalBtnHtml = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        this.disabled = true;

        setTimeout(async () => {
            try {
                if(format === 'excel') {
                    exportToExcel(selectedParams, part);
                    this.innerHTML = originalBtnHtml;
                    this.disabled = false;
                } else if(format === 'pdf') {
                    await exportToPdf(selectedParams, part);
                    this.innerHTML = originalBtnHtml;
                    this.disabled = false;
                } else if(format === 'word') {
                    exportToWord(selectedParams, part);
                    this.innerHTML = originalBtnHtml;
                    this.disabled = false;
                }
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Kesalahan: ' + e.message, 'error');
                this.innerHTML = originalBtnHtml;
                this.disabled = false;
            }
        }, 300);
    });

    function exportToExcel(params, part) {
        const wb = XLSX.utils.book_new();
        const oldCode = modalParamSelect.value;

        params.forEach(code => {
            const ghost = document.createElement('div');

            if(part === 'both' || part === 'main') {
                const origTable = document.querySelector(`.param-table[data-code="${code}"]`);
                if(origTable) {
                    const tableClone = origTable.cloneNode(true);
                    const origSelects = origTable.querySelectorAll('select');
                    tableClone.querySelectorAll('select').forEach((sel, idx) => {
                        const o = origSelects[idx];
                        const text = o && o.selectedIndex >= 0 ? o.options[o.selectedIndex].text : sel.value;
                        sel.parentNode.replaceChild(document.createTextNode(text || ''), sel);
                    });
                    tableClone.querySelectorAll('input').forEach(inp => {
                        const text = document.createTextNode(inp.value);
                        inp.parentNode.replaceChild(text, inp);
                    });
                    ghost.appendChild(tableClone);
                }
            }

            if(part === 'both' || part === 'anova') {
                if(!['AFT', 'CHN'].includes(code)) {
                    modalParamSelect.value = code;
                    renderModalAnova();

                    const anovaTable = document.getElementById('modalTableCombined').closest('table');
                    if(anovaTable) {
                        const anovaClone = anovaTable.cloneNode(true);
                        ghost.appendChild(anovaClone);
                    }
                }
            }

            let combinedWsData = [];
            let tableIndex = 0;
            const tablesInGhost = ghost.querySelectorAll('table');

            if(part === 'both' || part === 'main') {
                if(tablesInGhost[tableIndex]) {
                    const ws1 = XLSX.utils.table_to_sheet(tablesInGhost[tableIndex]);
                    const json1 = XLSX.utils.sheet_to_json(ws1, {header:1, raw:false});
                    combinedWsData = combinedWsData.concat([[`HASIL UJI HOMOGENITAS - ${code}`], []]);
                    combinedWsData = combinedWsData.concat(json1);
                    tableIndex++;
                }
            }

            if(part === 'both' || part === 'anova') {
                if(tablesInGhost[tableIndex]) {
                    if(combinedWsData.length > 0) {
                        combinedWsData.push([]);
                        combinedWsData.push([]);
                    }
                    combinedWsData.push([`DETAIL PERHITUNGAN ANOVA - ${code}`]);
                    combinedWsData.push([]);
                    const ws2 = XLSX.utils.table_to_sheet(tablesInGhost[tableIndex]);
                    const json2 = XLSX.utils.sheet_to_json(ws2, {header:1, raw:false});
                    combinedWsData = combinedWsData.concat(json2);

                    combinedWsData.push([]);
                    combinedWsData.push(['KESIMPULAN F-TEST']);
                    combinedWsData.push(['MSB = ', document.getElementById('modalValMsbLengkap').textContent]);
                    combinedWsData.push(['MSW = ', document.getElementById('modalValMswLengkap').textContent]);
                    combinedWsData.push(['F Hitung = ', document.getElementById('modalValFhitung').textContent]);
                    combinedWsData.push(['F Tabel = ', document.getElementById('modalValFtabel').textContent]);
                    combinedWsData.push(['Status = ', document.getElementById('modalValKesimpulanTextB').innerText]);
                }
            }

            const finalWs = XLSX.utils.aoa_to_sheet(combinedWsData);
            XLSX.utils.book_append_sheet(wb, finalWs, code);
        });

        modalParamSelect.value = oldCode;
        renderModalAnova();

        XLSX.writeFile(wb, `Hasil_Uji_Homogenitas.xlsx`);
    }

    function buildExportHtml(params, part) {

        const logoUrl = window.location.origin + '/images/Logo_Suco_Nobg.png';

        let html = `<html><head><meta charset="utf-8"><style>
            body { font-family: 'Arial', sans-serif; font-size: 11px; color: black; }
            .official-table { border-collapse: collapse; width: 100%; font-size: 10px; border: 2px solid black; }
            .official-table th, .official-table td {
                border: 1px solid black;
                padding: 5px 4px;
                text-align: center;
                vertical-align: middle;
                line-height: 1.4;
                height: 22px;
                overflow: visible;
            }
            .official-table th { background-color: #e9ecef; font-weight: bold; border-bottom: 2px solid black; }
            .official-table thead { border: 2px solid black; }
            .pdf-break { page-break-before: always; break-before: page; }
            .official-table.table-wide { table-layout: fixed; width: 100%; font-size: 10px; }
            .official-table.table-wide th,
            .official-table.table-wide td { padding: 2px 1px; word-break: break-word; overflow-wrap: anywhere; }
        </style></head><body>`;

        let sectionCount = 0;
        function openSection() {
            const isFirst = sectionCount++ === 0;
            return isFirst
                ? `<div class="pdf-section">`
                : `<div class="pdf-section pdf-break" style="page-break-before: always; break-before: page;">`;
        }

        const oldCode = modalParamSelect.value;

        params.forEach((code, index) => {

            if(part === 'both' || part === 'main') {
                const origTable = document.querySelector(`.param-table[data-code="${code}"]`);
                if(origTable) {
                    html += openSection();
                    html += `<div style="font-size: 16px; font-weight: bold; text-align: center; margin-bottom: 10px;">DATA UTAMA UJI HOMOGENITAS - ${code}</div>`;
                    const tableClone = origTable.cloneNode(true);
                    tableClone.style.minWidth = 'auto';
                    tableClone.querySelectorAll('input[type="hidden"]').forEach(el => el.remove());
                    const origSelects = origTable.querySelectorAll('select');
                    tableClone.querySelectorAll('select').forEach((sel, idx) => {
                        const o = origSelects[idx];
                        const text = o && o.selectedIndex >= 0 ? o.options[o.selectedIndex].text : sel.value;
                        sel.parentNode.replaceChild(document.createTextNode(text || ''), sel);
                    });
                    tableClone.querySelectorAll('input').forEach(inp => {
                        const text = document.createTextNode(inp.value);
                        inp.parentNode.replaceChild(text, inp);
                    });
                    tableClone.className = "official-table";
                    if (['VM', 'CV', 'ASH'].includes(code)) tableClone.classList.add('table-wide');
                    tableClone.style.width = '100%';
                    html += tableClone.outerHTML;
                    html += `</div>`;
                }
            }

            if(part === 'both' || part === 'anova') {
                if(['AFT', 'CHN'].includes(code)) return;
                modalParamSelect.value = code;
                renderModalAnova();
                const a = State[code].anova;
                if(!a) return;

                const anovaTable = document.getElementById('modalTableCombined').closest('table');
                let tableHtml = '';
                if(anovaTable) {
                    const anovaClone = anovaTable.cloneNode(true);
                    anovaClone.className = 'official-table';
                    anovaClone.style.minWidth = '100%';
                    tableHtml = anovaClone.outerHTML;
                }

                const msbFormulaAtas = document.getElementById('modalValMsbFormulaAtas').textContent;
                const mswFormulaAtas = document.getElementById('modalValMswFormulaAtas').textContent;
                const msb = document.getElementById('modalValMsbLengkap').textContent;
                const msw = document.getElementById('modalValMswLengkap').textContent;
                const fHitung = document.getElementById('modalValFhitung').textContent;
                const fTabel = a.FTabel !== null ? a.FTabel.toFixed(4) : '-';
                const isHomogen = a.isHomogen;
                const opText = isHomogen ? '<' : '>';

                let paramFull = code;
                if(code === 'CV') paramFull = 'Gross Calorific Value';
                else if(code === 'IM') paramFull = 'Inherent Moisture';
                else if(code === 'ASH') paramFull = 'Ash Content';
                else if(code === 'VM') paramFull = 'Volatile Matter';
                else if(code === 'TS') paramFull = 'Total Sulfur';

                html += `
                ${openSection()}
                <div style="width: 100%; margin: 0 auto;">

                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td align="left" valign="bottom" style="font-size: 16px; font-weight: bold; padding-bottom: 5px;">
                                Perhitungan Uji Homogenitas Sampel <i>Inhouse Standard</i>
                            </td>
                            <td align="right" valign="bottom" width="150" style="padding-bottom: 5px;">
                                <img src="${logoUrl}" style="width: 140px; height: auto; display: block; margin-left: auto; page-break-inside: avoid;" alt="SUCOFINDO">
                            </td>
                        </tr>
                    </table>
                    <hr size="4" color="black" style="background-color: black; border: none; margin: 0; padding: 0;">
                    <br>

                    <table width="80%" border="0" cellpadding="4" cellspacing="0" style="font-size: 11px;">
                        <tr>
                            <td width="35%" align="left">Parameter Uji</td>
                            <td width="5%" align="center">:</td>
                            <td width="45%" bgcolor="#e9ecef" align="center" style="border: 1px solid #ccc;"><b>${paramFull}</b></td>
                            <td width="15%" align="left">&nbsp;&nbsp;(db)</td>
                        </tr>
                        <tr><td colspan="4" height="6"></td></tr>
                        <tr>
                            <td align="left">Kode Sampel Inhouse Standard</td>
                            <td align="center">:</td>
                            <td bgcolor="#e9ecef" style="border: 1px solid #ccc;"></td>
                            <td></td>
                        </tr>
                    </table>

                    <br><br>

                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:11px; font-weight:bold; margin-bottom:5px;">
                        <tr>
                            <td width="28%" align="left">I. DATA:</td>
                            <td width="73%" align="left">II. PERHITUNGAN:</td>
                        </tr>
                    </table>

                    ${tableHtml}

                    <br>

                    <table width="70%" border="0" cellpadding="5" cellspacing="0" bgcolor="#f0f0f0" style="font-size: 11px; margin-bottom: 8px;">
                        <tr>
                            <td align="right" width="10%"><b>MSB =</b></td>
                            <td align="center" width="30%">
                                <span style="border-bottom: 1px solid black;">E [ (Ai + Bi) - Xab ]&sup2;</span><br>2 . (n-1)
                            </td>
                            <td align="center" width="5%">=</td>
                            <td align="center" width="20%">
                                <span style="border-bottom: 1px solid black;">${msbFormulaAtas}</span><br>${2 * (a.n - 1)}
                            </td>
                            <td align="center" width="5%">=</td>
                            <td align="left" width="30%"><b>${msb}</b></td>
                        </tr>
                    </table>
                    <br>
                    <table width="70%" border="0" cellpadding="5" cellspacing="0" bgcolor="#f0f0f0" style="font-size: 11px; margin-bottom: 25px;">
                        <tr>
                            <td align="right" width="10%"><b>MSW =</b></td>
                            <td align="center" width="30%">
                                <span style="border-bottom: 1px solid black;">E [ (Ai - Bi) - Xab ]&sup2;</span><br>2 . (n)
                            </td>
                            <td align="center" width="5%">=</td>
                            <td align="center" width="20%">
                                <span style="border-bottom: 1px solid black;">${mswFormulaAtas}</span><br>${2 * a.n}
                            </td>
                            <td align="center" width="5%">=</td>
                            <td align="left" width="30%"><b>${msw}</b></td>
                        </tr>
                    </table>
                    <br>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 11px; font-weight: bold; margin-bottom: 8px;">
                        <tr>
                            <td>III. PERHITUNGAN Nilai "F hitung" dan "F tabel":</td>
                        </tr>
                    </table>

                    <table width="100%" border="0" cellpadding="10" cellspacing="0" bgcolor="#e9ecef" style="font-size: 11px; margin-bottom: 10px;">
                        <tr>
                            <td align="right" width="15%"><b>F hitung =</b></td>
                            <td align="center" width="15%">
                                <span style="border-bottom: 1px solid black;">MSB</span><br>MSW
                            </td>
                            <td align="center" width="5%">=</td>
                            <td align="center" width="15%">
                                <span style="background-color: #fff; padding: 4px 15px; border: 1px solid #ccc;"><b>${fHitung}</b></span>
                            </td>
                            <td align="center" width="5%"><b>${opText}</b></td>
                            <td align="left" width="45%">
                                <b>F tabel (p=0.05 ; v1= ${a.n - 1}; v2= ${a.n} ) =</b> &nbsp;&nbsp;&nbsp;
                                <span style="background-color: #fff; padding: 4px 15px; border: 1px solid #ccc;"><b>${fTabel}</b></span>
                            </td>
                        </tr>
                    </table>
                    <br>

                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 11px; margin-bottom: 20px;">
                        <tr>
                            <td align="left" valign="top" width="15%"><b>IV. KESIMPULAN:</b></td>
                            <td align="left" width="85%">
                                a). F hitung ${opText} F tabel
                                <br>
                                b). Hal ini artinya, bahwa contoh tersebut
                                <span style="font-weight:bold;">${isHomogen ? 'HOMOGEN' : 'TIDAK HOMOGEN'}</span>
                                <span style="font-size: 9px; color: #555;">( apabila F hitung &lt; F tabel maka Homogen, dan jika sebaliknya maka Tidak Homogen )</span>
                            </td>
                        </tr>
                    </table>

                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 11px;">
                        <tr>
                            <td align="left" width="15%">Disusun oleh</td>
                            <td align="center" width="30%" bgcolor="#e9ecef" style="border: 1px solid #ccc; height: 18px;"></td>
                            <td width="10%"></td>
                            <td align="right" width="10%">Tanggal :&nbsp;</td>
                            <td align="center" width="20%" bgcolor="#e9ecef" style="border: 1px solid #ccc;"></td>
                            <td width="15%"></td>
                        </tr>
                        <tr><td colspan="6" height="20"></td></tr>
                        <tr>
                            <td align="left">Diperiksa oleh</td>
                            <td align="center" bgcolor="#e9ecef" style="border: 1px solid #ccc; height: 18px;"></td>
                            <td></td>
                            <td align="right">Tanggal :&nbsp;</td>
                            <td align="center" bgcolor="#e9ecef" style="border: 1px solid #ccc;"></td>
                            <td></td>
                        </tr>
                    </table>

                    <br><br>

                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 9px;">
                        <tr>
                            <td align="left" width="30%">FOR/COAL-OPS/214</td>
                            <td align="center" width="20%">Rev. 01</td>
                            <td align="center" width="30%">Tgl. berlaku: 12/08/2023</td>
                            <td align="right" width="20%">Hal 1 dari 1 hal</td>
                        </tr>
                    </table>
                </div>
                </div>
                `;
            }
        });

        modalParamSelect.value = oldCode;
        renderModalAnova();

        html += `</body></html>`;
        return html;
    }

    async function exportToPdf(params, part) {
        const html = buildExportHtml(params, part);

        const doc = new DOMParser().parseFromString(html, 'text/html');
        const css = Array.from(doc.querySelectorAll('style'))
            .map(s => s.textContent).join('\n');
        const sections = Array.from(doc.querySelectorAll('.pdf-section'));

        if (sections.length === 0) {
            throw new Error('Tidak ada data untuk dicetak.');
        }

        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF({ unit: 'mm', format: 'a4', orientation: 'portrait' });
        const pageW = 210, pageH = 297, margin = 10;

        const stage = document.createElement('div');
        stage.style.cssText = 'position:fixed; left:0; top:0; z-index:-1; background:#fff; overflow:visible;';
        document.body.appendChild(stage);

        try {
            for (let i = 0; i < sections.length; i++) {
                const sec = sections[i];
                const mainTable = sec.querySelector('.official-table');
                const isWide = !!sec.querySelector('.table-wide') || !!sec.querySelector('.table-cv');

                let renderW = 720;
                if (mainTable) {
                    const cols = mainTable.querySelectorAll('thead tr:first-child th').length;
                    renderW = isWide ? 1000 : Math.max(720, cols * 65);
                }

                sec.classList.remove('pdf-break');
                sec.removeAttribute('style');
                sec.style.cssText = `width:${renderW}px; box-sizing:border-box; padding:8px; background:#fff;`;

                sec.querySelectorAll('input[type="hidden"]').forEach(el => el.remove());
                sec.querySelectorAll('table').forEach(tbl => {
                    tbl.classList.remove('text-nowrap');
                    tbl.style.minWidth = '0';
                    tbl.style.maxWidth = '100%';
                    tbl.style.width = '100%';
                });

                stage.innerHTML = '';
                const style = document.createElement('style');
                style.textContent = css;
                stage.appendChild(style);
                stage.appendChild(sec);

                // Tunggu logo dan gambar selesai dimuat 100% sebelum dirender
                const imgs = Array.from(sec.querySelectorAll('img'));
                await Promise.all(imgs.map(img => img.complete ? Promise.resolve() :
                    new Promise(res => { img.onload = img.onerror = res; })));

                const canvas = await html2canvas(sec, {
                    scale: 2,
                    useCORS: true,
                    backgroundColor: '#ffffff',
                    scrollX: 0,
                    scrollY: 0,
                    windowWidth: renderW + 40
                });

                const maxW = pageW - margin * 2;
                const maxH = pageH - margin * 2;
                const ratio = Math.min(maxW / canvas.width, maxH / canvas.height);
                const w = canvas.width * ratio;
                const h = canvas.height * ratio;

                if (i > 0) pdf.addPage('a4', 'portrait');

                pdf.addImage(canvas.toDataURL('image/jpeg', 0.98), 'JPEG',
                    (pageW - w) / 2, margin, w, h);
            }

            pdf.save('Hasil_Uji_Homogenitas.pdf');
        } finally {
            stage.remove();
        }
    }

    function wordifyTable(tbl, fontPt = 9, pad = 3, rowH = 0) {
        tbl.removeAttribute('class');
        tbl.removeAttribute('style');
        tbl.setAttribute('border', '1');
        tbl.setAttribute('cellspacing', '0');
        tbl.setAttribute('cellpadding', pad);
        tbl.setAttribute('width', '100%');
        tbl.style.cssText = `border-collapse:collapse; border:1px solid #000; font-size:${fontPt}pt; font-family:Arial;`;

        tbl.querySelectorAll('th, td').forEach(c => {
            const isHead = c.tagName === 'TH';
            c.setAttribute('align', 'center');
            c.setAttribute('valign', 'middle');
            if (rowH && !isHead) c.setAttribute('height', rowH);
            c.style.cssText =
                `border:1px solid #000; padding:${pad}px 3px; text-align:center; vertical-align:middle;` +
                (isHead ? 'background:#e9ecef; font-weight:bold;' : '');
            c.removeAttribute('class');
            c.removeAttribute('width');
        });
        return tbl;
    }

    function buildWordHtml(params, part) {
        const logoUrl = window.location.origin + '/images/Logo_Suco_Nobg.png';
        const oldCode = modalParamSelect.value;

        let body = '';
        let first = true;
        const pageBreak = () => {
            if (first) { first = false; return ''; }
            return `<br clear="all" style="page-break-before:always; mso-break-type:page-break">`;
        };

        params.forEach(code => {
            if (part === 'both' || part === 'main') {
                const orig = document.querySelector(`.param-table[data-code="${code}"]`);
                if (orig) {
                    const clone = orig.cloneNode(true);
                    clone.querySelectorAll('input[type="hidden"]').forEach(el => el.remove());
                    const origSelects = orig.querySelectorAll('select');
                    clone.querySelectorAll('select').forEach((sel, idx) => {
                        const o = origSelects[idx];
                        const text = o && o.selectedIndex >= 0 ? o.options[o.selectedIndex].text : sel.value;
                        sel.parentNode.replaceChild(document.createTextNode(text || ''), sel);
                    });
                    clone.querySelectorAll('input').forEach(inp => {
                        inp.parentNode.replaceChild(document.createTextNode(inp.value), inp);
                    });
                    wordifyTable(clone, code === 'CV' ? 5.5 : (['VM','ASH'].includes(code) ? 6.5 : 9));

                    body += pageBreak();
                    body += `<p style="text-align:center; font-size:14pt; font-weight:bold; font-family:Arial;">DATA UTAMA UJI HOMOGENITAS - ${code}</p>`;
                    body += clone.outerHTML;
                }
            }

            if (part === 'both' || part === 'anova') {
                if (['AFT', 'CHN'].includes(code)) return; // Lewati ANOVA untuk AFT & CHN

                modalParamSelect.value = code;
                renderModalAnova();
                const a = State[code].anova;
                if (!a) return;

                const anovaTable = document.getElementById('modalTableCombined').closest('table');
                let tableHtml = '';
                if (anovaTable) {
                    const c = anovaTable.cloneNode(true);
                    wordifyTable(c, 8, 4, 20);
                    tableHtml = c.outerHTML;
                }

                const msbAtas = document.getElementById('modalValMsbFormulaAtas').textContent;
                const mswAtas = document.getElementById('modalValMswFormulaAtas').textContent;
                const msb = document.getElementById('modalValMsbLengkap').textContent;
                const msw = document.getElementById('modalValMswLengkap').textContent;
                const fHitung = document.getElementById('modalValFhitung').textContent;
                const fTabel = a.FTabel !== null ? a.FTabel.toFixed(4) : '-';
                const opText = a.isHomogen ? '&lt;' : '&gt;';

                const names = { CV:'Gross Calorific Value', IM:'Inherent Moisture', ASH:'Ash Content', VM:'Volatile Matter', TS:'Total Sulfur' };
                const paramFull = names[code] || code;

                const f = () => `font-size:10pt; font-family:Arial;`;
                // baris spasi vertikal (Word mengikuti ini dengan akurat)
                const sp = (pt) => `<p style="margin:0; font-size:${pt}pt; line-height:${pt}pt; mso-line-height-rule:exactly;">&nbsp;</p>`;
                // pecahan: pembilang di atas, garis, penyebut di bawah
                const frac = (top, bottom) => `
                    <table border="0" cellspacing="0" cellpadding="2" align="center" style="${f()}">
                        <tr><td align="center" style="border-bottom:1px solid #000;">${top}</td></tr>
                        <tr><td align="center">${bottom}</td></tr>
                    </table>`;
                // kotak nilai putih bergaris
                const valBox = (v) => `<span style="background:#fff; border:1px solid #999; padding:4px 14px;"><b>${v}</b></span>`;

                body += pageBreak();
                body += `
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="left" valign="bottom" style="font-size:14pt; font-weight:bold; font-family:Arial;">
                    Perhitungan Uji Homogenitas Sampel <i>Inhouse Standard</i>
                    </td>
                    <td align="right" valign="bottom" width="150">
                    <img src="${logoUrl}" width="140" height="48" alt="SUCOFINDO">
                    </td>
                </tr>
                </table>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td style="border-top:3px solid #000; font-size:1pt; line-height:1pt;">&nbsp;</td></tr>
                </table>
                ${sp(10)}

                <table border="0" cellspacing="0" cellpadding="6" width="85%" style="${f()}">
                <tr>
                    <td width="33%">Parameter Uji</td><td width="4%" align="center">:</td>
                    <td width="45%" align="center" bgcolor="#e9ecef" style="border:1px solid #999;"><b>${paramFull}</b></td>
                    <td width="18%">&nbsp;&nbsp;(db)</td>
                </tr>
                <tr><td colspan="4" height="6" style="font-size:1pt;">&nbsp;</td></tr>
                <tr>
                    <td>Kode Sampel Inhouse Standard</td><td align="center">:</td>
                    <td bgcolor="#e9ecef" style="border:1px solid #999;">&nbsp;</td><td>&nbsp;</td>
                </tr>
                </table>
                ${sp(14)}

                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="${f()} font-weight:bold;">
                <tr><td width="28%">I. DATA:</td><td width="72%">II. PERHITUNGAN:</td></tr>
                </table>
                ${sp(4)}
                ${tableHtml}
                ${sp(14)}

                <table width="100%" border="0" cellspacing="0" cellpadding="8" bgcolor="#f0f0f0" style="${f()}">
                <tr>
                    <td width="11%" align="right"><b>MSB =</b></td>
                    <td width="33%" align="center">${frac('&Sigma; [ (Ai + Bi) - Xab ]&sup2;', '2 . (n-1)')}</td>
                    <td width="5%" align="center">=</td>
                    <td width="18%" align="center">${frac(msbAtas, 2*(a.n-1))}</td>
                    <td width="5%" align="center">=</td>
                    <td width="28%" align="left"><b>${msb}</b></td>
                </tr>
                </table>
                ${sp(8)}
                <table width="100%" border="0" cellspacing="0" cellpadding="8" bgcolor="#f0f0f0" style="${f()}">
                <tr>
                    <td width="11%" align="right"><b>MSW =</b></td>
                    <td width="33%" align="center">${frac('&Sigma; [ (Ai - Bi) - Xab ]&sup2;', '2 . (n)')}</td>
                    <td width="5%" align="center">=</td>
                    <td width="18%" align="center">${frac(mswAtas, 2*a.n)}</td>
                    <td width="5%" align="center">=</td>
                    <td width="28%" align="left"><b>${msw}</b></td>
                </tr>
                </table>
                ${sp(14)}

                <p style="${f()} font-weight:bold; margin:0;">III. PERHITUNGAN Nilai "F hitung" dan "F tabel":</p>
                ${sp(6)}
                <table width="100%" border="0" cellspacing="0" cellpadding="8" bgcolor="#e9ecef" style="${f()}">
                <tr>
                    <td width="15%" align="right"><b>F hitung =</b></td>
                    <td width="12%" align="center">${frac('MSB', 'MSW')}</td>
                    <td width="4%" align="center">=</td>
                    <td width="16%" align="center">${valBox(fHitung)}</td>
                    <td width="4%" align="center"><b>${opText}</b></td>
                    <td width="29%" align="right"><b>F tabel (p=0.05 ; v1= ${a.n-1}; v2= ${a.n}) =</b></td>
                    <td width="20%" align="center">${valBox(fTabel)}</td>
                </tr>
                </table>
                ${sp(14)}

                <table width="100%" border="0" cellspacing="0" cellpadding="3" style="${f()}">
                <tr>
                    <td width="17%" valign="top"><b>IV. KESIMPULAN:</b></td>
                    <td width="83%">
                    a). F hitung ${opText} F tabel<br>
                    b). Hal ini artinya, bahwa contoh tersebut <b>${a.isHomogen ? 'HOMOGEN' : 'TIDAK HOMOGEN'}</b>
                    <span style="font-size:8pt; color:#555;">( apabila F hitung &lt; F tabel maka Homogen, dan jika sebaliknya maka Tidak Homogen )</span>
                    </td>
                </tr>
                </table>
                ${sp(16)}

                <table width="100%" border="0" cellspacing="0" cellpadding="4" style="${f()}">
                <tr>
                    <td width="15%">Disusun oleh</td>
                    <td width="32%" bgcolor="#e9ecef" style="border:1px solid #999;" height="24">&nbsp;</td>
                    <td width="8%">&nbsp;</td>
                    <td width="13%" align="right">Tanggal :&nbsp;</td>
                    <td width="22%" bgcolor="#e9ecef" style="border:1px solid #999;">&nbsp;</td>
                    <td width="10%">&nbsp;</td>
                </tr>
                <tr><td colspan="6" height="10" style="font-size:1pt;">&nbsp;</td></tr>
                <tr>
                    <td>Diperiksa oleh</td>
                    <td bgcolor="#e9ecef" style="border:1px solid #999;" height="24">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="right">Tanggal :&nbsp;</td>
                    <td bgcolor="#e9ecef" style="border:1px solid #999;">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                </table>
                ${sp(20)}

                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:8pt; font-family:Arial;">
                <tr>
                    <td width="30%" align="left">FOR/COAL-OPS/214</td>
                    <td width="20%" align="center">Rev. 01</td>
                    <td width="30%" align="center">Tgl. berlaku: 12/08/2023</td>
                    <td width="20%" align="right">Hal 1 dari 1 hal</td>
                </tr>
                </table>`;
            }
        });

        modalParamSelect.value = oldCode;
        renderModalAnova();

        return `<html xmlns:o="urn:schemas-microsoft-com:office:office"
                    xmlns:w="urn:schemas-microsoft-com:office:word"
                    xmlns="http://www.w3.org/TR/REC-html40">
        <head>
        <meta charset="utf-8">
        <!--[if gte mso 9]><xml><w:WordDocument><w:View>Print</w:View><w:Zoom>100</w:Zoom></w:WordDocument></xml><![endif]-->
        <style>
            @page WordSection1 {
                size: 21cm 29.7cm;        
                mso-page-orientation: portrait;
                margin: 1.5cm 1.5cm 1.5cm 1.5cm;
            }
            div.WordSection1 { page: WordSection1; }
            body { font-family: Arial, sans-serif; }
            table { border-collapse: collapse; }
        </style>
        </head>
        <body><div class="WordSection1">${body}</div></body></html>`;
    }

    function exportToWord(params, part) {
        const htmlContent = buildWordHtml(params, part);
        const blob = new Blob(['\ufeff', htmlContent], { type: 'application/msword' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'Hasil_Uji_Homogenitas.doc';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        setTimeout(() => URL.revokeObjectURL(url), 1000);
    }

    function updateResourceSummary(pid) {
        let modal = document.getElementById('modalResource-' + pid);
        if(!modal) return;

        let personils = [], alats = [], bahans = [];

        modal.querySelectorAll('.chk-personil:checked').forEach(cb => {
            let peran = cb.closest('tr').querySelector('.in-peran').value || 'Analis';
            let text = cb.closest('tr').querySelectorAll('td')[1].innerText;
            personils.push(`${text} (${peran})`);
        });

        modal.querySelectorAll('.chk-alat:checked').forEach(cb => {
            let text = cb.closest('.form-check').querySelector('strong').innerText;
            alats.push(text);
        });

        modal.querySelectorAll('.chk-bahan:checked').forEach(cb => {
            let qty = cb.closest('tr').querySelector('.in-qty').value || '0';
            let satuan = cb.closest('tr').querySelector('.input-group-text').innerText;
            bahans.push(`${cb.dataset.nama} (${qty} ${satuan})`);
        });

        let summaryDiv = document.getElementById('summary_' + pid);
        let contentDiv = summaryDiv.querySelector('.summary-content');

        if (personils.length || alats.length || bahans.length) {
                let html = '';
                if(personils.length) html += `<div><strong class="text-primary">Analis:</strong> ${personils.join(', ')}</div>`;
                if(alats.length) html += `<div><strong class="text-success">Alat:</strong> ${alats.join(', ')}</div>`;
                if(bahans.length) html += `<div><strong class="text-warning">Bahan:</strong> ${bahans.join(', ')}</div>`;

            contentDiv.innerHTML = html;
            summaryDiv.classList.remove('d-none');
        } else {
            summaryDiv.classList.add('d-none');
        }
    }

    document.querySelectorAll('.modal-resource').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function () {
            let pid = this.id.replace('modalResource-', '');
            updateResourceSummary(pid);
        });
    });

    document.querySelectorAll('.modal-resource').forEach(modal => {
        let pid = modal.id.replace('modalResource-', '');
        updateResourceSummary(pid);
    });

    document.querySelectorAll('.btn-copy-resource').forEach(btn => {
        btn.addEventListener('click', function() {
            let currentPid = this.dataset.pid;
            let currentTab = document.querySelector('#parameterTabs .nav-link.active');
            let prevTabItem = currentTab.closest('li.nav-item').previousElementSibling;

            if (prevTabItem) {
                let prevTabLink = prevTabItem.querySelector('.nav-link');
                let prevPid = prevTabLink.dataset.pid || prevTabLink.id.replace('tab-', '');

                let prevModal = document.getElementById('modalResource-' + prevPid);
                let currModal = document.getElementById('modalResource-' + currentPid);

                if (prevModal && currModal) {
                    currModal.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
                    currModal.querySelectorAll('.in-peran, .in-qty').forEach(inpt => inpt.value = '');

                    prevModal.querySelectorAll('.chk-personil:checked').forEach(cb => {
                        let targetCb = currModal.querySelector(`.chk-personil[value="${cb.value}"]`);
                        if(targetCb) {
                            targetCb.checked = true;
                            targetCb.closest('tr').querySelector('.in-peran').value = cb.closest('tr').querySelector('.in-peran').value;
                        }
                    });

                    prevModal.querySelectorAll('.chk-alat:checked').forEach(cb => {
                        let targetCb = currModal.querySelector(`.chk-alat[value="${cb.value}"]`);
                        if(targetCb) targetCb.checked = true;
                    });

                    prevModal.querySelectorAll('.chk-bahan:checked').forEach(cb => {
                        let targetCb = currModal.querySelector(`.chk-bahan[value="${cb.value}"]`);
                        if(targetCb) {
                            targetCb.checked = true;
                            targetCb.closest('tr').querySelector('.in-qty').value = cb.closest('tr').querySelector('.in-qty').value;
                        }
                    });
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Berhasil menyalin data dari parameter sebelumnya!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    updateResourceSummary(currentPid);
                }
            } else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: 'Ini adalah parameter pertama, tidak ada data sebelumnya yang bisa disalin.',
                    showConfirmButton: false,
                    timer: 2500
                });
            }
        });
    });

    document.querySelectorAll('.in-qty').forEach(input => {
        input.addEventListener('input', function() {
            let tr = this.closest('tr');
            let checkbox = tr.querySelector('.chk-bahan');
            if (this.value && parseFloat(this.value) > 0) {
                checkbox.checked = true;
            } else {
                checkbox.checked = false;
            }
        });
    });

    document.querySelectorAll('.chk-bahan').forEach(cb => {
        cb.addEventListener('change', function () {
            if (!this.checked) {
                let tr = this.closest('tr');
                let qtyInput = tr.querySelector('.in-qty');
                if (qtyInput) qtyInput.value = '';
            }
        });
    });

        const availableImBatches = @json($availableImBatches ?? []);

    // Toggle radio mode
    document.querySelectorAll('input[name="radio_im_mode"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const isManual = this.value === 'manual';
            document.getElementById('col_select_batch')?.classList.toggle('d-none', isManual);
            document.getElementById('col_manual_im')?.classList.toggle('d-none', !isManual);
        });
    });

    // Handler klik Terapkan Referensi IM
    const btnApplyIm = document.getElementById('btn_apply_im');
    if (btnApplyIm) {
        btnApplyIm.addEventListener('click', function() {
            const mode = document.querySelector('input[name="radio_im_mode"]:checked')?.value || 'batch';
            let imDataArray = [];
            let summaryText = '';

            const anyTable = Object.values(tablesByCode)[0];
            const totalRows = anyTable ? anyTable.querySelectorAll('.row-simplo').length : 10;

            if (mode === 'batch') {
                const batchId = document.getElementById('select_im_batch').value;
                if (!batchId) {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Pilih batch acuan IM terlebih dahulu.', showConfirmButton: false, timer: 2500 });
                    return;
                }
                const found = availableImBatches.find(b => String(b.id) === String(batchId));
                if (!found || !found.data || found.data.length === 0) {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Batch yang dipilih belum memiliki data IM.', showConfirmButton: false, timer: 2500 });
                    return;
                }

                const avgFallback = found.data.reduce((sum, d) => sum + (d.avg_adb || 0), 0) / (found.data.length || 1);

                for (let i = 0; i < totalRows; i++) {
                    const rowData = found.data[i];
                    if (rowData && rowData.simplo_adb !== null && rowData.duplo_adb !== null) {
                        imDataArray.push({
                            nomor_sampel: i + 1,
                            simplo_adb: rowData.simplo_adb,
                            duplo_adb: rowData.duplo_adb,
                            avg_adb: rowData.avg_adb ?? ((rowData.simplo_adb + rowData.duplo_adb) / 2)
                        });
                    } else {
                        imDataArray.push({
                            nomor_sampel: i + 1,
                            simplo_adb: parseFloat(rnd(avgFallback, 2)),
                            duplo_adb: parseFloat(rnd(avgFallback, 2)),
                            avg_adb: parseFloat(rnd(avgFallback, 2))
                        });
                    }
                }

                document.getElementById('in_ref_im_mode').value = 'batch';
                document.getElementById('in_ref_im_batch_id').value = batchId;
                document.getElementById('in_ref_im_manual').value = '';
                summaryText = `✓ Menggunakan data IM dari batch ${found.kode_batch} (${found.nama_sampel}).`;
            } else {
                const val = parseFloat(document.getElementById('input_manual_im_val').value);
                if (isNaN(val) || val <= 0 || val >= 100) {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Masukkan nilai IM yang valid (0.01 - 99.99 %).', showConfirmButton: false, timer: 2500 });
                    return;
                }

                for (let i = 0; i < totalRows; i++) {
                    imDataArray.push({
                        nomor_sampel: i + 1,
                        simplo_adb: val,
                        duplo_adb: val,
                        avg_adb: val
                    });
                }

                document.getElementById('in_ref_im_mode').value = 'manual';
                document.getElementById('in_ref_im_batch_id').value = '';
                document.getElementById('in_ref_im_manual').value = val;
                summaryText = `✓ Menggunakan nilai IM tetap: ${val}% adb untuk seluruh botol.`;
            }

            document.getElementById('in_ref_im_data').value = JSON.stringify(imDataArray);

            State['IM'] = {
                ready: true,
                is_reference: true,
                data: imDataArray
            };

            const badge = document.getElementById('imRefBadge');
            if (badge) {
                badge.className = 'badge bg-success';
                badge.textContent = 'Referensi IM Aktif';
            }
            const summaryEl = document.getElementById('imRefSummary');
            if (summaryEl) {
                summaryEl.className = 'small mt-2 text-success fw-bold';
                summaryEl.textContent = summaryText;
            }

            // Memicu perhitungan otomatis pada seluruh tabel batubara (ASH, VM, TS, CV)
            ['ASH', 'VM', 'TS', 'CV'].forEach(code => {
                if (tablesByCode[code]) processTable(code, tablesByCode[code]);
            });

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Data referensi IM berhasil diterapkan ke seluruh pengujian!',
                showConfirmButton: false,
                timer: 2200
            });
        });
    }
        const selectImBatchEl = document.getElementById('select_im_batch');
    const quickPreviewEl = document.getElementById('quick_preview_im_text');

    // Fungsi update ringkasan kecil saat dropdown berubah
    function updateQuickPreview() {
        if (!selectImBatchEl || !quickPreviewEl) return;
        const bId = selectImBatchEl.value;
        if (!bId) {
            quickPreviewEl.textContent = '';
            return;
        }
        const bData = availableImBatches.find(b => String(b.id) === String(bId));
        if (bData && bData.data && bData.data.length > 0) {
            const avgVal = bData.mean_global !== null ? bData.mean_global : (bData.data.reduce((s, d) => s + (d.avg_adb || 0), 0) / bData.data.length);
            const sdVal = bData.sd_global !== null ? bData.sd_global : '-';
            quickPreviewEl.innerHTML = `ℹ️ <strong>Rata-rata IM:</strong> ${rnd(avgVal, 2)}% | <strong>SD:</strong> ${rnd(sdVal, 4)} | <strong>Botol:</strong> ${bData.data.length}`;
        } else {
            quickPreviewEl.textContent = '';
        }
    }

    if (selectImBatchEl) {
        selectImBatchEl.addEventListener('change', updateQuickPreview);
        updateQuickPreview(); // Jalankan saat awal jika ada yang terpilih
    }

    // Handler klik tombol Preview Modal
    const btnPreviewIm = document.getElementById('btn_preview_im');
    if (btnPreviewIm) {
        btnPreviewIm.addEventListener('click', function() {
            const bId = selectImBatchEl ? selectImBatchEl.value : '';
            if (!bId) {
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'warning',
                    title: 'Pilih batch acuan terlebih dahulu untuk melihat preview.',
                    showConfirmButton: false, timer: 2500
                });
                return;
            }

            const bData = availableImBatches.find(b => String(b.id) === String(bId));
            if (!bData || !bData.data || bData.data.length === 0) {
                Swal.fire('Data Tidak Ada', 'Batch ini belum memiliki data pengujian IM.', 'info');
                return;
            }

            // Isi informasi modal
            document.getElementById('prev_im_batch_name').textContent = `${bData.kode_batch} - ${bData.nama_sampel}`;
            document.getElementById('prev_im_batch_date').textContent = `Tanggal: ${bData.tanggal}`;
            document.getElementById('prev_im_status').textContent = (bData.status_parameter || 'HOMOGEN').toUpperCase();

            const avgVal = bData.mean_global !== null ? bData.mean_global : (bData.data.reduce((s, d) => s + (d.avg_adb || 0), 0) / bData.data.length);
            document.getElementById('prev_im_mean').textContent = `${rnd(avgVal, 2)} %`;
            document.getElementById('prev_im_sd').textContent = bData.sd_global !== null ? rnd(bData.sd_global, 4) : '-';
            document.getElementById('prev_im_count').textContent = `${bData.data.length} Botol`;

            // Buat baris tabel botol (Simplo & Duplo lengkap dengan data penimbangan M1, M2, M3, A, B)
            let rowsHtml = '';
            bData.data.forEach((row, idx) => {
                const bNo = row.nomor_botol_fisik || (idx + 1);

                // --- Simplo ---
                const dish1 = (row.dish_1 !== undefined && row.dish_1 !== null && row.dish_1 !== '') ? row.dish_1 : '-';
                const m1_1 = (row.m1_1 !== undefined && row.m1_1 !== null && row.m1_1 !== '') ? row.m1_1 : '-';
                const a_1 = (row.a_1 !== undefined && row.a_1 !== null && row.a_1 !== '') ? row.a_1 : '-';
                const m3_1 = (row.m3_1 !== undefined && row.m3_1 !== null && row.m3_1 !== '') ? row.m3_1 : '-';

                let m2_1 = (row.m2_1 !== undefined && row.m2_1 !== null && row.m2_1 !== '') ? row.m2_1 : '';
                if (!m2_1 && m1_1 !== '-' && a_1 !== '-') {
                    m2_1 = rnd(parseFloat(m1_1) + parseFloat(a_1), 4);
                }
                m2_1 = m2_1 || '-';

                let b_1 = (row.b_1 !== undefined && row.b_1 !== null && row.b_1 !== '') ? row.b_1 : '';
                if (!b_1 && m3_1 !== '-' && m1_1 !== '-') {
                    b_1 = rnd(parseFloat(m3_1) - parseFloat(m1_1), 4);
                }
                b_1 = b_1 || '-';

                const s = (row.simplo_adb !== null && row.simplo_adb !== undefined) ? rnd(row.simplo_adb, 2) : '-';

                // --- Duplo ---
                const dish2 = (row.dish_2 !== undefined && row.dish_2 !== null && row.dish_2 !== '') ? row.dish_2 : '-';
                const m1_2 = (row.m1_2 !== undefined && row.m1_2 !== null && row.m1_2 !== '') ? row.m1_2 : '-';
                const a_2 = (row.a_2 !== undefined && row.a_2 !== null && row.a_2 !== '') ? row.a_2 : '-';
                const m3_2 = (row.m3_2 !== undefined && row.m3_2 !== null && row.m3_2 !== '') ? row.m3_2 : '-';

                let m2_2 = (row.m2_2 !== undefined && row.m2_2 !== null && row.m2_2 !== '') ? row.m2_2 : '';
                if (!m2_2 && m1_2 !== '-' && a_2 !== '-') {
                    m2_2 = rnd(parseFloat(m1_2) + parseFloat(a_2), 4);
                }
                m2_2 = m2_2 || '-';

                let b_2 = (row.b_2 !== undefined && row.b_2 !== null && row.b_2 !== '') ? row.b_2 : '';
                if (!b_2 && m3_2 !== '-' && m1_2 !== '-') {
                    b_2 = rnd(parseFloat(m3_2) - parseFloat(m1_2), 4);
                }
                b_2 = b_2 || '-';

                const d = (row.duplo_adb !== null && row.duplo_adb !== undefined) ? rnd(row.duplo_adb, 2) : '-';

                // --- Selisih (Diff), Toleransi, Average ---
                const avg = (row.avg_adb !== null && row.avg_adb !== undefined) ? rnd(row.avg_adb, 2) : '-';
                let diff = '-';
                let tolHtml = '-';
                if (row.simplo_adb !== null && row.duplo_adb !== null && row.simplo_adb !== undefined && row.duplo_adb !== undefined) {
                    const diffNum = Math.abs(parseFloat(row.simplo_adb) - parseFloat(row.duplo_adb));
                    diff = rnd(diffNum, 2);
                    const avgNum = parseFloat(row.avg_adb !== null && row.avg_adb !== undefined ? row.avg_adb : ((parseFloat(row.simplo_adb) + parseFloat(row.duplo_adb)) / 2));
                    const limit = 0.09 + (0.1 * avgNum);
                    const isOk = diffNum < limit;
                    tolHtml = isOk 
                        ? '<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">YES</span>' 
                        : '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">NO</span>';
                }

                rowsHtml += `
                    <tr>
                        <td rowspan="2" class="fw-bold bg-light align-middle text-dark">Botol ${bNo}</td>
                        <td>${dish1}</td>
                        <td>${m1_1}</td>
                        <td>${m2_1}</td>
                        <td>${m3_1}</td>
                        <td>${a_1}</td>
                        <td>${b_1}</td>
                        <td class="fw-bold bg-warning bg-opacity-10 text-primary">${s}</td>
                        <td rowspan="2" class="align-middle fw-semibold">${diff}</td>
                        <td rowspan="2" class="align-middle">${tolHtml}</td>
                        <td rowspan="2" class="align-middle fw-bold text-primary bg-light">${avg}</td>
                    </tr>
                    <tr>
                        <td>${dish2}</td>
                        <td>${m1_2}</td>
                        <td>${m2_2}</td>
                        <td>${m3_2}</td>
                        <td>${a_2}</td>
                        <td>${b_2}</td>
                        <td class="fw-bold bg-warning bg-opacity-10 text-primary">${d}</td>
                    </tr>
                `;
            });
            document.getElementById('prev_im_table_body').innerHTML = rowsHtml;

            // Buka Modal
            const modalEl = document.getElementById('modalPreviewImBatch');
            const modalObj = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalObj.show();
        });
    }

    // Handler tombol "Gunakan Batch Ini" di dalam Modal
    const btnUseThisIm = document.getElementById('btn_use_this_im_batch');
    if (btnUseThisIm) {
        btnUseThisIm.addEventListener('click', function() {
            const modalEl = document.getElementById('modalPreviewImBatch');
            const modalObj = bootstrap.Modal.getInstance(modalEl);
            if (modalObj) modalObj.hide();

            // Otomatis terapkan batch ini
            const btnApply = document.getElementById('btn_apply_im');
            if (btnApply) btnApply.click();
        });
    }

});

</script>
@endsection