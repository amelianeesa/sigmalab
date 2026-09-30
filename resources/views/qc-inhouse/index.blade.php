@extends('layouts.app')
@section('title', 'Daftar - QC In-House')

@section('content')
<style>
    .btn-corporate-blue {
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
        color: #ffffff !important;
    }
    .btn-corporate-blue:hover, 
    .btn-corporate-blue:focus {
        background-color: #14253e !important;
        border-color: #14253e !important;
        color: #ffffff !important;
    }
    
    .table-corporate thead th {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-bottom: 2px solid #14253e !important;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding-top: 15px;
        padding-bottom: 15px;
    }

    .btn-outline-corporate {
        color: #1b3152 !important;
        border-color: #1b3152 !important;
    }
    .btn-outline-corporate:hover {
        background-color: #1b3152 !important;
        color: #ffffff !important;
    }
</style>

<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="In-House" />

    <div class="row align-items-center mt-3 mb-4 g-3">
        <div class="col-12 col-md-6">
            <h2 class="fw-bold text-dark mb-0">
                <i class="fas fa-flask me-2" style="color: #1b3152;"></i>QC In-House
            </h2>
        </div>
        <div class="col-12 col-md-6">
            <div class="d-grid d-md-flex gap-2 justify-content-md-end">
                <a href="{{ route('qc-inhouse.create') }}" class="btn btn-outline-corporate shadow-sm rounded-pill px-4">
                    <i class="fas fa-plus me-1"></i> Buat Sampel Baru
                </a>
                <a href="{{ route('qc-harian.create') }}" class="btn btn-corporate-blue shadow-sm rounded-pill px-4">
                    <i class="fas fa-play me-1"></i> Mulai Pengujian Harian QC
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4 bg-light">
        <div class="card-body">
            <form action="{{ route('qc-inhouse.index') }}" method="GET" id="filterForm">
                <div class="row g-2 align-items-center">
                    
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="search" id="search" class="form-control border-start-0" placeholder="Cari kode atau nama sampel..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select name="jenis_batubara" id="jenis_batubara" class="form-select">
                            <option value="">-- Semua Jenis Batubara --</option>
                            <option value="sub_bituminous" {{ request('jenis_batubara') == 'sub_bituminous' ? 'selected' : '' }}>Sub Bituminous</option>
                            <option value="bituminous" {{ request('jenis_batubara') == 'bituminous' ? 'selected' : '' }}>Bituminous</option>
                            <option value="anthracite" {{ request('jenis_batubara') == 'anthracite' ? 'selected' : '' }}>Anthracite</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white">Mulai</span>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                            <span class="input-group-text bg-white">s/d</span>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                    </div>

                    <div class="col-md-1 text-end">
                        <a href="{{ route('qc-inhouse.index') }}" class="btn btn-outline-danger w-100" title="Reset Filter"><i class="fas fa-sync-alt"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-corporate">
                    <thead>
                        <tr>
                            <th class="ps-4">Kode Batch</th>
                            <th>Nama Sampel</th>
                            <th>Jenis / Metode</th>
                            <th>Jml Parameter</th>
                            <th>Pembuat</th>
                            <th>Tanggal Buat</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sampels as $s)
                            <tr>
                                <td class="ps-4 fw-bold">{{ $s->kode_batch ?? '-' }}</td>
                                <td>{{ $s->nama_sampel }}</td>
                                <td>
                                    <span class="d-block text-uppercase small fw-bold">{{ str_replace('_', ' ', $s->jenis_batubara) }}</span>
                                    <span class="d-block text-muted small">{{ strtoupper($s->metode_acuan) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark rounded-pill px-3">{{ $s->parameters->count() }} Parameter</span>
                                </td>
                                <td>{{ $s->pembuat->username ?? '-' }}</td>
                                <td>{{ $s->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $s->status_color }} py-1 px-2">{{ $s->status_label }}</span>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('qc-inhouse.show', $s->sampel_inhouse_id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">Belum ada data sampel In-House.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($sampels->hasPages())
            <div class="card-footer bg-white pt-3 pb-2 d-flex justify-content-center">
                {{ $sampels->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filterForm');
        let timeout = null;

        document.getElementById('search').addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => form.submit(), 500);
        });

        document.getElementById('jenis_batubara').addEventListener('change', function() {
            form.submit();
        });

        document.getElementById('start_date').addEventListener('change', checkDates);
        document.getElementById('end_date').addEventListener('change', checkDates);

        function checkDates() {
            const start = document.getElementById('start_date').value;
            const end = document.getElementById('end_date').value;
            if (start && end) {
                form.submit();
            }
        }
    });
</script>
@endsection