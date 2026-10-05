@extends('layouts.app')
@section('title', 'Detail Data - Parameter Uji')

@section('content')
<style>
    .pu-container { padding-top: 2px; }
    .pu-breadcrumb { font-size: 0.72rem; }
    .pu-title { font-size: 1.1rem; }

    .card-header-custom {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.85rem !important;
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

    /* Tombol seragam */
    .pu-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 0.75rem !important;
        font-weight: 600;
        line-height: 1.2;
        padding: 0.3rem 0.75rem !important;
        border-radius: 6px;
        white-space: nowrap;
    }
    .pu-btn i { font-size: 0.72rem; }

    .pu-info-table td { font-size: 0.8rem !important; vertical-align: top; }
    .pu-info-table code { font-size: 0.75rem; word-break: break-word; }

    .pu-table-header th {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        font-size: 0.72rem !important;
    }
    .pu-table td, .pu-table th {
        font-size: 0.78rem !important;
        vertical-align: middle !important;
    }

    .scroll-hint-pu {
        display: none;
        font-size: 0.68rem;
        color: #6c757d;
        margin-bottom: 0.4rem;
    }

    @media (max-width: 768px) {
        .pu-table td, .pu-table th { font-size: 0.68rem !important; }
        .scroll-hint-pu { display: block; }
    }
    @media (max-width: 575.98px) {
        .pu-card-header-actions { flex-direction: column; align-items: stretch !important; gap: 0.5rem; }
        .pu-card-header-actions .pu-actions { width: 100%; }
        .pu-card-header-actions .pu-actions .pu-btn { flex: 1; }
        .pu-info-table td:first-child { width: 120px !important; }
    }
</style>

