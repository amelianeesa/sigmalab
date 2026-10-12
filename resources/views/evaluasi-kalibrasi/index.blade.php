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

    .filter-bar .form-control,
    .filter-bar .form-label {
        font-size: 0.76rem;
    }
    .filter-bar .form-label {
        margin-bottom: 2px;
        font-weight: 600;
        color: #1b3152;
    }
    .filter-bar .form-control:focus {
        border-color: #1b3152;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
    }

    .table-responsive {
        overflow-x: auto;
    }

    .sticky-alat {
        position: sticky !important;
        left: 0 !important;
        width: 130px !important;
        min-width: 130px !important;
        z-index: 3;
        background-color: #ffffff !important;
    }

    thead th.sticky-alat {
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
        $adaFilter = request()->filled('q') || request()->filled('tanggal');
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

    <form method="GET" action="{{ route('evaluasi-kalibrasi.index') }}" class="filter-bar card border-0 shadow-sm p-2 mb-2">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-6">
                <label for="q" class="form-label">Cari Alat</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" id="q" name="q" value="{{ request('q') }}" class="form-control" placeholder="Nama atau kode alat...">
                </div>
            </div>

            <div class="col-12 col-md-3">
                <label for="tanggal" class="form-label">Tanggal Evaluasi</label>
                <input type="date" id="tanggal" name="tanggal" value="{{ request('tanggal') }}" class="form-control form-control-sm">
            </div>

            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-corporate-blue btn-sm flex-fill fw-semibold" style="font-size: 0.73rem;">
                    <i class="fas fa-filter me-1"></i> Terapkan
                </button>
                @if($adaFilter)
                    <a href="{{ route('evaluasi-kalibrasi.index') }}" class="btn btn-outline-secondary btn-sm flex-fill fw-semibold" style="font-size: 0.73rem;">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center bg-white shadow-sm rounded-3">
            <thead class="align-middle">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th class="sticky-alat text-start" style="width: 130px; min-width: 130px;">Alat</th>
                    <th style="width: 90px;">Tanggal</th>
                    <th style="width: 110px;">Keputusan</th>
                    <th style="width: 150px;">Dievaluasi Oleh</th>
                    <th style="width: 80px;">Laporan</th>
                    <th style="width: 70px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluasi as $index => $item)
                <tr>
                    <td class="fw-semibold">
                        {{ $evaluasi->firstItem() + $index }}
                    </td>

                    <td class="sticky-alat text-start fw-bold bg-white text-truncate" style="max-width: 130px;">
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

                            if (str_contains($keputusanLower, 'tidak')) {
                                $badge = 'danger';
                            } elseif (str_contains($keputusanLower, 'faktor') || str_contains($keputusanLower, 'koreksi') || str_contains($keputusanLower, 'penambahan')) {
                                $badge = 'warning text-dark';
                            } elseif (str_contains($keputusanLower, 'layak')) {
                                $badge = 'success';
                            }
                        @endphp
                        <span class="badge bg-{{ $badge }}" style="font-size: 0.55rem; padding: 0.3em 0.4em; white-space: normal; display: inline-block; max-width: 100px; word-break: break-word; line-height: 1.2;">
                            {{ strtoupper(str_replace('_', ' ', $item->keputusan)) }}
                        </span>
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
                <tr>
                    <td colspan="7" class="text-center text-muted py-3">
                        {{ $adaFilter ? 'Data evaluasi tidak ditemukan untuk filter yang dipilih.' : 'Belum ada data evaluasi.' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-2">
        {{ $evaluasi->links() }}
    </div>
</div>
@endsection