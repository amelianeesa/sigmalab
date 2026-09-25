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
        font-size: 0.85rem !important;
        padding: 0.4rem 0.75rem !important;
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

    @media (max-width: 576px) {
        .audit-detail-header {
            flex-direction: column;
            align-items: stretch !important;
            gap: 0.5rem;
        }
        .audit-detail-header a {
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 768px) {
        .table th, .table td {
            font-size: 0.65rem !important;
        }
    }
</style>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-2 mt-2 audit-detail-header">
        <h5 class="fw-bold text-dark mb-0" style="font-size: 1rem;"> Detail Rekam Jejak (Audit Log)</h5>
        <a href="{{ route('audit-log.index') }}" class="btn btn-secondary btn-sm py-1 px-2 shadow-sm" style="font-size: 0.72rem;">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @php
        $batchLogs = isset($batchLogs) ? $batchLogs : collect([$log]);
    @endphp

    <div class="row g-3">
        <!-- Informasi Meta -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 card-shadow-custom">
                <div class="card-header card-header-custom"> Informasi</div>
                <div class="card-body p-2.5">
                    <div class="table-responsive table-responsive-custom">
                        <table class="table table-bordered table-striped align-middle mb-0" style="font-size: 0.72rem;">
                            <tr>
                                <td class="fw-bold text-muted bg-light" style="width: 35%;">Aktor / User</td>
                                <td>
                                    <span class="fw-bold">{{ $log->causer ? ($log->causer->personil->nama_personil ?? $log->causer->username) : 'Sistem' }}</span>
                                    @if($log->causer && $log->causer->role)
                                        <br><span class="badge bg-secondary badge-custom-size mt-1">{{ $log->causer->role->nama_role }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted bg-light">Event</td>
                                <td>
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
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted bg-light">Deskripsi</td>
                                <td>{{ $log->description }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted bg-light">Model Entitas</td>
                                <td><code style="font-size: 0.65rem;">{{ $log->subject_type }}</code></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted bg-light">ID Entitas (PK)</td>
                                <td>{{ $log->subject_id }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted bg-light">Waktu Eksekusi</td>
                                <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-3">
            <div class="card shadow-sm border-0 card-shadow-custom">
                <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                    <span>Visualisasi Data Berubah</span>
                    <span class="badge bg-light text-dark badge-custom-size">{{ $batchLogs->count() }} Aktivitas</span>
                </div>
                <div class="card-body p-2.5">
                    @foreach($batchLogs as $index => $bLog)
                    <div class="bg-light p-2 border rounded mb-2 fw-bold d-flex justify-content-between align-items-center flex-wrap gap-1" style="font-size: 0.72rem;">
                        <span>#{{ $index + 1 }} - {{ $bLog->description }}</span>
                        <span class="text-muted" style="font-size: 0.65rem;">{{ $bLog->subject_type }} (ID: {{ $bLog->subject_id }})</span>
                    </div>
                    <div class="table-responsive table-responsive-custom mb-3">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead class="table-header-custom">
                                <tr>
                                    <th style="width: 30%;">Field Data (Atribut)</th>
                                    <th style="width: 35%;" class="text-danger">Data Lama (Before)</th>
                                    <th style="width: 35%;" class="text-success">Data Baru (After)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $properties = $bLog->attribute_changes ?? [];
                                    $oldData = $properties['old'] ?? [];
                                    $newData = $properties['attributes'] ?? [];
                                    
                                    $allKeys = array_unique(array_merge(array_keys($oldData), array_keys($newData)));
                                @endphp
                                
                                @forelse($allKeys as $key)
                                    @php
                                        $oldVal = array_key_exists($key, $oldData) ? $oldData[$key] : '-';
                                        $newVal = array_key_exists($key, $newData) ? $newData[$key] : '-';
                                        $isChanged = ($bLog->event === 'updated' && $oldVal != $newVal);
                                    @endphp
                                    <tr class="{{ $isChanged ? 'table-warning' : '' }}">
                                        <td class="fw-bold">{{ $key }}</td>
                                        <td class="{{ $isChanged ? 'text-danger fw-bold' : '' }}">
                                            {{ is_array($oldVal) ? json_encode($oldVal) : (is_null($oldVal) ? 'NULL' : $oldVal) }}
                                        </td>
                                        <td class="{{ $isChanged ? 'text-success fw-bold' : '' }}">
                                            {{ is_array($newVal) ? json_encode($newVal) : (is_null($newVal) ? 'NULL' : $newVal) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-2 text-muted">Tidak ada detail data yang direkam.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection