@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold text-dark mb-1">Edit Data Personil</h4>
                    <p class="text-muted small">Perbarui informasi data pegawai.</p>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="{{ route('sdm.update', $personil->personil_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">No. Induk Pegawai</label>
                            <input type="text" name="no_induk" class="form-control" value="{{ old('no_induk', $personil->no_induk) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap & Gelar</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $personil->nama) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Jabatan</label>
                                <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $personil->jabatan) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Unit Kerja</label>
                                <input type="text" name="unit_kerja" class="form-control" value="{{ old('unit_kerja', $personil->unit_kerja) }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Ganti Dokumen CV <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="file" name="file_cv" class="form-control" accept=".pdf">
                            @if($personil->file_cv)
                                <div class="form-text text-success mt-1">
                                    <i class="bi bi-check-circle"></i> CV saat ini sudah tersimpan di sistem.
                                </div>
                            @endif
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