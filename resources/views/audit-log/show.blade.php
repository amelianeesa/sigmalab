@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Detail Rekam Jejak (Audit Log)</h2>
            <p class="text-muted mb-0">Inspeksi data yang ditambahkan, diubah, atau dihapus.</p>
        </div>
        <a href="{{ route('audit-log.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Meta</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%" class="text-muted">Aktor / User</th>
                            <td>: <span class="fw-bold">{{ $log->causer ? ($log->causer->personil->nama_personil ?? $log->causer->username) : 'Sistem' }}</span>
                                @if($log->causer && $log->causer->role)
                                    <span class="badge bg-secondary ms-2">{{ $log->causer->role->nama_role }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Event</th>
                            <td>: 
                                @if($log->event === 'created')
                                    <span class="badge bg-success">Created</span>
                                @elseif($log->event === 'updated')
                                    <span class="badge bg-warning text-dark">Updated</span>
                                @elseif($log->event === 'deleted')
                                    <span class="badge bg-danger">Deleted</span>
                                @else
                                    <span class="badge bg-secondary">{{ $log->event }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Deskripsi</th>
                            <td>: {{ $log->description }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Model Entitas</th>
                            <td>: {{ $log->subject_type }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">ID Entitas (PK)</th>
                            <td>: {{ $log->subject_id }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Waktu Eksekusi</th>
                            <td>: {{ \Carbon\Carbon::parse($log->created_at)->format('d F Y H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-7 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Visualisasi Data Berubah</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="30%">Field Data (Atribut)</th>
                                    <th width="35%" class="text-danger">Data Lama (Before)</th>
                                    <th width="35%" class="text-success">Data Baru (After)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $properties = $log->properties;
                                    $oldData = $properties['old'] ?? [];
                                    $newData = $properties['attributes'] ?? [];
                                    
                                    // Combine all unique keys from old and new
                                    $allKeys = array_unique(array_merge(array_keys($oldData), array_keys($newData)));
                                @endphp
                                
                                @forelse($allKeys as $key)
                                    @php
                                        $oldVal = array_key_exists($key, $oldData) ? $oldData[$key] : '-';
                                        $newVal = array_key_exists($key, $newData) ? $newData[$key] : '-';
                                        
                                        // Highlight differences
                                        $isChanged = ($log->event === 'updated' && $oldVal != $newVal);
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
                                        <td colspan="3" class="text-center py-3 text-muted">Tidak ada detail data yang direkam (hanya log statis).</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
