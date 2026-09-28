@extends('layouts.app')
@section('title', 'Dashboard - QC CRM')

@section('content')

<style>
    .btn-corporate-blue {
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
        color: #ffffff !important;
    }
    .btn-corporate-blue:hover, .btn-corporate-blue:focus {
        background-color: #14253e !important;
        border-color: #14253e !important;
        color: #ffffff !important;
    }
    .btn-outline-corporate {
        color: #1b3152 !important;
        border-color: #1b3152 !important;
    }
    .btn-outline-corporate:hover {
        background-color: #1b3152 !important;
        color: #ffffff !important;
    }

    .table-corporate thead th {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-bottom: 2px solid #14253e !important;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding-top: 15px;
        padding-bottom: 15px;
    }

    .nav-tabs .nav-link {
        color: #6c757d; 
        border: none;
        border-bottom: 3px solid transparent;
        transition: all 0.2s ease-in-out;
    }
    .nav-tabs .nav-link:hover {
        color: #1b3152;
        border-bottom: 3px solid #dee2e6;
    }
    .nav-tabs .nav-link.active {
        color: #1b3152 !important;
        background-color: transparent !important;
        border: none !important;
        border-bottom: 3px solid #1b3152 !important;
    }
    .nav-tabs .nav-link.active i {
        color: #1b3152 !important;
    }
</style>

