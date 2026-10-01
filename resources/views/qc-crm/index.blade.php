@extends('layouts.app')
@section('title', 'Dashboard - QC CRM')

@section('content')
@include('qc-crm._qc-compact')

@php
    // Batas "segera kedaluwarsa" (hari) untuk botol CRM — ubah di sini kalau perlu
    $batasHariExpired = 90;

    $expState = [];   // id botol => none|aktif|segera|kedaluwarsa
    $sisaHari = [];   // id botol => sisa hari
    $expiredCount = 0;
    $segeraCount = 0;
    $menungguCount = 0;
    $outlierCount = 0;

    foreach ($katalogs as $r) {
        $state = 'none';
        $sisa = null;

        if ($r->tanggal_expired) {
            $sisa = (int) now()->startOfDay()->diffInDays($r->tanggal_expired->copy()->startOfDay(), false);
            if ($sisa < 0) {
                $state = 'kedaluwarsa';
                $expiredCount++;
            } elseif ($sisa <= $batasHariExpired) {
                $state = 'segera';
                $segeraCount++;
            } else {
                $state = 'aktif';
            }
        }

        $expState[$r->id] = $state;
        $sisaHari[$r->id] = $sisa;

        if (in_array($r->status, ['menunggu_verifikasi', 'menunggu_verifikasi_teknis'])) {
            $menungguCount++;
        }
        if ($r->verifikasiTeknis->where('status_evaluasi', 'outlier')->isNotEmpty()) {
            $outlierCount++;
        }
    }

    // Opsi filter status dibuat otomatis dari data yang ada
    $statusOptions = $katalogs->groupBy('status')->map(fn ($g) => $g->first()->statusLabel());
@endphp

