@extends('layouts.app')
@section('title', 'dashboard-index - views')

@section('content')
<style>
    .dashboard-container {
        padding: 2px 20px !important;
    }

    .dashboard-card {
        padding: 10px !important;
    }
    .dashboard-card h2 {
        font-size: 1.75rem !important;
    }
    .dashboard-card p {
        font-size: 0.75rem !important;
    }

    .info-card .card-body {
        padding: 10px !important;
    }
    .info-card h5 {
        font-size: 1rem !important;
    }
    .select2-container--bootstrap-5 .select2-dropdown {
        top: 100% !important;
        bottom: auto !important;
    }

    .tracking-card .card-body {
        padding: 10px !important;
    }

    .border-suco-biru-tua { border-color: #005aa5 !important; }
    .text-suco-biru-tua { color: #005aa5 !important; }
    .border-suco-biru { border-color: #0082c4 !important; }
    .text-suco-biru { color: #0082c4 !important; }
    .border-suco-biru-muda { border-color: #00a3e2 !important; }
    .text-suco-biru-muda { color: #00a3e2 !important; }
    .border-suco-hijau { border-color: #00a39b !important; }
    .text-suco-hijau { color: #00a39b !important; }

    .text-navy {
        color: #1b3152 !important;
    }
    .bg-navy {
        background-color: #1b3152 !important;
        color: #ffffff !important;
    }
    .border-navy {
        border-color: #1b3152 !important;
    }
    .btn-outline-navy {
        color: #1b3152;
        border-color: #1b3152;
        background-color: transparent;
        transition: all 0.2s ease-in-out;
    }
    .btn-outline-navy:hover,
    .btn-outline-navy:focus,
    .btn-outline-navy:active {
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
        color: #ffffff !important;
    }

    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #1b3152 !important;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15) !important;
    }
    .tracking-select-wrap {
        position: relative;
        display: inline-block;
        width: 100%;
        max-width: 300px;
    }
    .tracking-select-wrap > .select2-container:not(.select2) {
        top: 100% !important;
        bottom: auto !important;
        left: 0 !important;
        right: auto !important;
        margin-top: 0.15rem;
        width: 100% !important;
    }
    .select-tracking-dropdown .select2-search__field:focus {
        border-color: #1b3152 !important;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15) !important;
        outline: none;
    }
    .select-tracking-dropdown .select2-results__options {
        max-height: calc(3 * 1.95rem);
        overflow-y: auto;
    }
    .select-tracking-dropdown .select2-results__option {
        padding: 0.4rem 0.75rem;
        font-size: 0.8rem;
        line-height: 1.4;
    }
    .select-tracking-dropdown .select2-results__option--highlighted,
    .select-tracking-dropdown .select2-results__option--highlighted[aria-selected] {
        background-color: rgba(27, 49, 82, 0.15) !important;
        color: #1b3152 !important;
    }
    .select-tracking-dropdown .select2-results__option--selected,
    .select-tracking-dropdown .select2-results__option[aria-selected=true] {
        background-color: #1b3152 !important;
        color: #ffffff !important;
    }

    .tracking-steps-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .tracking-steps-inner {
        display: flex;
        min-width: 480px;
    }

    .summary-card-body {
        padding: 10px 12px !important;
    }
    .summary-card-value {
        font-size: 1.4rem;
        line-height: 1.1;
    }
    .summary-card-label {
        font-size: 0.68rem;
        letter-spacing: 0.5px;
    }
    .summary-card-link {
        font-size: 0.72rem;
    }

    @media (min-width: 992px) {
        .summary-card-body {
            display: grid;
            grid-template-columns: 1fr auto;
            column-gap: 12px;
            align-items: center;
        }
        .summary-card-label {
            grid-column: 1;
            grid-row: 1;
        }
        .summary-card-link {
            grid-column: 1;
            grid-row: 2;
            justify-self: start;
        }
        .summary-card-value {
            grid-column: 2;
            grid-row: 1 / span 2;
            font-size: 2rem;
        }
    }

    @media (max-width: 575.98px) {
        .summary-card-body {
            padding: 8px 8px !important;
        }
        .summary-card-value {
            font-size: 1.2rem;
        }
        .summary-card-label {
            font-size: 0.56rem;
            letter-spacing: 0.2px;
            line-height: 1.2;
            min-height: 2.4em;
        }
        .summary-card-link {
            font-size: 0.6rem;
            line-height: 1.2;
        }
    }

    @media (max-width: 360px) {
        .summary-card-label {
            font-size: 0.5rem;
        }
        .summary-card-value {
            font-size: 1.05rem;
        }
        .summary-card-link {
            font-size: 0.54rem;
        }
    }

    @media (max-width: 575.98px) {
        .tracking-card .card-header {
            flex-direction: column;
            align-items: stretch !important;
        }
        .tracking-select-wrap {
            max-width: 100%;
            width: 100%;
        }
        .tracking-select-wrap .select2-container {
            width: 100% !important;
        }
    }

    .info-card-link {
        text-decoration: none;
        color: inherit;
        display: block;
        height: 100%;
    }
    .info-card-link:focus-visible {
        outline: 2px solid #1b3152;
        outline-offset: 2px;
        border-radius: 0.375rem;
    }
    .info-card-clickable {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        cursor: pointer;
    }
    .info-card-clickable:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.12) !important;
    }
    .info-card-icon {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .info-card-icon.bg-danger-soft { background-color: #fdeaea; color: #dc3545; }
    .info-card-icon.bg-warning-soft { background-color: #fff8e6; color: #f0ad4e; }
    .info-card-icon.bg-navy-soft { background-color: rgba(27, 49, 82, 0.1); color: #1b3152; }

    .info-card-badge {
        font-size: 0.85rem !important;
        padding: 0.35rem 0.65rem !important;
        min-width: 32px;
    }
    .info-card-badge.pulse-danger {
        animation: pulseDanger 1.6s infinite;
    }
    .info-card-badge.pulse-warning {
        animation: pulseWarning 1.6s infinite;
    }
    .info-card-badge.pulse-navy {
        animation: pulseNavy 1.6s infinite;
    }
    @keyframes pulseDanger {
        0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.5); }
        70% { box-shadow: 0 0 0 7px rgba(220, 53, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
    @keyframes pulseWarning {
        0% { box-shadow: 0 0 0 0 rgba(240, 173, 78, 0.5); }
        70% { box-shadow: 0 0 0 7px rgba(240, 173, 78, 0); }
        100% { box-shadow: 0 0 0 0 rgba(240, 173, 78, 0); }
    }
    @keyframes pulseNavy {
        0% { box-shadow: 0 0 0 0 rgba(27, 49, 82, 0.5); }
        70% { box-shadow: 0 0 0 7px rgba(27, 49, 82, 0); }
        100% { box-shadow: 0 0 0 0 rgba(27, 49, 82, 0); }
    }

    .info-card-alert-btn {
        pointer-events: none;
    }
</style>

<div class="container-fluid dashboard-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold text-dark mb-1" style="font-size: 1.5rem;">Monitoring Center</h2>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Selamat datang, Anda login sebagai <strong class="text-navy">{{ $role }}</strong></p>
        </div>
    </div>

    <div class="row g-2 g-sm-3 mb-3">
        <div class="col-4">
            <a href="{{ route('kegiatan.index') }}" class="info-card-link" aria-label="Buka modul QC">
                <div class="card border-0 shadow-sm border-start border-4 border-suco-biru h-100 info-card-clickable">
                    <div class="card-body summary-card-body">
                        <p class="text-muted mb-0 text-uppercase fw-bold summary-card-label">PENGUJIAN AKTIF</p>
                        <h3 class="fw-bold text-dark mb-0 summary-card-value">{{ $kegiatanBerjalan }}</h3>
                        <span class="text-suco-biru fw-semibold d-inline-block summary-card-link">
                            Buka Modul QC
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-4">
            <a href="{{ route('alat.index') }}" class="info-card-link" aria-label="Cek alat yang masa kalibrasinya hampir habis">
                <div class="card border-0 shadow-sm border-start border-4 border-suco-biru-muda h-100 info-card-clickable">
                    <div class="card-body summary-card-body">
                        <p class="text-muted mb-0 text-uppercase fw-bold summary-card-label">KALIBRASI ALAT H-6 BULAN</p>
                        <h3 class="fw-bold text-dark mb-0 summary-card-value">{{ $tenggatKalibrasi }}</h3>
                        <span class="text-suco-biru-muda fw-semibold d-inline-block summary-card-link">
                            Cek Kalibrasi
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-4">
            <a href="{{ route('sdm.index') }}" class="info-card-link" aria-label="Cek sertifikasi personil">
                <div class="card border-0 shadow-sm border-start border-4 border-suco-hijau h-100 info-card-clickable">
                    <div class="card-body summary-card-body">
                        <p class="text-muted mb-0 text-uppercase fw-bold summary-card-label">SERTIFIKASI PERSONIL H-6</p>
                        <h3 class="fw-bold text-dark mb-0 summary-card-value">{{ $sertifikasiHampirHabis }}</h3>
                        <span class="text-suco-hijau fw-semibold d-inline-block summary-card-link">
                            Cek Sertifikasi
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-md-4">
            <a href="{{ route('barang.index') }}" class="info-card-link">
                <div class="card h-100 border-0 shadow-sm info-card info-card-clickable">
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="info-card-icon bg-danger-soft">
                                    <i class="fas fa-box-open"></i>
                                </span>
                                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Stok Kritis</h6>
                                    <span class="badge bg-danger rounded-pill info-card-badge {{ $stokTipis > 0 ? 'pulse-danger' : '' }}">{{ $stokTipis }}</span>
                                </div>
                            </div>
                            <p class="text-muted small mb-3" style="font-size: 0.78rem;">Terdapat {{ $stokTipis }} item barang di bawah batas minimum.</p>
                        </div>
                        <div>
                            <span class="btn btn-outline-danger btn-sm w-100 py-1 info-card-alert-btn" style="font-size: 0.78rem;">Cek Inventori</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="{{ route('barang.index') }}" class="info-card-link">
                <div class="card h-100 border-0 shadow-sm info-card info-card-clickable">
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="info-card-icon bg-warning-soft">
                                    <i class="fas fa-calendar-times"></i>
                                </span>
                                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Akan Kedaluwarsa</h6>
                                    <span class="badge bg-warning text-dark rounded-pill info-card-badge {{ $barangExp > 0 ? 'pulse-warning' : '' }}">{{ $barangExp }}</span>
                                </div>
                            </div>
                            <p class="text-muted small mb-3" style="font-size: 0.78rem;">Terdapat {{ $barangExp }} bahan kedaluwarsa dalam 180 hari.</p>
                        </div>
                        <div>
                            <span class="btn btn-outline-warning btn-sm w-100 py-1 info-card-alert-btn" style="font-size: 0.78rem;">Cek Expired Date</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="{{ route('pengadaan.index') ?? '#' }}" class="info-card-link">
                <div class="card h-100 border-0 shadow-sm info-card info-card-clickable">
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="info-card-icon bg-navy-soft">
                                    <i class="fas fa-shopping-cart"></i>
                                </span>
                                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">Approval Pengadaan</h6>
                                    <span class="badge bg-navy rounded-pill info-card-badge {{ $pengadaanPending > 0 ? 'pulse-navy' : '' }}">{{ $pengadaanPending }}</span>
                                </div>
                            </div>
                            <p class="text-muted small mb-3" style="font-size: 0.78rem;">Ada {{ $pengadaanPending }} pengajuan yang butuh persetujuan.</p>
                        </div>
                        <div>
                            <span class="btn btn-outline-navy btn-sm w-100 py-1 info-card-alert-btn" style="font-size: 0.78rem;">Proses Pengajuan</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 tracking-card">
            <div class="card-header bg-navy py-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="fw-bold mb-0 text-white" style="font-size: 0.95rem;">
                    <i class="fas fa-route text-white me-2"></i>Tracking Status Pengadaan Barang
                </h5>

                <div id="trackingSelectWrap" class="tracking-select-wrap">
                    <select id="selectTracking" class="form-select form-select-sm w-100">
                        <option value="">-- Pilih Barang/Bahan --</option>
                        @foreach($pengadaanAktif as $p)
                            <option value="track-{{ $p->permintaan_id }}" {{ $loop->first ? 'selected' : '' }}>
                                {{ $p->barang->nama_barang ?? 'Barang' }} ({{ $p->barang->kode_barang ?? 'Tanpa Kode' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="card-body p-3">
                @forelse($pengadaanAktif as $p)
                    @php
                        $isTerlambat = false;
                        if ($p->status != 'selesai') {
                            $batasWaktu = \Carbon\Carbon::parse($p->created_at)->addDays(30);
                            if (\Carbon\Carbon::now()->isAfter($batasWaktu)) {
                                $isTerlambat = true;
                            }
                        }
                    @endphp

                    <div id="track-{{ $p->permintaan_id }}" class="tracking-item" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-12 border-bottom pb-2">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                    <h6 class="fw-bold mb-0 text-navy" style="font-size: 0.95rem;">
                                        {{ $p->barang->nama_barang ?? '-' }} ({{ $p->barang->kode_barang ?? 'Tanpa Kode' }})
                                    </h6>

                                    @if($isTerlambat)
                                        <span class="badge bg-danger animate-pulse" style="font-size: 0.68rem;">
                                            <i class="fas fa-exclamation-triangle me-1"></i> Terlambat / Overdue
                                        </span>
                                    @endif
                                </div>
                                <div class="text-muted" style="font-size: 0.81rem;">
                                    <span>Jumlah: <b class="text-dark">{{ (float) $p->jumlah_diminta }} {{ $p->barang->satuan ?? '' }}</b></span><br>
                                    <span>Target Waktu Pengadaan: <b class="{{ $isTerlambat ? 'text-danger' : 'text-dark' }}">{{ $p->format_target_waktu ?? '-' }}</b></span><br>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center px-2">
                            <div class="col-12">
                                <div class="tracking-steps-scroll">
                                    <div class="tracking-steps-inner d-flex justify-content-between text-center position-relative px-3 py-1">
                                        <div class="progress position-absolute w-100" style="height: 3px; top: 14px; z-index: 1; left: 0;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 
                                                @if($p->status == 'menunggu_koordinator' || $p->status == 'diajukan') 12% 
                                                @elseif($p->status == 'menunggu_ga') 50% 
                                                @elseif($p->status == 'disetujui') 62% 
                                                @elseif(in_array($p->status, ['diproses', 'diproses_po', 'pembelian'])) 68% 
                                                @elseif($p->status == 'selesai') 100% 
                                                @else 0% @endif">
                                            </div>
                                        </div>

                                        <div class="position-relative text-center" style="z-index: 2; flex: 1;">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm bg-success text-white" style="width: 30px; height: 30px; font-size: 0.7rem;">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                            <div class="mt-1">
                                                <span class="fw-bold d-block text-success" style="font-size:0.68rem;">1. Diajukan</span>
                                                <small class="text-muted d-block" style="font-size:0.6rem;">
                                                    Oleh: {{ $p->pemohon ? $p->pemohon->username : '-' }}<br>
                                                    {{ \Carbon\Carbon::parse($p->created_at)->format('d M Y, H:i') }}
                                                </small>
                                            </div>
                                        </div>

                                        @php
                                            $isStuckKoordinator = $isTerlambat && $p->status == 'diajukan';
                                        @endphp
                                        <div class="position-relative text-center" style="z-index: 2; flex: 1;">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm {{ in_array($p->status, ['menunggu_ga', 'disetujui', 'diproses', 'diproses_po', 'pembelian', 'selesai']) ? 'bg-success text-white' : ($isTerlambat ? 'bg-danger text-white' : 'bg-secondary text-white') }}" style="width: 30px; height: 30px; font-size: 0.7rem;">
                                                <i class="fas fa-user-check"></i>
                                            </div>
                                            <div class="mt-1">
                                                <span class="fw-bold d-block {{ in_array($p->status, ['menunggu_ga', 'disetujui', 'diproses', 'diproses_po', 'pembelian', 'selesai']) ? 'text-success' : ($isTerlambat ? 'text-danger' : '') }}" style="font-size:0.68rem;">2. Koordinator</span>
                                                <small class="text-muted d-block" style="font-size:0.6rem;">
                                                    @if(in_array($p->status, ['menunggu_ga', 'disetujui', 'diproses', 'diproses_po', 'pembelian', 'selesai']))
                                                        <span class="text-success fw-semibold">Disetujui</span>
                                                    @else
                                                        <span class="{{ $isTerlambat ? 'text-danger fw-semibold' : 'text-warning fw-semibold' }}">
                                                            {{ $isTerlambat ? 'Terlambat / Menunggu' : 'Menunggu' }}
                                                        </span>
                                                    @endif
                                                </small>
                                            </div>
                                        </div>

                                        @php
                                            $isStuckGA = $isTerlambat && in_array($p->status, ['menunggu_ga', 'disetujui']);
                                        @endphp
                                        <div class="position-relative text-center" style="z-index: 2; flex: 1;">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm {{ in_array($p->status, ['diproses', 'diproses_po', 'pembelian', 'selesai']) ? 'bg-success text-white' : ($isTerlambat ? 'bg-danger text-white' : 'bg-secondary text-white') }}" style="width: 30px; height: 30px; font-size: 0.7rem;">
                                                <i class="fas fa-building"></i>
                                            </div>
                                            <div class="mt-1">
                                                <span class="fw-bold d-block {{ in_array($p->status, ['diproses', 'diproses_po', 'pembelian', 'selesai']) ? 'text-success' : ($isTerlambat ? 'text-danger' : '') }}" style="font-size:0.68rem;">3. GA Approval</span>
                                                <small class="text-muted d-block" style="font-size:0.6rem;">
                                                    @if(in_array($p->status, ['diproses', 'diproses_po', 'pembelian', 'selesai']))
                                                        <span class="text-success fw-semibold">Disetujui</span>
                                                    @else
                                                        <span class="{{ $isTerlambat ? 'text-danger fw-semibold' : '' }}">
                                                            {{ $isTerlambat ? 'Terlambat / Menunggu' : 'Menunggu' }}
                                                        </span>
                                                    @endif
                                                </small>
                                            </div>
                                        </div>

                                        <div class="position-relative text-center" style="z-index: 2; flex: 1;">
                                            @php
                                                $isStuckProses = $isTerlambat && in_array($p->status, ['diproses', 'diproses_po', 'pembelian']);
                                            @endphp
                                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm {{ in_array($p->status, ['diproses', 'diproses_po', 'pembelian', 'selesai']) ? ($isStuckProses ? 'bg-danger text-white' : 'bg-success text-white') : 'bg-secondary text-white' }}" style="width: 30px; height: 30px; font-size: 0.7rem;">
                                                <i class="fas fa-box-open"></i>
                                            </div>
                                            <div class="mt-1">
                                                <span class="fw-bold d-block {{ $isStuckProses ? 'text-danger' : (in_array($p->status, ['diproses', 'diproses_po', 'pembelian', 'selesai']) ? 'text-success' : '') }}" style="font-size:0.68rem;">4. Diproses</span>
                                                <small class="text-muted d-block" style="font-size:0.6rem;">
                                                    @if(in_array($p->status, ['diproses', 'diproses_po', 'pembelian', 'selesai']))
                                                        <span class="{{ $isStuckProses ? 'text-danger fw-semibold' : 'fw-semibold' }}">
                                                            {{ $isStuckProses ? 'Terlambat / Tertahan' : 'Sedang Diproses' }}
                                                        </span>
                                                    @else
                                                        Belum
                                                    @endif
                                                </small>
                                            </div>
                                        </div>

                                        <div class="position-relative text-center" style="z-index: 2; flex: 1;">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm {{ $p->status == 'selesai' ? 'bg-success text-white' : 'bg-secondary text-white' }}" style="width: 30px; height: 30px; font-size: 0.7rem;">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <div class="mt-1">
                                                <span class="fw-bold d-block {{ $p->status == 'selesai' ? 'text-success' : '' }}" style="font-size:0.68rem;">5. Diterima</span>
                                                <small class="text-muted d-block" style="font-size:0.6rem;">
                                                    @if($p->status == 'selesai')
                                                        Selesai
                                                    @else
                                                        Menunggu Tiba
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-2 text-muted">
                        <i class="fas fa-check-circle fa-2x mb-1 text-success"></i>
                        <p class="mb-0 small">Tidak ada pengadaan barang yang berjalan saat ini.</p>
                    </div>
                @endforelse

                <div id="trackingPlaceholder" class="text-center py-2 text-muted" style="display: none;">
                    <i class="fas fa-hand-pointer fa-lg mb-1 text-navy"></i>
                    <p class="mb-0 small">Silakan pilih salah satu barang di menu dropdown atas untuk melihat rincian alur tracking prosesnya.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const select = $('#selectTracking');
        const placeholder = document.getElementById('trackingPlaceholder');

        function updateTracking() {
            document.querySelectorAll('.tracking-item').forEach(el => el.style.display = 'none');

            const selectedId = select.val();
            if (selectedId) {
                if (placeholder) placeholder.style.display = 'none';
                const targetEl = document.getElementById(selectedId);
                if (targetEl) targetEl.style.display = 'block';
            } else {
                if (placeholder) placeholder.style.display = 'block';
            }
        }

        if (select.length) {
            select.select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $('#trackingSelectWrap'),
                minimumResultsForSearch: 0,
                allowClear: false,
                dropdownCssClass: 'select-tracking-dropdown',
                language: {
                    noResults: function() {
                        return 'Barang tidak ditemukan';
                    }
                }
            });

            select.on('select2:open', function() {
                $('#trackingSelectWrap .select2-dropdown--above').removeClass('select2-dropdown--above').addClass('select2-dropdown--below');
                $('#trackingSelectWrap .select2-container--above').removeClass('select2-container--above').addClass('select2-container--below');
                const field = document.querySelector('#trackingSelectWrap .select2-search__field');
                if (field) {
                    field.setAttribute('placeholder', 'Cari barang/bahan...');
                    field.focus();
                }
            });

            updateTracking();
            select.on('change', updateTracking);
        }
    });
</script>
@endpush
</div>
@endsection