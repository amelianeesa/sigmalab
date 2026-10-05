@include('parameter-uji.partials.limit-eval-alert')

<style>
    .aft-page {
        padding: 4px 20px !important;
        font-size: 0.82rem;
    }

    .aft-page .breadcrumb {
        margin: 0 0 6px 0 !important;
        padding: 0 !important;
        font-size: 0.75rem !important;
        background: transparent !important;
        flex-wrap: wrap;
    }
    .aft-page .breadcrumb .breadcrumb-item,
    .aft-page .breadcrumb .breadcrumb-item a {
        font-size: 0.75rem !important;
        font-weight: 500;
    }
    .aft-page .breadcrumb .breadcrumb-item.active {
        color: #000 !important;
        font-weight: 700;
    }

    .aft-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0;
        color: #1b3152;
    }

    .aft-page .card-body { padding: 10px !important; }
    .aft-page .card-header {
        padding: 8px 12px !important;
        background: #fff;
    }
    .aft-page .card-header h6 {
        font-size: 0.85rem !important;
        margin: 0;
    }

    .aft-page .form-label {
        font-size: 0.75rem;
        margin-bottom: 2px;
        font-weight: 600;
    }
    .aft-page .form-control,
    .aft-page .form-select {
        font-size: 0.8rem;
        padding-top: 0.3rem;
        padding-bottom: 0.3rem;
    }
    .aft-page .btn { font-size: 0.78rem; }

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

    .aft-page .alert {
        font-size: 0.8rem;
        padding: 6px 12px;
        margin-bottom: 10px;
    }

    .aft-page .table th,
    .aft-page .table td {
        padding: 6px 8px !important;
        vertical-align: middle !important;
        font-size: 0.72rem !important;
    }
    .aft-page .table thead th {
        font-size: 0.72rem !important;
        background-color: #1b3152 !important;
        color: #fff !important;
        border-color: #fff !important;
        white-space: nowrap;
    }

    .aft-page .table thead th.th-manual { background-color: #2f5d9e !important; color: #fff !important; }
    .aft-page .table thead th.th-auto   { background-color: #6b7a90 !important; color: #fff !important; }
    .aft-page .table thead th.th-diff   { background-color: #d9a21b !important; color: #1b1b1b !important; }
    .aft-page .table thead th.th-avg    { background-color: #2a8fa6 !important; color: #fff !important; }
    .aft-page .table thead th.th-final  { background-color: #2b7a4b !important; color: #fff !important; }

    .aft-page .badge { font-size: 0.65rem; }

    .aft-table-raw { min-width: 1000px; }
    .aft-table-raw td { white-space: nowrap; }
    .aft-table-eval { min-width: 560px; }

    .aft-scroll-hint {
        display: none;
        font-size: 0.68rem;
        color: #6c757d;
        margin-bottom: 0.4rem;
    }

    .aft-chart { height: 250px; width: 100%; }

    @media (max-width: 767.98px) {
        .aft-page { padding: 4px 10px !important; }
        .aft-page .breadcrumb,
        .aft-page .breadcrumb .breadcrumb-item,
        .aft-page .breadcrumb .breadcrumb-item a {
            font-size: 0.72rem !important;
        }
        .aft-title { font-size: 1rem; }
        .aft-scroll-hint { display: block; }
        .aft-chart { height: 220px; }
        .aft-page .table th,
        .aft-page .table td {
            padding: 5px 6px !important;
            font-size: 0.68rem !important;
        }
    }
</style>

<div class="container-fluid aft-page pb-4">

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="aft-title">Inhouse Control - {{ $selectedParameter->nama_parameter }}</h5>
    </div>

    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-header border-bottom">
            <h6 class="fw-bold" style="color: #1b3152;"><i class="fas fa-filter me-1"></i> Filter Data</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('parameter-uji.show', $selectedParameter->parameter_uji_id) }}" method="GET" class="row g-2 align-items-end">
                <input type="hidden" name="tab" value="{{ $jenisGrafik }}">
                <div class="col-12 col-md-4">
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
                <div class="col-6 col-md-2">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control form-control-sm" value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" class="form-control form-control-sm" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-12 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-corporate-blue btn-sm flex-grow-1 shadow-sm fw-semibold">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    @if(request('parameter_uji_id'))
                        <a href="{{ route('parameter-uji.cetak-control-chart', ['parameter_uji' => request()->route('parameter_uji') ?? $selectedParameter->parameter_uji_id, 'tanggal_mulai' => request('tanggal_mulai'), 'tanggal_akhir' => request('tanggal_akhir'), 'tab' => request('tab')]) }}" class="btn btn-danger btn-sm flex-grow-1 shadow-sm fw-semibold" target="_blank">
                            <i class="fas fa-file-pdf me-1"></i> Cetak
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if(isset($chartData) && $selectedParameter)
        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-header border-bottom">
                <h6 class="fw-bold" style="color: #1b3152;"><i class="fas fa-table me-1"></i> Raw Data Logger: {{ $selectedParameter->nama_parameter }}</h6>
            </div>
            <div class="card-body">
                <div class="aft-scroll-hint"><i class="fas fa-arrows-alt-h me-1"></i> Geser tabel ke samping untuk melihat kolom lainnya</div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 text-center align-middle aft-table-raw">
                        <thead>
                            <tr>
                                <th rowspan="2">Tanggal</th>
                                <th rowspan="2">Uji Ke-</th>
                                <th rowspan="2">Kode Sampel</th>
                                <th rowspan="2">Atmosfer</th>
                                <th colspan="4" class="th-manual">DISH 1 (Pengujian 1)</th>
                                <th colspan="4" class="th-manual">DISH 2 (Pengujian 2)</th>
                                <th colspan="4" class="th-diff">Absolute Difference (Tol: {{ $selectedParameter->toleransi_duplo ?? 50 }})</th>
                                <th colspan="4" class="th-avg">Average (℃)</th>
                                <th rowspan="2" class="th-final">Status Duplo</th>
                            </tr>
                            <tr>
                                <th class="th-manual">IDT</th> <th class="th-manual">ST</th> <th class="th-manual">HT</th> <th class="th-manual">FT</th>
                                <th class="th-manual">IDT</th> <th class="th-manual">ST</th> <th class="th-manual">HT</th> <th class="th-manual">FT</th>
                                <th class="th-diff">IDT</th> <th class="th-diff">ST</th> <th class="th-diff">HT</th> <th class="th-diff">FT</th>
                                <th class="th-avg">IDT</th> <th class="th-avg">ST</th> <th class="th-avg">HT</th> <th class="th-avg">FT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hasilList as $index => $hasil)
                                @php
                                    $dm = is_array($hasil->data_mentah) ? $hasil->data_mentah : json_decode($hasil->data_mentah, true);
                                    $tol = $selectedParameter->toleransi_duplo ?? 50.0;
                                @endphp
                                <tr>
                                    <td class="fw-bold">{{ $hasil->created_at->format('d/m/y') }}</td>
                                    <td class="fw-bold">{{ $index + 1 }}</td>
                                    <td>{{ $hasil->kegiatan ? $hasil->kegiatan->kode_sampel : '-' }}</td>
                                    <td>{{ $dm['Atmosphere'] ?? '-' }}</td>

                                    <td>{{ isset($dm['IDT_D1']) ? number_format($dm['IDT_D1'], 1) : '-' }}</td>
                                    <td>{{ isset($dm['ST_D1']) ? number_format($dm['ST_D1'], 1) : '-' }}</td>
                                    <td>{{ isset($dm['HT_D1']) ? number_format($dm['HT_D1'], 1) : '-' }}</td>
                                    <td>{{ isset($dm['FT_D1']) ? number_format($dm['FT_D1'], 1) : '-' }}</td>

                                    <td>{{ isset($dm['IDT_D2']) ? number_format($dm['IDT_D2'], 1) : '-' }}</td>
                                    <td>{{ isset($dm['ST_D2']) ? number_format($dm['ST_D2'], 1) : '-' }}</td>
                                    <td>{{ isset($dm['HT_D2']) ? number_format($dm['HT_D2'], 1) : '-' }}</td>
                                    <td>{{ isset($dm['FT_D2']) ? number_format($dm['FT_D2'], 1) : '-' }}</td>

                                    <td class="{{ isset($dm['Abs_IDT']) && $dm['Abs_IDT'] > $tol ? 'text-danger fw-bold' : '' }}">{{ isset($dm['Abs_IDT']) ? number_format($dm['Abs_IDT'], 1) : '-' }}</td>
                                    <td class="{{ isset($dm['Abs_ST']) && $dm['Abs_ST'] > $tol ? 'text-danger fw-bold' : '' }}">{{ isset($dm['Abs_ST']) ? number_format($dm['Abs_ST'], 1) : '-' }}</td>
                                    <td class="{{ isset($dm['Abs_HT']) && $dm['Abs_HT'] > $tol ? 'text-danger fw-bold' : '' }}">{{ isset($dm['Abs_HT']) ? number_format($dm['Abs_HT'], 1) : '-' }}</td>
                                    <td class="{{ isset($dm['Abs_FT']) && $dm['Abs_FT'] > $tol ? 'text-danger fw-bold' : '' }}">{{ isset($dm['Abs_FT']) ? number_format($dm['Abs_FT'], 1) : '-' }}</td>

                                    <td class="fw-bold bg-light">{{ isset($dm['Avg_IDT']) ? number_format($dm['Avg_IDT'], 1) : '-' }}</td>
                                    <td class="fw-bold bg-light">{{ isset($dm['Avg_ST']) ? number_format($dm['Avg_ST'], 1) : '-' }}</td>
                                    <td class="fw-bold bg-light">{{ isset($dm['Avg_HT']) ? number_format($dm['Avg_HT'], 1) : '-' }}</td>
                                    <td class="fw-bold bg-light">{{ isset($dm['Avg_FT']) ? number_format($dm['Avg_FT'], 1) : '-' }}</td>

                                    <td class="fw-bold">
                                        @if($hasil->status_berketerimaan === 'gagal_duplo')
                                            <span class="badge bg-danger">NO</span>
                                        @else
                                            <span class="badge bg-success">YES</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="22" class="text-center text-muted py-3">Belum ada data pengujian AFT.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if(isset($chartData['aft']))
            <h6 class="fw-bold border-bottom pb-2 mb-3" style="color: #1b3152; font-size: 0.9rem;"><i class="fas fa-chart-line me-1"></i> Control Charts (IDT, ST, HT, FT)</h6>
            <div class="row g-3 mb-3">
                @php $subParams = ['IDT', 'ST', 'HT', 'FT']; @endphp
                @foreach($subParams as $sub)
                <div class="col-12 col-xl-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header border-bottom">
                            <h6 class="fw-bold text-center" style="color: #1b3152;">Grafik Control Chart: {{ $sub }} (℃)</h6>
                        </div>
                        <div class="card-body">
                            <div class="aft-scroll-hint"><i class="fas fa-arrows-alt-h me-1"></i> Geser tabel ke samping untuk melihat kolom lainnya</div>
                            <div class="table-responsive mb-3" style="max-height: 250px; overflow-y: auto;">
                                <table class="table table-bordered table-striped table-hover mb-0 text-center align-middle aft-table-eval">
                                    <thead class="sticky-top">
                                        <tr>
                                            <th rowspan="2">Tanggal</th>
                                            <th colspan="6">Evaluasi Aturan Westgard</th>
                                            <th rowspan="2">Terima / Tolak</th>
                                            <th rowspan="2">Alasan</th>
                                            <th rowspan="2">Aksi</th>
                                        </tr>
                                        <tr>
                                            <th>1(2s)</th>
                                            <th>1(3s)</th>
                                            <th>2(2s)</th>
                                            <th>R(4s)</th>
                                            <th>4(1s)</th>
                                            <th>10x</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $validIdx = 0; @endphp
                                        @foreach($hasilList as $h)
                                            @if($h->status_berketerimaan !== 'gagal_duplo')
                                                @php
                                                    $eval = $chartData['aft'][$sub]['evaluations'][$validIdx] ?? null;
                                                    $validIdx++;
                                                @endphp
                                                @if($eval)
                                                <tr>
                                                    <td class="fw-bold text-nowrap">{{ $h->created_at->format('d/m/y') }}</td>
                                                    <td class="{{ $eval['1_2s'] == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['1_2s'] }}</td>
                                                    <td class="{{ $eval['1_3s'] == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['1_3s'] }}</td>
                                                    <td class="{{ $eval['2_2s'] == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['2_2s'] }}</td>
                                                    <td class="{{ $eval['R_4s'] == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['R_4s'] }}</td>
                                                    <td class="{{ $eval['4_1s'] == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['4_1s'] }}</td>
                                                    <td class="{{ $eval['10x']  == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['10x'] }}</td>
                                                    <td class="fw-bold {{ $eval['status'] == 'Tolak' ? 'text-danger' : 'text-success' }}">
                                                        {{ $eval['status'] }}
                                                    </td>
                                                    <td class="text-start {{ $eval['status'] == 'Tolak' ? 'text-danger' : '' }}">{{ $eval['alasan'] != '-' ? $eval['alasan'] : '' }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" data-bs-toggle="modal" data-bs-target="#modalOverrideGlobal"
                                                            data-id="{{ $eval['hasil_uji_id'] }}"
                                                            data-status="{{ $eval['status'] }}"
                                                            data-alasan="{{ $eval['alasan'] }}"
                                                            title="Edit Evaluasi">
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
                            <div class="aft-chart">
                                <canvas id="canvas_{{ $sub }}"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    @endif
</div>

@include('parameter-uji.partials.override-modal')

@if(isset($chartData['aft']))
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const labels = @json($chartData['labels']);
        const aftData = @json($chartData['aft']);

        if (typeof ChartDataLabels !== 'undefined') {
            Chart.register(ChartDataLabels);
        }

        ['IDT', 'ST', 'HT', 'FT'].forEach(function(sub) {
            if(!aftData[sub]) return;
            const ctx = document.getElementById('canvas_' + sub).getContext('2d');
            const d = aftData[sub];
            const values = d.values;

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
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: false,
                            tension: 0.3,
                            borderWidth: 1.5,
                            zIndex: 10,
                            datalabels: {
                                align: 'top',
                                anchor: 'end',
                                offset: 4,
                                color: '#000',
                                font: { size: 10, weight: 'bold' },
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
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            title: { display: true, text: 'Suhu (℃)', font: { size: 11 } },
                            ticks: { font: { size: 10 } },
                            suggestedMax: d.stats.plus3sd + d.stats.sd,
                            suggestedMin: d.stats.minus3sd - d.stats.sd,
                        },
                        x: { display: false }
                    }
                }
            });
        });
    });
</script>
@endpush
@endif