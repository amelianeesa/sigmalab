@extends('layouts.app')

@section('content')
@php
    $showArchived = $showArchived ?? false;
@endphp
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

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="GET" action="{{ $showArchived ? route('library.archive') : route('library.index') }}" class="row g-2 mb-3 align-items-center">
                <div class="col-md-5">
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari nama, nomor, penerbit, atau kategori..." autocomplete="off">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm flex-grow-1" type="submit">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    @if(!$showArchived)
                        <button type="submit" formaction="{{ route('library.export.pdf') }}" formtarget="_blank" class="btn btn-outline-success btn-sm text-nowrap" title="Cetak Rekap Daftar Induk Dokumen sesuai filter saat ini">
                            <i class="fas fa-file-pdf me-1"></i> Cetak PDF
                        </button>
                    @endif
                    <a href="{{ $showArchived ? route('library.archive') : route('library.index') }}" class="btn btn-outline-danger btn-sm" title="Reset pencarian dan filter">
                        <i class="fas fa-rotate-left"></i>
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center mb-0">
                    <thead class="table-dark">
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
                            @endphp
                            <tr>
                                <td>{{ $documents->firstItem() + $index }}</td>
                                <td>{{ $document->nomor_dokumen ?? '-' }}</td>
                                <td class="text-start"><span class="fw-semibold d-block">{{ $document->judul }}</span><small class="text-muted">{{ $document->category->nama_kategori ?? '-' }}</small></td>
                                <td>{{ $latestVersion?->version_number ?? '00' }}</td>
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
                                        <a href="{{ route('library.show', $document->id) }}" class="btn btn-outline-primary btn-sm" title="Lihat"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('library.download', $document->id) }}" class="btn btn-outline-secondary btn-sm" title="Unduh"><i class="fas fa-download"></i></a>
                                    @if(Auth::user()->hasModulAccess('library_manage', 'tambah_ubah'))
                                        <a href="{{ route('library.edit', $document->id) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <form action="{{ route('library.destroy', $document->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dokumen ini dari daftar aktif? Riwayat dokumen tetap tersimpan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus" aria-label="Hapus dari Daftar"><i class="fas fa-trash"></i></button>
                                        </form>
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
@endsection