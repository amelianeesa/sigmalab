@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Input Hasil Uji Baru</h1>
        <a href="{{ route('hasil-uji.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('hasil-uji.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="kegiatan_id" class="form-label">Kegiatan / Kode Sampel <span class="text-danger">*</span></label>
                    <select class="form-select @error('kegiatan_id') is-invalid @enderror" id="kegiatan_id" name="kegiatan_id" required>
                        <option value="">-- Pilih Kegiatan --</option>
                        @foreach($kegiatanList as $kegiatan)
                            <option value="{{ $kegiatan->kegiatan_id }}" {{ (old('kegiatan_id') ?? ($selectedKegiatan ?? '')) == $kegiatan->kegiatan_id ? 'selected' : '' }}>
                                {{ $kegiatan->jenis_kegiatan ?? 'Kegiatan' }} - {{ $kegiatan->kode_sampel ?? 'ID: ' . $kegiatan->kegiatan_id }}
                            </option>
                        @endforeach
                    </select>
                    @error('kegiatan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="parameter_uji_id" class="form-label">Parameter Uji <span class="text-danger">*</span></label>
                    <select class="form-select @error('parameter_uji_id') is-invalid @enderror" id="parameter_uji_id" name="parameter_uji_id" required>
                        <option value="">-- Pilih Parameter Uji --</option>
                        @foreach($parameterList as $param)
                            <option value="{{ $param->parameter_uji_id }}" {{ old('parameter_uji_id') == $param->parameter_uji_id ? 'selected' : '' }}>
                                {{ $param->nama_parameter }} ({{ $param->satuan ?? '-' }}) | Batas: {{ $param->batas_bawah ?? 'Min' }} - {{ $param->batas_atas ?? 'Max' }}
                            </option>
                        @endforeach
                    </select>
                    @error('parameter_uji_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nilai_hasil" class="form-label">Nilai Hasil <span class="text-danger">*</span></label>
                    <input type="number" step="0.0001" class="form-control @error('nilai_hasil') is-invalid @enderror" id="nilai_hasil" name="nilai_hasil" value="{{ old('nilai_hasil') }}" required>
                    @error('nilai_hasil')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info mb-4" role="alert">
                    <i class="fas fa-info-circle me-1"></i> Status inlier/outlier ditentukan otomatis oleh sistem berdasarkan batas parameter.
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Hasil Uji</button>
                <a href="{{ route('hasil-uji.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
