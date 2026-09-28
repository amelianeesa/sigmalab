@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
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
    .flatpickr-input {
        background-color: #fff !important;
    }
</style>

<div class="container-fluid dashboard-container">
    <div class="d-flex justify-content-between align-items-center mb-2 mt-1 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Revisi Dokumen</h4>
            <p class="text-muted small mb-0" style="font-size: 0.72rem;">Upload versi terbaru untuk {{ $document->judul }}.</p>
        </div>
        <a href="{{ route('library.show', $document->id) }}" class="btn btn-outline-secondary btn-sm py-1" style="font-size: 0.72rem;">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('library.revision.store', $document->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-2">
                    <div class="col-12">
                        <label class="form-label">Catatan Revisi</label>
                        <textarea name="catatan_revisi" rows="3" class="form-control form-control-sm" style="font-size: 0.72rem !important;" placeholder="Contoh: Perubahan prosedur untuk menyesuaikan persyaratan akreditasi..."></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Berlaku</label>
                        <input type="text" name="tanggal_berlaku" value="{{ old('tanggal_berlaku', now()->toDateString()) }}" class="form-control form-control-sm flatpickr-date" autocomplete="off">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">File Versi Baru</label>
                        <input type="file" name="file" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" required>
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-end gap-2">
                    <a href="{{ route('library.show', $document->id) }}" class="btn btn-outline-secondary btn-sm py-1 px-3" style="font-size: 0.72rem;">Batal</a>
                    <button type="submit" class="btn btn-corporate-blue btn-sm py-1 px-3 shadow-sm" style="font-size: 0.72rem;">
                        <i class="fas fa-save me-1"></i> Simpan Revisi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
    $(function () {
        flatpickr.localize(flatpickr.l10ns.id);
        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd F Y',
            allowInput: true,
            disableMobile: true
        });
    });
</script>
@endpush
@endsection