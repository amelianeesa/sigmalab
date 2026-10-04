@extends('layouts.app')
@section('title', 'Chart - QC Harian')

@section('content')
<style>
    .dashboard-container {
        padding: 0 20px !important;
        margin-top: -8px !important;
        padding-bottom: 1.5rem !important;
        font-size: 0.78rem;
        color: #000000;
    }

    .dashboard-container nav[aria-label="breadcrumb"],
    .dashboard-container > nav {
        margin: 0 !important;
        padding: 0 !important;
    }

    .dashboard-container .breadcrumb {
        margin: 0 0 6px 0 !important;
        padding: 0 !important;
        font-size: 0.75rem !important;
        line-height: 1.4;
        flex-wrap: wrap;
        align-items: center;
        background: transparent !important;
    }

    .dashboard-container .breadcrumb .breadcrumb-item,
    .dashboard-container .breadcrumb .breadcrumb-item a {
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        color: #0d6efd !important;
        text-decoration: none;
    }

    .dashboard-container .breadcrumb .breadcrumb-item a:hover {
        color: #0a58ca !important;
        text-decoration: underline;
    }

    .dashboard-container .breadcrumb .breadcrumb-item.active {
        color: #000000 !important;
        font-weight: 700 !important;
    }

    .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item {
        padding-left: 0.4rem;
    }

    .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        color: #6c757d !important;
        padding-right: 0.4rem;
        font-weight: 400;
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu {
        min-width: 190px;
        padding: 4px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item {
        color: #000000 !important;
        font-size: 0.78rem !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        background-color: transparent;
        padding: 7px 12px;
        border-radius: 5px;
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:hover,
    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:focus,
    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:active,
    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item.active {
        background-color: rgba(27, 49, 82, 0.15) !important;
        color: #000000 !important;
        text-decoration: none !important;
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item.active {
        font-weight: 700 !important;
    }

    .dashboard-container .card {
        border-radius: 0.5rem;
    }

    .card-body {
        padding: 12px !important;
    }

    .card-header-sm {
        padding: 8px 12px;
        background-color: #ffffff;
        border-bottom: 1px solid #e3e8ef;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .page-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #000000;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 0;
        color: #000000;
    }

    .page-subtitle {
        margin-bottom: 0;
        font-size: 0.72rem;
        line-height: 1.4;
        color: #495057;
    }

    .icon-corporate {
        color: #1b3152;
    }

    /* ===== Batch info ===== */
    .batch-title {
        margin-bottom: 2px;
        font-size: 0.88rem;
        font-weight: 700;
        color: #000000;
    }

    /* ===== Limit tiles ===== */
    .limit-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
        margin: 10px 0 12px 0;
    }

    .limit-tile {
        padding: 8px 6px;
        text-align: center;
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
    }

    .limit-tile small {
        display: block;
        margin-bottom: 2px;
        font-size: 0.66rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        color: #495057;
    }

    .limit-tile strong {
        font-size: 0.92rem;
        font-weight: 700;
        color: #000000;
    }

    .limit-tile.tile-soft {
        background-color: #f8fafc;
    }

    .limit-tile.tile-mean {
        background-color: rgba(25, 135, 84, 0.1);
        border-color: rgba(25, 135, 84, 0.35);
    }

    .limit-tile.tile-mean small,
    .limit-tile.tile-mean strong {
        color: #198754;
    }

    /* ===== Chart ===== */
    .chart-wrap {
        position: relative;
        width: 100%;
        height: 420px;
        padding: 8px;
        background-color: #ffffff;
        border: 1px solid #e3e8ef;
        border-radius: 6px;
    }

    /* ===== Table ===== */
    .qc-table-wrap {
        border: 1px solid #dfe4ea;
        border-radius: 6px;
    }

    .qc-table {
        min-width: 820px;
        margin-bottom: 0;
        font-size: 0.74rem;
        border-color: #cfd6df;
        --bs-table-hover-bg: rgba(27, 49, 82, 0.06);
        --bs-table-striped-bg: #f8fafc;
    }

    .qc-table thead th {
        padding: 0.4rem 0.4rem;
        font-size: 0.7rem;
        font-weight: 600;
        line-height: 1.25;
        color: #ffffff !important;
        background-color: #1b3152 !important;
        border-color: rgba(255, 255, 255, 0.25) !important;
        white-space: nowrap;
        vertical-align: middle;
    }

    .qc-table thead th.text-muted {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    .qc-table td {
        padding: 0.35rem 0.4rem;
        font-size: 0.74rem;
        color: #000000;
        border-color: #dfe4ea;
        vertical-align: middle;
    }

    .qc-table td.text-warning {
        color: #b58105 !important;
    }

    .qc-empty {
        padding: 1.2rem 0.5rem !important;
        font-size: 0.76rem;
        color: #6c757d !important;
    }

    .scroll-hint {
        display: none;
        margin-bottom: 4px;
        font-size: 0.68rem;
        color: #6c757d;
    }

    @media (max-width: 991.98px) {
        .limit-grid {
            grid-template-columns: repeat(4, 1fr);
        }

        .chart-wrap {
            height: 380px;
        }
    }

    @media (max-width: 767.98px) {
        .dashboard-container {
            padding: 0 10px !important;
            padding-bottom: 1.25rem !important;
        }

        .dashboard-container .breadcrumb,
        .dashboard-container .breadcrumb .breadcrumb-item,
        .dashboard-container .breadcrumb .breadcrumb-item a {
            font-size: 0.72rem !important;
        }

        .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item {
            padding-left: 0.3rem;
        }

        .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            padding-right: 0.3rem;
        }

        .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item {
            font-size: 0.85rem !important;
            padding: 10px 14px;
        }

        .card-body {
            padding: 10px !important;
        }

        .page-title {
            font-size: 0.95rem;
        }

        .limit-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .limit-tile strong {
            font-size: 0.88rem;
        }

        .chart-wrap {
            height: 320px;
            padding: 4px;
        }

        .scroll-hint {
            display: block;
        }
        .qc-table thead tr:first-child th:first-child,
        .qc-table tbody tr td:first-child {
            position: sticky;
            left: 0;
            z-index: 2;
        }
        .qc-table thead tr:first-child th:first-child {
            z-index: 3;
        }
    }
</style>

<div class="container-fluid dashboard-container">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $activeBatch->sampel_inhouse_id) }}" class="text-decoration-none">{{ $activeBatch->kode_batch }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('qc-harian.index') }}" class="text-decoration-none">Pengujian Harian QC</a></li>
        <li class="breadcrumb-item active">Control Chart</li>
    </x-qc-breadcrumb>

    <h5 class="page-title">
        <i class="fas fa-chart-area icon-corporate me-2"></i>Control Chart - {{ strtoupper($paramUji->nama_parameter) }}
    </h5>

    @php
        $m = (float)$paramUji->mean;
        $sd = (float)$paramUji->sd;
    @endphp

    <div class="card shadow-sm border-0 mb-2">
        <div class="card-header-sm">
            <h6 class="batch-title">Batch: {{ $activeBatch->kode_batch }}</h6>
            <p class="page-subtitle">Nilai acuan ditarik dari hasil Uji Homogenitas (Target).</p>
        </div>
        <div class="card-body">
            <div class="limit-grid">
                <div class="limit-tile"><small>UCL (+3SD)</small><strong>{{ number_format($m + 3*$sd, 2) }}</strong></div>
                <div class="limit-tile"><small>UWL (+2SD)</small><strong>{{ number_format($m + 2*$sd, 2) }}</strong></div>
                <div class="limit-tile tile-soft"><small>+1SD</small><strong>{{ number_format($m + 1*$sd, 2) }}</strong></div>
                <div class="limit-tile tile-mean"><small>MEAN</small><strong>{{ number_format($m, 2) }}</strong></div>
                <div class="limit-tile tile-soft"><small>-1SD</small><strong>{{ number_format($m - 1*$sd, 2) }}</strong></div>
                <div class="limit-tile"><small>LWL (-2SD)</small><strong>{{ number_format($m - 2*$sd, 2) }}</strong></div>
                <div class="limit-tile"><small>LCL (-3SD)</small><strong>{{ number_format($m - 3*$sd, 2) }}</strong></div>
            </div>

            <div class="chart-wrap">
                <canvas id="controlChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-2">
        <div class="card-header-sm">
            <h6 class="section-title"><i class="fas fa-table icon-corporate me-2"></i>Tabel Data Control Chart</h6>
        </div>
        <div class="card-body">
            <div class="scroll-hint"><i class="fas fa-arrows-alt-h me-1"></i> Geser tabel ke kiri/kanan untuk melihat seluruh kolom.</div>
            <div class="table-responsive qc-table-wrap">
                <table class="table table-bordered table-striped table-hover table-sm text-center align-middle qc-table">
                    <thead>
                        <tr>
                            <th rowspan="2" class="align-middle">Tanggal</th>
                            <th rowspan="2" class="align-middle">Pengujian Ke</th>
                            <th>LCL</th>
                            <th>LWL</th>
                            <th>&mu; - 1&sigma;</th>
                            <th>&mu; (Mean)</th>
                            <th>&mu; + 1&sigma;</th>
                            <th>UWL</th>
                            <th>UCL</th>
                            <th rowspan="2" class="align-middle">Control</th>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">({{ number_format($m - 3*$sd, 2) }})</th>
                            <th class="text-muted fw-normal">({{ number_format($m - 2*$sd, 2) }})</th>
                            <th class="text-muted fw-normal">({{ number_format($m - 1*$sd, 2) }})</th>
                            <th class="text-muted fw-normal">({{ number_format($m, 2) }})</th>
                            <th class="text-muted fw-normal">({{ number_format($m + 1*$sd, 2) }})</th>
                            <th class="text-muted fw-normal">({{ number_format($m + 2*$sd, 2) }})</th>
                            <th class="text-muted fw-normal">({{ number_format($m + 3*$sd, 2) }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($log->tanggal_uji)->format('d/m/Y') }}</td>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ number_format($m - 3*$sd, 2) }}</td>
                                <td>{{ number_format($m - 2*$sd, 2) }}</td>
                                <td>{{ number_format($m - 1*$sd, 2) }}</td>
                                <td>{{ number_format($m, 2) }}</td>
                                <td>{{ number_format($m + 1*$sd, 2) }}</td>
                                <td>{{ number_format($m + 2*$sd, 2) }}</td>
                                <td>{{ number_format($m + 3*$sd, 2) }}</td>
                                <td class="fw-bold {{ $log->status_evaluasi === 'outlier' ? 'text-danger' : ($log->status_evaluasi === 'warning' ? 'text-warning text-dark' : 'text-success') }}">
                                    {{ number_format($log->nilai_akhir, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="qc-empty">Belum ada data pengujian harian untuk parameter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('controlChart').getContext('2d');

    // Ukuran font chart disamakan dengan ukuran font halaman (tampilan saja)
    Chart.defaults.font.size = 11;

    const logs = @json($logs);
    
    const mean = {{ $m }};
    const sd = {{ $sd }};

    const labels = [];
    const dataPoints = [];
    const pointColors = [];
    const pointRadii = [];

    logs.forEach((log, index) => {

        let d = new Date(log.tanggal_uji);
        let tgl = ("0" + d.getDate()).slice(-2) + "/" + ("0" + (d.getMonth() + 1)).slice(-2) + "/" + d.getFullYear();
        labels.push(tgl);
        
        dataPoints.push(log.nilai_akhir);
        
        if (log.status_evaluasi === 'outlier') {
            pointColors.push('rgba(220, 53, 69, 1)'); // Red
            pointRadii.push(7);
        } else if (log.status_evaluasi === 'warning') {
            pointColors.push('rgba(255, 193, 7, 1)'); // Yellow
            pointRadii.push(6);
        } else {
            pointColors.push('rgba(0, 0, 0, 1)'); // Black
            pointRadii.push(4);
        }
    });

    const len = Math.max(10, labels.length);
    if(labels.length < 10) {
        for(let i = labels.length; i < 10; i++) labels.push('...');
    }

    const arrMean = Array(len).fill(mean);
    const arrUCL = Array(len).fill(mean + 3*sd);
    const arrLCL = Array(len).fill(mean - 3*sd);
    const arrUWL = Array(len).fill(mean + 2*sd);
    const arrLWL = Array(len).fill(mean - 2*sd);
    const arr1SD = Array(len).fill(mean + 1*sd);
    const arrM1SD = Array(len).fill(mean - 1*sd);

    if (typeof ChartDataLabels !== 'undefined') {
        Chart.register(ChartDataLabels);
    }
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Nilai QC',
                    data: dataPoints,
                    borderColor: 'rgba(0, 0, 0, 0.7)',
                    backgroundColor: 'transparent',
                    pointBackgroundColor: pointColors,
                    pointBorderColor: pointColors,
                    pointRadius: pointRadii,
                    pointHoverRadius: 8,
                    borderWidth: 2,
                    tension: 0.4,
                    order: 0,
                    datalabels: {
                        align: 'top',
                        anchor: 'end',
                        color: '#333',
                        font: { weight: 'bold', size: 10 },
                        formatter: function(value, context) {
                            return parseFloat(value).toFixed(2);
                        }
                    }
                },
                {
                    label: 'Mean',
                    data: arrMean,
                    borderColor: 'rgba(25, 135, 84, 0.8)',
                    borderWidth: 2,
                    pointRadius: 0,
                    order: 1,
                    datalabels: { display: false }
                },
                {
                    label: 'UCL (+3SD)',
                    data: arrUCL,
                    borderColor: 'rgba(220, 53, 69, 0.8)',
                    borderWidth: 2,
                    pointRadius: 0,
                    order: 2,
                    datalabels: { display: false }
                },
                {
                    label: 'LCL (-3SD)',
                    data: arrLCL,
                    borderColor: 'rgba(220, 53, 69, 0.8)',
                    borderWidth: 2,
                    pointRadius: 0,
                    order: 3,
                    datalabels: { display: false }
                },
                {
                    label: 'UWL (+2SD)',
                    data: arrUWL,
                    borderColor: 'rgba(255, 193, 7, 0.8)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    pointRadius: 0,
                    order: 4,
                    datalabels: { display: false }
                },
                {
                    label: 'LWL (-2SD)',
                    data: arrLWL,
                    borderColor: 'rgba(255, 193, 7, 0.8)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    pointRadius: 0,
                    order: 5,
                    datalabels: { display: false }
                },
                {
                    label: '+1SD',
                    data: arr1SD,
                    borderColor: 'rgba(108, 117, 125, 0.4)',
                    borderWidth: 1,
                    borderDash: [2, 2],
                    pointRadius: 0,
                    order: 6,
                    datalabels: { display: false }
                },
                {
                    label: '-1SD',
                    data: arrM1SD,
                    borderColor: 'rgba(108, 117, 125, 0.4)',
                    borderWidth: 1,
                    borderDash: [2, 2],
                    pointRadius: 0,
                    order: 7,
                    datalabels: { display: false }
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    bottom: 15 // Memberikan ruang tambahan di bawah agar label tidak terpotong
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if(context.datasetIndex === 0) {
                                let log = logs[context.dataIndex];
                                if(!log) return `Nilai: ${context.raw}`;
                                let status = log.status_evaluasi.toUpperCase();
                                let rule = log.pelanggaran_rule ? ` (${log.pelanggaran_rule})` : '';
                                return `Nilai: ${context.raw} | ${status}${rule}`;
                            }
                            return `${context.dataset.label}: ${context.raw}`;
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 24,
                        boxHeight: 8,
                        filter: function(item, chart) {

                            return !item.text.includes('1SD');
                        }
                    }
                }
            },
            scales: {
                y: {

                    suggestedMax: mean + (3.5 * sd),
                    suggestedMin: mean - (3.5 * sd)
                }
            }
        }
    });
});
</script>
@endsection