@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Catat Tindak Lanjut Baru</h1>
        <a href="{{ route('tindak-lanjut.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tindak Lanjut</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('tindak-lanjut.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="hasil_uji_id" class="form-label">Hasil Uji (Outlier) <span class="text-danger">*</span></label>
                    <select name="hasil_uji_id" id="hasil_uji_id" class="form-select @error('hasil_uji_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Hasil Uji --</option>
                        @foreach($hasilUjiOutlier as $hu)
                            <option value="{{ $hu->hasil_uji_id }}" {{ (old('hasil_uji_id', $selectedHasilUji ?? '') == $hu->hasil_uji_id) ? 'selected' : '' }}>
                                ID #{{ $hu->hasil_uji_id }} - Parameter: {{ $hu->parameterUji->nama_parameter ?? '-' }} - Kegiatan: {{ $hu->kegiatan->kode_sampel ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                    @error('hasil_uji_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="status_tindak_lanjut" class="form-label">Status Tindak Lanjut <span class="text-danger">*</span></label>
                    <select name="status_tindak_lanjut" id="status_tindak_lanjut" class="form-select @error('status_tindak_lanjut') is-invalid @enderror" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="belum_ditindaklanjuti" {{ old('status_tindak_lanjut') == 'belum_ditindaklanjuti' ? 'selected' : '' }}>Belum Ditindaklanjuti</option>
                        <option value="dalam_investigasi" {{ old('status_tindak_lanjut') == 'dalam_investigasi' ? 'selected' : '' }}>Dalam Investigasi</option>
                        <option value="selesai" {{ old('status_tindak_lanjut') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status_tindak_lanjut')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="catatan_investigasi" class="form-label">Catatan Investigasi <span class="text-danger">*</span></label>
                    <textarea name="catatan_investigasi" id="catatan_investigasi" rows="4" class="form-control @error('catatan_investigasi') is-invalid @enderror" required>{{ old('catatan_investigasi') }}</textarea>
                    @error('catatan_investigasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
