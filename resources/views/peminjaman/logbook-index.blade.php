@extends('layouts.app')
@section('title', 'Logbook Peminjaman Alat')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1b3152;">Logbook Peminjaman Alat</h4>
        <small class="text-muted">Pilih alat untuk melihat dan mencetak logbook-nya.</small>
    </div>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-secondary">&larr; Kembali</a>
</div>

<div class="row g-3">
    @forelse($alatList as $a)
        <div class="col-md-4 col-lg-3">
            <a href="{{ route('peminjaman.logbook.show', $a->alat_id) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="fw-bold" style="color:#1b3152;">{{ $a->nama_alat }}</div>
                        <div class="small text-muted">{{ $a->kode_alat }}</div>
                        <div class="small mt-2"><i class="fas fa-clipboard-list me-1"></i> {{ $jumlah[$a->alat_id] ?? 0 }} catatan</div>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12 text-muted small">Belum ada alat yang ditandai "Dapat dipinjam".</div>
    @endforelse
</div>
@endsection
