@extends('layouts.app')
@section('title', 'Detail Data - QC In-House')

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
        margin-bottom: 0;
        color: #000000;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 0;
        color: #000000;
    }

    .icon-corporate {
        color: #1b3152;
    }

    .status-badge {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.3em 0.7em;
        border-radius: 4px;
    }

    .alert {
        padding: 0.45rem 2rem 0.45rem 0.75rem !important;
        font-size: 0.75rem !important;
        line-height: 1.45;
        border-radius: 6px;
    }

    .alert i {
        font-size: 0.8rem;
    }

    .alert .btn-close {
        padding: 0.7rem !important;
        transform: scale(0.7);
    }

    .action-wrap .btn,
    .modal-footer .btn {
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        line-height: 1.3;
        white-space: nowrap;
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

    .btn-danger-corp {
        background-color: #dc3545 !important;
        border: 1px solid #dc3545 !important;
        color: #ffffff !important;
    }

    .btn-danger-corp:hover,
    .btn-danger-corp:focus,
    .btn-danger-corp:active {
        background-color: #b02a37 !important;
        border-color: #b02a37 !important;
        color: #ffffff !important;
    }

    .btn-outline-danger-corp {
        background-color: #ffffff !important;
        border: 1px solid #dc3545 !important;
        color: #dc3545 !important;
    }

    .btn-outline-danger-corp:hover,
    .btn-outline-danger-corp:focus,
    .btn-outline-danger-corp:active {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
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

    .report-card {
        border: 1px solid #f5c2c7 !important;
        border-left: 4px solid #dc3545 !important;
    }

    .report-card .report-head {
        padding: 8px 12px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #842029;
        background-color: #fdecea;
        border-bottom: 1px solid #f5c2c7;
        border-radius: 0.4rem 0.4rem 0 0;
    }

    .report-card h6 {
        font-size: 0.78rem;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .report-card p {
        font-size: 0.78rem;
        line-height: 1.5;
        color: #000000;
    }

    .report-card .report-meta {
        font-size: 0.7rem;
        color: #000000;
    }

    .info-list {
        margin: 0;
    }

    .info-row {
        display: flex;
        gap: 8px;
        padding: 6px 0;
        border-bottom: 1px dashed #dfe4ea;
    }

    .info-row:first-child {
        padding-top: 0;
    }

    .info-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .info-row dt {
        flex: 0 0 38%;
        max-width: 38%;
        font-size: 0.74rem;
        font-weight: 600;
        color: #000000;
        margin: 0;
    }

    .info-row dd {
        flex: 1 1 auto;
        min-width: 0;
        font-size: 0.78rem;
        color: #000000;
        margin: 0;
        word-break: break-word;
    }

    .param-table {
        font-size: 0.76rem;
        margin-bottom: 0;
        border-color: #cfd6df;
    }

    .param-table thead th {
        background-color: #1b3152;
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.4rem 0.5rem;
        white-space: nowrap;
        border-color: rgba(255, 255, 255, 0.25);
    }

    .param-table td {
        padding: 0.35rem 0.5rem;
        border-color: #dfe4ea;
        color: #000000;
        vertical-align: middle;
    }

    .param-table tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }

    .param-table .badge {
        font-size: 0.66rem;
        font-weight: 600;
        padding: 0.3em 0.6em;
        border-radius: 4px;
        white-space: nowrap;
    }

    .param-table .badge.badge-final {
        font-size: 0.68rem;
        padding: 0.35em 0.9em;
    }

    .param-table small {
        display: block;
        margin-top: 2px;
        font-size: 0.66rem;
        color: #000000;
    }

    .modal-investigasi .modal-header {
        padding: 10px 14px;
        background-color: #dc3545;
        color: #ffffff;
    }

    .modal-investigasi .modal-title {
        font-size: 0.9rem;
        font-weight: 700;
    }

    .modal-investigasi .modal-body {
        padding: 12px !important;
        font-size: 0.78rem;
        background-color: #f8fafc;
    }

    .modal-investigasi .alert {
        font-size: 0.75rem;
        padding: 8px 12px;
        margin-bottom: 10px !important;
        color: #000000;
        background-color: rgba(27, 49, 82, 0.08);
        border: 0;
        border-left: 4px solid #1b3152;
        border-radius: 6px;
    }

    .modal-investigasi .alert i {
        color: #1b3152;
    }

    .modal-investigasi .form-label {
        font-size: 0.74rem;
        font-weight: 600;
        margin-bottom: 3px;
        color: #000000;
    }

    .modal-investigasi .form-control {
        font-size: 0.78rem;
        color: #000000;
    }

    .modal-investigasi .form-control::placeholder {
        color: #8a939c;
    }

    .modal-investigasi .form-control:focus {
        border-color: #1b3152;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
    }

    .modal-investigasi .modal-footer {
        padding: 8px 12px;
        background-color: #ffffff;
    }

    .modal-konfirmasi .modal-dialog {
        max-width: 380px;
    }

    .modal-konfirmasi .modal-content {
        padding: 1rem;
        font-size: 0.82rem;
        text-align: center;
        border: 0;
        border-radius: 8px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .modal-konfirmasi .konfirmasi-icon-wrap {
        padding: 0.5rem 0 0.25rem 0;
    }

    .modal-konfirmasi .konfirmasi-icon {
        width: 56px;
        height: 56px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 24px;
        color: #f0ad4e;
        background-color: #fff8e6;
        border: 2px solid #ffeeba;
    }

    .modal-konfirmasi .modal-body {
        padding: 0.5rem 0.5rem !important;
        background-color: transparent;
    }

    .modal-konfirmasi .konfirmasi-judul {
        margin-bottom: 4px;
        font-size: 1rem;
        font-weight: 700;
        color: #000000;
    }

    .modal-konfirmasi .konfirmasi-teks {
        margin-bottom: 0;
        font-size: 0.78rem;
        line-height: 1.5;
        color: #495057;
    }

    .modal-konfirmasi .modal-footer {
        border: 0;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.25rem 0 0.5rem 0;
    }

    .modal-konfirmasi .modal-footer .btn {
        font-size: 0.78rem;
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

        .card-body {
            padding: 10px !important;
        }

        .action-wrap > a.btn,
        .action-wrap > form {
            flex: 1 1 100%;
        }

        .action-wrap .btn {
            width: 100%;
            min-height: 40px;
            font-size: 0.85rem;
        }

        .modal-investigasi .form-control {
            font-size: 16px;
        }

        .modal-investigasi .modal-footer {
            flex-direction: column-reverse;
        }

        .modal-investigasi .modal-footer .btn {
            width: 100%;
            min-height: 40px;
            margin: 0;
        }

        .modal-konfirmasi .modal-footer .btn {
            min-height: 38px;
            min-width: 110px;
        }

        .info-row dt {
            flex-basis: 42%;
            max-width: 42%;
        }
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

    @media (max-width: 767.98px) {
        .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item {
            font-size: 0.85rem !important;
            padding: 10px 14px;
        }
    }
</style>

<div class="container-fluid dashboard-container">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item active">{{ $batch->nama_sampel }}</li>
    </x-qc-breadcrumb>

    <div class="card shadow-sm border-0 mb-2">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-2">
                <div>
                    <h5 class="page-title"><i class="fas fa-vial icon-corporate me-2"></i>{{ $batch->nama_sampel }}</h5>
                    <div class="mt-1">
                        <span class="badge status-badge bg-{{ $batch->status_color }}">{{ $batch->status_label }}</span>
                    </div>
                </div>

                <div class="action-wrap d-flex flex-wrap justify-content-lg-end align-items-stretch gap-2">

                    @if(!in_array($batch->status, ['pemilihan_sampel', 'preparasi']))
                        <a href="{{ route('qc-inhouse.preparasi', $batch->sampel_inhouse_id) }}" class="btn btn-outline-corporate btn-sm py-1.5 px-3 shadow-sm fw-semibold"><i class="fas fa-eye me-1"></i> Preparasi</a>
                    @endif

                    @if(!in_array($batch->status, ['pemilihan_sampel', 'preparasi', 'uji_homogenitas']))
                        <a href="{{ route('qc-inhouse.homogenitas', $batch->sampel_inhouse_id) }}" class="btn btn-outline-corporate btn-sm py-1.5 px-3 shadow-sm fw-semibold"><i class="fas fa-eye me-1"></i> Homogenitas</a>
                    @endif

                    @if(!in_array($batch->status, ['pemilihan_sampel', 'preparasi', 'uji_homogenitas', 'gagal_homogenitas', 'penetapan_target']))
                        <a href="{{ route('qc-inhouse.penetapan-target', $batch->sampel_inhouse_id) }}" class="btn btn-outline-corporate btn-sm py-1.5 px-3 shadow-sm fw-semibold"><i class="fas fa-eye me-1"></i> Target</a>
                    @endif

                    @if(!in_array($batch->status, ['pemilihan_sampel', 'preparasi', 'uji_homogenitas', 'gagal_homogenitas', 'penetapan_target']))
                        <a href="{{ route('qc-inhouse.stabilitas', $batch->sampel_inhouse_id) }}" class="btn btn-outline-corporate btn-sm py-1.5 px-3 shadow-sm fw-semibold"><i class="fas fa-eye me-1"></i> Stabilitas</a>
                    @endif

                    @if($batch->status === 'pemilihan_sampel' || $batch->status === 'preparasi')
                        <a href="{{ route('qc-inhouse.preparasi', $batch->sampel_inhouse_id) }}" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold"><i class="fas fa-play me-1"></i> Input Data Preparasi</a>
                    @elseif($batch->status === 'uji_homogenitas')
                        <a href="{{ route('qc-inhouse.instruksi-homogenitas', $batch->sampel_inhouse_id) }}" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold"><i class="fas fa-play me-1"></i> Lanjut Uji Homogenitas</a>
                    @elseif($batch->status === 'penetapan_target')
                        <a href="{{ route('qc-inhouse.penetapan-target', $batch->sampel_inhouse_id) }}" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold"><i class="fas fa-play me-1"></i> Input Nilai Target</a>
                    @elseif($batch->status === 'uji_stabilitas')
                        <a href="{{ route('qc-inhouse.stabilitas', $batch->sampel_inhouse_id) }}" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold"><i class="fas fa-play me-1"></i> Input Stabilitas</a>
                    @elseif(in_array($batch->status, ['siap_digunakan', 'kadaluarsa']))
                        <form action="{{ route('qc-inhouse.aktifkan', $batch->sampel_inhouse_id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="button" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold"
                                    data-bs-toggle="modal" data-bs-target="#modalKonfirmasi"
                                    data-judul="Aktifkan Batch Ini?"
                                    data-teks="Batch yang sedang aktif sebelumnya akan otomatis dikadaluarsakan."
                                    data-tombol="Ya, Aktifkan!"
                                    data-warna="btn-corporate-blue">
                                <i class="fas fa-power-off me-1"></i> Aktifkan Sebagai Acuan Harian
                            </button>
                        </form>
                    @elseif($batch->status === 'aktif')
                        <a href="{{ route('qc-harian.index') }}" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold">
                            <i class="fas fa-chart-line me-1"></i> Modul Pengujian Harian (QC)
                        </a>

                        <form action="{{ route('qc-inhouse.nonaktifkan', $batch->sampel_inhouse_id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="button" class="btn btn-outline-danger-corp btn-sm py-1.5 px-3 shadow-sm fw-semibold"
                                    data-bs-toggle="modal" data-bs-target="#modalKonfirmasi"
                                    data-judul="Nonaktifkan Sampel Ini?"
                                    data-teks="Sampel ini akan diubah statusnya menjadi kadaluarsa dan tidak bisa digunakan untuk pengujian harian sebelum diaktifkan kembali."
                                    data-tombol="Ya, Nonaktifkan!"
                                    data-warna="btn-danger-corp">
                                <i class="fas fa-times-circle me-1"></i> Nonaktifkan Sampel
                            </button>
                        </form>
                    @elseif(in_array($batch->status, ['gagal_homogenitas', 'gagal_stabilitas']))
                        @if(!$batch->akar_masalah)
                            <button type="button" class="btn btn-danger-corp btn-sm py-1.5 px-3 shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalInvestigasi">
                                <i class="fas fa-search me-1"></i> Lakukan Investigasi
                            </button>
                        @else
                            <form action="{{ route('qc-inhouse.preparasi-ulang', $batch->sampel_inhouse_id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="button" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold"
                                        data-bs-toggle="modal" data-bs-target="#modalKonfirmasi"
                                        data-judul="Mulai Preparasi Ulang?"
                                        data-teks="Sistem akan membuat batch baru (Revisi) dan mengarsipkan data yang lama."
                                        data-tombol="Ya, Lanjutkan!"
                                        data-warna="btn-corporate-blue">
                                    <i class="fas fa-redo-alt me-1"></i> Mulai Preparasi Ulang (Re-Prep)
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($batch->akar_masalah)
    <div class="card shadow-sm report-card mb-2">
        <div class="report-head">
            <i class="fas fa-file-alt me-2"></i> Laporan Investigasi Kegagalan
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-6">
                    <h6 class="text-danger">Akar Masalah (Root Cause):</h6>
                    <p class="mb-0">{{ $batch->akar_masalah }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-success">Tindakan Perbaikan (Corrective Action):</h6>
                    <p class="mb-0">{{ $batch->tindakan_perbaikan }}</p>
                </div>
            </div>
            <hr class="my-2">
            <div class="report-meta">
                Diinvestigasi oleh: <strong>{{ $batch->investigator->username ?? '-' }}</strong> pada {{ \Carbon\Carbon::parse($batch->tanggal_investigasi)->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>
    @endif

    <div class="row g-2">
        <div class="col-xl-3 col-lg-4">
            <div class="card shadow-sm border-0 mb-2 h-100">
                <div class="card-header-sm">
                    <h6 class="section-title"><i class="fas fa-info-circle icon-corporate me-2"></i>Informasi Umum</h6>
                </div>
                <div class="card-body">
                    <dl class="info-list">
                        <div class="info-row">
                            <dt>Kode Batch</dt>
                            <dd class="fw-bold">{{ $batch->kode_batch ?? '-' }}</dd>
                        </div>
                        <div class="info-row">
                            <dt>Jenis Batubara</dt>
                            <dd class="text-uppercase">{{ str_replace('_', ' ', $batch->jenis_batubara) }}</dd>
                        </div>
                        <div class="info-row">
                            <dt>Metode Acuan</dt>
                            <dd class="text-uppercase">{{ $batch->metode_acuan ?? '-' }}</dd>
                        </div>
                        <div class="info-row">
                            <dt>Jumlah Botol</dt>
                            <dd>{{ $batch->jumlah_botol ? $batch->jumlah_botol . ' botol' : '-' }}</dd>
                        </div>
                        <div class="info-row">
                            <dt>Dibuat Oleh</dt>
                            <dd>{{ $batch->pembuat->username ?? '-' }} ({{ $batch->tanggal_pemilihan ? $batch->tanggal_pemilihan->format('d/m/Y') : '-' }})</dd>
                        </div>
                    </dl>
                </div>
            </div>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white pt-3 pb-2">
                    <h5 class="fw-bold"><i class="fas id-card text-success me-2"></i>Resource Pelaksanaan Pengujian</h5>
                </div>
                <div class="card-body">
                    @foreach($batch->parameters as $p)
                        @php
                            $firstStabilitas = $p->dataStabilitas->first();
                            $firstHomogen = $p->dataHomogenitas->first();
                            $mentah = $firstStabilitas ? $firstStabilitas->data_mentah : ($firstHomogen ? $firstHomogen->data_mentah : []);
                            
                            $personilIds = $mentah['personil_ids'] ?? [];
                            $alatIds = $mentah['alat_ids'] ?? [];
                            $barangIds = $mentah['barang_ids'] ?? [];
                            $barangJumlah = $mentah['barang_jumlah'] ?? [];
                        @endphp

                        <div class="mb-3 pb-2 border-bottom">
                            <span class="fw-bold text-primary">{{ $p->parameterUji->nama_parameter }}</span>
                            <ul class="list-unstyled mb-0 small mt-1">
                                <li>
                                    <strong>Analis:</strong> 
                                    @if(count($personilIds) > 0)
                                        {{ \App\Models\Personil::whereIn('personil_id', $personilIds)->pluck('nama')->join(', ') }}
                                    @else
                                        <span class="text-muted fst-italic">Tidak tercatat</span>
                                    @endif
                                </li>
                                <li>
                                    <strong>Alat:</strong> 
                                    @if(count($alatIds) > 0)
                                        {{ \App\Models\Alat::whereIn('alat_id', $alatIds)->pluck('nama_alat')->join(', ') }}
                                    @else
                                        <span class="text-muted fst-italic">Tidak tercatat</span>
                                    @endif
                                </li>
                                <li>
                                    <strong>Bahan:</strong> 
                                    @if(count($barangIds) > 0)
                                        @php
                                            $listBarang = \App\Models\Barang::whereIn('barang_id', $barangIds)->get();
                                        @endphp
                                        <span class="text-dark">
                                            @foreach($listBarang as $bhn)
                                                {{ $bhn->nama_barang }} ({{ $barangJumlah[$bhn->barang_id] ?? 0 }} {{ $bhn->satuan }}){{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        </span>
                                    @else
                                        <span class="text-muted fst-italic">Tidak ada bahan tercatat</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <div class="card shadow-sm border-0 mb-2 h-100">
                <div class="card-header-sm">
                    <h6 class="section-title"><i class="fas fa-tasks icon-corporate me-2"></i>Status Per Parameter Uji</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle text-center param-table">
                            <thead>
                                <tr>
                                    <th>Parameter</th>
                                    <th>Status Homogenitas</th>
                                    <th>Status Target</th>
                                    <th>Status Stabilitas</th>
                                    <th>Keputusan Final</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($batch->parameters as $p)
                                    <tr>
                                        <td class="fw-bold text-start ps-2">{{ $p->parameterUji->nama_parameter }}</td>

                                        <td>
                                            @if($p->status_parameter === 'draft' && !$p->f_hitung)
                                                <span class="badge bg-secondary">Belum Uji</span>
                                            @elseif(in_array($p->status_parameter, ['homogen', 'target_set', 'stabil']))
                                                <span class="badge bg-success"><i class="fas fa-check"></i> Homogen</span>
                                                <small>F = {{ number_format($p->f_hitung, 3) }}</small>
                                            @elseif($p->status_parameter === 'tidak_homogen')
                                                <span class="badge bg-danger"><i class="fas fa-times"></i> Tidak Homogen</span>
                                            @else
                                                <span class="badge bg-success"><i class="fas fa-check"></i> Homogen</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($p->mean_target)
                                                <span class="badge bg-success"><i class="fas fa-check"></i> Selesai</span>
                                                <small>Target: {{ number_format($p->mean_target, 3) }}</small>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($p->status_parameter === 'stabil')
                                                <span class="badge bg-success"><i class="fas fa-check"></i> Stabil</span>
                                                <small>t = {{ number_format($p->t_hitung, 3) }}</small>
                                            @elseif($p->status_parameter === 'tidak_stabil')
                                                <span class="badge bg-danger"><i class="fas fa-times"></i> Tidak Stabil</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($p->status_parameter === 'stabil')
                                                <span class="badge badge-final bg-success">AKTIF</span>
                                            @elseif(in_array($p->status_parameter, ['tidak_homogen', 'tidak_stabil']))
                                                <span class="badge badge-final bg-danger">GAGAL</span>
                                            @else
                                                <span class="badge badge-final bg-warning text-dark">PROSES</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-investigasi" id="modalInvestigasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('qc-inhouse.investigasi', $batch->sampel_inhouse_id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-search me-2"></i>Form Investigasi Kegagalan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert">
                        <i class="fas fa-exclamation-triangle me-1"></i> Sampel dinyatakan <strong>Tidak Homogen / Tidak Stabil</strong>. Sesuai instruksi kerja, sampel wajib diinvestigasi sebelum penyiapan ulang (Re-Prep) seluruh batch.
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Akar Masalah (Root Cause)</label>
                        <textarea class="form-control" name="akar_masalah" rows="3" required placeholder="Jelaskan penyebab mengapa sampel gagal (misal: suhu oven tidak stabil, dll)..."></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Tindakan Perbaikan (Corrective Action)</label>
                        <textarea class="form-control" name="tindakan_perbaikan" rows="3" required placeholder="Jelaskan perbaikan yang dilakukan sebelum sampel disiapkan ulang..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-kembali btn-sm py-1.5 px-3 shadow-sm fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold"><i class="fas fa-save me-1"></i> Simpan Investigasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-konfirmasi" id="modalKonfirmasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="konfirmasi-icon-wrap">
                <div class="konfirmasi-icon"><i class="fas fa-exclamation"></i></div>
            </div>
            <div class="modal-body">
                <h5 class="konfirmasi-judul" id="konfirmasiJudul"></h5>
                <p class="konfirmasi-teks" id="konfirmasiTeks"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm py-1.5 px-3 fw-semibold rounded-2" id="konfirmasiYa"></button>
                <button type="button" class="btn btn-secondary btn-sm py-1.5 px-3 fw-semibold rounded-2" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const modalKonfirmasi = document.getElementById('modalKonfirmasi');
    const tombolYa = document.getElementById('konfirmasiYa');
    let formTarget = null;

    modalKonfirmasi.addEventListener('show.bs.modal', function(event) {
        const pemicu = event.relatedTarget;
        formTarget = pemicu.closest('form');
        document.getElementById('konfirmasiJudul').textContent = pemicu.dataset.judul;
        document.getElementById('konfirmasiTeks').textContent = pemicu.dataset.teks;
        tombolYa.textContent = pemicu.dataset.tombol;
        tombolYa.className = 'btn btn-sm py-1.5 px-3 fw-semibold rounded-2 ' + pemicu.dataset.warna;
    });

    tombolYa.addEventListener('click', function() {
        if (formTarget) {
            formTarget.submit();
        }
    });
})();
</script>
@endsection