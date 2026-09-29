@extends('layouts.app')
@section('title', 'Pengujian Harian QC')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="In-House">
        @if($activeBatch)
            <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $activeBatch->sampel_inhouse_id) }}" class="text-decoration-none">{{ $activeBatch->kode_batch }}</a></li>
        @endif
        <li class="breadcrumb-item active">Pengujian Harian QC</li>
    </x-qc-breadcrumb>
    <div class="d-flex justify-content-between align-items-center mb-4"><h1 class="fw-bold text-dark mb-0">
        <i class="fas fa-chart-line text-danger me-2"></i>Pengujian Harian QC
    </h1><a href="{{ route('parameter-uji.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm"><i class="fas fa-cogs me-1"></i> Master Parameter Uji</a></div>

    @if(!$activeBatch)
    <div class="alert alert-warning border-0 shadow-sm">
        <i class="fas fa-exclamation-triangle me-2"></i> Belum ada Batch QC In-House yang berstatus <strong>Aktif</strong>. Silakan selesaikan proses Uji Stabilitas terlebih dahulu.
    </div>
    @else
    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body bg-light rounded d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1">Batch Aktif: {{ $activeBatch->kode_batch }}</h5>
                <p class="text-muted mb-0 small">Masa Berlaku: {{ $activeBatch->tanggal_dibuat ? \Carbon\Carbon::parse($activeBatch->tanggal_dibuat)->format('d M Y') : '-' }} s/d Selesai</p>
            </div>
            <div>
                <button type="button" class="btn btn-outline-danger rounded-pill px-3 shadow-sm me-2" data-bs-toggle="modal" data-bs-target="#modalCetakPdf">
                    <i class="fas fa-file-pdf me-1"></i> Cetak Laporan
                </button>
                <a href="{{ route('qc-harian.create') }}" class="btn btn-danger rounded-pill px-4 shadow-sm">
                    <i class="fas fa-plus me-1"></i> Input Data Harian
                </a>
            </div>
        </div>
    </div>

    <!-- Parameter Status Table (Redesigned as requested) -->
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-list me-2"></i>Daftar Parameter Uji Harian</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Nama Parameter</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center">Nilai Acuan (Mean)</th>
                            <th class="text-center">Batas Peringatan (± 2SD)</th>
                            <th class="text-center">Metode/Kriteria</th>
                            <th class="text-center">Status Harian</th>
                            <th class="text-center" style="width: 200px;">Aksi</th>
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
                                <td class="text-center">
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
                                <td class="text-center">
                                    @if($isLocked)
                                        <a href="#riwayat-table" class="btn btn-sm btn-outline-danger" title="Lakukan Investigasi" onclick="alert('Silakan cek tabel riwayat di bawah untuk mengisi form investigasi.')"><i class="fas fa-search me-1"></i> Investigasi</a>
                                    @else
                                        <a href="{{ route('qc-harian.chart', $param->parameter_uji_id) }}" class="btn btn-secondary btn-sm" title="Control Chart"><i class="fas fa-chart-line me-1"></i> Control Chart</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada parameter yang stabil.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Semua Data -->
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
                <table class="table table-hover align-middle mb-0" id="tableRiwayat">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Parameter</th>
                            <th>Nilai D1</th>
                            <th>Nilai D2</th>
                            <th>Nilai Akhir</th>
                            <th>Status (Westgard)</th>
                            <th>Analis</th>
                            <th class="pe-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogs as $log)
                        <tr>
                            <td class="ps-4">{{ \Carbon\Carbon::parse($log->tanggal_uji)->format('d M Y') }}</td>
                            <td class="fw-bold">{{ strtoupper($log->parameterUji->nama_parameter) }}</td>
                            <td>{{ number_format($log->nilai_d1, 2) }}</td>
                            <td>{{ $log->nilai_d2 ? number_format($log->nilai_d2, 2) : '-' }}</td>
                            <td class="fw-bold">{{ number_format($log->nilai_akhir, 2) }}</td>
                            <td>
                                @if($log->status_evaluasi === 'inlier')
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i> Inlier</span>
                                @elseif($log->status_evaluasi === 'warning')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i> Warning</span>
                                    <small class="d-block text-muted mt-1">{{ $log->pelanggaran_rule }}</small>
                                @elseif($log->status_evaluasi === 'outlier')
                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Outlier</span>
                                    <small class="d-block text-danger mt-1 fw-bold">{{ $log->pelanggaran_rule }}</small>
                                @endif
                            </td>
                            <td>{{ $log->analis ? $log->analis->username : '-' }}</td>
                            <td class="pe-4 text-center">
<button type="button" class="btn btn-sm btn-info text-white me-1 mb-1 btn-detail-qc"
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
                                    data-mentah="{{ htmlspecialchars(json_encode($log->data_mentah ?? []), ENT_QUOTES, 'UTF-8') }}">
                                    <i class="fas fa-eye"></i> Detail
                                </button>

                                @if($log->status_evaluasi === 'outlier')
                                    @if($log->status_investigasi === 'menunggu_investigasi')
                                        <a href="{{ route('qc-harian.investigasi', $log->id) }}" class="btn btn-sm btn-danger pulse-button mb-1"><i class="fas fa-edit"></i> Isi Investigasi</a>
                                    @else
                                        <button class="btn btn-sm btn-outline-success mb-1" disabled><i class="fas fa-check-double"></i> Investigasi Selesai</button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Belum ada data pengujian harian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

<!-- Modal Detail QC Harian -->
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
                            <th class="bg-light">Nilai D1</th>
                            <td id="detD1"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Nilai D2</th>
                            <td id="detD2"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Average</th>
                            <td id="detAkhir" class="fw-bold"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Mean</th>
                            <td id="detMean"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Standar Deviasi</th>
                            <td id="detSD"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Status Evaluasi (Westgard)</th>
                            <td id="detStatus"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Pelanggaran Rule</th>
                            <td id="detRule" class="text-danger fw-bold"></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Catatan Investigasi</th>
                            <td id="detInvestigasi"></td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- Container untuk tabel kalkulasi pengujian mentah -->
                <h6 class="mt-4 mb-2 fw-bold text-secondary"><i class="fas fa-calculator me-2"></i>Tabel Kalkulasi Pengujian</h6>
                <div id="detTableKalkulasi" class="table-responsive"></div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CETAK PDF -->
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
            
            // Generate Tabel Kalkulasi
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

<style>
    .pulse-button {
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
    
    /* Custom DataTables Pagination Style (Reference Match) */
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

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Custom DataTables filter for Bulan & Tahun
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        let filterBulan = $('#filterBulan').val();
        let filterTahun = $('#filterTahun').val();
        
        // Kolom pertama adalah Tanggal (contoh: 21 Apr 2026 atau 21 April 2026)
        let dateStr = data[0] || ""; 
        
        // Cek filter bulan
        if (filterBulan && !dateStr.includes(filterBulan)) {
            return false;
        }
        
        // Cek filter tahun
        if (filterTahun && !dateStr.includes(filterTahun)) {
            return false;
        }
        
        return true;
    });

    let table = $('#tableRiwayat').DataTable({
        "order": [], // Maintain default server sort (created_at desc)
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

    // Re-draw table ketika dropdown diubah
    $('#filterBulan, #filterTahun').on('change', function() {
        table.draw();
    });
});
</script>

@endsection