<style>
    .qc-page { padding: 4px 20px !important; font-size: 0.82rem; }

    /* ===== Tombol (sama dengan alat/index) ===== */
    .qc-page .btn-corporate-blue {
        background-color: #1b3152 !important; border-color: #1b3152 !important; color: #fff !important;
    }
    .qc-page .btn-corporate-blue:hover,
    .qc-page .btn-corporate-blue:focus,
    .qc-page .btn-corporate-blue:active {
        background-color: #14253e !important; border-color: #14253e !important; color: #fff !important;
    }
    .qc-page .btn-outline-corporate {
        color: #1b3152 !important; border: 1px solid #1b3152 !important; background: #fff !important;
    }
    .qc-page .btn-outline-corporate:hover { background-color: #1b3152 !important; color: #fff !important; }

    .qc-action-btn {
        width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.75rem; padding: 0 !important; border-radius: 6px;
    }

    /* ===== Notifikasi atas (kedaluwarsa / verifikasi): dibuat lebih ringkas ===== */
    .qc-page > .alert {
        padding-top: 0.35rem !important;
        padding-bottom: 0.35rem !important;
        margin-bottom: 0.4rem !important;
        font-size: 0.76rem !important;
        line-height: 1.35;
    }
    .qc-page > .alert .btn-close {
        top: 50% !important;
        transform: translateY(-50%);
        padding: 0.5rem !important;
    }

    /* ===== Catatan di tab Riwayat: teks kecil, bukan kotak alert ===== */
    #harian > .alert-info {
        background: transparent !important;
        border: 0 !important;
        box-shadow: none !important;
        color: #6c757d !important;
        font-size: 0.74rem !important;
        padding: 0 0.25rem !important;
        margin-bottom: 0.5rem !important;
    }
    #harian > .alert-info i { color: #0dcaf0; }
    #harian > .alert-info strong { color: #495057; }

    /* ===== Tabel ===== */
    .qc-page .table-qc th,
    .qc-page .table-qc td {
        padding: 8px 10px !important; vertical-align: middle !important; font-size: 0.75rem !important;
    }
    .qc-page .table-qc thead th {
        background-color: #1b3152 !important; color: #fff !important; border-color: #fff !important;
        font-size: 0.75rem !important; white-space: nowrap;
    }
    .qc-page .table-qc .badge { font-size: 0.68rem; }

    /* ===== Tab utama ===== */
    .qc-tabs { flex-wrap: nowrap; overflow-x: auto; overflow-y: hidden; white-space: nowrap; scrollbar-width: none; }
    .qc-tabs::-webkit-scrollbar { display: none; }
    .qc-tabs .nav-link { font-size: 0.82rem; padding: 0.5rem 1rem; color: #495057; }
    .qc-tabs .nav-link.active { color: #1b3152; border-bottom: 2px solid #1b3152; }

    /* ===== Pagination DataTables ===== */
    .dataTables_wrapper .dataTables_info { font-size: 0.72rem; padding: 0 12px; }
    .dataTables_wrapper .dataTables_paginate { float: right; margin: 10px 12px; }
    .dataTables_wrapper .pagination { margin: 0; flex-wrap: wrap; }
    .dataTables_wrapper .page-link { font-size: 0.72rem; padding: 0.2rem 0.55rem; color: #1b3152; }
    .dataTables_wrapper .page-item.active .page-link { background-color: #1b3152; border-color: #1b3152; color: #fff; }

    /* ===== Dropdown "Tampil ... data" (DataTables length) ===== */
    .dataTables_wrapper .dataTables_length {
        padding: 0 !important;
        margin-bottom: 10px;
        font-size: 0.75rem;
    }
    .dataTables_wrapper .dataTables_length label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 0;
        white-space: nowrap;
        font-size: 0.75rem;
        color: #495057;
    }
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_length .form-select {
        width: auto !important;
        min-width: 72px;
        display: inline-block;
        margin: 0 !important;
        padding: 0.25rem 2rem 0.25rem 0.6rem !important;  /* ruang kanan untuk panah */
        font-size: 0.75rem;
        line-height: 1.4;
        height: auto;
        border-radius: 6px;
        background-position: right 0.6rem center;          /* panah rapi di kanan */
        background-size: 12px 10px;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    /* ===== Chart ===== */
    .chart-wrap { height: 450px; width: 100%; position: relative; }

    .pulse-button { animation: pulse 1.5s infinite; }
    @keyframes pulse {
        0%   { box-shadow: 0 0 0 0 rgba(27, 49, 82, 0.6); }
        70%  { box-shadow: 0 0 0 8px rgba(27, 49, 82, 0); }
        100% { box-shadow: 0 0 0 0 rgba(27, 49, 82, 0); }
    }
    @media (prefers-reduced-motion: reduce) { .pulse-button { animation: none; } }

    /* ===== Mobile: tabel jadi kartu ===== */
    @media (max-width: 767.98px) {
        .qc-page { padding: 4px 10px !important; }
        .qc-tabs .nav-link { padding: 0.45rem 0.75rem; font-size: 0.78rem; }
        .chart-wrap { height: 320px; }

        .table-stack thead { display: none; }
        .table-stack, .table-stack tbody, .table-stack tr, .table-stack td { display: block; width: 100%; }
        .table-stack tbody tr {
            border: 1px solid #dee2e6; border-radius: 8px; margin: 0 0 10px; padding: 6px 12px;
            background: #fff; box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }
        .table-stack td {
            display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;
            text-align: right; border: 0 !important; padding: 5px 0 !important; background: transparent !important;
        }
        .table-stack td[data-label]::before {
            content: attr(data-label); font-weight: 600; color: #1b3152; text-align: left; flex-shrink: 0; max-width: 45%;
        }
        .table-stack td.td-aksi { justify-content: flex-end; flex-wrap: wrap; border-top: 1px solid #eee !important; margin-top: 4px; padding-top: 8px !important; }
        .table-stack td.td-aksi::before { display: none; }
        .table-stack td.td-empty { display: block; text-align: center; }
        .dataTables_wrapper .dataTables_paginate { float: none; display: flex; justify-content: center; }
        .dataTables_wrapper .dataTables_info { text-align: center; }

        /* Dropdown "Tampil ... data" di HP */
        .dataTables_wrapper .dataTables_length { text-align: left; }
        .dataTables_wrapper .dataTables_length label { width: 100%; }
        .dataTables_wrapper .dataTables_length select { min-width: 80px; }
    }
</style>

<div class="container-fluid qc-page qc-compact pb-4">
    <x-qc-breadcrumb active="CRM" />

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-2 mb-3 gap-2">
        <h5 class="fw-bold mb-0" style="font-size: 1.1rem;">
            <i class="fas fa-certificate me-2" style="color: #1b3152;"></i>QC CRM Dashboard
        </h5>

        <div class="d-grid d-md-flex gap-2">
            <a href="{{ route('crm-katalog.create') }}" class="btn btn-outline-corporate btn-sm shadow-sm fw-semibold px-3 py-1.5" style="font-size: 0.8rem;">
                <i class="fas fa-plus me-1"></i> Botol CRM Baru
            </a>
            <a href="{{ route('qc-crm.create') }}" class="btn btn-corporate-blue btn-sm shadow-sm fw-semibold px-3 py-1.5" style="font-size: 0.8rem;">
                <i class="fas fa-play me-1"></i> Mulai Pengujian CRM
            </a>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if($expiredCount > 0 || $segeraCount > 0)
        <div class="alert alert-warning alert-dismissible fade show shadow-sm py-2 ps-3 pe-5 mb-2" role="alert" style="font-size: 0.8rem;">
            <i class="fas fa-exclamation-triangle me-1"></i> <strong>Perhatian!</strong>
            @if($expiredCount > 0)
                Terdapat <strong>{{ $expiredCount }} botol CRM</strong> yang sudah kedaluwarsa.
            @endif
            @if($segeraCount > 0)
                Terdapat <strong>{{ $segeraCount }} botol CRM</strong> yang akan kedaluwarsa dalam {{ $batasHariExpired }} hari ke depan.
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.65rem; padding: 0.9rem;"></button>
        </div>
    @endif

    @if($menungguCount > 0 || $outlierCount > 0)
        <div class="alert alert-info alert-dismissible fade show shadow-sm py-2 ps-3 pe-5 mb-2" role="alert" style="font-size: 0.8rem;">
            <i class="fas fa-info-circle me-1"></i>
            @if($menungguCount > 0)
                <strong>{{ $menungguCount }} botol</strong> menunggu proses verifikasi.
            @endif
            @if($outlierCount > 0)
                <strong>{{ $outlierCount }} botol</strong> memiliki parameter outlier dan perlu uji ulang.
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.65rem; padding: 0.9rem;"></button>
        </div>
    @endif

    {{-- TABS NAV --}}
    <ul class="nav nav-tabs qc-tabs mb-3 mt-3" id="qcCrmTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="katalog-tab" data-bs-toggle="tab" data-bs-target="#katalog" type="button" role="tab" aria-controls="katalog" aria-selected="true">
                <i class="fas fa-box me-1"></i>
                <span class="d-none d-md-inline">Master Botol & Verifikasi CRM</span>
                <span class="d-md-none">Master Botol</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="harian-tab" data-bs-toggle="tab" data-bs-target="#harian" type="button" role="tab" aria-controls="harian" aria-selected="false">
                <i class="fas fa-vials me-1"></i>
                <span class="d-none d-md-inline">Riwayat Pengujian Harian CRM</span>
                <span class="d-md-none">Riwayat Harian</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="chart-tab" data-bs-toggle="tab" data-bs-target="#chartCrm" type="button" role="tab" aria-controls="chartCrm" aria-selected="false">
                <i class="fas fa-chart-area me-1"></i> Control Chart
            </button>
        </li>
    </ul>

    <div class="tab-content" id="qcCrmTabsContent">

        {{-- ================= TAB 1: MASTER BOTOL & VERIFIKASI ================= --}}
        <div class="tab-pane fade show active" id="katalog" role="tabpanel" aria-labelledby="katalog-tab">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-2 p-md-3">

                    {{-- FILTER --}}
                    <div class="row g-2 mb-3 align-items-center">
                        <div class="col-12 col-md-5">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" id="katalogSearch" class="form-control form-control-sm py-1.5" placeholder="Cari Nomor Lot, Produk, atau Sertifikat..." autocomplete="off" style="font-size: 0.82rem;">
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <select id="filterStatusBotol" class="form-select form-select-sm select2-qc">
                                <option value="">-- Filter Status Botol --</option>
                                @foreach($statusOptions as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-3">
                            <select id="filterExpiry" class="form-select form-select-sm select2-qc">
                                <option value="">-- Filter Masa Berlaku --</option>
                                <option value="aktif">Aktif (&gt; {{ $batasHariExpired }} Hari)</option>
                                <option value="segera">Segera Berakhir (&le; {{ $batasHariExpired }} Hari)</option>
                                <option value="kedaluwarsa">Kedaluwarsa</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-1">
                            <button type="button" id="btnResetKatalog" class="btn btn-outline-secondary btn-sm w-100 py-1.5" title="Reset"><i class="fas fa-sync-alt"></i></button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0 table-qc table-stack" id="tableKatalog">
                            <thead>
                                <tr>
                                    <th>Nomor Lot / Botol</th>
                                    <th>Nama Produk</th>
                                    <th>Sertifikat</th>
                                    <th>Expired Date</th>
                                    <th>Status Botol</th>
                                    <th>Data Verifikasi</th>
                                    <th class="text-center">Aksi / Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($katalogs as $r)
                                @php
                                    $state = $expState[$r->id];
                                    $sisa = $sisaHari[$r->id];
                                @endphp
                                <tr class="katalog-row"
                                    data-search="{{ strtolower($r->nomor_lot . ' ' . $r->nama_produk . ' ' . $r->nomor_sertifikat) }}"
                                    data-status="{{ $r->status }}"
                                    data-expiry="{{ $state }}">
                                    <td data-label="Nomor Lot"><span class="badge bg-primary">{{ $r->nomor_lot }}</span></td>
                                    <td data-label="Nama Produk" class="fw-bold">{{ $r->nama_produk }}</td>
                                    <td data-label="Sertifikat">
                                        <div>
                                            No: <strong>{{ $r->nomor_sertifikat ?? '-' }}</strong><br>
                                            Param: <span class="badge bg-secondary">{{ $r->sertifikats_count ?? $r->sertifikats->count() }}</span>
                                        </div>
                                    </td>
                                    <td data-label="Expired Date">
                                        <div>
                                            @if($r->tanggal_expired)
                                                {{ $r->tanggal_expired->format('d-m-Y') }}
                                                @if($state === 'kedaluwarsa')
                                                    <span class="badge bg-danger mt-1 d-block px-2 py-1"><i class="fas fa-times-circle"></i> Kedaluwarsa</span>
                                                @elseif($state === 'segera')
                                                    <span class="badge bg-warning text-dark mt-1 d-block px-2 py-1" title="Sisa {{ $sisa }} hari lagi"><i class="fas fa-clock"></i> Segera Berakhir ({{ $sisa }}h)</span>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </td>
                                    <td data-label="Status Botol">
                                        <span class="badge {{ $r->statusBadgeClass() }}">{{ $r->statusLabel() }}</span>
                                    </td>
                                    <td data-label="Data Verifikasi">
                                        @if($r->verifikasiTeknis->count() > 0)
                                            <button type="button" class="btn btn-sm btn-outline-success py-0.5 px-2" style="font-size: 0.72rem;" data-bs-toggle="modal" data-bs-target="#modalHasilVerif{{ $r->id }}">
                                                <i class="fas fa-check-double me-1"></i> Lihat Hasil
                                            </button>
                                        @else
                                            <span class="text-muted">Belum ada uji</span>
                                        @endif
                                    </td>
                                    <td class="text-center text-nowrap td-aksi">
                                        <div class="d-inline-flex flex-wrap justify-content-center align-items-center gap-1">
                                            <a href="{{ route('crm-katalog.show', $r->id) }}" class="btn btn-corporate-blue btn-sm qc-action-btn shadow-sm" title="Detail Botol & Input Parameter" aria-label="Detail Botol">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if($r->status === 'menunggu_verifikasi')
                                                <a href="{{ route('crm-katalog.verifikasi-administratif.form', $r->id) }}" class="btn btn-warning btn-sm shadow-sm" style="font-size: 0.72rem;" title="Lakukan Verifikasi Administratif">
                                                    <i class="fas fa-clipboard-check"></i> Verif. Admin
                                                </a>
                                            @elseif($r->status === 'menunggu_verifikasi_teknis')
                                                @php
                                                    $hasDraft = $r->verifikasiTeknis->where('status_evaluasi', 'draft')->isNotEmpty();
                                                    $hasOutlier = $r->verifikasiTeknis->where('status_evaluasi', 'outlier')->isNotEmpty();
                                                @endphp
                                                <a href="{{ route('crm-katalog.verifikasi-teknis.form', $r->id) }}"
                                                   class="btn btn-sm shadow-sm {{ $hasOutlier ? 'btn-danger' : ($hasDraft ? 'btn-warning' : 'btn-corporate-blue pulse-button') }}"
                                                   style="font-size: 0.72rem;"
                                                   title="{{ $hasOutlier ? 'Uji Ulang Parameter Outlier' : ($hasDraft ? 'Lanjutkan Draft Verifikasi' : 'Lakukan Verifikasi Teknis') }}">
                                                    <i class="fas {{ $hasOutlier ? 'fa-exclamation-triangle' : ($hasDraft ? 'fa-edit' : 'fa-flask') }}"></i>
                                                    {{ $hasOutlier ? 'Uji Ulang (Outlier)' : ($hasDraft ? 'Lanjut Draft' : 'Mulai Uji Verifikasi') }}
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4 td-empty">
                                        <i class="fas fa-box-open fs-1 text-light mb-2 d-block"></i>
                                        Belum ada data botol CRM. Silakan tambahkan Botol CRM Baru.
                                    </td>
                                </tr>
                                @endforelse

                                <tr id="katalogNoResult" class="d-none">
                                    <td colspan="7" class="text-center text-muted py-4 td-empty">
                                        <i class="fas fa-search fs-3 text-light mb-2 d-block"></i>
                                        Tidak ada botol yang cocok dengan filter.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- MODAL HASIL VERIFIKASI (di luar tabel) --}}
            @foreach($katalogs as $r)
                @if($r->verifikasiTeknis->count() > 0)
                <div class="modal fade" id="modalHasilVerif{{ $r->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-fullscreen-lg-down">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header text-white py-2 px-3" style="background-color: #1b3152;">
                                <h5 class="modal-title" style="font-size: 0.92rem;"><i class="fas fa-check-double me-2"></i>Hasil Uji Verifikasi - {{ $r->nomor_lot }}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0">

                                {{-- Tab per parameter --}}
                                <div class="bg-light pt-2 px-2 border-bottom">
                                    <ul class="nav nav-tabs qc-tabs border-bottom-0" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#ringkasan-{{ $r->id }}" type="button" role="tab">
                                                <i class="fas fa-list me-1"></i> Ringkasan
                                            </button>
                                        </li>
                                        @foreach($r->verifikasiTeknis as $verif)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#param-{{ $r->id }}-{{ $verif->id }}" type="button" role="tab">
                                                {{ strtoupper($verif->parameterUji->nama_parameter ?? 'PARAM') }}
                                            </button>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="tab-content p-2 p-md-3">

                                    {{-- RINGKASAN --}}
                                    <div class="tab-pane fade show active" id="ringkasan-{{ $r->id }}" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped mb-0 text-center align-middle table-qc">
                                                <thead>
                                                    <tr>
                                                        <th>Parameter</th>
                                                        <th>Analis</th>
                                                        <th>D1</th>
                                                        <th>D2</th>
                                                        <th>Nilai Akhir (Mean)</th>
                                                        <th>True Value</th>
                                                        <th>Uncertainty (&plusmn;)</th>
                                                        <th>Rentang Diterima</th>
                                                        <th>Status Verifikasi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($r->verifikasiTeknis as $verif)
                                                        <tr>
                                                            <td class="fw-bold">{{ strtoupper($verif->parameterUji->nama_parameter ?? '-') }}</td>
                                                            <td>{{ $verif->analis->nama ?? '-' }}</td>
                                                            <td>{{ number_format($verif->nilai_d1, 4) }}</td>
                                                            <td>{{ $verif->nilai_d2 ? number_format($verif->nilai_d2, 4) : '-' }}</td>
                                                            <td class="fw-bold text-primary">{{ number_format($verif->nilai_akhir, 4) }}</td>
                                                            <td>{{ number_format($verif->cert_value, 4) }}</td>
                                                            <td>{{ number_format($verif->cert_u, 4) }}</td>
                                                            <td class="text-nowrap">{{ number_format($verif->batas_bawah, 4) }} - {{ number_format($verif->batas_atas, 4) }}</td>
                                                            <td>
                                                                @if($verif->nilai_akhir >= $verif->batas_bawah && $verif->nilai_akhir <= $verif->batas_atas)
                                                                    <span class="badge bg-success"><i class="fas fa-check"></i> INLIER</span>
                                                                @else
                                                                    <span class="badge bg-danger"><i class="fas fa-times"></i> OUTLIER</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    {{-- DATA MENTAH PER PARAMETER --}}
                                    @foreach($r->verifikasiTeknis as $verif)
                                    @php
                                        $code = strtoupper($verif->parameterUji->nama_parameter ?? '');
                                        $dataMentahList = is_string($verif->data_mentah) ? json_decode($verif->data_mentah, true) : ($verif->data_mentah ?? []);
                                        if (!is_array($dataMentahList)) $dataMentahList = [];
                                    @endphp
                                    <div class="tab-pane fade" id="param-{{ $r->id }}-{{ $verif->id }}" role="tabpanel">
                                        <h6 class="fw-bold text-primary mb-3" style="font-size: 0.85rem;"><i class="fas fa-search me-2"></i>Data Mentah Pengujian - {{ $code }}</h6>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm text-center align-middle table-qc">
                                                <thead>
                                                    <tr>
                                                        <th>Pengujian Ke-</th>
                                                        <th>Replikasi</th>
                                                        @if(in_array($code, ['IM','RM']))
                                                            <th>M1</th><th>M2</th><th>M3</th><th>A (Massa Sampel)</th><th>B (Massa Residu)</th>
                                                        @elseif($code === 'ASH')
                                                            <th>M1</th><th>M2</th><th>Massa Sampel (M2-M1)</th><th>M3</th><th>Massa Residu (M3-M1)</th>
                                                        @elseif($code === 'VM')
                                                            <th>M1</th><th>Massa Sampel (M2-M1)</th><th>M2</th><th>M3</th><th>LOSS (M2-M3)</th><th>IM</th>
                                                        @elseif(in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
                                                            <th>Massa Sample</th><th>TS (adb)</th>
                                                        @elseif(in_array($code, ['CV','GCV']))
                                                            <th>Vessel No</th><th>Call ID</th><th>Weight Crucible</th><th>Sample Mass</th><th>Preliminary Result</th><th>Ee</th><th>Vol Titrant</th><th>Length Fuse</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($dataMentahList as $index => $mentah)
                                                    {{-- SIMPLO --}}
                                                    <tr>
                                                        <td rowspan="2" class="fw-bold bg-light">{{ $index + 1 }}</td>
                                                        <td class="fw-bold text-start ps-3">Simplo</td>
                                                        @if(in_array($code, ['IM','RM']))
                                                            <td>{{ $mentah['mentah']['m1_1'] ?? '-' }}</td>
                                                            <td>{{ number_format(floatval($mentah['mentah']['m1_1'] ?? 0) + floatval($mentah['mentah']['a_1'] ?? 0), 4) }}</td>
                                                            <td>{{ $mentah['mentah']['m3_1'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['a_1'] ?? '-' }}</td>
                                                            <td>{{ number_format(floatval($mentah['mentah']['m3_1'] ?? 0) - floatval($mentah['mentah']['m1_1'] ?? 0), 4) }}</td>
                                                        @elseif($code === 'ASH')
                                                            <td>{{ $mentah['mentah']['m1_1'] ?? '-' }}</td>
                                                            <td>{{ number_format(floatval($mentah['mentah']['m1_1'] ?? 0) + floatval($mentah['mentah']['m2m1_1'] ?? 0), 4) }}</td>
                                                            <td>{{ $mentah['mentah']['m2m1_1'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['m3_1'] ?? '-' }}</td>
                                                            <td>{{ number_format(floatval($mentah['mentah']['m3_1'] ?? 0) - floatval($mentah['mentah']['m1_1'] ?? 0), 4) }}</td>
                                                        @elseif($code === 'VM')
                                                            <td>{{ $mentah['mentah']['m1_1'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['m2m1_1'] ?? '-' }}</td>
                                                            <td>{{ number_format(floatval($mentah['mentah']['m1_1'] ?? 0) + floatval($mentah['mentah']['m2m1_1'] ?? 0), 4) }}</td>
                                                            <td>{{ $mentah['mentah']['m3_1'] ?? '-' }}</td>
                                                            <td>{{ number_format((floatval($mentah['mentah']['m1_1'] ?? 0) + floatval($mentah['mentah']['m2m1_1'] ?? 0)) - floatval($mentah['mentah']['m3_1'] ?? 0), 4) }}</td>
                                                            <td>{{ $mentah['mentah']['im_d1'] ?? '-' }}</td>
                                                        @elseif(in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
                                                            <td>{{ $mentah['mentah']['mass_1'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['ts_1'] ?? '-' }}</td>
                                                        @elseif(in_array($code, ['CV','GCV']))
                                                            <td>{{ $mentah['mentah']['vessel_1'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['callid_1'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['wc_1'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['mass_1'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['pre_1'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['ee_1'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['vt_1'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['lf_1'] ?? '-' }}</td>
                                                        @endif
                                                    </tr>
                                                    {{-- DUPLO --}}
                                                    <tr>
                                                        <td class="fw-bold text-start ps-3">Duplo</td>
                                                        @if(in_array($code, ['IM','RM']))
                                                            <td>{{ $mentah['mentah']['m1_2'] ?? '-' }}</td>
                                                            <td>{{ number_format(floatval($mentah['mentah']['m1_2'] ?? 0) + floatval($mentah['mentah']['a_2'] ?? 0), 4) }}</td>
                                                            <td>{{ $mentah['mentah']['m3_2'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['a_2'] ?? '-' }}</td>
                                                            <td>{{ number_format(floatval($mentah['mentah']['m3_2'] ?? 0) - floatval($mentah['mentah']['m1_2'] ?? 0), 4) }}</td>
                                                        @elseif($code === 'ASH')
                                                            <td>{{ $mentah['mentah']['m1_2'] ?? '-' }}</td>
                                                            <td>{{ number_format(floatval($mentah['mentah']['m1_2'] ?? 0) + floatval($mentah['mentah']['m2m1_2'] ?? 0), 4) }}</td>
                                                            <td>{{ $mentah['mentah']['m2m1_2'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['m3_2'] ?? '-' }}</td>
                                                            <td>{{ number_format(floatval($mentah['mentah']['m3_2'] ?? 0) - floatval($mentah['mentah']['m1_2'] ?? 0), 4) }}</td>
                                                        @elseif($code === 'VM')
                                                            <td>{{ $mentah['mentah']['m1_2'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['m2m1_2'] ?? '-' }}</td>
                                                            <td>{{ number_format(floatval($mentah['mentah']['m1_2'] ?? 0) + floatval($mentah['mentah']['m2m1_2'] ?? 0), 4) }}</td>
                                                            <td>{{ $mentah['mentah']['m3_2'] ?? '-' }}</td>
                                                            <td>{{ number_format((floatval($mentah['mentah']['m1_2'] ?? 0) + floatval($mentah['mentah']['m2m1_2'] ?? 0)) - floatval($mentah['mentah']['m3_2'] ?? 0), 4) }}</td>
                                                            <td>{{ $mentah['mentah']['im_d2'] ?? '-' }}</td>
                                                        @elseif(in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
                                                            <td>{{ $mentah['mentah']['mass_2'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['ts_2'] ?? '-' }}</td>
                                                        @elseif(in_array($code, ['CV','GCV']))
                                                            <td>{{ $mentah['mentah']['vessel_2'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['callid_2'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['wc_2'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['mass_2'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['pre_2'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['ee_2'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['vt_2'] ?? '-' }}</td>
                                                            <td>{{ $mentah['mentah']['lf_2'] ?? '-' }}</td>
                                                        @endif
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="10" class="text-muted py-4">
                                                            <i class="fas fa-info-circle me-1"></i> Data mentah tidak ditemukan untuk parameter ini.
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        {{-- ================= TAB 2: PENGUJIAN HARIAN ================= --}}
        <div class="tab-pane fade" id="harian" role="tabpanel" aria-labelledby="harian-tab">

            <div class="alert alert-info border-0 shadow-sm py-2 px-3 mb-2" style="font-size: 0.8rem;">
                <i class="fas fa-info-circle me-1"></i> Pilihan botol pada menu <strong>Mulai Pengujian CRM</strong> hanya menampilkan botol yang berstatus <strong>AKTIF</strong> (lolos uji verifikasi).
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-2 p-md-3">

                    {{-- FILTER --}}
                    @php
                        $uniqueParams = $kegiatanList->pluck('parameterUji.nama_parameter')->filter()->unique()->sort();
                    @endphp
                    <div class="row g-2 mb-3 align-items-end">
                        <div class="col-12 col-md-4">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.72rem;">Parameter Uji</label>
                            <select id="filterParam" class="form-select form-select-sm select2-qc">
                                <option value="">-- Semua Parameter --</option>
                                @foreach($uniqueParams as $p)
                                    <option value="{{ $p }}">{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-8 col-md-3">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.72rem;">Periode (Bulan & Tahun)</label>
                            <input type="month" id="filterPeriode" class="form-control form-control-sm" style="font-size: 0.82rem;">
                        </div>
                        <div class="col-4 col-md-1">
                            <button type="button" id="btnResetFilter" class="btn btn-outline-secondary btn-sm w-100 py-1.5" title="Reset"><i class="fas fa-sync-alt"></i></button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0 table-qc table-stack" id="tableHarianCRM">
                            <thead>
                                <tr>
                                    <th>Tanggal Uji</th>
                                    <th>No. Lembar Kerja</th>
                                    <th>Botol CRM (Lot)</th>
                                    <th>Parameter</th>
                                    <th>True Value ± U</th>
                                    <th>Nilai Uji (Akhir)</th>
                                    <th>Analis</th>
                                    <th>Status Harian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kegiatanList as $log)
                                <tr>
                                    <td data-label="Tanggal Uji"
                                        data-order="{{ $log->tanggal_uji->format('Y-m-d') }}"
                                        data-periode="{{ $log->tanggal_uji->format('Y-m') }}">{{ $log->tanggal_uji->format('d-m-Y') }}</td>
                                    <td data-label="No. Lembar Kerja">
                                        <span class="badge bg-light text-dark border"><i class="fas fa-file-alt text-secondary me-1"></i> {{ $log->no_lembar_kerja ?? '-' }}</span>
                                    </td>
                                    <td data-label="Botol CRM">
                                        <div>
                                            <span class="badge bg-primary">{{ $log->crmKatalog->nomor_lot ?? '-' }}</span><br>
                                            <small class="text-muted">{{ $log->crmKatalog->nama_produk ?? '' }}</small>
                                        </div>
                                    </td>
                                    <td data-label="Parameter"><span class="badge bg-secondary">{{ $log->parameterUji->nama_parameter ?? '-' }}</span></td>
                                    <td data-label="True Value ± U" class="text-muted">{{ number_format($log->cert_value, 2) }} &plusmn; {{ number_format($log->cert_u, 4) }}</td>
                                    <td data-label="Nilai Uji" class="fw-bold">{{ number_format($log->nilai_akhir, 2) }}</td>
                                    <td data-label="Analis">{{ $log->analis->nama ?? 'Unknown' }}</td>
                                    <td data-label="Status">
                                        @if($log->status_evaluasi === 'inlier')
                                            <span class="badge bg-success"><i class="fas fa-check-circle"></i> Inlier</span>
                                        @elseif($log->status_evaluasi === 'outlier')
                                            <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Outlier</span>
                                        @elseif($log->status_evaluasi === 'draft')
                                            <a href="{{ route('qc-crm.create', ['resume' => 1, 'crm_id' => $log->crm_katalog_id]) }}" class="btn btn-sm btn-warning fw-bold shadow-sm" style="font-size: 0.72rem;">
                                                <i class="fas fa-edit me-1"></i> Lanjutkan Draft
                                            </a>
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

        {{-- ================= TAB 3: CONTROL CHART ================= --}}
        <div class="tab-pane fade" id="chartCrm" role="tabpanel" aria-labelledby="chart-tab">
            <div class="card shadow-sm border-0">
                <div class="card-body p-2 p-md-3">
                    <h6 class="fw-bold mb-3"><i class="fas fa-chart-area me-2" style="color: #1b3152;"></i>Control Chart CRM</h6>

                    {{-- FILTER --}}
                    <div class="row g-2 mb-3 align-items-end">
                        <div class="col-12 col-md-4">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.72rem;">Pilih Botol CRM</label>
                            <select id="chartFilterBotol" class="form-select form-select-sm select2-qc">
                                <option value="">-- Pilih Botol --</option>
                                @foreach($katalogs->where('is_active', true) as $k)
                                    <option value="{{ $k->id }}">{{ $k->nomor_lot }} - {{ $k->nama_produk }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.72rem;">Pilih Parameter Uji</label>
                            <select id="chartFilterParam" class="form-select form-select-sm select2-qc" disabled>
                                <option value="">-- Pilih Botol dulu --</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4 d-grid d-md-block">
                            <button id="btnLoadChart" class="btn btn-corporate-blue btn-sm shadow-sm fw-semibold px-3" style="font-size: 0.8rem;" disabled>
                                <i class="fas fa-sync-alt me-1"></i> Tampilkan Chart
                            </button>
                        </div>
                    </div>

                    {{-- ALERT TREND --}}
                    <div id="alertTrend" class="alert alert-warning border-start border-4 border-warning shadow-sm py-2 d-none" role="alert" style="font-size: 0.8rem;">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Terdeteksi Trend!</strong> <span id="alertTrendMsg"></span>
                    </div>

                    {{-- GRAFIK --}}
                    <div id="chartContainer" style="display: none;">
                        <div class="row g-2 mb-3" id="chartLegendBoxes"></div>
                        <div class="chart-wrap">
                            <canvas id="crmControlChart"></canvas>
                        </div>

                        <hr class="my-3">
                        <h6 class="fw-bold mb-2" style="font-size: 0.85rem;"><i class="fas fa-table me-2" style="color: #1b3152;"></i>Tabel Data Control Chart</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover text-center align-middle mb-0 table-qc">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Uji</th>
                                        <th>Analis</th>
                                        <th>Nilai Akhir</th>
                                        <th>True Value &plusmn; U</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="chartTableBody"></tbody>
                            </table>
                        </div>
                    </div>

                    {{-- PESAN KOSONG --}}
                    <div id="chartEmpty" class="text-center py-4 text-muted">
                        <i class="fas fa-chart-line fa-3x mb-3 opacity-25"></i>
                        <p class="mb-0">Pilih Botol CRM dan Parameter Uji, lalu klik <strong>Tampilkan Chart</strong>.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Library --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script>window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.7.0.min.js"><\/script>');</script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
$(function () {

    // ---------- Select2 (sama seperti alat/index) ----------
    $('.select2-qc').select2({ theme: 'bootstrap-5', width: '100%' });

    // ---------- TAB 1: filter master botol ----------
    function filterKatalog() {
        const q  = $('#katalogSearch').val().toLowerCase().trim();
        const st = $('#filterStatusBotol').val();
        const ex = $('#filterExpiry').val();
        let shown = 0;

        document.querySelectorAll('#tableKatalog tbody tr.katalog-row').forEach(tr => {
            const ok = (!q  || tr.dataset.search.includes(q))
                    && (!st || tr.dataset.status === st)
                    && (!ex || tr.dataset.expiry === ex);
            tr.style.display = ok ? '' : 'none';
            if (ok) shown++;
        });

        const hasRows = document.querySelectorAll('#tableKatalog tbody tr.katalog-row').length > 0;
        document.getElementById('katalogNoResult').classList.toggle('d-none', shown > 0 || !hasRows);
    }

    let katalogTimer = null;
    $('#katalogSearch').on('input', function () {
        clearTimeout(katalogTimer);
        katalogTimer = setTimeout(filterKatalog, 250);
    });
    $('#filterStatusBotol, #filterExpiry').on('change', filterKatalog);
    $('#btnResetKatalog').on('click', function () {
        $('#katalogSearch').val('');
        $('#filterStatusBotol, #filterExpiry').val('').trigger('change.select2');
        filterKatalog();
    });

    // ---------- TAB 2: DataTables riwayat harian ----------
    const tableHarian = $('#tableHarianCRM').DataTable({
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        order: [],
        pageLength: 10,
        language: {
            lengthMenu: "Tampil _MENU_ data",
            info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ riwayat",
            infoEmpty: "Tidak ada data riwayat",
            paginate: {
                first: "Awal", last: "Akhir",
                next: "<i class='fas fa-chevron-right' style='font-size:10px'></i>",
                previous: "<i class='fas fa-chevron-left' style='font-size:10px'></i>"
            }
        }
    });

    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        if (settings.nTable.id !== 'tableHarianCRM') return true;

        const filterParam   = $('#filterParam').val();
        const filterPeriode = $('#filterPeriode').val(); // "YYYY-MM"

        const rowNode    = tableHarian.row(dataIndex).node();
        const rowPeriode = $(rowNode).find('td:eq(0)').data('periode');
        const rowParam   = (data[3] || '').trim(); // kolom ke-4 = Parameter

        if (filterParam && rowParam !== filterParam) return false;
        if (filterPeriode && rowPeriode !== filterPeriode) return false;
        return true;
    });

    $('#filterParam, #filterPeriode').on('change', function () { tableHarian.draw(); });
    $('#btnResetFilter').on('click', function () {
        $('#filterParam').val('').trigger('change.select2');
        $('#filterPeriode').val('');
        tableHarian.draw();
    });

    // ---------- TAB 3: Control Chart ----------
    let crmChart = null;
    if (typeof ChartDataLabels !== 'undefined') Chart.register(ChartDataLabels);

    $('#chartFilterBotol').on('change', function () {
        const $param = $('#chartFilterParam');
        const btnLoad = document.getElementById('btnLoadChart');
        btnLoad.disabled = true;

        if (!this.value) {
            $param.html('<option value="">-- Pilih Botol dulu --</option>').prop('disabled', true).trigger('change.select2');
            return;
        }

        $param.html('<option value="">-- Memuat... --</option>').prop('disabled', true).trigger('change.select2');

        fetch('/api/crm-katalog/' + this.value + '/parameters')
            .then(r => r.json())
            .then(data => {
                let opts = '<option value="">-- Pilih Parameter --</option>';
                data.forEach(item => {
                    const nama = item.parameter_uji ? item.parameter_uji.nama_parameter : 'Parameter #' + item.parameter_uji_id;
                    opts += '<option value="' + item.parameter_uji_id + '">' + nama + '</option>';
                });
                $param.html(opts).prop('disabled', false).trigger('change.select2');
            });
    });

    $('#chartFilterParam').on('change', function () {
        document.getElementById('btnLoadChart').disabled = !this.value;
    });

    document.getElementById('btnLoadChart').addEventListener('click', function () {
        const katalogId = $('#chartFilterBotol').val();
        const paramId   = $('#chartFilterParam').val();
        if (!katalogId || !paramId) return;

        const btn = this;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memuat...';
        btn.disabled = true;

        const resetBtn = () => {
            btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Tampilkan Chart';
            btn.disabled = false;
        };

        fetch('{{ route("qc-crm.chart.data") }}?crm_katalog_id=' + katalogId + '&parameter_uji_id=' + paramId)
            .then(r => r.json())
            .then(result => {
                resetBtn();

                if (!result.logs || result.logs.length === 0) {
                    document.getElementById('chartContainer').style.display = 'none';
                    document.getElementById('chartEmpty').innerHTML = '<i class="fas fa-inbox fa-3x mb-3 opacity-25"></i><p class="text-muted mb-0">Belum ada data pengujian harian final untuk kombinasi ini.</p>';
                    document.getElementById('chartEmpty').style.display = 'block';
                    document.getElementById('alertTrend').classList.add('d-none');
                    return;
                }

                document.getElementById('chartEmpty').style.display = 'none';
                document.getElementById('chartContainer').style.display = 'block';
                renderChart(result);
            })
            .catch(resetBtn);
    });

    function renderChart(result) {
        const logs = result.logs;
        const certVal = result.cert_value;
        const certU = result.cert_u;
        const batasAtas = certVal + certU;
        const batasBawah = certVal - certU;

        // Alert trend
        const alertEl = document.getElementById('alertTrend');
        if (result.trend_warning) {
            alertEl.classList.remove('d-none');
            document.getElementById('alertTrendMsg').textContent =
                '7 titik berturut-turut berada di ' + result.trend_warning + ' True Value. Disarankan melakukan pengecekan/kalibrasi alat.';
        } else {
            alertEl.classList.add('d-none');
        }

        // Kotak legenda
        document.getElementById('chartLegendBoxes').innerHTML = `
            <div class="col-md-3 col-6"><div class="p-2 border rounded text-center bg-danger bg-opacity-10"><small class="d-block text-muted">Batas Atas (TV + U)</small><strong class="text-danger">${batasAtas.toFixed(4)}</strong></div></div>
            <div class="col-md-3 col-6"><div class="p-2 border rounded text-center bg-success bg-opacity-10"><small class="d-block text-muted">True Value (Sertifikat)</small><strong class="text-success">${certVal.toFixed(4)}</strong></div></div>
            <div class="col-md-3 col-6"><div class="p-2 border rounded text-center bg-danger bg-opacity-10"><small class="d-block text-muted">Batas Bawah (TV - U)</small><strong class="text-danger">${batasBawah.toFixed(4)}</strong></div></div>
            <div class="col-md-3 col-6"><div class="p-2 border rounded text-center"><small class="d-block text-muted">Jumlah Data</small><strong>${logs.length} Pengujian</strong></div></div>
        `;

        // Data titik
        const labels = [], dataPoints = [], pointColors = [], pointRadii = [];
        logs.forEach((log, idx) => {
            labels.push(log.tanggal_uji);
            dataPoints.push(parseFloat(log.nilai_akhir));

            const isTrend = result.trend_indices && result.trend_indices.includes(idx);
            if (log.status_evaluasi === 'outlier') {
                pointColors.push('rgba(220, 53, 69, 1)'); pointRadii.push(7);
            } else if (isTrend) {
                pointColors.push('rgba(255, 152, 0, 1)'); pointRadii.push(6);
            } else {
                pointColors.push('rgba(0, 0, 0, 1)'); pointRadii.push(4);
            }
        });

        const len = Math.max(10, labels.length);
        for (let i = labels.length; i < 10; i++) labels.push('...');

        if (crmChart) crmChart.destroy();

        const ctx = document.getElementById('crmControlChart').getContext('2d');
        crmChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Nilai QC',
                        data: dataPoints,
                        borderColor: 'rgba(0, 0, 0, 0.6)',
                        backgroundColor: 'transparent',
                        pointBackgroundColor: pointColors,
                        pointBorderColor: pointColors,
                        pointRadius: pointRadii,
                        pointHoverRadius: 8,
                        borderWidth: 2,
                        tension: 0.3,
                        order: 0,
                        datalabels: {
                            align: 'top', anchor: 'end',
                            color: '#333', font: { weight: 'bold', size: 11 },
                            formatter: v => parseFloat(v).toFixed(2)
                        }
                    },
                    { label: 'True Value', data: Array(len).fill(certVal), borderColor: 'rgba(25, 135, 84, 0.9)', borderWidth: 2, pointRadius: 0, order: 1, datalabels: { display: false } },
                    { label: 'Batas Atas (TV + U)', data: Array(len).fill(batasAtas), borderColor: 'rgba(220, 53, 69, 0.7)', borderWidth: 2, borderDash: [6, 4], pointRadius: 0, order: 2, datalabels: { display: false } },
                    { label: 'Batas Bawah (TV - U)', data: Array(len).fill(batasBawah), borderColor: 'rgba(220, 53, 69, 0.7)', borderWidth: 2, borderDash: [6, 4], pointRadius: 0, order: 3, datalabels: { display: false } }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (c) {
                                if (c.datasetIndex === 0) {
                                    const log = logs[c.dataIndex];
                                    if (!log) return 'Nilai: ' + c.raw;
                                    return 'Nilai: ' + parseFloat(c.raw).toFixed(4) + ' | ' + log.status_evaluasi.toUpperCase();
                                }
                                return c.dataset.label + ': ' + parseFloat(c.raw).toFixed(4);
                            }
                        }
                    },
                    legend: { position: 'bottom', labels: { boxWidth: 20, font: { size: 11 } } }
                },
                scales: {
                    y: {
                        suggestedMax: batasAtas + (certU * 0.5),
                        suggestedMin: batasBawah - (certU * 0.5)
                    }
                }
            }
        });

        // Tabel data
        let tbody = '';
        logs.forEach((log, idx) => {
            const isOutlier = log.status_evaluasi === 'outlier';
            const isTrend = result.trend_indices && result.trend_indices.includes(idx);
            let statusLabel = isOutlier ? '<span class="badge bg-danger">Outlier</span>' : '<span class="badge bg-success">Inlier</span>';
            if (isTrend) statusLabel += ' <span class="badge bg-warning text-dark">Trend</span>';

            tbody += '<tr' + (isTrend ? ' class="table-warning"' : '') + '>' +
                '<td>' + (idx + 1) + '</td>' +
                '<td>' + log.tanggal_uji + '</td>' +
                '<td>' + (log.analis ? log.analis.nama : '-') + '</td>' +
                '<td class="' + (isOutlier ? 'text-danger fw-bold' : 'text-success') + '">' + parseFloat(log.nilai_akhir).toFixed(4) + '</td>' +
                '<td class="text-nowrap">' + certVal.toFixed(4) + ' &plusmn; ' + certU.toFixed(4) + '</td>' +
                '<td>' + statusLabel + '</td>' +
                '</tr>';
        });
        document.getElementById('chartTableBody').innerHTML = tbody;
    }
});
</script>
@endsection