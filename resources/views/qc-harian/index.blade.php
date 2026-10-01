@extends('layouts.app')
@section('title', 'Pengujian Harian QC')

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

    .page-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 0;
    }

    .icon-corporate {
        color: #1b3152 !important;
    }

    .card-body {
        padding: 10px !important;
    }

    .table th, .table td {
        padding: 8px 10px !important;
        vertical-align: middle !important;
        font-size: 0.72rem !important;
    }

    .table thead th {
        font-size: 0.75rem !important;
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-color: #ffffff !important;
        text-align: center !important;
    }

    .table thead th.bg-warning {
        background-color: #2f5185 !important;
    }

    .table td.bg-warning {
        background-color: #eaf0f8 !important;
    }

    .table .text-primary {
        color: #1b3152 !important;
    }

    .table-bordered > :not(caption) > * > * {
        border-color: #dee2e6;
    }

    .table-corporate thead th {
        border-bottom: 2px solid #0f1c2f !important;
    }

    .tbl-param,
    .tbl-riwayat {
        min-width: 820px;
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
        background-color: #ffffff;
    }

    .btn-outline-corporate:hover,
    .btn-outline-corporate:focus,
    .btn-outline-corporate:active {
        background-color: #1b3152 !important;
        color: #ffffff !important;
    }

    .dashboard-container .btn,
    .modal .btn {
        font-size: 0.78rem;
    }

    .aksi-btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem !important;
    }

    .dashboard-container .form-control,
    .dashboard-container .form-select,
    .modal .form-control,
    .modal .form-select {
        font-size: 0.78rem;
        padding-top: 0.28rem;
        padding-bottom: 0.28rem;
        color: #000000;
        border-color: #ced4da;
    }

    .dashboard-container .form-control:focus,
    .dashboard-container .form-select:focus,
    .modal .form-control:focus,
    .modal .form-select:focus {
        border-color: #1b3152;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
    }

    .form-label {
        font-size: 0.74rem;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .form-check-label {
        font-size: 0.78rem;
    }

    .form-check-input:checked {
        background-color: #1b3152;
        border-color: #1b3152;
    }

    .form-check-input:focus {
        border-color: #1b3152;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
    }

    .dashboard-container .alert {
        font-size: 0.75rem;
        padding: 0.4rem 0.7rem;
        border-radius: 6px;
    }

    .dashboard-container .badge,
    .modal .badge {
        font-size: 0.68rem;
    }

    .filter-bulan { width: 140px; }
    .filter-tahun { width: 120px; }

    .modal-content {
        border: 0;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.18);
        font-size: 0.8rem;
        overflow: hidden;
    }

    .modal-header.modal-corporate {
        background-color: #1b3152;
        color: #ffffff;
        padding: 0.55rem 0.9rem;
    }

    .modal-header.modal-corporate .modal-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: #ffffff;
    }

    .modal-footer {
        padding: 0.5rem 0.75rem;
    }

    .swal2-popup {
        font-size: 0.82rem !important;
        border-radius: 8px !important;
    }

    .swal2-title {
        font-size: 1rem !important;
        font-weight: 700 !important;
    }

    .swal2-html-container {
        font-size: 0.78rem !important;
    }

    .swal2-popup.swal2-toast .swal2-title {
        font-size: 0.8rem !important;
    }

    .swal2-styled {
        font-size: 0.78rem !important;
        border-radius: 0.375rem !important;
        padding: 0.4rem 1rem !important;
    }

    .pulse-button {
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info {
        font-size: 0.75rem;
    }

    .dataTables_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
    }

    .dataTables_wrapper .dataTables_filter input {
        width: auto;
        display: inline-block;
    }

    .dataTables_wrapper .dataTables_paginate {
        margin-top: 12px !important;
        float: right;
    }

    .dataTables_wrapper .pagination {
        border: 1px solid #d4d4d4;
        border-radius: 6px;
        padding: 0;
        margin: 0;
        display: inline-flex;
        align-items: center;
        background-color: #fff;
        overflow: hidden;
    }

    .dataTables_wrapper .page-item .page-link {
        border: none;
        color: #555;
        font-size: 0.72rem;
        font-weight: 500;
        background: transparent;
        margin: 0;
        padding: 0.3rem 0.7rem;
        box-shadow: none !important;
        border-radius: 0;
    }

    .dataTables_wrapper .page-item .page-link:hover {
        background-color: rgba(27, 49, 82, 0.1);
        color: #1b3152;
    }

    .dataTables_wrapper .page-item.active .page-link {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        font-weight: 700;
        border-radius: 0;
    }

    .dataTables_wrapper .page-item.disabled .page-link {
        color: #aaa;
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

        .page-title {
            font-size: 1rem;
        }

        .header-actions,
        .header-actions .btn,
        .batch-actions .btn {
            width: 100%;
        }

        .filter-wrap {
            width: 100%;
        }

        .filter-bulan,
        .filter-tahun {
            width: 100%;
            flex: 1 1 0;
        }

        .modal .form-control,
        .modal .form-select {
            font-size: 16px;
            min-height: 40px;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info {
            text-align: center !important;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: none;
            text-align: center;
        }
    }
</style>

<div class="container-fluid dashboard-container pb-4" style="font-size: 0.82rem;">
    <x-qc-breadcrumb active="In-House">
        @if($activeBatch)
            <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $activeBatch->sampel_inhouse_id) }}" class="text-decoration-none">{{ $activeBatch->kode_batch }}</a></li>
        @endif
        <li class="breadcrumb-item active">Pengujian Harian QC</li>
    </x-qc-breadcrumb>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h5 class="page-title text-dark">
            <i class="fas fa-chart-line icon-corporate me-2"></i>Pengujian Harian QC
        </h5>

        <div class="header-actions d-grid d-md-flex">
            <a href="{{ route('parameter-uji.index') }}" class="btn btn-outline-corporate btn-sm py-1 px-3 shadow-sm fw-semibold">
                <i class="fas fa-cogs me-1"></i> Master Parameter Uji
            </a>
        </div>
    </div>

    @if(!$activeBatch)
    <div class="alert alert-warning border-0 shadow-sm">
        <i class="fas fa-exclamation-triangle me-2"></i> Belum ada Batch QC In-House yang berstatus <strong>Aktif</strong>. Silakan selesaikan proses Uji Stabilitas terlebih dahulu.
    </div>
    @else

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body bg-light rounded">
            <div class="row align-items-center g-2">

                <div class="col-12 col-md-6">
                    <h6 class="fw-bold mb-1" style="font-size: 0.92rem;">Batch Aktif: {{ $activeBatch->kode_batch }}</h6>
                    <p class="text-muted mb-0" style="font-size: 0.72rem;">Masa Berlaku: {{ $activeBatch->tanggal_dibuat ? \Carbon\Carbon::parse($activeBatch->tanggal_dibuat)->format('d M Y') : '-' }} s/d Selesai</p>
                </div>

                <div class="col-12 col-md-6">
                    <div class="batch-actions d-flex flex-column flex-md-row justify-content-start justify-content-md-end gap-2">
                        <button type="button" class="btn btn-outline-corporate btn-sm py-1 px-3 shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalCetakPdf">
                            <i class="fas fa-file-pdf me-1"></i> Cetak Laporan
                        </button>
                        <a href="{{ route('qc-harian.create') }}" class="btn btn-corporate-blue btn-sm py-1 px-3 shadow-sm fw-semibold">
                            <i class="fas fa-plus me-1"></i> Input Data Harian
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-2">
            <h6 class="section-title text-dark"><i class="fas fa-list icon-corporate me-2"></i>Daftar Parameter Uji Harian</h6>
        </div>
        <div class="card-body p-0">
            <div class="px-2 pt-2 d-md-none text-muted" style="font-size: 0.68rem;">
                <i class="fas fa-arrows-alt-h me-1"></i> Geser tabel ke kiri/kanan untuk melihat seluruh kolom.
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-corporate align-middle mb-0 tbl-param">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Nama Parameter</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center">Nilai Acuan (Mean)</th>
                            <th class="text-center">Batas Peringatan (± 2SD)</th>
                            <th class="text-center">Metode/Kriteria</th>
                            <th class="text-center">Status Harian</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parameters as $idx => $param)
                            @php
                                $isLocked = \App\Models\QcHarian::where('sampel_inhouse_id', $activeBatch->id)
                                    ->where('parameter_uji_id', $param->parameter_uji_id)
                                    ->where('status_evaluasi', 'outlier')
                                    ->where('status_investigasi', 'menunggu_investigasi')
                                    ->exists();
                            @endphp
                            <tr>
                                <td class="text-center">{{ $idx + 1 }}</td>
                                <td class="fw-bold">{{ strtoupper($param->parameterUji->nama_parameter) }}</td>
                                <td class="text-center">{{ $param->parameterUji->satuan ?? '-' }}</td>
                                <td class="text-center">{{ number_format($param->parameterUji->mean, 4) }}</td>
                                <td class="text-center text-nowrap">
                                    {{ number_format($param->parameterUji->mean - (2 * $param->parameterUji->sd), 4) }} -
                                    {{ number_format($param->parameterUji->mean + (2 * $param->parameterUji->sd), 4) }}
                                </td>
                                <td class="text-center">{{ $param->parameterUji->metode_kriteria ?? '-' }}</td>
                                <td class="text-center">
                                    @if($isLocked)
                                        <span class="badge bg-danger"><i class="fas fa-lock me-1"></i> OOC (LOCKED)</span>
                                    @else
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> AMAN</span>
                                    @endif
                                </td>
                                <td class="text-center text-nowrap">
                                    @if($isLocked)
                                        <a href="#riwayat-table" class="btn btn-sm btn-outline-danger aksi-btn shadow-sm" title="Lakukan Investigasi" onclick="Swal.fire({toast: true, position: 'top-end', icon: 'info', title: 'Silakan cek tabel riwayat di bawah untuk mengisi form investigasi.', showConfirmButton: false, timer: 3000})"><i class="fas fa-search me-1"></i> Investigasi</a>
                                    @else
                                        <a href="{{ route('qc-harian.chart', $param->parameter_uji_id) }}" class="btn btn-corporate-blue btn-sm aksi-btn shadow-sm" title="Control Chart"><i class="fas fa-chart-line me-1"></i> Control Chart</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">Belum ada parameter yang stabil.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <h6 class="section-title text-dark"><i class="fas fa-history icon-corporate me-2"></i>Riwayat Pengujian</h6>
        <div class="filter-wrap d-flex gap-2">
            <select id="filterBulan" class="form-select form-select-sm filter-bulan">
                <option value="">Semua Bulan</option>
                <option value="Jan">Januari</option>
                <option value="Feb">Februari</option>
                <option value="Mar">Maret</option>
                <option value="Apr">April</option>
                <option value="May">Mei</option>
                <option value="Jun">Juni</option>
                <option value="Jul">Juli</option>
                <option value="Aug">Agustus</option>
                <option value="Sep">September</option>
                <option value="Oct">Oktober</option>
                <option value="Nov">November</option>
                <option value="Dec">Desember</option>
            </select>
            <select id="filterTahun" class="form-select form-select-sm filter-tahun">
                <option value="">Semua Tahun</option>
                @php
                    $startYear = date('Y') - 2;
                    $endYear = date('Y') + 1;
                @endphp
                @for($y = $startYear; $y <= $endYear; $y++)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body p-2">
            <div class="px-1 pb-1 d-md-none text-muted" style="font-size: 0.68rem;">
                <i class="fas fa-arrows-alt-h me-1"></i> Geser tabel ke kiri/kanan untuk melihat seluruh kolom.
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-corporate align-middle mb-0 tbl-riwayat" id="tableRiwayat">
                    <thead>
                        <tr>
                            <th class="ps-3">Tanggal</th>
                            <th>Parameter</th>
                            <th>Nilai D1</th>
                            <th>Nilai D2</th>
                            <th>Nilai Akhir</th>
                            <th>Status (Westgard)</th>
                            <th>Analis</th>
                            <th class="pe-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLogs as $log)
                            <tr>
                                <td class="ps-3 text-nowrap">{{ \Carbon\Carbon::parse($log->tanggal_uji)->format('d M Y') }}</td>
                                <td class="fw-bold">
                                    {{ strtoupper($log->parameterUji->nama_parameter) }}
                                    @if($log->status_pengujian === 'draft')
                                        <span class="badge bg-secondary ms-1">DRAFT</span>
                                    @endif
                                </td>
                                <td>{{ number_format($log->nilai_d1, 2) }}</td>
                                <td>{{ $log->nilai_d2 ? number_format($log->nilai_d2, 2) : '-' }}</td>
                                <td class="fw-bold">
                                    {{ $log->status_pengujian === 'draft' ? '-' : number_format($log->nilai_akhir, 2) }}
                                </td>
                                <td>
                                    @if($log->status_pengujian === 'draft')
                                        <span class="badge bg-light text-secondary border"><i class="fas fa-pencil-alt me-1"></i> Belum Selesai</span>
                                    @else
                                        @if($log->status_evaluasi === 'inlier')
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i> Inlier</span>
                                        @elseif($log->status_evaluasi === 'warning')
                                            <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i> Warning</span>
                                            <small class="d-block text-muted mt-1">{{ $log->pelanggaran_rule }}</small>
                                        @elseif($log->status_evaluasi === 'outlier')
                                            <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Outlier</span>
                                            <small class="d-block text-danger mt-1 fw-bold">{{ $log->pelanggaran_rule }}</small>
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $log->analis ? $log->analis->username : '-' }}</td>
                                <td class="pe-3 text-center text-nowrap">
                                    @if($log->status_pengujian === 'draft')

                                        <a href="{{ route('qc-harian.draft.edit', $log->id) }}" class="btn btn-sm btn-warning aksi-btn me-1 shadow-sm" title="Lanjutkan Draft">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>

                                        <form action="{{ route('qc-harian.draft.destroy', $log->id) }}" method="POST" class="d-inline form-delete-draft">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger aksi-btn shadow-sm" title="Hapus Draft">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @else

                                        <button type="button" class="btn btn-sm btn-corporate-blue aksi-btn shadow-sm btn-detail-qc"
                                                data-tanggal="{{ \Carbon\Carbon::parse($log->tanggal_uji)->format('d M Y') }}"
                                                data-parameter="{{ strtoupper($log->parameterUji->nama_parameter) }}"
                                                data-analis="{{ $log->analis ? $log->analis->username : '-' }}"
                                                data-d1="{{ number_format($log->nilai_d1, 2) }}"
                                                data-d2="{{ $log->nilai_d2 ? number_format($log->nilai_d2, 2) : '-' }}"
                                                data-akhir="{{ number_format($log->nilai_akhir, 2) }}"
                                                data-db1="{{ $log->nilai_db_1 ? number_format($log->nilai_db_1, 4) : '-' }}"
                                                data-db2="{{ $log->nilai_db_2 ? number_format($log->nilai_db_2, 4) : '-' }}"
                                                data-mean="{{ number_format($log->mean_acuan, 4) }}"
                                                data-sd="{{ number_format($log->sd_acuan, 4) }}"
                                                data-pelanggaran="{{ $log->pelanggaran_rule }}"
                                                title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

