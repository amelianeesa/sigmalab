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
                                <label class="form-label fw-semibold">Kategori Personil</label>
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
                                    <a href="{{ route('sdm.cv', $personil->personil_id) }}" target="_blank" class="ms-1">Lihat CV</a>
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
@endsection