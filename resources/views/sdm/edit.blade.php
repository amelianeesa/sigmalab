@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold text-dark mb-1">Edit Data Personil</h4>
                    <p class="text-muted small mb-0">Perbarui profil, dokumen, dan sertifikasi terakhir personil.</p>
                </div>
                <div class="card-body px-4 pb-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('sdm.update', $personil->personil_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">No. Induk Pegawai</label>
                            <input type="text" name="no_induk" class="form-control" value="{{ old('no_induk', $personil->no_induk) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $personil->nama) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-semibold mb-0">Kategori Personil</label>
                                    <button type="button" class="btn btn-sm btn-outline-dark rounded-pill py-0 px-2" data-bs-toggle="modal" data-bs-target="#modalTambahKategori" style="font-size: 12px;">
                                        + Kategori Baru
                                    </button>
                                </div>
                                <select name="kategori_personil" class="form-select">
                                    <option value="">— Pilih Kategori —</option>
                                    @foreach($kategoriOptions as $value => $label)
                                        <option value="{{ $value }}" {{ old('kategori_personil', $personil->kategori_personil) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Penempatan</label>
                                <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $personil->jabatan) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Unit Kerja</label>
                            <input type="text" name="unit_kerja" class="form-control" value="{{ old('unit_kerja', $personil->unit_kerja) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Ganti Dokumen CV <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="file" name="file_cv" class="form-control" accept="image/*,application/pdf">
                            @if($personil->file_cv)
                                <div class="form-text text-success mt-1">
                                    <i class="bi bi-check-circle"></i> CV saat ini sudah tersimpan di sistem.
                                    <a href="{{ route('sdm.cv', $personil->personil_id) }}?v={{ $personil->updated_at?->timestamp }}" target="_blank" class="ms-1">Lihat CV</a>
                                </div>
                            @endif
                        </div>

                        <div class="border-top pt-4 mt-2">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="rounded-circle bg-light text-primary d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px;"><i class="bi bi-award"></i></span>
                                <div>
                                    <h6 class="fw-bold mb-0">Sertifikasi Terakhir</h6>
                                    <span class="text-muted small">Kosongkan bila belum ada sertifikasi.</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Sertifikasi / Pelatihan</label>
                                <input type="text" name="nama_sertifikasi" class="form-control" value="{{ old('nama_sertifikasi', $sertifikasi?->jenis_sertifikasi) }}" placeholder="mis. Pelatihan K3 Laboratorium">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nomor Sertifikat</label>
                                <input type="text" name="no_sertifikasi" class="form-control" value="{{ old('no_sertifikasi', $sertifikasi?->no_sertifikasi) }}" placeholder="mis. K3-LAB/2026/001">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Tanggal Terbit</label>
                                    <input type="date" name="tanggal_terbit" class="form-control" value="{{ old('tanggal_terbit', $sertifikasi?->tanggal_terbit?->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Tanggal Berakhir</label>
                                    <input type="date" name="tanggal_berakhir" class="form-control" value="{{ old('tanggal_berakhir', $sertifikasi?->tanggal_berakhir?->format('Y-m-d')) }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('sdm.index') }}" class="btn btn-outline-secondary px-4">Kembali</a>
                            <button type="submit" class="btn btn-warning px-4 text-white">Perbarui Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-labelledby="modalTambahKategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-white px-4 py-3 border-bottom">
                <h5 class="modal-title fw-bold text-dark" id="modalTambahKategoriLabel">Kelola Kategori Personil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-white">
                @if(count($kategoriOptions))
                <label class="form-label fw-semibold text-dark small mb-2">Kategori Tersedia</label>
                <ul class="list-group mb-4">
                    @foreach($kategoriOptions as $kode => $label)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                            <span>{{ $label }}</span>
                            <form action="{{ route('sdm.kategori.destroy', $kode) }}" method="POST" class="m-0"
                                  onsubmit="return confirm('Hapus kategori &quot;{{ $label }}&quot;? Kategori hanya bisa dihapus jika belum dipakai personil manapun.')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus kategori">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
                <hr>
                @endif

                <form action="{{ route('sdm.kategori.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                    <label class="form-label fw-semibold text-dark small">Tambah Kategori Baru</label>
                    <input type="text" name="nama_kategori" class="form-control" placeholder="mis. Supervisor Lab, QC Inspector, dll" required>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-dark px-4 fw-semibold shadow-sm btn-sm">Simpan Kategori</button>
                    </div>
                </form>
            </div>

            <div class="modal-footer bg-light px-4 py-3 border-top">
                <button type="button" class="btn btn-light border px-4 text-secondary fw-semibold btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection