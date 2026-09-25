@extends('layouts.app')

@section('content')
<style>
    .btn-corporate-dark {
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
        color: #ffffff !important;
    }
    .btn-corporate-dark:hover,
    .btn-corporate-dark:focus,
    .btn-corporate-dark:active {
        background-color: #14253e !important;
        border-color: #14253e !important;
        color: #ffffff !important;
    }
    .notif-page-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0;
        border-color: #eef0f1;
    }
    .notif-page-item.unread {
        background: rgba(27, 49, 82, 0.05);
    }
    .notif-page-link {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        flex-grow: 1;
        min-width: 0;
        padding: 10px 14px;
        text-decoration: none;
        color: inherit;
        transition: background-color 0.15s ease;
    }
    .notif-page-link:hover {
        background: rgba(27, 49, 82, 0.07);
        color: inherit;
    }
    .notif-page-icon {
        width: 30px;
        height: 30px;
        flex-shrink: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }
    .notif-page-msg {
        font-size: 0.78rem;
        color: #334155;
        margin-bottom: 2px;
        line-height: 1.35;
        word-break: break-word;
    }
    .notif-page-msg.text-danger-emphasis {
        color: #b91c1c !important;
    }
    .notif-page-time {
        font-size: 0.68rem;
        color: #94a3b8;
    }
    .notif-page-actions {
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 4px;
        padding: 8px 14px 8px 0;
    }
    .pagination .page-link {
        font-size: 0.72rem;
        padding: 0.2rem 0.55rem;
    }
    .notif-header-btn {
        white-space: nowrap;
    }

    @media (max-width: 575.98px) {
        .notif-page-item {
            flex-wrap: wrap;
        }
        .notif-page-actions {
            flex-direction: row;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            width: 100%;
            padding: 0 14px 10px 54px;
        }
        .notif-header-row {
            flex-direction: column;
            align-items: stretch !important;
        }
        .notif-header-btn {
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 380px) {
        .notif-page-actions {
            padding-left: 14px;
        }
    }
</style>

<div class="container-fluid px-3 pt-1 pb-2" style="font-size: 0.8rem;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1 mt-1 small" style="font-size: 0.72rem;">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Notifikasi</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2 notif-header-row">
        <h4 class="mb-0 fw-bold" style="font-size: 1.1rem;">Daftar Notifikasi</h4>
        <form action="{{ route('notifikasi.read-all') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn btn-corporate-dark btn-sm py-1 px-3 notif-header-btn" style="font-size: 0.75rem;">
                <i class="fas fa-check-double me-1"></i> Tandai Semua Dibaca
            </button>
        </form>
    </div>

    <div class="card border-0 shadow-sm">
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
                    <a href="{{ route('notifikasi.klik', $notif->notifikasi_id) }}" class="notif-page-link" title="Klik untuk membuka">
                        <span class="notif-page-icon {{ $iconBg }}">
                            <i class="fas {{ $icon }} text-white"></i>
                        </span>
                        <div style="min-width: 0;">
                            <p class="notif-page-msg {{ $isDitolak ? 'text-danger-emphasis' : '' }}">{{ $notif->pesan }}</p>
                            <span class="notif-page-time">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</span>
                        </div>
                    </a>

                    <div class="notif-page-actions">
                        @if(!$notif->is_read)
                            <span class="badge bg-danger" style="font-size: 0.62rem;">Belum Dibaca</span>
                            <form action="{{ route('notifikasi.read', $notif->notifikasi_id) }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-outline-success py-0 px-2" style="font-size: 0.68rem;">Tandai Dibaca</button>
                            </form>
                        @else
                            <span class="badge bg-secondary" style="font-size: 0.62rem;">Sudah Dibaca</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4" style="font-size: 0.78rem;">
                    <i class="fas fa-bell-slash fs-3 mb-2 d-block opacity-50"></i>
                    Tidak ada notifikasi.
                </div>
            @endforelse
        </div>

        @if($notifikasis->hasPages())
        <div class="card-footer bg-white py-2">
            {{ $notifikasis->links('vendor.pagination.custom', ['size' => 'sm']) }}
        </div>
        @endif
    </div>
</div>
@endsection