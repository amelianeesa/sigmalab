@extends('layouts.app')

@section('content')
<div class="container-fluid pt-1 pb-3 px-4" style="max-width: 1100px;">
    <!-- Judul & Breadcrumb Lebih Dekat ke Topbar -->
    <h3 class="fw-bold mb-1">Evaluasi Kalibrasi</h3>
    <ol class="breadcrumb mb-3" style="font-size: 13.5px;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('evaluasi-kalibrasi.index') }}">Evaluasi Kalibrasi</a></li>
        <li class="breadcrumb-item active">Input Evaluasi</li>
    </ol>

    @if ($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Card Form dengan Warna Header Seragam dengan Topbar -->
    <div class="card shadow-sm border-0">
        <div class="card-header text-white py-2.5" style="background-color: #1b3152;">
            <h6 class="mb-0 fw-semibold"><i class="fas fa-edit me-1"></i> Form Input Evaluasi Kalibrasi</h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('evaluasi-kalibrasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <!-- Pilih Alat -->
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Pilih Alat <span class="text-danger">*</span></label>
                        <select name="alat_id" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Alat --</option>
                            @foreach ($alatList as $alat)
                                <option value="{{ $alat->alat_id }}" {{ old('alat_id') == $alat->alat_id ? 'selected' : '' }}>
                                    {{ $alat->nama_alat }} ({{ $alat->kode_alat }})
                                    @if($alat->nonaktif) — Nonaktif @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Evaluasi -->
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Tanggal Evaluasi <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_evaluasi" class="form-control form-control-sm" value="{{ old('tanggal_evaluasi', date('Y-m-d')) }}" required>
                    </div>

                    <!-- Upload Laporan -->
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Upload Laporan Evaluasi</label>
                        <input type="file" name="file_laporan" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text" style="font-size: 11.5px;">Format: PDF, JPG, JPEG, PNG (Maks. 5MB)</div>
                    </div>

                    <!-- Dropdown Keputusan -->
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Keputusan <span class="text-danger">*</span></label>
                        <select name="keputusan" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Keputusan --</option>
                            <option value="Layak" {{ old('keputusan') == 'Layak' ? 'selected' : '' }}>Layak</option>
                            <option value="Tidak Layak" {{ old('keputusan') == 'Tidak Layak' ? 'selected' : '' }}>Tidak Layak</option>
                            <option value="Layak dengan penambahan faktor koreksi" {{ old('keputusan') == 'Layak dengan penambahan faktor koreksi' ? 'selected' : '' }}>Layak dengan penambahan faktor koreksi</option>
                        </select>
                    </div>

                    <!-- Catatan Spesifikasi -->
                    <div class="col-md-12">
                        <label class="form-label small fw-bold">Catatan Spesifikasi</label>
                        <textarea name="catatan_spesifikasi" class="form-control form-control-sm" rows="3" placeholder="Tuliskan catatan spesifikasi hasil evaluasi...">{{ old('catatan_spesifikasi') }}</textarea>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-sm text-white px-4" style="background-color: #1b3152;">
                        <i class="fas fa-save me-1"></i> Simpan Evaluasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection