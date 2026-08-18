@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Revisi Dokumen</h4>
            <p class="text-muted mb-0">Upload versi terbaru untuk {{ $document->judul }}.</p>
        </div>
        <a href="{{ route('library.show', $document->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('library.revision.store', $document->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Catatan Revisi</label>
                        <textarea name="catatan_revisi" rows="4" class="form-control" placeholder="Contoh: Perubahan prosedur untuk menyesuaikan persyaratan akreditasi..."></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Berlaku</label>
                        <input type="date" name="tanggal_berlaku" value="{{ old('tanggal_berlaku', now()->toDateString()) }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">File Versi Baru</label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" required>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('library.show', $document->id) }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Revisi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
