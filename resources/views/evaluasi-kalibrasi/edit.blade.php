@extends('layouts.app')

@push('styles')
<style>
    /* Mengatur jarak atas container agar tidak terlalu jauh dari topbar */
    .container-fluid {
        padding-top: 2px !important;
    }
    .card-body {
        padding: 0.85rem 1.1rem !important;
    }
    .form-label {
        font-size: 0.75rem !important;
        font-weight: bold;
        margin-bottom: 0.2rem !important;
        color: #495057;
    }
    .form-control, .form-select {
        font-size: 0.75rem !important;
        padding: 0.25rem 0.5rem !important;
        height: auto !important;
    }
    .form-text {
        font-size: 0.68rem !important;
    }
    textarea.form-control {
        font-size: 0.75rem !important;
    }
    .card-shadow-custom {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
        border: 1px solid #e3e6f0;
    }
    /* Memastikan tombol bernuansa biru tua solid korporat #1b3152 */
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
@endpush

@section('content')
<div class="container-fluid pt-0 pb-3 px-4" style="max-width: 950px;">

    @if ($errors->any())
        <div class="alert alert-danger py-2 px-3 mb-2 shadow-sm" style="font-size: 0.78rem;">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm card-shadow-custom bg-white">
        <div class="card-header text-white fw-bold py-2 px-3 d-flex align-items-center" style="background-color: #1b3152; font-size: 0.85rem;">
            <i class="fas fa-edit me-2"></i> Form Edit Evaluasi Kalibrasi
        </div>
        <div class="card-body">
            <form action="{{ route('evaluasi-kalibrasi.update', $evaluasi->evaluasi_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label">Pilih Alat <span class="text-danger">*</span></label>
                        <select name="alat_id" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Alat --</option>
                            @foreach ($alatList as $alat)
                                <option value="{{ $alat->alat_id }}" {{ old('alat_id', $evaluasi->alat_id) == $alat->alat_id ? 'selected' : '' }}>
                                    {{ $alat->nama_alat }} ({{ $alat->kode_alat }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Evaluasi -->
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Evaluasi <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_evaluasi" class="form-control form-control-sm" value="{{ old('tanggal_evaluasi', $evaluasi->tanggal_evaluasi ? $evaluasi->tanggal_evaluasi->format('Y-m-d') : '') }}" required>
                    </div>

                    <!-- File Laporan -->
                    <div class="col-md-6">
                        <label class="form-label">Upload Laporan Evaluasi (Opsional)</label>
                        <input type="file" name="file_laporan" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text text-muted">Format: PDF, JPG, PNG (Maks. 5MB).</div>

                        <!-- Laporan Saat Ini -->
                        @if($evaluasi->file_laporan)
                            <div class="mt-2 p-2 border rounded bg-light d-flex align-items-center justify-content-between shadow-sm">
                                <div class="d-flex align-items-center text-truncate me-2">
                                    <i class="fas fa-file-pdf text-danger fa-lg me-2"></i>
                                    <div class="text-truncate">
                                        <small class="d-block text-muted" style="font-size: 10px;">Laporan Terunggah Saat Ini:</small>
                                        <span class="text-dark fw-semibold text-truncate d-inline-block" style="font-size: 11px; max-width: 220px;">{{ basename($evaluasi->file_laporan) }}</span>
                                    </div>
                                </div>
                                <!-- Tombol Lihat dengan Inline CSS Biru Dongker -->
                                <a href="{{ asset('storage/' . $evaluasi->file_laporan) }}" target="_blank" class="btn btn-sm text-nowrap py-1 px-2 shadow-sm d-flex align-items-center text-white" style="background-color: #1b3152 !important; border-color: #1b3152 !important; font-size: 11px; gap: 5px;">
                                    <i class="fas fa-eye text-white"></i> <span class="text-white">Lihat</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Keputusan -->
                    <div class="col-md-6">
                        <label class="form-label">Keputusan <span class="text-danger">*</span></label>
                        <select name="keputusan" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Keputusan --</option>
                            <option value="Layak" {{ old('keputusan', $evaluasi->keputusan) == 'Layak' ? 'selected' : '' }}>Layak</option>
                            <option value="Tidak Layak" {{ old('keputusan', $evaluasi->keputusan) == 'Tidak Layak' ? 'selected' : '' }}>Tidak Layak</option>
                            <option value="Layak dengan penambahan faktor koreksi" {{ old('keputusan', $evaluasi->keputusan) == 'Layak dengan penambahan faktor koreksi' ? 'selected' : '' }}>Layak dengan penambahan faktor koreksi</option>
                        </select>
                    </div>

                    <!-- Komentar -->
                    <div class="col-md-12">
                        <label class="form-label">Komentar</label>
                        <textarea name="catatan_spesifikasi" class="form-control" rows="3" placeholder="Tuliskan catatan spesifikasi...">{{ old('catatan_spesifikasi', $evaluasi->catatan_spesifikasi) }}</textarea>
                    </div>
                </div>

                <!-- Tombol Aksi Bawah -->
                <div class="mt-3 text-end pt-2 border-top">
                    <a href="{{ route('evaluasi-kalibrasi.index') }}" class="btn btn-secondary btn-sm me-1 px-3 shadow-sm" style="font-size: 0.75rem;">Batal</a>
                    <!-- Tombol Perbarui dengan Inline CSS Biru Dongker -->
                    <button type="submit" class="btn btn-sm px-3 fw-bold shadow-sm text-white" style="background-color: #1b3152 !important; border-color: #1b3152 !important; font-size: 0.75rem;">
                        <i class="fas fa-save me-1"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection