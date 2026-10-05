@include('parameter-uji.partials.limit-eval-alert')

<style>
    .cv-page { padding-top: 2px; font-size: 0.8rem; }
    .cv-page .breadcrumb { font-size: 0.72rem; margin-bottom: 0.4rem; }
    .cv-page .cv-title { font-size: 1.05rem; font-weight: 700; color: #1b3152; margin-bottom: 0.75rem; }

    .cv-page .card { border: 0; border-radius: 0.5rem; }
    .cv-page .card-header { background-color: #fff; border-bottom: 1px solid #e9ecef; padding: 0.6rem 0.9rem !important; }
    .cv-page .card-header h6 { font-size: 0.85rem; color: #1b3152 !important; margin: 0; }
    .cv-page .card-body { padding: 0.75rem 0.9rem; }

    .cv-page .form-label { font-size: 0.72rem; font-weight: 600; margin-bottom: 0.2rem; color: #495057; }
    .cv-page .form-control,
    .cv-page .form-select { font-size: 0.78rem; padding: 0.3rem 0.55rem; }
    .cv-page .form-control:focus,
    .cv-page .form-select:focus {
        border-color: #1b3152;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
    }

    .cv-page .btn {
        font-size: 0.75rem;
        padding: 0.32rem 0.8rem;
        font-weight: 600;
        border-radius: 0.375rem;
        transition: background-color .15s ease, border-color .15s ease, color .15s ease, box-shadow .15s ease, transform .15s ease;
    }
    .cv-page .btn:hover { transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0, 0, 0, 0.18); }
    .cv-page .btn:active { transform: none; box-shadow: none; }
    .btn-corporate-blue {
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
        color: #fff !important;
    }
    .btn-corporate-blue:hover,
    .btn-corporate-blue:focus,
    .btn-corporate-blue:active {
        background-color: #14253e !important;
        border-color: #14253e !important;
        color: #fff !important;
    }
    .cv-page .btn-icon { padding: 0.2rem 0.5rem; font-size: 0.72rem; }

    .cv-filter-actions { display: flex; gap: 0.5rem; }

    .cv-table { font-size: 0.72rem; margin-bottom: 0; }
    .cv-table th,
    .cv-table td { padding: 0.3rem 0.4rem !important; vertical-align: middle !important; }
    .cv-table thead th {
        background-color: #1b3152 !important;
        color: #fff !important;
        border-color: rgba(255, 255, 255, 0.45) !important;
        font-weight: 600;
        white-space: nowrap;
    }
    .cv-table thead th.th-dish   { background-color: #2f5d9e !important; color: #fff !important; }
    .cv-table thead th.th-manual { background-color: #4a77b4 !important; color: #fff !important; }
    .cv-table thead th.th-auto   { background-color: #6b7a90 !important; color: #fff !important; }
    .cv-table thead th.th-ts     { background-color: #7a5ea8 !important; color: #fff !important; }
    .cv-table thead th.th-diff   { background-color: #d9a21b !important; color: #1b1b1b !important; }
    .cv-table thead th.th-im     { background-color: #2a8fa6 !important; color: #fff !important; }
    .cv-table thead th.th-avg    { background-color: #2a8fa6 !important; color: #fff !important; }
    .cv-table thead th.th-final  { background-color: #2b7a4b !important; color: #fff !important; }
    .cv-table thead th.th-rule   { background-color: #2a8fa6 !important; color: #fff !important; }
    .cv-table thead th.th-rule-sub { background-color: #5fb0c2 !important; color: #fff !important; }
    .cv-table thead th.th-status { background-color: #2b7a4b !important; color: #fff !important; }
    .cv-table thead th.th-note   { background-color: #6b7a90 !important; color: #fff !important; }

    .cv-table-raw { min-width: 1100px; }
    .cv-table-raw td { white-space: nowrap; }
    .cv-table-eval { min-width: 640px; }
    .cv-table-eval td.cv-alasan { min-width: 140px; }

    .cv-chart-wrap { height: 400px; width: 100%; }

    .cv-scroll-hint { display: none; font-size: 0.68rem; color: #6c757d; margin-bottom: 0.4rem; }

    @media (max-width: 991.98px) {
        .cv-chart-wrap { height: 320px; }
    }

    @media (max-width: 767.98px) {
        .cv-page .cv-title { font-size: 0.95rem; }
        .cv-page .card-body { padding: 0.6rem; }
        .cv-scroll-hint { display: block; }
        .cv-chart-wrap { height: 260px; }
        .cv-filter-actions .btn { flex: 1; }
        .cv-table { font-size: 0.68rem; }
    }
</style>

<div class="container-fluid cv-page px-2 px-md-3">
    <h1 class="cv-title">Inhouse Control - {{ $selectedParameter->nama_parameter }}</h1>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form action="{{ route('parameter-uji.show', $selectedParameter->parameter_uji_id) }}" method="GET" class="row g-2 align-items-end">
                <input type="hidden" name="tab" value="{{ $jenisGrafik }}">
                <div class="col-12 col-md-3">
                    <label class="form-label">Parameter Uji</label>
                    <select name="parameter_uji_id" class="form-select form-select-sm" required>
                        <option value="">-- Pilih Parameter --</option>
                        @foreach($parameterList as $p)
                            <option value="{{ $p->parameter_uji_id }}" {{ request('parameter_uji_id') == $p->parameter_uji_id ? 'selected' : '' }}>
                                {{ $p->nama_parameter }} ({{ $p->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control form-control-sm" value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" class="form-control form-control-sm" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-12 col-md-3">
                    <div class="cv-filter-actions">
                        <button type="submit" class="btn btn-corporate-blue shadow-sm"><i class="fas fa-filter me-1"></i> Tampilkan</button>
                        @if(request('parameter_uji_id'))
                            <a href="{{ route('parameter-uji.cetak-control-chart', ['parameter_uji' => request()->route('parameter_uji') ?? $selectedParameter->parameter_uji_id, 'tanggal_mulai' => request('tanggal_mulai'), 'tanggal_akhir' => request('tanggal_akhir'), 'tab' => request('tab')]) }}" class="btn btn-danger shadow-sm" target="_blank">
                                <i class="fas fa-file-pdf me-1"></i> Cetak
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(isset($chartData) && $selectedParameter)
    <div class="row g-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="fw-bold"><i class="fas fa-table me-1"></i> Raw Data Logger: {{ $selectedParameter->nama_parameter }}</h6>
                </div>
                <div class="card-body">
                    <div class="cv-scroll-hint"><i class="fas fa-arrows-alt-h me-1"></i> Geser tabel ke samping untuk melihat kolom lainnya</div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center cv-table cv-table-raw">
                            <thead>
                                <tr>
                                    <th rowspan="2">Tanggal</th>
                                    <th rowspan="2">Uji Ke-</th>
                                    <th rowspan="2">Kode Sampel</th>
                                    <th colspan="7" class="th-dish">DISH 1 (Pengujian 1)</th>
                                    <th colspan="7" class="th-dish">DISH 2 (Pengujian 2)</th>
                                    <th rowspan="2" class="th-ts">TS %</th>
                                    <th rowspan="2" class="th-diff">Abs. Diff<br><small>(Tol: {{ $selectedParameter->toleransi_duplo ?? 50.0 }})</small></th>
                                    <th rowspan="2" class="th-avg">Avg (adb)</th>
                                    <th rowspan="2" class="th-im">IM %</th>
                                    <th rowspan="2" class="th-final">Avg (db)</th>
                                </tr>
                                <tr>
                                    <th class="th-manual">Vessel</th>
                                    <th class="th-manual">Call ID</th>
                                    <th class="th-manual">Massa (g)</th>
                                    <th class="th-manual">Primary</th>
                                    <th class="th-manual">Ee</th>
                                    <th class="th-manual">Titrant</th>
                                    <th class="th-auto">Final (adb)</th>
                                    <th class="th-manual">Vessel</th>
                                    <th class="th-manual">Call ID</th>
                                    <th class="th-manual">Massa (g)</th>
                                    <th class="th-manual">Primary</th>
                                    <th class="th-manual">Ee</th>
                                    <th class="th-manual">Titrant</th>
                                    <th class="th-auto">Final (adb)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hasilList as $index => $hasil)
                                    @php
                                        $dm = is_array($hasil->data_mentah) ? $hasil->data_mentah : json_decode($hasil->data_mentah, true);
                                    @endphp
                                    <tr>
                                        <td class="fw-bold">{{ $hasil->created_at->format('d/m/y') }}</td>
                                        <td class="fw-bold">{{ $index + 1 }}</td>
                                        <td>{{ $hasil->kegiatan ? $hasil->kegiatan->kode_sampel : '-' }}</td>
                                        <td>{{ $dm['Vessel_D1'] ?? '-' }}</td>
                                        <td>{{ $dm['Call_ID_D1'] ?? '-' }}</td>
                                        <td>{{ isset($dm['Massa_D1']) ? number_format($dm['Massa_D1'], 4) : '-' }}</td>
                                        <td>{{ isset($dm['Primary_D1']) ? number_format($dm['Primary_D1'], 2) : '-' }}</td>
                                        <td>{{ isset($dm['Ee_D1']) ? number_format($dm['Ee_D1'], 2) : '-' }}</td>
                                        <td>{{ isset($dm['Titrant_D1']) ? number_format($dm['Titrant_D1'], 1) : '-' }}</td>
                                        <td class="fw-bold bg-light">{{ isset($dm['Final_adb_D1']) ? number_format($dm['Final_adb_D1'], 2) : '-' }}</td>
                                        <td>{{ $dm['Vessel_D2'] ?? '-' }}</td>
                                        <td>{{ $dm['Call_ID_D2'] ?? '-' }}</td>
                                        <td>{{ isset($dm['Massa_D2']) ? number_format($dm['Massa_D2'], 4) : '-' }}</td>
                                        <td>{{ isset($dm['Primary_D2']) ? number_format($dm['Primary_D2'], 2) : '-' }}</td>
                                        <td>{{ isset($dm['Ee_D2']) ? number_format($dm['Ee_D2'], 2) : '-' }}</td>
                                        <td>{{ isset($dm['Titrant_D2']) ? number_format($dm['Titrant_D2'], 1) : '-' }}</td>
                                        <td class="fw-bold bg-light">{{ isset($dm['Final_adb_D2']) ? number_format($dm['Final_adb_D2'], 2) : '-' }}</td>

                                        <td>{{ isset($dm['Total Sulfur (TS)']) ? number_format($dm['Total Sulfur (TS)'], 2) : '-' }}</td>

                                        <td class="{{ isset($dm['Absolute_Diff']) && $dm['Absolute_Diff'] > ($selectedParameter->toleransi_duplo ?? 50) ? 'text-danger fw-bold' : 'fw-bold' }}">
                                            {{ isset($dm['Absolute_Diff']) ? number_format($dm['Absolute_Diff'], 2) : '-' }}
                                        </td>
                                        <td class="fw-bold">{{ isset($dm['Average_adb']) ? number_format($dm['Average_adb'], 2) : '-' }}</td>
                                        <td class="fw-bold text-info">{{ isset($dm['IM']) && is_numeric($dm['IM']) ? number_format($dm['IM'], 2) : '-' }}</td>

                                        <td class="fw-bold bg-light">
                                            @if($hasil->status_berketerimaan === 'pending')
                                                <span class="text-warning">PENDING (IM/TS)</span>
                                            @elseif($hasil->status_berketerimaan === 'gagal_duplo')
                                                <span class="text-danger"><del>{{ number_format($hasil->nilai_hasil, 2) }}</del><br><small>RE-TEST</small></span>
                                            @else
                                                <span class="text-success">{{ number_format($hasil->nilai_hasil, 2) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="21" class="text-muted py-3">Belum ada data pengujian CV.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($chartData))
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="fw-bold"><i class="fas fa-list-check me-1"></i> Tabel Control Chart: Inhouse {{ $selectedParameter->nama_parameter }} (%db)</h6>
                </div>
                <div class="card-body">
                    <div class="cv-scroll-hint"><i class="fas fa-arrows-alt-h me-1"></i> Geser tabel ke samping untuk melihat kolom lainnya</div>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-hover text-center cv-table cv-table-eval">
                            <thead>
                                <tr>
                                    <th rowspan="2">Tanggal</th>
                                    <th colspan="6" class="th-rule">Evaluasi Aturan Westgard</th>
                                    <th rowspan="2" class="th-status">Terima / Tolak</th>
                                    <th rowspan="2" class="th-note">Alasan</th>
                                    <th rowspan="2">Aksi</th>
                                </tr>
                                <tr>
                                    <th class="th-rule-sub">1 (2s)</th>
                                    <th class="th-rule-sub">1 (3s)</th>
                                    <th class="th-rule-sub">2 (2s)</th>
                                    <th class="th-rule-sub">R (4s)</th>
                                    <th class="th-rule-sub">4 (1s)</th>
                                    <th class="th-rule-sub">10x</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $validIdx = 0; @endphp
                                @foreach($hasilList as $hasil)
                                    @if($hasil->status_berketerimaan !== 'gagal_duplo' && $hasil->status_berketerimaan !== 'pending')
                                        @php
                                            $eval = $chartData['evaluations'][$validIdx] ?? null;
                                            $validIdx++;
                                        @endphp
                                        @if($eval)
                                        <tr>
                                            <td class="fw-bold text-nowrap">{{ $hasil->created_at->format('d/m/Y') }}</td>
                                            <td class="{{ $eval['1_2s'] == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['1_2s'] }}</td>
                                            <td class="{{ $eval['1_3s'] == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['1_3s'] }}</td>
                                            <td class="{{ $eval['2_2s'] == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['2_2s'] }}</td>
                                            <td class="{{ $eval['R_4s'] == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['R_4s'] }}</td>
                                            <td class="{{ $eval['4_1s'] == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['4_1s'] }}</td>
                                            <td class="{{ $eval['10x']  == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['10x'] }}</td>
                                            <td class="fw-bold {{ $eval['status'] == 'Tolak' ? 'text-danger' : 'text-success' }}">
                                                {{ $eval['status'] }}
                                            </td>
                                            <td class="text-start cv-alasan {{ $eval['status'] == 'Tolak' ? 'text-danger' : '' }}">{{ $eval['alasan'] != '-' ? $eval['alasan'] : '' }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary btn-icon" data-bs-toggle="modal" data-bs-target="#modalOverrideGlobal"
                                                    data-id="{{ $eval['hasil_uji_id'] }}"
                                                    data-status="{{ $eval['status'] }}"
                                                    data-alasan="{{ $eval['alasan'] }}"
                                                    title="Edit Evaluasi" aria-label="Edit Evaluasi">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endif
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h6 class="fw-bold text-center mt-4 mb-2" style="font-size: 0.82rem; color: #1b3152;"><i class="fas fa-chart-line me-1"></i> Grafik Control Chart (Levy-Jennings) - {{ $selectedParameter->nama_parameter }}</h6>
                    <div class="cv-chart-wrap">
                        <canvas id="controlChartCanvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>

@include('parameter-uji.partials.override-modal')

@if(isset($chartData))
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('controlChartCanvas').getContext('2d');
        const data = @json($chartData);

        const labels = data.labels;
        const values = data.values;

        if (typeof ChartDataLabels !== 'undefined') {
            Chart.register(ChartDataLabels);
        }

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Control',
                        data: values,
                        borderColor: '#000000',
                        backgroundColor: '#000000',
                        pointBackgroundColor: '#000000',
                        pointBorderColor: '#000000',
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        fill: false,
                        tension: 0.3,
                        borderWidth: 1.5,
                        zIndex: 10,
                        datalabels: {
                            align: 'top',
                            anchor: 'end',
                            offset: 3,
                            color: '#000',
                            font: { size: 9, weight: 'bold' },
                            formatter: function(value) {
                                return parseFloat(value).toFixed(2);
                            }
                        }
                    },
                    { label: 'UCL (' + ({{ $selectedParameter->ucl ?? 0 }}).toFixed(2) + ')', data: Array(labels.length).fill({{ $selectedParameter->ucl ?? 0 }}), borderColor: '#ff0000', borderWidth: 1.5, borderDash: [4, 4], pointRadius: 0, fill: false, datalabels: {display: false} },
                    { label: 'UWL (' + ({{ $selectedParameter->uwl_atas ?? 0 }}).toFixed(2) + ')', data: Array(labels.length).fill({{ $selectedParameter->uwl_atas ?? 0 }}), borderColor: '#ff9900', borderWidth: 1.5, borderDash: [4, 4], pointRadius: 0, fill: false, datalabels: {display: false} },
                    { label: 'µ+1σ (' + ({{ ($selectedParameter->mean ?? 0) + ($selectedParameter->sd ?? 0) }}).toFixed(2) + ')', data: Array(labels.length).fill({{ ($selectedParameter->mean ?? 0) + ($selectedParameter->sd ?? 0) }}), borderColor: '#facc15', borderWidth: 1.5, borderDash: [4, 4], pointRadius: 0, fill: false, datalabels: {display: false} },
                    { label: 'µ (' + ({{ $selectedParameter->mean ?? 0 }}).toFixed(2) + ')', data: Array(labels.length).fill({{ $selectedParameter->mean ?? 0 }}), borderColor: '#00ff00', borderWidth: 1.5, borderDash: [4, 4], pointRadius: 0, fill: false, datalabels: {display: false} },
                    { label: 'µ-1σ (' + ({{ ($selectedParameter->mean ?? 0) - ($selectedParameter->sd ?? 0) }}).toFixed(2) + ')', data: Array(labels.length).fill({{ ($selectedParameter->mean ?? 0) - ($selectedParameter->sd ?? 0) }}), borderColor: '#facc15', borderWidth: 1.5, borderDash: [4, 4], pointRadius: 0, fill: false, datalabels: {display: false} },
                    { label: 'LWL (' + ({{ $selectedParameter->uwl_bawah ?? 0 }}).toFixed(2) + ')', data: Array(labels.length).fill({{ $selectedParameter->uwl_bawah ?? 0 }}), borderColor: '#ff9900', borderWidth: 1.5, borderDash: [4, 4], pointRadius: 0, fill: false, datalabels: {display: false} },
                    { label: 'LCL (' + ({{ $selectedParameter->lcl ?? 0 }}).toFixed(2) + ')', data: Array(labels.length).fill({{ $selectedParameter->lcl ?? 0 }}), borderColor: '#ff0000', borderWidth: 1.5, borderDash: [4, 4], pointRadius: 0, fill: false, datalabels: {display: false} }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 14, font: { size: 10 } }
                    },
                    tooltip: {
                        callbacks: {
                            afterLabel: function(context) {
                                const idx = context.dataIndex;
                                const aturan = data.kodeAturan[idx];
                                return aturan ? 'Pelanggaran: ' + aturan : '';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        title: { display: true, text: 'Calorific Value (cal/g, db)', font: { size: 10 } },
                        ticks: { font: { size: 10 } },
                        suggestedMax: data.stats.plus3sd + data.stats.sd,
                        suggestedMin: data.stats.minus3sd - data.stats.sd,
                    },
                    x: {
                        title: { display: true, text: 'Tanggal Pengujian', font: { size: 10 } },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    });
</script>
@endpush
@endif