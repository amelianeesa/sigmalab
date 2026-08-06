@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold text-dark mb-1">Tambah Personil Baru</h4>
                    <p class="text-muted small">Masukkan informasi identitas pegawai baru sesuai dengan standar perusahaan.</p>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('sdm.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">No. Induk Pegawai</label>
                            <input type="text" name="no_induk" class="form-control @error('no_induk') is-invalid @enderror" value="{{ old('no_induk') }}" placeholder="Contoh: 199802..." required>
                            @error('no_induk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap & Gelar</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Contoh: Dr. Ahmad, S.Si." required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Jabatan</label>
                                <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}" placeholder="Contoh: Analis Laboratorium" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Unit Kerja</label>
                                <input type="text" name="unit_kerja" class="form-control" value="{{ old('unit_kerja') }}" placeholder="Contoh: Lab Pengujian & Preparasi" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Unggah Dokumen CV <span class="text-muted fw-normal">(Format PDF, Maks. 2MB)</span></label>
                            <input type="file" name="file_cv" class="form-control" accept=".pdf">
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('sdm.index') }}" class="btn btn-outline-secondary px-4">Kembali</a>
                            <button type="submit" class="btn btn-primary px-4">Simpan Data Personil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection