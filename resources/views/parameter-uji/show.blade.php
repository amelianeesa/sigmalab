@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-1 mt-3">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
        <li class="breadcrumb-item"><a href="{{ route('parameter-uji.index') }}" class="text-decoration-none">Parameter Uji</a></li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>
    <h1 class="mb-4">Detail Parameter Uji</h1>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-info-circle me-2"></i>Informasi Parameter Uji</h6>
            <div>
                @can('update', $parameterUji)
                    <a href="{{ route('parameter-uji.edit', $parameterUji->parameter_uji_id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                @endcan
                <a href="{{ route('parameter-uji.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
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
                            <td class="fw-bold">Jenis Kontrol</td>
                            <td>:</td>
                            <td>
                                @if($parameterUji->jenis_kontrol === 'crm')
                                    <span class="badge bg-info"><i class="fas fa-certificate me-1"></i>CRM (Sertifikat Pabrik)</span>
                                @else
                                    <span class="badge bg-primary"><i class="fas fa-flask me-1"></i>In-House Control</span>
                                @endif
                            </td>
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
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
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
                                    <code>{{ $parameterUji->rumus_kalkulasi }}</code>
                                    @if(is_array($parameterUji->langkah_kalkulasi) && count($parameterUji->langkah_kalkulasi) > 0)
                                        <button type="button" class="btn btn-sm btn-outline-info ms-2" data-bs-toggle="modal" data-bs-target="#modalDetailRumus">
                                            <i class="fas fa-list-ol"></i> Detail Rumus
                                        </button>
                                        
                                        <!-- Modal Detail Langkah Kalkulasi -->
                                        <div class="modal fade" id="modalDetailRumus" tabindex="-1" aria-labelledby="modalDetailRumusLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info text-white">
                                                        <h5 class="modal-title" id="modalDetailRumusLabel"><i class="fas fa-list-ol me-2"></i> Detail Langkah Kalkulasi Per Kolom</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-sm">
                                                                <thead class="bg-light">
                                                                    <tr>
                                                                        <th>No</th>
                                                                        <th>Nama Variabel Output</th>
                                                                        <th>Rumus Matematika</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($parameterUji->langkah_kalkulasi as $index => $langkah)
                                                                    <tr>
                                                                        <td>{{ $index + 1 }}</td>
                                                                        <td><code>{{ $langkah['var'] ?? '-' }}</code></td>
                                                                        <td>{{ $langkah['rumus'] ?? '-' }}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
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
@endsection
