@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $activeBatch->sampel_inhouse_id) }}" class="text-decoration-none">{{ $activeBatch->kode_batch }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('qc-harian.index') }}" class="text-decoration-none">Pengujian Harian QC</a></li>
        <li class="breadcrumb-item active">Control Chart</li>
    </x-qc-breadcrumb>
    <h2 class="mb-4 fw-bold text-dark">
        <i class="fas fa-chart-area text-primary me-2"></i>Control Chart - {{ strtoupper($paramUji->nama_parameter) }}
    </h2>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <h5 class="fw-bold mb-1">Batch: {{ $activeBatch->kode_batch }}</h5>
                    <p class="text-muted mb-0 small">Nilai acuan ditarik dari hasil Uji Homogenitas (Target).</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('qc-harian.index') }}" class="btn btn-outline-secondary rounded-pill px-4"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
                </div>
            </div>

            <!-- Legenda Nilai Acuan -->
            <div class="row g-2 mb-4">
                @php
                    $m = (float)$paramUji->mean;
                    $sd = (float)$paramUji->sd;
                @endphp
                <div class="col-md-2 col-6"><div class="p-2 border rounded text-center"><small class="d-block text-muted">UCL (+3SD)</small><strong>{{ number_format($m + 3*$sd, 2) }}</strong></div></div>
                <div class="col-md-2 col-6"><div class="p-2 border rounded text-center"><small class="d-block text-muted">UWL (+2SD)</small><strong>{{ number_format($m + 2*$sd, 2) }}</strong></div></div>
                <div class="col-md-2 col-6"><div class="p-2 border rounded text-center bg-light"><small class="d-block text-muted">+1SD</small><strong>{{ number_format($m + 1*$sd, 2) }}</strong></div></div>
                <div class="col-md-2 col-6"><div class="p-2 border rounded text-center bg-success bg-opacity-10"><small class="d-block text-success">MEAN</small><strong class="text-success">{{ number_format($m, 2) }}</strong></div></div>
                <div class="col-md-2 col-6"><div class="p-2 border rounded text-center bg-light"><small class="d-block text-muted">-1SD</small><strong>{{ number_format($m - 1*$sd, 2) }}</strong></div></div>
                <div class="col-md-2 col-6"><div class="p-2 border rounded text-center"><small class="d-block text-muted">LWL (-2SD)</small><strong>{{ number_format($m - 2*$sd, 2) }}</strong></div></div>
                <div class="col-md-2 col-6"><div class="p-2 border rounded text-center"><small class="d-block text-muted">LCL (-3SD)</small><strong>{{ number_format($m - 3*$sd, 2) }}</strong></div></div>
            </div>

            <div style="height: 500px; width: 100%;">
                <canvas id="controlChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('controlChart').getContext('2d');
    
    // Data dari Controller
    const logs = @json($logs);
    
    const mean = {{ $m }};
    const sd = {{ $sd }};
    
    // Siapkan array data
    const labels = [];
    const dataPoints = [];
    const pointColors = [];
    const pointRadii = [];

    logs.forEach((log, index) => {
        labels.push(log.tanggal_uji);
        dataPoints.push(log.nilai_akhir);
        
        if (log.status_evaluasi === 'outlier') {
            pointColors.push('rgba(220, 53, 69, 1)'); // Red
            pointRadii.push(7);
        } else if (log.status_evaluasi === 'warning') {
            pointColors.push('rgba(255, 193, 7, 1)'); // Yellow
            pointRadii.push(6);
        } else {
            pointColors.push('rgba(13, 110, 253, 1)'); // Blue
            pointRadii.push(4);
        }
    });

    // Buat array konstan untuk garis acuan sepanjang jumlah data
    // Minimal 10 titik agar garis tetap panjang walau data masih sedikit
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

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Nilai QC',
                    data: dataPoints,
                    borderColor: 'rgba(13, 110, 253, 0.5)',
                    backgroundColor: 'transparent',
                    pointBackgroundColor: pointColors,
                    pointBorderColor: pointColors,
                    pointRadius: pointRadii,
                    pointHoverRadius: 8,
                    borderWidth: 2,
                    tension: 0.1,
                    order: 0
                },
                {
                    label: 'Mean',
                    data: arrMean,
                    borderColor: 'rgba(25, 135, 84, 0.8)',
                    borderWidth: 2,
                    pointRadius: 0,
                    order: 1
                },
                {
                    label: 'UCL (+3SD)',
                    data: arrUCL,
                    borderColor: 'rgba(220, 53, 69, 0.8)',
                    borderWidth: 2,
                    pointRadius: 0,
                    order: 2
                },
                {
                    label: 'LCL (-3SD)',
                    data: arrLCL,
                    borderColor: 'rgba(220, 53, 69, 0.8)',
                    borderWidth: 2,
                    pointRadius: 0,
                    order: 3
                },
                {
                    label: 'UWL (+2SD)',
                    data: arrUWL,
                    borderColor: 'rgba(255, 193, 7, 0.8)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    pointRadius: 0,
                    order: 4
                },
                {
                    label: 'LWL (-2SD)',
                    data: arrLWL,
                    borderColor: 'rgba(255, 193, 7, 0.8)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    pointRadius: 0,
                    order: 5
                },
                {
                    label: '+1SD',
                    data: arr1SD,
                    borderColor: 'rgba(108, 117, 125, 0.4)',
                    borderWidth: 1,
                    borderDash: [2, 2],
                    pointRadius: 0,
                    order: 6
                },
                {
                    label: '-1SD',
                    data: arrM1SD,
                    borderColor: 'rgba(108, 117, 125, 0.4)',
                    borderWidth: 1,
                    borderDash: [2, 2],
                    pointRadius: 0,
                    order: 7
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
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
                        filter: function(item, chart) {
                            // Sembunyikan 1SD dari legend biar ga keramaian
                            return !item.text.includes('1SD');
                        }
                    }
                }
            },
            scales: {
                y: {
                    // Agar range grafik tidak terlalu nempel dengan garis 3SD
                    suggestedMax: mean + (3.5 * sd),
                    suggestedMin: mean - (3.5 * sd)
                }
            }
        }
    });
});
</script>
@endsection
