@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4"><i class="fas fa-vial text-primary me-2"></i>Detail Parameter Uji</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('parameter-uji.index') }}">Parameter Uji</a></li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>

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
