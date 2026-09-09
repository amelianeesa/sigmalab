@extends('layouts.app')

@section('content')
@php
    $showArchived = $showArchived ?? false;
@endphp
<style>
    .library-card { border-radius: 14px; }
    .library-filter-label { color: #64748b; font-size: .78rem; font-weight: 600; margin-bottom: .35rem; }
    .library-table { --bs-table-bg: transparent; --bs-table-striped-bg: #f8fafc; }
    .library-table thead th { background: #212529; border-bottom: 0; color: #fff; font-size: .78rem; font-weight: 700; letter-spacing: .02em; padding: .85rem .75rem; text-transform: uppercase; }
    .library-table tbody td { border-color: #e2e8f0; color: #334155; padding: 1rem .75rem; }
    .library-table tbody tr { transition: background-color .15s ease; }
    .library-table tbody tr:hover { background-color: #f8f9fa; }
    .library-document-link { color: #0d6efd; font-size: 1rem; line-height: 1.35; text-decoration: none; }
    .library-document-link:hover { color: #0a58ca; text-decoration: underline; }
    .library-category { background: #e9ecef; border: 1px solid #ced4da; color: #6c757d; font-size: .72rem; font-weight: 600; }
    .library-revision { background: #f1f5f9; color: #475569; font-size: .75rem; font-weight: 700; }
    .library-action-btn { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; }
    .library-detail-btn { border-color: #0d6efd; color: #0d6efd; }
    .library-detail-btn:hover { background: #0d6efd; border-color: #0d6efd; color: #fff; }
</style>
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">{{ $showArchived ? 'Arsip Dokumen Library' : 'Library Digital' }}</h4>
            <p class="text-muted mb-0">{{ $showArchived ? 'Dokumen yang disembunyikan dari daftar aktif.' : 'Daftar induk dokumen prosedur, formulir, dan instruksi kerja.' }}</p>
        </div>

        @if(Auth::check() && Auth::user()->hasModulAccess('library_manage', 'tambah_ubah'))
            <div class="d-flex gap-2">
                @if($showArchived)
                    <a href="{{ route('library.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Dokumen Aktif</a>
                @else
                    <a href="{{ route('library.archive') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-box-archive me-1"></i> Arsip Dokumen</a>
                    <a href="{{ route('library.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Upload Dokumen</a>
                @endif
            </div>
        @endif
    </div>

    <div class="card library-card shadow-sm border-0">
        <div class="card-body p-3 p-md-4">
            <form method="GET" action="{{ $showArchived ? route('library.archive') : route('library.index') }}" class="row g-2 mb-3 align-items-end">
                <div class="col-md-5">
                    <label for="library-search" class="library-filter-label d-block">Cari Dokumen</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input id="library-search" type="search" name="search" value="{{ request('search') }}" class="form-control border-start-0 ps-0" placeholder="Nama, nomor, penerbit, atau kategori..." autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="library-category" class="library-filter-label d-block">Kategori</label>
                    <select id="library-category" name="category_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="library-filter-label d-block" style="visibility: hidden;">Aksi</label>
                    <div class="d-flex gap-2">
                        @if(!$showArchived)
                            <button type="submit" formaction="{{ route('library.export.pdf') }}" formtarget="_blank" class="btn btn-outline-success flex-grow-1 text-nowrap" title="Cetak Rekap Daftar Induk Dokumen sesuai filter saat ini">
                                <i class="fas fa-file-pdf me-1"></i>Cetak PDF
                            </button>
                        @endif
                        <a href="{{ $showArchived ? route('library.archive') : route('library.index') }}" class="btn btn-outline-secondary" title="Reset pencarian dan filter" aria-label="Reset pencarian dan filter">
                            <i class="fas fa-rotate-left"></i>
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table library-table table-striped align-middle text-center mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Dokumen</th>
                            <th>Nama Dokumen</th>
                            <th>Revisi</th>
                            <th>Tanggal Berlaku</th>
                            <th>Penerbit Dokumen</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $index => $document)
                            @php
                                $latestVersion = $document->versions->sortByDesc('revisi_ke')->first();
                                $ext = strtolower($document->file_extension ?? '');
                            @endphp
                            <tr>
                                <td>{{ $documents->firstItem() + $index }}</td>
                                <td>{{ $document->nomor_dokumen ?? '-' }}</td>
                                <td class="text-start">
                                    @if($showArchived)
                                        <span class="fw-semibold d-block">{{ $document->judul }}</span>
                                    @else
                                        <button type="button" class="btn btn-link library-document-link p-0 fw-semibold text-start d-block" data-bs-toggle="modal" data-bs-target="#modalPreviewDokumen" data-preview-url="{{ route('library.preview', $document->id) }}" data-preview-nama="{{ $document->judul }}" data-preview-ext="{{ $ext }}" title="Klik untuk lihat pratinjau">
                                            {{ $document->judul }}
                                        </button>
                                    @endif
                                    <span class="badge rounded-pill library-category mt-2">{{ $document->category->nama_kategori ?? '-' }}</span>
                                </td>
                                <td><span class="badge rounded-pill library-revision">Rev. {{ $latestVersion?->version_number ?? '00' }}</span></td>
                                <td>{{ $latestVersion?->tanggal_berlaku?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $document->penerbit_dokumen ?? '-' }}</td>
                                <td class="text-nowrap">
                                    @if($showArchived)
                                        <form action="{{ route('library.activate', $document->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tampilkan kembali dokumen ini pada daftar aktif?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-success btn-sm"><i class="fas fa-rotate-left me-1"></i> Tampilkan Kembali</button>
                                        </form>
                                    @else
                                        <a href="{{ route('library.show', $document->id) }}" class="btn library-detail-btn btn-sm" title="Detail dokumen"><i class="fas fa-circle-info me-1"></i> Detail</a>
                                        <a href="{{ route('library.download', $document->id) }}" class="btn btn-outline-secondary btn-sm library-action-btn" title="Unduh" aria-label="Unduh"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->hasModulAccess('library_manage', 'tambah_ubah'))
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-outline-secondary btn-sm library-action-btn" type="button" data-bs-toggle="dropdown" data-bs-strategy="fixed" aria-expanded="false" aria-label="Aksi lainnya">
                                                <i class="fas fa-ellipsis-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                <li><a class="dropdown-item" href="{{ route('library.edit', $document->id) }}"><i class="fas fa-pen text-warning me-2"></i>Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('library.destroy', $document->id) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini dari daftar aktif? Riwayat dokumen tetap tersimpan.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash me-2"></i>Hapus</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada dokumen di library.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $documents->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPreviewDokumen" tabindex="-1" aria-labelledby="modalPreviewDokumenLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fs-6" id="modalPreviewDokumenLabel">Pratinjau Dokumen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="modalPreviewBody" style="height: 75vh; overflow: auto;"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalPreview = document.getElementById('modalPreviewDokumen');
        if (!modalPreview) return;

        const body = document.getElementById('modalPreviewBody');
        const title = document.getElementById('modalPreviewDokumenLabel');
        const imageExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        modalPreview.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const url = button.getAttribute('data-preview-url');
            const nama = button.getAttribute('data-preview-nama');
            const ext = button.getAttribute('data-preview-ext');

            title.textContent = nama;

            if (ext === 'pdf') {
                body.innerHTML = '<iframe src="' + url + '" style="width:100%;height:100%;border:0;"></iframe>';
            } else if (imageExt.includes(ext)) {
                body.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 bg-light p-3"><img src="' + url + '" style="max-width:100%;max-height:100%;object-fit:contain;"></div>';
            } else {
                body.innerHTML = '<div class="d-flex flex-column align-items-center justify-content-center h-100 text-center p-4">' +
                    '<i class="fas fa-file-alt fs-1 text-muted mb-3"></i>' +
                    '<p class="text-muted mb-3">Pratinjau langsung belum didukung untuk format file ini (.' + ext + ').<br>Unduh dokumen untuk membukanya.</p>' +
                    '<a href="' + url.replace('/preview', '/download') + '" class="btn btn-primary btn-sm"><i class="fas fa-download me-1"></i> Unduh Dokumen</a>' +
                    '</div>';
            }
        });

        modalPreview.addEventListener('hidden.bs.modal', function () {
            body.innerHTML = '';
        });
    });
</script>
@endpush