<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="CRM" />

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center mt-3 mb-4 gap-3">
        <!-- Bagian Judul -->
        <div>
            <h2 class="fw-bold text-dark mb-0">
                <i class="fas fa-certificate me-2" style="color: #1b3152;"></i>QC CRM Dashboard
            </h2>
        </div>
        
        <!-- Bagian Tombol Aksi (Tambahkan d-grid agar otomatis full-width di HP) -->
        <div class="d-flex flex-column flex-md-row gap-2 d-grid d-md-flex">
            <a href="{{ route('crm-katalog.create') }}" class="btn btn-outline-corporate shadow-sm rounded-pill px-4">
                <i class="fas fa-plus me-1"></i> Botol CRM Baru
            </a>
            <a href="{{ route('qc-crm.create') }}" class="btn btn-corporate-blue shadow-sm rounded-pill px-4">
                <i class="fas fa-play me-1"></i> Mulai Pengujian CRM
            </a>
        </div>
    </div>

    <!-- TABS NAV -->
    <ul class="nav nav-tabs mb-4" id="qcCrmTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold px-4" id="katalog-tab" data-bs-toggle="tab" data-bs-target="#katalog" type="button" role="tab" aria-controls="katalog" aria-selected="true">
                <i class="fas fa-box text-secondary me-2"></i>Master Botol & Verifikasi CRM
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold px-4" id="harian-tab" data-bs-toggle="tab" data-bs-target="#harian" type="button" role="tab" aria-controls="harian" aria-selected="false">
                <i class="fas fa-vials text-secondary me-2"></i>Riwayat Pengujian Harian CRM
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold px-4" id="chart-tab" data-bs-toggle="tab" data-bs-target="#chartCrm" type="button" role="tab" aria-controls="chartCrm" aria-selected="false">
                <i class="fas fa-chart-area text-secondary me-2"></i>Control Chart CRM
            </button>
        </li>
    </ul>

    <!-- TABS CONTENT -->
    <div class="tab-content" id="qcCrmTabsContent">
        
        <!-- TAB 1: MASTER BOTOL & VERIFIKASI -->
        <div class="tab-pane fade show active" id="katalog" role="tabpanel" aria-labelledby="katalog-tab">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 table-corporate">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Nomor Lot / Botol</th>
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
                                <tr>
                                    <td class="ps-3"><span class="badge bg-primary fs-6">{{ $r->nomor_lot }}</span></td>
                                    <td class="fw-bold">{{ $r->nama_produk }}</td>
                                    <td>
                                        <div class="small">
                                            No: <strong>{{ $r->nomor_sertifikat ?? '-' }}</strong><br>
                                            Param: <span class="badge bg-secondary">{{ $r->sertifikats_count ?? $r->sertifikats->count() }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($r->tanggal_expired)
                                            @if($r->tanggal_expired < now())
                                                <span class="text-danger fw-bold"><i class="fas fa-exclamation-circle"></i> {{ $r->tanggal_expired->format('d M Y') }} (Expired)</span>
                                            @else
                                                {{ $r->tanggal_expired->format('d M Y') }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $r->statusBadgeClass() }}">{{ $r->statusLabel() }}</span>
                                    </td>
                                    <td>
                                        @if($r->verifikasiTeknis->count() > 0)
                                            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalHasilVerif{{ $r->id }}">
                                                <i class="fas fa-check-double me-1"></i> Lihat Hasil
                                            </button>
                                        @else
                                            <span class="text-muted small">Belum ada uji</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('crm-katalog.show', $r->id) }}" class="btn btn-sm btn-info text-white mb-1" title="Detail Botol & Input Parameter">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        
                                        @if($r->status === 'menunggu_verifikasi')
                                            <a href="{{ route('crm-katalog.verifikasi-administratif.form', $r->id) }}" class="btn btn-sm btn-warning mb-1" title="Lakukan Verifikasi Administratif">
                                                <i class="fas fa-clipboard-check"></i> Verif. Admin
                                            </a>
                                        @elseif($r->status === 'menunggu_verifikasi_teknis')
                                            @php
                                                $hasDraft = $r->verifikasiTeknis->where('status_evaluasi', 'draft')->isNotEmpty(); 
                                                $hasOutlier = $r->verifikasiTeknis->where('status_evaluasi', 'outlier')->isNotEmpty();
                                            @endphp
                                            
                                            <a href="{{ route('crm-katalog.verifikasi-teknis.form', $r->id) }}" 
                                            class="btn btn-sm {{ $hasOutlier ? 'btn-danger' : ($hasDraft ? 'btn-warning' : 'btn-primary pulse-button') }} mb-1" 
                                            title="{{ $hasOutlier ? 'Uji Ulang Parameter Outlier' : ($hasDraft ? 'Lanjutkan Draft Verifikasi' : 'Lakukan Verifikasi Teknis') }}">
                                                
                                                <i class="fas {{ $hasOutlier ? 'fa-exclamation-triangle' : ($hasDraft ? 'fa-edit' : 'fa-flask') }}"></i> 
                                                {{ $hasOutlier ? 'Uji Ulang (Outlier)' : ($hasDraft ? 'Lanjut Draft' : 'Mulai Uji Verifikasi') }}
                                            
                                            </a>
                                        @endif
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="fas fa-box-open fs-1 text-light mb-3 d-block"></i>
                                        Belum ada data botol CRM. Silakan tambahkan Botol CRM Baru.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- MODALS RENDERED OUTSIDE THE TABLE -->
            @foreach($katalogs as $r)
                @if($r->verifikasiTeknis->count() > 0)
                <div class="modal fade" id="modalHasilVerif{{ $r->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title"><i class="fas fa-check-double me-2"></i>Hasil Uji Verifikasi - {{ $r->nomor_lot }}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0">
                                
                                <!-- 1. TABS NAVIGATION -->
                                <div class="bg-light pt-3 px-3 border-bottom">
                                    <ul class="nav nav-tabs border-bottom-0" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active fw-bold text-dark" data-bs-toggle="tab" data-bs-target="#ringkasan-{{ $r->id }}" type="button" role="tab">
                                                <i class="fas fa-list me-1"></i> Ringkasan
                                            </button>
                                        </li>
                                        @foreach($r->verifikasiTeknis as $verif)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link fw-bold text-secondary" data-bs-toggle="tab" data-bs-target="#param-{{ $r->id }}-{{ $verif->id }}" type="button" role="tab">
                                                {{ strtoupper($verif->parameterUji->nama_parameter ?? 'PARAM') }}
                                            </button>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <!-- 2. TABS CONTENT -->
                                <div class="tab-content p-3">
                                    
                                    <!-- TAB RINGKASAN (DEFAULT) -->
                                    <div class="tab-pane fade show active" id="ringkasan-{{ $r->id }}" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped mb-0 text-center align-middle" style="font-size: 13px;">
                                                <thead class="table-light">
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
                                                            <td>{{ number_format($verif->batas_bawah, 4) }} - {{ number_format($verif->batas_atas, 4) }}</td>
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

                                    <!-- TAB DATA MENTAH PER PARAMETER -->
                                    @foreach($r->verifikasiTeknis as $verif)
                                    @php
                                        $code = strtoupper($verif->parameterUji->nama_parameter ?? '');
                                        // Mengurai JSON array dari database
                                        $dataMentahList = is_string($verif->data_mentah) ? json_decode($verif->data_mentah, true) : ($verif->data_mentah ?? []);
                                        if (!is_array($dataMentahList)) $dataMentahList = [];
                                    @endphp
                                    <div class="tab-pane fade" id="param-{{ $r->id }}-{{ $verif->id }}" role="tabpanel">
                                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-search me-2"></i>Data Mentah Pengujian - {{ $code }}</h6>
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm text-center align-middle">
                                                <thead class="table-light">
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
                                                    <!-- SIMPLO -->
                                                    <tr>
                                                        <td rowspan="2" class="fw-bold bg-light align-middle">{{ $index + 1 }}</td>
                                                        <td class="fw-bold text-start ps-3 border-end-0">Simplo</td>
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
                                                    <!-- DUPLO -->
                                                    <tr>
                                                        <td class="fw-bold text-start ps-3 border-end-0">Duplo</td>
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
            <!-- END MODALS -->
        </div>

        <!-- TAB 2: PENGUJIAN HARIAN CRM -->
        <div class="tab-pane fade" id="harian" role="tabpanel" aria-labelledby="harian-tab">
            
            <div class="alert alert-info border-0 shadow-sm mb-3">
                <i class="fas fa-info-circle me-2"></i> Pilihan botol pada menu <strong>Mulai Pengujian CRM</strong> hanya menampilkan botol yang sudah memiliki status <strong>AKTIF</strong> (Lolos uji verifikasi).
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                                        {{-- KOTAK FILTER PENGUJIAN HARIAN --}}
                    <div class="row px-3 mb-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-bold">Filter Parameter Uji</label>
                            <select id="filterParam" class="form-select form-select-sm border-secondary">
                                <option value="">-- Semua Parameter --</option>
                                @php
                                    // Mengambil nama-nama parameter secara otomatis dari data tabel
                                    $uniqueParams = $kegiatanList->pluck('parameterUji.nama_parameter')->filter()->unique()->sort();
                                @endphp
                                @foreach($uniqueParams as $p)
                                    <option value="{{ $p }}">{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-bold">Periode (Bulan & Tahun)</label>
                            <input type="month" id="filterPeriode" class="form-control form-control-sm border-secondary">
                        </div>
                        <div class="col-md-3">
                            <button id="btnResetFilter" class="btn btn-sm btn-light border shadow-sm"><i class="fas fa-sync-alt me-1"></i> Reset</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                                                <table class="table table-hover align-middle mb-0 table-corporate" id="tableHarianCRM">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Tanggal Uji</th>
                                    <!-- INI KOLOM BARUNYA -->
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
                                    <td class="ps-4" data-periode="{{ $log->tanggal_uji->format('Y-m') }}">{{ $log->tanggal_uji->format('d M Y') }}</td>
                                    
                                    <!-- INI DATA BARUNYA -->
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="fas fa-file-alt text-secondary me-1"></i> {{ $log->no_lembar_kerja ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-primary fs-6">{{ $log->crmKatalog->nomor_lot ?? '-' }}</span><br>
                                        <small class="text-muted">{{ $log->crmKatalog->nama_produk ?? '' }}</small>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $log->parameterUji->nama_parameter ?? '-' }}</span></td>
                                    <td class="text-muted">{{ number_format($log->cert_value, 2) }} &plusmn; {{ number_format($log->cert_u, 4) }}</td>
                                    <td class="fw-bold">{{ number_format($log->nilai_akhir, 2) }}</td>
                                    <td>{{ $log->analis->nama ?? 'Unknown' }}</td>
                                    <td>
                                        @if($log->status_evaluasi === 'inlier')
                                            <span class="badge bg-success"><i class="fas fa-check-circle"></i> Inlier</span>
                                        @elseif($log->status_evaluasi === 'outlier')
                                            <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Outlier</span>
                                        @elseif($log->status_evaluasi === 'draft')
                                            <a href="{{ route('qc-crm.create', ['resume' => 1, 'crm_id' => $log->crm_katalog_id]) }}" class="btn btn-sm btn-warning fw-bold mt-1 shadow-sm">
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

        <!-- TAB 3: CONTROL CHART CRM -->
        <div class="tab-pane fade" id="chartCrm" role="tabpanel" aria-labelledby="chart-tab">
            
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-chart-area text-primary me-2"></i>Control Chart CRM</h5>
                    
                    {{-- FILTER --}}
                    <div class="row mb-4 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold">Pilih Botol CRM</label>
                            <select id="chartFilterBotol" class="form-select form-select-sm border-secondary">
                                <option value="">-- Pilih Botol --</option>
                                @foreach($katalogs->where('is_active', true) as $k)
                                    <option value="{{ $k->id }}">{{ $k->nomor_lot }} - {{ $k->nama_produk }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold">Pilih Parameter Uji</label>
                            <select id="chartFilterParam" class="form-select form-select-sm border-secondary" disabled>
                                <option value="">-- Pilih Botol dulu --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button id="btnLoadChart" class="btn btn-sm btn-primary shadow-sm" disabled>
                                <i class="fas fa-sync-alt me-1"></i> Tampilkan Chart
                            </button>
                        </div>
                    </div>

                    {{-- ALERT TREND --}}
                    <div id="alertTrend" class="alert alert-warning border-start border-4 border-warning shadow-sm d-none" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Terdeteksi Trend!</strong> <span id="alertTrendMsg"></span>
                    </div>

                    {{-- GRAFIK --}}
                    <div id="chartContainer" style="display: none;">
                        <div class="row g-2 mb-4" id="chartLegendBoxes"></div>
                        <div style="height: 450px; width: 100%; position: relative;">
                            <canvas id="crmControlChart"></canvas>
                        </div>

                        {{-- TABEL DATA --}}
                        <hr class="my-4">
                        <h6 class="fw-bold mb-3"><i class="fas fa-table text-primary me-2"></i>Tabel Data Control Chart</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover text-center align-middle" style="font-size: 14px;">
                                <thead class="table-light">
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
                    <div id="chartEmpty" class="text-center py-5 text-muted">
                        <i class="fas fa-chart-line fa-3x mb-3 opacity-25"></i>
                        <p>Silakan pilih Botol CRM dan Parameter Uji, lalu klik <strong>Tampilkan Chart</strong>.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .pulse-button {
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
</style>

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {

    
        // 1. Inisialisasi DataTables (Search Global bawaan disembunyikan lewat opsi "dom")
    var tableHarian = $('#tableHarianCRM').DataTable({
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        "order": [],
        "pageLength": 10,
        "language": {
            "lengthMenu": "Tampil _MENU_ data",
            "info": "Menampilkan _START_ s/d _END_ dari _TOTAL_ riwayat",
            "infoEmpty": "Tidak ada data riwayat",
            "paginate": {
                "first": "Awal",
                "last": "Akhir",
                "next": "Next <i class='fas fa-chevron-right' style='font-size:10px; margin-left:4px'></i>",
                "previous": "<i class='fas fa-chevron-left' style='font-size:10px; margin-right:4px'></i> Previous"
            }
        }
    });

    // 2. Suntikkan Logika Filter Pintar (Parameter & Periode)
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        // Logika ini hanya berlaku untuk tabel harian
        if(settings.nTable.id !== 'tableHarianCRM') return true;
        
        let filterParam = $('#filterParam').val().toLowerCase();
        let filterPeriode = $('#filterPeriode').val(); // Format dari input month: "YYYY-MM"
        
        // Ambil data tersembunyi dari elemen <td>
        let rowNode = tableHarian.row(dataIndex).node();
        let rowPeriode = $(rowNode).find('td:eq(0)').data('periode'); 
        let rowParam = data[2].toLowerCase(); // Kolom ke-3 (index 2) adalah Parameter

        // Cek Kecocokan Parameter
        if (filterParam && !rowParam.includes(filterParam)) {
            return false; // Sembunyikan baris jika tidak cocok
        }

        // Cek Kecocokan Periode (Bulan & Tahun)
        if (filterPeriode && rowPeriode !== filterPeriode) {
            return false; // Sembunyikan baris jika tidak cocok
        }

        return true; // Tampilkan baris jika semua kriteria lolos
    });

    // 3. Picu Ulang Tabel Setiap Kali Filter Berubah
    $('#filterParam, #filterPeriode').on('change', function() {
        tableHarian.draw();
    });
    
    // 4. Aksi Tombol Reset
    $('#btnResetFilter').on('click', function() {
        $('#filterParam').val('');
        $('#filterPeriode').val('');
        tableHarian.draw();
    });

});
</script>

