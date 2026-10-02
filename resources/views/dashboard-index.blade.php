@extends('layouts.app')
@section('title', 'dashboard-index - views')

@section('content')
<style>
    .dashboard-container {
        padding: 2px 20px !important;
    }

    .info-card .card-body {
        padding: 10px !important;
    }
    .select2-container--bootstrap-5 .select2-dropdown {
        top: 100% !important;
        bottom: auto !important;
    }

    .tracking-card .card-body {
        padding: 10px !important;
    }

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
        max-width: 320px;
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
    .tracking-step-icon {
        width: 30px;
        height: 30px;
        font-size: 0.7rem;
        text-decoration: none;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .tracking-step-icon:hover,
    .tracking-step-icon:focus-visible {
        transform: scale(1.15);
        color: #ffffff;
        box-shadow: 0 0 0 4px rgba(27, 49, 82, 0.12) !important;
    }

    .summary-card-body {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px !important;
    }
    .summary-card-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .summary-card-icon.icon-biru { background-color: rgba(0, 130, 196, 0.12); color: #0082c4; }
    .summary-card-icon.icon-biru-muda { background-color: rgba(0, 163, 226, 0.12); color: #00a3e2; }
    .summary-card-icon.icon-hijau { background-color: rgba(0, 163, 155, 0.12); color: #00a39b; }
    .summary-card-text {
        min-width: 0;
    }
    .summary-card-label {
        font-size: 0.78rem;
        letter-spacing: 0.4px;
        line-height: 1.25;
    }
    .summary-card-value {
        font-size: 1.9rem;
        line-height: 1.1;
        margin: 2px 0 !important;
    }
    .summary-card-link {
        font-size: 0.82rem;
    }

    @media (max-width: 991.98px) {
        .summary-card-body {
            gap: 10px;
            padding: 12px !important;
        }
        .summary-card-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        .summary-card-label {
            font-size: 0.68rem;
            letter-spacing: 0.2px;
        }
        .summary-card-value {
            font-size: 1.6rem;
        }
        .summary-card-link {
            font-size: 0.72rem;
        }
    }

    @media (max-width: 575.98px) {
        .summary-card-body {
            flex-direction: column;
            justify-content: flex-start;
            text-align: center;
            gap: 6px;
            padding: 10px 6px !important;
        }
        .summary-card-icon {
            width: 34px;
            height: 34px;
            font-size: 0.9rem;
        }
        .summary-card-label {
            font-size: 0.62rem;
            letter-spacing: 0;
            line-height: 1.2;
            min-height: 2.4em;
        }
        .summary-card-value {
            font-size: 1.5rem;
            margin: 0 !important;
        }
        .summary-card-link {
            display: none !important;
        }
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

    @media (max-width: 360px) {
        .summary-card-label {
            font-size: 0.56rem;
        }
        .summary-card-value {
            font-size: 1.3rem;
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
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.12) !important;
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
    .info-card-badge.pulse-danger { animation: pulseDanger 1.6s infinite; }
    .info-card-badge.pulse-warning { animation: pulseWarning 1.6s infinite; }
    .info-card-badge.pulse-navy { animation: pulseNavy 1.6s infinite; }
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
                        <span class="summary-card-icon icon-biru">
                            <i class="fas fa-flask"></i>
                        </span>
                        <div class="summary-card-text">
                            <p class="text-muted mb-0 text-uppercase fw-bold summary-card-label">PENGUJIAN AKTIF</p>
                            <h3 class="fw-bold text-dark mb-0 summary-card-value">{{ $kegiatanBerjalan }}</h3>
                            <span class="text-suco-biru fw-semibold d-inline-block summary-card-link">Buka Modul QC</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-4">
            <a href="{{ route('alat.index') }}" class="info-card-link" aria-label="Cek alat yang masa kalibrasinya hampir habis">
                <div class="card border-0 shadow-sm border-start border-4 border-suco-biru-muda h-100 info-card-clickable">
                    <div class="card-body summary-card-body">
                        <span class="summary-card-icon icon-biru-muda">
                            <i class="fas fa-balance-scale"></i>
                        </span>
                        <div class="summary-card-text">
                            <p class="text-muted mb-0 text-uppercase fw-bold summary-card-label">KALIBRASI ALAT H-6 BULAN</p>
                            <h3 class="fw-bold text-dark mb-0 summary-card-value">{{ $tenggatKalibrasi }}</h3>
                            <span class="text-suco-biru-muda fw-semibold d-inline-block summary-card-link">Cek Kalibrasi</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-4">
            <a href="{{ route('sdm.index') }}" class="info-card-link" aria-label="Cek sertifikasi personil">
                <div class="card border-0 shadow-sm border-start border-4 border-suco-hijau h-100 info-card-clickable">
                    <div class="card-body summary-card-body">
                        <span class="summary-card-icon icon-hijau">
                            <i class="fas fa-id-badge"></i>
                        </span>
                        <div class="summary-card-text">
                            <p class="text-muted mb-0 text-uppercase fw-bold summary-card-label">SERTIFIKASI PERSONIL H-6</p>
                            <h3 class="fw-bold text-dark mb-0 summary-card-value">{{ $sertifikasiHampirHabis }}</h3>
                            <span class="text-suco-hijau fw-semibold d-inline-block summary-card-link">Cek Sertifikasi</span>
                        </div>
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
            <a href="{{ route('pengadaan.index') }}" class="info-card-link">
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

                    @php
                        $cekArsip = fn ($p) => $p->status == 'selesai'
                            || $p->status == 'batal'
                            || str_contains(strtolower($p->status), 'ditolak');

                        $semuaPengadaan = collect($pengadaanAktif)
                            ->concat($pengadaanSelesai ?? [])
                            ->unique('permintaan_id');

                        $idAwal = optional($semuaPengadaan->first(fn ($p) => !$cekArsip($p)))->permintaan_id;
                    @endphp
                    <div id="trackingSelectWrap" class="tracking-select-wrap">
                        <select id="selectTracking" class="form-select form-select-sm w-100">
                            <option value="">-- Pilih Barang/Bahan --</option>
                            @foreach($semuaPengadaan as $p)
                                @php
                                    $namaBarang = $p->barang->nama_barang ?? 'Barang';
                                    $kodeBarang = $p->barang->kode_barang ?? 'Tanpa Kode';
                                    $statusLabel = ucwords(str_replace('_', ' ', $p->status));
                                @endphp
                                <option value="track-{{ $p->permintaan_id }}" data-arsip="{{ $cekArsip($p) ? 1 : 0 }}" {{ $p->permintaan_id == $idAwal ? 'selected' : '' }}>
                                    {{ $namaBarang }} ({{ $kodeBarang }}) - {{ $statusLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card-body p-3">
                    @forelse($semuaPengadaan as $p)
                        @php
                            $rankMap = [
                                'diajukan' => 1,
                                'menunggu_koordinator' => 1,
                                'ditolak_koordinator' => 1,
                                'ditolak' => 1,
                                'menunggu_ga' => 2,
                                'ditolak_ga' => 2,
                                'disetujui' => 3,
                                'diproses' => 4,
                                'diproses_po' => 4,
                                'pembelian' => 4,
                                'selesai' => 5,
                            ];
                            $rank = $rankMap[$p->status] ?? 1;

                            $roleAjuan = optional(optional($p->pemohon)->role)->nama_role;
                            $jenisAjuan = $roleAjuan == \App\Enums\PeranPengguna::GA_OFFICER->value
                                ? 'ga'
                                : ($roleAjuan == \App\Enums\PeranPengguna::KOORDINATOR_LAB->value ? 'koor' : 'analis');

                            $isDitolak = str_contains(strtolower($p->status), 'ditolak');

                            $isTerlambat = false;
                            if ($p->status != 'selesai' && $p->status != 'batal' && !$isDitolak) {
                                $batasWaktu = \Carbon\Carbon::parse($p->created_at)->addDays(30);
                                $isTerlambat = \Carbon\Carbon::now()->isAfter($batasWaktu);
                            }

                            $redStep = null;
                            if ($isDitolak) {
                                $redStep = $rank + 1;
                            } elseif ($isTerlambat) {
                                $redStep = $rank == 4 ? 4 : $rank + 1;
                            }

                            // Perbaikan logika persentase progress bar agar mencapai titik merah/batal
                            $effectiveRank = $redStep ? max($rank, $redStep) : $rank;
                            $progressMap = [0, 10, 30, 50, 70, 100];
                            $progressWidth = $progressMap[$effectiveRank] ?? ($effectiveRank >= 5 ? 100 : $effectiveRank * 25);

                            $namaPemohon = optional($p->pemohon)->username ?? '-';
                            $namaPenyetuju = optional($p->penyetuju)->username;

                            $tahapPenyetuju = match ($p->status) {
                                'menunggu_ga', 'ditolak_koordinator', 'ditolak' => 2,
                                'disetujui', 'ditolak_ga' => 3,
                                'diproses', 'diproses_po', 'pembelian', 'selesai' => 4,
                                default => null,
                            };

                            $aktor = function ($no) use ($tahapPenyetuju, $namaPenyetuju, $namaPemohon, $jenisAjuan, $p) {
                                if (($no == 2 && $jenisAjuan == 'koor') || ($no == 3 && $jenisAjuan == 'ga')) {
                                    return ['who' => $namaPemohon, 'at' => $p->created_at];
                                }
                                if ($no == $tahapPenyetuju && $namaPenyetuju) {
                                    return ['who' => $namaPenyetuju, 'at' => $p->tanggal_keputusan];
                                }
                                return ['who' => '-', 'at' => null];
                            };

                            $steps = [
                                1 => ['label' => 'Diajukan', 'icon' => 'fa-file-alt', 'who' => $namaPemohon, 'at' => $p->created_at],
                                2 => array_merge(['label' => 'Koordinator', 'icon' => 'fa-user-check'], $aktor(2)),
                                3 => array_merge(['label' => 'GA Approval', 'icon' => 'fa-building'], $aktor(3)),
                                4 => array_merge(['label' => 'Diproses', 'icon' => 'fa-box-open'], $aktor(4)),
                                5 => ['label' => 'Selesai', 'icon' => 'fa-check-circle', 'who' => $p->nama_penerima ?? '-', 'at' => $p->waktu_diterima],
                            ];
                        @endphp

                        <div id="track-{{ $p->permintaan_id }}" class="tracking-item" style="display: none;">
                            <div class="row mb-3">
                                <div class="col-md-12 border-bottom pb-2">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                        <h6 class="fw-bold mb-0 text-navy" style="font-size: 0.95rem;">
                                            {{ $p->barang->nama_barang ?? '-' }} ({{ $p->barang->kode_barang ?? 'Tanpa Kode' }})
                                        </h6>

                                        @if($isDitolak)
                                            <span class="badge bg-danger" style="font-size: 0.68rem;">
                                                <i class="fas fa-times-circle me-1"></i> Ditolak
                                            </span>
                                        @elseif($isTerlambat)
                                            <span class="badge bg-danger" style="font-size: 0.68rem;">
                                                <i class="fas fa-exclamation-triangle me-1"></i> Terlambat / Overdue
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-muted" style="font-size: 0.81rem;">
                                        <span>Jumlah: <b class="text-dark">{{ (float) $p->jumlah_diminta }} {{ $p->barang->satuan ?? '' }}</b></span><br>
                                        <span>Target Waktu Pengadaan: <b class="{{ $isTerlambat ? 'text-danger' : 'text-dark' }}">{{ $p->format_target_waktu ?? '-' }}</b></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center px-2">
                                <div class="col-12">
                                    <div class="tracking-steps-scroll">
                                        <div class="tracking-steps-inner d-flex justify-content-between text-center position-relative px-3 py-1">
                                            <div class="progress position-absolute w-100" style="height: 3px; top: 14px; z-index: 1; left: 0;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressWidth }}%;"></div>
                                            </div>

                                            @foreach($steps as $no => $s)
                                                @php
                                                    $isRed = $redStep === $no;
                                                    $isDone = $rank >= $no;
                                                    $bgClass = $isRed ? 'bg-danger' : ($isDone ? 'bg-success' : 'bg-secondary');
                                                    $textClass = $isRed ? 'text-danger' : ($isDone ? 'text-success' : '');

                                                    if ($isRed) {
                                                        $statusText = $isDitolak ? 'Ditolak' : ($no == 4 ? 'Terlambat / Tertahan' : 'Terlambat / Menunggu');
                                                    } elseif ($isDone) {
                                                        $doneText = [
                                                            2 => $jenisAjuan == 'koor' ? 'Otomatis Disetujui' : ($jenisAjuan == 'ga' ? 'Mengetahui' : 'Disetujui'),
                                                            3 => $jenisAjuan == 'ga' ? 'Otomatis Disetujui' : 'Disetujui',
                                                            4 => $rank == 4 ? 'Sedang Diproses' : 'Selesai Diproses',
                                                            5 => 'Selesai',
                                                        ];
                                                        $statusText = $doneText[$no] ?? '';
                                                    } else {
                                                        $pendingText = [2 => 'Menunggu', 3 => 'Menunggu', 4 => 'Belum', 5 => 'Menunggu Tiba'];
                                                        $statusText = $pendingText[$no] ?? '';
                                                    }

                                                    $atCarbon = $s['at'] ? \Carbon\Carbon::parse($s['at']) : null;
                                                    $atText = $atCarbon
                                                        ? $atCarbon->format($atCarbon->format('H:i') == '00:00' ? 'd M Y' : 'd M Y, H:i')
                                                        : '-';
                                                    $showMeta = $no == 1 || $isDone || ($isRed && $isDitolak);
                                                @endphp
                                                <div class="position-relative text-center" style="z-index: 2; flex: 1;">
                                                    <a href="{{ route('pengadaan.index') }}" class="tracking-step-icon rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm text-white {{ $bgClass }}" title="Buka halaman pengadaan barang">
                                                        <i class="fas {{ $s['icon'] }}"></i>
                                                    </a>
                                                    <div class="mt-1">
                                                        <span class="fw-bold d-block {{ $textClass }}" style="font-size: 0.68rem;">{{ $no }}. {{ $s['label'] }}</span>
                                                        <small class="text-muted d-block" style="font-size: 0.6rem;">
                                                            @if($statusText !== '' && $no != 1)
                                                                <span class="fw-semibold {{ $textClass }}">{{ $statusText }}</span>
                                                                @if($showMeta)<br>@endif
                                                            @endif
                                                            @if($showMeta)
                                                                Oleh: {{ $s['who'] ?? '-' }}<br>
                                                                {{ $atText }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            @endforeach
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
                        <p class="mb-0 small">Silakan pilih atau cari barang di menu dropdown atas untuk melihat rincian alur tracking prosesnya.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const select = $('#selectTracking');
        const placeholder = document.getElementById('trackingPlaceholder');

        function updateTracking() {
            document.querySelectorAll('.tracking-item').forEach(el => el.style.display = 'none');

            const selectedId = select.val();
            if (selectedId) {
                if (placeholder) placeholder.style.display = 'none';
                const targetEl = document.getElementById(selectedId);
                if (targetEl) targetEl.style.display = 'block';
            } else if (placeholder) {
                placeholder.style.display = 'block';
            }
        }

        if (!select.length) return;

        select.select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#trackingSelectWrap'),
            minimumResultsForSearch: 0,
            allowClear: false,
            dropdownCssClass: 'select-tracking-dropdown',
            matcher: function (params, data) {
                if (!data.element) return data;

                const isArsip = $(data.element).data('arsip') == 1;
                const term = $.trim(params.term || '').toLowerCase();

                if (term === '') {
                    return isArsip ? null : data;
                }

                return data.text.toLowerCase().indexOf(term) > -1 ? data : null;
            },
            language: {
                noResults: function () {
                    return 'Barang tidak ditemukan';
                }
            }
        });

        select.on('select2:open', function () {
            $('#trackingSelectWrap .select2-dropdown--above').removeClass('select2-dropdown--above').addClass('select2-dropdown--below');
            $('#trackingSelectWrap .select2-container--above').removeClass('select2-container--above').addClass('select2-container--below');

            const field = document.querySelector('#trackingSelectWrap .select2-search__field');
            if (field) {
                field.setAttribute('placeholder', 'Cari nama barang, kode, atau status...');
                field.focus();
            }
        });

        updateTracking();
        select.on('change', updateTracking);
    });
</script>
@endpush
@endsection