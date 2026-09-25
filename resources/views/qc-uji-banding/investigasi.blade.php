@extends('layouts.app')
@section('title', 'Investigasi - QC Uji Banding')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="Uji Banding">
        <li class="breadcrumb-item"><a href="{{ route('qc-uji-banding.show', $program->id) }}" class="text-decoration-none">{{ $program->nama_program }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('qc-uji-banding.ringkasan', $program->id) }}" class="text-decoration-none">Ringkasan Unjuk Kerja</a></li>
        <li class="breadcrumb-item active" aria-current="page">Lembar Ketidaksesuaian</li>
    </x-qc-breadcrumb>

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="fas fa-file-alt text-danger me-2"></i>Lembar Ketidaksesuaian (LKS) / CAPA</h2>
        <p class="text-muted">Investigasi untuk parameter <span class="fw-bold text-dark">{{ $parameter->parameterUji->nama_parameter }}</span> pada Uji Banding <span class="fw-bold text-dark">{{ $program->nama_program }}</span>.</p>
    </div>

    <form action="{{ route('qc-uji-banding.store-investigasi', [$program->id, $parameter->id]) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card shadow-sm border-danger mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0 fw-bold"><i class="fas fa-search me-2"></i>Bagian 1: Analisis Akar Masalah</h5>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-2">Jelaskan secara detail faktor utama yang menyebabkan hasil lab ({{ $parameter->nilai_akhir }}) menyimpang dari Z-Score/Target Vendor (Z: {{ $parameter->z_score ?? '-' }}).</p>
                <textarea name="akar_masalah" class="form-control" rows="5" required placeholder="Contoh: Kondisi alat belum dikalibrasi ulang, suhu ruangan tidak stabil... ">{{ $parameter->akar_masalah }}</textarea>
            </div>
        </div>

        <div class="card shadow-sm border-primary mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 fw-bold"><i class="fas fa-tools me-2"></i>Bagian 2: Tindakan Perbaikan </h5>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-2">Tindakan langsung yang diambil untuk mengoreksi ketidaksesuaian saat ini.</p>
                <textarea name="tindakan_perbaikan" class="form-control" rows="4" required placeholder="Contoh: Melakukan kalibrasi ulang pada timbangan, memanaskan oven lebih lama...">{{ $parameter->tindakan_perbaikan }}</textarea>
            </div>
        </div>

        <div class="card shadow-sm border-success mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0 fw-bold"><i class="fas fa-shield-alt me-2"></i>Bagian 3: Tindakan Pencegahan</h5>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-2">Tindakan proaktif untuk mencegah masalah yang sama terulang di masa depan.</p>
                <textarea name="tindakan_pencegahan" class="form-control" rows="4" placeholder="Contoh: Membuat jadwal kalibrasi internal setiap 2 minggu...">{{ $parameter->tindakan_pencegahan }}</textarea>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('qc-uji-banding.show', $program->id) }}" class="btn btn-outline-secondary px-4">Batal</a>
            <button type="submit" class="btn btn-danger px-4 fw-bold"><i class="fas fa-save me-2"></i> Simpan LKS</button>
        </div>
    </form>
</div>
@endsection

