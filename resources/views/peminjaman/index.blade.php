@extends('layouts.app')
@section('title', 'Peminjaman Alat')

@section('content')
<style>
    .pj-card:hover { box-shadow: 0 .4rem .9rem rgba(27,49,82,.12) !important; }
    .pj-status { font-size: 0.7rem; font-weight: 600; padding: 3px 8px; border-radius: 999px; display: inline-block; }
    .pj-status.tersedia { background: #e6f4ea; color: #1e7a3c; border: 1px solid #b7e0c3; }
    .pj-status.dipinjam { background: #fff4d6; color: #8a6100; border: 1px solid #f3dd9a; }
    .pj-info { font-size: 0.8rem; min-height: 70px; }
</style>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1b3152;">Peminjaman Alat</h4>
        <small class="text-muted">Pilih alat untuk membuka logbook-nya, lalu isi catatan peminjaman.</small>
    </div>
    <a href="{{ route('peminjaman.pdf-semua') }}" target="_blank" class="btn btn-sm btn-outline-secondary fw-semibold">
        <i class="fas fa-file-pdf me-1"></i> Cetak Semua Logbook (PDF)
    </a>
</div>

<form method="GET" class="mb-3">
    <div class="input-group input-group-sm">
        <input type="text" name="search" value="{{ $cari }}" class="form-control" placeholder="Cari nama / kode alat...">
        @if($cari !== '')
            <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary">Reset</a>
        @endif
        <button type="submit" class="btn text-white fw-semibold px-4" style="background:#1b3152;">
            <i class="fas fa-search me-1"></i> Cari
        </button>
    </div>
</form>

<div class="row g-3">
    @forelse($alatList as $a)
        @php
            $r = $ringkas[$a->alat_id] ?? null;
            $aktif = $sedangDipinjam[$a->alat_id] ?? null;
            $akhir = $terakhir[$a->alat_id] ?? null;
            $lewat = $aktif && $aktif->rencana_kembali && $aktif->rencana_kembali->lt(today());
        @endphp
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('peminjaman.alat', $a->alat_id) }}" class="text-decoration-none d-block h-100">
                <div class="card border-0 shadow-sm h-100 pj-card">
                    <div class="card-body d-flex flex-column">
                        <div class="fw-bold" style="color:#1b3152;">{{ $a->nama_alat }}</div>
                        <div class="small text-muted mb-2">{{ $a->kode_alat }}</div>

                        <div class="pj-info text-dark">
                            @if($aktif)
                                <span class="pj-status dipinjam">Sedang dipinjam</span>
                                <div class="mt-1">{{ $aktif->petugas }} &ndash; {{ $aktif->pekerjaan }}</div>
                                @if($aktif->rencana_kembali)
                                    <div class="{{ $lewat ? 'text-danger fw-semibold' : 'text-muted' }}">Rencana kembali: {{ $aktif->rencana_kembali->format('d M Y') }}{{ $lewat ? ' (lewat)' : '' }}</div>
                                @endif
                            @else
                                <span class="pj-status tersedia">Tersedia</span>
                                @if($akhir)
                                    <div class="mt-1 text-muted">Terakhir: {{ $akhir->petugas }}, {{ $akhir->tanggal->format('d M Y') }}</div>
                                @endif
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-2 mt-auto small">
                            <span class="text-dark">
                                <i class="fas fa-book me-1 text-muted"></i>{{ $r->total ?? 0 }} catatan
                                @if(($r->menunggu ?? 0) > 0)
                                    <span class="badge bg-warning text-dark ms-1">{{ $r->menunggu }} menunggu</span>
                                @endif
                            </span>
                            <span class="fw-semibold" style="color:#1b3152;">Buka &rarr;</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12 text-muted small">
            @if($cari !== '')
                Tidak ada alat yang cocok dengan pencarian "{{ $cari }}".
            @else
                Belum ada alat yang ditandai "Dapat dipinjam". Atur di menu Data Alat.
            @endif
        </div>
    @endforelse
</div>
@endsection