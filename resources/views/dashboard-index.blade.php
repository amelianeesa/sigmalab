@extends('layouts.app')
@section('title', 'dashboard-index - views')

@section('content')
<style>
    .dashboard-container {
        padding: 2px 16px !important;
    }
    .dashboard-title {
        font-size: 1.2rem;
    }
    .dashboard-subtitle {
        font-size: 0.76rem;
    }
    .select2-container--bootstrap-5 .select2-dropdown {
        top: 100% !important;
        bottom: auto !important;
    }
    .tracking-card .card-body {
        padding: 10px 12px !important;
    }
    .border-suco-biru { border-color: #0082c4 !important; }
    .text-suco-biru { color: #0082c4 !important; }
    .border-suco-biru-muda { border-color: #00a3e2 !important; }
    .text-suco-biru-muda { color: #00a3e2 !important; }
    .border-suco-hijau { border-color: #00a39b !important; }
    .text-suco-hijau { color: #00a39b !important; }
    .text-navy { color: #1b3152 !important; }
    .bg-navy { background-color: #1b3152 !important; color: #ffffff !important; }
    .border-navy { border-color: #1b3152 !important; }
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
        padding: 0.35rem 0.7rem;
        font-size: 0.76rem;
        line-height: 1.4;
    }

    .info-card-link {
        text-decoration: none;
        color: inherit;
        display: block;
        height: 100%;
    }
    .info-card-clickable {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        cursor: pointer;
    }
    .info-card-clickable:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.4rem 0.9rem rgba(0, 0, 0, 0.12) !important;
    }

    .summary-card {
        border-radius: 0.5rem;
        height: 100%;
    }
    .summary-card-body {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 7px 10px !important;
    }
    .summary-card-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.78rem;
        flex-shrink: 0;
    }
    .summary-card-icon.icon-merah { background-color: rgba(220, 53, 69, 0.12); color: #dc3545; }
    .summary-card-icon.icon-biru { background-color: rgba(0, 130, 196, 0.12); color: #0082c4; }
    .summary-card-icon.icon-biru-muda { background-color: rgba(0, 163, 226, 0.12); color: #00a3e2; }
    .summary-card-icon.icon-hijau { background-color: rgba(0, 163, 155, 0.12); color: #00a39b; }
    .summary-card-text {
        min-width: 0;
    }
    .summary-card-label {
        font-size: 0.6rem;
        letter-spacing: 0.2px;
        line-height: 1.2;
    }
    .summary-card-value {
        font-size: 1.15rem;
        line-height: 1.1;
        margin: 1px 0 !important;
    }
    .summary-card-link {
        font-size: 0.65rem;
    }

    .info-card {
        border-radius: 0.5rem;
    }
    .info-card .card-body {
        padding: 7px 10px !important;
    }
    .info-card-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        flex-shrink: 0;
    }
    .info-card-icon.bg-danger-soft { background-color: #fdeaea; color: #dc3545; }
    .info-card-icon.bg-warning-soft { background-color: #fff8e6; color: #f0ad4e; }
    .info-card-icon.bg-navy-soft { background-color: rgba(27, 49, 82, 0.1); color: #1b3152; }
    .info-card-title {
        font-size: 0.76rem;
    }
    .info-card-text {
        font-size: 0.66rem;
        line-height: 1.25;
    }
    .info-card-badge {
        font-size: 0.68rem !important;
        padding: 0.22rem 0.5rem !important;
        min-width: 24px;
    }
    .info-card-alert-btn {
        pointer-events: none;
        font-size: 0.66rem;
        padding-top: 0.1rem;
        padding-bottom: 0.1rem;
    }

    .tracking-steps-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .tracking-steps-inner {
        display: flex;
        min-width: 520px;
    }
    .tracking-step {
        position: relative;
        z-index: 2;
        flex: 1;
        text-align: center;
    }
    .tracking-step-icon {
        width: 28px;
        height: 28px;
        font-size: 0.66rem;
        text-decoration: none;
        flex-shrink: 0;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    a.tracking-step-icon:hover {
        transform: scale(1.14);
        box-shadow: 0 0.25rem 0.6rem rgba(0, 0, 0, 0.25) !important;
        color: #ffffff;
    }
    .tracking-step-disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }
    .tracking-step-label {
        font-size: 0.68rem;
    }
    .tracking-step-meta {
        font-size: 0.61rem;
        line-height: 1.35;
    }
    .tracking-progress-line {
        height: 3px;
        top: 13px;
        z-index: 1;
        left: 0;
    }

    @media (max-width: 991.98px) {
        .summary-card-label {
            font-size: 0.58rem;
        }
    }

    @media (max-width: 767.98px) {
        .dashboard-container {
            padding: 2px 8px !important;
        }
        .dashboard-title {
            font-size: 1.05rem;
        }
        .dashboard-subtitle {
            font-size: 0.7rem;
        }
        .tracking-card .card-body {
            padding: 10px 12px !important;
        }
        .tracking-select-wrap {
            max-width: 100%;
        }
        .tracking-steps-scroll {
            overflow-x: visible;
        }
        .tracking-steps-inner {
            flex-direction: column;
            min-width: 0;
            padding: 0 !important;
        }
        .tracking-progress-line {
            display: none;
        }
        .tracking-step {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            flex: none;
            text-align: left;
            padding-bottom: 14px;
        }
        .tracking-step:last-child {
            padding-bottom: 0;
        }
        .tracking-step:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 13px;
            top: 30px;
            bottom: 2px;
            width: 2px;
            background-color: #dee2e6;
            z-index: 0;
        }
        .tracking-step.is-done:not(:last-child)::after {
            background-color: #198754;
        }
        .tracking-step .tracking-step-icon {
            margin: 0 !important;
            position: relative;
            z-index: 1;
        }
        .tracking-step-body {
            margin-top: 0 !important;
            text-align: left;
        }
        .tracking-step-label {
            font-size: 0.76rem;
        }
        .tracking-step-meta {
            font-size: 0.68rem;
        }
        .info-card-text {
            display: none;
        }
        .info-card .card-body {
            padding: 6px 10px !important;
        }
    }

    @media (max-width: 575.98px) {
        .summary-card-body {
            flex-direction: column;
            justify-content: center;
            text-align: center;
            gap: 3px;
            padding: 7px 4px !important;
        }
        .summary-card-icon {
            width: 26px;
            height: 26px;
            font-size: 0.7rem;
        }
        .summary-card-label {
            font-size: 0.55rem;
            letter-spacing: 0;
        }
        .summary-card-value {
            font-size: 1.05rem;
        }
        .summary-card-link {
            display: none !important;
        }
        .summary-card.border-start {
            border-left-width: 3px !important;
        }
    }
</style>

<div class="container-fluid dashboard-container">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h2 class="fw-bold text-dark mb-0 dashboard-title">Monitoring Center</h2>
            <p class="text-muted mb-0 dashboard-subtitle">Selamat datang, Anda login sebagai <strong class="text-navy">{{ $role }}</strong></p>
        </div>
    </div>

    <div class="row g-2 mb-2">
        <div class="col-6 col-lg-3">
            <div class="card summary-card border-0 shadow-sm border-start border-4 border-danger info-card-clickable" data-bs-toggle="modal" data-bs-target="#modalQcOutlier">
                <div class="card-body summary-card-body">
                    <span class="summary-card-icon icon-merah"><i class="fas fa-exclamation-triangle"></i></span>
                    <div class="summary-card-text">
                        <p class="text-danger mb-0 text-uppercase fw-bold summary-card-label">QC OUTLIER (OPEN)</p>
                        <h3 class="fw-bold text-dark mb-0 summary-card-value">{{ $totalQcOutlier }}</h3>
                        <span class="text-danger fw-semibold d-inline-block summary-card-link">Klik untuk eksekusi</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <a href="{{ route('kegiatan.index') }}" class="info-card-link">
                <div class="card summary-card border-0 shadow-sm border-start border-4 border-suco-biru info-card-clickable">
                    <div class="card-body summary-card-body">
                        <span class="summary-card-icon icon-biru"><i class="fas fa-flask"></i></span>
                        <div class="summary-card-text">
                            <p class="text-muted mb-0 text-uppercase fw-bold summary-card-label">PENGUJIAN AKTIF</p>
                            <h3 class="fw-bold text-dark mb-0 summary-card-value">{{ $kegiatanBerjalan }}</h3>
                            <span class="text-suco-biru fw-semibold d-inline-block summary-card-link">Buka Modul QC</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-lg-3">
            <a href="{{ route('alat.index') }}" class="info-card-link">
                <div class="card summary-card border-0 shadow-sm border-start border-4 border-suco-biru-muda info-card-clickable">
                    <div class="card-body summary-card-body">
                        <span class="summary-card-icon icon-biru-muda"><i class="fas fa-balance-scale"></i></span>
                        <div class="summary-card-text">
                            <p class="text-muted mb-0 text-uppercase fw-bold summary-card-label">KALIBRASI ALAT H-6 BULAN</p>
                            <h3 class="fw-bold text-dark mb-0 summary-card-value">{{ $tenggatKalibrasi }}</h3>
                            <span class="text-suco-biru-muda fw-semibold d-inline-block summary-card-link">Cek Kalibrasi</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-lg-3">
            <a href="{{ route('sdm.index') }}" class="info-card-link">
                <div class="card summary-card border-0 shadow-sm border-start border-4 border-suco-hijau info-card-clickable">
                    <div class="card-body summary-card-body">
                        <span class="summary-card-icon icon-hijau"><i class="fas fa-id-badge"></i></span>
                        <div class="summary-card-text">
                            <p class="text-muted mb-0 text-uppercase fw-bold summary-card-label">SERTIFIKASI PERSONIL H-6 BULAN</p>
                            <h3 class="fw-bold text-dark mb-0 summary-card-value">{{ $sertifikasiHampirHabis }}</h3>
                            <span class="text-suco-hijau fw-semibold d-inline-block summary-card-link">Cek Sertifikasi</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-2 mb-2">
        <div class="col-12 col-md-4">
            <a href="{{ route('barang.index') }}" class="info-card-link">
                <div class="card h-100 border-0 shadow-sm info-card info-card-clickable">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="info-card-icon bg-danger-soft"><i class="fas fa-box-open"></i></span>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="fw-bold mb-0 text-dark info-card-title">Stok Kritis</h6>
                                    <span class="badge bg-danger rounded-pill info-card-badge">{{ $stokTipis }}</span>
                                </div>
                                <p class="text-muted mb-0 info-card-text">Terdapat {{ $stokTipis }} item barang di bawah batas minimum.</p>
                            </div>
                        </div>
                        <div class="mt-2"><span class="btn btn-outline-danger btn-sm w-100 info-card-alert-btn">Cek Inventori</span></div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="{{ route('barang.index') }}" class="info-card-link">
                <div class="card h-100 border-0 shadow-sm info-card info-card-clickable">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="info-card-icon bg-warning-soft"><i class="fas fa-calendar-times"></i></span>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="fw-bold mb-0 text-dark info-card-title">Akan Kedaluwarsa</h6>
                                    <span class="badge bg-warning text-dark rounded-pill info-card-badge">{{ $barangExp }}</span>
                                </div>
                                <p class="text-muted mb-0 info-card-text">Terdapat {{ $barangExp }} bahan kedaluwarsa dalam 180 hari.</p>
                            </div>
                        </div>
                        <div class="mt-2"><span class="btn btn-outline-warning btn-sm w-100 info-card-alert-btn">Cek Expired Date</span></div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="{{ route('pengadaan.index') }}" class="info-card-link">
                <div class="card h-100 border-0 shadow-sm info-card info-card-clickable">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="info-card-icon bg-navy-soft"><i class="fas fa-shopping-cart"></i></span>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="fw-bold mb-0 text-dark info-card-title">Approval Pengadaan</h6>
                                    <span class="badge bg-navy rounded-pill info-card-badge">{{ $pengadaanPending }}</span>
                                </div>
                                <p class="text-muted mb-0 info-card-text">Ada {{ $pengadaanPending }} pengajuan yang butuh persetujuan.</p>
                            </div>
                        </div>
                        <div class="mt-2"><span class="btn btn-outline-navy btn-sm w-100 info-card-alert-btn">Proses Pengajuan</span></div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0 tracking-card">
                <div class="card-header bg-navy py-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="fw-bold mb-0 text-white" style="font-size: 0.85rem;">
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
                                    $labelStatusMap = ['diproses' => 'PO', 'diproses_po' => 'PO', 'pembelian' => 'Proses'];
                                    $statusLabel = $labelStatusMap[$p->status] ?? ucwords(str_replace('_', ' ', $p->status));
                                @endphp
                                <option value="track-{{ $p->permintaan_id }}" data-arsip="{{ $cekArsip($p) ? 1 : 0 }}" {{ $p->permintaan_id == $idAwal ? 'selected' : '' }}>
                                    {{ $namaBarang }} ({{ $kodeBarang }}) - {{ $statusLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card-body">
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

                            $isDitolak = str_contains(strtolower($p->status), 'ditolak');

                            $namaProses = in_array($p->status, ['diproses', 'diproses_po'])
                                ? 'PO'
                                : ($p->status == 'pembelian' ? 'Proses' : 'Diproses');

                            $logs = $p->logs->keyBy('tahap');
                            $logTolak = $isDitolak
                                ? $p->logs->whereIn('aksi', ['tolak', 'batal'])->sortByDesc('tahap')->first()
                                : null;

                            if ($logTolak) {
                                $rank = max($logTolak->tahap - 1, 1);
                            }

                            $roleAjuan = optional(optional($p->pemohon)->role)->nama_role;
                            $jenisAjuan = $roleAjuan == \App\Enums\PeranPengguna::GA_OFFICER->value
                                ? 'ga'
                                : ($roleAjuan == \App\Enums\PeranPengguna::KOORDINATOR_LAB->value ? 'koor' : 'analis');

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

                            $effectiveRank = $redStep ? max($rank, $redStep) : $rank;
                            $progressMap = [0, 10, 30, 50, 70, 100];
                            $progressWidth = $progressMap[$effectiveRank] ?? ($effectiveRank >= 5 ? 100 : $effectiveRank * 25);

                            $namaUser = fn ($u) => optional($u)->username ?? optional($u)->name ?? '-';
                            $namaPemohon = $namaUser($p->pemohon);
                            $namaAkunPenyetuju = optional($p->penyetuju)->username;

                            $aktor = function ($no) use ($p, $rank, $jenisAjuan, $namaPemohon, $namaAkunPenyetuju, $logs, $namaUser) {
                                $kosong = ['who' => '-', 'at' => null];

                                $log = $logs->get($no);
                                if ($log) {
                                    return ['who' => $namaUser($log->user), 'at' => $log->dicatat_pada];
                                }

                                $otomatis = ['who' => $namaPemohon, 'at' => $p->created_at];

                                if ($no == 2 && $jenisAjuan != 'analis') {
                                    return $otomatis;
                                }

                                if ($no == 3 && $jenisAjuan == 'ga') {
                                    return $otomatis;
                                }

                                $waktuKeputusan = ($p->tanggal_keputusan && $p->tanggal_keputusan->format('H:i') != '00:00')
                                    ? $p->tanggal_keputusan
                                    : $p->updated_at;
                                $keputusan = ['who' => $namaAkunPenyetuju ?? '-', 'at' => $waktuKeputusan];

                                if ($no == 2) {
                                    return in_array($p->status, ['menunggu_ga', 'ditolak_koordinator', 'ditolak']) ? $keputusan : $kosong;
                                }

                                if ($no == 3) {
                                    return in_array($p->status, ['disetujui', 'diproses', 'diproses_po', 'pembelian', 'selesai', 'ditolak_ga']) ? $keputusan : $kosong;
                                }

                                if ($no == 4) {
                                    return $rank >= 4 ? $keputusan : $kosong;
                                }

                                return $kosong;
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
                            <div class="row mb-2">
                                <div class="col-md-12 border-bottom pb-2">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                        <h6 class="fw-bold mb-0 text-navy" style="font-size: 0.85rem;">
                                            {{ $p->barang->nama_barang ?? '-' }} ({{ $p->barang->kode_barang ?? 'Tanpa Kode' }})
                                        </h6>

                                        @if($isDitolak)
                                            <span class="badge bg-danger" style="font-size: 0.64rem;">
                                                <i class="fas fa-times-circle me-1"></i> {{ $logTolak && $logTolak->aksi == 'batal' ? 'Dibatalkan' : 'Ditolak' }}
                                            </span>
                                        @elseif($isTerlambat)
                                            <span class="badge bg-danger" style="font-size: 0.64rem;">
                                                <i class="fas fa-exclamation-triangle me-1"></i> Terlambat / Overdue
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-muted" style="font-size: 0.74rem;">
                                        <span>Jumlah: <b class="text-dark">{{ (float) $p->jumlah_diminta }} {{ $p->barang->satuan ?? '' }}</b></span><br>
                                        <span>Target Waktu Pengadaan: <b class="{{ $isTerlambat ? 'text-danger' : 'text-dark' }}">{{ $p->format_target_waktu ?? '-' }}</b></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center px-md-2">
                                <div class="col-12">
                                    <div class="tracking-steps-scroll">
                                        <div class="tracking-steps-inner d-flex justify-content-between text-center position-relative px-3 py-1">
                                            <div class="progress position-absolute w-100 tracking-progress-line">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progressWidth }}%;"></div>
                                            </div>

                                            @foreach($steps as $no => $s)
                                                @php
                                                    $isRed = $redStep === $no;
                                                    $isDone = $rank >= $no;
                                                    $bisaKlik = $isDone || ($isRed && $isDitolak);
                                                    $bgClass = $isRed ? 'bg-danger' : ($isDone ? 'bg-success' : 'bg-secondary');
                                                    $textClass = $isRed ? 'text-danger' : ($isDone ? 'text-success' : '');

                                                    if ($isRed) {
                                                        if ($isDitolak) {
                                                            $statusText = $logTolak && $logTolak->aksi == 'batal' ? 'Dibatalkan' : 'Ditolak';
                                                        } else {
                                                            $statusText = $no == 4 ? 'Terlambat / Tertahan' : 'Terlambat / Menunggu';
                                                        }
                                                    } elseif ($isDone) {
                                                        $doneText = [
                                                            2 => $jenisAjuan == 'koor' ? 'Otomatis Disetujui' : ($jenisAjuan == 'ga' ? 'Mengetahui' : 'Disetujui'),
                                                            3 => $jenisAjuan == 'ga' && !$logs->has(3) ? 'Otomatis Disetujui' : 'Disetujui',
                                                            4 => $rank == 4 ? 'Sedang ' . $namaProses : 'Selesai Diproses',
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
                                                    $showMeta = $no == 1 || !empty($s['at']);
                                                @endphp
                                                <div class="tracking-step {{ $isDone && !$isRed ? 'is-done' : '' }}">
                                                    @if($bisaKlik)
                                                        <a href="{{ route('pengadaan.index') }}" class="tracking-step-icon rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm text-white {{ $bgClass }}" title="Buka halaman pengadaan barang">
                                                            <i class="fas {{ $s['icon'] }}"></i>
                                                        </a>
                                                    @else
                                                        <span class="tracking-step-icon tracking-step-disabled rounded-circle d-flex align-items-center justify-content-center mx-auto text-white {{ $bgClass }}" title="Belum sampai tahap ini" aria-disabled="true">
                                                            <i class="fas {{ $s['icon'] }}"></i>
                                                        </span>
                                                    @endif
                                                    <div class="mt-1 tracking-step-body">
                                                        <span class="fw-bold d-block tracking-step-label {{ $textClass }}">{{ $no }}. {{ $s['label'] }}</span>
                                                        <small class="text-muted d-block tracking-step-meta">
                                                            @if($statusText !== '' && $no != 1)
                                                                <span class="fw-semibold d-block {{ $textClass }}">{{ $statusText }}</span>
                                                            @endif
                                                            @if($showMeta)
                                                                <span class="d-block">Oleh: <b>{{ $s['who'] ?? '-' }}</b></span>
                                                                <span class="d-block">Waktu: {{ $atText }}</span>
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

<div class="modal fade" id="modalQcOutlier" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white py-2">
                <h5 class="modal-title fw-bold" style="font-size: 1rem;"><i class="fas fa-exclamation-triangle me-2"></i>Daftar Kendali Mutu Outlier</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                @if($totalQcOutlier > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle mb-0" style="font-size: 0.82rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Modul Verifikasi</th>
                                    <th>Parameter</th>
                                    <th>Tanggal</th>
                                    <th class="text-end pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($daftarQcOutlier as $outlier)
                                <tr>
                                    <td class="ps-3"><span class="badge bg-secondary">{{ $outlier['modul'] }}</span></td>
                                    <td class="fw-bold">{{ $outlier['parameter'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($outlier['tanggal'])->format('d M Y') }}</td>
                                    <td class="text-end pe-3">
                                        <a href="{{ $outlier['url'] }}" class="btn btn-sm btn-danger fw-bold">
                                            <i class="fas fa-search me-1"></i>Investigasi
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                        <h5 class="text-muted fw-bold">Semua Aman!</h5>
                        <p class="text-muted mb-0">Tidak ada data outlier pengujian yang perlu diinvestigasi.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection