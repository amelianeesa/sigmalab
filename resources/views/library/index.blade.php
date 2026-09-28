@extends('layouts.app')

@section('content')
@php
    $showArchived = $showArchived ?? false;
@endphp
<style>
    .dashboard-container {
        padding: 4px 20px !important;
    }
    .library-card { 
        border-radius: 8px; 
    }
    .library-filter-label { 
        color: #64748b; 
        font-size: 0.7rem; 
        font-weight: 600; 
        margin-bottom: 0.2rem; 
    }
    .card-body {
        padding: 10px !important;
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
    .library-table tbody tr { 
        transition: background-color .15s ease; 
    }
    .library-table tbody tr:hover { 
        background-color: #f8f9fa; 
    }
    .library-document-link { 
        color: #0d6efd; 
        font-size: 0.75rem; 
        line-height: 1.3; 
        text-decoration: none; 
    }
    .library-document-link:hover { 
        color: #0a58ca; 
        text-decoration: underline; 
    }
    .library-category { 
        background: #e9ecef; 
        border: 1px solid #ced4da; 
        color: #6c757d; 
        font-size: 0.58rem; 
        font-weight: 600; 
        padding: 2px 6px !important;
    }
    .library-revision { 
        background: #f1f5f9; 
        color: #475569; 
        font-size: 0.65rem; 
        font-weight: 700; 
        padding: 2px 6px !important;
    }
    .library-action-btn { 
        width: 28px; 
        height: 28px; 
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 0.7rem;
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
    .dropdown-menu-compact {
        min-width: 110px !important;
        padding: 3px 0 !important;
        font-size: 0.7rem !important;
    }
    .dropdown-menu-compact .dropdown-item {
        padding: 4px 10px !important;
        font-size: 0.7rem !important;
        transition: background-color 0.15s ease, color 0.15s ease;
    }
    .dropdown-menu-compact .dropdown-item:hover {
        background-color: #f1f5f9 !important;
        color: #1b3152 !important;
    }
    .dropdown-menu-compact .dropdown-item.text-danger:hover {
        background-color: #fdf2f2 !important;
        color: #dc3545 !important;
    }
    .pagination .page-link {
        font-size: 0.72rem;
        padding: 0.2rem 0.55rem;
    }

    .filter-select {
        position: relative;
        font-size: 0.72rem;
    }
    .filter-select-trigger {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 4px 8px;
        font-size: 0.72rem;
        cursor: pointer;
        text-align: left;
        color: #212529;
    }
    .filter-select-trigger:after {
        content: "";
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid #6c757d;
        margin-left: 6px;
        flex-shrink: 0;
    }
    .filter-select.open .filter-select-trigger {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
    }
    .filter-select-options {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1060;
        margin-top: 2px;
        max-height: 220px;
        overflow-y: auto;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.12);
        list-style: none;
        padding: 4px 0;
    }
    .filter-select.open .filter-select-options {
        display: block;
    }
    .filter-select-options li {
        padding: 6px 10px;
        font-size: 0.72rem;
        cursor: pointer;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .filter-select-options li:hover {
        background-color: #f1f3f5;
    }
    .filter-select-options li.selected {
        background-color: #0d6efd;
        color: #fff;
    }

    @media (max-width: 576px) {
        .library-header-row { flex-direction: column; align-items: stretch !important; }
        .library-header-row .d-flex.gap-2 { width: 100%; }
        .library-header-row .d-flex.gap-2 a { flex: 1; text-align: center; }
    }
</style>

<div class="container-fluid dashboard-container">
    <div class="d-flex justify-content-between align-items-center mb-2 mt-1 flex-wrap gap-2 library-header-row">
        <div>
            <h4 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">{{ $showArchived ? 'Arsip Dokumen Library' : 'Library Digital' }}</h4>
            <p class="text-muted small mb-0" style="font-size: 0.72rem;">{{ $showArchived ? 'Dokumen yang disembunyikan dari daftar aktif.' : 'Daftar induk dokumen prosedur, formulir, dan instruksi kerja.' }}</p>
        </div>

        @if(Auth::check() && Auth::user()->hasModulAccess('library_manage', 'tambah_ubah'))
            <div class="d-flex gap-2">
                @if($showArchived)
                    <a href="{{ route('library.index') }}" class="btn btn-outline-secondary btn-sm py-1" style="font-size: 0.72rem;"><i class="fas fa-arrow-left me-1"></i> Dokumen Aktif</a>
                @else
                    <a href="{{ route('library.archive') }}" class="btn btn-outline-secondary btn-sm py-1" style="font-size: 0.72rem;"><i class="fas fa-box-archive me-1"></i> Arsip Dokumen</a>
                    <a href="{{ route('library.create') }}" class="btn btn-corporate-blue btn-sm py-1 shadow-sm" style="font-size: 0.72rem;"><i class="fas fa-plus me-1"></i> Upload Dokumen</a>
                @endif
            </div>
        @endif
    </div>

    <div class="card library-card shadow-sm border-0 mb-3">
        <div class="card-body p-2 px-3">
            <form id="library-filter-form" method="GET" action="{{ $showArchived ? route('library.archive') : route('library.index') }}" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label for="library-search" class="library-filter-label d-block">Cari Dokumen</label>
                    <div class="input-group input-group-sm">
                        <input id="library-search" type="search" name="search" value="{{ request('search') }}" class="form-control form-control-sm py-1" placeholder="Nama, nomor, penerbit, atau kategori..." autocomplete="off" style="font-size: 0.72rem;">
                        <button type="submit" class="btn btn-corporate-blue btn-sm px-3 fw-semibold" title="Cari Dokumen" style="font-size: 0.72rem;">
                            Cari
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="library-filter-label d-block">Kategori</label>
                    @php
                        $selectedCategoryFilter = (string) request('category_id', '');
                    @endphp
                    <div class="filter-select" id="selectCategoryFilter">
                        <input type="hidden" name="category_id" value="{{ $selectedCategoryFilter }}">
                        <button type="button" class="filter-select-trigger">
                            {{ $categories->firstWhere('id', $selectedCategoryFilter)->nama_kategori ?? 'Semua Kategori' }}
                        </button>
                        <ul class="filter-select-options">
                            <li data-value="" class="{{ $selectedCategoryFilter === '' ? 'selected' : '' }}">Semua Kategori</li>
                            @foreach($categories as $category)
                                <li data-value="{{ $category->id }}" class="{{ $selectedCategoryFilter === (string) $category->id ? 'selected' : '' }}">{{ $category->nama_kategori }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="library-filter-label d-block" style="visibility: hidden;">Aksi</label>
                    <div class="d-flex gap-1">
                        @if(!$showArchived)
                            <button type="button" onclick="exportPdf(document.getElementById('library-filter-form'))" class="btn btn-outline-success btn-sm flex-grow-1 text-nowrap py-1" title="Cetak Rekap Daftar Induk Dokumen sesuai filter saat ini" style="font-size: 0.72rem;">
                                <i class="fas fa-file-pdf me-1"></i>Cetak PDF
                            </button>
                        @endif
                        <a href="{{ $showArchived ? route('library.archive') : route('library.index') }}" class="btn btn-outline-secondary btn-sm py-1 px-2" title="Reset pencarian dan filter" aria-label="Reset pencarian dan filter" style="font-size: 0.72rem;">
                            <i class="fas fa-rotate-left"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card library-card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table library-table table-bordered table-striped align-middle text-center mb-0">
                    <thead>
                        <tr>
                            <th style="width: 45px;">No</th>
                            <th style="width: 140px;">Nomor Dokumen</th>
                            <th class="text-start">Nama Dokumen</th>
                            <th style="width: 80px;">Revisi</th>
                            <th style="width: 100px;">Tanggal Berlaku</th>
                            <th style="width: 130px;">Penerbit Dokumen</th>
                            <th style="width: 110px;">Aksi</th>
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
                                        <span class="fw-semibold d-block" style="font-size: 0.72rem;">{{ $document->judul }}</span>
                                    @else
                                        <button type="button" class="btn btn-link library-document-link p-0 fw-semibold text-start d-block" data-bs-toggle="modal" data-bs-target="#modalPreviewDokumen" data-preview-url="{{ route('library.preview', $document->id) }}" data-preview-nama="{{ $document->judul }}" data-preview-ext="{{ $ext }}" title="Klik untuk lihat pratinjau">
                                            {{ $document->judul }}
                                        </button>
                                    @endif
                                    <span class="badge rounded-pill library-category mt-1">{{ $document->category->nama_kategori ?? '-' }}</span>
                                </td>
                                <td><span class="badge rounded-pill library-revision">Rev. {{ $latestVersion?->version_number ?? '00' }}</span></td>
                                <td>{{ $latestVersion?$latestVersion->tanggal_berlaku?->format('d/m/Y') : '-' }}</td>
                                <td>{{ $document->penerbit_dokumen ?? '-' }}</td>
                                <td class="text-nowrap">
                                    @if($showArchived)
                                        <form id="form-activate-{{ $document->id }}" action="{{ route('library.activate', $document->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" class="btn btn-outline-success btn-sm py-0 px-1.5 btn-activate" data-id="{{ $document->id }}" style="font-size: 0.65rem;"><i class="fas fa-rotate-left me-1"></i> Tampilkan</button>
                                        </form>
                                    @else
                                        <a href="{{ route('library.show', $document->id) }}" class="btn btn-corporate-blue btn-sm library-action-btn shadow-sm" title="Detail dokumen"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('library.download', $document->id) }}" class="btn btn-outline-secondary btn-sm library-action-btn" title="Unduh" aria-label="Unduh"><i class="fas fa-download"></i></a>
                                        @if(Auth::user()->hasModulAccess('library_manage', 'tambah_ubah'))
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-outline-secondary btn-sm library-action-btn" type="button" data-bs-toggle="dropdown" data-bs-strategy="fixed" aria-expanded="false" aria-label="Aksi lainnya">
                                                    <i class="fas fa-ellipsis-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm dropdown-menu-compact">
                                                    <li><a class="dropdown-item" href="{{ route('library.edit', $document->id) }}"><i class="fas fa-pen text-warning me-2"></i>Edit</a></li>
                                                    <li><hr class="dropdown-divider my-1"></li>
                                                    <li>
                                                        <form id="form-delete-{{ $document->id }}" action="{{ route('library.destroy', $document->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="dropdown-item text-danger btn-delete" data-id="{{ $document->id }}"><i class="fas fa-trash me-2"></i>Hapus</button>
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
                                <td colspan="7" class="text-center text-muted py-3" style="font-size: 0.72rem;">
                                    Belum ada dokumen di library.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-2 px-3 pb-2">
                {{ $documents->links('vendor.pagination.custom', ['size' => 'sm']) }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPreviewDokumen" tabindex="-1" aria-labelledby="modalPreviewDokumenLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header py-2 text-white" style="background-color: #1b3152;">
                <h5 class="modal-title fs-6" id="modalPreviewDokumenLabel">Pratinjau Dokumen</h5>
                <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="modalPreviewBody" style="height: 75vh; overflow: auto;"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKonfirmasiHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 360px;">
        <div class="modal-content border-0 shadow text-center p-3" style="font-size: 0.78rem; border-radius: 8px;">
            <div class="pt-2 pb-1">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #fff8e6; color: #f0ad4e; font-size: 22px; border: 2px solid #ffeeba;">
                    <i class="fas fa-exclamation"></i>
                </div>
            </div>
            <div class="modal-body px-2 py-2">
                <h5 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Apakah Anda yakin?</h5>
                <p class="text-muted mb-0" style="font-size: 0.72rem;">Hapus dokumen ini dari daftar aktif? Riwayat dokumen tetap tersimpan.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-1 pb-2">
                <button type="button" id="btnConfirmDelete" class="btn btn-danger btn-sm py-1 px-3 fw-semibold rounded-2" style="font-size: 0.73rem;">Ya, Hapus!</button>
                <button type="button" class="btn btn-secondary btn-sm py-1 px-3 fw-semibold rounded-2" data-bs-dismiss="modal" style="font-size: 0.73rem;">Batal</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKonfirmasiPulihkan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 360px;">
        <div class="modal-content border-0 shadow text-center p-3" style="font-size: 0.78rem; border-radius: 8px;">
            <div class="pt-2 pb-1">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #e8f4fd; color: #0d6efd; font-size: 22px; border: 2px solid #cfe2ff;">
                    <i class="fas fa-question"></i>
                </div>
            </div>
            <div class="modal-body px-2 py-2">
                <h5 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Apakah Anda yakin?</h5>
                <p class="text-muted mb-0" style="font-size: 0.72rem;">Tampilkan kembali dokumen ini pada daftar aktif?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-1 pb-2">
                <button type="button" id="btnConfirmActivate" class="btn btn-success btn-sm py-1 px-3 fw-semibold rounded-2" style="font-size: 0.73rem;">Ya, Tampilkan!</button>
                <button type="button" class="btn btn-secondary btn-sm py-1 px-3 fw-semibold rounded-2" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function exportPdf(form) {
        const originalAction = form.action;
        const originalTarget = form.target;

        form.action = "{{ route('library.export.pdf') }}";
        form.target = "_blank";
        form.submit();

        form.action = originalAction;
        form.target = originalTarget;
    }

    document.addEventListener('DOMContentLoaded', function () {
        let activeDeleteId = null;
        let activeActivateId = null;

        const modalHapusEl = document.getElementById('modalKonfirmasiHapus');
        const modalHapus = modalHapusEl ? new bootstrap.Modal(modalHapusEl) : null;

        const modalPulihkanEl = document.getElementById('modalKonfirmasiPulihkan');
        const modalPulihkan = modalPulihkanEl ? new bootstrap.Modal(modalPulihkanEl) : null;

        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function () {
                activeDeleteId = this.getAttribute('data-id');
                if (modalHapus) modalHapus.show();
            });
        });

        const btnConfirmDelete = document.getElementById('btnConfirmDelete');
        if (btnConfirmDelete) {
            btnConfirmDelete.addEventListener('click', function () {
                if (activeDeleteId) {
                    document.getElementById('form-delete-' + activeDeleteId).submit();
                }
            });
        }

        document.querySelectorAll('.btn-activate').forEach(button => {
            button.addEventListener('click', function () {
                activeActivateId = this.getAttribute('data-id');
                if (modalPulihkan) modalPulihkan.show();
            });
        });

        const btnConfirmActivate = document.getElementById('btnConfirmActivate');
        if (btnConfirmActivate) {
            btnConfirmActivate.addEventListener('click', function () {
                if (activeActivateId) {
                    document.getElementById('form-activate-' + activeActivateId).submit();
                }
            });
        }

        const modalPreview = document.getElementById('modalPreviewDokumen');
        if (modalPreview) {
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
                        '<a href="' + url.replace('/preview', '/download') + '" class="btn btn-corporate-blue btn-sm"><i class="fas fa-download me-1"></i> Unduh Dokumen</a>' +
                        '</div>';
                }
            });

            modalPreview.addEventListener('hidden.bs.modal', function () {
                body.innerHTML = '';
            });
        }

        document.querySelectorAll('.filter-select').forEach(function (wrapper) {
            const trigger = wrapper.querySelector('.filter-select-trigger');
            const hiddenInput = wrapper.querySelector('input[type="hidden"]');
            const options = wrapper.querySelectorAll('.filter-select-options li');
            const form = wrapper.closest('form');

            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                document.querySelectorAll('.filter-select.open').forEach(function (other) {
                    if (other !== wrapper) other.classList.remove('open');
                });
                wrapper.classList.toggle('open');
            });

            options.forEach(function (li) {
                li.addEventListener('click', function () {
                    hiddenInput.value = li.getAttribute('data-value');
                    trigger.textContent = li.textContent;
                    options.forEach(function (o) { o.classList.remove('selected'); });
                    li.classList.add('selected');
                    wrapper.classList.remove('open');
                    if (form) form.submit();
                });
            });
        });

        document.addEventListener('click', function () {
            document.querySelectorAll('.filter-select.open').forEach(function (wrapper) {
                wrapper.classList.remove('open');
            });
        });
    });
</script>
@endpush