@extends('layouts.app')
@section('title', 'Daftar - Notifikasi')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Notifikasi</li>
    </ol>
</nav>

<style>
    .notif-page-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px 20px;
    }
    .notif-page-item.unread {
        background: rgba(37, 99, 235, 0.05);
    }
    .notif-page-icon {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    .notif-page-msg {
        font-size: 0.92rem;
        color: #334155;
        margin-bottom: 4px;
        line-height: 1.4;
    }
    .notif-page-msg.text-danger-emphasis {
        color: #b91c1c !important;
    }
    .notif-page-time {
        font-size: 0.78rem;
        color: #94a3b8;
    }
    .notif-page-actions {
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
        margin-left: auto;
    }
    @media (max-width: 575.98px) {
        .notif-page-item {
            flex-wrap: wrap;
        }
        .notif-page-actions {
            flex-direction: row;
            align-items: center;
            margin-left: 54px;
        }
    }
</style>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">Daftar Notifikasi</h5>
        <form action="{{ route('notifikasi.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
            </button>
        </form>
    </div>

    <div class="list-group list-group-flush">
        @forelse($notifikasis as $notif)
            @php
                $isDitolak = str_contains(strtolower($notif->pesan), 'ditolak');
                $iconBg = match($notif->jenis_notifikasi) {
                    'qc' => 'bg-primary',
                    'kalibrasi' => 'bg-warning',
                    'stok' => 'bg-success',
                    'sertifikasi' => 'bg-danger',
                    default => 'bg-secondary',
                };
                $icon = match($notif->jenis_notifikasi) {
                    'qc' => 'fa-flask',
                    'kalibrasi' => 'fa-tools',
                    'stok' => 'fa-box',
                    'sertifikasi' => 'fa-certificate',
                    default => 'fa-bell',
                };
            @endphp
            <div class="list-group-item notif-page-item {{ !$notif->is_read ? 'unread' : '' }}">
                <span class="notif-page-icon {{ $iconBg }}">
                    <i class="fas {{ $icon }} text-white"></i>
                </span>

                <div class="flex-grow-1" style="min-width: 200px;">
                    <p class="notif-page-msg {{ $isDitolak ? 'text-danger-emphasis' : '' }} mb-1">{{ $notif->pesan }}</p>
                    <span class="notif-page-time">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</span>
                </div>

                <div class="notif-page-actions">
                    @if(!$notif->is_read)
                        <span class="badge bg-danger">Belum Dibaca</span>
                        <form action="{{ route('notifikasi.read', $notif->notifikasi_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-success">Tandai Dibaca</button>
                        </form>
                    @else
                        <span class="badge bg-secondary">Sudah Dibaca</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                <i class="fas fa-bell-slash fs-1 mb-2 d-block opacity-50"></i>
                Tidak ada notifikasi.
            </div>
        @endforelse
    </div>

    @if($notifikasis->hasPages())
    <div class="card-footer bg-white">
        {{ $notifikasis->links() }}
    </div>
    @endif
</div>
@endsection