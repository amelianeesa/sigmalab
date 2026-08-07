@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4"><i class="fas fa-vial text-primary me-2"></i>Tambah Parameter Uji</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('parameter-uji.index') }}">Parameter Uji</a></li>
        <li class="breadcrumb-item active">Tambah</li>
    </ol>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus-circle me-2"></i>Form Tambah Parameter Uji</h6>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('parameter-uji.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nama Parameter <span class="text-danger">*</span></label>
                        <input type="text" name="nama_parameter" class="form-control" value="{{ old('nama_parameter') }}" required maxlength="50">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="satuan" class="form-control" value="{{ old('satuan') }}" required maxlength="20">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Nilai Acuan <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" name="nilai_acuan" class="form-control" value="{{ old('nilai_acuan') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Batas Bawah <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" name="batas_bawah" class="form-control" value="{{ old('batas_bawah') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Batas Atas <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" name="batas_atas" class="form-control" value="{{ old('batas_atas') }}" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Metode / Kriteria</label>
                        <input type="text" name="metode_kriteria" class="form-control" value="{{ old('metode_kriteria') }}" maxlength="50">
                        <div class="form-text">Contoh: SNI 01-2891-1992</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Rumus Kalkulasi</label>
                        <textarea name="rumus_kalkulasi" class="form-control" rows="2">{{ old('rumus_kalkulasi') }}</textarea>
                        <div class="form-text">Biarkan kosong jika tidak ada rumus.</div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('parameter-uji.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Parameter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
