@extends('layouts.app')

@section('content')
<style>
    .dashboard-container {
        padding: 2px 20px !important;
    }
    .table th, .table td {
        padding: 5px 8px !important;
        vertical-align: middle !important;
        font-size: 0.72rem !important;
    }
    .table thead th {
        font-size: 0.72rem !important;
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-color: #ffffff !important;
    }
    .table-bordered > :not(caption) > * > * {
        border-color: #dee2e6;
    }
    .table thead.table-dark th, 
    .table thead th {
        border-color: #ffffff !important;
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
</style>

<div class="container-fluid dashboard-container">
    <div class="mb-2">
        <h4 class="fw-bold mb-0"> Evaluasi Kalibrasi</h4></div>

    <div class="d-flex justify-content-end mb-2">
        <a href="{{ route('evaluasi-kalibrasi.create') }}" class="btn btn-corporate-blue btn-sm py-1 shadow-sm" style="font-size: 0.75rem;">
            <i class="fas fa-plus me-1"></i> Input Evaluasi Baru
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center bg-white shadow-sm">
            <thead class="align-middle">
                <tr>
                    <th style="width: 90px;">Tanggal</th>
                    <th class="text-start">Alat</th>
                    <th style="width: 220px;">Keputusan</th>
                    <th style="width: 140px;">Dievaluasi Oleh</th>
                    <th style="width: 80px;">Laporan</th>
                    <th style="width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluasi as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_evaluasi)->format('d/m/Y') }}</td>
                    <td class="text-start fw-bold">
                        <a href="{{ route('evaluasi-kalibrasi.show', $item->evaluasi_id) }}" class="text-decoration-none text-primary">
                            {{ $item->alat->nama_alat ?? '-' }}
                        </a> 
                        <code class="text-dark" style="font-size: 0.65rem;">({{ $item->alat->kode_alat ?? '-' }})</code>
                    </td>
                    <td>
                        @php
                            $keputusanLower = strtolower($item->keputusan);
                            $badge = 'secondary'; 
                            
                            if (str_contains($keputusanLower, 'layak') && !str_contains($keputusanLower, 'tidak')) {
                                $badge = 'success'; 
                            } elseif (str_contains($keputusanLower, 'tidak')) {
                                $badge = 'danger';  
                            } elseif ($keputusanLower == 'ya') {
                                $badge = 'info';    
                            }
                        @endphp
                        <span class="badge bg-{{ $badge }}" style="font-size: 0.6rem;">{{ strtoupper($item->keputusan) }}</span>
                    </td>
                    <td>{{ $item->evaluator->name ?? $item->evaluator->username ?? '-' }}</td>
                    <td>
                        @if($item->file_laporan)
                            <a href="{{ asset('storage/' . $item->file_laporan) }}" target="_blank" class="btn btn-outline-primary btn-sm py-0 px-1" style="font-size: 0.60rem;" title="Lihat Laporan">
                                <i class="fas fa-file-alt"></i> Lihat
                            </a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('evaluasi-kalibrasi.show', $item->evaluasi_id) }}" class="btn btn-corporate-blue btn-sm py-0 px-1 shadow-sm" style="font-size: 0.60rem;" title="Detail">
                            <i class="fas fa-eye me-0.5"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-3">Belum ada data evaluasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-2">
        {{ $evaluasi->links() }}
    </div>
</div>
@endsection