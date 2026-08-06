@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Manajemen SDM & Kompetensi</h2>
            <p class="text-muted mb-0">Kelola data personil laboratorium, riwayat sertifikasi, dan dokumen CV.</p>
        </div>
        <div>
            <a href="{{ route('sdm.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-person-plus-fill me-1"></i> Tambah Personil Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Card Tabel Data -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-secondary">
                        <tr>
                            <th class="py-3">No. Induk</th>
                            <th class="py-3">Nama Personil</th>
                            <th class="py-3">Jabatan</th>
                            <th class="py-3">Unit Kerja</th>
                            <th class="py-3 text-center">Sertifikasi & Kompetensi</th>
                            <th class="py-3 text-center">Dokumen CV</th>
                            <th class="py-3 text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($personil as $row)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $row->no_induk }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-initial rounded-circle bg-light-primary text-primary fw-bold me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 14px; background: #e3f2fd;">
                                        {{ substr($row->nama, 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ $row->nama }}</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $row->jabatan }}</span></td>
                            <td><span class="text-muted">{{ $row->unit_kerja }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('sdm.kompetensi.detail', $row->personil_id) }}" class="btn btn-sm btn-outline-info px-3 rounded-pill">
                                    <i class="bi bi-award me-1"></i> Lihat Detail
                                </a>
                            </td>
                            <td class="text-center">
                                @if($row->file_cv)
                                    <a href="{{ route('sdm.cv', $row->personil_id) }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill" target="_blank">
                                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Buka CV
                                    </a>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Belum Diunggah</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('sdm.edit', $row->personil_id) }}" class="btn btn-sm btn-light text-warning" title="Edit Data">
                                        <i class="bi bi-pencil-square fs-6"></i>
                                    </a>
                                    <form action="{{ route('sdm.destroy', $row->personil_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan personil ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger" title="Hapus / Nonaktifkan">
                                            <i class="bi bi-trash-fill fs-6"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"><span>Belum ada data personil laboratorium terdaftar.</span></i>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection