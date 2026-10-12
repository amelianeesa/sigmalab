@extends('layouts.app')
@section('title', 'Detail Data - QC Uji Banding')

@section('content')
<style>
    .dashboard-container {
        padding: 0 20px !important;
        margin-top: -8px !important;
    }

    .dashboard-container nav[aria-label="breadcrumb"],
    .dashboard-container > nav {
        margin: 0 !important;
        padding: 0 !important;
    }

    .dashboard-container .breadcrumb {
        margin: 0 0 6px 0 !important;
        padding: 0 !important;
        font-size: 0.75rem !important;
        line-height: 1.4;
        flex-wrap: wrap;
        align-items: center;
        background: transparent !important;
    }

    .dashboard-container .breadcrumb .breadcrumb-item,
    .dashboard-container .breadcrumb .breadcrumb-item a {
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        color: #0d6efd !important;
        text-decoration: none;
    }

    .dashboard-container .breadcrumb .breadcrumb-item a:hover {
        color: #0a58ca !important;
        text-decoration: underline;
    }

    .dashboard-container .breadcrumb .breadcrumb-item.active {
        color: #000000 !important;
        font-weight: 700 !important;
    }

    .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item {
        padding-left: 0.4rem;
    }

    .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        color: #6c757d !important;
        padding-right: 0.4rem;
        font-weight: 400;
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu {
        min-width: 190px;
        padding: 4px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item {
        color: #000000 !important;
        font-size: 0.78rem !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        background-color: transparent;
        padding: 7px 12px;
        border-radius: 5px;
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:hover,
    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:focus,
    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:active,
    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item.active {
        background-color: rgba(27, 49, 82, 0.15) !important;
        color: #000000 !important;
        text-decoration: none !important;
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item.active {
        font-weight: 700 !important;
    }

    .card-body {
        padding: 10px !important;
    }

    .card-header {
        padding: 10px 14px !important;
    }

    .card-header h5 {
        font-size: 0.9rem !important;
        font-weight: 700;
        margin: 0;
    }

    .table th, .table td {
        padding: 8px 10px !important;
        vertical-align: middle !important;
        font-size: 0.72rem !important;
    }

    .table thead th {
        font-size: 0.75rem !important;
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-color: #ffffff !important;
        text-align: center !important;
    }

    .table-bordered > :not(caption) > * > * {
        border-color: #dee2e6;
    }

    .btn-corporate-blue {
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
        color: #ffffff !important;
    }

    .btn-corporate-blue:hover,
    .btn-corporate-blue:focus,
    .btn-corporate-blue:active {
        background-color: #14253e !important;
        border-color: #14253e !important;
        color: #ffffff !important;
    }

    .btn-outline-corporate {
        color: #1b3152 !important;
        border-color: #1b3152 !important;
    }

    .btn-outline-corporate:hover,
    .btn-outline-corporate:focus {
        background-color: #1b3152 !important;
        color: #ffffff !important;
    }

    .qc-table {
        min-width: 560px;
    }

    .info-table td {
        font-size: 0.78rem !important;
        padding: 7px 8px !important;
        border: 0;
        border-bottom: 1px solid #eef0f3;
    }

    .info-table tr:last-child td {
        border-bottom: 0;
    }

    @media (max-width: 767.98px) {
        .dashboard-container {
            padding: 0 10px !important;
        }

        .dashboard-container .breadcrumb,
        .dashboard-container .breadcrumb .breadcrumb-item,
        .dashboard-container .breadcrumb .breadcrumb-item a {
            font-size: 0.72rem !important;
        }

        .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item {
            padding-left: 0.3rem;
        }

        .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            padding-right: 0.3rem;
        }

        .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item {
            font-size: 0.85rem !important;
            padding: 10px 14px;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            width: 100%;
        }
    }
</style>

<div class="container-fluid dashboard-container" style="font-size: 0.82rem;">
    <x-qc-breadcrumb active="Uji Banding">
        <li class="breadcrumb-item active">Detail</li>
    </x-qc-breadcrumb>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="fw-bold mb-0" style="font-size: 1.1rem;">
            <i class="fas fa-balance-scale me-2" style="color: #1b3152;"></i>Detail Uji Banding
        </h5>

        <div class="header-actions d-grid d-md-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('qc-uji-banding.edit', $program->id) }}" class="btn btn-outline-corporate btn-sm py-1.5 px-3 shadow-sm fw-semibold" style="font-size: 0.8rem;">
                <i class="fas fa-edit me-1"></i> Edit Data
            </a>
            <button type="button" class="btn btn-outline-corporate btn-sm py-1.5 px-3 shadow-sm fw-semibold" style="font-size: 0.8rem;" data-bs-toggle="modal" data-bs-target="#modalPilihEvaluasi">
                <i class="fas fa-chart-bar me-1"></i> Input Hasil Evaluasi Vendor
            </button>
            <a href="{{ route('qc-uji-banding.ringkasan', $program->id) }}" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold" style="font-size: 0.8rem;">
                <i class="fas fa-table me-1"></i> Ringkasan Unjuk Kerja
            </a>
            <a href="{{ route('qc-uji-banding.printPdf', $program->id) }}" class="btn btn-danger btn-sm py-1.5 px-3 shadow-sm fw-semibold" style="font-size: 0.8rem;" target="_blank">
                <i class="fas fa-file-pdf me-1"></i> Cetak Laporan (PDF)
            </a>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger shadow-sm border-0 py-2" style="font-size: 0.8rem;">
        <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
    </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header text-white" style="background-color: #1b3152;">
                    <h5><i class="fas fa-info-circle me-1"></i> Informasi Program</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0 info-table">
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
                            <td class="fw-bold"><span class="badge bg-secondary" style="font-size: 0.7rem;">{{ $program->kode_sampel }}</span></td>
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
                        <table class="table table-bordered table-striped align-middle mb-0 text-center qc-table" style="font-size: 0.78rem;">
                            <thead class="align-middle">
                                <tr>
                                    <th class="text-start">Parameter</th>
                                    <th>Nilai Lab</th>
                                    <th>Z-Score Vendor</th>
                                    <th style="width: 130px;">Status</th>
                                    <th style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($program->parameters as $param)
                                <tr>
                                    <td class="text-start fw-bold">{{ $param->parameterUji->nama_parameter }}</td>
                                    <td class="fw-bold" style="color: #1b3152;">{{ $param->nilai_akhir }}</td>
                                    <td>{{ $param->z_score ?? '-' }}</td>
                                    <td>
                                        @if($param->status_evaluasi === 'menunggu')
                                            <span class="badge bg-warning text-dark" style="font-size: 0.7rem;"><i class="fas fa-clock"></i> Menunggu Vendor</span>
                                        @elseif($param->status_evaluasi === 'inlier')
                                            <span class="badge bg-success" style="font-size: 0.7rem;"><i class="fas fa-check-circle"></i> Inlier</span>
                                        @elseif($param->status_evaluasi === 'warning')
                                            <span class="badge bg-info" style="font-size: 0.7rem;"><i class="fas fa-exclamation-circle"></i> Warning</span>
                                        @elseif($param->status_evaluasi === 'outlier')
                                            <span class="badge bg-danger" style="font-size: 0.7rem;"><i class="fas fa-times-circle"></i> Outlier</span>
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
                                                <a href="{{ route('qc-uji-banding.investigasi', [$program->id, $param->id]) }}" class="btn btn-danger btn-sm py-1 px-2 shadow-sm" style="font-size: 0.75rem;" title="Isi Lembar Ketidaksesuaian (LKS)">
                                                    <i class="fas fa-file-alt"></i> Isi LKS
                                                </a>
                                            @elseif($param->status_investigasi === 'selesai_investigasi')
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('qc-uji-banding.cetak.pdf', [$program->id, $param->id]) }}" class="btn btn-outline-danger" style="font-size: 0.75rem;"><i class="fas fa-file-pdf"></i> PDF</a>
                                                    <a href="{{ route('qc-uji-banding.cetak.word', [$program->id, $param->id]) }}" class="btn btn-outline-corporate" style="font-size: 0.75rem;"><i class="fas fa-file-word"></i> Word</a>
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

<div class="modal fade" id="modalPilihEvaluasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background-color: #1b3152;">
                <h5 class="modal-title" style="font-size: 1rem;"><i class="fas fa-list-check me-2"></i>Pilih Parameter Uji</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('qc-uji-banding.evaluasi.form', $program->id) }}" method="GET">
                <div class="modal-body">
                    <p class="text-muted mb-3" style="font-size: 0.78rem;">Centang parameter apa saja yang diberikan hasil (Alg Mean & SDPA) oleh penyelenggara:</p>
                    <div class="row g-2">
                        @foreach($program->parameters as $param)
                        <div class="col-12 col-sm-6">
                            <label class="d-flex gap-2 align-items-center border rounded p-2 h-100" style="cursor: pointer;">
                                <input class="form-check-input flex-shrink-0 mt-0" type="checkbox" name="p[]" value="{{ $param->id }}" checked>
                                <strong class="text-dark" style="font-size: 0.8rem;">{{ $param->parameterUji->nama_parameter }}</strong>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-corporate-blue btn-sm shadow-sm">Lanjutkan Pengisian <i class="fas fa-arrow-right ms-1"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection