@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Edit Evaluasi Kalibrasi</h1>
        <a href="{{ route('evaluasi-kalibrasi.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('evaluasi-kalibrasi.update', $evaluasi->evaluasi_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Pilih Alat -->
                    <div class="col-md-6">
                        <label class="form-label">Pilih Alat <span class="text-danger">*</span></label>
                        <select name="alat_id" class="form-select" required>
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
                        <input type="date" name="tanggal_evaluasi" class="form-control" value="{{ old('tanggal_evaluasi', $evaluasi->tanggal_evaluasi ? $evaluasi->tanggal_evaluasi->format('Y-m-d') : '') }}" required>
                    </div>

                    <!-- File Laporan -->
                    <div class="col-md-6">
                        <label class="form-label">Upload Laporan Evaluasi (Opsional)</label>
                        <input type="file" name="file_laporan" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text">Format: PDF, JPG, PNG (Maks. 5MB).</div>

                        <!-- Laporan Saat Ini -->
                        @if($evaluasi->file_laporan)
                            <div class="mt-2 p-2 border rounded bg-light d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center text-truncate">
                                    <i class="fas fa-file-alt text-primary fa-lg me-2"></i>
                                    <div class="text-truncate">
                                        <small class="d-block text-muted" style="font-size: 11px;">Laporan Terunggah Saat Ini:</small>
                                        <span class="text-dark fw-semibold" style="font-size: 13px;">{{ basename($evaluasi->file_laporan) }}</span>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $evaluasi->file_laporan) }}" target="_blank" class="btn btn-sm btn-outline-primary text-nowrap ms-2">
                                    <i class="fas fa-eye me-1"></i> Lihat File
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Keputusan (Hanya 3 Pilihan) -->
                    <div class="col-md-6">
                        <label class="form-label">Keputusan <span class="text-danger">*</span></label>
                        <select name="keputusan" class="form-select" required>
                            <option value="">-- Pilih Keputusan --</option>
                            <option value="Layak" {{ old('keputusan', $evaluasi->keputusan) == 'Layak' ? 'selected' : '' }}>Layak</option>
                            <option value="Tidak Layak" {{ old('keputusan', $evaluasi->keputusan) == 'Tidak Layak' ? 'selected' : '' }}>Tidak Layak</option>
                            <option value="Layak dengan penambahan faktor koreksi" {{ old('keputusan', $evaluasi->keputusan) == 'Layak dengan penambahan faktor koreksi' ? 'selected' : '' }}>Layak dengan penambahan faktor koreksi</option>
                        </select>
                    </div>

                    <!-- Komentar -->
                    <div class="col-md-12">
                        <label class="form-label">Komentar</label>
                        <textarea name="catatan_spesifikasi" class="form-control" rows="4" placeholder="Tuliskan catatan spesifikasi...">{{ old('catatan_spesifikasi', $evaluasi->catatan_spesifikasi) }}</textarea>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('evaluasi-kalibrasi.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-warning text-dark">
                        <i class="fas fa-save me-1"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection