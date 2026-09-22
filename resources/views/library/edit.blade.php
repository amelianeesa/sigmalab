@extends('layouts.app')

@section('content')
<style>
    .dashboard-container {
        padding: 4px 20px !important;
    }
    .form-label {
        font-size: 0.7rem !important;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.2rem !important;
    }
    .form-control-sm, .form-select-sm {
        font-size: 0.72rem !important;
        padding: 4px 8px !important;
    }
    .card-body {
        padding: 12px !important;
    }
    .btn-corporate-blue {
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
        color: #ffffff !important;
    }
    .btn-corporate-blue:hover, 
    .btn-corporate-blue:focus, 
    .btn-corporate-blue:active {
        background-color: #14253e !important;
        border-color: #14253e !important;
        color: #ffffff !important;
    }
</style>

<div class="container-fluid dashboard-container">
    <div class="d-flex justify-content-between align-items-center mb-2 mt-1 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-0">Edit Dokumen</h4>
            <p class="text-muted small mb-0" style="font-size: 0.72rem;">Perbarui metadata dokumen library digital.</p>
        </div>
        <a href="{{ route('library.index') }}" class="btn btn-outline-secondary btn-sm py-1" style="font-size: 0.72rem;">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('library.update', $document->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" class="form-select form-select-sm" required>
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
                        <input type="text" name="judul" class="form-control form-control-sm" value="{{ old('judul', $document->judul) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nomor Dokumen</label>
                        <input type="text" name="nomor_dokumen" class="form-control form-control-sm" value="{{ old('nomor_dokumen', $document->nomor_dokumen) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Penerbit Dokumen</label>
                        <input type="text" name="penerbit_dokumen" class="form-control form-control-sm" value="{{ old('penerbit_dokumen', $document->penerbit_dokumen) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Berlaku</label>
                        <input type="date" name="tanggal_berlaku" class="form-control form-control-sm" value="{{ old('tanggal_berlaku', $latestVersion?->tanggal_berlaku?->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="form-control form-control-sm" style="font-size: 0.72rem !important;">{{ old('deskripsi', $document->deskripsi) }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="alert alert-light border mb-0 py-2 px-3 small" style="font-size: 0.7rem;">
                            <i class="fas fa-info-circle me-1 text-primary"></i>
                            Untuk mengganti file dokumen, gunakan tombol <strong>Revisi Baru</strong> agar versi lama tetap tersimpan.
                        </div>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-end gap-2">
                    <a href="{{ route('library.index') }}" class="btn btn-outline-secondary btn-sm py-1 px-3" style="font-size: 0.72rem;">Batal</a>
                    <button type="submit" class="btn btn-corporate-blue btn-sm py-1 px-3 shadow-sm" style="font-size: 0.72rem;">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection