@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="In-House" />

    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="fas fa-flask text-primary me-2"></i>QC In-House Standard (CRM)</h2>
        <a href="{{ route('qc-inhouse.create') }}" class="btn btn-primary shadow-sm"><i class="fas fa-plus me-1"></i> Buat Sampel Baru</a>
    </div>

    <!-- Filters could be placed here -->

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Kode Batch</th>
                            <th>Nama Sampel</th>
                            <th>Jenis / Metode</th>
                            <th>Jml Parameter</th>
                            <th>Pembuat</th>
                            <th>Tanggal Buat</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sampels as $s)
                            <tr>
                                <td class="ps-4 fw-bold">{{ $s->kode_batch ?? '-' }}</td>
                                <td>{{ $s->nama_sampel }}</td>
                                <td>
                                    <span class="d-block text-uppercase small fw-bold">{{ str_replace('_', ' ', $s->jenis_batubara) }}</span>
                                    <span class="d-block text-muted small">{{ strtoupper($s->metode_acuan) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark rounded-pill px-3">{{ $s->parameters->count() }} Parameter</span>
                                </td>
                                <td>{{ $s->pembuat->name ?? '-' }}</td>
                                <td>{{ $s->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $s->status_color }} py-1 px-2">{{ $s->status_label }}</span>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('qc-inhouse.show', $s->sampel_inhouse_id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">Belum ada data sampel In-House.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($sampels->hasPages())
            <div class="card-footer bg-white pt-3 pb-2">
                {{ $sampels->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
