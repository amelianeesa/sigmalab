@extends('layouts.app')
@section('title', 'Daftar - QC Uji Banding')

@section('content')
@include('qc-uji-banding.partials._style')

<div class="container-fluid qc-page pb-4">
    <x-qc-breadcrumb active="Uji Banding"></x-qc-breadcrumb>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="qc-title"><i class="fas fa-balance-scale me-1" style="color: #1b3152;"></i> QC Uji Banding</h5>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('master.toleransi.index') }}" class="btn btn-outline-corporate btn-sm py-1 px-3 shadow-sm fw-semibold">
                <i class="fas fa-cog me-1"></i> Master Batas Toleransi
            </a>
            <a href="{{ route('qc-uji-banding.create') }}" class="btn btn-corporate-blue btn-sm py-1 px-3 shadow-sm fw-semibold">
                <i class="fas fa-plus me-1"></i> Input Uji Banding Baru
            </a>
        </div>
    </div>

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="m-0 fw-bold" style="color: #1b3152;"><i class="fas fa-list me-1"></i> Data Program Uji Banding</h6>
            <form action="{{ route('qc-uji-banding.index') }}" method="GET" id="searchForm" class="m-0 qc-search">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control form-control-sm border-start-0" name="search" id="searchInput" placeholder="Ketik untuk mencari..." value="{{ request('search') }}" autocomplete="off">
                </div>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0 qc-table-min">
                    <thead>
                        <tr class="text-center">
                            <th>Tgl Terima & Uji</th>
                            <th>Nama Program</th>
                            <th>Penyelenggara</th>
                            <th>Kode Sampel</th>
                            <th>Status Parameter</th>
                            <th style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programs as $prog)
                        <tr>
                            <td>
                                <span class="d-block text-muted" style="font-size: 0.68rem;">Terima: {{ $prog->tanggal_terima ? $prog->tanggal_terima->format('d/m/Y') : '-' }}</span>
                                <span class="fw-bold">Uji: {{ $prog->tanggal_uji ? $prog->tanggal_uji->format('d/m/Y') : '-' }}</span>
                            </td>
                            <td class="fw-bold">
                                {{ $prog->nama_program }}
                                @if(isset($prog->status) && $prog->status === 'draft')
                                    <span class="badge bg-warning text-dark ms-1">DRAFT</span>
                                @endif
                            </td>
                            <td>{{ $prog->penyelenggara }}</td>
                            <td class="text-center"><span class="badge bg-secondary">{{ $prog->kode_sampel }}</span></td>
                            <td>
                                @php
                                    $menunggu = $prog->parameters->where('status_evaluasi', 'menunggu')->count();
                                    $inlier = $prog->parameters->where('status_evaluasi', 'inlier')->count();
                                    $outlier = $prog->parameters->where('status_evaluasi', 'outlier')->count();
                                    $warning = $prog->parameters->where('status_evaluasi', 'warning')->count();
                                @endphp
                                <div class="d-flex flex-wrap gap-1">
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
                                </div>
                            </td>
                            <td class="text-center text-nowrap">
                                <div class="d-inline-flex align-items-center gap-1">
                                    @if(isset($prog->status) && $prog->status === 'draft')
                                        <a href="{{ route('qc-uji-banding.create', ['draft_id' => $prog->id]) }}" class="btn btn-warning btn-sm qc-action-btn shadow-sm" title="Lanjutkan Draft" aria-label="Lanjutkan Draft"><i class="fas fa-edit"></i></a>
                                    @else
                                        <a href="{{ route('qc-uji-banding.show', $prog->id) }}" class="btn btn-corporate-blue btn-sm qc-action-btn shadow-sm" title="Detail" aria-label="Detail"><i class="fas fa-eye"></i></a>
                                        @if($menunggu > 0)
                                            <a href="{{ route('qc-uji-banding.evaluasi.form', $prog->id) }}" class="btn btn-warning btn-sm qc-action-btn shadow-sm" title="Input Hasil Vendor" aria-label="Input Hasil Vendor"><i class="fas fa-chart-bar"></i></a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Belum ada data QC Uji Banding.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $programs->appends(['search' => request('search')])->links('vendor.pagination.custom', ['size' => 'sm']) }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let typingTimer;
        const doneTypingInterval = 500;
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');
        if(searchInput) {
            searchInput.addEventListener('keyup', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    searchForm.submit();
                }, doneTypingInterval);
            });
            searchInput.addEventListener('search', function () {
                searchForm.submit();
            });
            if(searchInput.value) {
                searchInput.focus();
                const val = searchInput.value;
                searchInput.value = '';
                searchInput.value = val;
            }
        }
    });
</script>
@endsection