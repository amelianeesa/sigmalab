@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-primary text-white">
        <div class="card-body p-4 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-1">{{ $personil->nama }}</h3>
                <p class="mb-0 opacity-75">{{ $personil->jabatan }} — Unit Kerja: {{ $personil->unit_kerja }} | No. Induk: {{ $personil->no_induk }}</p>
            </div>
            <div>
                <a href="{{ route('sdm.index') }}" class="btn btn-light text-primary btn-sm px-3 fw-semibold">Kembali</a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold text-dark mb-0">Matriks Kompetensi & Sertifikasi Personil</h5>
                <p class="text-muted small">Riwayat sertifikasi keahlian yang terdaftar secara resmi.</p>
            </div>
            @if(Auth::user()->role->nama_role !== 'Admin Lab')
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahSertifikat">
                    <i class="fas fa-plus me-1"></i> Tambah Sertifikasi
                </button>
            @endif
        </div>
        <div class="card-body px-4 pb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-secondary">
                        <tr>
                            <th class="py-3">Jenis Sertifikasi</th>
                            <th class="py-3">No. Sertifikat</th>
                            <th class="py-3">Tanggal Terbit</th>
                            <th class="py-3">Masa Berlaku Berakhir</th>
                            <th class="py-3">Masa Berlaku Berakhir</th>
                            <th class="py-3 text-center">Status Keaktifan</th>
                            <th class="py-3 text-center">File</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($personil->kompetensi ?? [] as $komp)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $komp->jenis_sertifikasi }}</td>
                            <td><code>{{ $komp->no_sertifikasi }}</code></td>
                            <td>{{ date('d-m-Y', strtotime($komp->tanggal_terbit)) }}</td>
                            <td>{{ date('d-m-Y', strtotime($komp->tanggal_berakhir)) }}</td>
                            <td class="text-center">
                                @if(strtotime($komp->tanggal_berakhir) >= time())
                                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Aktif / Berlaku</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">Masa Berlaku Habis</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($komp->file_sertifikat)
                                    <a href="{{ Storage::url('uploads/sertifikat/' . $komp->file_sertifikat) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat/Download"><i class="fas fa-file-alt"></i></a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center text-nowrap">
                                @if(Auth::user()->role->nama_role !== 'Admin Lab')
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditSertifikat{{ $komp->kompetensi_personil_id }}" title="Edit"><i class="fas fa-edit"></i></button>
                                    <form action="{{ route('sdm.kompetensi.destroy', [$personil->personil_id, $komp->kompetensi_personil_id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus sertifikat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="modalEditSertifikat{{ $komp->kompetensi_personil_id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('sdm.kompetensi.update', [$personil->personil_id, $komp->kompetensi_personil_id]) }}" method="POST" enctype="multipart/form-data" class="modal-content">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Sertifikasi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Nama Sertifikasi</label>
                                            <input type="text" name="jenis_sertifikasi" class="form-control form-control-sm" value="{{ $komp->jenis_sertifikasi }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">No Sertifikat</label>
                                            <input type="text" name="no_sertifikasi" class="form-control form-control-sm" value="{{ $komp->no_sertifikasi }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Tgl Terbit</label>
                                            <input type="date" name="tanggal_terbit" class="form-control form-control-sm" value="{{ date('Y-m-d', strtotime($komp->tanggal_terbit)) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Tgl Berakhir</label>
                                            <input type="date" name="tanggal_berakhir" class="form-control form-control-sm" value="{{ date('Y-m-d', strtotime($komp->tanggal_berakhir)) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Upload File Baru (Opsional)</label>
                                            <input type="file" name="file_sertifikat" class="form-control form-control-sm" accept="image/*,application/pdf">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-warning btn-sm text-white">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-exclamation fs-2 d-block mb-2"></i>
                                Belum ada data sertifikasi kompetensi yang tercatat untuk personil ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahSertifikat" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('sdm.kompetensi.store', $personil->personil_id) }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Sertifikasi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nama Sertifikasi</label>
                    <input type="text" name="jenis_sertifikasi" class="form-control form-control-sm" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">No Sertifikat</label>
                    <input type="text" name="no_sertifikasi" class="form-control form-control-sm">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Tgl Terbit</label>
                    <input type="date" name="tanggal_terbit" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Tgl Berakhir</label>
                    <input type="date" name="tanggal_berakhir" class="form-control form-control-sm">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Upload File</label>
                    <input type="file" name="file_sertifikat" class="form-control form-control-sm" accept="image/*,application/pdf">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm">Tambah Sertifikasi</button>
            </div>
        </form>
    </div>
</div>
@endsection