<div class="container-fluid px-4 pu-container">
    <ol class="breadcrumb mb-1 mt-1 pu-breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
        <li class="breadcrumb-item"><a href="{{ route('parameter-uji.index') }}" class="text-decoration-none">Parameter Uji</a></li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>
    <h4 class="fw-bold text-dark mb-3 pu-title">Dashboard Parameter: {{ $parameterUji->nama_parameter }}</h4>

    @include('parameter-uji.partials.tabs', [
        'selectedParameter' => $parameterUji,
        'jenisGrafik' => $tab
    ])

    @if($tab === 'in_house')
        {{-- INHOUSE MASTER DATA --}}
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-white py-2 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2 pu-card-header-actions">
                <h6 class="m-0 fw-bold text-primary" style="font-size: 0.9rem;">
                    <i class="fas fa-info-circle me-1"></i> Informasi Parameter Uji
                </h6>
                <div class="d-flex gap-2 pu-actions">
                    @can('update', $parameterUji)
                        <a href="{{ route('parameter-uji.edit', $parameterUji->parameter_uji_id) }}" class="btn btn-warning pu-btn shadow-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    @endcan
                    <a href="{{ route('parameter-uji.index') }}" class="btn btn-secondary pu-btn shadow-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm pu-info-table mb-0">
                                <tr>
                                    <td class="fw-bold" style="width: 150px;">Nama Parameter</td>
                                    <td style="width: 10px;">:</td>
                                    <td>{{ $parameterUji->nama_parameter }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Satuan</td>
                                    <td>:</td>
                                    <td>{{ $parameterUji->satuan }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nilai Acuan</td>
                                    <td>:</td>
                                    <td>{{ number_format($parameterUji->nilai_acuan, 4) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Batas Bawah</td>
                                    <td>:</td>
                                    <td>{{ number_format($parameterUji->batas_bawah, 4) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Batas Atas</td>
                                    <td>:</td>
                                    <td>{{ number_format($parameterUji->batas_atas, 4) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-borderless table-sm pu-info-table mb-0">
                                <tr>
                                    <td class="fw-bold" style="width: 150px;">Metode / Kriteria</td>
                                    <td style="width: 10px;">:</td>
                                    <td>{{ $parameterUji->metode_kriteria ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Rumus Kalkulasi</td>
                                    <td>:</td>
                                    <td>
                                        @if($parameterUji->rumus_kalkulasi)
                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                <code>{{ $parameterUji->rumus_kalkulasi }}</code>
                                                @if(is_array($parameterUji->langkah_kalkulasi) && count($parameterUji->langkah_kalkulasi) > 0)
                                                    <button type="button" class="btn btn-outline-info pu-btn" data-bs-toggle="modal" data-bs-target="#modalDetailRumus">
                                                        <i class="fas fa-list-ol"></i> Detail Rumus
                                                    </button>
                                                @endif
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status Aktif</td>
                                    <td>:</td>
                                    <td>
                                        @if($parameterUji->status_aktif)
                                            <span class="badge bg-success" style="font-size: 0.68rem;">Aktif</span>
                                        @else
                                            <span class="badge bg-danger" style="font-size: 0.68rem;">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Dibuat Pada</td>
                                    <td>:</td>
                                    <td>{{ $parameterUji->created_at ? $parameterUji->created_at->format('d M Y, H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Terakhir Diupdate</td>
                                    <td>:</td>
                                    <td>{{ $parameterUji->updated_at ? $parameterUji->updated_at->format('d M Y, H:i') : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Detail Langkah Kalkulasi (di luar tabel) --}}
        @if($parameterUji->rumus_kalkulasi && is_array($parameterUji->langkah_kalkulasi) && count($parameterUji->langkah_kalkulasi) > 0)
            <div class="modal fade" id="modalDetailRumus" tabindex="-1" aria-labelledby="modalDetailRumusLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header text-white py-2 px-3" style="background-color: #1b3152;">
                            <h5 class="modal-title" id="modalDetailRumusLabel" style="font-size: 0.9rem;">
                                <i class="fas fa-list-ol me-1"></i> Detail Langkah Kalkulasi Per Kolom
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm mb-0 pu-table">
                                    <thead class="pu-table-header">
                                        <tr>
                                            <th style="width: 50px;">No</th>
                                            <th>Nama Variabel Output</th>
                                            <th>Rumus Matematika</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($parameterUji->langkah_kalkulasi as $index => $langkah)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td><code>{{ $langkah['var'] ?? '-' }}</code></td>
                                                <td>{{ $langkah['rumus'] ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer py-2">
                            <button type="button" class="btn btn-secondary pu-btn" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    @elseif($tab === 'crm')
        {{-- CRM MASTER DATA --}}
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-white py-2 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2 pu-card-header-actions">
                <h6 class="m-0 fw-bold text-primary" style="font-size: 0.9rem;">
                    <i class="fas fa-certificate me-1"></i> Informasi True Value CRM (Sertifikat Pabrik)
                </h6>
                <div class="d-flex gap-2 pu-actions">
                    @can('update', $parameterUji)
                        <a href="{{ route('parameter-uji.edit', $parameterUji->parameter_uji_id) }}#crm" class="btn btn-warning pu-btn shadow-sm">
                            <i class="fas fa-edit"></i> Edit CRM
                        </a>
                    @endcan
                    <a href="{{ route('parameter-uji.index') }}" class="btn btn-secondary pu-btn shadow-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="scroll-hint-pu"><i class="fas fa-arrows-alt-h me-1"></i> Geser tabel ke samping untuk melihat kolom lainnya</div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0 text-center pu-table">
                        <thead class="pu-table-header">
                            <tr>
                                <th>No</th>
                                <th>Katalog CRM (Sertifikat)</th>
                                <th>No. Batch / Lot</th>
                                <th>Nilai True Value</th>
                                <th>Uncertainty (U)</th>
                                <th>Batas Bawah</th>
                                <th>Batas Atas</th>
                                <th>Status Aktif CRM</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $crmSerts = $parameterUji->sertifikatCrm()->with('katalog')->get(); @endphp
                            @forelse($crmSerts as $index => $sert)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $sert->katalog->nama_katalog ?? '-' }}</td>
                                    <td><span class="badge bg-secondary" style="font-size: 0.68rem;">{{ $sert->katalog->no_batch ?? '-' }}</span></td>
                                    <td class="text-primary fw-bold">{{ number_format($sert->cert_value, 4, ',', '.') }}</td>
                                    <td class="text-muted">±{{ number_format($sert->cert_u, 4, ',', '.') }}</td>
                                    <td class="text-danger">{{ number_format($sert->cert_value - $sert->cert_u, 4, ',', '.') }}</td>
                                    <td class="text-danger">{{ number_format($sert->cert_value + $sert->cert_u, 4, ',', '.') }}</td>
                                    <td>
                                        @if(optional($sert->katalog)->is_active)
                                            <span class="badge bg-success" style="font-size: 0.68rem;">Aktif</span>
                                        @else
                                            <span class="badge bg-danger" style="font-size: 0.68rem;">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-3 text-muted">Belum ada Sertifikat CRM yang terdaftar untuk parameter ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            @if(view()->exists($partialView))
                @include($partialView, [
                    'selectedParameter' => $parameterUji,
                    'chartData' => $chartData,
                    'hasilList' => $hasilList,
                    'stats' => $stats,
                    'jenisGrafik' => $jenisGrafik,
                ])
            @else
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-3 d-block"></i>
                    <p>View template untuk grafik parameter ini (<code>{{ $partialView }}</code>) belum tersedia.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection