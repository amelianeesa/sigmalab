@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Edit Dokumen</h4>
            <p class="text-muted mb-0">Perbarui metadata dokumen library digital.</p>
        </div>
        <a href="{{ route('library.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('library.update', $document->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $document->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Judul Dokumen</label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul', $document->judul) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nomor Dokumen</label>
                        <input type="text" name="nomor_dokumen" class="form-control" value="{{ old('nomor_dokumen', $document->nomor_dokumen) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Penerbit Dokumen</label>
                        <input type="text" name="penerbit_dokumen" class="form-control" value="{{ old('penerbit_dokumen', $document->penerbit_dokumen) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Berlaku</label>
                        <input type="date" name="tanggal_berlaku" class="form-control" value="{{ old('tanggal_berlaku', $latestVersion?->tanggal_berlaku?->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="form-control">{{ old('deskripsi', $document->deskripsi) }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="alert alert-light border mb-0 small">
                            <i class="fas fa-info-circle me-1"></i>
                            Untuk mengganti file dokumen, gunakan tombol <strong>Revisi Baru</strong> agar versi lama tetap tersimpan.
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('library.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
