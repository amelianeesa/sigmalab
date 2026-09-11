@extends('layouts.app')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Notifikasi</li>
    </ol>
</nav>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Notifikasi</h5>
        <form action="{{ route('notifikasi.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-primary">Tandai Semua Dibaca</button>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <tbody>
                    @forelse($notifikasis as $notif)
                        <tr class="{{ !$notif->is_read ? 'bg-warning bg-opacity-10' : '' }}">
                            <td class="text-center align-middle" style="width: 50px;">
                                @if($notif->jenis_notifikasi == 'qc')
                                    <i class="fas fa-flask text-primary"></i>
                                @elseif($notif->jenis_notifikasi == 'kalibrasi')
                                    <i class="fas fa-tools text-warning"></i>
                                @elseif($notif->jenis_notifikasi == 'stok')
                                    <i class="fas fa-box text-success"></i>
                                @elseif($notif->jenis_notifikasi == 'sertifikasi')
                                    <i class="fas fa-certificate text-danger"></i>
                                @else
                                    <i class="fas fa-bell text-secondary"></i>
                                @endif
                            </td>
                            <td>
                                <div>{{ $notif->pesan }}</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</small>
                            </td>
                            <td class="text-end align-middle">
                                @if(!$notif->is_read)
                                    <span class="badge bg-danger me-2">Belum Dibaca</span>
                                    <form action="{{ route('notifikasi.read', $notif->notifikasi_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success">Tandai Dibaca</button>
                                    </form>
                                @else
                                    <span class="badge bg-secondary">Sudah Dibaca</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">Tidak ada notifikasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($notifikasis->hasPages())
    <div class="card-footer bg-white">
        {{ $notifikasis->links() }}
    </div>
    @endif
</div>
@endsection