<style>
    /* Tab Styling Custom */
    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 3px solid transparent;
        border-radius: 0;
        padding-bottom: 12px;
    }
    .nav-tabs .nav-link:hover {
        color: #495057;
        border-color: transparent;
    }
    .nav-tabs .nav-link.active {
        color: #6f42c1; /* Purple theme for CRM */
        border-bottom: 3px solid #6f42c1;
        background-color: transparent;
    }

    /* Custom DataTables Pagination Style */
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 15px !important;
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
    }
    .dataTables_wrapper .page-item .page-link {
        border: none;
        color: #555;
        font-weight: 500;
        background: transparent;
        margin: 0;
        padding: 8px 16px;
        box-shadow: none !important;
    }
    .dataTables_wrapper .page-item .page-link:hover {
        background-color: #f8f9fa;
        color: #000;
    }
    .dataTables_wrapper .page-item.active .page-link {
        border-top: 1px solid #000 !important;
        border-bottom: 1px solid #000 !important;
        border-left: 1px solid #000 !important;
        border-right: 1px solid #000 !important;
        background-color: transparent !important;
        color: #111 !important;
        font-weight: bold;
        border-radius: 0;
        margin-top: -1px;
        margin-bottom: -1px;
        padding: 8px 16px;
        position: relative;
        z-index: 2;
    }
    .dataTables_wrapper .page-item:last-child .page-link {
        border-left: 1px solid #e0e0e0;
        border-radius: 0;
    }
    .dataTables_wrapper .page-item:first-child .page-link {
        border-radius: 0;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const spinnerButtons = document.querySelectorAll('.btn-spinner');
    
    spinnerButtons.forEach(btn => {
        if (!btn.hasAttribute('data-original-html')) {
            btn.setAttribute('data-original-html', btn.innerHTML);
        }

        btn.addEventListener('click', function() {
            this.style.pointerEvents = 'none';
            this.classList.add('disabled');
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Loading...';
        });
    });
});

