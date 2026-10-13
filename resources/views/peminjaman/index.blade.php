@extends('layouts.app')
@section('title', 'Peminjaman Alat')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1b3152;">Peminjaman Alat</h4>
        <small class="text-muted">{{ $lihatSemua ? 'Semua pengajuan peminjaman' : 'Pengajuan peminjaman Anda' }}</small>
    </div>
    @if($bisaAjukan)
        <a href="{{ route('peminjaman.create') }}" class="btn btn-sm text-white fw-semibold" style="background:#1b3152;">
            <i class="fas fa-plus me-1"></i> Ajukan Peminjaman
        </a>
    @endif
</div>

<form method="GET" class="mb-3">
    <select name="status" class="form-select form-select-sm" style="max-width:220px;" onchange="this.form.submit()">
        <option value="">Semua status</option>
        @foreach(\App\Models\Peminjaman::STATUS_LABEL as $key => $info)
            <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>{{ $info[0] }}</option>
        @endforeach
    </select>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-sm align-middle mb-0" style="font-size:0.85rem;">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Kode</th>
                    @if($lihatSemua)<th>Peminjam</th>@endif
                    <th>Alat</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th class="text-end pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($daftar as $p)
                @php [$label, $warna] = \App\Models\Peminjaman::STATUS_LABEL[$p->status] ?? [$p->status, 'secondary']; @endphp
                <tr>
                    <td class="ps-3 fw-semibold">{{ $p->kode }}</td>
                    @if($lihatSemua)<td>{{ $p->peminjam->personil->nama ?? $p->peminjam->username }}</td>@endif
                    <td>
                        {{ $p->items->take(2)->map(fn($i) => $i->alat->nama_alat ?? '-')->implode(', ') }}
                        @if($p->items->count() > 2)<span class="text-muted">+{{ $p->items->count() - 2 }} lainnya</span>@endif
                    </td>
                    <td>{{ $p->tgl_pinjam->format('d M Y') }} – {{ $p->tgl_rencana_kembali->format('d M Y') }}</td>
                    <td>
                        <span class="badge bg-{{ $warna }}">{{ $label }}</span>
                        @if($p->terlambat)<span class="badge bg-danger">Terlambat</span>@endif
                    </td>
                    <td class="text-end pe-3">
                        <a href="{{ route('peminjaman.show', $p->peminjaman_id) }}" class="btn btn-outline-primary btn-sm py-0 px-2">Detail</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data peminjaman.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $daftar->links() }}</div>
@endsection
