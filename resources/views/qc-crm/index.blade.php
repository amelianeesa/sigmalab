@extends('layouts.app')
@section('title', 'Daftar - QC CRM')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="CRM" />

    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="fas fa-certificate text-purple me-2"></i>QC CRM</h2>
        <div>
            <a href="{{ route('crm-katalog.index') }}" class="btn btn-outline-secondary shadow-sm me-2"><i class="fas fa-box me-1"></i> Master Botol CRM</a>
            <a href="{{ route('qc-crm.create') }}" class="btn btn-primary shadow-sm"><i class="fas fa-plus me-1"></i> Mulai Pengujian CRM</a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Tanggal Uji</th>
                            <th>Botol CRM (Lot)</th>
                            <th>Parameter</th>
                            <th>Nilai Akhir</th>
                            <th>True Value ± U</th>
                            <th>Analis</th>
                            <th>Status Evaluasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kegiatanList as $log)
                        <tr>
                            <td class="ps-4">{{ $log->tanggal_uji->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-primary fs-6">{{ $log->crmKatalog->nomor_lot ?? '-' }}</span><br>
                                <small class="text-muted">{{ $log->crmKatalog->nama_produk ?? '' }}</small>
                            </td>
                            <td><span class="badge bg-secondary">{{ $log->parameterUji->nama_parameter ?? '-' }}</span></td>
                            <td class="fw-bold">{{ $log->nilai_akhir }}</td>
                            <td class="text-muted">{{ $log->cert_value }} ± {{ $log->cert_u }}</td>
                            <td>{{ $log->analis->nama ?? 'Unknown' }}</td>
                            <td>
                                @if($log->status_evaluasi === 'inlier')
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Inlier</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Outlier</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data pengujian QC CRM.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
