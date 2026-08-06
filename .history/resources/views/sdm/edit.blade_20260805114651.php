@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Edit Data Personil</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('sdm.update', $personil->personil_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">No Induk</label>
                            <input type="text" name="no_induk" class="form-control" value="{{ old('no_induk', $personil->no_induk) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Personil</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $personil->nama) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $personil->jabatan) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unit Kerja</label>
                            <input type="text" name="unit_kerja" class="form-control" value="{{ old('unit_kerja', $personil->unit_kerja) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ganti Dokumen CV (Opsional - PDF)</label>
                            <input type="file" name="file_cv" class="form-control" accept=".pdf">
                            @if($personil->file_cv)
                                <small class="text-muted">CV saat ini sudah ada.</small>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('sdm.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Perbarui Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection