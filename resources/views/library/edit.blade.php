@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
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
        font-size: 0.72rem !important;
    }
    .flatpickr-calendar {
        font-size: 0.8rem;
    }

    .filter-select {
        position: relative;
        font-size: 0.72rem;
    }
    .filter-select-trigger {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 4px 8px;
        font-size: 0.72rem;
        cursor: pointer;
        text-align: left;
        color: #212529;
    }
    .filter-select-trigger:after {
        content: "";
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid #6c757d;
        margin-left: 6px;
        flex-shrink: 0;
    }
    .filter-select.open .filter-select-trigger {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
    }
    .filter-select-options {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1060;
        margin-top: 2px;
        max-height: 220px;
        overflow-y: auto;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.12);
        list-style: none;
        padding: 4px 0;
    }
    .filter-select.open .filter-select-options {
        display: block;
    }
    .filter-select-options li {
        padding: 6px 10px;
        font-size: 0.72rem;
        cursor: pointer;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .filter-select-options li:hover {
        background-color: #f1f3f5;
    }
    .filter-select-options li.selected {
        background-color: #0d6efd;
        color: #fff;
    }
</style>

<div class="container-fluid dashboard-container">
    <div class="d-flex justify-content-between align-items-center mb-2 mt-1 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Edit Dokumen</h4>
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
                        @php
                            $selectedCategoryId = old('category_id', $document->category_id);
                        @endphp
                        <div class="filter-select" id="selectCategory">
                            <input type="hidden" name="category_id" required value="{{ $selectedCategoryId }}">
                            <button type="button" class="filter-select-trigger">
                                {{ $categories->firstWhere('id', $selectedCategoryId)->nama_kategori ?? 'Pilih kategori' }}
                            </button>
                            <ul class="filter-select-options">
                                <li data-value="">Pilih kategori</li>
                                @foreach($categories as $category)
                                    <li data-value="{{ $category->id }}" class="{{ (string) $selectedCategoryId === (string) $category->id ? 'selected' : '' }}">{{ $category->nama_kategori }}</li>
                                @endforeach
                            </ul>
                        </div>
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
                        <input type="text" name="tanggal_berlaku" id="tglBerlaku" class="form-control form-control-sm flatpickr-date" value="{{ old('tanggal_berlaku', $latestVersion?->tanggal_berlaku?->format('Y-m-d')) }}" placeholder="dd/mm/yyyy" autocomplete="off" required>
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

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr('#tglBerlaku', {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            allowInput: true
        });

        document.querySelectorAll('.filter-select').forEach(function (wrapper) {
            const trigger = wrapper.querySelector('.filter-select-trigger');
            const hiddenInput = wrapper.querySelector('input[type="hidden"]');
            const options = wrapper.querySelectorAll('.filter-select-options li');

            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                document.querySelectorAll('.filter-select.open').forEach(function (other) {
                    if (other !== wrapper) other.classList.remove('open');
                });
                wrapper.classList.toggle('open');
            });

            options.forEach(function (li) {
                li.addEventListener('click', function () {
                    hiddenInput.value = li.getAttribute('data-value');
                    trigger.textContent = li.textContent;
                    options.forEach(function (o) { o.classList.remove('selected'); });
                    li.classList.add('selected');
                    wrapper.classList.remove('open');
                });
            });
        });

        document.addEventListener('click', function () {
            document.querySelectorAll('.filter-select.open').forEach(function (wrapper) {
                wrapper.classList.remove('open');
            });
        });
    });
</script>
@endpush
@endsection