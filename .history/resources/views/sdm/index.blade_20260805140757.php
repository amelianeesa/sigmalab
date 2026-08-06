@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">SDM & Kompetensi</h2>
            <p class="text-muted mb-0">Profil personil, matriks kompetensi & sertifikasi</p>
        </div>
        <div>
            <!-- Tombol Pemicu Modal -->
            <button type="button" class="btn btn-dark shadow-sm px-3 fw-semibold rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambahPersonil">
                <i class="bi bi-plus-lg me-1"></i> Tambah Personil
            </button>
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
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>Belum ada data personil laboratorium terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL TAMBAH PERSONIL ================= -->
<div class="modal fade" id="modalTambahPersonil" tabindex="-1" aria-labelledby="modalTambahPersonilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white px-4 py-3">
                <h5 class="modal-title fw-bold" id="modalTambahPersonilLabel">Tambah Personil Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('sdm.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">No. Induk Pegawai</label>
                        <input type="text" name="no_induk" class="form-control" value="{{ old('no_induk') }}" placeholder="Contoh: 199802..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Nama Lengkap & Gelar</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Contoh: Dr. Ahmad, S.Si." required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-dark">Jabatan / Posisi</label>
                            <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}" placeholder="Contoh: Analis Lab Kimia" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-dark">Unit Kerja / Lab</label>
                            <input type="text" name="unit_kerja" class="form-control" value="{{ old('unit_kerja') }}" placeholder="Contoh: Lab Pengujian & Preparasi" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Unggah Dokumen CV <span class="text-muted fw-normal">(Format PDF, Maks. 2MB)</span></label>
                        <input type="file" name="file_cv" class="form-control" accept=".pdf">
                    </div>
                </div>

                <div class="modal-footer bg-light px-4 py-3 border-0">
                    <button type="button" class="btn btn-light border px-4 text-secondary fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark px-4 fw-semibold shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection