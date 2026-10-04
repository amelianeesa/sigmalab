@extends('layouts.app')
@section('title', 'Tambah Baru - QC Harian')

@section('content')
<style>
    .dashboard-container {
        padding: 0 20px !important;
        margin-top: -8px !important;
        padding-bottom: 1.5rem !important;
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

    .card-header-sm {
        padding: 8px 12px;
        background-color: #ffffff;
        border-bottom: 1px solid #e3e8ef;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .page-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #000000;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 0;
        color: #000000;
    }

    .sub-title {
        margin-bottom: 8px;
        padding-bottom: 6px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #1b3152;
        border-bottom: 1px solid #e3e8ef;
    }

    .icon-corporate,
    .dashboard-container .text-primary,
    .modal .text-primary,
    .dashboard-container .text-warning:not(input),
    .modal .text-warning:not(input),
    .modal .text-success {
        color: #1b3152 !important;
    }

    /* ===== Buttons (seragam) ===== */
    .dashboard-container .btn,
    .modal .btn {
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

    .btn-corporate-blue {
        background-color: #1b3152 !important;
        border: 1px solid #1b3152 !important;
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
        background-color: #ffffff !important;
        border: 1px solid #1b3152 !important;
        color: #1b3152 !important;
    }

    .btn-outline-corporate:hover,
    .btn-outline-corporate:focus,
    .btn-outline-corporate:active {
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
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

    /* ===== Form ===== */
    .dashboard-container .form-label {
        margin-bottom: 3px;
        font-size: 0.74rem;
        font-weight: 600;
        color: #000000;
    }

    .dashboard-container .form-control,
    .modal .form-control {
        font-size: 0.78rem;
        padding-top: 0.28rem;
        padding-bottom: 0.28rem;
        color: #000000;
        border-color: #ced4da;
    }

    .dashboard-container .form-control:focus,
    .modal .form-control:focus {
        border-color: #1b3152;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
    }

    .form-check-input:checked {
        background-color: #1b3152;
        border-color: #1b3152;
    }

    .form-check-input:focus {
        border-color: #1b3152;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
    }

    .modal .input-group-text {
        font-size: 0.75rem;
        font-weight: 600;
        color: #1b3152;
        background-color: #f1f4f8;
    }

    .dashboard-container .alert-info {
        margin: 10px 12px !important;
        padding: 8px 12px !important;
        font-size: 0.75rem;
        line-height: 1.5;
        color: #000000;
        background-color: rgba(27, 49, 82, 0.08);
        border: 0;
        border-left: 4px solid #1b3152 !important;
        border-radius: 6px !important;
    }

    .dashboard-container .alert-info i {
        color: #1b3152;
    }

    input:invalid { box-shadow: none; }

    /* ===== Accordion ===== */
    .accordion-flush > .accordion-item {
        border-bottom: 1px solid #dee2e6;
    }

    .accordion-header .accordion-button {
        padding: 0.55rem 0.75rem;
        font-size: 0.8rem;
        color: #000000;
        background-color: transparent;
        box-shadow: none;
    }

    .accordion-header .accordion-button:not(.collapsed) {
        color: #1b3152;
        background-color: rgba(27, 49, 82, 0.08);
    }

    .accordion-header .accordion-button:focus {
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
    }

    .accordion-header .accordion-button:hover {
        background-color: rgba(27, 49, 82, 0.06);
    }

    .accordion-header .accordion-button .badge {
        font-size: 0.64rem;
        white-space: normal;
        text-align: left;
    }

    .param-head-check {
        padding: 0.55rem 0.75rem;
    }

    .accordion-body {
        padding: 10px !important;
    }

    /* ===== Ringkasan resource ===== */
    .res-summary {
        font-size: 0.76rem;
        line-height: 1.5;
    }

    .res-summary > div {
        word-break: break-word;
    }

    /* ===== Tabel input ===== */
    .param-table {
        width: 100%;
        min-width: 420px;
        margin-bottom: 0;
        font-size: 0.75rem;
        border-color: #cfd6df;
    }

    .param-table[data-code="IM"] { min-width: 760px; }
    .param-table[data-code="ASH"] { min-width: 920px; }
    .param-table[data-code="VM"] { min-width: 1100px; }
    .param-table[data-code="TS"] { min-width: 560px; }
    .param-table[data-code="CV"] { min-width: 1240px; }

    .param-table thead th {
        padding: 0.4rem 0.3rem;
        font-size: 0.7rem;
        font-weight: 600;
        line-height: 1.25;
        color: #ffffff !important;
        background-color: #1b3152 !important;
        border-color: rgba(255, 255, 255, 0.25) !important;
        vertical-align: middle;
        white-space: normal;
    }

    .param-table thead th.bg-warning {
        background-color: #3a5a8c !important;
    }

    .param-table thead th i {
        color: #ffffff !important;
    }

    .param-table td {
        padding: 0.15rem 0.2rem;
        font-size: 0.75rem;
        vertical-align: middle;
        border-color: #dfe4ea;
    }

    .param-table td.bg-warning {
        background-color: rgba(27, 49, 82, 0.07) !important;
    }

    .param-table td.bg-light {
        font-size: 0.74rem;
        white-space: nowrap;
    }

    .param-table input.form-control-sm {
        width: 100%;
        min-width: 0;
        height: 28px;
        padding: 0.2rem 0.25rem;
        font-size: 0.75rem;
        text-align: center;
        border-radius: 0.2rem;
    }

    .param-table input:disabled {
        background-color: #f1f3f5;
    }

    .param-table .in-im-1,
    .param-table .in-im-2 {
        color: #1b3152 !important;
    }

    .param-table .badge {
        font-size: 0.66rem;
        font-weight: 600;
        padding: 0.3em 0.6em;
        border-radius: 4px;
    }

    /* ===== Modal ===== */
    .modal-content {
        border: 0;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.18);
        font-size: 0.8rem;
        overflow: hidden;
    }

    .modal-header.modal-corporate {
        padding: 0.55rem 0.9rem;
        color: #ffffff;
        background-color: #1b3152;
    }

    .modal-header.modal-corporate .modal-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: #ffffff;
    }

    .modal-footer {
        padding: 0.5rem 0.75rem;
    }

    .modal-resource .nav-tabs .nav-link {
        padding: 0.55rem 0.5rem;
        font-size: 0.78rem;
        color: #1b3152 !important;
        border-radius: 0;
    }

    .modal-resource .nav-tabs .nav-link.active {
        background-color: rgba(27, 49, 82, 0.12);
        box-shadow: inset 0 -3px 0 #1b3152;
    }

    .modal-resource .table thead th {
        font-size: 0.72rem;
        color: #ffffff !important;
        background-color: #1b3152 !important;
        border-color: #ffffff !important;
    }

    .modal-resource .table td {
        font-size: 0.75rem;
        vertical-align: middle;
    }

    .modal-resource .table-hover > tbody > tr:hover > * {
        --bs-table-hover-bg: rgba(27, 49, 82, 0.06);
    }

    .modal-resource .modal-body > .tab-content {
        padding: 0.75rem !important;
    }

    .scroll-hint {
        display: none;
        margin-bottom: 4px;
        font-size: 0.68rem;
        color: #6c757d;
    }

    /* ===== Tablet ===== */
    @media (max-width: 991.98px) {
        .param-table thead th:first-child,
        .param-table tbody tr td:first-child {
            position: sticky;
            left: 0;
            z-index: 2;
        }

        .param-table tbody tr td:first-child {
            background-color: #f8f9fa !important;
        }
    }

    /* ===== Mobile ===== */
    @media (max-width: 767.98px) {
        .dashboard-container {
            padding: 0 10px !important;
            padding-bottom: 1.25rem !important;
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

        .page-title {
            font-size: 0.95rem;
        }

        .dashboard-container .alert-info {
            margin: 8px !important;
        }

        .dashboard-container .form-control:not(.param-table .form-control),
        .modal .form-control:not(.in-peran):not(.in-qty) {
            font-size: 16px;
            min-height: 40px;
        }

        .accordion-body {
            padding: 8px !important;
        }

        .scroll-hint {
            display: block;
        }

        .form-actions .btn {
            width: 100%;
            min-height: 40px;
            font-size: 0.85rem;
        }

        .modal-resource .nav-tabs .nav-link {
            font-size: 0.72rem;
        }

        .modal-footer .btn {
            width: 100%;
            min-height: 40px;
        }
    }
</style>

<div class="container-fluid dashboard-container">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item"><a href="{{ route('qc-harian.index') }}" class="text-decoration-none">Pengujian Harian QC I</a></li>
        <li class="breadcrumb-item active">Input Data</li>
    </x-qc-breadcrumb>

    <h5 class="page-title">
        <i class="fas fa-plus-circle icon-corporate me-2"></i>Input Data Harian QC
    </h5>

    <form action="{{ route('qc-harian.store') }}" method="POST" id="formQc">
        @csrf
        <input type="hidden" name="is_draft" id="is_draft" value="0">
        <input type="hidden" name="old_draft_group_id" value="{{ isset($serverDraft) ? $serverDraft['draft_group_id'] : '' }}">

        <div class="card shadow-sm border-0 mb-2">
            <div class="card-header-sm">
                <h6 class="section-title"><i class="fas fa-clipboard-list icon-corporate me-2"></i>Section 1: Data Dasar Pengujian</h6>
            </div>
            <div class="card-body">

                <h6 class="sub-title"><i class="fas fa-info-circle me-2"></i>Informasi Umum</h6>
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label">Kode Batch Aktif</label>
                        <input type="text" class="form-control bg-light" value="{{ $activeBatch->kode_batch }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nama Sampel <span class="text-danger">*</span></label>
                        <input type="text" name="nama_sampel_uji" class="form-control" value="{{ $activeBatch->nama_sampel }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Uji <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_uji" class="form-control" value="{{ date('Y-m-d') }}" required max="{{ date('Y-m-d') }}">
                    </div>
                </div>

            </div>
        </div>

        <div class="card shadow-sm border-0 mb-2">
            <div class="card-header-sm">
                <h6 class="section-title"><i class="fas fa-table icon-corporate me-2"></i>Section 2: Input Data Pengujian</h6>
            </div>
            <div class="card-body p-0">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Centang kotak di samping nama parameter untuk mengaktifkan tabel inputnya.
                </div>

                <div class="accordion accordion-flush" id="parameterAccordion">
                    @foreach($parameters as $param)
                    @php
                        $code = strtoupper($param->parameterUji->nama_parameter);
                        $pid = $param->parameter_uji_id;
                        $isLocked = \App\Models\QcHarian::where('sampel_inhouse_id', $activeBatch->id)
                            ->where('parameter_uji_id', $pid)
                            ->where('status_evaluasi', 'outlier')
                            ->where('status_investigasi', 'menunggu_investigasi')
                            ->exists();
                    @endphp
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-{{ $pid }}">
                            <div class="d-flex align-items-center w-100 bg-light border-bottom">
                                <div class="param-head-check">
                                    @if($isLocked)
                                        <i class="fas fa-lock text-danger" title="Menunggu investigasi"></i>
                                    @else
                                        <input class="form-check-input param-enable-check" type="checkbox" name="params[{{ $pid }}][selected]" value="1" data-pid="{{ $pid }}" style="transform: scale(1.3);">
                                    @endif
                                </div>
                                <button class="accordion-button collapsed {{ $isLocked ? 'text-danger' : 'fw-bold' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $pid }}" aria-expanded="false" aria-controls="collapse-{{ $pid }}">
                                    {{ $code }} - {{ $param->parameterUji->satuan }}
                                    @if($isLocked)
                                        <span class="badge bg-danger ms-2">OUTLIER - MENUNGGU INVESTIGASI (LOCKED)</span>
                                    @endif
                                </button>
                            </div>
                        </h2>
                        <div id="collapse-{{ $pid }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $pid }}" data-bs-parent="#parameterAccordion">
                            <div class="accordion-body">

                                <div class="row align-items-center bg-light p-2 rounded mb-2 border mx-0 g-2">
                                    <div class="col-md-8">
                                        <div class="d-flex flex-column gap-1 res-summary">
                                            <div><i class="fas fa-users icon-corporate me-2"></i><strong>Personil:</strong> <span id="sum-personil-{{ $pid }}" class="text-muted fst-italic">Belum diatur</span></div>
                                            <div><i class="fas fa-tools icon-corporate me-2"></i><strong>Alat:</strong> <span id="sum-alat-{{ $pid }}" class="text-muted fst-italic">Belum diatur</span></div>
                                            <div><i class="fas fa-flask icon-corporate me-2"></i><strong>Bahan:</strong> <span id="sum-bahan-{{ $pid }}" class="text-muted fst-italic">Belum diatur</span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex flex-column gap-1">
                                        <button type="button" class="btn btn-sm btn-corporate-blue w-100 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalResource-{{ $pid }}">
                                            <i class="fas fa-cog me-1"></i> Atur Alat & Bahan
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-corporate w-100 shadow-sm btn-copy-resource" data-pid="{{ $pid }}">
                                            <i class="fas fa-copy me-1"></i> Salin dari Atas
                                        </button>
                                    </div>
                                </div>

                                <div class="modal fade modal-resource" id="modalResource-{{ $pid }}" tabindex="-1" aria-labelledby="label-{{ $pid }}" aria-hidden="true" data-bs-backdrop="static">
                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-md-down">
                                        <div class="modal-content">
                                            <div class="modal-header modal-corporate">
                                                <h5 class="modal-title" id="label-{{ $pid }}"><i class="fas fa-tasks me-2"></i>Atur Resource Parameter: {{ $code }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-0">

                                                <ul class="nav nav-tabs nav-fill bg-light m-0" id="tab-{{ $pid }}" role="tablist">
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link active fw-bold" id="personil-tab-{{ $pid }}" data-bs-toggle="tab" data-bs-target="#tab-personil-{{ $pid }}" type="button" role="tab"><i class="fas fa-users icon-corporate me-1"></i>Personil</button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link fw-bold" id="alat-tab-{{ $pid }}" data-bs-toggle="tab" data-bs-target="#tab-alat-{{ $pid }}" type="button" role="tab"><i class="fas fa-tools icon-corporate me-1"></i>Alat</button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link fw-bold" id="bahan-tab-{{ $pid }}" data-bs-toggle="tab" data-bs-target="#tab-bahan-{{ $pid }}" type="button" role="tab"><i class="fas fa-flask icon-corporate me-1"></i>Bahan / Reagen</button>
                                                    </li>
                                                </ul>

                                                <div class="tab-content p-3" id="tabContent-{{ $pid }}">

                                                    <div class="tab-pane fade show active" id="tab-personil-{{ $pid }}" role="tabpanel">
                                                        <div class="table-responsive border rounded">
                                                            <table class="table table-hover table-sm mb-0">
                                                                <thead>
                                                                    <tr><th width="5%" class="text-center">Pilih</th><th>Nama Personil</th><th>Peran / Tugas</th></tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($personilList as $personil)
                                                                    <tr>
                                                                        <td class="text-center align-middle">
                                                                            
                                                                            <input class="form-check-input chk-personil" style="transform: scale(1.2);" type="checkbox" name="params[{{ $pid }}][mentah][personil_ids][]" value="{{ $personil->personil_id }}" id="pers_{{ $pid }}_{{ $personil->personil_id }}" data-nama="{{ $personil->nama }}">
                                                                        </td>
                                                                        <td class="align-middle"><label for="pers_{{ $pid }}_{{ $personil->personil_id }}" class="mb-0 cursor-pointer d-block">{{ $personil->nama }}</label></td>
                                                                        <td><input type="text" class="form-control form-control-sm in-peran" name="params[{{ $pid }}][mentah][personil_peran][{{ $personil->personil_id }}]" value="Analis" placeholder="Analis"></td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade" id="tab-alat-{{ $pid }}" role="tabpanel">
                                                        <div class="row g-2">
                                                            @foreach($alatList as $alat)
                                                            <div class="col-12 col-md-6">
                                                                <div class="form-check border p-2 rounded bg-light">
                                                                    <input class="form-check-input chk-alat ms-1" style="transform: scale(1.2);" type="checkbox" name="params[{{ $pid }}][mentah][alat_ids][]" value="{{ $alat->alat_id }}" id="alat_{{ $pid }}_{{ $alat->alat_id }}" data-nama="{{ $alat->nama_alat }}">
                                                                    <label class="form-check-label ms-2 cursor-pointer w-100" style="font-size: 0.75rem;" for="alat_{{ $pid }}_{{ $alat->alat_id }}">
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
                                                                <thead style="position: sticky; top: 0; z-index: 1;">
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
                                                                            <input class="form-check-input chk-bahan" style="transform: scale(1.2);" type="checkbox" name="params[{{ $pid }}][mentah][barang_ids][]" value="{{ $barang->barang_id }}" id="bh_{{ $pid }}_{{ $barang->barang_id }}"  data-nama="{{ $barang->nama_barang }}">
                                                                        </td>
                                                                        <td class="align-middle">
                                                                            <label for="bh_{{ $pid }}_{{ $barang->barang_id }}" class="mb-0 cursor-pointer d-block {{ $habis ? 'text-muted' : '' }}">
                                                                                <strong>{{ $barang->nama_barang }}</strong> 
                                                                                @if($habis) <span class="badge bg-danger ms-1" style="font-size: 0.65rem;">Habis</span> @endif
                                                                            </label>
                                                                        </td>
                                                                        <td class="align-middle text-muted">{{ number_format($saldoAkhir, 0, ',', '.') }} {{ $barang->satuan }}</td>
                                                                        <td>
                                                                            <div class="input-group input-group-sm">
                                                                                
                                                                                <input type="number" step="0.01" min="0" class="form-control in-qty" name="params[{{ $pid }}][mentah][barang_jumlah][{{ $barang->barang_id }}]" placeholder="0" >
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
                                            <div class="modal-footer bg-light border-top-0">
                                                
                                                <button type="button" class="btn btn-sm btn-corporate-blue px-4 btn-save-modal" data-pid="{{ $pid }}" data-bs-dismiss="modal">
                                                    <i class="fas fa-check me-2"></i>Simpan Resource
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="scroll-hint"><i class="fas fa-arrows-alt-h me-1"></i> Geser tabel ke kiri/kanan untuk melihat seluruh kolom.</div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle text-center param-table" id="table-{{ $pid }}" data-pid="{{ $pid }}" data-code="{{ $code }}">
                                        <thead class="table-light">
                                            @if($code === 'IM')
                                                <tr>
                                                    <th>PENGULANGAN</th>
                                                    <th>M1</th>
                                                    <th>M2</th>
                                                    <th>M3</th>
                                                    <th>A</th>
                                                    <th>B</th>
                                                    <th class="bg-warning bg-opacity-25">M%</th>
                                                    <th colspan="2">
                                                        ABSOLUTE DIFFERENCE 
                                                        <i class="fas fa-info-circle ms-1 text-primary" data-bs-toggle="tooltip" title="0.09 + (0.1 * AVG)"></i>
                                                    </th>
                                                    <th>AVERAGE %</th>
                                                </tr>
                                            @elseif($code === 'ASH')
                                                <tr>
                                                    <th>PENGULANGAN</th>
                                                    <th>M1</th>
                                                    <th>M2</th>
                                                    <th>M2-M1</th>
                                                    <th>M3</th>
                                                    <th>M3-M1</th>
                                                    <th class="bg-warning bg-opacity-25">ASH%</th>
                                                    <th colspan="2">
                                                        ABSOLUTE DIFFERENCE 
                                                        <i class="fas fa-info-circle ms-1 text-primary" data-bs-toggle="tooltip" title="0.09 + (0.1 * AVG)"></i>
                                                    </th>
                                                    <th>AVERAGE %adb</th>
                                                    <th>%db</th>
                                                    <th>db</th>
                                                </tr>
                                            @elseif($code === 'VM')
                                                <tr>
                                                    <th>PENGULANGAN</th>
                                                    <th>M1</th>
                                                    <th>M2</th>
                                                    <th>M2-M1</th>
                                                    <th>M3</th>
                                                    <th>M2-M3</th>
                                                    <th>LOSS%</th>
                                                    <th>IM</th>
                                                    <th class="bg-warning bg-opacity-25">VM%</th>
                                                    <th colspan="2">
                                                        ABSOLUTE DIFFERENCE 
                                                        <i class="fas fa-info-circle ms-1 text-primary" data-bs-toggle="tooltip" title="0.09 + (0.1 * AVG)"></i>
                                                    </th>
                                                    <th>AVERAGE %adb</th>
                                                    <th>AVERAGE %db</th>
                                                    <th>%db</th>
                                                </tr>
                                            @elseif($code === 'TS')
                                                <tr>
                                                    <th>PENGULANGAN</th>
                                                    <th style="width: 16%">Massa sample</th>
                                                    <th class="bg-warning bg-opacity-25" style="width: 16%">TS (Adb)</th>
                                                    <th style="width: 16%">Average %(adb)</th>
                                                    <th style="width: 16%">Average % (Db)</th>
                                                    <th style="width: 16%">%db</th>
                                                </tr>
                                            @elseif($code === 'CV')
                                                <tr>
                                                    <th>PENGULANGAN</th>
                                                    <th>Weight of Crucible</th>
                                                    <th>Sample Mass</th>
                                                    <th>Primary Result (cal/g)</th>
                                                    <th>Ee</th>
                                                    <th>t</th>
                                                    <th>Volume of Titrant (ml)</th>
                                                    <th>Length of Fuse (cm)</th>
                                                    <th>Total TS</th>
                                                    <th class="bg-warning bg-opacity-25" style="width: 16%">
                                                        Final Result (cal/g) adb 
                                                        <i class="fas fa-info-circle ms-1 text-primary" data-bs-toggle="tooltip" title="(PR - (14.3 * 0.0699 * VT) - (2.3 * LF) - (13.2 * TS * SM)) / SM"></i>
                                                    </th>
                                                    <th>Average Result (cal/g), adb</th>
                                                    <th>Average Result (cal/g), db</th>
                                                    <th>%db</th>
                                                </tr>
                                            @else
                                                <tr>
                                                    <th>PENGULANGAN</th>
                                                    <th class="bg-warning bg-opacity-25">Hasil Uji (adb)</th>
                                                    <th>ABSOLUTE DIFFERENCE</th>
                                                    <th>AVERAGE % (adb)</th>
                                                </tr>
                                            @endif
                                        </thead>
                                        <tbody>
                                            
                                            <tr class="row-entry">
                                                <td class="fw-bold bg-light">
                                                    Simplo (D1)
                                                    <input type="hidden" class="in-d1" name="params[{{ $pid }}][d1]">
                                                    <input type="hidden" class="in-db-1" name="params[{{ $pid }}][db1]">
                                                </td>
                                                
                                                @if($code === 'IM')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-1" name="params[{{ $pid }}][mentah][m1_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-1" name="params[{{ $pid }}][mentah][m3_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-a-1" name="params[{{ $pid }}][mentah][a_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-b-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                                                    <td rowspan="2" class="align-middle out-diff">-</td>
                                                    <td rowspan="2" class="align-middle out-tol fw-bold">-</td>
                                                    <td rowspan="2" class="align-middle out-avg-adb">-</td>
                                                @elseif($code === 'ASH')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-1" name="params[{{ $pid }}][mentah][m1_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-1" name="params[{{ $pid }}][mentah][m2m1_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-1" name="params[{{ $pid }}][mentah][m3_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m3m1-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                                                    <td rowspan="2" class="align-middle out-diff">-</td>
                                                    <td rowspan="2" class="align-middle out-tol fw-bold">-</td>
                                                    <td rowspan="2" class="align-middle out-avg-adb">-</td>
                                                    <td rowspan="2" class="align-middle out-avg-db">-</td>
                                                    <td class="align-middle out-db-1 fw-bold text-success">-</td>
                                                @elseif($code === 'VM')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-1" name="params[{{ $pid }}][mentah][m1_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-1" name="params[{{ $pid }}][mentah][m2m1_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-1" name="params[{{ $pid }}][mentah][m3_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2m3-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" class="form-control form-control-sm in-loss-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td class="bg-warning bg-opacity-10">
                                                        <input type="text" inputmode="decimal" class="form-control form-control-sm in-im-1 fw-bold bg-transparent border-0 text-center text-warning" name="params[{{ $pid }}][mentah][im_d1]" placeholder="IM D1" disabled>
                                                    </td>
                                                    <td class="bg-warning bg-opacity-25"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                                                    <td rowspan="2" class="align-middle out-diff">-</td>
                                                    <td rowspan="2" class="align-middle out-tol fw-bold">-</td>
                                                    <td rowspan="2" class="align-middle out-avg-adb">-</td>
                                                    <td rowspan="2" class="align-middle out-avg-db">-</td>
                                                    <td class="align-middle out-db-1 fw-bold text-success">-</td>
                                                @elseif($code === 'TS')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-mass-1" name="params[{{ $pid }}][mentah][mass_1]" disabled></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" disabled></td>
                                                    <td rowspan="2" class="align-middle out-avg-adb fw-bold">-</td>
                                                    <td rowspan="2" class="align-middle out-avg-db fw-bold">-</td>
                                                    <td class="align-middle out-db-1 fw-bold text-success">-</td>
                                                @elseif($code === 'CV')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-w-1" name="params[{{ $pid }}][mentah][w_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-sm-1" name="params[{{ $pid }}][mentah][sm_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-pr-1" name="params[{{ $pid }}][mentah][pr_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-ee-1" name="params[{{ $pid }}][mentah][ee_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-t-1" name="params[{{ $pid }}][mentah][t_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-vt-1" name="params[{{ $pid }}][mentah][vt_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-lf-1" name="params[{{ $pid }}][mentah][lf_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-ts-1 bg-light border-0" name="params[{{ $pid }}][mentah][ts_1]" readonly tabindex="-1" placeholder="Auto dari TS"></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                                                    <td rowspan="2" class="align-middle out-avg-adb fw-bold">-</td>
                                                    <td rowspan="2" class="align-middle out-avg-db fw-bold">-</td>
                                                    <td class="align-middle out-db-1 fw-bold text-success">-</td>
                                                @else
                                                    <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" disabled></td>
                                                    <td rowspan="2" class="align-middle out-diff">-</td>
                                                    <td rowspan="2" class="align-middle out-avg-adb">-</td>
                                                @endif
                                            </tr>

                                            <tr class="row-entry">
                                                <td class="fw-bold bg-light">
                                                    Duplo (D2)
                                                    <input type="hidden" class="in-d2" name="params[{{ $pid }}][d2]">
                                                    <input type="hidden" class="in-db-2" name="params[{{ $pid }}][db2]">
                                                </td>
                                                
                                                @if($code === 'IM')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-2" name="params[{{ $pid }}][mentah][m1_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-2" name="params[{{ $pid }}][mentah][m3_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-a-2" name="params[{{ $pid }}][mentah][a_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-b-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                                                @elseif($code === 'ASH')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-2" name="params[{{ $pid }}][mentah][m1_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-2" name="params[{{ $pid }}][mentah][m2m1_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-2" name="params[{{ $pid }}][mentah][m3_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m3m1-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                                                    <td class="align-middle out-db-2 fw-bold text-success">-</td>
                                                @elseif($code === 'VM')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-2" name="params[{{ $pid }}][mentah][m1_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-2" name="params[{{ $pid }}][mentah][m2m1_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-2" name="params[{{ $pid }}][mentah][m3_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2m3-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" class="form-control form-control-sm in-loss-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td class="bg-warning bg-opacity-10">
                                                        <input type="text" inputmode="decimal" class="form-control form-control-sm in-im-2 fw-bold bg-transparent border-0 text-center text-warning" name="params[{{ $pid }}][mentah][im_d2]" placeholder="IM D2" disabled>
                                                    </td>
                                                    <td class="bg-warning bg-opacity-25"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                                                    <td class="align-middle out-db-2 fw-bold text-success">-</td>
                                                @elseif($code === 'TS')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-mass-2" name="params[{{ $pid }}][mentah][mass_2]" disabled></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" disabled></td>
                                                    <td class="align-middle out-db-2 fw-bold text-success">-</td>
                                                @elseif($code === 'CV')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-w-2" name="params[{{ $pid }}][mentah][w_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-sm-2" name="params[{{ $pid }}][mentah][sm_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-pr-2" name="params[{{ $pid }}][mentah][pr_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-ee-2" name="params[{{ $pid }}][mentah][ee_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-t-2" name="params[{{ $pid }}][mentah][t_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-vt-2" name="params[{{ $pid }}][mentah][vt_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-lf-2" name="params[{{ $pid }}][mentah][lf_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-ts-2 bg-light border-0" name="params[{{ $pid }}][mentah][ts_2]" readonly tabindex="-1" placeholder="Auto dari TS"></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                                                    <td class="align-middle out-db-2 fw-bold text-success">-</td>
                                                @else
                                                    <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" disabled></td>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row align-items-center g-2 mt-2 mb-2 form-actions">
            
            <div class="col-md-6 order-2 order-md-1">
                <div class="d-grid d-md-block">
                    <a href="{{ route('qc-harian.index') }}" class="btn btn-kembali btn-sm px-4 shadow-sm">
                        Batal
                    </a>
                </div>
            </div>
            
            <div class="col-md-6 order-1 order-md-2 text-md-end">
                <div class="d-grid d-md-flex justify-content-md-end gap-2">
                    <button type="button" class="btn btn-outline-corporate btn-sm px-4 shadow-sm" id="btnDraft">
                        <i class="fas fa-save me-2"></i>Simpan Draft
                    </button>
                    <button type="submit" class="btn btn-corporate-blue btn-sm px-4 shadow-sm" id="btnSubmit">
                        <i class="fas fa-check-circle me-2"></i>Simpan & Evaluasi Semua
                    </button>
                </div>
            </div>
            
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.8.0/math.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    const paramConfigs = {
        @if(isset($allParameters))
            @foreach($allParameters as $param)
                "{{ $param->parameter_uji_id }}": {
                    rumus: {!! json_encode($param->rumus_kalkulasi ?? '') !!},
                    langkah: {!! json_encode($param->langkah_kalkulasi ?? []) !!},
                    toleransi: {!! json_encode($param->toleransi_duplo ?? '') !!}
                },
            @endforeach
        @elseif(isset($parameters))
            @foreach($parameters as $param)
                "{{ $param->parameter_uji_id }}": {
                    rumus: {!! json_encode($param->parameterUji->rumus_kalkulasi ?? '') !!},
                    langkah: {!! json_encode($param->parameterUji->langkah_kalkulasi ?? []) !!},
                    toleransi: {!! json_encode($param->parameterUji->toleransi_duplo ?? '') !!}
                },
            @endforeach
        @endif
    };

    function parseNum(val) {
        const n = parseFloat(val);
        return isNaN(n) ? 0 : n;
    }

        const selectCrm = document.getElementById('crm_katalog_id');
    if(selectCrm) {
        selectCrm.addEventListener('change', function() {
            const val = this.value;
            window.crmCertificates = {};
            if(!val) {
                document.querySelectorAll('.btn-evaluasi').forEach(b => b.classList.add('d-none'));
                return;
            }
            fetch('/api/crm-katalog/' + val + '/parameters')
                .then(r => r.json())
                .then(data => {
                    data.forEach(item => {
                        window.crmCertificates[item.parameter_uji_id] = { val: item.cert_value, u: item.cert_u };
                    });
                    document.querySelectorAll('.in-hasil-1').forEach(inp => inp.dispatchEvent(new Event('input')));
                });
        });
    }

    function calculateRow(row, pid, isSyncing = false) {
        if(!row) return;
        
        let config = paramConfigs[pid];
        if(!config) return;

        let i = row.querySelector('.in-hasil-1') ? 1 : 2;
        let scope = {};

        const inputs = row.querySelectorAll('input[class*="in-"]');
        inputs.forEach(inp => {
            const match = inp.className.match(/in-([a-zA-Z0-9_]+)-\d/);
            if(match) {
                const varName = match[1].toLowerCase();
                scope[varName] = parseNum(inp.value);
            }
        });

        if(row.closest('table').dataset.code === 'CV') {
            const tsTable = document.querySelector('table[data-code="TS"]');
            if(tsTable) {
                const tsInp = tsTable.querySelector(`.in-hasil-${i}`);
                if(tsInp && tsInp.value) {
                    scope['ts'] = parseNum(tsInp.value);
                    const tsRow = row.querySelector(`.in-ts-${i}`);
                    if(tsRow) tsRow.value = tsInp.value;
                }
            }
        }

        if(row.closest('table').dataset.code === 'VM') {
            const imTable = document.querySelector('table[data-code="IM"]');
            if(imTable) {
                const imInp = imTable.querySelector(`.in-hasil-${i}`);
                if(imInp && imInp.value) {
                    scope['im'] = parseNum(imInp.value);

                    const vmImInp = row.querySelector(`.in-im-${i}`);
                    if(vmImInp && !vmImInp.value) vmImInp.value = imInp.value;
                }
            }
        }

        if(config.langkah && config.langkah.length > 0) {
            config.langkah.forEach(step => {
                if(step.var && step.rumus) {
                    try {
                        let res = math.evaluate(step.rumus.toLowerCase().replace(/\|/g, ''), scope);
                        scope[step.var.toLowerCase()] = res;

                        let cleanKey = step.var.toLowerCase().replace(/[^a-z0-9_]/g, '');
                        if(cleanKey !== step.var.toLowerCase()) scope[cleanKey] = res;

                        let targetInp = null;
                        try { targetInp = row.querySelector(`.in-${step.var.toLowerCase()}-${i}`); } catch(ex) {}
                        if(!targetInp) targetInp = row.querySelector(`.in-${cleanKey}-${i}`);
                        if(targetInp) targetInp.value = res.toFixed(4);
                    } catch(e) { }
                }
            });
        }

        let result = 0;
        if(config.rumus) {
            try {
                result = math.evaluate(config.rumus.toLowerCase(), scope);
            } catch(e) {
                if(row.closest('table').dataset.code === 'TS') result = scope['mass'] || 0;
            }
        } else {
            const code = row.closest('table').dataset.code;
            if(code === 'IM') {
                if(scope.m2 > 0) result = ((scope.m2 - scope.m3) / scope.m2) * 100;
            } else if(code === 'ASH') {
                if(scope.m2m1 > 0) result = (scope.m3m1 / scope.m2m1) * 100;
            } else if(code === 'VM') {
                result = (scope.loss || 0) - (scope.im || 0);
            } else if(code === 'TS') {
                result = scope.mass || 0;
            }
        }

        const inHasil = row.querySelector(`.in-hasil-${i}`);
        const inD = row.querySelector(`.in-d${i}`); 
        if(inHasil && !inHasil.hasAttribute('disabled')) {
            const dec = (row.closest('table').dataset.code === 'CV') ? 0 : 2;
            inHasil.value = result.toFixed(dec);
        }
        if(inD) inD.value = result.toFixed(4);

        const tbody = row.closest('tbody');
        const d1 = parseNum(tbody.querySelector('.in-d1')?.value || tbody.querySelector('.in-hasil-1')?.value);
        const d2 = parseNum(tbody.querySelector('.in-d2')?.value || tbody.querySelector('.in-hasil-2')?.value);

        if(d1 > 0 && d2 > 0) {
            const diff = Math.abs(d1 - d2);
            const diffStr = diff.toFixed(2);
            const avg = (d1 + d2) / 2;
            const avgStr = avg.toFixed(2);

            const outDiff = tbody.querySelector('.out-diff');
            const outTol = tbody.querySelector('.out-tol');
            
            if(outDiff) outDiff.textContent = diffStr;

            if(outTol && config.toleransi) {
                let limit = 0;
                try {
                    limit = math.evaluate(config.toleransi, { AVG: avg, avg: avg });
                } catch(e) {
                    limit = parseFloat(config.toleransi) || 0;
                }
                const isYes = diff < limit;
                outTol.innerHTML = isYes ? `<span class="badge bg-success">YES</span>` : `<span class="badge bg-danger">NO</span>`;
            } else if(outTol) {
                const isYes = diff < (0.09 + (0.1 * avg));
                outTol.innerHTML = isYes ? `<span class="badge bg-success">YES</span>` : `<span class="badge bg-danger">NO</span>`;
            }
            
            const outAvg = tbody.querySelector('.out-avg-adb');
            if(outAvg) outAvg.textContent = avgStr;

            const code = tbody.closest('table').dataset.code;
            if (code === 'ASH' || code === 'VM' || code === 'TS' || code === 'CV') {
                const outDb1 = tbody.querySelector('.out-db-1');
                const outDb2 = tbody.querySelector('.out-db-2');
                const inDb1 = tbody.querySelector('.in-db-1');
                const inDb2 = tbody.querySelector('.in-db-2');
                const outAvgDb = tbody.querySelector('.out-avg-db');
                
                let im1 = 0, im2 = 0;
                if (code === 'VM') {
                    im1 = parseNum(tbody.querySelector('.in-im-1')?.value);
                    im2 = parseNum(tbody.querySelector('.in-im-2')?.value);

                    if(im1 === 0 || im2 === 0) {
                        const imTable = document.querySelector('table[data-code="IM"]');
                        if(imTable) {
                            const imCheck = document.querySelector(`.param-enable-check[data-pid="${imTable.dataset.pid}"]`);
                            if(imCheck && imCheck.checked) {
                                if(im1 === 0) im1 = parseNum(imTable.querySelector('.in-hasil-1')?.value);
                                if(im2 === 0) im2 = parseNum(imTable.querySelector('.in-hasil-2')?.value);
                            }
                        }
                    }
                } else {
                    const imTable = document.querySelector('table[data-code="IM"]');
                    if(imTable) {
                        const imCheck = document.querySelector(`.param-enable-check[data-pid="${imTable.dataset.pid}"]`);
                        if(imCheck && imCheck.checked) {
                            im1 = parseNum(imTable.querySelector('.in-hasil-1')?.value);
                            im2 = parseNum(imTable.querySelector('.in-hasil-2')?.value);
                        }
                    }
                }

                let db1 = 0, db2 = 0;
                if(im1 > 0) {
                    db1 = d1 * (100 / (100 - im1));
                    if(outDb1) outDb1.textContent = db1.toFixed(2);
                    if(inDb1) inDb1.value = db1.toFixed(4);
                }
                if(im2 > 0) {
                    db2 = d2 * (100 / (100 - im2));
                    if(outDb2) outDb2.textContent = db2.toFixed(2);
                    if(inDb2) inDb2.value = db2.toFixed(4);
                }

                if(im1 > 0 && im2 > 0) {
                    const avgDb = (db1 + db2) / 2;
                    if(outAvgDb) outAvgDb.textContent = avgDb.toFixed(2);
                }
            }
            
            let evalVal = parseFloat(avgStr);
            const codeEval = tbody.closest('table').dataset.code;
            if (codeEval === 'ASH' || codeEval === 'VM' || codeEval === 'TS' || codeEval === 'CV') {
                const outAvgDb = tbody.querySelector('.out-avg-db');
                if (outAvgDb && outAvgDb.textContent && !isNaN(parseFloat(outAvgDb.textContent))) {
                    evalVal = parseFloat(outAvgDb.textContent);
                }
            }

            if(window.location.href.includes('qc-harian')) {
                const trAvg = tbody.querySelector('.tr-avg');
                if(trAvg) {
                    const mean = parseFloat(trAvg.dataset.mean);
                    const sd = parseFloat(trAvg.dataset.sd);
                    const btnEval = trAvg.querySelector('.btn-evaluasi');
                    const formEval = document.getElementById('eval_' + pid);
                    
                    if(mean && sd && btnEval && formEval) {
                        btnEval.classList.remove('d-none');
                        const statusEval = formEval.querySelector('.status-eval');
                        if(statusEval) {
                            if(evalVal >= (mean - 2*sd) && evalVal <= (mean + 2*sd)) {
                                statusEval.value = 'inlier';
                                btnEval.className = 'btn btn-sm fw-bold btn-success btn-evaluasi mt-1';
                                btnEval.innerHTML = '<i class="fas fa-check-circle"></i> STATUS: INLIER';
                            } else if((evalVal >= (mean - 3*sd) && evalVal < (mean - 2*sd)) || (evalVal > (mean + 2*sd) && evalVal <= (mean + 3*sd))) {
                                statusEval.value = 'warning';
                                btnEval.className = 'btn btn-sm fw-bold btn-warning btn-evaluasi mt-1';
                                btnEval.innerHTML = '<i class="fas fa-exclamation-triangle"></i> STATUS: WARNING';
                            } else {
                                statusEval.value = 'outlier';
                                btnEval.className = 'btn btn-sm fw-bold btn-danger btn-evaluasi mt-1';
                                btnEval.innerHTML = '<i class="fas fa-times-circle"></i> STATUS: OUTLIER';
                            }
                        }
                        const avgInp = formEval.querySelector('.nilai-akhir-input');
                        if(avgInp) avgInp.value = evalVal.toFixed(2);
                    }
                }
            }
            
            if(window.location.href.includes('qc-crm')) {
                const trAvg = tbody.querySelector('.tr-avg');
                if(trAvg) {
                    const certData = window.crmCertificates[pid];
                    const btnEval = trAvg.querySelector('.btn-evaluasi');
                    const formEval = document.getElementById('eval_' + pid);

                    if (certData && btnEval && formEval) {
                        btnEval.classList.remove('d-none');
                        const statusEval = formEval.querySelector('.status-eval');
                        const v = certData.val;
                        const u = certData.u;
                        
                        if(statusEval) {
                            if(evalVal >= (v - u) && evalVal <= (v + u)) {
                                statusEval.value = 'inlier';
                                btnEval.className = 'btn btn-sm fw-bold btn-success btn-evaluasi mt-1';
                                btnEval.innerHTML = '<i class="fas fa-check-circle"></i> STATUS: INLIER';
                            } else {
                                statusEval.value = 'outlier';
                                btnEval.className = 'btn btn-sm fw-bold btn-danger btn-evaluasi mt-1';
                                btnEval.innerHTML = '<i class="fas fa-times-circle"></i> STATUS: OUTLIER';
                            }
                        }
                        const avgInp = formEval.querySelector('.nilai-akhir-input');
                        if(avgInp) avgInp.value = evalVal.toFixed(2);
                    }
                }
            }
        }
    }

    document.querySelectorAll('.param-table input').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('.row-entry');
            const pid = this.closest('table').dataset.pid;
            
            if(this.classList.contains('in-hasil-1') || this.classList.contains('in-hasil-2')) {
                const i = this.classList.contains('in-hasil-1') ? 1 : 2;
                const hiddenInp = row.querySelector(`.in-d${i}`);
                if(hiddenInp) hiddenInp.value = this.value;
            }
            
            calculateRow(row, pid, true);
        });
    });

    const formQc = document.getElementById('formQc');
    document.querySelectorAll('.param-enable-check').forEach(check => {
        check.addEventListener('change', function() {
            const pid = this.dataset.pid;
            const table = document.getElementById('table-' + pid);
            if(table) {
                const inputs = table.querySelectorAll('input:not([type="hidden"])');
                if(this.checked) {
                    inputs.forEach(inp => { if(!inp.classList.contains('bg-light')) inp.disabled = false; });
                } else {
                    inputs.forEach(inp => {
                        inp.disabled = true;
                        inp.value = '';
                    });
                }
            }
        });
    });

    function updateResourceSummary(pid) {
        const modal = document.getElementById('modalResource-' + pid);
        if(!modal) return;

        let personils = [];
        modal.querySelectorAll('.chk-personil:checked').forEach(cb => { personils.push(cb.getAttribute('data-nama')); });
        document.getElementById('sum-personil-' + pid).innerHTML = personils.length ? `<span class="text-dark fw-bold">${personils.join(', ')}</span>` : '<span class="text-danger fst-italic">Belum diatur</span>';

        let alats = [];
        modal.querySelectorAll('.chk-alat:checked').forEach(cb => { alats.push(cb.getAttribute('data-nama')); });
        document.getElementById('sum-alat-' + pid).innerHTML = alats.length ? `<span class="text-dark fw-bold">${alats.join(', ')}</span>` : '<span class="text-danger fst-italic">Belum diatur</span>';

        let bahans = [];
        modal.querySelectorAll('.chk-bahan:checked').forEach(cb => {
            let qty = cb.closest('tr').querySelector('.in-qty').value;
            let nama = cb.getAttribute('data-nama');
            bahans.push(`<span class="text-dark fw-bold">${nama} (${qty || 0})</span>`);
        });
        document.getElementById('sum-bahan-' + pid).innerHTML = bahans.length ? bahans.join(', ') : '<span class="text-danger fst-italic">Belum diatur</span>';
    }

    document.querySelectorAll('.modal-resource').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function () {
            let pid = this.id.replace('modalResource-', '');
            updateResourceSummary(pid);
        });
    });

    document.querySelectorAll('.btn-copy-resource').forEach(btn => {
        btn.addEventListener('click', function() {
            let currentPid = this.getAttribute('data-pid');

            let activeChecks = Array.from(document.querySelectorAll('.param-enable-check:checked'));
            let currentIndex = activeChecks.findIndex(cb => cb.dataset.pid === currentPid);

            if (currentIndex <= 0) {
                Swal.fire('Tidak Bisa Menyalin', 'Ini adalah parameter pertama yang diaktifkan, atau tidak ada parameter aktif di atasnya untuk disalin.', 'info');
                return;
            }

            let prevPid = activeChecks[currentIndex - 1].dataset.pid;
            let prevModal = document.getElementById('modalResource-' + prevPid);
            let currModal = document.getElementById('modalResource-' + currentPid);
            
            if (prevModal && currModal) {

                currModal.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
                currModal.querySelectorAll('.in-peran, .in-qty').forEach(inpt => { if(!inpt.disabled) inpt.value = ''; });

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
                    if(targetCb && !targetCb.disabled) {
                        targetCb.checked = true;
                        targetCb.closest('tr').querySelector('.in-qty').value = cb.closest('tr').querySelector('.in-qty').value;
                    }
                });

                updateResourceSummary(currentPid);

                let sumRow = this.closest('.row');
                let oldBg = sumRow.style.backgroundColor;
                sumRow.style.backgroundColor = '#d1e7dd'; 
                setTimeout(() => sumRow.style.backgroundColor = oldBg, 600);
            }
        });
    });

    if(formQc) {
        formQc.addEventListener('submit', function(e) {
            const isDraft = document.getElementById('is_draft').value === '1';
            const checked = document.querySelectorAll('.param-enable-check:checked');
            
            if (checked.length === 0) {
                e.preventDefault();
                Swal.fire({icon: 'warning', title: 'Belum Ada Parameter', text: 'Silakan centang minimal 1 parameter yang ingin diuji.'});
                return;
            }

            if (!isDraft) {
                let errorMsg = null;
                for (let i = 0; i < checked.length; i++) {
                    let pid = checked[i].dataset.pid;
                    let modal = document.getElementById('modalResource-' + pid);
                    let paramCode = document.getElementById('table-' + pid).dataset.code;

                    let bahans = modal.querySelectorAll('.chk-bahan:checked');
                    for (let j = 0; j < bahans.length; j++) {
                        let qty = bahans[j].closest('tr').querySelector('.in-qty').value;
                        if (!qty || parseFloat(qty) <= 0) {
                            errorMsg = `Jumlah pemakaian bahan <b>${bahans[j].dataset.nama}</b> pada parameter <b>${paramCode}</b> belum diisi!`;
                            break;
                        }
                    }
                    if (errorMsg) break;
                }

                if (errorMsg) {
                    e.preventDefault(); 
                    Swal.fire({
                        icon: 'error',
                        title: 'Data Bahan Tidak Lengkap!',
                        html: errorMsg,
                        confirmButtonColor: '#1b3152'
                    });
                    return;
                }
            }
        });
    }

    const btnDraft = document.getElementById('btnDraft');
    if(btnDraft) {
        btnDraft.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('is_draft').value = '1';
            
            Swal.fire({
                title: 'Menyimpan Draft...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            document.getElementById('formQc').submit();
        });
    }

    @if(isset($serverDraft))
        const serverDraft = @json($serverDraft);

        if(serverDraft.tanggal_uji) {
            const tgl = document.querySelector('input[name="tanggal_uji"]');
            if(tgl) tgl.value = serverDraft.tanggal_uji;
        }
        if(serverDraft.nama_sampel) {
            const nm = document.querySelector('input[name="nama_sampel_uji"]');
            if(nm) nm.value = serverDraft.nama_sampel;
        }

        Object.keys(serverDraft.params).forEach(pid => {
            const paramData = serverDraft.params[pid];
            const check = document.querySelector(`.param-enable-check[data-pid="${pid}"]`);
            const table = document.getElementById('table-' + pid);
            const modal = document.getElementById('modalResource-' + pid);
            
            if(check && table && paramData.selected) {

                check.checked = true;
                check.dispatchEvent(new Event('change'));

                if (paramData.mentah && modal) {

                    if (paramData.mentah.personil_ids) {
                        paramData.mentah.personil_ids.forEach(id => {
                            let cb = modal.querySelector(`.chk-personil[value="${id}"]`);
                            if (cb) {
                                cb.checked = true;
                                if (paramData.mentah.personil_peran && paramData.mentah.personil_peran[id]) {
                                    cb.closest('tr').querySelector('.in-peran').value = paramData.mentah.personil_peran[id];
                                }
                            }
                        });
                    }

                    if (paramData.mentah.alat_ids) {
                        paramData.mentah.alat_ids.forEach(id => {
                            let cb = modal.querySelector(`.chk-alat[value="${id}"]`);
                            if (cb) cb.checked = true;
                        });
                    }

                    if (paramData.mentah.barang_ids) {
                        paramData.mentah.barang_ids.forEach(id => {
                            let cb = modal.querySelector(`.chk-bahan[value="${id}"]`);
                            if (cb && !cb.disabled) {
                                cb.checked = true;
                                if (paramData.mentah.barang_jumlah && paramData.mentah.barang_jumlah[id]) {
                                    cb.closest('tr').querySelector('.in-qty').value = paramData.mentah.barang_jumlah[id];
                                }
                            }
                        });
                    }
                }

                if (typeof updateResourceSummary === 'function') {
                    updateResourceSummary(pid);
                }

                if (paramData.mentah) {
                    Object.keys(paramData.mentah).forEach(k => {

                        const inp = table.querySelector(`[name="params[${pid}][mentah][${k}]"]`);
                        if (inp && paramData.mentah[k] !== undefined && paramData.mentah[k] !== null) {
                            if (inp.type !== 'checkbox' && inp.type !== 'radio') {
                                inp.value = paramData.mentah[k];
                                inp.dispatchEvent(new Event('input'));  
                            }
                        }
                    });
                }

                const d1Inp = table.querySelector('.in-d1');
                if(d1Inp && paramData.d1) {
                    d1Inp.value = paramData.d1;
                    d1Inp.dispatchEvent(new Event('input'));
                }
                const d2Inp = table.querySelector('.in-d2');
                if(d2Inp && paramData.d2) {
                    d2Inp.value = paramData.d2;
                    d2Inp.dispatchEvent(new Event('input'));
                }
            }
        });
        
        Swal.fire({
            icon: 'info',
            title: 'Melanjutkan Draft',
            text: 'Seluruh data pengujian, alat, dan bahan berhasil dipulihkan.',
            timer: 2000,
            showConfirmButton: false
        });
    @endif
});
</script>
@endsection