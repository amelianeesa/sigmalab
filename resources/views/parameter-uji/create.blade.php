@extends('layouts.app')
@section('title', 'Tambah Baru - Parameter Uji')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
<style>
    .pu-container { padding-top: 2px; }
    .pu-breadcrumb { font-size: 0.72rem; }
    .pu-title { font-size: 1.1rem; }

    .card-header-custom {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.85rem !important;
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
        color: #1b3152 !important; border: 1px solid #1b3152 !important; background-color: #ffffff !important;
    }
    .btn-outline-corporate:hover,
    .btn-outline-corporate:focus { background-color: #1b3152 !important; color: #ffffff !important; }

    /* ===== Form & modal: ukuran kompak (sama dengan halaman lain) ===== */
    .form-label { font-size: 0.75rem !important; font-weight: 600; margin-bottom: 0.2rem; }
    .form-control, .form-select,
    .form-control-sm, .form-select-sm { font-size: 0.8rem !important; }
    .pu-container .form-control, .pu-container .form-select,
    .modal .form-control, .modal .form-select {
        padding: 0.32rem 0.6rem !important; min-height: 0;
    }
    .pu-container .form-control-sm, .modal .form-control-sm { padding: 0.25rem 0.5rem !important; }
    .form-text, small.text-muted { font-size: 0.7rem !important; }

    .pu-container .btn, .modal .btn { font-size: 0.78rem; padding: 0.32rem 0.8rem; }
    .pu-container .btn-sm, .modal .btn-sm { font-size: 0.72rem; padding: 0.22rem 0.55rem; }
    .pu-action-btn {
        width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.75rem; padding: 0 !important; border-radius: 6px;
    }

    .nav-tabs { flex-wrap: wrap; }
    .nav-tabs .nav-link { font-size: 0.8rem; }

    /* ===== Modal ===== */
    .pu-modal-header { background-color: #1b3152 !important; color: #ffffff !important; padding: 0.5rem 0.9rem; }
    .pu-modal-header .modal-title { font-size: 0.9rem !important; }
    .modal .modal-body { font-size: 0.8rem; }
    .modal .modal-footer { padding: 0.5rem 0.9rem; }

    /* ===== Tabel ===== */
    .pu-table-head th {
        background-color: #1b3152 !important; color: #ffffff !important; border-color: #ffffff !important;
        font-size: 0.72rem !important; vertical-align: middle !important;
    }
    .pu-table td { font-size: 0.75rem !important; vertical-align: middle !important; }

    /* ===== Flatpickr: altInput memakai class sendiri ===== */
    .flatpickr-input { font-size: 0.8rem !important; }
    .flatpickr-calendar { font-size: 0.85rem; }

    /* ===== Dropdown kustom ===== */
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
        padding: 0.32rem 0.6rem;
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
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
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
    .filter-select-options li:hover { background-color: #f1f3f5; }
    .filter-select-options li.selected { background-color: #1b3152; color: #fff; }

    .pu-form-actions .btn {
        font-size: 0.8rem !important;
        padding: 0.4rem 1rem !important;
    }
    .input-group .btn {
        font-size: 0.8rem !important;
        padding: 0.4rem 0.9rem !important;
    }

    @media (max-width: 575.98px) {
        .pu-form-actions { flex-direction: column-reverse; }
        .pu-form-actions a, .pu-form-actions button { width: 100%; text-align: center; }
    }

    @media (max-width: 767.98px) {
        /* tabel lot CRM jadi kartu per baris */
        .pu-stack thead { display: none; }
        .pu-stack, .pu-stack tbody, .pu-stack tr, .pu-stack td { display: block; width: 100%; }
        .pu-stack tbody tr {
            border: 1px solid #dee2e6; border-radius: 8px; margin-bottom: 10px; padding: 6px 12px;
            background: #fff; box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }
        .pu-stack td {
            display: flex; justify-content: space-between; align-items: center; gap: 12px;
            text-align: right; border: 0 !important; padding: 5px 0 !important; background: transparent !important;
        }
        .pu-stack td[data-label]::before {
            content: attr(data-label); font-weight: 600; color: #1b3152; text-align: left; flex-shrink: 0; max-width: 40%;
        }
        .pu-stack td input.form-control { max-width: 60%; }
        .pu-stack td.pu-td-aksi { justify-content: flex-end; border-top: 1px solid #eee !important; margin-top: 4px; padding-top: 8px !important; }
        .pu-stack td.pu-td-aksi::before { display: none; }

        /* tabel langkah rumus: tetap bisa digeser, input tidak menyempit */
        #langkahTable td input.form-control { min-width: 120px; }
    }
</style>

<div class="container-fluid px-2 px-md-4 pu-container">
    <ol class="breadcrumb mb-1 mt-1 pu-breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
        <li class="breadcrumb-item"><a href="{{ route('parameter-uji.index') }}" class="text-decoration-none">Parameter Uji</a></li>
        <li class="breadcrumb-item active">Tambah</li>
    </ol>
    <h4 class="fw-bold text-dark mb-3 pu-title">Tambah Parameter Uji</h4>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header card-header-custom"><i class="fas fa-plus-circle me-2"></i>Form Tambah Parameter Uji</div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger py-2" style="font-size: 0.8rem;">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('parameter-uji.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nama Parameter <span class="text-danger">*</span></label>
                        <input type="text" name="nama_parameter" class="form-control" value="{{ old('nama_parameter') }}" required maxlength="50">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="satuan" class="form-control" value="{{ old('satuan') }}" required maxlength="20">
                    </div>
                </div>

                <ul class="nav nav-tabs mb-4" id="configTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active fw-bold" id="inhouse-tab" data-bs-toggle="tab" href="#inhouse" role="tab"><span class="d-none d-sm-inline">In-House Control & Pengaturan Umum</span><span class="d-sm-none">In-House</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" id="crm-tab" data-bs-toggle="tab" href="#crm" role="tab"><span class="d-none d-sm-inline">Sertifikat Pabrik (CRM)</span><span class="d-sm-none">CRM</span></a>
                    </li>
                </ul>
                <div class="tab-content border-start border-end border-bottom p-3 p-md-4 mb-4" id="configTabsContent" style="margin-top: -25px; background: white;">
                    <div class="tab-pane fade show active" id="inhouse" role="tabpanel">

                <div class="card bg-light mb-3 border-0">
                    <div class="card-body py-2">
                        <p class="mb-2 text-muted fw-bold" style="font-size: 0.8rem;"><i class="fas fa-chart-line"></i> Input Data Statistik (In-House)</p>
                        <div class="alert alert-info py-1 px-2 mb-2" style="font-size: 0.75rem;">
                            <i class="fas fa-info-circle me-1"></i> Nilai <strong>Mean</strong> dan <strong>SD</strong> didapatkan secara otomatis dari hasil <strong>Uji Homogenitas</strong>. Jika dilakukan uji homogenitas ulang, maka nilai-nilai ini akan ikut berubah secara otomatis.
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <label class="form-label fw-bold text-primary">Mean (Rata-rata)</label>
                                <input type="number" step="0.0001" name="mean" id="inputMean" class="form-control" value="{{ old('mean') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-primary">SD (Standard Deviation)</label>
                                <input type="number" step="0.0001" name="sd" id="inputSd" class="form-control" value="{{ old('sd') }}">
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6 col-md">
                                <label class="form-label text-muted">LCL (-3 SD)</label>
                                <input type="text" id="calcLcl" name="lcl" class="form-control form-control-sm bg-white" readonly value="{{ old('lcl') }}">
                            </div>
                            <div class="col-6 col-md">
                                <label class="form-label text-muted">LWL (-2 SD)</label>
                                <input type="text" id="calcUwlBawah" name="uwl_bawah" class="form-control form-control-sm bg-white" readonly value="{{ old('uwl_bawah') }}">
                            </div>
                            <div class="col-6 col-md">
                                <label class="form-label text-muted">UWL (+2 SD)</label>
                                <input type="text" id="calcUwlAtas" name="uwl_atas" class="form-control form-control-sm bg-white" readonly value="{{ old('uwl_atas') }}">
                            </div>
                            <div class="col-6 col-md">
                                <label class="form-label text-muted">UCL (+3 SD)</label>
                                <input type="text" id="calcUcl" name="ucl" class="form-control form-control-sm bg-white" readonly value="{{ old('ucl') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Nilai Acuan <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" name="nilai_acuan" id="inputAcuan" class="form-control" value="{{ old('nilai_acuan') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Batas Bawah <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" name="batas_bawah" id="inputBatasBawah" class="form-control" value="{{ old('batas_bawah') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Batas Atas <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" name="batas_atas" id="inputBatasAtas" class="form-control" value="{{ old('batas_atas') }}" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Metode / Kriteria</label>
                        <input type="text" name="metode_kriteria" class="form-control" value="{{ old('metode_kriteria') }}" maxlength="50">
                        <div class="form-text">Contoh: SNI 01-2891-1992</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Rumus Kalkulasi Nilai Akhir</label>
                        <div class="input-group">
                            <input type="text" name="rumus_kalkulasi" class="form-control" value="{{ old('rumus_kalkulasi') }}">
                            <button class="btn btn-outline-corporate btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#modalLangkahKalkulasi">
                                <i class="fas fa-list-ol"></i> Detail Rumus
                            </button>
                        </div>
                        <div class="form-text">Contoh: CUSTOM_TS, CUSTOM_VM, CUSTOM_IM, atau formula (M_D1 + M_D2) / 2</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-danger">Toleransi Duplo (%)</label>
                        <input type="text" name="toleransi_duplo" class="form-control" value="{{ old('toleransi_duplo') }}">
                        <div class="form-text text-danger">Gunakan angka statis (contoh: 1.5) atau formula matematika.</div>
                    </div>
                </div>

                <!-- Modal Langkah Kalkulasi -->
                <div class="modal fade" id="modalLangkahKalkulasi" tabindex="-1" aria-labelledby="modalLangkahKalkulasiLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header pu-modal-header">
                                <h5 class="modal-title" id="modalLangkahKalkulasiLabel"><i class="fas fa-list-ol me-2"></i> Detail Langkah Kalkulasi Per Kolom</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted small">Jika Anda mengisi langkah kalkulasi di bawah ini, mesin akan menghitung variabel secara berurutan sebelum mencapai Nilai Akhir. Sangat berguna untuk perhitungan yang bertahap (multi-step).</p>
                                <div class="table-responsive mb-2">
                                    <table class="table table-bordered table-sm pu-table" id="langkahTable">
                                        <thead class="pu-table-head">
                                            <tr>
                                                <th width="35%">Nama Variabel Output</th>
                                                <th width="55%">Rumus Matematika</th>
                                                <th width="10%" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $langkahs = old('langkah_kalkulasi', []); @endphp
                                            @if(is_array($langkahs) && count($langkahs) > 0)
                                                @foreach($langkahs as $index => $langkah)
                                                <tr>
                                                    <td><input type="text" name="langkah_kalkulasi[{{$index}}][var]" class="form-control form-control-sm" value="{{ $langkah['var'] ?? '' }}" placeholder="Contoh: M2_D1"></td>
                                                    <td><input type="text" name="langkah_kalkulasi[{{$index}}][rumus]" class="form-control form-control-sm" value="{{ $langkah['rumus'] ?? '' }}" placeholder="Contoh: M1_D1 + A_D1"></td>
                                                    <td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-remove-langkah"><i class="fas fa-trash"></i></button></td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-sm btn-corporate-blue shadow-sm" id="btnAddLangkah"><i class="fas fa-plus"></i> Tambah Baris Rumus</button>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                    </div> <!-- end inhouse tab -->

                    <div class="tab-pane fade" id="crm" role="tabpanel">
                        <p class="mb-3 text-muted fw-bold" style="font-size: 0.8rem;"><i class="fas fa-certificate"></i> Pengaturan Nilai Sertifikat per Lot CRM</p>
                        <div class="alert alert-warning py-1 px-2 mb-3" style="font-size: 0.75rem;">
                            <span>Anda dapat mengisi nilai Sertifikat (Cert Value) dan Uncertainty (U) untuk masing-masing kode Lot CRM yang saat ini aktif. Nilai ini akan digunakan untuk mengevaluasi akurasi hasil uji.</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h6 class="m-0 fw-semibold text-secondary" style="font-size: 0.85rem;">Daftar Botol / Lot CRM</h6>
                            <button type="button" class="btn btn-sm btn-corporate-blue shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalAddCrm">
                                <i class="fas fa-plus"></i> Tambah Lot CRM
                            </button>
                        </div>

                        @if(isset($katalogCrmList) && $katalogCrmList->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered pu-table pu-stack">
                                    <thead class="pu-table-head">
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
                                            <tr>
                                                <td data-label="Kode Lot" class="align-middle fw-bold">{{ $katalog->nomor_lot }}</td>
                                                <td data-label="Produsen / Jenis" class="align-middle">{{ $katalog->produsen ? $katalog->produsen . " - " : "" }}{{ $katalog->nama_produk }}</td>
                                                <td data-label="Cert Value">
                                                    <input type="number" step="0.0001" name="crm_sertifikat[{{ $katalog->id }}][cert_value]" class="form-control form-control-sm" placeholder="Contoh: 9.5000" inputmode="decimal">
                                                </td>
                                                <td data-label="Uncertainty (U)">
                                                    <input type="number" step="0.0001" name="crm_sertifikat[{{ $katalog->id }}][cert_u]" class="form-control form-control-sm" placeholder="Contoh: 0.0500" inputmode="decimal">
                                                </td>
                                                <td class="text-center align-middle pu-td-aksi">
                                                    <button type="button" class="btn btn-danger btn-sm pu-action-btn shadow-sm btn-delete-crm" data-id="{{ $katalog->id }}" data-lot="{{ $katalog->nomor_lot }}" title="Hapus" aria-label="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-3" style="font-size: 0.8rem;">
                                <i class="fas fa-box-open fs-3 d-block mb-2 opacity-25"></i>
                                Belum ada Lot CRM. Klik "Tambah Lot CRM" untuk menambahkan.
                            </div>
                        @endif
                    </div> <!-- end crm tab -->
                </div> <!-- end tab content -->

                <div class="d-flex justify-content-end gap-2 pu-form-actions">
                    <a href="{{ route('parameter-uji.index') }}" class="btn btn-secondary btn-sm px-3 py-2"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="submit" class="btn btn-corporate-blue btn-sm px-3 py-2 shadow-sm fw-semibold"><i class="fas fa-save"></i> Simpan Parameter</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Tambah CRM -->
<div class="modal fade" id="modalAddCrm" tabindex="-1" aria-labelledby="modalAddCrmLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header pu-modal-header">
        <h5 class="modal-title" id="modalAddCrmLabel"><i class="fas fa-certificate"></i> Tambah Lot CRM Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formAddCrm">
          <input type="hidden" id="crm_parameter_uji_id" value="">

          <div class="mb-3">
            <label class="form-label fw-bold">Nomor Lot / Kode Sampel <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="crm_nomor_lot" required>
          </div>

          <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Produsen / Brand</label>
                <input type="text" class="form-control" id="crm_produsen" placeholder="Contoh: Alpha Resources">
              </div>
              <div class="col-md-6 mb-3">
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

          <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Nilai Sertifikat (Cert Value)</label>
                <input type="number" step="0.0001" class="form-control" id="crm_cert_value" placeholder="Contoh: 9.5000" inputmode="decimal">
                <small class="text-muted">Untuk parameter ini</small>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Ketidakpastian (U)</label>
                <input type="number" step="0.0001" class="form-control" id="crm_cert_u" placeholder="Contoh: 0.0500" inputmode="decimal">
              </div>
          </div>

          <div class="mb-1">
            <label class="form-label fw-bold">Tanggal Kadaluarsa (Exp)</label>
            <input type="text" class="form-control flatpickr-date" id="crm_tanggal_expired" placeholder="dd/mm/yyyy" autocomplete="off">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-corporate-blue btn-sm fw-semibold" id="btnSaveCrm">Simpan CRM</button>
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
        // altInput bawaan memakai class "form-control input" (ukuran besar), diganti agar kompak
        altInputClass: 'form-control form-control-sm flatpickr-alt',
        allowInput: true,
        disableMobile: true
    });

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
            if (confirm(`Apakah Anda yakin ingin menghapus botol CRM Lot ${lot} ini? Penghapusan ini bersifat permanen dan akan menghapus nilai sertifikatnya di semua parameter.`)) {
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
                        alert('Botol CRM berhasil dihapus.');
                        window.location.reload();
                    } else {
                        alert('Gagal menghapus: ' + data.message);
                    }
                });
            }
        });
    });

    const inputMean = document.getElementById('inputMean');
    const inputSd = document.getElementById('inputSd');

    // Langkah Kalkulasi Dynamic Form
    const tableBody = document.querySelector('#langkahTable tbody');
    const btnAdd = document.getElementById('btnAddLangkah');
    let stepCount = {{ is_array(old('langkah_kalkulasi')) ? count(old('langkah_kalkulasi')) : 0 }};

    btnAdd.addEventListener('click', function() {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="langkah_kalkulasi[${stepCount}][var]" class="form-control form-control-sm" placeholder="Contoh: M2_D1"></td>
            <td><input type="text" name="langkah_kalkulasi[${stepCount}][rumus]" class="form-control form-control-sm" placeholder="Contoh: M1_D1 + A_D1"></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-remove-langkah"><i class="fas fa-trash"></i></button></td>
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
                alert('Nomor Lot dan Jenis Material wajib diisi!');
                return;
            }
            if (!paramId) {
                alert('Parameter Uji ID belum tersedia. Harap simpan Parameter Uji terlebih dahulu sebelum menambah Lot CRM.');
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
                    alert('CRM Baru berhasil ditambahkan beserta nilai sertifikatnya! Halaman akan dimuat ulang.');
                    window.location.reload();
                } else {
                    alert('Gagal menambahkan CRM: ' + (res.message || 'Cek kembali isian Anda.'));
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(err => {
                alert('Terjadi kesalahan sistem.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    }
});
</script>
@endsection