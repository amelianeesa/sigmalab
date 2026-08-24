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
                        <table class="table table-bordered table-sm text-center" style="font-size: 0.75rem;">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2" class="align-middle">Tanggal</th>
                                    <th rowspan="2" class="align-middle">Uji Ke-</th>
                                    <th rowspan="2" class="align-middle">Kode Sampel</th>
                                    <th colspan="7">DISH 1 (Pengujian 1)</th>
                                    <th colspan="7">DISH 2 (Pengujian 2)</th>
                                    <th rowspan="2" class="align-middle">TS %</th>
                                    <th rowspan="2" class="align-middle">Abs. Diff<br><small>(Tol: {{ $selectedParameter->toleransi_duplo ?? 50.0 }})</small></th>
                                    <th rowspan="2" class="align-middle">Avg (adb)</th>
                                    <th rowspan="2" class="align-middle">IM %</th>
                                    <th rowspan="2" class="align-middle bg-primary text-white">Avg (db)</th>
                                </tr>
                                <tr>
                                    <!-- Dish 1 -->
                                    <th>Vessel</th>
                                    <th>Call ID</th>
                                    <th>Massa (g)</th>
                                    <th>Primary</th>
                                    <th>Ee</th>
                                    <th>Titrant</th>
                                    <th>Final (adb)</th>
                                    <!-- Dish 2 -->
                                    <th>Vessel</th>
                                    <th>Call ID</th>
                                    <th>Massa (g)</th>
                                    <th>Primary</th>
                                    <th>Ee</th>
                                    <th>Titrant</th>
                                    <th>Final (adb)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hasilList as $index => $hasil)
                                    @php
                                        $dm = is_array($hasil->data_mentah) ? $hasil->data_mentah : json_decode($hasil->data_mentah, true);
                                    @endphp
                                    <tr>
                                        <td>{{ $hasil->created_at->format('d/m/y') }}</td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $hasil->kegiatan ? $hasil->kegiatan->kode_sampel : '-' }}</td>
                                        <!-- Dish 1 -->
                                        <td>{{ $dm['Vessel_D1'] ?? '-' }}</td>
                                        <td>{{ $dm['Call_ID_D1'] ?? '-' }}</td>
                                        <td>{{ isset($dm['Massa_D1']) ? number_format($dm['Massa_D1'], 4) : '-' }}</td>
                                        <td>{{ isset($dm['Primary_D1']) ? number_format($dm['Primary_D1'], 2) : '-' }}</td>
                                        <td>{{ isset($dm['Ee_D1']) ? number_format($dm['Ee_D1'], 2) : '-' }}</td>
                                        <td>{{ isset($dm['Titrant_D1']) ? number_format($dm['Titrant_D1'], 1) : '-' }}</td>
                                        <td class="fw-bold">{{ isset($dm['Final_adb_D1']) ? number_format($dm['Final_adb_D1'], 2) : '-' }}</td>
                                        <!-- Dish 2 -->
                                        <td>{{ $dm['Vessel_D2'] ?? '-' }}</td>
                                        <td>{{ $dm['Call_ID_D2'] ?? '-' }}</td>
                                        <td>{{ isset($dm['Massa_D2']) ? number_format($dm['Massa_D2'], 4) : '-' }}</td>
                                        <td>{{ isset($dm['Primary_D2']) ? number_format($dm['Primary_D2'], 2) : '-' }}</td>
                                        <td>{{ isset($dm['Ee_D2']) ? number_format($dm['Ee_D2'], 2) : '-' }}</td>
                                        <td>{{ isset($dm['Titrant_D2']) ? number_format($dm['Titrant_D2'], 1) : '-' }}</td>
                                        <td class="fw-bold">{{ isset($dm['Final_adb_D2']) ? number_format($dm['Final_adb_D2'], 2) : '-' }}</td>
                                        
                                        <!-- Dependencies -->
                                        <td>{{ isset($dm['Total Sulfur (TS)']) ? number_format($dm['Total Sulfur (TS)'], 2) : '-' }}</td>
                                        
                                        <!-- Calculation Results -->
                                        <td class="{{ isset($dm['Absolute_Diff']) && $dm['Absolute_Diff'] > ($selectedParameter->toleransi_duplo ?? 50) ? 'text-danger fw-bold' : '' }}">
                                            {{ isset($dm['Absolute_Diff']) ? number_format($dm['Absolute_Diff'], 2) : '-' }}
                                        </td>
                                        <td>{{ isset($dm['Average_adb']) ? number_format($dm['Average_adb'], 2) : '-' }}</td>
                                        <td>{{ isset($dm['IM']) && is_numeric($dm['IM']) ? number_format($dm['IM'], 2) : '-' }}</td>
                                        
                                        <td class="fw-bold bg-light">
                                            @if($hasil->status_berketerimaan === 'pending')
                                                <span class="text-warning">PENDING (IM/TS)</span>
                                            @elseif($hasil->status_berketerimaan === 'gagal_duplo')
                                                <span class="text-danger"><del>{{ number_format($hasil->nilai_hasil, 2) }}</del><br><small>RE-TEST</small></span>
                                            @else
                                                <span class="text-success">{{ number_format($hasil->nilai_hasil, 2) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="21">Belum ada data pengujian CV.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Statistik & Grafik Levy-Jennings -->
        @if(isset($chartData))
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tabel Control Chart: Inhouse {{ $selectedParameter->nama_parameter }} (%db)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm text-center" style="font-size: 0.85rem;">
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

                    <h6 class="font-weight-bold text-center mt-5 mb-3">Grafik Control Chart (Levy-Jennings) - {{ $selectedParameter->nama_parameter }}</h6>
                    <div style="height: 400px; width: 100%;">
                        <canvas id="controlChartCanvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
    @include('inhouse-control.partials.override-modal')
</div>
@endsection

@if(isset($chartData))
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('controlChartCanvas').getContext('2d');
        const data = @json($chartData);
        
        // Buat array konstan untuk garis batas
        const lineMean = Array(data.labels.length).fill(data.stats.mean);
        const lineUCL = Array(data.labels.length).fill(data.stats.plus3sd);
        const lineLCL = Array(data.labels.length).fill(data.stats.minus3sd);
        const lineUWL = Array(data.labels.length).fill(data.stats.plus2sd);
        const lineLWL = Array(data.labels.length).fill(data.stats.minus2sd);
        
        // Warna titik berdasarkan status
        const pointColors = data.statuses.map(status => {
            if (status === 'merah') return 'red';
            if (status === 'kuning') return 'orange';
            return 'green';
        });

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
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
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            afterLabel: function(context) {
                                const idx = context.dataIndex;
                                const aturan = data.kodeAturan[idx];
                                return aturan ? 'Pelanggaran: ' + aturan : '';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        title: { display: true, text: 'Calorific Value (cal/g, db)' },
                        suggestedMax: data.stats.plus3sd + data.stats.sd,
                        suggestedMin: data.stats.minus3sd - data.stats.sd,
                    },
                    x: {
                        title: { display: true, text: 'Tanggal Pengujian' }
                    }
                }
            }
        });
    });
</script>
@endpush
@endif
