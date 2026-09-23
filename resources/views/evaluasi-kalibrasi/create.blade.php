@extends('layouts.app')

@section('content')
<div class="container py-3">
    <h1>Evaluasi Kalibrasi</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('evaluasi-kalibrasi.index') }}">Evaluasi Kalibrasi</a></li>
        <li class="breadcrumb-item active">Input Evaluasi</li>
    </ol>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Form Input Evaluasi Kalibrasi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('evaluasi-kalibrasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <!-- Pilih Alat -->
                    <div class="col-md-6">
                        <label class="form-label">Pilih Alat <span class="text-danger">*</span></label>
                        <select name="alat_id" class="form-select" required>
                            <option value="">-- Pilih Alat --</option>
                            @foreach ($alatList as $alat)
                                <option value="{{ $alat->alat_id }}" {{ old('alat_id') == $alat->alat_id ? 'selected' : '' }}>
                                    {{ $alat->nama_alat }} ({{ $alat->kode_alat }})
                                    @if($alat->nonaktif) — Nonaktif @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Evaluasi (Tetap Ada) -->
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Evaluasi <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_evaluasi" class="form-control" value="{{ old('tanggal_evaluasi', date('Y-m-d')) }}" required>
                    </div>

                    <!-- Upload Laporan -->
                    <div class="col-md-6">
                        <label class="form-label">Upload Laporan Evaluasi</label>
                        <input type="file" name="file_laporan" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text">Format: PDF, JPG, JPEG, PNG (Maks. 5MB)</div>
                    </div>

                    <!-- Dropdown Keputusan -->
                    <div class="col-md-6">
                        <label class="form-label">Keputusan <span class="text-danger">*</span></label>
                        <select name="keputusan" class="form-select" required>
                            <option value="">-- Pilih Keputusan --</option>
                            <option value="Layak" {{ old('keputusan') == 'Layak' ? 'selected' : '' }}>Layak</option>
                            <option value="Tidak Layak" {{ old('keputusan') == 'Tidak Layak' ? 'selected' : '' }}>Tidak Layak</option>
                            <option value="Layak dengan penambahan faktor koreksi" {{ old('keputusan') == 'Layak dengan penambahan faktor koreksi' ? 'selected' : '' }}>Layak dengan penambahan faktor koreksi</option>
                        </select>
                    </div>

                    <!-- Catatan Spesifikasi -->
                    <div class="col-md-12">
                        <label class="form-label">Catatan Spesifikasi</label>
                        <textarea name="catatan_spesifikasi" class="form-control" rows="4" placeholder="Tuliskan catatan spesifikasi hasil evaluasi...">{{ old('catatan_spesifikasi') }}</textarea>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Evaluasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection