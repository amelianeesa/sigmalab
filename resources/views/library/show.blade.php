@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Detail Dokumen</h4>
            <p class="text-muted mb-0">Informasi dokumen dan riwayat revisinya.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('library.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            @if(Auth::user()->hasModulAccess('library_manage', 'tambah_ubah'))
                <a href="{{ route('library.edit', $document->id) }}" class="btn btn-outline-warning btn-sm"><i class="fas fa-pen me-1"></i> Edit</a>
                <a href="{{ route('library.revision.create', $document->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-history me-1"></i> Revisi Baru
                </a>
            @endif
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="small text-muted">Judul</div>
                    <div class="fw-bold fs-5">{{ $document->judul }}</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Kategori</div>
                    <div>{{ $document->category->nama_kategori ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="small text-muted">Status</div>
                    <span class="badge bg-success">Aktif</span>
                </div>

                <div class="col-md-6">
                    <div class="small text-muted">Nomor Dokumen</div>
                    <div>{{ $document->nomor_dokumen ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Penerbit Dokumen</div>
                    <div>{{ $document->penerbit_dokumen ?? '-' }}</div>
                </div>

                <div class="col-12">
                    <div class="small text-muted">Deskripsi</div>
                    <div>{{ $document->deskripsi ?? '-' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="small text-muted">File Aktif</div>
                    <a href="{{ route('library.download', $document->id) }}" class="btn btn-outline-primary btn-sm mt-1">
                        <i class="fas fa-file-download me-1"></i> {{ $document->file_name }}
                    </a>
                </div>
                <div class="col-md-6">
                    <div class="small text-muted">Pembaruan Terakhir</div>
                    <div>{{ $document->updated_at?->format('d/m/Y H:i') ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold">Riwayat Versi</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0 align-middle text-center">
                    <thead class="table-light">
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
                                    <a href="{{ route('library.version.download', [$document->id, $version->id]) }}" class="btn btn-link btn-sm p-0">Download</a>
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
