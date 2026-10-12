@extends('layouts.app')
@section('title', 'Tambah Baru - QC In-House')

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

    .page-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .page-subtitle {
        font-size: 0.72rem;
        color: #6c757d;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 1px;
    }

    .section-note {
        font-size: 0.68rem;
        color: #6c757d;
        margin-bottom: 0;
    }

    .form-label {
        font-size: 0.74rem;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .form-control,
    .form-select,
    .input-group-text {
        font-size: 0.78rem;
    }

    .form-control,
    .form-select {
        padding-top: 0.28rem;
        padding-bottom: 0.28rem;
        color: #000000;
    }

    .form-control::placeholder {
        color: #8a939c;
    }

    .input-group-text {
        background-color: #f1f4f8;
        color: #1b3152;
        font-weight: 600;
        padding-top: 0.28rem;
        padding-bottom: 0.28rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1b3152;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
    }

    .select2-container--bootstrap-5 .select2-selection {
        min-height: calc(1.5em + 0.56rem + 2px);
        font-size: 0.78rem;
    }

    .select2-container--bootstrap-5 .select2-selection--single {
        display: flex;
        align-items: center;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        color: #000000;
        font-weight: 400;
    }

    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #ced4da;
        box-shadow: none;
    }

    .select2-container--bootstrap-5 .select2-dropdown {
        border-color: #ced4da;
    }

    .select2-container--bootstrap-5 .select2-dropdown .select2-results__option {
        color: #000000;
        font-size: 0.82rem;
        padding: 8px 12px;
    }

    .select2-container--bootstrap-5 .select2-dropdown .select2-results__option[aria-selected="true"],
    .select2-container--bootstrap-5 .select2-dropdown .select2-results__option--selected {
        background-color: rgba(27, 49, 82, 0.18) !important;
        color: #000000 !important;
    }

    .select2-container .select2-results__option,
    .select2-container .select2-results__options .select2-results__option,
    .select2-dropdown .select2-results__option,
    .form-select option {
        color: #000000 !important;
    }

    .select2-container .select2-results__option:hover,
    .select2-container .select2-results__option--highlighted,
    .select2-container .select2-results__option--highlighted[aria-selected],
    .select2-dropdown .select2-results__option:hover,
    .select2-dropdown .select2-results__option--highlighted,
    .select2-dropdown .select2-results__option--highlighted[aria-selected] {
        background-color: rgba(27, 49, 82, 0.15) !important;
        color: #000000 !important;
    }

    .limit-hint {
        font-size: 0.66rem;
        color: #6c757d;
        margin-top: 3px;
    }

    .limit-text {
        font-weight: 700;
        color: #1b3152;
    }

    .icon-corporate {
        color: #1b3152;
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

    .btn-corporate-blue:disabled {
        opacity: 0.6;
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
    }

    .fc-note {
        font-size: 0.68rem;
        color: #6c757d;
        margin-bottom: 0;
    }

    #fc_display {
        font-size: 1.15rem;
    }

    .warning-box {
        background-color: #fdecea;
        border: 1px solid #f5c2c7;
        border-left: 4px solid #dc3545;
        border-radius: 6px;
        color: #842029;
        padding: 8px 12px;
        margin-top: 8px;
        font-size: 0.75rem;
    }

    .warning-title {
        font-weight: 800;
        letter-spacing: 0.04em;
        margin-bottom: 3px;
        font-size: 0.78rem;
    }

    .warning-box ul {
        margin-bottom: 0;
        padding-left: 1.1rem;
    }

    .warning-box li {
        margin-bottom: 1px;
        line-height: 1.4;
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

    .sop-card p,
    .sop-card li {
        font-size: 0.78rem;
        line-height: 1.5;
        color: #e8eef7;
    }

    .sop-card strong {
        color: #ffffff;
    }

    .sop-card ul {
        margin-bottom: 0;
    }

    .sop-card li {
        font-weight: 600;
        color: #ffffff;
    }

    .sop-card .sop-note {
        background-color: #ffffff;
        color: #1b3152;
        border-radius: 6px;
        padding: 8px 10px;
        font-size: 0.78rem;
        font-weight: 600;
        line-height: 1.45;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
    }

    .sop-card .sop-note strong {
        color: #1b3152;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.03em;
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

    .dashboard-container .page-title,
    .dashboard-container .page-subtitle,
    .dashboard-container .section-title,
    .dashboard-container .section-note,
    .dashboard-container .form-label,
    .dashboard-container .text-secondary,
    .dashboard-container .text-dark,
    .dashboard-container .text-muted,
    .dashboard-container .limit-hint,
    .dashboard-container .limit-text,
    .dashboard-container .fc-title,
    .dashboard-container .fc-note,
    .dashboard-container .input-group-text,
    .dashboard-container .select2-selection__rendered {
        color: #000000 !important;
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

        .form-control,
        .form-select,
        .input-group-text {
            font-size: 16px;
        }

        .form-control,
        .form-select {
            min-height: 40px;
        }

        .form-label {
            font-size: 0.78rem;
        }

        .limit-hint {
            font-size: 0.7rem;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container--bootstrap-5 .select2-selection {
            min-height: 40px;
            font-size: 16px;
            border: 1px solid #ced4da;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            font-size: 16px;
            font-weight: 400;
        }

        .select2-container--bootstrap-5 .select2-dropdown .select2-results__option {
            font-size: 16px;
            padding: 12px 14px;
        }

        .warning-box {
            font-size: 0.8rem;
        }

        .sop-card p,
        .sop-card li,
        .sop-card .sop-note {
            font-size: 0.82rem;
        }

        .submit-wrap {
            flex-direction: column-reverse;
        }

        .submit-wrap .btn-kembali,
        .submit-wrap .btn-corporate-blue {
            width: 100%;
            min-height: 40px;
            font-size: 0.85rem;
        }
    }
</style>

<div class="container-fluid dashboard-container" style="font-size: 0.78rem;">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item active">Tahap 1: Pemilihan Sampel</li>
    </x-qc-breadcrumb>

    <div class="row g-2">
        <div class="col-xl-9 col-lg-8 order-2 order-lg-1">
            <div class="card shadow-sm border-0 mb-2">
                <div class="card-body">
                    <h5 class="page-title text-dark"><i class="fas fa-clipboard-list icon-corporate me-2"></i>Tahap 1: Screening & Pemilihan Sampel</h5>
                    <p class="page-subtitle">Masukkan spesifikasi kandidat sampel standar batubara. Sistem akan memvalidasi kesesuaian rentang komoditas dan hukum fisika proksimat sebelum otomatis memaketkan 5 parameter uji (MAD, Ash, VM, TS, GCV).</p>

                    @if($errors->any())
                        <div class="alert alert-danger py-2 px-3 mb-2 rounded-3" style="font-size: 0.75rem;">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $err)
                                    <li><i class="fas fa-exclamation-triangle me-1"></i> {{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('qc-inhouse.store') }}" method="POST" id="formPemilihan" autocomplete="off">
                        @csrf

                        <div class="row g-2 mb-2">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Nama Sampel <span class="text-danger">*</span></label>
                                <input type="text" name="nama_sampel" class="form-control" value="{{ old('nama_sampel') }}" placeholder="Contoh: Coal A Agustus" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Jenis Batubara <span class="text-danger">*</span></label>
                                <select name="jenis_batubara" class="form-select" id="jenis_batubara" required>
                                    <option value="">-- Pilih Jenis Batubara --</option>
                                    <option value="lignite" {{ old('jenis_batubara') == 'lignite' ? 'selected' : '' }}>Lignite</option>
                                    <option value="sub_bituminous" {{ old('jenis_batubara') == 'sub_bituminous' ? 'selected' : '' }}>Sub Bituminous</option>
                                    <option value="bituminous" {{ old('jenis_batubara') == 'bituminous' ? 'selected' : '' }}>Bituminous</option>
                                    <option value="anthracite" {{ old('jenis_batubara') == 'anthracite' ? 'selected' : '' }}>Anthracite</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-3 mb-2 border-bottom pb-1">
                            <h6 class="section-title text-dark"><i class="fas fa-flask icon-corporate me-2"></i>Data Analisis Pemilihan (Screening Kasar)</h6>
                            <p class="section-note">Hukum Fisika: TM harus > MAD. Total Proksimat (MAD + Ash + VM) harus &lt; 100%.</p>
                        </div>

                        <div class="row g-2">
                            <div class="col-12 col-md-6 col-xl-4">
                                <label class="form-label text-secondary">Total Moisture (TM) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" name="tm" id="tm" class="form-control" value="{{ old('tm') }}" inputmode="decimal" required>
                                    <span class="input-group-text">% AR</span>
                                </div>
                                <div class="limit-hint"><i class="fas fa-info-circle"></i> <span id="limit_tm" class="limit-text">Pilih Jenis Batubara</span></div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-4">
                                <label class="form-label text-secondary">Moisture in Analysis (MAD) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" name="mad" id="mad" class="form-control" value="{{ old('mad') }}" inputmode="decimal" required>
                                    <span class="input-group-text">% ADB</span>
                                </div>
                                <div class="limit-hint"><i class="fas fa-info-circle"></i> <span id="limit_mad" class="limit-text">Pilih Jenis Batubara</span></div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-4">
                                <label class="form-label text-secondary">Ash Content <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" name="ash" id="ash" class="form-control" value="{{ old('ash') }}" inputmode="decimal" required>
                                    <span class="input-group-text">% ADB</span>
                                </div>
                                <div class="limit-hint"><i class="fas fa-info-circle"></i> <span id="limit_ash" class="limit-text">Pilih Jenis Batubara</span></div>
                            </div>

                            <div class="col-12 col-md-6 col-xl-4">
                                <label class="form-label text-secondary">Volatile Matter (VM) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" name="vm" id="vm" class="form-control" value="{{ old('vm') }}" inputmode="decimal" required>
                                    <span class="input-group-text">% ADB</span>
                                </div>
                                <div class="limit-hint"><i class="fas fa-info-circle"></i> <span id="limit_vm" class="limit-text">Pilih Jenis Batubara</span></div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-4">
                                <label class="form-label text-secondary">Total Sulfur (TS) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" name="ts" id="ts" class="form-control" value="{{ old('ts') }}" inputmode="decimal" required>
                                    <span class="input-group-text">% ADB</span>
                                </div>
                                <div class="limit-hint"><i class="fas fa-info-circle"></i> <span id="limit_ts" class="limit-text">Pilih Jenis Batubara</span></div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-4">
                                <label class="form-label text-secondary">Gross Calorific Value (GCV) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="1" name="gcv" id="gcv" class="form-control" value="{{ old('gcv') }}" inputmode="numeric" required>
                                    <span class="input-group-text">Kcal/kg</span>
                                </div>
                                <div class="limit-hint"><i class="fas fa-info-circle"></i> <span id="limit_gcv" class="limit-text">Pilih Jenis Batubara</span></div>
                            </div>
                        </div>

                        <div class="fc-box mt-3">
                            <div class="row align-items-center g-2">
                                <div class="col-8 col-md-9">
                                    <h6 class="fc-title">Fixed Carbon (FC) Kalkulasi Keseimbangan</h6>
                                    <p class="fc-note">Dihitung otomatis: 100 - (MAD + Ash + VM)</p>
                                </div>
                                <div class="col-4 col-md-3 text-end">
                                    <h3 class="fw-bold text-success mb-0" id="fc_display">- %</h3>
                                </div>
                            </div>
                        </div>

                        <div id="physics_warning" class="warning-box d-none" role="alert">
                            <div class="warning-title"><i class="fas fa-exclamation-triangle me-1"></i> PERINGATAN</div>
                            <ul id="physics_warning_list"></ul>
                        </div>

                        <div class="submit-wrap d-flex justify-content-end align-items-center gap-2 mt-3">
                            <a href="{{ route('qc-inhouse.index') }}" class="btn btn-kembali btn-sm py-1.5 px-3 shadow-sm fw-semibold">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold" id="btnSubmit">
                                <i class="fas fa-save me-1"></i> Simpan & Lanjut ke Preparasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-4 order-1 order-lg-2">
            <div class="card sop-card shadow-sm mb-2">
                <div class="sop-header" role="button" data-bs-toggle="collapse" data-bs-target="#sopBody" aria-expanded="false" aria-controls="sopBody">
                    <h6 class="sop-title"><i class="fas fa-info-circle me-1"></i> Informasi SOP</h6>
                    <i class="fas fa-chevron-down sop-chevron"></i>
                </div>
                <div id="sopBody" class="collapse sop-collapse">
                    <div class="sop-content">
                        <p class="mb-2">Pada Tahap 1, batubara akan melalui <strong>Screening Kasar</strong>.</p>
                        <p class="mb-2">Jika lolos rentang komoditas, sistem akan otomatis menetapkan batubara ini sebagai sampel <strong>General Analysis (GA)</strong> dan memaketkan 5 parameter berikut untuk Uji Homogenitas:</p>
                        <ul class="ps-3">
                            <li>MAD</li>
                            <li>Ash Content</li>
                            <li>Volatile Matter (VM)</li>
                            <li>Total Sulfur (TS)</li>
                            <li>Gross Calorific Value (GCV)</li>
                        </ul>
                        <p class="sop-note mt-3 mb-0"><strong>Note:</strong> Total Moisture (TM) dieliminasi dari pengujian selanjutnya.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rentangBaku = @json($rentangBaku ?? []);

    const jenisDropdown = document.getElementById('jenis_batubara');
    const limitTm = document.getElementById('limit_tm');
    const limitMad = document.getElementById('limit_mad');
    const limitAsh = document.getElementById('limit_ash');
    const limitVm = document.getElementById('limit_vm');
    const limitTs = document.getElementById('limit_ts');
    const limitGcv = document.getElementById('limit_gcv');

    const tm = document.getElementById('tm');
    const mad = document.getElementById('mad');
    const ash = document.getElementById('ash');
    const vm = document.getElementById('vm');
    const ts = document.getElementById('ts');
    const gcv = document.getElementById('gcv');
    const fcDisplay = document.getElementById('fc_display');
    const btnSubmit = document.getElementById('btnSubmit');
    const warningBox = document.getElementById('physics_warning');
    const warningList = document.getElementById('physics_warning_list');

    const fieldMap = [
        { key: 'tm', label: 'TM', input: tm },
        { key: 'mad', label: 'MAD', input: mad },
        { key: 'ash', label: 'Ash', input: ash },
        { key: 'vm', label: 'VM', input: vm },
        { key: 'ts', label: 'TS', input: ts },
        { key: 'gcv', label: 'GCV', input: gcv }
    ];

    function formatLimit(obj) {
        if (!obj) return 'Tidak ada standar';
        if (obj.min === null && obj.max === null) return 'Rentang bebas (Tidak dibatasi)';
        if (obj.min === null) return 'Batas Maks: ' + obj.max;
        if (obj.max === null) return 'Batas Min: ' + obj.min;
        return 'Batas: ' + obj.min + ' s.d ' + obj.max;
    }

    function updateLimits() {
        let jenis = jenisDropdown.value;
        if (!jenis || !rentangBaku[jenis]) {
            let msg = 'Pilih Jenis Batubara';
            limitTm.textContent = msg;
            limitMad.textContent = msg;
            limitAsh.textContent = msg;
            limitVm.textContent = msg;
            limitTs.textContent = msg;
            limitGcv.textContent = msg;
        } else {
            let limits = rentangBaku[jenis];
            limitTm.textContent = formatLimit(limits.tm);
            limitMad.textContent = formatLimit(limits.mad);
            limitAsh.textContent = formatLimit(limits.ash);
            limitVm.textContent = formatLimit(limits.vm);
            limitTs.textContent = formatLimit(limits.ts);
            limitGcv.textContent = formatLimit(limits.gcv);
        }
    }

    function collectRangeWarnings() {
        let messages = [];
        let jenis = jenisDropdown.value;

        if (!jenis || !rentangBaku[jenis]) return messages;

        let limits = rentangBaku[jenis];

        fieldMap.forEach(function(field) {
            let value = parseFloat(field.input.value);
            let limit = limits[field.key];

            if (isNaN(value) || !limit) return;

            if (limit.max !== null && limit.max !== undefined && value > limit.max) {
                messages.push(field.label + ' terlalu tinggi (maks ' + limit.max + ').');
            }

            if (limit.min !== null && limit.min !== undefined && value < limit.min) {
                messages.push(field.label + ' terlalu rendah (min ' + limit.min + ').');
            }
        });

        return messages;
    }

    function checkPhysics() {
        let valTm = parseFloat(tm.value);
        let valMad = parseFloat(mad.value);
        let valAsh = parseFloat(ash.value);
        let valVm = parseFloat(vm.value);

        let hasError = false;
        let messages = collectRangeWarnings();

        if (!isNaN(valTm) && !isNaN(valMad)) {
            if (valTm <= valMad) {
                hasError = true;
                messages.push('TM harus lebih besar dari MAD.');
            }
        }

        if (!isNaN(valMad) && !isNaN(valAsh) && !isNaN(valVm)) {
            let total = valMad + valAsh + valVm;
            let fc = 100 - total;

            fcDisplay.textContent = fc.toFixed(2) + ' %';

            if (fc <= 0) {
                hasError = true;
                messages.push('Total MAD + Ash + VM melebihi 100%, Fixed Carbon tidak boleh negatif/nol.');
                fcDisplay.className = 'fw-bold text-danger mb-0';
            } else {
                fcDisplay.className = 'fw-bold text-success mb-0';
            }
        } else {
            fcDisplay.textContent = '- %';
            fcDisplay.className = 'fw-bold text-success mb-0';
        }

        warningList.innerHTML = '';

        if (messages.length > 0) {
            messages.forEach(function(text) {
                let li = document.createElement('li');
                li.textContent = text;
                warningList.appendChild(li);
            });
            warningBox.classList.remove('d-none');
        } else {
            warningBox.classList.add('d-none');
        }

        btnSubmit.disabled = hasError;
    }

    $('#jenis_batubara').select2({
        theme: 'bootstrap-5',
        width: '100%',
        minimumResultsForSearch: Infinity
    }).on('select2:select select2:clear', function() {
        jenisDropdown.dispatchEvent(new Event('change'));
    });

    jenisDropdown.addEventListener('change', function() {
        updateLimits();
        checkPhysics();
    });

    fieldMap.forEach(function(field) {
        field.input.addEventListener('input', checkPhysics);
    });

    updateLimits();
    checkPhysics();
});
</script>
@endsection