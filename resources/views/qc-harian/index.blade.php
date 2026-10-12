@extends('layouts.app')
@section('title', 'Pengujian Harian QC In-House')

@section('content')
<style>
    .table-corporate thead th {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-bottom: 2px solid #0f1c2f !important;
    }
</style>

<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="In-House">
        @if($activeBatch)
            <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $activeBatch->sampel_inhouse_id) }}" class="text-decoration-none">{{ $activeBatch->kode_batch }}</a></li>
        @endif
        <li class="breadcrumb-item active">Pengujian Harian QC In-House</li>
    </x-qc-breadcrumb>

    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="fw-bold text-dark mb-0">
                <i class="fas fa-chart-line text-danger me-2"></i>Pengujian Harian QC In-House
            </h1>
        </div>

        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="d-grid d-md-inline-block">
                <a href="{{ route('parameter-uji.index') }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="fas fa-cogs me-1"></i> Master Parameter Uji
                </a>
            </div>
        </div>
    </div>

    @if(!$activeBatch)
    <div class="alert alert-warning border-0 shadow-sm">
        <i class="fas fa-exclamation-triangle me-2"></i> Belum ada Batch QC In-House yang berstatus <strong>Aktif</strong>. Silakan selesaikan proses Uji Stabilitas terlebih dahulu.
    </div>
    @else
    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body bg-light rounded">
            <div class="row align-items-center">
                
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5 class="fw-bold mb-1">Batch Aktif: {{ $activeBatch->kode_batch }}</h5>
                    <p class="text-muted mb-0 small">Masa Berlaku: {{ $activeBatch->tanggal_dibuat ? \Carbon\Carbon::parse($activeBatch->tanggal_dibuat)->format('d M Y') : '-' }} s/d Selesai</p>
                </div>
                
                <div class="col-md-6">
                    <div class="d-flex flex-column flex-md-row justify-content-start justify-content-md-end gap-2">
                        <button type="button" class="btn btn-outline-danger rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCetakPdf">
                            <i class="fas fa-file-pdf me-1"></i> Cetak Laporan
                        </button>
                        <a href="{{ route('qc-harian.create') }}" class="btn btn-danger rounded-pill px-4 shadow-sm">
                            <i class="fas fa-plus me-1"></i> Input Data Harian
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-5">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-list me-2"></i>Daftar Parameter Uji Harian</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-corporate align-middle mb-0" style="font-size: 0.9rem;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Nama Parameter</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center">Nilai Acuan (Mean)</th>
                            <th class="text-center">Batas Peringatan (± 2SD)</th>
                            <th class="text-center">Status Harian</th>
                            <th class="text-center" style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parameters as $idx => $param)
                            @php
                                $isLocked = \App\Models\QcHarian::where('sampel_inhouse_id', $activeBatch->sampel_inhouse_id)
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
                                <td class="text-center">
                                    {{ number_format($param->parameterUji->mean - (2 * $param->parameterUji->sd), 4) }} - 
                                    {{ number_format($param->parameterUji->mean + (2 * $param->parameterUji->sd), 4) }}
                                </td>
                                <td class="text-center">
                                    @if($isLocked)
                                        <span class="badge bg-danger"><i class="fas fa-lock me-1"></i> OOC (LOCKED)</span>
                                    @else
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> AMAN</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($isLocked)
                                        @php
                                            $lockedLog = \App\Models\QcHarian::where('sampel_inhouse_id', $activeBatch->sampel_inhouse_id)
                                                ->where('parameter_uji_id', $param->parameter_uji_id)
                                                ->where('status_evaluasi', 'outlier')
                                                ->where('status_investigasi', 'menunggu_investigasi')
                                                ->latest('id')->first();
                                        @endphp
                                        <a href="{{ route('qc-harian.investigasi', $lockedLog->id) }}" class="btn btn-sm btn-outline-danger" title="Lakukan Investigasi">
                                            <i class="fas fa-search me-1"></i> Investigasi
                                        </a>
                                    @else
                                        <a href="{{ route('qc-harian.chart', $param->parameter_uji_id) }}" class="btn btn-secondary btn-sm" title="Control Chart"><i class="fas fa-chart-line me-1"></i> Control Chart</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada parameter yang stabil.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 fw-bold"><i class="fas fa-history me-2 text-secondary"></i>Riwayat Pengujian</h4>
        <div class="d-flex gap-2">
            <select id="filterBulan" class="form-select form-select-sm" style="width: 140px;">
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
            <select id="filterTahun" class="form-select form-select-sm" style="width: 120px;">
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
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover table-corporate align-middle mb-0" id="tableRiwayat">
                    <thead>
                        <tr>
                            <th class="ps-4">Tanggal Uji</th>
                            <th>Nama Sampel</th>
                            <th>Analis</th>
                            <th>Status / Info</th>
                            <th class="pe-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedLogs as $groupId => $logs)
                            @php
                                $first = $logs->first();
                                $isDraft = $first->status_pengujian === 'draft';
                                $outlierCount = $logs->where('status_evaluasi', 'outlier')->count();
                                $htmlId = \Illuminate\Support\Str::slug($groupId);
                            @endphp
                            <tr>
                                <td class="ps-4 fw-bold">{{ \Carbon\Carbon::parse($first->tanggal_uji)->format('d M Y') }}</td>
                                <td>{{ $first->data_mentah['nama_sampel_uji'] ?? '-' }}</td>
                                <td>{{ $first->analis ? $first->analis->username : '-' }}</td>
                                <td>
                                    @if($isDraft)
                                        <span class="badge bg-secondary"><i class="fas fa-pencil-alt me-1"></i> DRAFT ({{ $logs->count() }} Parameter)</span>
                                    @elseif($outlierCount > 0)
                                        <span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i> Terdapat {{ $outlierCount }} Outlier</span>
                                    @else
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Selesai ({{ $logs->count() }} Parameter)</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-center">
                                    <button type="button" class="btn btn-sm btn-info text-white shadow-sm btn-expand" data-group="{{ $htmlId }}" title="Lihat Detail Parameter">
                                        <i class="fas fa-plus"></i> Detail
                                    </button>

                                    @if($isDraft)
                                        <a href="{{ route('qc-harian.draft.edit', $first->id) }}" class="btn btn-sm btn-warning text-dark shadow-sm ms-1" title="Lanjutkan Draft">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('qc-harian.draft.destroy', $first->id) }}" method="POST" class="d-inline form-delete-draft ms-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger shadow-sm" title="Hapus Draft">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <div id="child-{{ $htmlId }}" class="d-none">
                                        <div class="p-3 bg-light border rounded shadow-sm text-start">
                                            <h6 class="fw-bold mb-3 text-secondary"><i class="fas fa-list me-2"></i>Rincian Parameter Uji</h6>
                                            <table class="table table-sm table-bordered bg-white mb-0" style="font-size: 0.85rem;">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Parameter</th>
                                                        <th>Nilai D1</th>
                                                        <th>Nilai D2</th>
                                                        <th>Nilai Akhir</th>
                                                        <th>Status (Westgard)</th>
                                                        @if(!$isDraft)
                                                        <th class="text-center">Aksi Detail</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($logs as $log)
                                                        <tr>
                                                            <td class="fw-bold">{{ strtoupper($log->parameterUji->nama_parameter) }}</td>
                                                            <td>{{ number_format($log->nilai_d1, 2) }}</td>
                                                            <td>{{ $log->nilai_d2 ? number_format($log->nilai_d2, 2) : '-' }}</td>
                                                            <td class="fw-bold text-primary">{{ $log->status_pengujian === 'draft' ? '-' : number_format($log->nilai_akhir, 2) }}</td>
                                                            <td>
                                                                @if($log->status_pengujian === 'draft')
                                                                    <span class="badge bg-secondary">Draft</span>
                                                                @else
                                                                    @if($log->status_evaluasi === 'inlier')
                                                                        <span class="badge bg-success"><i class="fas fa-check"></i> Inlier</span>
                                                                    @elseif($log->status_evaluasi === 'warning')
                                                                        <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle"></i> Warning</span>
                                                                        <small class="d-block text-muted mt-1">{{ $log->pelanggaran_rule }}</small>
                                                                    @elseif($log->status_evaluasi === 'outlier')
                                                                        <span class="badge bg-danger"><i class="fas fa-times"></i> Outlier</span>
                                                                        <small class="d-block text-danger fw-bold mt-1">{{ $log->pelanggaran_rule }}</small>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            @if(!$isDraft)
                                                            <td class="text-center">
                                                                <button type="button" class="btn btn-sm btn-info text-white btn-detail-qc py-0 px-2 shadow-sm"
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
                                                                    data-status="{{ $log->status_evaluasi }}"
                                                                    data-rule="{{ $log->pelanggaran_rule ?? '-' }}"
                                                                    data-investigasi="{{ $log->catatan_investigasi ?? '-' }}"
                                                                    data-mentah="{{ json_encode($log->data_mentah) }}"
                                                                    title="Lihat Kalkulasi">
                                                                <i class="fas fa-calculator"></i>
                                                            </button>
                                                                    @if($log->status_evaluasi === 'outlier')
                                                                        @if($log->status_investigasi === 'menunggu_investigasi')
                                                                            <a href="{{ route('qc-harian.investigasi', $log->id) }}" class="btn btn-sm btn-danger text-white py-0 px-2 shadow-sm ms-1" title="Form Investigasi">
                                                                                <i class="fas fa-search"></i> Investigasi
                                                                            </a>
                                                                        @else
                                                                            <button type="button" class="btn btn-sm btn-success text-white py-0 px-2 shadow-sm ms-1 btn-view-capa"
                                                                                data-tanggal="{{ \Carbon\Carbon::parse($log->tanggal_uji)->format('d M Y') }}"
                                                                                data-sampel="{{ $first->data_mentah['nama_sampel_uji'] ?? ($log->sampelInhouse->kode_sampel ?? '-') }}"
                                                                                data-parameter="{{ strtoupper($log->parameterUji->nama_parameter) }}"
                                                                                data-analis="{{ $log->analis ? ($log->analis->name ?? $log->analis->username) : '-' }}"
                                                                                data-d1="{{ number_format($log->nilai_d1, 2) }}"
                                                                                data-d2="{{ $log->nilai_d2 ? number_format($log->nilai_d2, 2) : '-' }}"
                                                                                data-akhir="{{ number_format($log->nilai_akhir, 2) }}"
                                                                                data-mean="{{ number_format($log->mean_acuan, 2) }}"
                                                                                data-sd="{{ number_format($log->sd_acuan, 2) }}"
                                                                                data-rule="{{ $log->pelanggaran_rule ?? '-' }}"
                                                                                data-akar="{{ $log->akar_masalah ?? '-' }}"
                                                                                data-temuan="{{ $log->catatan_investigasi ?? '-' }}"
                                                                                data-perbaikan="{{ $log->tindakan_perbaikan ?? '-' }}"
                                                                                data-pdf-url="{{ route('qc-harian.investigasi.pdf', $log->id) }}"
                                                                                title="Lihat Laporan CAPA">
                                                                                <i class="fas fa-file-alt me-1"></i> Baca CAPA
                                                                            </button>
                                                                        @endif
                                                                    @endif
                                                            </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalDetailQcHarianLabel"><i class="fas fa-info-circle me-2"></i>Detail Pengujian Harian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="bg-light" width="35%">Tanggal Pengujian</th>
                            <td id="detTanggal"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Parameter</th>
                            <td id="detParameter" class="fw-bold"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Analis</th>
                            <td id="detAnalis"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Status Evaluasi (Westgard)</th>
                            <td id="detStatus"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Pelanggaran Rule</th>
                            <td id="detRule" class="text-danger fw-bold"></td>
                        </tr>
                    </tbody>
                </table>

                <h6 class="mt-4 mb-2 fw-bold text-secondary"><i class="fas fa-calculator me-2"></i>Tabel Kalkulasi Pengujian</h6>
                <div id="detTableKalkulasi" class="table-responsive"></div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCetakPdf" tabindex="-1" aria-labelledby="modalCetakPdfLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('qc-harian.print-pdf') }}" method="GET" target="_blank" class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalCetakPdfLabel"><i class="fas fa-file-pdf me-2"></i> Cetak Laporan Pengujian Harian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Pilih Bulan</label>
                        <select name="bulan" class="form-select" required>
                            @php
                                $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                $currentMonth = date('n');
                            @endphp
                            @foreach($months as $idx => $mName)
                                <option value="{{ $idx + 1 }}" {{ $currentMonth == ($idx + 1) ? 'selected' : '' }}>{{ $mName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Pilih Tahun</label>
                        <select name="tahun" class="form-select" required>
                            @php
                                $currentYear = date('Y');
                            @endphp
                            @for($y = $currentYear; $y >= $currentYear - 2; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Bagian yang Ingin Dicetak</label>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="cetak_riwayat" value="1" id="cRiwayat" checked>
                        <label class="form-check-label" for="cRiwayat">Riwayat Data Pengujian Harian (Tabel Rekapan)</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="cetak_chart" value="1" id="cChart" checked>
                        <label class="form-check-label" for="cChart">Control Chart (Diagram Grafik & Tabel Kalkulasi Limit)</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Parameter</label>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="param_type" id="pSemua" value="all" checked onchange="toggleParamCheckboxes(false)">
                        <label class="form-check-label" for="pSemua">Semua Parameter Aktif</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="param_type" id="pKhusus" value="specific" onchange="toggleParamCheckboxes(true)">
                        <label class="form-check-label" for="pKhusus">Pilih Parameter Spesifik...</label>
                    </div>

                    <div id="paramCheckboxes" class="mt-3 p-3 bg-light rounded d-none border">
                        <div class="row">
                            @foreach($parameters as $param)
                                <div class="col-md-4 mb-2">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger"><i class="fas fa-print me-2"></i> Tampilkan & Cetak</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalViewCapaQcHarian" tabindex="-1" aria-labelledby="modalViewCapaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="modalViewCapaLabel">
                    <i class="fas fa-file-signature me-2"></i>Laporan Investigasi & Tindakan Perbaikan (CAPA)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="card border-0 bg-light p-3 mb-3">
                    <div class="row g-2 small">
                        <div class="col-md-6"><strong>Tanggal Uji:</strong> <span id="capaTanggal">-</span></div>
                        <div class="col-md-6"><strong>Kode Sampel:</strong> <span id="capaSampel">-</span></div>
                        <div class="col-md-6"><strong>Parameter:</strong> <span id="capaParam" class="fw-bold text-danger">-</span></div>
                        <div class="col-md-6"><strong>Analis:</strong> <span id="capaAnalis">-</span></div>
                        <div class="col-md-6"><strong>Nilai D1 / D2:</strong> <span id="capaD1D2">-</span></div>
                        <div class="col-md-6"><strong>Nilai Akhir (Outlier):</strong> <span id="capaAkhir" class="badge bg-danger fs-6">-</span></div>
                        <div class="col-md-6"><strong>Target Acuan:</strong> <span id="capaAcuan">-</span></div>
                        <div class="col-md-6"><strong>Rule Westgard:</strong> <span id="capaRule" class="text-danger fw-bold">-</span></div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary mb-1"><i class="fas fa-tag me-1"></i>Kategori Akar Masalah</label>
                    <div class="p-2 border rounded bg-white fw-bold text-dark" id="capaAkar">-</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary mb-1"><i class="fas fa-search me-1"></i>Detail Temuan Investigasi</label>
                    <div class="p-3 border rounded bg-white" style="white-space: pre-wrap;" id="capaTemuan">-</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary mb-1"><i class="fas fa-tools me-1"></i>Tindakan Perbaikan (Corrective Action)</label>
                    <div class="p-3 border rounded bg-white" style="white-space: pre-wrap;" id="capaPerbaikan">-</div>
                </div>
            </div>
            <div class="modal-footer bg-light justify-content-between">
                <a href="#" id="btnDownloadCapaPdf" target="_blank" class="btn btn-outline-danger shadow-sm">
                    <i class="fas fa-file-pdf me-1"></i> Cetak Lembar CAPA (PDF)
                </a>
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
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
    
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 20px !important;
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
    .dataTables_wrapper .page-item.disabled .page-link {
        color: #aaa;
    }
</style>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

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

    $(document).ready(function() {
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            let filterBulan = $('#filterBulan').val();
            let filterTahun = $('#filterTahun').val();
            let dateStr = data[0] || ""; 
            if (filterBulan && !dateStr.includes(filterBulan)) return false;
            if (filterTahun && !dateStr.includes(filterTahun)) return false;
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

        $('#tableRiwayat tbody').on('click', 'button.btn-expand', function () {
            let tr = $(this).closest('tr');
            let row = table.row(tr);
            let groupId = $(this).data('group');
            let childHtml = $('#child-' + groupId).html();
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                $(this).html('<i class="fas fa-plus"></i> Detail');
                $(this).removeClass('btn-secondary').addClass('btn-info text-white');
            } else {
                row.child(childHtml).show();
                tr.addClass('shown');
                $(this).html('<i class="fas fa-minus"></i> Tutup');
                $(this).removeClass('btn-info text-white').addClass('btn-secondary');
            }
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
                cancelButtonColor: '#1b3152', 
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

        $('body').on('click', '.btn-detail-qc', function() {
            document.getElementById('detTanggal').innerText = this.getAttribute('data-tanggal');
            document.getElementById('detParameter').innerText = this.getAttribute('data-parameter');
            document.getElementById('detAnalis').innerText = this.getAttribute('data-analis');
            
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

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-view-capa');
        if (!btn) return;

        document.getElementById('capaTanggal').textContent = btn.dataset.tanggal;
        document.getElementById('capaSampel').textContent = btn.dataset.sampel;
        document.getElementById('capaParam').textContent = btn.dataset.parameter;
        document.getElementById('capaAnalis').textContent = btn.dataset.analis;
        document.getElementById('capaD1D2').textContent = `D1: ${btn.dataset.d1} | D2: ${btn.dataset.d2}`;
        document.getElementById('capaAkhir').textContent = btn.dataset.akhir;
        document.getElementById('capaAcuan').textContent = `Mean: ${btn.dataset.mean} | SD: ${btn.dataset.sd}`;
        document.getElementById('capaRule').textContent = btn.dataset.rule;
        document.getElementById('capaAkar').textContent = btn.dataset.akar;
        document.getElementById('capaTemuan').textContent = btn.dataset.temuan;
        document.getElementById('capaPerbaikan').textContent = btn.dataset.perbaikan;
        document.getElementById('btnDownloadCapaPdf').href = btn.dataset.pdfUrl;

        const modal = new bootstrap.Modal(document.getElementById('modalViewCapaQcHarian'));
        modal.show();
    });
</script>

@endsection

