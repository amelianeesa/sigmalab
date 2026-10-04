@extends('layouts.app')
@section('title', 'Investigasi - QC Uji Banding')

@section('content')
@include('qc-uji-banding.partials._style')

<div class="container-fluid qc-page pb-4">
    <x-qc-breadcrumb active="Uji Banding">
        <li class="breadcrumb-item"><a href="{{ route('qc-uji-banding.show', $program->id) }}" class="text-decoration-none">{{ $program->nama_program }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('qc-uji-banding.ringkasan', $program->id) }}" class="text-decoration-none">Ringkasan Unjuk Kerja</a></li>
        <li class="breadcrumb-item active" aria-current="page">Lembar Ketidaksesuaian</li>
    </x-qc-breadcrumb>

    <div class="mb-3">
        <h5 class="qc-title"><i class="fas fa-file-alt text-danger me-1"></i> Lembar Ketidaksesuaian (LKS) / CAPA</h5>
        <p class="qc-subtitle">Investigasi untuk parameter <span class="fw-bold text-dark">{{ $parameter->parameterUji->nama_parameter }}</span> pada Uji Banding <span class="fw-bold text-dark">{{ $program->nama_program }}</span>.</p>
    </div>

    <form action="{{ route('qc-uji-banding.store-investigasi', [$program->id, $parameter->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm mb-3 border-start border-4 border-danger">
            <div class="card-header text-white" style="background-color: #1b3152;">
                <h5><i class="fas fa-search me-1"></i> Bagian 1: Analisis Akar Masalah</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2" style="font-size: 0.75rem;">Jelaskan secara detail faktor utama yang menyebabkan hasil lab ({{ $parameter->nilai_akhir }}) menyimpang dari Z-Score/Target Vendor (Z: {{ $parameter->z_score ?? '-' }}).</p>
                <textarea name="akar_masalah" class="form-control" rows="5" required placeholder="Contoh: Kondisi alat belum dikalibrasi ulang, suhu ruangan tidak stabil... ">{{ $parameter->akar_masalah }}</textarea>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3 border-start border-4 border-primary">
            <div class="card-header text-white" style="background-color: #1b3152;">
                <h5><i class="fas fa-tools me-1"></i> Bagian 2: Tindakan Perbaikan</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2" style="font-size: 0.75rem;">Tindakan langsung yang diambil untuk mengoreksi ketidaksesuaian saat ini.</p>
                <textarea name="tindakan_perbaikan" class="form-control" rows="4" required placeholder="Contoh: Melakukan kalibrasi ulang pada timbangan, memanaskan oven lebih lama...">{{ $parameter->tindakan_perbaikan }}</textarea>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3 border-start border-4 border-success">
            <div class="card-header text-white" style="background-color: #1b3152;">
                <h5><i class="fas fa-shield-alt me-1"></i> Bagian 3: Tindakan Pencegahan</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2" style="font-size: 0.75rem;">Tindakan proaktif untuk mencegah masalah yang sama terulang di masa depan.</p>
                <textarea name="tindakan_pencegahan" class="form-control" rows="4" placeholder="Contoh: Membuat jadwal kalibrasi internal setiap 2 minggu...">{{ $parameter->tindakan_pencegahan }}</textarea>
            </div>
        </div>

        <div class="d-flex flex-column-reverse flex-md-row justify-content-md-between gap-2">
            <a href="{{ route('qc-uji-banding.show', $program->id) }}" class="btn btn-outline-secondary btn-sm px-3">Batal</a>
            <button type="submit" class="btn btn-corporate-blue btn-sm px-3 shadow-sm fw-semibold"><i class="fas fa-save me-1"></i> Simpan LKS</button>
        </div>
    </form>
</div>
@endsection