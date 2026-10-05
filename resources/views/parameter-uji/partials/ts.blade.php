@include('parameter-uji.partials.limit-eval-alert')

<style>
    .ts-page {
        padding: 4px 20px !important;
        font-size: 0.82rem;
    }

    /* Breadcrumb */
    .ts-page .breadcrumb {
        margin: 0 0 6px 0 !important;
        padding: 0 !important;
        font-size: 0.75rem !important;
        background: transparent !important;
        flex-wrap: wrap;
    }
    .ts-page .breadcrumb .breadcrumb-item,
    .ts-page .breadcrumb .breadcrumb-item a {
        font-size: 0.75rem !important;
        font-weight: 500;
    }
    .ts-page .breadcrumb .breadcrumb-item.active {
        color: #000 !important;
        font-weight: 700;
    }

    /* Judul */
    .ts-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0;
        color: #1b3152;
    }

    /* Card */
    .ts-page .card-body { padding: 10px !important; }
    .ts-page .card-header {
        padding: 8px 12px !important;
        background: #fff;
    }
    .ts-page .card-header h6 {
        font-size: 0.85rem !important;
        margin: 0;
    }

    /* Form */
    .ts-page .form-label {
        font-size: 0.75rem;
        margin-bottom: 2px;
        font-weight: 600;
    }
    .ts-page .form-control,
    .ts-page .form-select {
        font-size: 0.8rem;
        padding-top: 0.3rem;
        padding-bottom: 0.3rem;
    }
    .ts-page .btn { font-size: 0.78rem; }

    /* Tombol corporate */
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

    /* Alert */
    .ts-page .alert {
        font-size: 0.8rem;
        padding: 6px 12px;
        margin-bottom: 10px;
    }

    /* Tabel */
    .ts-page .table th,
    .ts-page .table td {
        padding: 6px 8px !important;
        vertical-align: middle !important;
        font-size: 0.72rem !important;
    }
    .ts-page .table thead th {
        font-size: 0.72rem !important;
        background-color: #1b3152 !important;
        color: #fff !important;
        border-color: #fff !important;
    }
    .ts-page .table thead th small {
        font-size: 0.64rem !important;
        font-weight: 400;
    }

    /* Warna kelompok kolom (kalem) */
    .ts-page .table thead th.th-manual { background-color: #2f5d9e !important; color: #fff !important; }
    .ts-page .table thead th.th-auto   { background-color: #6b7a90 !important; color: #fff !important; }
    .ts-page .table thead th.th-diff   { background-color: #d9a21b !important; color: #1b1b1b !important; }
    .ts-page .table thead th.th-avg    { background-color: #2a8fa6 !important; color: #fff !important; }
    .ts-page .table thead th.th-final  { background-color: #2b7a4b !important; color: #fff !important; }

    .ts-page .badge { font-size: 0.65rem; }

    /* Grafik */
    .ts-chart { height: 320px; width: 100%; }

    @media (max-width: 767.98px) {
        .ts-page { padding: 4px 10px !important; }
        .ts-page .breadcrumb,
        .ts-page .breadcrumb .breadcrumb-item,
        .ts-page .breadcrumb .breadcrumb-item a {
            font-size: 0.72rem !important;
        }
        .ts-title { font-size: 1rem; }
        .ts-chart { height: 240px; }
        .ts-page .table th,
        .ts-page .table td {
            padding: 5px 6px !important;
            font-size: 0.68rem !important;
        }
    }
</style>

<div class="container-fluid ts-page pb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
            <li class="breadcrumb-item"><a href="{{ route('parameter-uji.index') }}" class="text-decoration-none">Parameter Uji</a></li>
            @if($selectedParameter)
                <li class="breadcrumb-item active" aria-current="page">{{ $selectedParameter->nama_parameter }}</li>
            @endif
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="ts-title">Inhouse Control - {{ $selectedParameter->nama_parameter }}</h5>
    </div>

    <!-- Filter Card -->
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-header border-bottom">
            <h6 class="fw-bold" style="color: #1b3152;"><i class="fas fa-filter me-1"></i> Filter Data</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('parameter-uji.show', $selectedParameter->parameter_uji_id) }}" method="GET" class="row g-2 align-items-end">
                <input type="hidden" name="tab" value="{{ $jenisGrafik ?? 'in_house' }}">

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
                        <i class="fas fa-filter me-1"></i> Tampilkan
                    </button>
                    @if(request('parameter_uji_id'))
                        <a href="{{ route('parameter-uji.cetak-control-chart', [
                            'parameter_uji' => request()->route('parameter_uji') ?? $selectedParameter->parameter_uji_id,
                            'tanggal_mulai' => request('tanggal_mulai'),
                            'tanggal_akhir' => request('tanggal_akhir'),
                            'tab' => request('tab')
                        ]) }}" class="btn btn-danger btn-sm flex-grow-1 shadow-sm fw-semibold" target="_blank">
                            <i class="fas fa-file-pdf me-1"></i> Cetak
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if(isset($chartData) && $selectedParameter)

        <!-- Raw Data Logger -->
        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-header border-bottom">
                <h6 class="fw-bold" style="color: #1b3152;"><i class="fas fa-table me-1"></i> Raw Data Logger: {{ $selectedParameter->nama_parameter }}</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered table-hover mb-0 text-center align-middle">
                        <thead class="sticky-top">
                            <tr>
                                <th rowspan="2" class="align-middle">Tanggal</th>
                                <th rowspan="2" class="align-middle">Pengujian Ke-</th>
                                <th rowspan="2" class="align-middle">Kode Sampel</th>
                                <th colspan="2">DISH 1 (Pengujian 1)</th>
                                <th colspan="2">DISH 2 (Pengujian 2)</th>
                                <th rowspan="2" class="align-middle th-diff">Abs. Diff<br><small>(|TS1 - TS2|)</small></th>
                                <th rowspan="2" class="align-middle th-avg">Avg %<br>(adb)</th>
                                <th rowspan="2" class="align-middle th-avg">IM %</th>
                                <th rowspan="2" class="align-middle th-final">Avg %<br>(db)</th>
                                <th rowspan="2" class="align-middle">Status Duplo<br><small>(Tol: {{ $selectedParameter->toleransi_duplo ?? 0.05 }})</small></th>
                            </tr>
                            <tr>
                                <th class="th-manual">Massa (g)</th>
                                <th class="th-auto">TS % (adb)</th>
                                <th class="th-manual">Massa (g)</th>
                                <th class="th-auto">TS % (adb)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hasilList as $index => $hasil)
                                @php
                                    $dm = is_array($hasil->data_mentah) ? $hasil->data_mentah : json_decode($hasil->data_mentah, true);
                                @endphp
                                <tr>
                                    <td class="fw-bold">{{ $hasil->created_at->format('d/m/Y') }}</td>
                                    <td class="fw-bold">{{ $index + 1 }}</td>
                                    <td>{{ $hasil->kegiatan ? $hasil->kegiatan->kode_sampel : '-' }}</td>
                                    <td>{{ isset($dm['Massa_D1']) ? number_format($dm['Massa_D1'], 4) : '-' }}</td>
                                    <td class="bg-light">{{ isset($dm['TS_adb_D1']) ? number_format($dm['TS_adb_D1'], 4) : '-' }}</td>
                                    <td>{{ isset($dm['Massa_D2']) ? number_format($dm['Massa_D2'], 4) : '-' }}</td>
                                    <td class="bg-light">{{ isset($dm['TS_adb_D2']) ? number_format($dm['TS_adb_D2'], 4) : '-' }}</td>

                                    <td class="fw-bold">{{ isset($dm['Absolute_Diff']) ? number_format($dm['Absolute_Diff'], 4) : '-' }}</td>
                                    <td>{{ isset($dm['Average_adb']) ? number_format($dm['Average_adb'], 4) : '-' }}</td>
                                    <td>{{ isset($dm['IM']) && is_numeric($dm['IM']) ? number_format($dm['IM'], 4) : '-' }}</td>

                                    <td class="fw-bold">
                                        @if($hasil->status_berketerimaan === 'pending')
                                            <span class="text-warning">PENDING</span>
                                        @elseif($hasil->status_berketerimaan === 'gagal_duplo')
                                            <span class="text-danger"><del>{{ number_format($hasil->nilai_hasil, 4) }}</del></span>
                                        @else
                                            <span class="text-success">{{ number_format($hasil->nilai_hasil, 4) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hasil->status_berketerimaan === 'pending')
                                            <span class="badge bg-warning text-dark">Tunggu IM</span>
                                        @elseif($hasil->status_berketerimaan === 'gagal_duplo')
                                            <span class="badge bg-danger">NO</span>
                                        @else
                                            <span class="badge bg-success">YES</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center text-muted py-3">Belum ada data pengujian TS.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tabel Evaluasi + Grafik -->
        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-header border-bottom">
                <h6 class="fw-bold" style="color: #1b3152;"><i class="fas fa-list-check me-1"></i> Tabel Control Chart: Inhouse {{ $selectedParameter->nama_parameter }} (%db)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive mb-3" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered table-striped mb-0 text-center align-middle">
                        <thead class="sticky-top">
                            <tr>
                                <th rowspan="2">Tanggal</th>
                                <th colspan="6">Evaluasi Aturan Westgard</th>
                                <th rowspan="2">Terima / Tolak</th>
                                <th rowspan="2">Alasan</th>
                                <th rowspan="2">Aksi</th>
                            </tr>
                            <tr>
                                <th>1 (2s)</th>
                                <th>1 (3s)</th>
                                <th>2 (2s)</th>
                                <th>R (4s)</th>
                                <th>4 (1s)</th>
                                <th>10x</th>
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
                                        <td class="fw-bold">{{ $hasil->created_at->format('d/m/Y') }}</td>
                                        <td class="{{ $eval['1_2s'] == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['1_2s'] }}</td>
                                        <td class="{{ $eval['1_3s'] == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['1_3s'] }}</td>
                                        <td class="{{ $eval['2_2s'] == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['2_2s'] }}</td>
                                        <td class="{{ $eval['R_4s'] == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['R_4s'] }}</td>
                                        <td class="{{ $eval['4_1s'] == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['4_1s'] }}</td>
                                        <td class="{{ $eval['10x'] == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['10x'] }}</td>
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

                <h6 class="fw-bold text-center mt-3 mb-2" style="font-size: 0.85rem; color: #1b3152;">
                    Grafik Control Chart (Levy-Jennings) - {{ $selectedParameter->nama_parameter }}
                </h6>
                <div class="ts-chart">
                    <canvas id="controlChartCanvas"></canvas>
                </div>
            </div>
        </div>
    @endif

    @include('parameter-uji.partials.override-modal')
</div>

@if(isset($chartData))
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById('controlChartCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');

        const data = @json($chartData);
        const labels = data.labels;
        const values = data.values;

        const ucl    = {{ $selectedParameter->ucl ?? 0 }};
        const uwl    = {{ $selectedParameter->uwl_atas ?? 0 }};
        const mean   = {{ $selectedParameter->mean ?? 0 }};
        const sd     = {{ $selectedParameter->sd ?? 0 }};
        const lwl    = {{ $selectedParameter->uwl_bawah ?? 0 }};
        const lcl    = {{ $selectedParameter->lcl ?? 0 }};
        const plus1  = mean + sd;
        const minus1 = mean - sd;

        if (typeof ChartDataLabels !== 'undefined') {
            Chart.register(ChartDataLabels);
        }

        const makeLine = (label, value, color) => ({
            label: label + ' (' + Number(value).toFixed(2) + ')',
            data: Array(labels.length).fill(value),
            borderColor: color,
            borderWidth: 1.5,
            borderDash: [4, 4],
            pointRadius: 0,
            fill: false,
            datalabels: { display: false }
        });

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
                    makeLine('UCL', ucl, '#ff0000'),
                    makeLine('UWL', uwl, '#ff9900'),
                    makeLine('µ+1σ', plus1, '#facc15'),
                    makeLine('µ', mean, '#00ff00'),
                    makeLine('µ-1σ', minus1, '#facc15'),
                    makeLine('LWL', lwl, '#ff9900'),
                    makeLine('LCL', lcl, '#ff0000')
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            usePointStyle: true,
                            font: { size: 10 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            afterLabel: function(context) {
                                const aturan = data.kodeAturan ? data.kodeAturan[context.dataIndex] : null;
                                return aturan ? 'Pelanggaran: ' + aturan : '';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        suggestedMax: data.stats.plus3sd + data.stats.sd,
                        suggestedMin: data.stats.minus3sd - data.stats.sd,
                        ticks: { font: { size: 10 } },
                        title: { display: true, text: 'Persentase (%db)', font: { size: 11 } }
                    },
                    x: {
                        ticks: { font: { size: 10 } },
                        title: { display: true, text: 'Tanggal Pengujian', font: { size: 11 } }
                    }
                }
            }
        });
    });
</script>
@endpush
@endif