window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        const spinnerButtons = document.querySelectorAll('.btn-spinner');
        spinnerButtons.forEach(btn => {
            // Normalkan kembali tombolnya
            btn.style.pointerEvents = 'auto';
            btn.classList.remove('disabled');
            
            // Kembalikan desain asli tombolnya
            if (btn.hasAttribute('data-original-html')) {
                btn.innerHTML = btn.getAttribute('data-original-html');
            }
        });
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
(function() {
    let crmChart = null;

    // --- Filter Cascading: Saat Botol dipilih, isi daftar Parameter ---
    document.getElementById('chartFilterBotol').addEventListener('change', function() {
        const paramSelect = document.getElementById('chartFilterParam');
        const btnLoad = document.getElementById('btnLoadChart');
        paramSelect.innerHTML = '<option value="">-- Memuat... --</option>';
        paramSelect.disabled = true;
        btnLoad.disabled = true;

        if (!this.value) {
            paramSelect.innerHTML = '<option value="">-- Pilih Botol dulu --</option>';
            return;
        }

        fetch('/api/crm-katalog/' + this.value + '/parameters')
            .then(r => r.json())
            .then(data => {
                paramSelect.innerHTML = '<option value="">-- Pilih Parameter --</option>';
                data.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.parameter_uji_id;
                    opt.textContent = item.parameter_uji ? item.parameter_uji.nama_parameter : 'Parameter #' + item.parameter_uji_id;
                    paramSelect.appendChild(opt);
                });
                paramSelect.disabled = false;
            });
    });

    document.getElementById('chartFilterParam').addEventListener('change', function() {
        document.getElementById('btnLoadChart').disabled = !this.value;
    });

    // --- Tombol Tampilkan Chart ---
    document.getElementById('btnLoadChart').addEventListener('click', function() {
        const katalogId = document.getElementById('chartFilterBotol').value;
        const paramId = document.getElementById('chartFilterParam').value;
        if (!katalogId || !paramId) return;

        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memuat...';
        this.disabled = true;

        fetch('{{ route("qc-crm.chart.data") }}?crm_katalog_id=' + katalogId + '&parameter_uji_id=' + paramId)
            .then(r => r.json())
            .then(result => {
                this.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Tampilkan Chart';
                this.disabled = false;

                if (!result.logs || result.logs.length === 0) {
                    document.getElementById('chartContainer').style.display = 'none';
                    document.getElementById('chartEmpty').innerHTML = '<i class="fas fa-inbox fa-3x mb-3 opacity-25"></i><p class="text-muted">Belum ada data pengujian harian final untuk kombinasi ini.</p>';
                    document.getElementById('chartEmpty').style.display = 'block';
                    document.getElementById('alertTrend').classList.add('d-none');
                    return;
                }

                document.getElementById('chartEmpty').style.display = 'none';
                document.getElementById('chartContainer').style.display = 'block';
                renderChart(result);
            })
            .catch(() => {
                this.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Tampilkan Chart';
                this.disabled = false;
            });
    });

    function renderChart(result) {
        const logs = result.logs;
        const certVal = result.cert_value;
        const certU = result.cert_u;
        const batasAtas = certVal + certU;
        const batasBawah = certVal - certU;

        // --- Alert Trend ---
        const alertEl = document.getElementById('alertTrend');
        if (result.trend_warning) {
            alertEl.classList.remove('d-none');
            document.getElementById('alertTrendMsg').textContent =
                '7 titik berturut-turut berada di ' + result.trend_warning + ' True Value. Disarankan melakukan pengecekan/kalibrasi alat.';
        } else {
            alertEl.classList.add('d-none');
        }

        // --- Legend Boxes ---
        document.getElementById('chartLegendBoxes').innerHTML = `
            <div class="col-md-3 col-6"><div class="p-2 border rounded text-center bg-danger bg-opacity-10"><small class="d-block text-muted">Batas Atas (TV + U)</small><strong class="text-danger">${batasAtas.toFixed(4)}</strong></div></div>
            <div class="col-md-3 col-6"><div class="p-2 border rounded text-center bg-success bg-opacity-10"><small class="d-block text-muted">True Value (Sertifikat)</small><strong class="text-success">${certVal.toFixed(4)}</strong></div></div>
            <div class="col-md-3 col-6"><div class="p-2 border rounded text-center bg-danger bg-opacity-10"><small class="d-block text-muted">Batas Bawah (TV - U)</small><strong class="text-danger">${batasBawah.toFixed(4)}</strong></div></div>
            <div class="col-md-3 col-6"><div class="p-2 border rounded text-center"><small class="d-block text-muted">Jumlah Data</small><strong>${logs.length} Pengujian</strong></div></div>
        `;

        // --- Siapkan Data ---
        const labels = [];
        const dataPoints = [];
        const pointColors = [];
        const pointRadii = [];

        logs.forEach((log, idx) => {
            labels.push(log.tanggal_uji);
            dataPoints.push(parseFloat(log.nilai_akhir));

            let isTrend = result.trend_indices && result.trend_indices.includes(idx);

            if (log.status_evaluasi === 'outlier') {
                pointColors.push('rgba(220, 53, 69, 1)');
                pointRadii.push(7);
            } else if (isTrend) {
                pointColors.push('rgba(255, 152, 0, 1)');
                pointRadii.push(6);
            } else {
                pointColors.push('rgba(0, 0, 0, 1)');
                pointRadii.push(4);
            }
        });

        const len = Math.max(10, labels.length);
        if (labels.length < 10) {
            for (let i = labels.length; i < 10; i++) labels.push('...');
        }

        const arrCertVal = Array(len).fill(certVal);
        const arrBatasAtas = Array(len).fill(batasAtas);
        const arrBatasBawah = Array(len).fill(batasBawah);

        // --- Gambar Chart ---
        if (crmChart) crmChart.destroy();

        if (typeof ChartDataLabels !== 'undefined') Chart.register(ChartDataLabels);

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
                    {
                        label: 'True Value',
                        data: arrCertVal,
                        borderColor: 'rgba(25, 135, 84, 0.9)',
                        borderWidth: 2,
                        pointRadius: 0, order: 1,
                        datalabels: { display: false }
                    },
                    {
                        label: 'Batas Atas (TV + U)',
                        data: arrBatasAtas,
                        borderColor: 'rgba(220, 53, 69, 0.7)',
                        borderWidth: 2, borderDash: [6, 4],
                        pointRadius: 0, order: 2,
                        datalabels: { display: false }
                    },
                    {
                        label: 'Batas Bawah (TV - U)',
                        data: arrBatasBawah,
                        borderColor: 'rgba(220, 53, 69, 0.7)',
                        borderWidth: 2, borderDash: [6, 4],
                        pointRadius: 0, order: 3,
                        datalabels: { display: false }
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                if (ctx.datasetIndex === 0) {
                                    let log = logs[ctx.dataIndex];
                                    if (!log) return 'Nilai: ' + ctx.raw;
                                    return 'Nilai: ' + parseFloat(ctx.raw).toFixed(4) + ' | ' + log.status_evaluasi.toUpperCase();
                                }
                                return ctx.dataset.label + ': ' + parseFloat(ctx.raw).toFixed(4);
                            }
                        }
                    },
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: {
                        suggestedMax: batasAtas + (certU * 0.5),
                        suggestedMin: batasBawah - (certU * 0.5)
                    }
                }
            }
        });

        // --- Isi Tabel ---
        let tbody = '';
        logs.forEach((log, idx) => {
            let statusClass = log.status_evaluasi === 'outlier' ? 'text-danger fw-bold' : 'text-success';
            let statusLabel = log.status_evaluasi === 'outlier' ? '<span class="badge bg-danger">Outlier</span>' : '<span class="badge bg-success">Inlier</span>';
            let isTrend = result.trend_indices && result.trend_indices.includes(idx);
            if (isTrend) statusLabel += ' <span class="badge bg-warning text-dark">Trend</span>';

            tbody += '<tr' + (isTrend ? ' class="table-warning"' : '') + '>' +
                '<td>' + (idx + 1) + '</td>' +
                '<td>' + log.tanggal_uji + '</td>' +
                '<td>' + (log.analis ? log.analis.nama : '-') + '</td>' +
                '<td class="' + statusClass + '">' + parseFloat(log.nilai_akhir).toFixed(4) + '</td>' +
                '<td>' + certVal.toFixed(4) + ' &plusmn; ' + certU.toFixed(4) + '</td>' +
                '<td>' + statusLabel + '</td>' +
                '</tr>';
        });
        document.getElementById('chartTableBody').innerHTML = tbody;
    }
})();
</script>
@endsection
