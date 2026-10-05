@extends('layouts.app')
@section('title', 'Edit Data - Parameter Uji')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
<style>
    .pu-page { font-size: 0.8rem; }
    .pu-container { padding-top: 2px; }
    .pu-breadcrumb { font-size: 0.72rem; }
    .pu-title { font-size: 1.05rem; font-weight: 700; color: #1b3152; margin-bottom: 0.6rem; }

    .pu-page .card { border: 0; border-radius: 0.5rem; }
    .pu-page .card-body { padding: 0.75rem; }
    .card-header-custom {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.85rem !important;
        padding: 0.5rem 0.9rem !important;
    }

    .pu-page .text-primary { color: #1b3152 !important; }
    .pu-page .text-secondary { color: #1b3152 !important; }

    .pu-page .form-label {
        font-size: 0.75rem !important;
        font-weight: 600;
        margin-bottom: 0.2rem;
    }
    .pu-page .form-control,
    .pu-page .form-select,
    .pu-page .form-control-sm,
    .pu-page .form-select-sm {
        font-size: 0.8rem !important;
        padding: 0.3rem 0.55rem;
    }
    .pu-page .form-control:focus,
    .pu-page .form-select:focus {
        border-color: #1b3152;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
    }
    .pu-page .form-text,
    .pu-page small.text-muted { font-size: 0.7rem !important; }

    .pu-page .alert {
        font-size: 0.75rem;
        padding: 0.4rem 0.7rem;
        margin-bottom: 0.6rem;
    }

    .pu-page .btn,
    .modal .btn {
        font-size: 0.78rem !important;
        padding: 0.32rem 0.85rem !important;
        font-weight: 600;
        border-radius: 0.375rem;
        transition: background-color .15s ease, border-color .15s ease, color .15s ease, box-shadow .15s ease, transform .15s ease;
    }
    .pu-page .btn:hover,
    .modal .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.18);
    }
    .pu-page .btn:active,
    .modal .btn:active { transform: none; box-shadow: none; }
    .pu-page .btn.btn-icon,
    .modal .btn.btn-icon { padding: 0.2rem 0.5rem !important; }

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
        background-color: transparent !important;
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

    .pu-page .nav-tabs { flex-wrap: wrap; margin-bottom: 0; border-bottom: 1px solid #dee2e6; }
    .pu-page .nav-tabs { border-bottom: 2px solid #1b3152; }
    .pu-page .nav-tabs .nav-link {
        font-size: 0.78rem;
        padding: 0.45rem 0.85rem;
        color: #1b3152;
        background-color: #eef2f8;
        font-weight: 700;
        border: 1px solid #d5deee;
        border-bottom: 0;
        margin-right: 3px;
        margin-bottom: 0;
        border-radius: 0.375rem 0.375rem 0 0;
    }
    .pu-page .nav-tabs .nav-link:hover {
        background-color: #dde5f0;
        color: #14253e;
    }
    .pu-page .nav-tabs .nav-link:focus,
    .pu-page .nav-tabs .nav-link:focus-visible {
        outline: none;
        box-shadow: none;
    }
    .pu-page .nav-tabs .nav-link.active {
        background-color: #1b3152;
        border-color: #1b3152;
        color: #ffffff;
    }
    .pu-tab-content {
        border: 1px solid #dee2e6;
        border-top: 0;
        background: #fff;
        padding: 0.9rem;
        margin-bottom: 0.9rem;
    }

    .pu-table-crm { font-size: 0.78rem; min-width: 640px; }
    .pu-table-langkah { min-width: 480px; }
    .pu-page .table th,
    .pu-page .table td,
    .modal .table th,
    .modal .table td { padding: 0.35rem 0.5rem; vertical-align: middle; }
    .pu-page .table thead th,
    .modal .table thead th {
        background-color: #1b3152 !important;
        color: #fff !important;
        font-size: 0.75rem;
        border-color: rgba(255, 255, 255, 0.35) !important;
    }

    .flatpickr-input { font-size: 0.8rem !important; }
    .flatpickr-calendar { font-size: 0.85rem; }

    .pu-modal-header { background-color: #1b3152 !important; color: #ffffff !important; padding: 0.5rem 0.9rem; }
    .pu-modal-header .modal-title { font-size: 0.9rem !important; }
    .modal .modal-body { font-size: 0.8rem; padding: 0.9rem; }
    .modal .modal-footer { padding: 0.5rem 0.9rem; gap: 0.4rem; }

    .filter-select {
        position: relative;
        font-size: 0.8rem;
    }
    .filter-select-trigger {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.3rem 0.55rem;
        font-size: 0.8rem;
        cursor: pointer;
        text-align: left;
        color: #212529;
    }
    .filter-select-trigger:after {
        content: "";
        width: 0; height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid #6c757d;
        margin-left: 6px;
        flex-shrink: 0;
    }
    .filter-select.open .filter-select-trigger {
        border-color: #1b3152;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
    }
    .filter-select-options {
        display: none;
        position: absolute;
        top: 100%; left: 0; right: 0;
        z-index: 1055;
        margin-top: 2px;
        max-height: 220px;
        overflow-y: auto;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.12);
        list-style: none;
        padding: 4px 0;
    }
    .filter-select.open .filter-select-options { display: block; }
    .filter-select-options li { padding: 6px 10px; font-size: 0.8rem; cursor: pointer; }
    .filter-select-options li:hover { background-color: #eef2f8; }
    .filter-select-options li.selected { background-color: #1b3152; color: #fff; }

    .swal2-popup { font-size: 0.9rem !important; }
    .swal2-title { font-size: 1.1rem !important; }
    .swal2-html-container { font-size: 0.82rem !important; }

    @media (max-width: 767.98px) {
        .pu-title { font-size: 0.95rem; }
        .pu-page .card-body { padding: 0.6rem; }
        .pu-tab-content { padding: 0.65rem; }
        .pu-page .nav-tabs .nav-link { font-size: 0.72rem; padding: 0.4rem 0.6rem; }
    }

    @media (max-width: 575.98px) {
        .pu-form-actions { flex-direction: column-reverse; }
        .pu-form-actions a, .pu-form-actions button { width: 100%; text-align: center; }
        .pu-crm-head .btn { width: 100%; }
    }
</style>

<div class="container-fluid px-2 px-md-4 pu-container pu-page">
    <ol class="breadcrumb mb-1 mt-1 pu-breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
        <li class="breadcrumb-item"><a href="{{ route('parameter-uji.index') }}" class="text-decoration-none">Parameter Uji</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    <h4 class="pu-title">Edit Parameter Uji</h4>

    <div class="card mb-3 shadow-sm">
        <div class="card-header card-header-custom"><i class="fas fa-edit me-2"></i>Form Edit Parameter Uji</div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('parameter-uji.update', $parameterUji->parameter_uji_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-2 mb-2">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Nama Parameter <span class="text-danger">*</span></label>
                        <input type="text" name="nama_parameter" class="form-control" value="{{ old('nama_parameter', $parameterUji->nama_parameter) }}" required maxlength="50">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="satuan" class="form-control" value="{{ old('satuan', $parameterUji->satuan) }}" required maxlength="20">
                    </div>
                </div>

                <ul class="nav nav-tabs" id="configTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="inhouse-tab" data-bs-toggle="tab" href="#inhouse" role="tab">In-House Control & Pengaturan Umum</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="crm-tab" data-bs-toggle="tab" href="#crm" role="tab">Sertifikat Pabrik (CRM)</a>
                    </li>
                </ul>
                <div class="tab-content pu-tab-content" id="configTabsContent">
                    <div class="tab-pane fade show active" id="inhouse" role="tabpanel">

                        <div class="card bg-light mb-2 border-0">
                            <div class="card-body">
                                <p class="mb-2 fw-bold" style="font-size: 0.8rem; color: #1b3152;"><i class="fas fa-chart-line me-1"></i> Input Data Statistik (In-House)</p>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-1"></i> Nilai <strong>Mean</strong> dan <strong>SD</strong> didapatkan secara otomatis dari hasil <strong>Uji Homogenitas</strong>. Jika dilakukan uji homogenitas ulang, maka nilai-nilai ini akan ikut berubah secara otomatis.
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-bold text-primary">Mean (Rata-rata)</label>
                                        <input type="number" step="0.0001" name="mean" id="inputMean" class="form-control" value="{{ old('mean', $parameterUji->mean ? number_format($parameterUji->mean, 4, '.', '') : '') }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-bold text-primary">SD (Standard Deviation)</label>
                                        <input type="number" step="0.0001" name="sd" id="inputSd" class="form-control" value="{{ old('sd', $parameterUji->sd ? number_format($parameterUji->sd, 4, '.', '') : '') }}">
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-6 col-md">
                                        <label class="form-label text-muted">LCL (-3 SD)</label>
                                        <input type="text" id="calcLcl" name="lcl" class="form-control form-control-sm bg-white" readonly value="{{ old('lcl', $parameterUji->lcl ? number_format($parameterUji->lcl, 4, '.', '') : '') }}">
                                    </div>
                                    <div class="col-6 col-md">
                                        <label class="form-label text-muted">LWL (-2 SD)</label>
                                        <input type="text" id="calcUwlBawah" name="uwl_bawah" class="form-control form-control-sm bg-white" readonly value="{{ old('uwl_bawah', $parameterUji->uwl_bawah ? number_format($parameterUji->uwl_bawah, 4, '.', '') : '') }}">
                                    </div>
                                    <div class="col-6 col-md">
                                        <label class="form-label text-muted">UWL (+2 SD)</label>
                                        <input type="text" id="calcUwlAtas" name="uwl_atas" class="form-control form-control-sm bg-white" readonly value="{{ old('uwl_atas', $parameterUji->uwl_atas ? number_format($parameterUji->uwl_atas, 4, '.', '') : '') }}">
                                    </div>
                                    <div class="col-6 col-md">
                                        <label class="form-label text-muted">UCL (+3 SD)</label>
                                        <input type="text" id="calcUcl" name="ucl" class="form-control form-control-sm bg-white" readonly value="{{ old('ucl', $parameterUji->ucl ? number_format($parameterUji->ucl, 4, '.', '') : '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-bold">Nilai Acuan <span class="text-danger">*</span></label>
                                <input type="number" step="0.0001" name="nilai_acuan" id="inputAcuan" class="form-control" value="{{ old('nilai_acuan', number_format($parameterUji->nilai_acuan, 4, '.', '')) }}" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-bold">Batas Bawah <span class="text-danger">*</span></label>
                                <input type="number" step="0.0001" name="batas_bawah" id="inputBatasBawah" class="form-control" value="{{ old('batas_bawah', number_format($parameterUji->batas_bawah, 4, '.', '')) }}" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-bold">Batas Atas <span class="text-danger">*</span></label>
                                <input type="number" step="0.0001" name="batas_atas" id="inputBatasAtas" class="form-control" value="{{ old('batas_atas', number_format($parameterUji->batas_atas, 4, '.', '')) }}" required>
                            </div>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-bold">Metode / Kriteria</label>
                                <input type="text" name="metode_kriteria" class="form-control" value="{{ old('metode_kriteria', $parameterUji->metode_kriteria) }}" maxlength="50">
                            </div>
                            <div class="col-12 col-md-8">
                                <label class="form-label fw-bold">Rumus Kalkulasi Nilai Akhir</label>
                                <div class="input-group">
                                    <input type="text" name="rumus_kalkulasi" class="form-control" value="{{ old('rumus_kalkulasi', $parameterUji->rumus_kalkulasi) }}" placeholder="Contoh: (M1 - M2) / M3 * 100">
                                    <button class="btn btn-outline-corporate" type="button" data-bs-toggle="modal" data-bs-target="#modalLangkahKalkulasi">
                                        <i class="fas fa-list-ol"></i> Detail Rumus
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-1">Gunakan nama variabel input (contoh: <code>M1</code>, <code>M2</code>, <code>Avg_C</code>).</small>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold text-danger">Formula Toleransi Duplo</label>
                                <input type="text" name="toleransi_duplo" class="form-control" value="{{ old('toleransi_duplo', $parameterUji->toleransi_duplo) }}" placeholder="Contoh: 0.09 + (0.1 * Avg_M)">
                                <small class="text-muted d-block mt-1">Gunakan angka statis (contoh: <code>1.5</code>) atau formula matematika.</small>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Status Aktif</label>
                                @php $statusAktifSelected = old('status_aktif', $parameterUji->status_aktif); @endphp
                                <div class="filter-select" id="selectStatusAktif">
                                    <input type="hidden" name="status_aktif" value="{{ $statusAktifSelected }}">
                                    <button type="button" class="filter-select-trigger">{{ $statusAktifSelected == 1 ? 'Aktif' : 'Nonaktif' }}</button>
                                    <ul class="filter-select-options">
                                        <li data-value="1" class="{{ $statusAktifSelected == 1 ? 'selected' : '' }}">Aktif</li>
                                        <li data-value="0" class="{{ $statusAktifSelected == 0 ? 'selected' : '' }}">Nonaktif</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modalLangkahKalkulasi" tabindex="-1" aria-labelledby="modalLangkahKalkulasiLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header pu-modal-header">
                                        <h5 class="modal-title" id="modalLangkahKalkulasiLabel"><i class="fas fa-list-ol me-2"></i> Detail Langkah Kalkulasi Per Kolom</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-muted small mb-2">Jika Anda mengisi langkah kalkulasi di bawah ini, mesin akan menghitung variabel secara berurutan sebelum mencapai Nilai Akhir. Sangat berguna untuk perhitungan yang bertahap (multi-step).</p>
                                        <div class="table-responsive mb-2">
                                            <table class="table table-bordered table-sm mb-2 pu-table-langkah" id="langkahTable">
                                                <thead>
                                                    <tr>
                                                        <th width="35%">Nama Variabel Output</th>
                                                        <th width="55%">Rumus Matematika</th>
                                                        <th width="10%" class="text-center">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $langkahs = old('langkah_kalkulasi', $parameterUji->langkah_kalkulasi ?? []); @endphp
                                                    @if(is_array($langkahs) && count($langkahs) > 0)
                                                        @foreach($langkahs as $index => $langkah)
                                                        <tr>
                                                            <td><input type="text" name="langkah_kalkulasi[{{$index}}][var]" class="form-control form-control-sm" value="{{ $langkah['var'] ?? '' }}" placeholder="Contoh: M2_D1"></td>
                                                            <td><input type="text" name="langkah_kalkulasi[{{$index}}][rumus]" class="form-control form-control-sm" value="{{ $langkah['rumus'] ?? '' }}" placeholder="Contoh: M1_D1 + A_D1"></td>
                                                            <td class="text-center"><button type="button" class="btn btn-outline-danger btn-icon btn-remove-langkah"><i class="fas fa-trash"></i></button></td>
                                                        </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-corporate-blue" id="btnAddLangkah"><i class="fas fa-plus"></i> Tambah Baris Rumus</button>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane fade" id="crm" role="tabpanel">
                        <p class="mb-2 fw-bold" style="font-size: 0.8rem; color: #1b3152;"><i class="fas fa-certificate me-1"></i> Pengaturan Nilai Sertifikat per Lot CRM</p>
                        <div class="alert alert-warning">
                            <span>Anda dapat mengisi nilai Sertifikat (Cert Value) dan Uncertainty (U) untuk masing-masing kode Lot CRM yang saat ini aktif. Nilai ini akan digunakan untuk mengevaluasi akurasi hasil uji.</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2 pu-crm-head">
                            <h6 class="m-0 fw-bold" style="font-size: 0.85rem; color: #1b3152;">Daftar Botol / Lot CRM</h6>
                            <button type="button" class="btn btn-corporate-blue shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddCrm">
                                <i class="fas fa-plus"></i> Tambah Lot CRM
                            </button>
                        </div>

                        @if(isset($katalogCrmList) && $katalogCrmList->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0 pu-table-crm">
                                    <thead>
                                        <tr>
                                            <th>Kode Lot CRM</th>
                                            <th>Produsen / Jenis</th>
                                            <th class="text-center" style="width: 25%">Nilai Sertifikat (Cert Value)</th>
                                            <th class="text-center" style="width: 25%">Uncertainty (U)</th>
                                            <th class="text-center" style="width: 10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($katalogCrmList as $katalog)
                                            @php $sert = $parameterUji->sertifikatCrm->where('crm_katalog_id', $katalog->id)->first(); @endphp
                                            <tr>
                                                <td class="align-middle fw-bold">{{ $katalog->nomor_lot }}</td>
                                                <td class="align-middle">{{ $katalog->produsen ? $katalog->produsen . " - " : "" }}{{ $katalog->nama_produk }}</td>
                                                <td>
                                                    <input type="number" step="0.0001" name="crm_sertifikat[{{ $katalog->id }}][cert_value]" class="form-control form-control-sm" value="{{ $sert ? number_format($sert->cert_value, 4, '.', '') : '' }}" placeholder="Contoh: 9.5000">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.0001" name="crm_sertifikat[{{ $katalog->id }}][cert_u]" class="form-control form-control-sm" value="{{ $sert && $sert->cert_u ? number_format($sert->cert_u, 4, '.', '') : '' }}" placeholder="Contoh: 0.0500">
                                                </td>
                                                <td class="text-center align-middle">
                                                    <button type="button" class="btn btn-outline-danger btn-icon btn-delete-crm" data-id="{{ $katalog->id }}" data-lot="{{ $katalog->nomor_lot }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pu-form-actions">
                    <a href="{{ route('parameter-uji.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="submit" class="btn btn-corporate-blue"><i class="fas fa-save"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAddCrm" tabindex="-1" aria-labelledby="modalAddCrmLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header pu-modal-header">
        <h5 class="modal-title" id="modalAddCrmLabel"><i class="fas fa-certificate"></i> Tambah Lot CRM Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formAddCrm">
          <input type="hidden" id="crm_parameter_uji_id" value="{{ $parameterUji->parameter_uji_id }}">

          <div class="mb-2">
            <label class="form-label fw-bold">Nomor Lot / Kode Sampel <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="crm_nomor_lot" required>
          </div>

          <div class="row g-2 mb-2">
              <div class="col-12 col-md-6">
                <label class="form-label fw-bold">Produsen / Brand</label>
                <input type="text" class="form-control" id="crm_produsen" placeholder="Contoh: Alpha Resources">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label fw-bold">Jenis / Tipe Material <span class="text-danger">*</span></label>
                @php
                    $tipeMaterialLabels = [
                        '' => 'Pilih Tipe Material...',
                        'lignite coal standard' => 'Lignite Coal Standard',
                        'sub-bituminous coal standard' => 'Sub-Bituminous Coal Standard',
                        'bituminous coal standard' => 'Bituminous Coal Standard',
                        'lainnya' => 'Lainnya...',
                    ];
                @endphp
                <div class="filter-select" id="selectCrmNamaProduk">
                    <input type="hidden" id="crm_nama_produk" value="">
                    <button type="button" class="filter-select-trigger">Pilih Tipe Material...</button>
                    <ul class="filter-select-options">
                        @foreach($tipeMaterialLabels as $value => $label)
                            <li data-value="{{ $value }}">{{ $label }}</li>
                        @endforeach
                    </ul>
                </div>
              </div>
          </div>

          <div class="row g-2 mb-2">
              <div class="col-12 col-md-6">
                <label class="form-label fw-bold">Nilai Sertifikat (Cert Value)</label>
                <input type="number" step="0.0001" class="form-control" id="crm_cert_value" placeholder="Contoh: 9.5000">
                <small class="text-muted">Untuk parameter ini</small>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label fw-bold">Ketidakpastian (U)</label>
                <input type="number" step="0.0001" class="form-control" id="crm_cert_u" placeholder="Contoh: 0.0500">
              </div>
          </div>

          <div class="mb-1">
            <label class="form-label fw-bold">Tanggal Kadaluarsa (Exp)</label>
            <input type="text" class="form-control flatpickr-date" id="crm_tanggal_expired" placeholder="dd/mm/yyyy" autocomplete="off">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-corporate-blue" id="btnSaveCrm">Simpan CRM</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    flatpickr('#crm_tanggal_expired', {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd/m/Y',
        altInputClass: 'form-control form-control-sm flatpickr-alt',
        allowInput: true,
        disableMobile: true
    });

    if(window.location.hash) {
        var hash = window.location.hash;
        var tabTrigger = document.querySelector('[data-bs-target="' + hash + '"]') || document.querySelector('[href="' + hash + '"]');
        if (tabTrigger) {
            var tab = new bootstrap.Tab(tabTrigger);
            tab.show();
        }
    }

    document.querySelectorAll('.filter-select').forEach(function (wrapper) {
        const trigger = wrapper.querySelector('.filter-select-trigger');
        const hiddenInput = wrapper.querySelector('input[type="hidden"]');
        const options = wrapper.querySelectorAll('.filter-select-options li');

        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            document.querySelectorAll('.filter-select.open').forEach(function (other) {
                if (other !== wrapper) other.classList.remove('open');
            });
            wrapper.classList.toggle('open');
        });

        options.forEach(function (li) {
            li.addEventListener('click', function () {
                hiddenInput.value = li.getAttribute('data-value');
                trigger.textContent = li.textContent;
                options.forEach(function (o) { o.classList.remove('selected'); });
                li.classList.add('selected');
                wrapper.classList.remove('open');
            });
        });
    });
    document.addEventListener('click', function () {
        document.querySelectorAll('.filter-select.open').forEach(function (wrapper) {
            wrapper.classList.remove('open');
        });
    });

    document.querySelectorAll('.btn-delete-crm').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const lot = this.dataset.lot;

            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: `Hapus botol CRM Lot <strong>${lot}</strong> ini? Penghapusan ini bersifat permanen dan akan menghapus nilai sertifikatnya di semua parameter.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (!result.isConfirmed) return;

                fetch(`/parameter-uji/crm-katalog/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Botol CRM berhasil dihapus.',
                            icon: 'success',
                            confirmButtonColor: '#1b3152'
                        }).then(() => window.location.reload());
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat menghapus.',
                            icon: 'error',
                            confirmButtonColor: '#1b3152'
                        });
                    }
                });
            });
        });
    });

    const inputMean = document.getElementById('inputMean');
    const inputSd = document.getElementById('inputSd');
    const calcLcl = document.getElementById('calcLcl');
    const calcUwlBawah = document.getElementById('calcUwlBawah');
    const calcUwlAtas = document.getElementById('calcUwlAtas');
    const calcUcl = document.getElementById('calcUcl');

    const inputAcuan = document.getElementById('inputAcuan');
    const inputBatasBawah = document.getElementById('inputBatasBawah');
    const inputBatasAtas = document.getElementById('inputBatasAtas');

    function calculateLimits() {
        const mean = parseFloat(inputMean.value) || 0;
        const sd = parseFloat(inputSd.value) || 0;

        if (calcLcl) calcLcl.value = (mean - 3 * sd).toFixed(4);
        if (calcUwlBawah) calcUwlBawah.value = (mean - 2 * sd).toFixed(4);
        if (calcUwlAtas) calcUwlAtas.value = (mean + 2 * sd).toFixed(4);
        if (calcUcl) calcUcl.value = (mean + 3 * sd).toFixed(4);

        if (mean !== 0) {
            inputAcuan.value = mean.toFixed(4);
            inputBatasBawah.value = (mean - 3 * sd).toFixed(4);
            inputBatasAtas.value = (mean + 3 * sd).toFixed(4);
        }
    }

    inputMean.addEventListener('input', calculateLimits);
    inputSd.addEventListener('input', calculateLimits);

    const tableBody = document.querySelector('#langkahTable tbody');
    const btnAdd = document.getElementById('btnAddLangkah');
    let stepCount = {{ is_array(old('langkah_kalkulasi', $parameterUji->langkah_kalkulasi ?? [])) ? count(old('langkah_kalkulasi', $parameterUji->langkah_kalkulasi ?? [])) : 0 }};

    btnAdd.addEventListener('click', function() {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="langkah_kalkulasi[${stepCount}][var]" class="form-control form-control-sm" placeholder="Contoh: M2_D1"></td>
            <td><input type="text" name="langkah_kalkulasi[${stepCount}][rumus]" class="form-control form-control-sm" placeholder="Contoh: M1_D1 + A_D1"></td>
            <td class="text-center"><button type="button" class="btn btn-outline-danger btn-icon btn-remove-langkah"><i class="fas fa-trash"></i></button></td>
        `;
        tableBody.appendChild(tr);
        stepCount++;
    });

    if(tableBody) {
        tableBody.addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove-langkah')) {
                e.target.closest('tr').remove();
            }
        });
    }

    const btnSaveCrm = document.getElementById('btnSaveCrm');
    if (btnSaveCrm) {
        btnSaveCrm.addEventListener('click', function() {
            const btn = this;
            const originalText = btn.innerHTML;

            const noLot = document.getElementById('crm_nomor_lot').value;
            const namaProd = document.getElementById('crm_nama_produk').value;
            const paramId = document.getElementById('crm_parameter_uji_id').value;

            if (!noLot || !namaProd) {
                Swal.fire({
                    title: 'Data Belum Lengkap',
                    text: 'Nomor Lot dan Jenis Material wajib diisi!',
                    icon: 'warning',
                    confirmButtonColor: '#1b3152'
                });
                return;
            }
            if (!paramId) {
                Swal.fire({
                    title: 'Belum Bisa Disimpan',
                    text: 'Parameter Uji ID belum tersedia. Harap simpan Parameter Uji terlebih dahulu sebelum menambah Lot CRM.',
                    icon: 'warning',
                    confirmButtonColor: '#1b3152'
                });
                return;
            }

            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;

            const data = {
                _token: '{{ csrf_token() }}',
                nomor_lot: noLot,
                nama_produk: namaProd,
                produsen: document.getElementById('crm_produsen').value,
                tanggal_expired: document.getElementById('crm_tanggal_expired').value,
                cert_value: document.getElementById('crm_cert_value').value,
                cert_u: document.getElementById('crm_cert_u').value,
                parameter_uji_id: paramId
            };

            fetch('{{ route('parameter-uji.store-crm') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'CRM Baru berhasil ditambahkan beserta nilai sertifikatnya. Halaman akan dimuat ulang.',
                        icon: 'success',
                        confirmButtonColor: '#1b3152'
                    }).then(() => window.location.reload());
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: res.message || 'Cek kembali isian Anda.',
                        icon: 'error',
                        confirmButtonColor: '#1b3152'
                    });
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(err => {
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan sistem.',
                    icon: 'error',
                    confirmButtonColor: '#1b3152'
                });
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    }
});
</script>
@endsection