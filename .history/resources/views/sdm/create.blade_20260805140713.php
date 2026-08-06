@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <!-- Header Navigasi Kecil / Breadcrumb -->
            <div class="mb-3">
                <a href="{{ route('sdm.index') }}" class="text-decoration-none text-muted small fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Manajemen SDM
                </a>
            </div>

            <!-- Card Form Utama -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white p-4">
                    <h4 class="fw-bold mb-1">Tambah Personil Baru</h4>
                    <p class="text-white-50 small mb-0">Masukkan informasi identitas pegawai baru sesuai dengan standar perusahaan.</p>
                </div>
                
                <div class="card-body p-4 p-md-5 bg-white">
                    <form action="{{ route('sdm.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">No. Induk Pegawai</label>
                            <input type="text" name="no_induk" class="form-control @error('no_induk') is-invalid @enderror" value="{{ old('no_induk') }}" placeholder="Contoh: 199802..." required>
                            @error('no_induk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Nama Lengkap & Gelar</label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Contoh: Dr. Ahmad, S.Si." required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark">Jabatan / Posisi</label>
                                <input type="text" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan') }}" placeholder="Contoh: Analis Lab Kimia" required>
                                @error('jabatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-dark">Unit Kerja / Lab</label>
                                <input type="text" name="unit_kerja" class="form-control @error('unit_kerja') is-invalid @enderror" value="{{ old('unit_kerja') }}" placeholder="Contoh: Lab Pengujian & Preparasi" required>
                                @error('unit_kerja')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">Unggah Dokumen CV <span class="text-muted fw-normal">(Format PDF, Maks. 2MB)</span></label>
                            <input type="file" name="file_cv" class="form-control @error('file_cv') is-invalid @enderror" accept=".pdf">
                            @error('file_cv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end align-items-center gap-2 pt-3 border-top">
                            <a href="{{ route('sdm.index') }}" class="btn btn-light px-4 border text-secondary fw-semibold">Batal</a>
                            <button type="submit" class="btn btn-dark px-4 fw-semibold shadow-sm">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection