@extends('layouts.app')
@section('title', 'Detail Data - QC Uji Banding')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="Uji Banding">
        <li class="breadcrumb-item active">Detail</li>
    </x-qc-breadcrumb>

    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-lg-4">
            <h2 class="fw-bold text-dark mb-0">
                <i class="fas fa-balance-scale text-primary me-2"></i>Detail Uji Banding
            </h2>
        </div>
        <div class="col-12 col-lg-8">
            <div class="d-flex flex-column flex-md-row flex-wrap justify-content-lg-end gap-2">
                <a href="{{ route('qc-uji-banding.edit', $program->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-edit"></i> Edit Data
                </a>
                <a href="{{ route('qc-uji-banding.evaluasi.form', $program->id) }}" class="btn btn-outline-primary">
                    <i class="fas fa-chart-bar"></i> Input Hasil Evaluasi Vendor
                </a>
                <a href="{{ route('qc-uji-banding.ringkasan', $program->id) }}" class="btn btn-outline-success">
                    <i class="fas fa-table"></i> Ringkasan Unjuk Kerja
                </a>
                <a href="{{ route('qc-uji-banding.printPdf', $program->id) }}" class="btn btn-danger" target="_blank">
                    <i class="fas fa-file-pdf"></i> Cetak Laporan (PDF)
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success shadow-sm border-0">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger shadow-sm border-0">
        <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
    </div>
    @endif

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header text-white" style="background-color: #1b3152;">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Program</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted w-50">Nama Program</td>
                            <td class="fw-bold">{{ $program->nama_program }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Penyelenggara</td>
                            <td class="fw-bold">{{ $program->penyelenggara }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kode Sampel</td>
                            <td class="fw-bold"><span class="badge bg-secondary">{{ $program->kode_sampel }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal Terima</td>
                            <td class="fw-bold">{{ $program->tanggal_terima->format('d M Y') }}</td>
                        </tr>

                        @if($program->keterangan)
                        <tr>
                            <td class="text-muted">Keterangan</td>
                            <td class="fw-bold">{{ $program->keterangan }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 h-100">
                    <div class="card-header text-white" style="background-color: #1b3152;">
                    <h5 class="mb-0"><i class="fas fa-list-check me-2"></i>Parameter Uji & Evaluasi Vendor</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Parameter</th>
                                    <th>Nilai Lab</th>
                                    <th>Z-Score Vendor</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($program->parameters as $param)
                                <tr>
                                    <td class="ps-3 fw-bold">{{ $param->parameterUji->nama_parameter }}</td>
                                    <td class="text-primary fw-bold">{{ $param->nilai_akhir }}</td>
                                    <td>{{ $param->z_score ?? '-' }}</td>
                                    <td>
                                        @if($param->status_evaluasi === 'menunggu')
                                            <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Menunggu Vendor</span>
                                        @elseif($param->status_evaluasi === 'inlier')
                                            <span class="badge bg-success"><i class="fas fa-check-circle"></i> Inlier</span>
                                        @elseif($param->status_evaluasi === 'warning')
                                            <span class="badge bg-info"><i class="fas fa-exclamation-circle"></i> Warning</span>
                                        @elseif($param->status_evaluasi === 'outlier')
                                            <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Outlier</span>
                                            @if($param->status_investigasi === 'menunggu_investigasi')
                                                <div class="small text-danger mt-1"><i class="fas fa-exclamation-triangle"></i> Butuh LKS</div>
                                            @elseif($param->status_investigasi === 'selesai_investigasi')
                                                <div class="small text-success mt-1"><i class="fas fa-check-double"></i> LKS Selesai</div>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        
                                        @if($param->status_evaluasi === 'outlier')
                                            @if($param->status_investigasi === 'menunggu_investigasi')
                                                <a href="{{ route('qc-uji-banding.investigasi', [$program->id, $param->id]) }}" class="btn btn-sm btn-danger" title="Isi Lembar Ketidaksesuaian (LKS)"><i class="fas fa-file-alt"></i> Isi LKS</a>
                                            @elseif($param->status_investigasi === 'selesai_investigasi')
                                                <div class="btn-group">
                                                    <a href="{{ route('qc-uji-banding.cetak.pdf', [$program->id, $param->id]) }}" class="btn btn-sm btn-outline-danger"><i class="fas fa-file-pdf"></i> PDF</a>
                                                    <a href="{{ route('qc-uji-banding.cetak.word', [$program->id, $param->id]) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-file-word"></i> Word</a>
                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection

