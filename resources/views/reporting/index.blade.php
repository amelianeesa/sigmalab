@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active" aria-current="page">Reporting</li>
        </ol>
    </nav>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reporting & Statistik</h1>
    </div>

    <!-- Cards Row -->
    <div class="row">
        <!-- Total Kegiatan Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Kegiatan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalKegiatan ?? 0 }}</div>
                            <div class="mt-2 text-sm text-muted">
                                <small>Draft: {{ $kegiatanPerStatus['draft'] ?? 0 }}</small> | 
                                <small>Berjalan: {{ $kegiatanPerStatus['berjalan'] ?? 0 }}</small><br>
                                <small>Selesai: {{ $kegiatanPerStatus['selesai'] ?? 0 }}</small> | 
                                <small>Batal: {{ $kegiatanPerStatus['dibatalkan'] ?? 0 }}</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Hasil Uji Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Hasil Uji</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalHasilUji ?? 0 }}</div>
                            <div class="mt-2 text-sm">
                                <span class="text-success"><i class="fas fa-check-circle"></i> Inlier: {{ $totalInlier ?? 0 }}</span><br>
                                <span class="text-danger"><i class="fas fa-exclamation-circle"></i> Outlier: {{ $totalOutlier ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-flask fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tindak Lanjut Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Tindak Lanjut</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTindakLanjut ?? 0 }}</div>
                            <div class="mt-2 text-sm text-muted">
                                <small>Belum: {{ $tindakLanjutPerStatus['belum_ditindaklanjuti'] ?? 0 }}</small><br>
                                <small>Investigasi: {{ $tindakLanjutPerStatus['dalam_investigasi'] ?? 0 }}</small><br>
                                <small>Selesai: {{ $tindakLanjutPerStatus['selesai'] ?? 0 }}</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rasio Keberterimaan Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Rasio Keberterimaan (Inlier)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @php
                                    $rasio = ($totalHasilUji ?? 0 > 0) ? round((($totalInlier ?? 0) / $totalHasilUji) * 100, 2) : 0;
                                @endphp
                                {{ $rasio }}%
                            </div>
                            <div class="mt-2">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $rasio }}%" aria-valuenow="{{ $rasio }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
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

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 5 Parameter Paling Sering Outlier</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Parameter Uji</th>
                                    <th>Satuan</th>
                                    <th>Jumlah Outlier</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topOutlier ?? [] as $index => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->parameterUji->nama_parameter ?? '-' }}</td>
                                    <td>{{ $item->parameterUji->satuan ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-danger">{{ $item->jumlah_outlier }} kasus</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <div class="py-3">
                                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                            <p class="mb-0">Belum ada data outlier saat ini.</p>
                                        </div>
                                    </td>
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
@endsection
