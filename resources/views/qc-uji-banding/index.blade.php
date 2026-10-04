@extends('layouts.app')
@section('title', 'Daftar - QC Uji Banding')

@section('content')

<style>
    .btn-corporate-blue {
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
        color: #ffffff !important;
    }
    .btn-corporate-blue:hover, .btn-corporate-blue:focus {
        background-color: #14253e !important;
        border-color: #14253e !important;
        color: #ffffff !important;
    }
    .btn-outline-corporate {
        color: #1b3152 !important;
        border-color: #1b3152 !important;
    }
    .btn-outline-corporate:hover {
        background-color: #1b3152 !important;
        color: #ffffff !important;
    }

    .table-corporate thead th {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-bottom: 2px solid #14253e !important;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding-top: 15px;
        padding-bottom: 15px;
    }
</style>

<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="Uji Banding"></x-qc-breadcrumb>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h2 class="fw-bold text-dark mb-0">
            <i class="fas fa-balance-scale me-2" style="color: #1b3152;"></i>QC Uji Banding
        </h2>
        
        <div class="d-flex flex-column flex-md-row gap-2">
            <a href="{{ route('master.toleransi.index') }}" class="btn btn-outline-corporate rounded-pill px-4 shadow-sm">
                <i class="fas fa-cog me-1"></i> Master Batas Toleransi
            </a>
            <a href="{{ route('qc-uji-banding.create') }}" class="btn btn-corporate-blue rounded-pill px-4 shadow-sm">
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
        <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold" style="color: #1b3152;"><i class="fas fa-list me-2"></i>Data Program Uji Banding</h6>
            <form action="{{ route('qc-uji-banding.index') }}" method="GET" id="searchForm" class="m-0" style="width: 300px;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control border-start-0" name="search" id="searchInput" placeholder="Ketik untuk mencari..." value="{{ request('search') }}">
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-corporate">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Tanggal Terima</th>
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
                            <td class="ps-4 fw-bold text-dark">
                                {{ $prog->tanggal_terima ? $prog->tanggal_terima->format('d/m/Y') : '-' }}
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
                                <!-- @if($menunggu > 0)
                                        <a href="{{ route('qc-uji-banding.evaluasi.form', $prog->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-chart-bar me-1"></i>Input Hasil Vendor
                                        </a>
                                    @endif  -->
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
                                    <a href="{{ route('qc-uji-banding.show', $prog->id) }}" class="btn btn-sm btn-outline-corporate">Detail</a>
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

            <div class="p-3 border-top">
                {{ $programs->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let typingTimer;                
        const doneTypingInterval = 500; // waktu tunggu setengah detik
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

