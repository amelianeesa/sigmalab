@extends('layouts.app')

@section('content')
<style>
    .dashboard-container {
        padding: 4px 20px !important;
    }
    .card-body {
        padding: 12px !important;
    }
    .detail-label {
        color: #64748b;
        font-size: 0.68rem;
        font-weight: 600;
        margin-bottom: 0.1rem;
    }
    .detail-value {
        color: #334155;
        font-size: 0.78rem;
    }
    .table th, .table td {
        padding: 6px 10px !important;
        vertical-align: middle !important;
        font-size: 0.72rem !important;
    }
    .library-table thead th {
        background-color: #1b3152 !important;
        border-color: #ffffff !important;
        color: #fff !important;
        font-size: 0.72rem !important;
        font-weight: 700;
        letter-spacing: .02em;
        padding: 6px 8px !important;
        text-transform: uppercase;
    }
    .library-table tbody td {
        border-color: #e2e8f0;
        color: #334155;
    }
    .library-table tbody tr:hover {
        background-color: #f8f9fa;
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
</style>

<div class="container-fluid dashboard-container">
    <div class="d-flex justify-content-between align-items-center mb-2 mt-1 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Detail Dokumen</h4>
            <p class="text-muted small mb-0" style="font-size: 0.72rem;">Informasi dokumen dan riwayat revisinya.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('library.index') }}" class="btn btn-outline-secondary btn-sm py-1" style="font-size: 0.72rem;">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            @if(Auth::user()->hasModulAccess('library_manage', 'tambah_ubah'))
                <a href="{{ route('library.edit', $document->id) }}" class="btn btn-outline-warning btn-sm py-1" style="font-size: 0.72rem;">
                    <i class="fas fa-pen me-1"></i> Edit
                </a>
                <a href="{{ route('library.revision.create', $document->id) }}" class="btn btn-corporate-blue btn-sm py-1 shadow-sm" style="font-size: 0.72rem;">
                    <i class="fas fa-history me-1"></i> Revisi Baru
                </a>
            @endif
        </div>
    </div> 

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="detail-label">Judul</div>
                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $document->judul }}</div>
                </div>
                <div class="col-md-3">
                    <div class="detail-label">Kategori</div>
                    <div class="detail-value">{{ $document->category->nama_kategori ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="detail-label">Status</div>
                    <span class="badge bg-success" style="font-size: 0.6rem;">Aktif</span>
                </div>

                <div class="col-md-6">
                    <div class="detail-label">Nomor Dokumen</div>
                    <div class="detail-value">{{ $document->nomor_dokumen ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="detail-label">Penerbit Dokumen</div>
                    <div class="detail-value">{{ $document->penerbit_dokumen ?? '-' }}</div>
                </div>

                <div class="col-12">
                    <div class="detail-label">Deskripsi</div>
                    <div class="detail-value">{{ $document->deskripsi ?? '-' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="detail-label">File</div>
                    <div class="d-flex align-items-center gap-2 mt-1 detail-value">
                        <i class="fas fa-file-alt text-muted"></i>
                        <span>{{ $document->file_name ?? '-' }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-label">Pembaruan Terakhir</div>
                    <div class="detail-value">{{ $document->updated_at?->format('d/m/Y H:i') ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white fw-bold py-2 px-3" style="font-size: 0.85rem;">Riwayat Versi</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table library-table table-bordered table-striped mb-0 align-middle text-center">
                    <thead>
                        <tr>
                            <th>Versi</th>
                            <th>Revisi</th>
                            <th>Catatan</th>
                            <th>Tanggal Berlaku</th>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($document->versions->sortByDesc('revisi_ke') as $version)
                            <tr>
                                <td>{{ $version->version_number }}</td>
                                <td>{{ $version->revisi_ke }}</td>
                                <td class="text-start">{{ $version->catatan_revisi ?? '-' }}</td>
                                <td>{{ $version->tanggal_berlaku?->format('d/m/Y') ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('library.version.download', [$document->id, $version->id]) }}" class="btn btn-link btn-sm p-0" style="font-size: 0.72rem;">Download</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted py-3">Belum ada riwayat revisi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection