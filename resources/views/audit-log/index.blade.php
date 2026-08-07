@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">CCTV Audit Log</h2>
            <p class="text-muted mb-0">Rekam jejak aktivitas pengguna dan perubahan data dalam sistem.</p>
        </div>
    </div>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Audit Log</li>
    </ol>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('audit-log.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-muted small">Tipe Event</label>
                    <select name="event" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Event</option>
                        <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created (Baru)</option>
                        <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated (Ubah)</option>
                        <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted (Hapus)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small">Filter Entitas / Model (opsional)</label>
                    <input type="text" name="subject_type" class="form-control form-control-sm" placeholder="Contoh: Barang, Kegiatan" value="{{ request('subject_type') }}" onkeyup="clearTimeout(window.searchTimer); window.searchTimer = setTimeout(() => this.form.submit(), 500);">
                </div>
                <div class="col-md-2 d-none d-md-block">
                    <!-- Tombol Filter disembunyikan karena sudah live-search, tapi tetap bisa digunakan jika perlu -->
                    <button type="submit" class="btn btn-sm btn-primary w-100 visually-hidden">Filter Log</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('audit-log.index') }}" class="btn btn-sm btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="15%">Waktu Kejadian</th>
                            <th width="15%">Aktor (User)</th>
                            <th width="10%">Event</th>
                            <th width="30%">Deskripsi (Keterangan)</th>
                            <th width="15%">Model Terkait</th>
                            <th width="15%" class="text-center">Perubahan Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i:s') }}
                                    <br><small class="text-muted">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $log->causer ? ($log->causer->personil->nama_personil ?? $log->causer->username) : 'Sistem (Otomatis)' }}</span>
                                    @if($log->causer && $log->causer->role)
                                        <br><small class="text-muted">{{ $log->causer->role->nama_role }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($log->event === 'created')
                                        <span class="badge bg-success p-2">Created</span>
                                    @elseif($log->event === 'updated')
                                        <span class="badge bg-warning text-dark p-2">Updated</span>
                                    @elseif($log->event === 'deleted')
                                        <span class="badge bg-danger p-2">Deleted</span>
                                    @else
                                        <span class="badge bg-secondary p-2">{{ ucfirst($log->event) }}</span>
                                    @endif
                                </td>
                                <td>{{ $log->description }}</td>
                                <td>
                                    @php
                                        $modelPath = explode('\\', $log->subject_type);
                                        $modelName = end($modelPath);
                                    @endphp
                                    <span class="badge bg-light text-dark border">{{ $modelName ?: '-' }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('audit-log.show', $log->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-search me-1"></i> Lihat Data
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada rekam jejak yang tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $logs->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
