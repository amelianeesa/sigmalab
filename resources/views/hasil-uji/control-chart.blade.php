@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-underline text-primary text-decoration-none">Verifikasi Mutu</a></li>
            <li class="breadcrumb-item"><a href="{{ route('hasil-uji.inhouse-control') }}" class="text-decoration-none">Monitoring QC</a></li>
            @if($selectedParameter)
                <li class="breadcrumb-item active" aria-current="page">{{ $selectedParameter->nama_parameter }}</li>
            @endif
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Inhouse Control {{ $selectedParameter ? '- ' . $selectedParameter->nama_parameter : '' }}</h1>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Data</h6>
        </div>
        <div class="card-body">
            <form action="{{ url()->current() }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
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
                <div class="col-md-2">
                    <label for="jenis_grafik" class="form-label">Jenis Grafik</label>
                    <select class="form-select" id="jenis_grafik" name="jenis_grafik" required>
                        <option value="in_house" {{ request('jenis_grafik', 'in_house') == 'in_house' ? 'selected' : '' }}>In-House (Levy-Jennings)</option>
                        <option value="crm" {{ request('jenis_grafik') == 'crm' ? 'selected' : '' }}>CRM (Akurasi % Recovery)</option>
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
                        <button type="button" class="btn btn-danger flex-grow-1" onclick="cetakPDF()">
                            <i class="fas fa-file-pdf"></i> Cetak
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if($selectedParameter)
    <!-- Form Cetak PDF (Hidden) -->
    <form id="formCetak" action="{{ route('hasil-uji.inhouse-control.cetak') }}" method="POST" target="_blank" style="display: none;">
        @csrf
        <input type="hidden" name="parameter_uji_id" value="{{ request('parameter_uji_id') }}">
        <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
        <input type="hidden" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
        <input type="hidden" name="chart_image" id="chart_image">
    </form>
    @endif

    @if(!isset($selectedParameter) || !$selectedParameter)
        <!-- No Parameter Selected -->
        <div class="alert alert-info shadow-sm" role="alert">
            <i class="fas fa-info-circle me-2"></i> Silakan pilih Parameter Uji terlebih dahulu untuk menampilkan Control Chart.
        </div>
    @else
        <!-- Chart Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    Levy-Jennings Chart: {{ $selectedParameter->nama_parameter }}
                </h6>
            </div>
            <div class="card-body">
                @if(isset($chartData) && count($chartData['labels']) > 0)
                    <div class="chart-area" style="position: relative; height:60vh; width:100%">
                        <canvas id="controlChart"></canvas>
                    </div>
                @else
                    <div class="alert alert-warning">
                        Tidak ada data yang ditemukan untuk periode dan parameter tersebut.
                    </div>
                @endif
            </div>
        </div>

        @if(isset($chartData) && count($chartData['labels']) > 0)
            <!-- Summary Card -->
            <div class="row">
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Statistik Parameter
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        Mean (µ): {{ number_format($chartData['lines']['mean'], 4) }}
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        SD (σ): {{ number_format(abs($chartData['lines']['mean'] - $chartData['lines']['plus1sd']), 4) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calculator fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Distribusi Hasil
                                    </div>
                                    @php
                                        $inControl = collect($chartData['statuses'])->filter(fn($s) => $s === 'hijau')->count();
                                        $warning = collect($chartData['statuses'])->filter(fn($s) => $s === 'kuning')->count();
                                        $outOfControl = collect($chartData['statuses'])->filter(fn($s) => $s === 'merah')->count();
                                    @endphp
                                    <div class="mb-0 text-gray-800">
                                        <span class="badge bg-success">In-Control: {{ $inControl }}</span>
                                        <span class="badge bg-warning text-dark">Warning: {{ $warning }}</span>
                                        <span class="badge bg-danger">Out-of-Control: {{ $outOfControl }}</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Rules Card -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Aturan Westgard Aktif</h6>
                        </div>
                        <div class="card-body">
                            @if($selectedParameter->westgardRules && $selectedParameter->westgardRules->count() > 0)
                                <ul class="list-group">
                                    @foreach($selectedParameter->westgardRules as $rule)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $rule->nama_aturan ?? $rule->kode_aturan }}
                                            <span class="badge bg-primary rounded-pill">{{ $rule->kode_aturan }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted mb-0">Tidak ada aturan spesifik yang ditetapkan.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="col-md-8 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tabel Control Chart In-house</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-bordered table-striped table-hover mb-0 text-center" style="font-size: 0.85rem;">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Pengujian Ke-</th>
                                            <th class="text-danger" title="Lower Control Limit (-3SD)">LCL</th>
                                            <th class="text-warning" title="Lower Warning Limit (-2SD)">LWL</th>
                                            <th class="text-success" title="Mean - 1SD">µ-1σ</th>
                                            <th class="text-primary font-weight-bold">µ</th>
                                            <th class="text-success" title="Mean + 1SD">µ+1σ</th>
                                            <th class="text-warning" title="Upper Warning Limit (+2SD)">UWL</th>
                                            <th class="text-danger" title="Upper Control Limit (+3SD)">UCL</th>
                                            <th class="bg-primary text-white">Nilai</th>
                                            <th class="bg-dark text-white">Status Akhir</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($hasilList as $index => $hasil)
                                        <tr>
                                            <td>{{ $hasil->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="text-danger">{{ number_format($chartData['lines']['minus3sd'], 4) }}</td>
                                            <td class="text-warning text-dark">{{ number_format($chartData['lines']['minus2sd'], 4) }}</td>
                                            <td class="text-success">{{ number_format($chartData['lines']['minus1sd'], 4) }}</td>
                                            <td class="text-primary font-weight-bold">{{ number_format($chartData['lines']['mean'], 4) }}</td>
                                            <td class="text-success">{{ number_format($chartData['lines']['plus1sd'], 4) }}</td>
                                            <td class="text-warning text-dark">{{ number_format($chartData['lines']['plus2sd'], 4) }}</td>
                                            <td class="text-danger">{{ number_format($chartData['lines']['plus3sd'], 4) }}</td>
                                            <td class="font-weight-bold {{ $hasil->status_berketerimaan == 'outlier' ? 'text-danger' : ($hasil->kode_aturan_dilanggar == '1-2s' ? 'text-warning' : 'text-success') }}">
                                                {{ number_format($hasil->nilai_hasil, 4) }}
                                            </td>
                                            <td>
                                                @if($hasil->override_status)
                                                    <span class="badge {{ $hasil->override_status == 'outlier' ? 'bg-danger' : ($hasil->override_status == 'warning' ? 'bg-warning text-dark' : 'bg-success') }}">
                                                        {{ strtoupper($hasil->override_status) }} 
                                                        @if($hasil->override_kode) ({{ $hasil->override_kode }}) @endif
                                                        <i class="fas fa-user-edit ms-1" title="Di-override Manual"></i>
                                                    </span>
                                                @else
                                                    <span class="badge {{ $hasil->status_berketerimaan == 'outlier' ? 'bg-danger' : ($hasil->kode_aturan_dilanggar == '1-2s' ? 'bg-warning text-dark' : 'bg-success') }}">
                                                        {{ $hasil->status_berketerimaan == 'outlier' ? 'OUTLIER' : 'INLIER' }}
                                                        @if($hasil->kode_aturan_dilanggar) ({{ $hasil->kode_aturan_dilanggar }}) @endif
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-secondary py-0" onclick="openOverrideModal({{ $hasil->hasil_uji_id }}, '{{ $hasil->override_status ?? $hasil->status_berketerimaan }}', '{{ $hasil->override_kode ?? $hasil->kode_aturan_dilanggar }}')" title="Timpa Manual">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>

<!-- Modal Override Westgard -->
<div class="modal fade" id="overrideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="overrideForm" method="POST" action="">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Override Status Westgard</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Fitur ini digunakan jika analis/lab memiliki interpretasi aturan Westgard yang berbeda dari kalkulasi sistem otomatis.</p>
                    
                    <div class="mb-3">
                        <label class="form-label">Status Baru</label>
                        <select class="form-select" name="override_status" id="override_status" required>
                            <option value="inlier">INLIER (Normal)</option>
                            <option value="warning">WARNING (Peringatan)</option>
                            <option value="outlier">OUTLIER (Tolak)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kode Pelanggaran (Opsional)</label>
                        <input type="text" class="form-control" name="override_kode" id="override_kode" placeholder="Misal: 1-2s, 1-3s, 2-2s...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Override</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@if(isset($chartData) && count($chartData['labels']) > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('controlChart').getContext('2d');
        
        // Prepare Data
        const labels = {!! json_encode($chartData['labels']) !!};
        const values = {!! json_encode($chartData['values']) !!};
        const statuses = {!! json_encode($chartData['statuses']) !!};
        const kodeAturan = {!! json_encode($chartData['kodeAturan']) !!};
        
        const lines = {!! json_encode($chartData['lines']) !!};
        
        // Color Mapping
        const pointColors = statuses.map(status => {
            if (status === 'merah') return '#dc3545'; // Danger
            if (status === 'kuning') return '#ffc107'; // Warning
            return '#28a745'; // Success / In-Control
        });
        
        const meanData = labels.map(() => lines.mean);
        const p1sdData = labels.map(() => lines.plus1sd);
        const p2sdData = labels.map(() => lines.plus2sd);
        const p3sdData = labels.map(() => lines.plus3sd);
        const m1sdData = labels.map(() => lines.minus1sd);
        const m2sdData = labels.map(() => lines.minus2sd);
        const m3sdData = labels.map(() => lines.minus3sd);

        const controlChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Nilai Ukur',
                        data: values,
                        borderColor: '#4e73df',
                        backgroundColor: pointColors,
                        pointBackgroundColor: pointColors,
                        pointBorderColor: pointColors,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: false,
                        tension: 0,
                        borderWidth: 2,
                        zIndex: 10
                    },
                    {
                        label: 'Mean',
                        data: meanData,
                        borderColor: '#0d6efd',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    },
                    @if(request('jenis_grafik') != 'crm')
                    {
                        label: '+1 SD',
                        data: p1sdData,
                        borderColor: '#198754',
                        borderWidth: 1,
                        borderDash: [3, 3],
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: '-1 SD',
                        data: m1sdData,
                        borderColor: '#198754',
                        borderWidth: 1,
                        borderDash: [3, 3],
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: '+2 SD (UWL)',
                        data: p2sdData,
                        borderColor: '#fd7e14',
                        borderWidth: 1,
                        borderDash: [4, 4],
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: '-2 SD (LWL)',
                        data: m2sdData,
                        borderColor: '#fd7e14',
                        borderWidth: 1,
                        borderDash: [4, 4],
                        pointRadius: 0,
                        fill: false
                    },
                    @endif
                    {
                        label: '{{ request('jenis_grafik') == 'crm' ? 'Batas Atas Recovery' : '+3 SD (UCL)' }}',
                        data: p3sdData,
                        borderColor: '#dc3545',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: '{{ request('jenis_grafik') == 'crm' ? 'Batas Bawah Recovery' : '-3 SD (LCL)' }}',
                        data: m3sdData,
                        borderColor: '#dc3545',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointRadius: 0,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label === 'Nilai Ukur') {
                                    let idx = context.dataIndex;
                                    let val = context.parsed.y;
                                    let info = 'Nilai: ' + val;
                                    if (kodeAturan[idx]) {
                                        info += ' | Pelanggaran: ' + kodeAturan[idx];
                                    }
                                    return info;
                                }
                                return label + ': ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Tanggal / Urutan'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Nilai'
                        },
                        suggestedMin: lines.minus3sd - (lines.plus1sd - lines.mean),
                        suggestedMax: lines.plus3sd + (lines.plus1sd - lines.mean)
                    }
                }
            }
        });
    });

    function cetakPDF() {
        var canvas = document.getElementById('controlChart');
        if (canvas) {
            // Wait for chart animations to finish, though usually they are done by the time user clicks
            var base64 = canvas.toDataURL('image/png');
            document.getElementById('chart_image').value = base64;
        }
        document.getElementById('formCetak').submit();
    }
    function openOverrideModal(hasilUjiId, currentStatus, currentKode) {
        var modal = new bootstrap.Modal(document.getElementById('overrideModal'));
        
        document.getElementById('overrideForm').action = '/hasil-uji/' + hasilUjiId + '/override';
        
        // Map status to simpler dropdown value if needed, or just set it
        var statusSelect = document.getElementById('override_status');
        if (currentStatus === 'outlier') statusSelect.value = 'outlier';
        else if (currentStatus === 'warning' || currentKode === '1-2s') statusSelect.value = 'warning';
        else statusSelect.value = 'inlier';
        
        document.getElementById('override_kode').value = currentKode || '';
        
        modal.show();
    }
</script>
@endif
@endpush
