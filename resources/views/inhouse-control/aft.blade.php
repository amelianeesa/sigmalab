@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
            <li class="breadcrumb-item"><a href="{{ route('hasil-uji.inhouse-control') }}" class="text-decoration-none">Inhouse Control</a></li>
            @if($selectedParameter)
                <li class="breadcrumb-item active" aria-current="page">{{ $selectedParameter->nama_parameter }}</li>
            @endif
        </ol>
    </nav>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Inhouse Control - {{ $selectedParameter->nama_parameter }}</h1>
    </div>

    <!-- Parameter Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('hasil-uji.inhouse-control') }}" method="GET" class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Parameter Uji</label>
                    <select name="parameter_uji_id" class="form-select" required>
                        <option value="">-- Pilih Parameter --</option>
                        @foreach($parameterList as $p)
                            <option value="{{ $p->parameter_uji_id }}" {{ request('parameter_uji_id') == $p->parameter_uji_id ? 'selected' : '' }}>
                                {{ $p->nama_parameter }} ({{ $p->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Tampilkan</button>
                    @if(request('parameter_uji_id'))
                        <a href="{{ route('hasil-uji.inhouse-control.cetak', request()->all()) }}" class="btn btn-danger" target="_blank">
                            <i class="fas fa-file-pdf"></i> Cetak
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if(isset($chartData) && $selectedParameter)
    <div class="row">
        <!-- Tabel Data (Raw Data Logger) -->
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Raw Data Logger: {{ $selectedParameter->nama_parameter }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center align-middle" style="font-size: 0.75rem;">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2">Tanggal</th>
                                    <th rowspan="2">Uji Ke-</th>
                                    <th rowspan="2">Kode Sampel</th>
                                    <th rowspan="2">Atmosfer</th>
                                    <th colspan="4">DISH 1 (Pengujian 1)</th>
                                    <th colspan="4">DISH 2 (Pengujian 2)</th>
                                    <th colspan="4">Absolute Difference (Tol: {{ $selectedParameter->toleransi_duplo ?? 50 }})</th>
                                    <th colspan="4" class="bg-primary text-white">Average (℃)</th>
                                    <th rowspan="2">Status Duplo</th>
                                </tr>
                                <tr>
                                    <!-- Dish 1 -->
                                    <th>IDT</th> <th>ST</th> <th>HT</th> <th>FT</th>
                                    <!-- Dish 2 -->
                                    <th>IDT</th> <th>ST</th> <th>HT</th> <th>FT</th>
                                    <!-- Abs Diff -->
                                    <th>IDT</th> <th>ST</th> <th>HT</th> <th>FT</th>
                                    <!-- Average -->
                                    <th>IDT</th> <th>ST</th> <th>HT</th> <th>FT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hasilList as $index => $hasil)
                                    @php
                                        $dm = is_array($hasil->data_mentah) ? $hasil->data_mentah : json_decode($hasil->data_mentah, true);
                                        $tol = $selectedParameter->toleransi_duplo ?? 50.0;
                                    @endphp
                                    <tr>
                                        <td>{{ $hasil->created_at->format('d/m/y') }}</td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $hasil->kegiatan ? $hasil->kegiatan->kode_sampel : '-' }}</td>
                                        <td>{{ $dm['Atmosphere'] ?? '-' }}</td>
                                        
                                        <!-- Dish 1 -->
                                        <td>{{ isset($dm['IDT_D1']) ? number_format($dm['IDT_D1'], 1) : '-' }}</td>
                                        <td>{{ isset($dm['ST_D1']) ? number_format($dm['ST_D1'], 1) : '-' }}</td>
                                        <td>{{ isset($dm['HT_D1']) ? number_format($dm['HT_D1'], 1) : '-' }}</td>
                                        <td>{{ isset($dm['FT_D1']) ? number_format($dm['FT_D1'], 1) : '-' }}</td>
                                        
                                        <!-- Dish 2 -->
                                        <td>{{ isset($dm['IDT_D2']) ? number_format($dm['IDT_D2'], 1) : '-' }}</td>
                                        <td>{{ isset($dm['ST_D2']) ? number_format($dm['ST_D2'], 1) : '-' }}</td>
                                        <td>{{ isset($dm['HT_D2']) ? number_format($dm['HT_D2'], 1) : '-' }}</td>
                                        <td>{{ isset($dm['FT_D2']) ? number_format($dm['FT_D2'], 1) : '-' }}</td>
                                        
                                        <!-- Abs Diff -->
                                        <td class="{{ isset($dm['Abs_IDT']) && $dm['Abs_IDT'] > $tol ? 'text-danger fw-bold' : '' }}">{{ isset($dm['Abs_IDT']) ? number_format($dm['Abs_IDT'], 1) : '-' }}</td>
                                        <td class="{{ isset($dm['Abs_ST']) && $dm['Abs_ST'] > $tol ? 'text-danger fw-bold' : '' }}">{{ isset($dm['Abs_ST']) ? number_format($dm['Abs_ST'], 1) : '-' }}</td>
                                        <td class="{{ isset($dm['Abs_HT']) && $dm['Abs_HT'] > $tol ? 'text-danger fw-bold' : '' }}">{{ isset($dm['Abs_HT']) ? number_format($dm['Abs_HT'], 1) : '-' }}</td>
                                        <td class="{{ isset($dm['Abs_FT']) && $dm['Abs_FT'] > $tol ? 'text-danger fw-bold' : '' }}">{{ isset($dm['Abs_FT']) ? number_format($dm['Abs_FT'], 1) : '-' }}</td>
                                        
                                        <!-- Average -->
                                        <td class="fw-bold">{{ isset($dm['Avg_IDT']) ? number_format($dm['Avg_IDT'], 1) : '-' }}</td>
                                        <td class="fw-bold">{{ isset($dm['Avg_ST']) ? number_format($dm['Avg_ST'], 1) : '-' }}</td>
                                        <td class="fw-bold">{{ isset($dm['Avg_HT']) ? number_format($dm['Avg_HT'], 1) : '-' }}</td>
                                        <td class="fw-bold">{{ isset($dm['Avg_FT']) ? number_format($dm['Avg_FT'], 1) : '-' }}</td>
                                        
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
                                        <td colspan="22">Belum ada data pengujian AFT.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($chartData['aft']))
        <div class="col-12 mb-4">
            <h5 class="font-weight-bold text-primary border-bottom pb-2 mb-4">Control Charts (IDT, ST, HT, FT)</h5>
            <div class="row">
                @php $subParams = ['IDT', 'ST', 'HT', 'FT']; @endphp
                @foreach($subParams as $sub)
                <!-- Card for each sub parameter -->
                <div class="col-xl-6 col-lg-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary text-center">Grafik Control Chart: {{ $sub }} (℃)</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive mb-3">
                                <table class="table table-bordered table-sm text-center" style="font-size: 0.7rem;">
                                    <thead class="table-dark sticky-top">
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
                                                    <td class="fw-bold">{{ $h->created_at->format('d/m/y') }}</td>
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
                            <div style="height: 250px; width: 100%;">
                                <canvas id="canvas_{{ $sub }}"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
    @include('inhouse-control.partials.override-modal')
</div>
@endsection

@if(isset($chartData['aft']))
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const labels = @json($chartData['labels']);
        const aftData = @json($chartData['aft']);
        
        ['IDT', 'ST', 'HT', 'FT'].forEach(function(sub) {
            if(!aftData[sub]) return;
            const ctx = document.getElementById('canvas_' + sub).getContext('2d');
            const d = aftData[sub];
            
            const lineMean = Array(labels.length).fill(d.stats.mean);
            const lineUCL = Array(labels.length).fill(d.stats.plus3sd);
            const lineLCL = Array(labels.length).fill(d.stats.minus3sd);
            const lineUWL = Array(labels.length).fill(d.stats.plus2sd);
            const lineLWL = Array(labels.length).fill(d.stats.minus2sd);
            
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
                            title: { display: true, text: 'Suhu (℃)' },
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
