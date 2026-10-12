@extends('layouts.app')
@section('title', 'Control Chart - Parameter Uji')

@section('content')
<style>
    .cc-page { font-size: 0.82rem; }
    .cc-page .breadcrumb { font-size: 0.78rem; }

    .cc-header {
        background-color: #1b3152 !important; color: #ffffff !important;
        font-weight: 600; font-size: 0.85rem !important;
    }

    .cc-chart-wrap { position: relative; width: 100%; height: 400px; }

    .cc-head th {
        background-color: #1b3152 !important; color: #ffffff !important; border-color: #ffffff !important;
        font-size: 0.72rem !important; vertical-align: middle !important;
    }
    .cc-head th.cc-accent { background-color: #0d6efd !important; }
    .cc-table td { font-size: 0.75rem !important; vertical-align: middle !important; }

    .cc-chip { border: 1px solid #dee2e6; border-radius: 6px; padding: 4px 8px; text-align: center; background: #fff; }
    .cc-chip small { display: block; font-size: 0.62rem; color: #6c757d; }
    .cc-chip strong { font-size: 0.75rem; }

    @media (max-width: 767.98px) {
        .cc-chart-wrap { height: 320px; }
        .cc-table th, .cc-table td { padding: 6px 6px !important; }
    }
</style>

<div class="container-fluid px-2 px-md-4 cc-page pb-4">
    <ol class="breadcrumb mb-1 mt-3">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
        <li class="breadcrumb-item"><a href="{{ route('parameter-uji.index') }}" class="text-decoration-none">Parameter Uji</a></li>
        <li class="breadcrumb-item active">Control Chart</li>
    </ol>
    <h5 class="fw-bold mb-3" style="font-size: 1.1rem;">
        <i class="fas fa-chart-line me-2" style="color: #1b3152;"></i>Control Chart: {{ $parameterUji->nama_parameter }}
    </h5>

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-header cc-header py-2">
            <i class="fas fa-chart-line me-1"></i> Grafik Control Chart (Westgard Rules)
        </div>
        <div class="card-body p-2 p-md-3">
            <div class="cc-chart-wrap">
                <canvas id="controlChartCanvas"></canvas>
            </div>
        </div>
    </div>

    <div class="card mb-3 shadow-sm border-0">
        <div class="card-header cc-header py-2">
            <i class="fas fa-table me-1"></i> Tabel Data: Control Chart Inhouse {{ $parameterUji->nama_parameter }} In The Analysis
        </div>
        <div class="card-body p-0">

            {{-- Ringkasan batas kontrol (HP) — di layar besar tampil sebagai kolom tabel --}}
            <div class="d-md-none px-2 pt-2">
                <div class="row g-1">
                    <div class="col-4"><div class="cc-chip"><small>LCL</small><strong class="text-danger">{{ number_format($parameterUji->lcl, 2, ',', '.') }}</strong></div></div>
                    <div class="col-4"><div class="cc-chip"><small>LWL</small><strong class="text-warning">{{ number_format($parameterUji->uwl_bawah, 2, ',', '.') }}</strong></div></div>
                    <div class="col-4"><div class="cc-chip"><small>µ-1σ</small><strong class="text-success">{{ number_format($minus1Sd, 2, ',', '.') }}</strong></div></div>
                    <div class="col-4"><div class="cc-chip"><small>µ</small><strong class="text-primary">{{ number_format($parameterUji->mean, 2, ',', '.') }}</strong></div></div>
                    <div class="col-4"><div class="cc-chip"><small>µ+1σ</small><strong class="text-success">{{ number_format($plus1Sd, 2, ',', '.') }}</strong></div></div>
                    <div class="col-4"><div class="cc-chip"><small>UWL</small><strong class="text-warning">{{ number_format($parameterUji->uwl_atas, 2, ',', '.') }}</strong></div></div>
                    <div class="col-12"><div class="cc-chip"><small>UCL</small><strong class="text-danger">{{ number_format($parameterUji->ucl, 2, ',', '.') }}</strong></div></div>
                </div>
            </div>

            <div class="table-responsive mt-2 mt-md-0">
                <table class="table table-hover table-striped align-middle border mb-0 text-center cc-table">
                    <thead class="cc-head">
                        <tr>
                            <th>Tanggal</th>
                            <th>Pengujian Ke-</th>
                            <th class="d-none d-md-table-cell">LCL ({{ number_format($parameterUji->lcl, 2, ',', '.') }})</th>
                            <th class="d-none d-md-table-cell">LWL ({{ number_format($parameterUji->uwl_bawah, 2, ',', '.') }})</th>
                            <th class="d-none d-md-table-cell">µ-1σ ({{ number_format($minus1Sd, 2, ',', '.') }})</th>
                            <th class="d-none d-md-table-cell cc-accent">µ ({{ number_format($parameterUji->mean, 2, ',', '.') }})</th>
                            <th class="d-none d-md-table-cell">µ+1σ ({{ number_format($plus1Sd, 2, ',', '.') }})</th>
                            <th class="d-none d-md-table-cell">UWL ({{ number_format($parameterUji->uwl_atas, 2, ',', '.') }})</th>
                            <th class="d-none d-md-table-cell">UCL ({{ number_format($parameterUji->ucl, 2, ',', '.') }})</th>
                            <th class="cc-accent">Control</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hasilUjiList as $index => $item)
                        <tr>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            <td>{{ $index + 1 }}</td>
                            <td class="d-none d-md-table-cell">{{ number_format($parameterUji->lcl, 2, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell">{{ number_format($parameterUji->uwl_bawah, 2, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell">{{ number_format($minus1Sd, 2, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell fw-bold">{{ number_format($parameterUji->mean, 2, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell">{{ number_format($plus1Sd, 2, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell">{{ number_format($parameterUji->uwl_atas, 2, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell">{{ number_format($parameterUji->ucl, 2, ',', '.') }}</td>
                            <td class="fw-bold">{{ number_format($item->nilai_hasil, 2, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">Belum ada data hasil pengujian untuk parameter ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('controlChartCanvas').getContext('2d');
        
        const labels = {!! json_encode($hasilUjiList->map(function($item, $idx) { return 'Uji ' . ($idx + 1) . ' (' . $item->created_at->format('d/m') . ')'; })) !!};
        const dataControl = {!! json_encode($hasilUjiList->pluck('nilai_hasil')) !!};
        
        const lcl = {{ $parameterUji->lcl ?? 0 }};
        const lwl = {{ $parameterUji->uwl_bawah ?? 0 }};
        const mean = {{ $parameterUji->mean ?? 0 }};
        const uwl = {{ $parameterUji->uwl_atas ?? 0 }};
        const ucl = {{ $parameterUji->ucl ?? 0 }};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Control (Aktual)',
                        data: dataControl,
                        borderColor: 'rgb(13, 110, 253)', // Primary blue
                        backgroundColor: 'rgba(13, 110, 253, 0.5)',
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgb(13, 110, 253)',
                        fill: false,
                        tension: 0.1
                    },
                    {
                        label: 'UCL (+3SD)',
                        data: Array(labels.length).fill(ucl),
                        borderColor: 'rgb(220, 53, 69)', // Danger red
                        borderWidth: 1.5,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: 'UWL (+2SD)',
                        data: Array(labels.length).fill(uwl),
                        borderColor: 'rgb(255, 193, 7)', // Warning yellow
                        borderWidth: 1,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: 'Mean (µ)',
                        data: Array(labels.length).fill(mean),
                        borderColor: 'rgb(25, 135, 84)', // Success green
                        borderWidth: 2,
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: 'LWL (-2SD)',
                        data: Array(labels.length).fill(lwl),
                        borderColor: 'rgb(255, 193, 7)', // Warning yellow
                        borderWidth: 1,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: 'LCL (-3SD)',
                        data: Array(labels.length).fill(lcl),
                        borderColor: 'rgb(220, 53, 69)', // Danger red
                        borderWidth: 1.5,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Nilai Hasil Uji'
                        },
                        suggestedMin: lcl - (mean - lcl) * 0.5
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Siklus Pengujian'
                        }
                    }
                },
                plugins: {
                    legend: {
                        // Di HP legenda dipindah ke bawah supaya area grafik tidak menyempit
                        position: window.innerWidth < 768 ? 'bottom' : 'right',
                        labels: { boxWidth: 12, font: { size: 11 } }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                }
            }
        });
    });
</script>
@endsection