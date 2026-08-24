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
                                    <th colspan="4">DISH 1 (Pengujian 1)</th>
                                    <th colspan="4">DISH 2 (Pengujian 2)</th>
                                    <th colspan="3">Absolute Difference (Tol: {{ $selectedParameter->toleransi_duplo ?? 0.5 }})</th>
                                    <th colspan="3" class="bg-primary text-white">Average (% db)</th>
                                    <th rowspan="2">Status Duplo</th>
                                </tr>
                                <tr>
                                    <!-- Dish 1 -->
                                    <th>Weight (mg)</th><th>C %</th><th>H %</th><th>N %</th>
                                    <!-- Dish 2 -->
                                    <th>Weight (mg)</th><th>C %</th><th>H %</th><th>N %</th>
                                    <!-- Abs Diff -->
                                    <th>C</th> <th>H</th> <th>N</th>
                                    <!-- Average -->
                                    <th>C</th> <th>H</th> <th>N</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hasilList as $index => $hasil)
                                    @php
                                        $dm = is_array($hasil->data_mentah) ? $hasil->data_mentah : json_decode($hasil->data_mentah, true);
                                        $tol = $selectedParameter->toleransi_duplo ?? 0.5;
                                    @endphp
                                    <tr>
                                        <td>{{ $hasil->created_at->format('d/m/y') }}</td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $hasil->kegiatan ? $hasil->kegiatan->kode_sampel : '-' }}</td>
                                        
                                        <!-- Dish 1 -->
                                        <td>{{ isset($dm['Weight_D1']) ? number_format($dm['Weight_D1'], 2) : '-' }}</td>
                                        <td>{{ isset($dm['C_D1']) ? number_format($dm['C_D1'], 2) : '-' }}</td>
                                        <td>{{ isset($dm['H_D1']) ? number_format($dm['H_D1'], 2) : '-' }}</td>
                                        <td>{{ isset($dm['N_D1']) ? number_format($dm['N_D1'], 2) : '-' }}</td>
                                        
                                        <!-- Dish 2 -->
                                        <td>{{ isset($dm['Weight_D2']) ? number_format($dm['Weight_D2'], 2) : '-' }}</td>
                                        <td>{{ isset($dm['C_D2']) ? number_format($dm['C_D2'], 2) : '-' }}</td>
                                        <td>{{ isset($dm['H_D2']) ? number_format($dm['H_D2'], 2) : '-' }}</td>
                                        <td>{{ isset($dm['N_D2']) ? number_format($dm['N_D2'], 2) : '-' }}</td>
                                        
                                        <!-- Abs Diff -->
                                        <td class="{{ isset($dm['Abs_C']) && $dm['Abs_C'] > $tol ? 'text-danger fw-bold' : '' }}">{{ isset($dm['Abs_C']) ? number_format($dm['Abs_C'], 2) : '-' }}</td>
                                        <td class="{{ isset($dm['Abs_H']) && $dm['Abs_H'] > $tol ? 'text-danger fw-bold' : '' }}">{{ isset($dm['Abs_H']) ? number_format($dm['Abs_H'], 2) : '-' }}</td>
                                        <td class="{{ isset($dm['Abs_N']) && $dm['Abs_N'] > $tol ? 'text-danger fw-bold' : '' }}">{{ isset($dm['Abs_N']) ? number_format($dm['Abs_N'], 2) : '-' }}</td>
                                        
                                        <!-- Average -->
                                        <td class="fw-bold">{{ isset($dm['Avg_C']) ? number_format($dm['Avg_C'], 2) : '-' }}</td>
                                        <td class="fw-bold">{{ isset($dm['Avg_H']) ? number_format($dm['Avg_H'], 2) : '-' }}</td>
                                        <td class="fw-bold">{{ isset($dm['Avg_N']) ? number_format($dm['Avg_N'], 2) : '-' }}</td>
                                        
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
                                        <td colspan="19">Belum ada data pengujian CHN.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>
    @endif
</div>
@endsection
