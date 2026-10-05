@include('parameter-uji.partials.limit-eval-alert')

<style>
    .ash-page {
        padding: 4px 20px !important;
        font-size: 0.82rem;
    }

    /* Breadcrumb */
    .ash-page .breadcrumb {
        margin: 0 0 6px 0 !important;
        padding: 0 !important;
        font-size: 0.75rem !important;
        background: transparent !important;
        flex-wrap: wrap;
    }
    .ash-page .breadcrumb .breadcrumb-item,
    .ash-page .breadcrumb .breadcrumb-item a {
        font-size: 0.75rem !important;
        font-weight: 500;
    }
    .ash-page .breadcrumb .breadcrumb-item.active {
        color: #000 !important;
        font-weight: 700;
    }

    /* Judul */
    .ash-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0;
        color: #1b3152;
    }

    /* Card */
    .ash-page .card-body { padding: 10px !important; }
    .ash-page .card-header {
        padding: 8px 12px !important;
        background: #fff;
    }
    .ash-page .card-header h6 {
        font-size: 0.85rem !important;
        margin: 0;
    }

    /* Form */
    .ash-page .form-label {
        font-size: 0.75rem;
        margin-bottom: 2px;
        font-weight: 600;
    }
    .ash-page .form-control,
    .ash-page .form-select {
        font-size: 0.8rem;
        padding-top: 0.3rem;
        padding-bottom: 0.3rem;
    }
    .ash-page .btn { font-size: 0.78rem; }

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

    /* Alert / notifikasi */
    .ash-page .alert {
        font-size: 0.8rem;
        padding: 6px 12px;
        margin-bottom: 10px;
    }

    /* Tabel */
    .ash-page .table th,
    .ash-page .table td {
        padding: 6px 8px !important;
        vertical-align: middle !important;
        font-size: 0.72rem !important;
    }
    .ash-page .table thead th {
        font-size: 0.72rem !important;
        background-color: #1b3152 !important;
        color: #fff !important;
        border-color: #fff !important;
    }

    /* Warna kelompok kolom (kalem) */
    .ash-page .table thead th.th-manual { background-color: #2f5d9e !important; color: #fff !important; }
    .ash-page .table thead th.th-auto   { background-color: #6b7a90 !important; color: #fff !important; }
    .ash-page .table thead th.th-diff   { background-color: #d9a21b !important; color: #1b1b1b !important; }
    .ash-page .table thead th.th-avg    { background-color: #2a8fa6 !important; color: #fff !important; }
    .ash-page .table thead th.th-final  { background-color: #2b7a4b !important; color: #fff !important; }

    .ash-page .badge { font-size: 0.65rem; }

    /* Kartu batas kendali */
    .ash-limit-row,
    .ash-limit-row span { font-size: 0.75rem; }

    /* Grafik */
    .ash-chart { height: 280px; }

    @media (max-width: 767.98px) {
        .ash-page { padding: 4px 10px !important; }
        .ash-page .breadcrumb,
        .ash-page .breadcrumb .breadcrumb-item,
        .ash-page .breadcrumb .breadcrumb-item a {
            font-size: 0.72rem !important;
        }
        .ash-title { font-size: 1rem; }
        .ash-chart { height: 220px; }
        .ash-page .table th,
        .ash-page .table td {
            padding: 5px 6px !important;
            font-size: 0.68rem !important;
        }
    }
</style>

<div class="container-fluid ash-page pb-4">

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="ash-title">
            {{ ($jenisGrafik ?? 'in_house') == 'crm' ? 'Sertifikat Pabrik (CRM)' : 'Inhouse Control' }}: Ash Content (ASH)
        </h5>
    </div>

    <!-- Filter Card -->
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-header border-bottom">
            <h6 class="fw-bold" style="color: #1b3152;"><i class="fas fa-filter me-1"></i> Filter Data</h6>
        </div>
        <div class="card-body">
            <form action="{{ url()->current() }}" method="GET" class="row g-2 align-items-end">
                <input type="hidden" name="tab" value="{{ $jenisGrafik ?? 'in_house' }}">

                <div class="col-12 col-md-4">
                    <label for="parameter_uji_id" class="form-label">Parameter Uji</label>
                    <select class="form-select form-select-sm" id="parameter_uji_id" name="parameter_uji_id" required>
                        <option value="">-- Pilih Parameter --</option>
                        @foreach($parameterList as $param)
                            <option value="{{ $param->parameter_uji_id }}" {{ request('parameter_uji_id') == $param->parameter_uji_id ? 'selected' : '' }}>
                                {{ $param->nama_parameter }} ({{ $param->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control form-control-sm" id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control form-control-sm" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-12 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-corporate-blue btn-sm flex-grow-1 shadow-sm fw-semibold">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    @if($selectedParameter)
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

    @if(!isset($selectedParameter) || !$selectedParameter)
        <!-- No Parameter Selected -->
        <div class="alert alert-info shadow-sm border-0" role="alert">
            <i class="fas fa-info-circle me-1"></i> Silakan pilih Parameter Uji terlebih dahulu untuk menampilkan Inhouse Control.
        </div>
    @else

        <!-- Raw Data Logger (Duplo) -->
        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-header border-bottom">
                <h6 class="fw-bold" style="color: #1b3152;"><i class="fas fa-table me-1"></i> Tabel Raw Data Logger (Duplo Testing)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered table-hover mb-0 text-center align-middle">
                        <thead class="sticky-top">
                            <tr>
                                <th rowspan="2" class="align-middle">Tanggal</th>
                                <th rowspan="2" class="align-middle">Pengujian Ke-</th>
                                <th rowspan="2" class="align-middle">Kode Sampel</th>
                                <th rowspan="2" class="align-middle">Dish No.</th>
                                <th colspan="3" class="th-manual">Input Data (Mass)</th>
                                <th colspan="2" class="th-auto">Calculated (g)</th>
                                <th rowspan="2" class="align-middle">ASH%</th>
                                <th colspan="2" class="th-diff">Absolute Diff.</th>
                                <th rowspan="2" class="align-middle th-avg">Average %adb</th>
                                <th rowspan="2" class="align-middle th-avg">%db per Dish</th>
                                <th rowspan="2" class="align-middle th-final">Rata-rata %db</th>
                            </tr>
                            <tr>
                                <th class="th-manual" title="Massa cawan kosong">M1</th>
                                <th class="th-manual" title="Massa sampel">M2-M1</th>
                                <th class="th-manual" title="Massa cawan + Abu">M3</th>
                                <th class="th-auto" title="M1 + M2M1">M2</th>
                                <th class="th-auto" title="M3 - M1">M3-M1</th>
                                <th class="th-diff">Value</th>
                                <th class="th-diff">Eval (&le; 0.20)</th>
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
                                    <td class="bg-light">{{ is_numeric($M2_1) ? number_format($M2_1, 4) : $M2_1 }}</td>
                                    <td class="bg-light">{{ is_numeric($M3M1_1) ? number_format($M3M1_1, 4) : $M3M1_1 }}</td>
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
                                    <td rowspan="2" class="align-middle fw-bold">{{ is_numeric($avgAdb) ? number_format($avgAdb, 4) : $avgAdb }}</td>
                                    <td class="fw-bold text-primary">{{ is_numeric($DB_1) ? number_format($DB_1, 2) : $DB_1 }}</td>
                                    <td rowspan="2" class="align-middle fw-bold {{ $hasil->status_berketerimaan === 'gagal_duplo' ? 'text-danger text-decoration-line-through' : 'text-success' }}">
                                        {{ number_format($hasil->nilai_hasil, 2) }}
                                        @if($hasil->status_berketerimaan === 'gagal_duplo')
                                            <br><span class="badge bg-danger mt-1">REJECTED</span>
                                        @elseif($hasil->status_berketerimaan === 'pending')
                                            <br><span class="badge bg-warning text-dark mt-1">Menunggu IM</span>
                                        @endif
                                    </td>
                                </tr>
                                <!-- Dish 2 Row -->
                                <tr>
                                    <td class="text-primary fw-bold">Dish 2</td>
                                    <td>{{ $M1_2 }}</td>
                                    <td class="fw-bold">{{ $M2M1_2 }}</td>
                                    <td>{{ $M3_2 }}</td>
                                    <td class="bg-light">{{ is_numeric($M2_2) ? number_format($M2_2, 4) : $M2_2 }}</td>
                                    <td class="bg-light">{{ is_numeric($M3M1_2) ? number_format($M3M1_2, 4) : $M3M1_2 }}</td>
                                    <td class="fw-bold">{{ is_numeric($ASH_2) ? number_format($ASH_2, 4) : $ASH_2 }}</td>
                                    <td class="fw-bold text-primary">{{ is_numeric($DB_2) ? number_format($DB_2, 2) : $DB_2 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center text-muted py-3">Belum ada data pengujian Ash Content.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if(isset($chartData) && count($chartData['labels']) > 0)

            <div class="row g-3 mb-3">
                <!-- Limit Data Card -->
                <div class="col-12 col-xl-3 col-md-5">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header border-bottom">
                            <h6 class="fw-bold" style="color: #1b3152;">Batas Kendali (%db)</h6>
                        </div>
                        <div class="card-body">
                            @php
                                $stats = $chartData['stats'];
                            @endphp

                            <div class="ash-limit-row d-flex justify-content-between border-bottom pb-1 mb-1">
                                <span class="text-muted">UCL (+3&sigma;)</span>
                                <span class="fw-bold text-danger">{{ number_format($stats['plus3sd'], 4) }}</span>
                            </div>
                            <div class="ash-limit-row d-flex justify-content-between border-bottom pb-1 mb-1">
                                <span class="text-muted">UWL (+2&sigma;)</span>
                                <span class="fw-bold text-warning">{{ number_format($stats['plus2sd'], 4) }}</span>
                            </div>
                            <div class="ash-limit-row d-flex justify-content-between border-bottom pb-1 mb-1">
                                <span class="text-muted">+1&sigma;</span>
                                <span class="fw-bold text-success">{{ number_format($stats['plus1sd'], 4) }}</span>
                            </div>
                            <div class="ash-limit-row d-flex justify-content-between border-bottom pb-1 mb-1 bg-light">
                                <span class="text-dark fw-bold">Mean (&mu;)</span>
                                <span class="fw-bold text-dark">{{ number_format($stats['mean'], 4) }}</span>
                            </div>
                            <div class="ash-limit-row d-flex justify-content-between border-bottom pb-1 mb-1">
                                <span class="text-muted">-1&sigma;</span>
                                <span class="fw-bold text-success">{{ number_format($stats['minus1sd'], 4) }}</span>
                            </div>
                            <div class="ash-limit-row d-flex justify-content-between border-bottom pb-1 mb-1">
                                <span class="text-muted">LWL (-2&sigma;)</span>
                                <span class="fw-bold text-warning">{{ number_format($stats['minus2sd'], 4) }}</span>
                            </div>
                            <div class="ash-limit-row d-flex justify-content-between pb-1">
                                <span class="text-muted">LCL (-3&sigma;)</span>
                                <span class="fw-bold text-danger">{{ number_format($stats['minus3sd'], 4) }}</span>
                            </div>
                            <div class="mt-2 text-center">
                                <small class="text-muted" style="font-size: 0.72rem;">SD (&sigma;) = {{ number_format($stats['sd'], 4) }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Card Control Chart Limit -->
                <div class="col-12 col-xl-9 col-md-7">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header border-bottom">
                            <h6 class="fw-bold" style="color: #1b3152;">Tabel Limit Control Chart In-house ASH Content</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                                <table class="table table-bordered table-striped table-hover mb-0 text-center">
                                    <thead class="sticky-top">
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
                                            <th title="Lower Control Limit (-3SD)">LCL<br>({{ number_format($fixedLcl, 2) }})</th>
                                            <th title="Lower Warning Limit (-2SD)">LWL<br>({{ number_format($fixedLwl, 2) }})</th>
                                            <th title="Mean - 1SD">µ-1σ<br>({{ number_format($minus1Sd, 2) }})</th>
                                            <th>µ<br>({{ number_format($fixedMean, 2) }})</th>
                                            <th title="Mean + 1SD">µ+1σ<br>({{ number_format($plus1Sd, 2) }})</th>
                                            <th title="Upper Warning Limit (+2SD)">UWL<br>({{ number_format($fixedUwl, 2) }})</th>
                                            <th title="Upper Control Limit (+3SD)">UCL<br>({{ number_format($fixedUcl, 2) }})</th>
                                            <th>Control (%db)</th>
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
                                                <td class="text-primary fw-bold">{{ number_format($fixedMean, 4) }}</td>
                                                <td class="text-success">{{ number_format($plus1Sd, 4) }}</td>
                                                <td class="text-warning text-dark">{{ number_format($fixedUwl, 4) }}</td>
                                                <td class="text-danger">{{ number_format($fixedUcl, 4) }}</td>
                                                <td class="fw-bold bg-info text-white">
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
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-header border-bottom">
                    <h6 class="fw-bold" style="color: #1b3152;"><i class="fas fa-chart-line me-1"></i> Levy-Jennings Chart (%db)</h6>
                </div>
                <div class="card-body">
                    <div class="ash-chart">
                        <canvas id="levyJenningsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tabel Evaluasi Control Chart (Westgard) -->
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-header border-bottom">
                    <h6 class="fw-bold" style="color: #1b3152;"><i class="fas fa-list-check me-1"></i> Tabel Evaluasi Control Chart (Westgard Rules)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
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
                                    display: true,
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 10,
                                        usePointStyle: true,
                                        font: { size: 10 }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            if (context.datasetIndex === 0) {
                                                const status = chartData.statuses[context.dataIndex];
                                                const rule = chartData.rules ? chartData.rules[context.dataIndex] : '';
                                                let label = context.dataset.label + ': ' + context.parsed.y;
                                                if (status === 'outlier') {
                                                    label += ' (OUTLIER' + (rule ? ': ' + rule : '') + ')';
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
                                    ticks: { font: { size: 10 } },
                                    title: {
                                        display: true,
                                        text: 'Nilai (%db)',
                                        font: { size: 11 }
                                    }
                                },
                                x: {
                                    ticks: { font: { size: 10 } }
                                }
                            }
                        }
                    });
                });
            </script>
            @endpush
        @else
            <div class="alert alert-warning shadow-sm border-0">
                <i class="fas fa-exclamation-triangle me-1"></i> Belum ada data historis pengujian untuk parameter ini yang dapat ditampilkan di Control Chart.
            </div>
        @endif

    @endif
    @include('parameter-uji.partials.override-modal')
</div>