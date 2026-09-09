@include('parameter-uji.partials.limit-eval-alert')

<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
            <li class="breadcrumb-item"><a href="{{ route('parameter-uji.index') }}" class="text-decoration-none">Parameter Uji</a></li>
            @if($selectedParameter)
                <li class="breadcrumb-item active" aria-current="page">{{ $selectedParameter->nama_parameter }}</li>
            @endif
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ ($jenisGrafik ?? 'in_house') == 'crm' ? 'Sertifikat Pabrik (CRM)' : 'Inhouse Control' }}: Ash Content (ASH)</h1>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Data</h6>
        </div>
        <div class="card-body">
            <form action="{{ url()->
            <input type="hidden" name="tab" value="{{ $jenisGrafik }}">
current() }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="parameter_uji_id" class="form-label">Parameter Uji</label>
                    <select class="form-select" id="parameter_uji_id" name="parameter_uji_id" required>
                        <option value="">-- Pilih Parameter --</option>
                        @foreach($parameterList as $param)
                            <option value="{{ $param->parameter_uji_id }}" {{ request('parameter_uji_id') == $param->parameter_uji_id ? 'selected' : '' }}>
                                {{ $param->nama_parameter }} ({{ $param->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-md-3">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    @if($selectedParameter)
                        <a href="{{ route('parameter-uji.cetak-control-chart', ['parameter_uji' => request()->route('parameter_uji') ?? $selectedParameter->parameter_uji_id, 'tanggal_mulai' => request('tanggal_mulai'), 'tanggal_akhir' => request('tanggal_akhir'), 'tab' => request('tab')]), 'tanggal_mulai' => request('tanggal_mulai'), 'tanggal_akhir' => request('tanggal_akhir')]) }}" class="btn btn-danger flex-grow-1" target="_blank">
                            <i class="fas fa-file-pdf"></i> Cetak
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if(!isset($selectedParameter) || !$selectedParameter)
        <!-- No Parameter Selected -->
        <div class="alert alert-info shadow-sm" role="alert">
            <i class="fas fa-info-circle me-2"></i> Silakan pilih Parameter Uji terlebih dahulu untuk menampilkan Inhouse Control.
        </div>
    @else
        
        <!-- Raw Data Logger (Duplo) -->
        <div class="card shadow mb-4 border-left-primary">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Tabel Raw Data Logger (Duplo Testing)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered table-hover mb-0 text-center align-middle" style="font-size: 0.8rem;">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th rowspan="2" class="align-middle">Tanggal</th>
                                <th rowspan="2" class="align-middle">Pengujian Ke-</th>
                                <th rowspan="2" class="align-middle">Kode Sampel</th>
                                <th rowspan="2" class="align-middle">Dish No.</th>
                                <th colspan="3" class="bg-primary text-white">Input Data (Mass)</th>
                                <th colspan="2" class="bg-secondary text-white">Calculated (g)</th>
                                <th rowspan="2" class="align-middle">ASH%</th>
                                <th colspan="2" class="bg-warning text-dark">Absolute Diff.</th>
                                <th rowspan="2" class="align-middle bg-info text-white">Average %adb</th>
                                <th rowspan="2" class="align-middle bg-primary text-white">%db per Dish</th>
                                <th rowspan="2" class="align-middle bg-success text-white">Rata-rata %db</th>
                            </tr>
                            <tr>
                                <th class="bg-primary text-white" title="Massa cawan kosong">M1</th>
                                <th class="bg-primary text-white" title="Massa sampel">M2-M1</th>
                                <th class="bg-primary text-white" title="Massa cawan + Abu">M3</th>
                                <th class="bg-secondary text-white" title="M1 + M2M1">M2</th>
                                <th class="bg-secondary text-white" title="M3 - M1">M3-M1</th>
                                <th class="bg-warning text-dark">Value</th>
                                <th class="bg-warning text-dark">Eval (<= 0.20)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hasilList as $index => $hasil)
                                @php
                                    $dm = is_array($hasil->data_mentah) ? $hasil->data_mentah : json_decode($hasil->data_mentah, true);
                                    $M1_1 = $dm['M1_D1'] ?? '-';
                                    $M2M1_1 = $dm['M2M1_D1'] ?? '-';
                                    $M3_1 = $dm['M3_D1'] ?? '-';
                                    $M2_1 = $dm['M2_D1_Result'] ?? '-';
                                    $M3M1_1 = $dm['M3M1_D1_Result'] ?? '-';
                                    $ASH_1 = $dm['ASH_D1_Result'] ?? '-';
                                    $DB_1 = $dm['DB_D1_Result'] ?? '-';
                                    
                                    $M1_2 = $dm['M1_D2'] ?? '-';
                                    $M2M1_2 = $dm['M2M1_D2'] ?? '-';
                                    $M3_2 = $dm['M3_D2'] ?? '-';
                                    $M2_2 = $dm['M2_D2_Result'] ?? '-';
                                    $M3M1_2 = $dm['M3M1_D2_Result'] ?? '-';
                                    $ASH_2 = $dm['ASH_D2_Result'] ?? '-';
                                    $DB_2 = $dm['DB_D2_Result'] ?? '-';
                                    
                                    $absDiff = $dm['Absolute_Diff'] ?? '-';
                                    $avgAdb = $dm['Average_adb'] ?? '-';
                                    $isYes = is_numeric($absDiff) && $absDiff <= 0.20;
                                @endphp
                                
                                <!-- Dish 1 Row -->
                                <tr>
                                    <td rowspan="2" class="align-middle fw-bold">{{ $hasil->created_at->format('d/m/Y') }}</td>
                                    <td rowspan="2" class="align-middle fw-bold">{{ $index + 1 }}</td>
                                    <td rowspan="2" class="align-middle">{{ $hasil->kegiatan->kode_sampel ?? '-' }}</td>
                                    
                                    <td class="text-primary fw-bold">Dish 1</td>
                                    <td>{{ $M1_1 }}</td>
                                    <td class="fw-bold">{{ $M2M1_1 }}</td>
                                    <td>{{ $M3_1 }}</td>
                                    <td>{{ is_numeric($M2_1) ? number_format($M2_1, 4) : $M2_1 }}</td>
                                    <td>{{ is_numeric($M3M1_1) ? number_format($M3M1_1, 4) : $M3M1_1 }}</td>
                                    <td class="fw-bold">{{ is_numeric($ASH_1) ? number_format($ASH_1, 4) : $ASH_1 }}</td>
                                    
                                    <td rowspan="2" class="align-middle fw-bold">{{ is_numeric($absDiff) ? number_format($absDiff, 4) : $absDiff }}</td>
                                    <td rowspan="2" class="align-middle">
                                        @if(is_numeric($absDiff))
                                            @if($isYes)
                                                <span class="badge bg-success">YES</span>
                                            @else
                                                <span class="badge bg-danger">NO</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td rowspan="2" class="align-middle fw-bold fs-6">{{ is_numeric($avgAdb) ? number_format($avgAdb, 4) : $avgAdb }}</td>
                                    <td class="fw-bold text-primary">{{ is_numeric($DB_1) ? number_format($DB_1, 2) : $DB_1 }}</td>
                                    <td rowspan="2" class="align-middle fw-bold fs-6 {{ $hasil->status_berketerimaan === 'gagal_duplo' ? 'text-danger text-decoration-line-through' : 'text-success' }}">
                                        {{ number_format($hasil->nilai_hasil, 2) }}
                                        @if($hasil->status_berketerimaan === 'gagal_duplo')
                                            <br><small class="badge bg-danger mt-1">REJECTED</small>
                                        @elseif($hasil->status_berketerimaan === 'pending')
                                            <br><small class="badge bg-warning text-dark mt-1">Menunggu IM</small>
                                        @endif
                                    </td>
                                </tr>
                                <!-- Dish 2 Row -->
                                <tr>
                                    <td class="text-primary fw-bold">Dish 2</td>
                                    <td>{{ $M1_2 }}</td>
                                    <td class="fw-bold">{{ $M2M1_2 }}</td>
                                    <td>{{ $M3_2 }}</td>
                                    <td>{{ is_numeric($M2_2) ? number_format($M2_2, 4) : $M2_2 }}</td>
                                    <td>{{ is_numeric($M3M1_2) ? number_format($M3M1_2, 4) : $M3M1_2 }}</td>
                                    <td class="fw-bold">{{ is_numeric($ASH_2) ? number_format($ASH_2, 4) : $ASH_2 }}</td>
                                    <td class="fw-bold text-primary">{{ is_numeric($DB_2) ? number_format($DB_2, 2) : $DB_2 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center py-4">Belum ada data pengujian Ash Content.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if(isset($chartData) && count($chartData['labels']) > 0)
            
                          <div class="row">
                <!-- Limit Data Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-2">Batas Kendali (%db)</div>
                            
                            @php
                                $stats = $chartData['stats'];
                            @endphp

                            <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                <span class="text-muted small">UCL (+3&sigma;)</span>
                                <span class="font-weight-bold text-danger">{{ number_format($stats['plus3sd'], 4) }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                <span class="text-muted small">UWL (+2&sigma;)</span>
                                <span class="font-weight-bold text-warning">{{ number_format($stats['plus2sd'], 4) }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                <span class="text-muted small">+1&sigma;</span>
                                <span class="font-weight-bold text-success">{{ number_format($stats['plus1sd'], 4) }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-1 mb-1 bg-light">
                                <span class="text-dark small fw-bold">Mean (&mu;)</span>
                                <span class="font-weight-bold text-dark">{{ number_format($stats['mean'], 4) }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                <span class="text-muted small">-1&sigma;</span>
                                <span class="font-weight-bold text-success">{{ number_format($stats['minus1sd'], 4) }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                <span class="text-muted small">LWL (-2&sigma;)</span>
                                <span class="font-weight-bold text-warning">{{ number_format($stats['minus2sd'], 4) }}</span>
                            </div>
                            <div class="d-flex justify-content-between pb-1">
                                <span class="text-muted small">LCL (-3&sigma;)</span>
                                <span class="font-weight-bold text-danger">{{ number_format($stats['minus3sd'], 4) }}</span>
                            </div>
                            <div class="mt-3 text-center">
                                <small class="text-muted">SD (&sigma;) = {{ number_format($stats['sd'], 4) }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Card Control Chart Limit -->
                <div class="col-xl-9 col-md-12 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Limit Control Chart In-house ASH Content</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                                <table class="table table-bordered table-striped table-hover mb-0 text-center" style="font-size: 0.85rem;">
                                    <thead class="table-light sticky-top">
                                        @php 
                                            $validIndex = 1; 
                                            $fixedLcl = $selectedParameter->lcl ?? 0;
                                            $fixedLwl = $selectedParameter->uwl_bawah ?? 0;
                                            $fixedMean = $selectedParameter->mean ?? 0;
                                            $fixedUwl = $selectedParameter->uwl_atas ?? 0;
                                            $fixedUcl = $selectedParameter->ucl ?? 0;
                                            $fixedSd = $selectedParameter->sd ?? 0;
                                            $minus1Sd = $fixedMean - $fixedSd;
                                            $plus1Sd = $fixedMean + $fixedSd;
                                        @endphp
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Pengujian Ke-</th>
                                            <th class="text-danger" title="Lower Control Limit (-3SD)">LCL<br>({{ number_format($fixedLcl, 2) }})</th>
                                            <th class="text-warning text-dark" title="Lower Warning Limit (-2SD)">LWL<br>({{ number_format($fixedLwl, 2) }})</th>
                                            <th class="text-success" title="Mean - 1SD">µ-1σ<br>({{ number_format($minus1Sd, 2) }})</th>
                                            <th class="text-primary font-weight-bold">µ<br>({{ number_format($fixedMean, 2) }})</th>
                                            <th class="text-success" title="Mean + 1SD">µ+1σ<br>({{ number_format($plus1Sd, 2) }})</th>
                                            <th class="text-warning text-dark" title="Upper Warning Limit (+2SD)">UWL<br>({{ number_format($fixedUwl, 2) }})</th>
                                            <th class="text-danger" title="Upper Control Limit (+3SD)">UCL<br>({{ number_format($fixedUcl, 2) }})</th>
                                            <th class="bg-primary text-white">Control (%db)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($hasilList as $hasil)
                                        @if($hasil->status_berketerimaan !== 'gagal_duplo' && $hasil->status_berketerimaan !== 'pending')
                                        <tr>
                                            <td>{{ $hasil->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $validIndex++ }}</td>
                                            <td class="text-danger">{{ number_format($fixedLcl, 4) }}</td>
                                            <td class="text-warning text-dark">{{ number_format($fixedLwl, 4) }}</td>
                                            <td class="text-success">{{ number_format($minus1Sd, 4) }}</td>
                                            <td class="text-primary font-weight-bold">{{ number_format($fixedMean, 4) }}</td>
                                            <td class="text-success">{{ number_format($plus1Sd, 4) }}</td>
                                            <td class="text-warning text-dark">{{ number_format($fixedUwl, 4) }}</td>
                                            <td class="text-danger">{{ number_format($fixedUcl, 4) }}</td>
                                            <td class="font-weight-bold bg-info text-white">
                                                {{ number_format($hasil->nilai_hasil, 2) }}
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Control Chart Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow h-100">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Levy-Jennings Chart (%db)</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-area" style="height: 300px;">
                                <canvas id="levyJenningsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Evaluasi Control Chart (Westgard) -->
            <div class="card shadow mb-4 border-left-info">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Tabel Evaluasi Control Chart (Westgard Rules)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-bordered table-striped mb-0 text-center align-middle" style="font-size: 0.85rem;">
                            <thead class="table-dark sticky-top">
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
                                            <td class="{{ $eval['10x']  == 'Yes' ? 'text-danger fw-bold' : '' }}">{{ $eval['10x'] }}</td>
                                            <td class="fw-bold {{ $eval['status'] == 'Tolak' ? 'text-danger' : 'text-success' }}">
                                                {{ $eval['status'] }}
                                            </td>
                                            <td class="text-start {{ $eval['status'] == 'Tolak' ? 'text-danger' : '' }}">{{ $eval['alasan'] != '-' ? $eval['alasan'] : '' }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalOverrideGlobal" 
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
                </div>
            </div>

            @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('levyJenningsChart').getContext('2d');
                    
                    const chartData = @json($chartData);
                    const stats = chartData.stats;
                    
                    const labels = chartData.labels;
                    const dataPoints = chartData.values;
                    
                    // Arrays for horizontal lines
                    const lineMean = Array(labels.length).fill(stats.mean);
                    const lineUCL = Array(labels.length).fill(stats.plus3sd);
                    const lineLCL = Array(labels.length).fill(stats.minus3sd);
                    const lineUWL = Array(labels.length).fill(stats.plus2sd);
                    const lineLWL = Array(labels.length).fill(stats.minus2sd);
                    const linePlus1 = Array(labels.length).fill(stats.plus1sd);
                    const lineMinus1 = Array(labels.length).fill(stats.minus1sd);

                    // Map point colors based on z-score or outlier status
                    const pointColors = chartData.statuses.map(status => {
                        return status === 'outlier' ? 'rgba(231, 74, 59, 1)' : 'rgba(78, 115, 223, 1)';
                    });

                    const pointRadius = chartData.statuses.map(status => status === 'outlier' ? 6 : 4);

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [
                    {
                        label: 'Control',
                        data: dataPoints,
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
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 12,
                                        usePointStyle: true,
                                        font: { size: 11 }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            if (context.datasetIndex === 0) {
                                                const status = chartData.statuses[context.dataIndex];
                                                const rule = chartData.rules[context.dataIndex];
                                                let label = context.dataset.label + ': ' + context.parsed.y;
                                                if (status === 'outlier') {
                                                    label += ' (OUTLIER: ' + rule + ')';
                                                }
                                                return label;
                                            }
                                            return context.dataset.label + ': ' + context.parsed.y;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    suggestedMax: stats.plus3sd + stats.sd,
                                    suggestedMin: stats.minus3sd - stats.sd,
                                    title: {
                                        display: true,
                                        text: 'Nilai (%db)'
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
            @endpush
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i> Belum ada data historis pengujian untuk parameter ini yang dapat ditampilkan di Control Chart.
            </div>
        @endif

    @endif
    @include('parameter-uji.partials.override-modal')
</div>
