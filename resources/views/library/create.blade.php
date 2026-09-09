@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">Upload Dokumen</h4>
                <p class="text-muted mb-0">Tambah dokumen prosedur, formulir, atau instruksi kerja.</p>
            </div>
            <a href="{{ route('library.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('library.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Pilih kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                        {{ $category->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Dokumen</label>
                            <input type="text" name="judul" value="{{ old('judul') }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nomor Dokumen</label>
                            <input type="text" name="nomor_dokumen" value="{{ old('nomor_dokumen') }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Penerbit Dokumen</label>
                            <input type="text" name="penerbit_dokumen" value="{{ old('penerbit_dokumen') }}"
                                class="form-control" placeholder="Contoh: Sub Bagian QSHE">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Berlaku</label>
                            <input type="date" name="tanggal_berlaku"
                                value="{{ old('tanggal_berlaku', now()->toDateString()) }}" class="form-control" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" rows="4" class="form-control">{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">File Dokumen</label>
                            <input type="file" name="file" class="form-control"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" required>
                            <small class="text-muted">Maksimal 20 MB. Format: PDF, Office, JPG, atau PNG.</small>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('library.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Dokumen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection