@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah Data Personil Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('sdm.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">No Induk</label>
                            <input type="text" name="no_induk" class="form-control" value="{{ old('no_induk') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Personil</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unit Kerja</label>
                            <input type="text" name="unit_kerja" class="form-control" value="{{ old('unit_kerja') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unggah Dokumen CV (PDF)</label>
                            <input type="file" name="file_cv" class="form-control" accept=".pdf">
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('sdm.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-success">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection