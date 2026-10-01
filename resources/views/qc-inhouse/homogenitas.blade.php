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
        background-color: rgba(27, 49, 82, 0.15) !important;
        color: #000000 !important;
        text-decoration: none !important;
    }

    .hom-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item.active {
        font-weight: 700 !important;
    }

    .hom-page .text-primary {
        color: #1b3152 !important;
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
        color: #1b3152 !important;
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
        color: #1b3152;
    }

    .hom-page .nav-tabs .nav-link.active {
        color: #ffffff;
        background-color: #1b3152;
        border-color: #1b3152;
    }

    .hom-page .alert-info {
        margin: 10px 12px !important;
        padding: 8px 12px;
        font-size: 0.75rem;
        line-height: 1.5;
        color: #000;
        background-color: rgba(27, 49, 82, 0.08);
        border: 0;
        border-left: 4px solid #1b3152 !important;
        border-radius: 6px !important;
    }

    .hom-page .alert-info i {
        color: #1b3152;
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
        background-color: #1b3152;
        border-color: #1b3152;
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
        color: #1b3152;
        border-color: #1b3152;
        background-color: transparent;
    }

    .hom-page .btn-outline-primary:hover,
    .hom-page .btn-outline-primary:focus,
    .hom-page .btn-outline-primary:active,
    .hom-page .btn-outline-dark:hover,
    .hom-page .btn-outline-dark:focus,
    .hom-page .btn-outline-dark:active {
        background-color: #1b3152;
        border-color: #1b3152;
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
        background-color: #1b3152;
        border-color: #1b3152;
    }

    .hom-page .form-check-input:focus,
    .hom-page .form-control:focus,
    .hom-page .form-select:focus {
        border-color: #1b3152;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
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
        --bs-table-bg: #1b3152;
        --bs-table-color: #ffffff;
        --bs-table-border-color: rgba(255, 255, 255, 0.25);
    }

    .hom-page .param-table thead th {
        background-color: #1b3152 !important;
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
        background-color: rgba(27, 49, 82, 0.07) !important;
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
        background-color: #1b3152 !important;
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
        color: #1b3152 !important;
        background-color: rgba(27, 49, 82, 0.1);
        box-shadow: inset 0 -3px 0 #1b3152;
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
        color: #1b3152 !important;
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
                        <div class="alert alert-info m-3 rounded-0 border-start border-4 border-info">
                            <i class="fas fa-info-circle me-2"></i> Ketergantungan Data: Tab <strong>IM</strong> wajib diisi lebih dulu karena parameter lain membutuhkan nilai IM untuk konversi ke basis Dry Basis (db). Tab <strong>CV</strong> juga membutuhkan nilai dari tab <strong>TS</strong>.
                        </div>

                        @php

                            $rowCount = max(3, $batch->parameters->max(function ($p) {
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

                                    <div class="px-3 pt-3">
                                        <button type="button" class="btn btn-outline-primary btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modalResource-{{ $pid }}">
                                            <i class="fas fa-users-cog me-1"></i> Pilih Personil & Alat ({{ $code }})
                                        </button>
                                        <div id="summary_{{ $pid }}" class="p-2 border rounded bg-light small d-none">
                                            <div class="fw-bold text-secondary mb-1">Informasi Pelaksanaan Pengujian:</div>
                                            <div class="summary-content text-dark"></div>
                                        </div>
                                    </div>

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

                                                    <div class="tab-content p-4">
                                                        <div class="d-flex justify-content-end mb-3">
                                                            <button type="button" class="btn btn-sm btn-info text-white btn-copy-resource" data-pid="{{ $pid }}">
                                                                <i class="fas fa-copy me-1"></i> Salin dari Parameter Sebelumnya
                                                            </button>
                                                        </div>

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
                                                                                <input class="form-check-input chk-personil" style="transform: scale(1.3);" type="checkbox" name="resource_{{ $pid }}[personil_ids][]" value="{{ $personil->personil_id }}">
                                                                            </td>
                                                                            <td class="align-middle">{{ $personil->nama }}</td>
                                                                            <td><input type="text" class="form-control form-control-sm in-peran" name="resource_{{ $pid }}[personil_peran][{{ $personil->personil_id }}]" value="Analis" placeholder="Analis"></td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <div class="tab-pane fade" id="tab-alat-{{ $pid }}" role="tabpanel">
                                                            <div class="row">
                                                                @foreach($alatList as $alat)
                                                                <div class="col-md-6 mb-3">
                                                                    <div class="form-check border p-2 rounded bg-light">
                                                                        <input class="form-check-input chk-alat ms-1" style="transform: scale(1.3);" type="checkbox" name="resource_{{ $pid }}[alat_ids][]" value="{{ $alat->alat_id }}">
                                                                        <label class="form-check-label ms-2 cursor-pointer w-100">
                                                                            <strong>{{ $alat->nama_alat }}</strong> <br><small class="text-muted">({{ $alat->kode_alat }})</small>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>

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
                                                                                <input class="form-check-input chk-bahan" style="transform: scale(1.3);" type="checkbox" name="resource_{{ $pid }}[barang_ids][]" value="{{ $barang->barang_id }}" data-nama="{{ $barang->nama_barang }}">
                                                                            </td>
                                                                            <td class="align-middle">
                                                                                <strong>{{ $barang->nama_barang }}</strong>
                                                                                @if($habis) <span class="badge bg-danger ms-1">Habis</span> @endif
                                                                            </td>
                                                                            <td class="align-middle {{ $habis ? 'text-danger fw-bold' : '' }}">{{ $saldoAkhir }} {{ $barang->satuan }}</td>
                                                                            <td>
                                                                                <div class="input-group input-group-sm">
                                                                                    <input type="number" step="0.01" class="form-control in-qty" name="resource_{{ $pid }}[barang_jumlah][{{ $barang->barang_id }}]" placeholder="0">
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
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-primary px-4 fw-bold" data-bs-dismiss="modal"><i class="fas fa-check me-1"></i> Simpan Pilihan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive p-3">
                                        <table class="table table-bordered table-sm align-middle text-center param-table text-nowrap" id="table-{{ $pid }}" data-pid="{{ $pid }}" data-code="{{ $code }}">
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
                                                        <th>Ee</th>
                                                        <th>t</th>
                                                        <th>Volume of Titrant (ml)</th>
                                                        <th>Length of Fuse (cm)</th>
                                                        <th>Total TS</th>
                                                        <th class="bg-warning bg-opacity-25">Final Result (cal/g) adb</th>
                                                        <th>Average Result (cal/g), adb</th>
                                                        <th>Average Result (cal/g), db</th>
                                                        <th>%db</th>
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
                                                        <td><input type="text" class="form-control form-control-sm text-center fw-bold bg-light" name="data_{{ $pid }}[{{ $i-1 }}][mentah][dish_1]" value="{{ $mentah['dish_1'] ?? '' }}" ></td>

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
                                                        @else
                                                            <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d1]" value="{{ $dh->nilai_d1 ?? '' }}"></td>
                                                            <td rowspan="2" class="align-middle out-diff">-</td>
                                                            <td rowspan="2" class="align-middle out-avg-adb">-</td>
                                                        @endif
                                                    </tr>

                                                    <tr class="row-duplo">
                                                        <td><input type="text" class="form-control form-control-sm text-center fw-bold bg-light" name="data_{{ $pid }}[{{ $i-1 }}][mentah][dish_2]" value="{{ $mentah['dish_2'] ?? '' }}" ></td>

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
                                                        @else
                                                            <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d2]" value="{{ $dh->nilai_d2 ?? '' }}"></td>
                                                        @endif
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
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

                <div class="d-flex justify-content-end mb-3">
                    @if(in_array($batch->status, ['uji_homogenitas', 'gagal_homogenitas']))
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm" id="btnSubmit">
                        <i class="fas fa-check-double me-2"></i> Kunci Semua & Lanjut ke Penetapan Target
                    </button>
                    @endif
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
                                    SD<sub><small>sampel in-house</small></sub> = &radic;<span style="border-top: 1px solid black; padding-top: 2px;">&nbsp;&nbsp;<frac>(MSB - MSW)<br><span style="border-top: 1px solid black; display:block">2</span></frac>&nbsp;&nbsp;</span>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

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
                if(e.target.value && !e.target.readOnly) e.target.value = rnd(e.target.value, 2);
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
                val1 = parseFloat(rnd(val1, code === 'CV' ? 0 : 2));
                val2 = parseFloat(rnd(val2, code === 'CV' ? 0 : 2));

                State[code].data[i].simplo_adb = val1;
                State[code].data[i].duplo_adb = val2;

                let avg_adb = (val1 + val2) / 2.0;
                avg_adb = parseFloat(rnd(avg_adb, code === 'CV' ? 0 : 2));
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
                        isOk = diff < 0.05;
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
        const isImReady = State['IM'] ? State['IM'].ready : true;
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

        let sdVal = Math.sqrt(Math.max(0, (a.MSB - a.MSW) / 2));
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
            processTable(newCode, document.querySelector(`.param-table[data-code="${newCode}"]`));
        });
    });

    document.querySelectorAll('.btn-save-sheet').forEach(btn => {
        btn.addEventListener('click', function() {
            const pid = this.dataset.pid;
            const code = this.dataset.code;
            const table = document.getElementById('table-' + pid);

            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
            this.disabled = true;

            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('is_draft', '1');

            table.querySelectorAll('input').forEach(inp => {
                if (inp.name) {
                    formData.append(inp.name, inp.value);
                }
            });

            fetch(document.getElementById('formHomogenitas').action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
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
            .catch(err => {
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
                    tableClone.querySelectorAll('input').forEach(inp => {
                        const text = document.createTextNode(inp.value);
                        inp.parentNode.replaceChild(text, inp);
                    });
                    ghost.appendChild(tableClone);
                }
            }

            if(part === 'both' || part === 'anova') {
                modalParamSelect.value = code;
                renderModalAnova();

                const anovaTable = document.getElementById('modalTableCombined').closest('table');
                if(anovaTable) {
                    const anovaClone = anovaTable.cloneNode(true);
                    ghost.appendChild(anovaClone);
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

    function exportToPdf(params, part) {
        const html = buildExportHtml(params, part);

        const doc = new DOMParser().parseFromString(html, 'text/html');
        const css = doc.querySelector('style') ? doc.querySelector('style').textContent : '';
        const sections = Array.from(doc.querySelectorAll('.pdf-section'));

        if (sections.length === 0) {
            return Promise.reject(new Error('Tidak ada data untuk dicetak.'));
        }

        const pages = sections.map(sec => {
            sec.classList.remove('pdf-break');
            sec.style.pageBreakBefore = 'auto';
            sec.style.breakBefore = 'auto';

            const wrap = document.createElement('div');
            wrap.style.cssText = 'overflow: visible; padding-bottom: 10px;';
            const style = document.createElement('style');
            style.textContent = css;
            wrap.appendChild(style);
            wrap.appendChild(sec);
            return wrap;
        });

        const opt = {
            margin:       [0.4, 0.4, 0.4, 0.4],
            filename:     'Hasil_Uji_Homogenitas.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true, letterRendering: true, scrollY: 0 },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' },
            pagebreak:    { mode: ['css', 'legacy'] }
        };

        let worker = html2pdf().set(opt).from(pages[0]).toPdf();

        for (let i = 1; i < pages.length; i++) {
            worker = worker
                .get('pdf')
                .then(pdf => { pdf.addPage(); })
                .from(pages[i])
                .toContainer()
                .toCanvas()
                .toPdf();
        }

        return worker.save();
    }

    function exportToWord(params, part) {
        const htmlContent = buildExportHtml(params, part);
        const blob = new Blob(['\ufeff', htmlContent], {
            type: 'application/msword'
        });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'Hasil_Uji_Homogenitas.doc';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
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
                    alert('Berhasil menyalin data dari parameter sebelumnya!');
                    updateResourceSummary(currentPid);
                }
            } else {
                alert('Ini adalah parameter pertama, tidak ada data sebelumnya yang bisa disalin.');
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

});

</script>
@endsection