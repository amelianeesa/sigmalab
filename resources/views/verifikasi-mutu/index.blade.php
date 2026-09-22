@extends('layouts.app')

@section('content')
<style>
    .dashboard-container {
        padding: 2px 20px !important;
    }
    .card-body {
        padding: 12px !important;
    }
    .table th, .table td {
        padding: 6px 10px !important;
        vertical-align: middle !important;
        font-size: 0.75rem !important;
    }
    .table thead th {
        font-size: 0.75rem !important;
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-color: #ffffff !important;
    }
    .table-bordered > :not(caption) > * > * {
        border-color: #dee2e6;
    }
    .btn-corporate-blue {
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
        color: #ffffff !important;
    }
    .btn-corporate-blue:hover, 
    .btn-corporate-blue:focus, 
    .btn-corporate-blue:active {
        background-color: #14253e !important;
        border-color: #14253e !important;
        color: #ffffff !important;
    }
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.08) !important;
    }
</style>

<div class="container-fluid dashboard-container">
    <!-- Breadcrumb di Paling Atas -->
    <div class="mb-2 mt-2">

    </div>

    <!-- Judul & Tombol Master Parameter Uji Sejajar di Kanan -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Portal Verifikasi Mutu (QC)</h4>
            <p class="text-muted small mb-0" style="font-size: 0.72rem;">Silakan pilih pilar Quality Control yang ingin Anda kelola.</p>
        </div>
        <a href="{{ route('parameter-uji.index') }}" class="btn btn-outline-secondary btn-sm py-1 shadow-sm" style="font-size: 0.72rem;">
            <i class="fas fa-cogs me-1"></i> Master Parameter Uji
        </a>
    </div>

    <!-- 3 Pilar QC Card Ringkas -->
    <div class="row g-3 mb-4">
        <!-- QC In-House -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 hover-lift" style="transition: transform 0.2s;">
                <div class="card-body text-center p-3">
                    <div class="rounded-circle d-inline-flex p-3 mb-2" style="background-color: rgba(27, 49, 82, 0.08);">
                        <i class="fas fa-vial fa-2x" style="color: #1b3152;"></i>
                    </div>
                    <h6 class="card-title fw-bold mb-1" style="font-size: 0.9rem;">QC In-House</h6>
                    <p class="card-text text-muted mb-3" style="font-size: 0.68rem;">Penyiapan Uji Homogenitas, Stabilitas, & Target dari sampel internal.</p>
                    <a href="{{ route('qc-inhouse.index') }}" class="btn btn-corporate-blue btn-sm rounded-pill px-3 py-1 shadow-sm" style="font-size: 0.72rem;">Masuk <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                <div class="card-footer bg-white border-top-0 text-center pb-2 pt-0">
                    <span class="badge bg-light border" style="font-size: 0.65rem; color: #1b3152; border-color: #1b3152 !important;">{{ $inhouseCount ?? 0 }} Sampel Aktif</span>
                </div>
            </div>
        </div>

        <!-- QC CRM -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 hover-lift" style="transition: transform 0.2s;">
                <div class="card-body text-center p-3">
                    <div class="rounded-circle d-inline-flex p-3 mb-2" style="background-color: rgba(27, 49, 82, 0.08);">
                        <i class="fas fa-certificate fa-2x" style="color: #1b3152;"></i>
                    </div>
                    <h6 class="card-title fw-bold mb-1" style="font-size: 0.9rem;">QC CRM</h6>
                    <p class="card-text text-muted mb-3" style="font-size: 0.68rem;">Pengujian berdasarkan sertifikat (True Value) Certified Reference Material.</p>
                    <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1 disabled" style="font-size: 0.72rem;">Coming Soon</button>
                </div>
                <div class="card-footer bg-white border-top-0 text-center pb-2 pt-0">
                    <span class="badge bg-light text-muted border" style="font-size: 0.65rem;">{{ $crmCount ?? 0 }} Kegiatan</span>
                </div>
            </div>
        </div>

        <!-- QC Uji Banding -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 hover-lift" style="transition: transform 0.2s;">
                <div class="card-body text-center p-3">
                    <div class="rounded-circle d-inline-flex p-3 mb-2" style="background-color: rgba(27, 49, 82, 0.08);">
                        <i class="fas fa-globe fa-2x" style="color: #1b3152;"></i>
                    </div>
                    <h6 class="card-title fw-bold mb-1" style="font-size: 0.9rem;">QC Uji Banding</h6>
                    <p class="card-text text-muted mb-3" style="font-size: 0.68rem;">Komparasi hasil uji lab dengan vendor / Interlaboratory.</p>
                    <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1 disabled" style="font-size: 0.72rem;">Coming Soon</button>
                </div>
                <div class="card-footer bg-white border-top-0 text-center pb-2 pt-0">
                    <span class="badge bg-light text-muted border" style="font-size: 0.65rem;">0 Kegiatan</span>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-3">

    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
        <div>
            <h5 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">Daftar Kegiatan Pengujian (Harian)</h5>
            <p class="text-muted small mb-0" style="font-size: 0.68rem;">Lakukan batching pengujian harian untuk sampel reguler, In-House, dan CRM di sini.</p>
        </div>
        <div>
            @can('create', App\Models\Kegiatan::class)
                <a href="{{ route('kegiatan.create') }}" class="btn btn-corporate-blue btn-sm rounded-pill px-3 py-1 shadow-sm" style="font-size: 0.72rem;">
                    <i class="fas fa-plus me-1"></i> Tambah Kegiatan
                </a>
            @endcan
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body bg-light rounded py-2 px-3">
            <form action="{{ route('verifikasi-mutu.index') }}" method="GET" class="row g-2 align-items-end live-search-form" data-target="#table-container">
                <div class="col-md-4">
                    <label for="kode_sampel" class="form-label text-muted small fw-bold mb-1" style="font-size: 0.7rem;">Pencarian Kode Sampel</label>
                    <input type="text" class="form-control form-control-sm py-1" id="kode_sampel" name="search" value="{{ request('search') }}" placeholder="Ketik kode sampel..." style="font-size: 0.72rem;">
                </div>
                <div class="col-md-3">
                    <label for="status_kegiatan" class="form-label text-muted small fw-bold mb-1" style="font-size: 0.7rem;">Status</label>
                    <select class="form-select form-select-sm py-1" id="status_kegiatan" name="status_kegiatan" style="font-size: 0.72rem;">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status_kegiatan') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="berjalan" {{ request('status_kegiatan') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="selesai" {{ request('status_kegiatan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('verifikasi-mutu.index') }}" class="btn btn-outline-secondary btn-sm w-100 py-1" style="font-size: 0.72rem;"><i class="fas fa-sync-alt me-1"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0" id="table-container">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0 text-center">
                    <thead class="align-middle">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th class="text-start">Nama Kegiatan</th>
                            <th style="width: 150px;">Kode Sampel</th>
                            <th style="width: 120px;">Tanggal</th>
                            <th style="width: 90px;">Status</th>
                            <th style="width: 130px;">Dibuat Oleh</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kegiatans as $kegiatan)
                            <tr>
                                <td>{{ $loop->iteration + ($kegiatans->currentPage() - 1) * $kegiatans->perPage() }}</td>
                                <td class="text-start fw-bold">
                                    {{ $kegiatan->nama_kegiatan }}
                                </td>
                                <td>{{ $kegiatan->kode_sampel ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->format('d/m/Y') }}</td>
                                <td>
                                    @if($kegiatan->status_kegiatan == 'draft')
                                        <span class="badge bg-secondary" style="font-size: 0.6rem;">Draft</span>
                                    @elseif($kegiatan->status_kegiatan == 'berjalan')
                                        <span class="badge bg-primary" style="font-size: 0.6rem;">Berjalan</span>
                                    @elseif($kegiatan->status_kegiatan == 'selesai')
                                        <span class="badge bg-success" style="font-size: 0.6rem;">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary" style="font-size: 0.6rem;">{{ $kegiatan->status_kegiatan }}</span>
                                    @endif
                                </td>
                                <td>{{ $kegiatan->pembuatKegiatan ? $kegiatan->pembuatKegiatan->username : '-' }}</td>
                                <td>
                                    <a href="{{ route('kegiatan.show', $kegiatan->kegiatan_id) }}" class="btn btn-corporate-blue btn-sm py-0 px-2 shadow-sm" style="font-size: 0.65rem;" title="Workspace Hasil Uji">
                                        <i class="fas fa-flask me-1"></i> Input Data
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3" style="font-size: 0.72rem;">Data kegiatan tidak ditemukan. Mulai tambah kegiatan baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-2 px-3 pb-2">
                {{ $kegiatans->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection