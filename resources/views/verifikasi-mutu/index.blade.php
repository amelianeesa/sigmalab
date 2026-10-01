@extends('layouts.app')
@section('title', 'Daftar - Verifikasi Mutu')

@section('content')
<style>
    .dashboard-container {
        padding: 4px 20px !important;
    }

    .card-body {
        padding: 10px !important;
    }

    /* ===== Tabel (sama dengan alat/index) ===== */
    .table th, .table td {
        padding: 8px 10px !important;
        vertical-align: middle !important;
        font-size: 0.72rem !important;
    }
    .table thead th {
        font-size: 0.75rem !important;
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-color: #ffffff !important;
        white-space: nowrap;
    }
    .table-bordered > :not(caption) > * > * {
        border-color: #dee2e6;
    }

    /* ===== Tombol ===== */
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

    /* ===== Pagination ===== */
    .pagination .page-link {
        font-size: 0.72rem;
        padding: 0.2rem 0.55rem;
    }

    /* ===== Kartu pilar QC ===== */
    .pillar-card .card-body { padding: 14px !important; }
    .pillar-card .pillar-icon {
        width: 56px; height: 56px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 50%;
        background-color: rgba(27, 49, 82, 0.08);
        color: #1b3152;
        font-size: 1.4rem;
    }
    .pillar-card .pillar-actions {
        display: flex; flex-direction: column; align-items: center; gap: 8px;
    }
    .pillar-card .pillar-actions .badge {
        font-size: 0.65rem; color: #1b3152; background: #fff; border: 1px solid #1b3152 !important;
    }
    .hover-lift {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.08) !important;
    }
    @media (prefers-reduced-motion: reduce) {
        .hover-lift { transition: none; }
        .hover-lift:hover { transform: none; }
    }

    /* ===== Mobile ===== */
    @media (max-width: 767.98px) {
        .dashboard-container { padding: 4px 10px !important; }

        /* Kartu pilar: ikon kiri, teks kanan, tombol + badge satu baris */
        .pillar-card .card-body {
            display: flex; align-items: flex-start; gap: 12px; text-align: left !important;
            padding: 12px !important;
        }
        .pillar-card .pillar-icon { width: 44px; height: 44px; font-size: 1.1rem; flex-shrink: 0; margin: 0 !important; }
        .pillar-card .pillar-body { flex: 1; min-width: 0; }
        .pillar-card .pillar-body .card-text { margin-bottom: 8px !important; }
        .pillar-card .pillar-actions {
            flex-direction: row; justify-content: space-between; flex-wrap: wrap; gap: 8px;
        }

        /* Tabel jadi kartu */
        .table-stack thead { display: none; }
        .table-stack, .table-stack tbody, .table-stack tr, .table-stack td { display: block; width: 100%; }
        .table-stack tbody tr {
            border: 1px solid #dee2e6; border-radius: 8px; margin: 0 0 10px; padding: 6px 12px;
            background: #fff; box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }
        .table-stack td {
            display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;
            text-align: right !important; border: 0 !important; padding: 5px 0 !important; background: transparent !important;
        }
        .table-stack td[data-label]::before {
            content: attr(data-label); font-weight: 600; color: #1b3152; text-align: left; flex-shrink: 0; max-width: 45%;
        }
        .table-stack td.td-aksi {
            justify-content: flex-end; border-top: 1px solid #eee !important; margin-top: 4px; padding-top: 8px !important;
        }
        .table-stack td.td-aksi::before { display: none; }
        .table-stack td.td-empty { display: block; text-align: center !important; }

        /* Pagination di tengah */
        #table-container nav,
        #table-container .pagination { justify-content: center; flex-wrap: wrap; }
    }
</style>

@php
    // Notifikasi ringkas dari data yang tampil di daftar
    $kegiatanItems = $kegiatans->getCollection();
    $draftCount    = $kegiatanItems->where('status_kegiatan', 'draft')->count();
    $berjalanCount = $kegiatanItems->where('status_kegiatan', 'berjalan')->count();
@endphp

<div class="container-fluid dashboard-container" style="font-size: 0.82rem;">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-2 mb-3 gap-2">
        <div>
            <h5 class="fw-bold mb-0" style="font-size: 1.1rem;">Portal Verifikasi Mutu (QC)</h5>
            <p class="text-muted mb-0" style="font-size: 0.72rem;">Silakan pilih pilar Quality Control yang ingin Anda kelola.</p>
        </div>
        <div class="d-grid d-md-flex">
            <a href="{{ route('parameter-uji.index') }}" class="btn btn-outline-secondary btn-sm py-1.5 px-3 shadow-sm fw-semibold" style="font-size: 0.8rem;">
                <i class="fas fa-cogs me-1"></i> Master Parameter Uji
            </a>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if($draftCount > 0 || $berjalanCount > 0)
        <div class="alert alert-warning alert-dismissible fade show shadow-sm py-2 ps-3 pe-5 mb-3" role="alert" style="font-size: 0.8rem;">
            <i class="fas fa-exclamation-triangle me-1"></i> <strong>Perhatian!</strong>
            @if($berjalanCount > 0)
                Terdapat <strong>{{ $berjalanCount }} kegiatan</strong> yang masih berjalan.
            @endif
            @if($draftCount > 0)
                Terdapat <strong>{{ $draftCount }} kegiatan</strong> berstatus draft yang belum diselesaikan.
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.65rem; padding: 0.9rem;"></button>
        </div>
    @endif

    {{-- 3 PILAR QC --}}
    <div class="row g-2 g-md-3 mb-3">

        {{-- Kartu 1: QC In-House --}}
        <div class="col-12 col-md-4">
            <div class="card pillar-card h-100 shadow-sm border-0 hover-lift" style="cursor: pointer;" ondblclick="window.location='{{ route('qc-inhouse.index') }}'">
                <div class="card-body text-center">
                    <div class="pillar-icon mb-2"><i class="fas fa-vial"></i></div>
                    <div class="pillar-body">
                        <h6 class="card-title fw-bold mb-1" style="font-size: 0.9rem;">QC In-House</h6>
                        <p class="card-text text-muted mb-3" style="font-size: 0.7rem;">Penyiapan Uji Homogenitas, Stabilitas, &amp; Target dari sampel internal.</p>
                        <div class="pillar-actions">
                            <a href="{{ route('qc-inhouse.index') }}" class="btn btn-corporate-blue btn-sm px-3 py-1 shadow-sm fw-semibold" style="font-size: 0.75rem;">
                            Masuk <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                            <span class="badge bg-light border" style="font-size: 0.65rem; color: #1b3152; border-color: #1b3152 !important;">{{ $inhouseCount ?? 0 }} Sampel Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kartu 2: QC CRM --}}
        <div class="col-12 col-md-4">
            <div class="card pillar-card h-100 shadow-sm border-0 hover-lift" style="cursor: pointer;" ondblclick="window.location='{{ route('qc-crm.index') }}'">
                <div class="card-body text-center">
                    <div class="pillar-icon mb-2"><i class="fas fa-certificate"></i></div>
                    <div class="pillar-body">
                        <h6 class="card-title fw-bold mb-1" style="font-size: 0.9rem;">QC CRM</h6>
                        <p class="card-text text-muted mb-3" style="font-size: 0.7rem;">Pengujian berdasarkan sertifikat (True Value) Certified Reference Material.</p>
                        <div class="pillar-actions">
                            <a href="{{ route('qc-crm.index') }}" class="btn btn-corporate-blue btn-sm px-3 py-1 shadow-sm fw-semibold" style="font-size: 0.75rem;">
                            Masuk <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                            <span class="badge bg-light border" style="font-size: 0.65rem; color: #1b3152; border-color: #1b3152 !important;">{{ \App\Models\QcCrm::count() ?? 0 }} Kegiatan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kartu 3: QC Uji Banding --}}
        <div class="col-12 col-md-4">
            <div class="card pillar-card h-100 shadow-sm border-0 hover-lift" style="cursor: pointer;" ondblclick="window.location='{{ route('qc-uji-banding.index') }}'">
                <div class="card-body text-center">
                    <div class="pillar-icon mb-2"><i class="fas fa-globe"></i></div>
                    <div class="pillar-body">
                        <h6 class="card-title fw-bold mb-1" style="font-size: 0.9rem;">QC Uji Banding</h6>
                        <p class="card-text text-muted mb-3" style="font-size: 0.7rem;">Komparasi hasil uji lab dengan vendor / Interlaboratory.</p>
                        <div class="pillar-actions">
                            <a href="{{ route('qc-uji-banding.index') }}" class="btn btn-corporate-blue btn-sm px-3 py-1 shadow-sm fw-semibold" style="font-size: 0.75rem;">
                            Masuk <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                            <span class="badge bg-light border" style="font-size: 0.65rem; color: #1b3152; border-color: #1b3152 !important;">{{ \App\Models\QcUjiBanding::count() ?? 0 }} Program Terdaftar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-3">

    {{-- JUDUL DAFTAR KEGIATAN --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
        <div>
            <h5 class="fw-bold mb-0" style="font-size: 1.1rem;">Daftar Kegiatan Pengujian (Harian)</h5>
            <p class="text-muted mb-0" style="font-size: 0.72rem;">Lakukan batching pengujian harian untuk sampel reguler, In-House, dan CRM di sini.</p>
        </div>
        <div class="d-grid d-md-flex">
            @can('create', App\Models\Kegiatan::class)
                <a href="{{ route('kegiatan.create') }}" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold" style="font-size: 0.8rem;">
                    <i class="fas fa-plus me-1"></i> Tambah Kegiatan
                </a>
            @endcan
        </div>
    </div>

    {{-- FILTER + TABEL --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">

            <form action="{{ route('verifikasi-mutu.index') }}" method="GET" class="row g-2 mb-3 align-items-center live-search-form" data-target="#table-container">
                <div class="col-12 col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control form-control-sm py-1.5" id="kode_sampel" name="search" value="{{ request('search') }}" placeholder="Cari Kode Sampel..." autocomplete="off" style="font-size: 0.82rem;">
                    </div>
                </div>
                <div class="col-8 col-md-3">
                    <select class="form-select form-select-sm py-1.5" id="status_kegiatan" name="status_kegiatan" style="font-size: 0.82rem;">
                        <option value="">-- Filter Status --</option>
                        <option value="draft" {{ request('status_kegiatan') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="berjalan" {{ request('status_kegiatan') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                        <option value="selesai" {{ request('status_kegiatan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-4 col-md-1">
                    <a href="{{ route('verifikasi-mutu.index') }}" class="btn btn-outline-secondary btn-sm w-100 py-1.5" title="Reset" aria-label="Reset"><i class="fas fa-sync-alt"></i></a>
                </div>
            </form>

            <div id="table-container">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle mb-0 text-center table-stack">
                        <thead class="align-middle">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th class="text-start">Nama Kegiatan</th>
                                <th style="width: 150px;">Kode Sampel</th>
                                <th style="width: 120px;">Tanggal</th>
                                <th style="width: 90px;">Status</th>
                                <th style="width: 130px;">Dibuat Oleh</th>
                                <th style="width: 110px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kegiatans as $kegiatan)
                                <tr>
                                    <td data-label="No">{{ $loop->iteration + ($kegiatans->currentPage() - 1) * $kegiatans->perPage() }}</td>
                                    <td data-label="Nama Kegiatan" class="text-md-start fw-bold">{{ $kegiatan->nama_kegiatan }}</td>
                                    <td data-label="Kode Sampel">{{ $kegiatan->kode_sampel ?? '-' }}</td>
                                    <td data-label="Tanggal">{{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->format('d/m/Y') }}</td>
                                    <td data-label="Status">
                                        @if($kegiatan->status_kegiatan == 'draft')
                                            <span class="badge bg-secondary" style="font-size: 0.7rem;">Draft</span>
                                        @elseif($kegiatan->status_kegiatan == 'berjalan')
                                            <span class="badge bg-primary" style="font-size: 0.7rem;">Berjalan</span>
                                        @elseif($kegiatan->status_kegiatan == 'selesai')
                                            <span class="badge bg-success" style="font-size: 0.7rem;">Selesai</span>
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 0.7rem;">{{ $kegiatan->status_kegiatan }}</span>
                                        @endif
                                    </td>
                                    <td data-label="Dibuat Oleh">{{ $kegiatan->pembuatKegiatan ? $kegiatan->pembuatKegiatan->username : '-' }}</td>
                                    <td class="text-nowrap td-aksi">
                                        <a href="{{ route('kegiatan.show', $kegiatan->kegiatan_id) }}" class="btn btn-corporate-blue btn-sm py-1 px-2 shadow-sm" style="font-size: 0.72rem;" title="Workspace Hasil Uji">
                                            <i class="fas fa-flask me-1"></i> Input Data
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4 td-empty">
                                        <i class="fas fa-box-open fs-1 text-light mb-2 d-block"></i>
                                        Data kegiatan tidak ditemukan. Mulai tambah kegiatan baru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $kegiatans->withQueryString()->links('vendor.pagination.custom', ['size' => 'sm']) }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection