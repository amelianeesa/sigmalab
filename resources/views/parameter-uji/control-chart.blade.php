@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-1 mt-3">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
        <li class="breadcrumb-item"><a href="{{ route('parameter-uji.index') }}" class="text-decoration-none">Parameter Uji</a></li>
        <li class="breadcrumb-item active">Control Chart</li>
    </ol>
    <h1 class="mb-4">Control Chart: {{ $parameterUji->nama_parameter }}</h1>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-chart-line me-2"></i> Grafik Control Chart (Westgard Rules)
            </h6>
        </div>
        <div class="card-body">
            <canvas id="controlChartCanvas" style="width: 100%; height: 400px;"></canvas>
        </div>
    </div>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-table me-2"></i>Tabel Data: Control Chart Inhouse {{ $parameterUji->nama_parameter }} In The Analysis</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle border mb-0 text-center" style="font-size: 0.85rem;">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>Pengujian Ke-</th>
                            <th>LCL ({{ number_format($parameterUji->lcl, 2, ',', '.') }})</th>
                            <th>LWL ({{ number_format($parameterUji->uwl_bawah, 2, ',', '.') }})</th>
                            <th>µ-1σ ({{ number_format($minus1Sd, 2, ',', '.') }})</th>
                            <th class="bg-primary text-white">µ ({{ number_format($parameterUji->mean, 2, ',', '.') }})</th>
                            <th>µ+1σ ({{ number_format($plus1Sd, 2, ',', '.') }})</th>
                            <th>UWL ({{ number_format($parameterUji->uwl_atas, 2, ',', '.') }})</th>
                            <th>UCL ({{ number_format($parameterUji->ucl, 2, ',', '.') }})</th>
                            <th class="bg-info text-white">Control</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hasilUjiList as $index => $item)
                        <tr>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ number_format($parameterUji->lcl, 2, ',', '.') }}</td>
                            <td>{{ number_format($parameterUji->uwl_bawah, 2, ',', '.') }}</td>
                            <td>{{ number_format($minus1Sd, 2, ',', '.') }}</td>
                            <td class="fw-bold">{{ number_format($parameterUji->mean, 2, ',', '.') }}</td>
                            <td>{{ number_format($plus1Sd, 2, ',', '.') }}</td>
                            <td>{{ number_format($parameterUji->uwl_atas, 2, ',', '.') }}</td>
                            <td>{{ number_format($parameterUji->ucl, 2, ',', '.') }}</td>
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
                        position: 'right',
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
