@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="In-House">
        @if($activeBatch)
            <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $activeBatch->sampel_inhouse_id) }}" class="text-decoration-none">{{ $activeBatch->kode_batch }}</a></li>
        @endif
        <li class="breadcrumb-item active">Pengujian Harian QC</li>
    </x-qc-breadcrumb>
    <h1 class="mb-4 fw-bold text-dark">
        <i class="fas fa-chart-line text-danger me-2"></i>Pengujian Harian QC
    </h1>

    @if(!$activeBatch)
    <div class="alert alert-warning border-0 shadow-sm">
        <i class="fas fa-exclamation-triangle me-2"></i> Belum ada Batch QC In-House yang berstatus <strong>Aktif</strong>. Silakan selesaikan proses Uji Stabilitas terlebih dahulu.
    </div>
    @else
    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body bg-light rounded d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold mb-1">Batch Aktif: {{ $activeBatch->kode_batch }}</h5>
                <p class="text-muted mb-0 small">Masa Berlaku: {{ $activeBatch->tanggal_dibuat ? \Carbon\Carbon::parse($activeBatch->tanggal_dibuat)->format('d M Y') : '-' }} s/d Selesai</p>
            </div>
            <div>
                <a href="{{ route('qc-harian.create') }}" class="btn btn-danger rounded-pill px-4 shadow-sm">
                    <i class="fas fa-plus me-1"></i> Input Data Harian
                </a>
            </div>
        </div>
    </div>

    <!-- Parameter Status Table (Redesigned as requested) -->
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-list me-2"></i>Daftar Parameter Uji Harian</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Nama Parameter</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center">Nilai Acuan (Mean)</th>
                            <th class="text-center">Batas Peringatan (± 2SD)</th>
                            <th class="text-center">Metode/Kriteria</th>
                            <th class="text-center">Status Harian</th>
                            <th class="text-center" style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parameters as $idx => $param)
                            @php
                                $isLocked = \App\Models\QcHarian::where('sampel_inhouse_id', $activeBatch->id)
                                    ->where('parameter_uji_id', $param->parameter_uji_id)
                                    ->where('status_evaluasi', 'outlier')
                                    ->where('status_investigasi', 'menunggu_investigasi')
                                    ->exists();
                            @endphp
                            <tr>
                                <td class="text-center">{{ $idx + 1 }}</td>
                                <td class="fw-bold">{{ strtoupper($param->parameterUji->nama_parameter) }}</td>
                                <td class="text-center">{{ $param->parameterUji->satuan ?? '-' }}</td>
                                <td class="text-center">{{ number_format($param->parameterUji->mean, 4) }}</td>
                                <td class="text-center">
                                    {{ number_format($param->parameterUji->mean - (2 * $param->parameterUji->sd), 4) }} - 
                                    {{ number_format($param->parameterUji->mean + (2 * $param->parameterUji->sd), 4) }}
                                </td>
                                <td class="text-center">{{ $param->parameterUji->metode_kriteria ?? '-' }}</td>
                                <td class="text-center">
                                    @if($isLocked)
                                        <span class="badge bg-danger"><i class="fas fa-lock me-1"></i> OOC (LOCKED)</span>
                                    @else
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> AMAN</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($isLocked)
                                        <a href="#riwayat-table" class="btn btn-sm btn-outline-danger" title="Lakukan Investigasi" onclick="alert('Silakan cek tabel riwayat di bawah untuk mengisi form investigasi.')"><i class="fas fa-search me-1"></i> Investigasi</a>
                                    @else
                                        <a href="{{ route('qc-harian.chart', $param->parameter_uji_id) }}" class="btn btn-secondary btn-sm" title="Control Chart"><i class="fas fa-chart-line me-1"></i> Control Chart</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada parameter yang stabil.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat 50 Data Terakhir -->
    <h4 class="mb-3 fw-bold"><i class="fas fa-history me-2 text-secondary"></i>Riwayat Pengujian Terakhir</h4>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Parameter</th>
                            <th>Nilai D1</th>
                            <th>Nilai D2</th>
                            <th>Nilai Akhir</th>
                            <th>Status (Westgard)</th>
                            <th>Analis</th>
                            <th class="pe-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogs as $log)
                        <tr>
                            <td class="ps-4">{{ \Carbon\Carbon::parse($log->tanggal_uji)->format('d M Y') }}</td>
                            <td class="fw-bold">{{ strtoupper($log->parameterUji->nama_parameter) }}</td>
                            <td>{{ number_format($log->nilai_d1, 2) }}</td>
                            <td>{{ $log->nilai_d2 ? number_format($log->nilai_d2, 2) : '-' }}</td>
                            <td class="fw-bold">{{ number_format($log->nilai_akhir, 2) }}</td>
                            <td>
                                @if($log->status_evaluasi === 'inlier')
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i> Inlier</span>
                                @elseif($log->status_evaluasi === 'warning')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i> Warning</span>
                                    <small class="d-block text-muted mt-1">{{ $log->pelanggaran_rule }}</small>
                                @elseif($log->status_evaluasi === 'outlier')
                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i> Outlier</span>
                                    <small class="d-block text-danger mt-1 fw-bold">{{ $log->pelanggaran_rule }}</small>
                                @endif
                            </td>
                            <td>{{ $log->analis ? $log->analis->name : '-' }}</td>
                            <td class="pe-4 text-center">
                                @if($log->status_evaluasi === 'outlier')
                                    @if($log->status_investigasi === 'menunggu_investigasi')
                                        <a href="{{ route('qc-harian.investigasi', $log->id) }}" class="btn btn-sm btn-danger pulse-button"><i class="fas fa-edit"></i> Isi Investigasi</a>
                                    @else
                                        <button class="btn btn-sm btn-outline-success" disabled><i class="fas fa-check-double"></i> Investigasi Selesai</button>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Belum ada data pengujian harian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

<style>
    .pulse-button {
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
</style>
@endsection
