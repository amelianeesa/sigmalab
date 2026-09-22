@extends('layouts.app')

@section('content')

<style>
    .container-fluid {
        padding-top: 2px !important;
        max-width: 1250px;
    }
    .card-header-custom {
        background-color: #1b3152 !important;
        color: white !important;
        font-weight: bold;
        font-size: 0.9rem !important;
        padding: 0.5rem 0.75rem !important;
    }
    .table-header-custom, 
    .table-header-custom th, 
    .table-header-custom tr {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-color: #2c4975 !important;
        font-size: 0.7rem !important;
        vertical-align: middle !important;
        text-align: center !important;
    }
    .table-responsive-custom {
        font-size: 0.72rem !important;
    }
    .table th, .table td {
        padding: 0.25rem 0.35rem !important;
        font-size: 0.72rem !important;
        vertical-align: middle !important;
    }
    .form-control-sm, .form-select-sm {
        font-size: 0.72rem !important;
        padding: 0.15rem 0.3rem !important;
        height: auto !important;
    }
    .card-shadow-custom {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .badge-custom-size {
        font-size: 0.6rem !important;
        padding: 0.2rem 0.4rem !important;
    }
    .btn-custom-outline {
        color: #1b3152;
        border-color: #1b3152;
        background-color: transparent;
        transition: all 0.2s ease-in-out;
    }
    .btn-custom-outline:hover {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-color: #1b3152 !important;
    }
</style>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
        <h4 class="fw-bold text-dark mb-0" style="font-size: 1rem;"><i class="fas fa-history me-1"></i> Audit Trail</h4>
    </div>
    
    <div class="card shadow-sm border-0 mb-3 card-shadow-custom">
        <div class="card-body p-2.5">
            <form action="{{ route('audit-log.index') }}" method="GET" class="row g-2 align-items-center" id="filter-form">
                <div class="col-md-4">
                    <select name="event" class="form-select form-select-sm">
                        <option value="">-- Semua Event --</option>
                        <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created (Baru)</option>
                        <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated (Ubah)</option>
                        <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted (Hapus)</option>
                    </select>
                </div>
                <div class="col-md-7">
                    <input type="text" name="subject_type" class="form-control form-control-sm" placeholder="Ketik nama entitas (Contoh: Barang, Kegiatan)..." value="{{ request('subject_type') }}" autocomplete="off">
                </div>
                <div class="col-md-1">
                    <a href="{{ route('audit-log.index') }}" class="btn btn-sm btn-outline-secondary w-100 py-1" title="Reset"><i class="fas fa-sync-alt"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 card-shadow-custom">
        <div class="card-body p-2.5">
            <div class="table-responsive table-responsive-custom">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-header-custom">
                        <tr>
                            <th style="width: 15%;">Waktu Kejadian</th>
                            <th style="width: 15%;">Aktor (User)</th>
                            <th style="width: 10%;">Event</th>
                            <th style="width: 30%;">Deskripsi (Keterangan)</th>
                            <th style="width: 15%;">Model Terkait</th>
                            <th style="width: 15%;" class="text-center">Perubahan Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}
                                    <br><small class="text-muted" style="font-size: 0.6rem;">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $log->causer ? ($log->causer->personil->nama_personil ?? $log->causer->username) : 'Sistem (Otomatis)' }}</span>
                                    @if($log->causer && $log->causer->role)
                                        <br><small class="text-muted" style="font-size: 0.6rem;">{{ $log->causer->role->nama_role }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($log->event === 'created')
                                        <span class="badge bg-success badge-custom-size">Created</span>
                                    @elseif($log->event === 'updated')
                                        <span class="badge bg-warning text-dark badge-custom-size">Updated</span>
                                    @elseif($log->event === 'deleted')
                                        <span class="badge bg-danger badge-custom-size">Deleted</span>
                                    @else
                                        <span class="badge bg-secondary badge-custom-size">{{ ucfirst($log->event) }}</span>
                                    @endif
                                </td>
                                <td class="text-start">{{ $log->description }}</td>
                                <td class="text-center">
                                    @php
                                        $modelPath = explode('\\', $log->subject_type);
                                        $modelName = end($modelPath);
                                    @endphp
                                    <span class="badge bg-light text-dark border badge-custom-size">{{ $modelName ?: '-' }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('audit-log.show', $log->id) }}" class="btn btn-sm py-0 px-1 btn-custom-outline" style="font-size: 0.68rem;">
                                        <i class="fas fa-search me-1"></i> Lihat Data
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Belum ada rekam jejak yang tercatat</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-end" style="font-size: 0.72rem;">
                {{ $logs->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('filter-form');
        const selectEvent = form.querySelector('select[name="event"]');
        const inputSubject = form.querySelector('input[name="subject_type"]');
        
        selectEvent.addEventListener('change', function() {
            form.submit();
        });

        let timeout = null;
        inputSubject.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                form.submit();
            }, 500);
        });
    });
</script>
@endpush