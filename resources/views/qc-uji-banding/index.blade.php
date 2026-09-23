@extends('layouts.app')
@section('title', 'Daftar - QC Uji Banding')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="Uji Banding"></x-qc-breadcrumb>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="fas fa-balance-scale text-primary me-2"></i>QC Uji Banding</h2>
        
        <!-- Bungkus tombol agar posisinya sejajar di kanan -->
        <div>
            <a href="{{ route('master.toleransi.index') }}" class="btn btn-outline-secondary shadow-sm me-2">
                <i class="fas fa-cog me-1"></i> Master Batas Toleransi
            </a>
            <a href="{{ route('qc-uji-banding.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-1"></i> Input Uji Banding Baru
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success shadow-sm border-0">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Tgl Terima & Uji</th>
                            <th>Nama Program</th>
                            <th>Penyelenggara</th>
                            <th>Kode Sampel</th>
                            <th>Status Parameter</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programs as $prog)
                        <tr>
                            <td class="ps-4">
                                <span class="d-block text-muted small">Terima: {{ $prog->tanggal_terima ? $prog->tanggal_terima->format('d/m/Y') : '-' }}</span>
                                <span class="fw-bold">Uji: {{ $prog->tanggal_uji ? $prog->tanggal_uji->format('d/m/Y') : '-' }}</span>
                            </td>
                            <td class="fw-bold">
                                {{ $prog->nama_program }}
                                @if(isset($prog->status) && $prog->status === 'draft')
                                    <span class="badge bg-warning text-dark ms-2">DRAFT</span>
                                @endif
                            </td>
                            <td>{{ $prog->penyelenggara }}</td>
                            <td><span class="badge bg-secondary">{{ $prog->kode_sampel }}</span></td>
                            <td>
                                @php
                                    $menunggu = $prog->parameters->where('status_evaluasi', 'menunggu')->count();
                                    $inlier = $prog->parameters->where('status_evaluasi', 'inlier')->count();
                                    $outlier = $prog->parameters->where('status_evaluasi', 'outlier')->count();
                                    $warning = $prog->parameters->where('status_evaluasi', 'warning')->count();
                                @endphp
                                @if($menunggu > 0)
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> {{ $menunggu }} Menunggu Vendor</span>
                                @endif
                                @if($inlier > 0)
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> {{ $inlier }} Inlier</span>
                                @endif
                                @if($warning > 0)
                                    <span class="badge bg-info"><i class="fas fa-exclamation-circle"></i> {{ $warning }} Warning</span>
                                @endif
                                @if($outlier > 0)
                                    <span class="badge bg-danger"><i class="fas fa-times-circle"></i> {{ $outlier }} Outlier</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if(isset($prog->status) && $prog->status === 'draft')
                                    <a href="{{ route('qc-uji-banding.create', ['draft_id' => $prog->id]) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit me-1"></i>Lanjutkan Draft</a>
                                @else
                                    <a href="{{ route('qc-uji-banding.show', $prog->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                    @if($menunggu > 0)
                                        <a href="{{ route('qc-uji-banding.evaluasi.form', $prog->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-chart-bar me-1"></i>Input Hasil Vendor
                                        </a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data QC Uji Banding.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

