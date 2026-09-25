@extends('layouts.app')

@section('content')
<style>
    .dashboard-container {
        padding: 2px 20px !important;
    }
    .table th, .table td {
        padding: 6px 10px !important;
        vertical-align: middle !important;
        font-size: 0.76rem !important;
    }
    .table thead th {
        font-size: 0.78rem !important;
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-color: #dee2e6 !important;
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
    .btn-outline-primary-birdong {
        color: #1b3152 !important;
        background-color: transparent !important;
        border-color: #1b3152 !important;
    }
    .btn-outline-primary-birdong:hover,
    .btn-outline-primary-birdong:focus,
    .btn-outline-primary-birdong:active {
        color: #ffffff !important;
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
    }

    .table-responsive {
        overflow-x: auto;
    }
    
    .sticky-no {
        position: sticky !important;
        left: 0 !important;
        width: 50px !important;
        min-width: 50px !important;
        z-index: 3;
        background-color: #ffffff !important;
    }
    
    .sticky-alat {
        position: sticky !important;
        left: 50px !important; 
        width: 170px !important;
        min-width: 170px !important;
        z-index: 3;
        background-color: #ffffff !important;
    }

    thead th.sticky-no, thead th.sticky-alat {
        background-color: #1b3152 !important;
        z-index: 4;
    }

    .sticky-alat::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 4px;
        box-shadow: inset -3px 0 3px -2px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="container-fluid dashboard-container">
    @php
        $allowedRoles = ['Koordinator Laboratorium', 'Analis Lab', 'Admin Aplikasi'];
        $userRoleName = Auth::user()->role->nama_role ?? '';
        $canInputEvaluasi = in_array($userRoleName, $allowedRoles);
    @endphp

    <div class="mb-2">
        <h4 class="fw-bold mb-0">Evaluasi Kalibrasi</h4>
    </div>

    <div class="d-flex justify-content-end mb-2">
        @if($canInputEvaluasi)
            <a href="{{ route('evaluasi-kalibrasi.create') }}" class="btn btn-corporate-blue btn-sm py-1 px-2.5 shadow-sm fw-semibold" style="font-size: 0.73rem;">
                <i class="fas fa-plus me-1"></i> Input Evaluasi Baru
            </a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center bg-white shadow-sm rounded-3">
            <thead class="align-middle">
                <tr>
                    <th class="sticky-no" style="width: 50px;">No</th>
                    <th class="sticky-alat text-start" style="width: 170px; min-width: 170px;">Alat</th>
                    <th style="width: 90px;">Tanggal</th>
                    <th style="width: 200px;">Keputusan</th>
                    <th style="width: 150px;">Dievaluasi Oleh</th>
                    <th style="width: 80px;">Laporan</th>
                    <th style="width: 70px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluasi as $index => $item)
                <tr>
                    <td class="sticky-no fw-semibold bg-white">
                        {{ $evaluasi->firstItem() + $index }}
                    </td>

                    <td class="sticky-alat text-start fw-bold bg-white text-truncate" style="max-width: 170px;">
                        <a href="{{ route('evaluasi-kalibrasi.show', $item->evaluasi_id) }}" class="text-decoration-none text-primary">
                            {{ $item->alat->nama_alat ?? '-' }}
                        </a> 
                        <code class="text-dark fw-normal d-block" style="font-size: 0.68rem;">({{ $item->alat->kode_alat ?? '-' }})</code>
                    </td>

                    <td>{{ \Carbon\Carbon::parse($item->tanggal_evaluasi)->format('d/m/Y') }}</td>

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
                        <span class="badge bg-{{ $badge }}" style="font-size: 0.65rem; padding: 0.3rem 0.5rem;">{{ strtoupper($item->keputusan) }}</span>
                    </td>

                    <td>{{ $item->evaluator->name ?? $item->evaluator->username ?? '-' }}</td>

                    <td>
                        @if($item->file_laporan)
                            <a href="{{ asset('storage/' . $item->file_laporan) }}" target="_blank" class="btn btn-outline-primary-birdong btn-sm py-0.5 px-1.5" style="font-size: 0.68rem;" title="Lihat Laporan">
                                <i class="fas fa-file-alt"></i> Lihat
                            </a>
                        @else
                            -
                        @endif
                    </td>

                    <td class="text-nowrap">
                        <a href="{{ route('evaluasi-kalibrasi.show', $item->evaluasi_id) }}" class="btn btn-corporate-blue btn-sm py-1 px-2 shadow-sm fw-semibold" style="font-size: 0.7rem;" title="Detail">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-3">Belum ada data evaluasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-2">
        {{ $evaluasi->links() }}
    </div>
</div>
@endsection