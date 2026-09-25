@extends('layouts.app')
@section('title', 'Dashboard - QC CRM')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="CRM" />

    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0"><i class="fas fa-certificate text-purple me-2"></i>QC CRM Dashboard</h2>
            <p class="text-muted mb-0 mt-1">Kelola Master Botol, Uji Verifikasi, dan Pengujian Harian CRM.</p>
        </div>
        <div>
            <a href="{{ route('crm-katalog.create') }}" class="btn btn-outline-primary shadow-sm me-2">
                <i class="fas fa-plus me-1"></i> Botol CRM Baru
            </a>
            <a href="{{ route('qc-crm.create') }}" class="btn btn-primary shadow-sm">
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
    </ul>

    <!-- TABS CONTENT -->
    <div class="tab-content" id="qcCrmTabsContent">
        
        <!-- TAB 1: MASTER BOTOL & VERIFIKASI -->
        <div class="tab-pane fade show active" id="katalog" role="tabpanel" aria-labelledby="katalog-tab">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
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
                                                // Cek apakah ada record verifikasi yang berstatus draft
                                                $hasDraft = $r->verifikasiTeknis->where('status_evaluasi', 'draft')->isNotEmpty();
                                            @endphp
                                            
                                            <a href="{{ route('crm-katalog.verifikasi-teknis.form', $r->id) }}" 
                                            class="btn btn-sm {{ $hasDraft ? 'btn-warning' : 'btn-danger pulse-button' }} mb-1" 
                                            title="{{ $hasDraft ? 'Lanjutkan Draft Verifikasi' : 'Lakukan Verifikasi Teknis' }}">
                                                <i class="fas {{ $hasDraft ? 'fa-edit' : 'fa-flask' }}"></i> 
                                                {{ $hasDraft ? 'Lanjut Draft' : 'Mulai Uji Verifikasi' }}
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
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tableHarianCRM">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Tanggal Uji</th>
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
                                    <td class="ps-4">{{ $log->tanggal_uji->format('d M Y') }}</td>
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
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Outlier</span>
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
    var tableHarian = $('#tableHarianCRM').DataTable({
        "order": [],
        "pageLength": 10,
        "language": {
            "search": "Cari Riwayat:",
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

    // Cek Draft Lokal
    try {
        let rawDraft = localStorage.getItem('qc_crm_draft');
        if(rawDraft) {
            let draft = JSON.parse(rawDraft);
            
            let dateDraft = draft.tanggal_uji ? draft.tanggal_uji : 'Belum diisi';
            let paramsCount = Object.keys(draft.params || {}).filter(k => draft.params[k].selected).length;
            
            let crmName = "Botol ID: " + (draft.crm_katalog_id || '?');
            @php
                $crmMap = $katalogs->mapWithKeys(function($k) {
                    return [$k->id => ['lot' => $k->nomor_lot, 'name' => $k->nama_produk]];
                })->toJson();
            @endphp
            let crmData = {!! $crmMap !!};
            if(draft.crm_katalog_id && crmData[draft.crm_katalog_id]) {
                crmName = '<span class="badge bg-primary fs-6">' + crmData[draft.crm_katalog_id].lot + '</span><br><small class="text-muted">' + crmData[draft.crm_katalog_id].name + '</small>';
            }

            let btnLanjut = `<a href="{{ route('qc-crm.create') }}" class="btn btn-sm btn-warning rounded-pill mt-1"><i class="fas fa-pencil-alt"></i> Lanjutkan Draft</a>`;

            let draftRow = tableHarian.row.add([
                '<span class="text-warning fw-bold">' + dateDraft + '</span>',
                crmName,
                '<span class="badge bg-secondary">' + paramsCount + ' Parameter Uji</span>',
                '<span class="text-muted">-</span>',
                '<span class="text-muted fst-italic">Tersimpan: ' + (draft.saved_at || '-') + '</span>',
                '<span class="text-muted">-</span>',
                '<span class="badge bg-warning text-dark mb-1"><i class="fas fa-edit"></i> DRAFT LOKAL</span><br>' + btnLanjut
            ]).draw(false).node();

            $(draftRow).addClass('table-warning');
        }
    } catch(e) {}
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
@endsection
