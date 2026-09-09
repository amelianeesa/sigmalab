@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-1 mt-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Verifikasi Mutu</li>
        </ol>
        <a href="{{ route('parameter-uji.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="fas fa-cogs me-1"></i> Master Parameter Uji
        </a>
    </div>
    <h1 class="mb-4 fw-bold text-dark">Portal Verifikasi Mutu (QC)</h1>
    <p class="text-muted mb-4">Silakan pilih pilar Quality Control yang ingin Anda kelola.</p>

    <div class="row g-4">
        <!-- QC In-House -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 hover-lift" style="transition: transform 0.2s;">
                <div class="card-body text-center p-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                        <i class="fas fa-vial fa-3x text-primary"></i>
                    </div>
                    <h4 class="card-title fw-bold">QC In-House</h4>
                    <p class="card-text text-muted small">Penyiapan Uji Homogenitas, Stabilitas, & Target dari sampel internal.</p>
                    <div class="mt-4">
                        <a href="{{ route('qc-inhouse.index') }}" class="btn btn-primary rounded-pill px-4">Masuk <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 text-center pb-3">
                    <span class="badge bg-light text-primary border border-primary">{{ $inhouseCount ?? 0 }} Sampel Aktif</span>
                </div>
            </div>
        </div>

        <!-- QC CRM -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 hover-lift" style="transition: transform 0.2s;">
                <div class="card-body text-center p-4">
                    <div class="bg-purple bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3" style="background-color: rgba(111, 66, 193, 0.1);">
                        <i class="fas fa-certificate fa-3x text-purple" style="color: #6f42c1;"></i>
                    </div>
                    <h4 class="card-title fw-bold">QC CRM</h4>
                    <p class="card-text text-muted small">Pengujian berdasarkan sertifikat (True Value) Certified Reference Material.</p>
                    <div class="mt-4">
                        <a href="#" class="btn btn-outline-purple rounded-pill px-4" style="color: #6f42c1; border-color: #6f42c1;">Coming Soon</a>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 text-center pb-3">
                    <span class="badge bg-light text-purple border" style="color: #6f42c1; border-color: #6f42c1 !important;">{{ $crmCount ?? 0 }} Kegiatan</span>
                </div>
            </div>
        </div>

        <!-- QC Uji Banding -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 hover-lift" style="transition: transform 0.2s;">
                <div class="card-body text-center p-4">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                        <i class="fas fa-globe fa-3x text-info"></i>
                    </div>
                    <h4 class="card-title fw-bold">QC Uji Banding</h4>
                    <p class="card-text text-muted small">Komparasi hasil uji lab dengan vendor / Interlaboratory.</p>
                    <div class="mt-4">
                        <a href="#" class="btn btn-outline-info rounded-pill px-4">Coming Soon</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DAILY TESTING TABLE SECTION -->
    <hr class="my-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0 fw-bold text-dark"><i class="fas fa-clipboard-list me-2 text-primary"></i>Daftar Kegiatan Pengujian (Harian)</h3>
            <p class="text-muted small mb-0">Lakukan batching pengujian harian untuk sampel reguler, In-House, dan CRM di sini.</p>
        </div>
        <div>
            @can('create', App\Models\Kegiatan::class)
                <a href="{{ route('kegiatan.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Kegiatan
                </a>
            @endcan
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body bg-light rounded">
            <form action="{{ route('verifikasi-mutu.index') }}" method="GET" class="row g-3 live-search-form" data-target="#table-container">
                <div class="col-md-4">
                    <label for="kode_sampel" class="form-label text-muted small fw-bold">Pencarian Kode Sampel</label>
                    <input type="text" class="form-control form-control-sm" id="kode_sampel" name="search" value="{{ request('search') }}" placeholder="Ketik kode sampel...">
                </div>
                <div class="col-md-3">
                    <label for="status_kegiatan" class="form-label text-muted small fw-bold">Status</label>
                    <select class="form-select form-select-sm" id="status_kegiatan" name="status_kegiatan">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status_kegiatan') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="berjalan" {{ request('status_kegiatan') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="selesai" {{ request('status_kegiatan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('verifikasi-mutu.index') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="fas fa-sync-alt"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body" id="table-container">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th>Nama Kegiatan</th>
                            <th>Kode Sampel</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kegiatans as $kegiatan)
                            <tr>
                                <td class="text-center">{{ $loop->iteration + ($kegiatans->currentPage() - 1) * $kegiatans->perPage() }}</td>
                                <td>
                                    <span class="fw-bold">{{ $kegiatan->nama_kegiatan }}</span>
                                </td>
                                <td>{{ $kegiatan->kode_sampel ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->format('d M Y') }}</td>
                                <td>
                                    @if($kegiatan->status_kegiatan == 'draft')
                                        <span class="badge bg-secondary">Draft</span>
                                    @elseif($kegiatan->status_kegiatan == 'berjalan')
                                        <span class="badge bg-primary">Berjalan</span>
                                    @elseif($kegiatan->status_kegiatan == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $kegiatan->status_kegiatan }}</span>
                                    @endif
                                </td>
                                <td>{{ $kegiatan->pembuatKegiatan ? $kegiatan->pembuatKegiatan->username : '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('kegiatan.show', $kegiatan->kegiatan_id) }}" class="btn btn-sm btn-info text-white" title="Workspace Hasil Uji">
                                        <i class="fas fa-flask"></i> Input Data
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Data kegiatan tidak ditemukan. Mulai tambah kegiatan baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $kegiatans->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection
