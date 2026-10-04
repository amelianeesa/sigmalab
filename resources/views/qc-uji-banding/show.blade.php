@extends('layouts.app')
@section('title', 'Detail Data - QC Uji Banding')

@section('content')
@include('qc-uji-banding.partials._style')

<div class="container-fluid qc-page pb-4">
    <x-qc-breadcrumb active="Uji Banding">
        <li class="breadcrumb-item active">Detail</li>
    </x-qc-breadcrumb>

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-3 gap-2">
        <h5 class="qc-title"><i class="fas fa-balance-scale text-primary me-1"></i> Detail Uji Banding</h5>

        <div class="d-flex flex-column flex-md-row flex-wrap gap-2 qc-stack-sm">
            <a href="{{ route('qc-uji-banding.edit', $program->id) }}" class="btn btn-outline-secondary btn-sm py-1 px-3">
                <i class="fas fa-edit me-1"></i> Edit Data
            </a>
            <a href="{{ route('qc-uji-banding.evaluasi.form', $program->id) }}" class="btn btn-outline-primary btn-sm py-1 px-3">
                <i class="fas fa-chart-bar me-1"></i> Input Hasil Evaluasi Vendor
            </a>
            <a href="{{ route('qc-uji-banding.ringkasan', $program->id) }}" class="btn btn-outline-success btn-sm py-1 px-3">
                <i class="fas fa-table me-1"></i> Ringkasan Unjuk Kerja
            </a>
            <a href="{{ route('qc-uji-banding.printPdf', $program->id) }}" class="btn btn-danger btn-sm py-1 px-3" target="_blank">
                <i class="fas fa-file-pdf me-1"></i> Cetak Laporan (PDF)
            </a>
        </div>
    </div>


    <div class="row g-3">
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header text-white" style="background-color: #1b3152;">
                    <h5><i class="fas fa-info-circle me-1"></i> Informasi Program</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
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

        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header text-white" style="background-color: #1b3152;">
                    <h5><i class="fas fa-list-check me-1"></i> Parameter Uji & Evaluasi Vendor</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0 text-center">
                            <thead>
                                <tr>
                                    <th class="text-start">Parameter</th>
                                    <th>Nilai Lab</th>
                                    <th>Z-Score Vendor</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($program->parameters as $param)
                                <tr>
                                    <td class="text-start fw-bold">{{ $param->parameterUji->nama_parameter }}</td>
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
                                                <div class="text-danger mt-1" style="font-size: 0.68rem;"><i class="fas fa-exclamation-triangle"></i> Butuh LKS</div>
                                            @elseif($param->status_investigasi === 'selesai_investigasi')
                                                <div class="text-success mt-1" style="font-size: 0.68rem;"><i class="fas fa-check-double"></i> LKS Selesai</div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        @if($param->status_evaluasi === 'outlier')
                                            @if($param->status_investigasi === 'menunggu_investigasi')
                                                <a href="{{ route('qc-uji-banding.investigasi', [$program->id, $param->id]) }}" class="btn btn-sm btn-danger py-1 px-2" title="Isi Lembar Ketidaksesuaian (LKS)"><i class="fas fa-file-alt"></i> Isi LKS</a>
                                            @elseif($param->status_investigasi === 'selesai_investigasi')
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('qc-uji-banding.cetak.pdf', [$program->id, $param->id]) }}" class="btn btn-outline-danger"><i class="fas fa-file-pdf"></i> PDF</a>
                                                    <a href="{{ route('qc-uji-banding.cetak.word', [$program->id, $param->id]) }}" class="btn btn-outline-primary"><i class="fas fa-file-word"></i> Word</a>
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
</div>
@endsection