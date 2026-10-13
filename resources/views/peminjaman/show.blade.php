@extends('layouts.app')
@section('title', 'Detail Peminjaman')

@section('content')
@php [$label, $warna] = \App\Models\Peminjaman::STATUS_LABEL[$p->status] ?? [$p->status, 'secondary']; @endphp

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1b3152;">{{ $p->kode }}</h4>
        <span class="badge bg-{{ $warna }}">{{ $label }}</span>
        @if($p->terlambat)<span class="badge bg-danger">Terlambat</span>@endif
    </div>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-secondary">&larr; Kembali</a>
</div>

@if($errors->any())
    <div class="alert alert-danger py-2 small"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body small">
        <div class="row g-2">
            <div class="col-md-4"><div class="text-muted">Peminjam</div><strong>{{ $p->peminjam->personil->nama ?? $p->peminjam->username }}</strong></div>
            <div class="col-md-4"><div class="text-muted">Pekerjaan</div><strong>{{ $p->tujuan }}</strong></div>
            <div class="col-md-4"><div class="text-muted">Periode</div><strong>{{ $p->tgl_pinjam->format('d M Y') }} – {{ $p->tgl_rencana_kembali->format('d M Y') }}</strong></div>
            @if($p->penyetuju)
                <div class="col-md-4"><div class="text-muted">Diputuskan oleh</div><strong>{{ $p->penyetuju->personil->nama ?? $p->penyetuju->username }}</strong> ({{ $p->tgl_keputusan?->format('d M Y H:i') }})</div>
                <div class="col-md-8"><div class="text-muted">Catatan</div><strong>{{ $p->catatan_approval ?: '-' }}</strong></div>
            @endif
            @if($p->tgl_kembali_aktual)
                <div class="col-md-4"><div class="text-muted">Dikembalikan</div><strong>{{ $p->tgl_kembali_aktual->format('d M Y') }}</strong></div>
            @endif
        </div>
    </div>
</div>

<form method="POST" action="{{ route('peminjaman.kembalikan', $p->peminjaman_id) }}">
@csrf
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white fw-bold small">Alat yang Dipinjam</div>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0" style="font-size:0.85rem;">
            <thead class="table-light">
                <tr><th class="ps-3">Alat</th><th>Kondisi keluar</th><th>Kondisi kembali</th><th>Catatan</th></tr>
            </thead>
            <tbody>
            @foreach($p->items as $item)
                <tr>
                    <td class="ps-3">{{ $item->alat->nama_alat ?? '-' }} <small class="text-muted">({{ $item->alat->kode_alat ?? '-' }})</small></td>
                    <td>{{ ucfirst($item->kondisi_keluar) }}</td>
                    @if($pengelola && $p->status === 'dipinjam')
                        <td>
                            <select name="kondisi[{{ $item->peminjaman_item_id }}]" class="form-select form-select-sm" style="max-width:140px;">
                                <option value="baik">Baik</option>
                                <option value="rusak">Rusak</option>
                                <option value="perbaikan">Perlu perbaikan</option>
                            </select>
                        </td>
                        <td><input type="text" name="catatan[{{ $item->peminjaman_item_id }}]" class="form-control form-control-sm" maxlength="255"></td>
                    @else
                        <td>{{ $item->kondisi_kembali ? ucfirst($item->kondisi_kembali) : '-' }}</td>
                        <td>{{ $item->catatan_kembali ?: '-' }}</td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if($pengelola && $p->status === 'dipinjam')
        <div class="card-footer bg-white text-end">
            <button class="btn btn-success btn-sm fw-semibold" onclick="return confirm('Catat pengembalian alat?')">Catat Pengembalian</button>
        </div>
    @endif
</div>
</form>

<div class="d-flex gap-2 flex-wrap">
    @if($pengelola && $p->status === 'diajukan')
        <form method="POST" action="{{ route('peminjaman.approve', $p->peminjaman_id) }}" class="d-flex gap-2">
            @csrf
            <input type="text" name="catatan_approval" class="form-control form-control-sm" placeholder="Catatan (opsional)" style="min-width:220px;">
            <button class="btn btn-success btn-sm fw-semibold">Setujui</button>
        </form>
        <form method="POST" action="{{ route('peminjaman.reject', $p->peminjaman_id) }}" class="d-flex gap-2">
            @csrf
            <input type="text" name="catatan_approval" class="form-control form-control-sm" placeholder="Alasan penolakan" style="min-width:220px;" required>
            <button class="btn btn-danger btn-sm fw-semibold">Tolak</button>
        </form>
    @endif

    @if($pengelola && $p->status === 'disetujui')
        <form method="POST" action="{{ route('peminjaman.serahkan', $p->peminjaman_id) }}">
            @csrf
            <button class="btn btn-primary btn-sm fw-semibold" onclick="return confirm('Tandai alat sudah diserahkan?')">Serahkan Alat</button>
        </form>
    @endif

    @if(in_array($p->status, ['diajukan', 'disetujui']) && ($p->peminjam_id === auth()->id() || $pengelola))
        <form method="POST" action="{{ route('peminjaman.batal', $p->peminjaman_id) }}">
            @csrf
            <button class="btn btn-outline-secondary btn-sm" onclick="return confirm('Batalkan peminjaman ini?')">Batalkan</button>
        </form>
    @endif
</div>
@endsection