<div class="modal fade" id="modalDetailQcHarian" tabindex="-1" aria-labelledby="modalDetailQcHarianLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header modal-corporate">
                <h5 class="modal-title" id="modalDetailQcHarianLabel"><i class="fas fa-info-circle me-2"></i>Detail Pengujian Harian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light text-dark" style="width: 35%; text-align: left !important;">Tanggal Pengujian</th>
                                <td id="detTanggal"></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-dark" style="text-align: left !important;">Parameter</th>
                                <td id="detParameter" class="fw-bold"></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-dark" style="text-align: left !important;">Analis</th>
                                <td id="detAnalis"></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-dark" style="text-align: left !important;">Nilai D1</th>
                                <td id="detD1"></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-dark" style="text-align: left !important;">Nilai D2</th>
                                <td id="detD2"></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-dark" style="text-align: left !important;">Average</th>
                                <td id="detAkhir" class="fw-bold"></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-dark" style="text-align: left !important;">Mean</th>
                                <td id="detMean"></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-dark" style="text-align: left !important;">Standar Deviasi</th>
                                <td id="detSD"></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-dark" style="text-align: left !important;">Status Evaluasi (Westgard)</th>
                                <td id="detStatus"></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-dark" style="text-align: left !important;">Pelanggaran Rule</th>
                                <td id="detRule" class="text-danger fw-bold"></td>
                            </tr>
                            <tr>
                                <th class="bg-light text-dark" style="text-align: left !important;">Catatan Investigasi</th>
                                <td id="detInvestigasi"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h6 class="section-title text-dark mt-3 mb-2"><i class="fas fa-calculator icon-corporate me-2"></i>Tabel Kalkulasi Pengujian</h6>
                <div id="detTableKalkulasi" class="table-responsive"></div>

            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm px-3 fw-semibold" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCetakPdf" tabindex="-1" aria-labelledby="modalCetakPdfLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down">
        <form action="{{ route('qc-harian.print-pdf') }}" method="GET" target="_blank" class="modal-content">
            <div class="modal-header modal-corporate">
                <h5 class="modal-title" id="modalCetakPdfLabel"><i class="fas fa-file-pdf me-2"></i> Cetak Laporan Pengujian Harian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">

                <div class="row g-2 mb-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Pilih Bulan</label>
                        <select name="bulan" class="form-select form-select-sm" required>
                            @php
                                $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                $currentMonth = date('n');
                            @endphp
                            @foreach($months as $idx => $mName)
                                <option value="{{ $idx + 1 }}" {{ $currentMonth == ($idx + 1) ? 'selected' : '' }}>{{ $mName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Pilih Tahun</label>
                        <select name="tahun" class="form-select form-select-sm" required>
                            @php
                                $currentYear = date('Y');
                            @endphp
                            @for($y = $currentYear; $y >= $currentYear - 2; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Bagian yang Ingin Dicetak</label>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" name="cetak_riwayat" value="1" id="cRiwayat" checked>
                        <label class="form-check-label" for="cRiwayat">Riwayat Data Pengujian Harian (Tabel Rekapan)</label>
                    </div>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox" name="cetak_chart" value="1" id="cChart" checked>
                        <label class="form-check-label" for="cChart">Control Chart (Diagram Grafik & Tabel Kalkulasi Limit)</label>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label">Pilih Parameter</label>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="radio" name="param_type" id="pSemua" value="all" checked onchange="toggleParamCheckboxes(false)">
                        <label class="form-check-label" for="pSemua">Semua Parameter Aktif</label>
                    </div>
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="radio" name="param_type" id="pKhusus" value="specific" onchange="toggleParamCheckboxes(true)">
                        <label class="form-check-label" for="pKhusus">Pilih Parameter Spesifik...</label>
                    </div>

                    <div id="paramCheckboxes" class="mt-2 p-2 bg-light rounded d-none border">
                        <div class="row g-1">
                            @foreach($parameters as $param)
                                <div class="col-6 col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input cb-param" type="checkbox" name="param_ids[]" value="{{ $param->parameter_uji_id }}" id="chk_param_{{ $param->parameter_uji_id }}">
                                        <label class="form-check-label" for="chk_param_{{ $param->parameter_uji_id }}">
                                            {{ strtoupper($param->parameterUji->nama_parameter) }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm px-3 fw-semibold" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-corporate-blue btn-sm px-3 fw-semibold"><i class="fas fa-print me-1"></i> Tampilkan & Cetak</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleParamCheckboxes(show) {
    const container = document.getElementById('paramCheckboxes');
    if (show) {
        container.classList.remove('d-none');
        document.querySelectorAll('.cb-param').forEach(cb => cb.required = true);
    } else {
        container.classList.add('d-none');
        document.querySelectorAll('.cb-param').forEach(cb => cb.required = false);
    }
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-detail-qc').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('detTanggal').innerText = this.getAttribute('data-tanggal');
            document.getElementById('detParameter').innerText = this.getAttribute('data-parameter');
            document.getElementById('detAnalis').innerText = this.getAttribute('data-analis');
            document.getElementById('detD1').innerText = this.getAttribute('data-d1');
            document.getElementById('detD2').innerText = this.getAttribute('data-d2');
            document.getElementById('detAkhir').innerText = this.getAttribute('data-akhir');
            document.getElementById('detMean').innerText = this.getAttribute('data-mean');
            document.getElementById('detSD').innerText = this.getAttribute('data-sd');

            let status = this.getAttribute('data-status');
            let statusHtml = '';
            if (status === 'inlier') {
                statusHtml = '<span class="badge bg-success"><i class="fas fa-check me-1"></i> Inlier</span>';
            } else if (status === 'warning') {
                statusHtml = '<span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i> Warning</span>';
            } else if (status === 'outlier') {
                statusHtml = '<span class="badge bg-danger"><i class="fas fa-times me-1"></i> Outlier</span>';
            }
            document.getElementById('detStatus').innerHTML = statusHtml;

            document.getElementById('detRule').innerText = this.getAttribute('data-rule');
            document.getElementById('detInvestigasi').innerText = this.getAttribute('data-investigasi');

            const code = this.getAttribute('data-parameter').toUpperCase();
            let mentahRaw = this.getAttribute('data-mentah');
            let m = {};
            try {
                if (mentahRaw) m = JSON.parse(mentahRaw);
            } catch (e) {}

            function num(val) {
                let n = parseFloat(val);
                return isNaN(n) ? 0 : n;
            }
            function fmt(val, dec = 4) {
                let n = parseFloat(val);
                return isNaN(n) ? '-' : n.toFixed(dec);
            }

            let d1 = num(this.getAttribute('data-d1'));
            let d2Str = this.getAttribute('data-d2');
            let hasD2 = d2Str !== '-';
            let d2 = hasD2 ? num(d2Str) : 0;
            let avgD = hasD2 ? (d1 + d2) / 2 : d1;
            let diffD = hasD2 ? Math.abs(d1 - d2) : 0;

            let db1 = num(this.getAttribute('data-db1'));
            let db2Str = this.getAttribute('data-db2');
            let hasDb2 = db2Str !== '-';
            let db2 = hasDb2 ? num(db2Str) : 0;
            let avgDb = (hasDb2 && db1>0) ? (db1 + db2) / 2 : db1;

            let thead = '';
            let tbody1 = '';
            let tbody2 = '';

            if (code === 'IM') {
                let m2_1 = num(m.m1_1) + num(m.a_1);
                let b_1 = m2_1 - num(m.m3_1);
                let m2_2 = num(m.m1_2) + num(m.a_2);
                let b_2 = m2_2 - num(m.m3_2);

                thead = `<tr><th>PENGULANGAN</th><th>M1</th><th>M2</th><th>M3</th><th>A</th><th>B</th><th class="bg-warning bg-opacity-25">M%</th><th>DIFF</th><th>AVG %</th></tr>`;

                tbody1 = `<tr>
                    <td class="fw-bold bg-light">Simplo (D1)</td>
                    <td>${fmt(m.m1_1)}</td><td>${fmt(m2_1)}</td><td>${fmt(m.m3_1)}</td><td>${fmt(m.a_1)}</td><td>${fmt(b_1)}</td>
                    <td class="bg-warning bg-opacity-10 fw-bold text-primary">${fmt(d1, 2)}</td>
                    <td rowspan="2" class="align-middle fw-bold">${hasD2 ? fmt(diffD, 2) : '-'}</td>
                    <td rowspan="2" class="align-middle fw-bold">${hasD2 ? fmt(avgD, 2) : fmt(d1, 2)}</td>
                </tr>`;

                if (hasD2) {
                    tbody2 = `<tr>
                        <td class="fw-bold bg-light">Duplo (D2)</td>
                        <td>${fmt(m.m1_2)}</td><td>${fmt(m2_2)}</td><td>${fmt(m.m3_2)}</td><td>${fmt(m.a_2)}</td><td>${fmt(b_2)}</td>
                        <td class="bg-warning bg-opacity-10 fw-bold text-primary">${fmt(d2, 2)}</td>
                    </tr>`;
                }
            }
            else if (code === 'ASH') {
                let m2_1 = num(m.m1_1) + num(m.m2m1_1);
                let m3m1_1 = num(m.m3_1) - num(m.m1_1);
                let m2_2 = num(m.m1_2) + num(m.m2m1_2);
                let m3m1_2 = num(m.m3_2) - num(m.m1_2);

                thead = `<tr><th>PENGULANGAN</th><th>M1</th><th>M2</th><th>M2-M1</th><th>M3</th><th>M3-M1</th><th class="bg-warning bg-opacity-25">ASH%</th><th>DIFF</th><th>AVG %adb</th><th>%db</th></tr>`;

                tbody1 = `<tr>
                    <td class="fw-bold bg-light">Simplo (D1)</td>
                    <td>${fmt(m.m1_1)}</td><td>${fmt(m2_1)}</td><td>${fmt(m.m2m1_1)}</td><td>${fmt(m.m3_1)}</td><td>${fmt(m3m1_1)}</td>
                    <td class="bg-warning bg-opacity-10 fw-bold text-primary">${fmt(d1, 2)}</td>
                    <td rowspan="2" class="align-middle fw-bold">${hasD2 ? fmt(diffD, 2) : '-'}</td>
                    <td rowspan="2" class="align-middle fw-bold">${hasD2 ? fmt(avgD, 2) : fmt(d1, 2)}</td>
                    <td class="fw-bold text-success">${db1 > 0 ? fmt(db1, 2) : '-'}</td>
                </tr>`;

                if (hasD2) {
                    tbody2 = `<tr>
                        <td class="fw-bold bg-light">Duplo (D2)</td>
                        <td>${fmt(m.m1_2)}</td><td>${fmt(m2_2)}</td><td>${fmt(m.m2m1_2)}</td><td>${fmt(m.m3_2)}</td><td>${fmt(m3m1_2)}</td>
                        <td class="bg-warning bg-opacity-10 fw-bold text-primary">${fmt(d2, 2)}</td>
                        <td class="fw-bold text-success">${db2 > 0 ? fmt(db2, 2) : '-'}</td>
                    </tr>`;
                }
            }
            else if (code === 'VM') {
                let m2_1 = num(m.m1_1) + num(m.m2m1_1);
                let m2m3_1 = m2_1 - num(m.m3_1);
                let loss_1 = m.m2m1_1 > 0 ? (m2m3_1 / num(m.m2m1_1))*100 : 0;

                let m2_2 = num(m.m1_2) + num(m.m2m1_2);
                let m2m3_2 = m2_2 - num(m.m3_2);
                let loss_2 = m.m2m1_2 > 0 ? (m2m3_2 / num(m.m2m1_2))*100 : 0;

                thead = `<tr><th>PENGULANGAN</th><th>M1</th><th>M2</th><th>M2-M1</th><th>M3</th><th>M2-M3</th><th>LOSS%</th><th>IM</th><th class="bg-warning bg-opacity-25">VM%</th><th>DIFF</th><th>AVG %adb</th><th>%db</th></tr>`;

                tbody1 = `<tr>
                    <td class="fw-bold bg-light">Simplo (D1)</td>
                    <td>${fmt(m.m1_1)}</td><td>${fmt(m2_1)}</td><td>${fmt(m.m2m1_1)}</td><td>${fmt(m.m3_1)}</td><td>${fmt(m2m3_1)}</td><td>${fmt(loss_1)}</td><td>${fmt(m.im_d1)}</td>
                    <td class="bg-warning bg-opacity-10 fw-bold text-primary">${fmt(d1, 2)}</td>
                    <td rowspan="2" class="align-middle fw-bold">${hasD2 ? fmt(diffD, 2) : '-'}</td>
                    <td rowspan="2" class="align-middle fw-bold">${hasD2 ? fmt(avgD, 2) : fmt(d1, 2)}</td>
                    <td class="fw-bold text-success">${db1 > 0 ? fmt(db1, 2) : '-'}</td>
                </tr>`;

                if (hasD2) {
                    tbody2 = `<tr>
                        <td class="fw-bold bg-light">Duplo (D2)</td>
                        <td>${fmt(m.m1_2)}</td><td>${fmt(m2_2)}</td><td>${fmt(m.m2m1_2)}</td><td>${fmt(m.m3_2)}</td><td>${fmt(m2m3_2)}</td><td>${fmt(loss_2)}</td><td>${fmt(m.im_d2)}</td>
                        <td class="bg-warning bg-opacity-10 fw-bold text-primary">${fmt(d2, 2)}</td>
                        <td class="fw-bold text-success">${db2 > 0 ? fmt(db2, 2) : '-'}</td>
                    </tr>`;
                }
            }
            else if (code === 'TS') {
                thead = `<tr><th>PENGULANGAN</th><th>Massa Sample</th><th class="bg-warning bg-opacity-25">TS (Adb)</th><th>AVG %adb</th><th>%db</th></tr>`;
                tbody1 = `<tr>
                    <td class="fw-bold bg-light">Simplo (D1)</td>
                    <td>${fmt(m.mass_1)}</td>
                    <td class="bg-warning bg-opacity-10 fw-bold text-primary">${fmt(d1, 2)}</td>
                    <td rowspan="2" class="align-middle fw-bold">${hasD2 ? fmt(avgD, 2) : fmt(d1, 2)}</td>
                    <td class="fw-bold text-success">${db1 > 0 ? fmt(db1, 2) : '-'}</td>
                </tr>`;
                if (hasD2) {
                    tbody2 = `<tr>
                        <td class="fw-bold bg-light">Duplo (D2)</td>
                        <td>${fmt(m.mass_2)}</td>
                        <td class="bg-warning bg-opacity-10 fw-bold text-primary">${fmt(d2, 2)}</td>
                        <td class="fw-bold text-success">${db2 > 0 ? fmt(db2, 2) : '-'}</td>
                    </tr>`;
                }
            }
            else if (code === 'CV') {
                thead = `<tr><th>PENGULANGAN</th><th>W</th><th>SM</th><th>PR</th><th>Ee</th><th>t</th><th>VT</th><th>LF</th><th>TS</th><th class="bg-warning bg-opacity-25">FINAL RESULT (cal/g)</th><th>AVG (adb)</th><th>%db</th></tr>`;
                tbody1 = `<tr>
                    <td class="fw-bold bg-light">Simplo (D1)</td>
                    <td>${fmt(m.w_1)}</td><td>${fmt(m.sm_1)}</td><td>${fmt(m.pr_1)}</td><td>${fmt(m.ee_1)}</td><td>${fmt(m.t_1)}</td><td>${fmt(m.vt_1)}</td><td>${fmt(m.lf_1)}</td><td>${fmt(m.ts_1)}</td>
                    <td class="bg-warning bg-opacity-10 fw-bold text-primary">${fmt(d1, 0)}</td>
                    <td rowspan="2" class="align-middle fw-bold">${hasD2 ? fmt(avgD, 0) : fmt(d1, 0)}</td>
                    <td class="fw-bold text-success">${db1 > 0 ? fmt(db1, 0) : '-'}</td>
                </tr>`;
                if (hasD2) {
                    tbody2 = `<tr>
                        <td class="fw-bold bg-light">Duplo (D2)</td>
                        <td>${fmt(m.w_2)}</td><td>${fmt(m.sm_2)}</td><td>${fmt(m.pr_2)}</td><td>${fmt(m.ee_2)}</td><td>${fmt(m.t_2)}</td><td>${fmt(m.vt_2)}</td><td>${fmt(m.lf_2)}</td><td>${fmt(m.ts_2)}</td>
                        <td class="bg-warning bg-opacity-10 fw-bold text-primary">${fmt(d2, 0)}</td>
                        <td class="fw-bold text-success">${db2 > 0 ? fmt(db2, 0) : '-'}</td>
                    </tr>`;
                }
            } else {
                thead = `<tr><th>PENGULANGAN</th><th class="bg-warning bg-opacity-25">Hasil Uji</th><th>DIFF</th><th>AVG</th></tr>`;
                tbody1 = `<tr>
                    <td class="fw-bold bg-light">Simplo (D1)</td>
                    <td class="bg-warning bg-opacity-10 fw-bold text-primary">${fmt(d1, 2)}</td>
                    <td rowspan="2" class="align-middle fw-bold">${hasD2 ? fmt(diffD, 2) : '-'}</td>
                    <td rowspan="2" class="align-middle fw-bold">${hasD2 ? fmt(avgD, 2) : fmt(d1, 2)}</td>
                </tr>`;
                if (hasD2) {
                    tbody2 = `<tr>
                        <td class="fw-bold bg-light">Duplo (D2)</td>
                        <td class="bg-warning bg-opacity-10 fw-bold text-primary">${fmt(d2, 2)}</td>
                    </tr>`;
                }
            }

            let tableHtml = `<table class="table table-bordered table-sm align-middle text-center" style="font-size: 11px;">
                <thead class="table-light">${thead}</thead>
                <tbody>${tbody1}${tbody2}</tbody>
            </table>`;

            document.getElementById('detTableKalkulasi').innerHTML = tableHtml;

            new bootstrap.Modal(document.getElementById('modalDetailQcHarian')).show();
        });
    });
});
</script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        let filterBulan = $('#filterBulan').val();
        let filterTahun = $('#filterTahun').val();

        let dateStr = data[0] || "";

        if (filterBulan && !dateStr.includes(filterBulan)) {
            return false;
        }

        if (filterTahun && !dateStr.includes(filterTahun)) {
            return false;
        }

        return true;
    });

    let table = $('#tableRiwayat').DataTable({
        "order": [],
        "pageLength": 10,
        "language": {
            "search": "Live Search:",
            "lengthMenu": "Tampilkan _MENU_ baris",
            "info": "Menampilkan _START_ s/d _END_ dari total _TOTAL_ riwayat",
            "infoEmpty": "Tidak ada data riwayat yang tersedia",
            "infoFiltered": "(disaring dari _MAX_ total data)",
            "zeroRecords": "Data tidak ditemukan",
            "paginate": {
                "first": "Awal",
                "last": "Akhir",
                "next": "Next <i class='fas fa-chevron-right' style='font-size:10px; margin-left:4px'></i>",
                "previous": "<i class='fas fa-chevron-left' style='font-size:10px; margin-right:4px'></i> Previous"
            }
        }
    });

    $('#filterBulan, #filterTahun').on('change', function() {
        table.draw();
    });
});

    $('.form-delete-draft').on('submit', function(e) {
        e.preventDefault();
        let form = this;

        Swal.fire({
            title: 'Hapus Draft Ini?',
            text: "Data pengujian yang belum selesai ini akan hilang permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                form.submit();
            }
        });
    });
</script>

@endsection