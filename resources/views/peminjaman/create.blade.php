@extends('layouts.app')
@section('title', 'Ajukan Peminjaman Alat')

@section('content')
<div class="mb-3">
    <h4 class="fw-bold mb-0" style="color:#1b3152;">Ajukan Peminjaman Alat</h4>
    <small class="text-muted">Pilih alat induk dan/atau sub alat yang dibutuhkan.</small>
</div>

@if($errors->any())
    <div class="alert alert-danger py-2 small">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ route('peminjaman.store') }}">
    @csrf
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label small fw-bold mb-1">Pekerjaan / Keperluan</label>
                    <input type="text" name="tujuan" class="form-control form-control-sm" value="{{ old('tujuan') }}" maxlength="255" placeholder="Contoh: DS Final Bunn Sagara" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold mb-1">Tanggal Pinjam</label>
                    <input type="date" name="tgl_pinjam" class="form-control form-control-sm" value="{{ old('tgl_pinjam', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold mb-1">Rencana Kembali</label>
                    <input type="date" name="tgl_rencana_kembali" class="form-control form-control-sm" value="{{ old('tgl_rencana_kembali') }}" min="{{ date('Y-m-d') }}" required>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white fw-bold small">Pilih Alat</div>
        <div class="card-body">
            @forelse($induk as $a)
                @php $keluar = in_array($a->alat_id, $kunciSekarang); @endphp
                <div class="border rounded p-2 mb-2">
                    <div class="form-check">
                        <input class="form-check-input induk-cb" type="checkbox" name="alat_ids[]" value="{{ $a->alat_id }}"
                               id="alat{{ $a->alat_id }}" data-induk="{{ $a->alat_id }}"
                               {{ !$a->dapat_dipinjam || $keluar || $a->kondisi_barang !== 'baik' ? 'disabled' : '' }}
                               {{ in_array($a->alat_id, old('alat_ids', [])) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="alat{{ $a->alat_id }}">
                            {{ $a->nama_alat }} <small class="text-muted">({{ $a->kode_alat }})</small>
                            @if($keluar)<span class="badge bg-secondary">Sedang dipinjam</span>@endif
                            @if($a->kondisi_barang !== 'baik')<span class="badge bg-danger">{{ ucfirst($a->kondisi_barang) }}</span>@endif
                        </label>
                    </div>
                    @if($a->subAlat->isNotEmpty())
                        <div class="ms-4 mt-1">
                            @foreach($a->subAlat as $s)
                                @php $subKeluar = in_array($s->alat_id, $kunciSekarang); @endphp
                                <div class="form-check">
                                    <input class="form-check-input sub-cb" type="checkbox" name="alat_ids[]" value="{{ $s->alat_id }}"
                                           id="alat{{ $s->alat_id }}" data-parent="{{ $a->alat_id }}"
                                           {{ $subKeluar ? 'disabled' : '' }}
                                           {{ in_array($s->alat_id, old('alat_ids', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="alat{{ $s->alat_id }}">
                                        {{ $s->nama_alat }} <small class="text-muted">({{ $s->kode_alat }})</small>
                                        @if($subKeluar)<span class="badge bg-secondary">Sedang dipinjam</span>@endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-muted small mb-0">Belum ada alat yang ditandai dapat dipinjam. Hubungi GA untuk mengatur data alat.</p>
            @endforelse
        </div>
    </div>

    <button type="submit" class="btn btn-sm text-white fw-semibold px-4" style="background:#1b3152;">Kirim Pengajuan</button>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-secondary px-3">Batal</a>
</form>
@endsection

@push('scripts')
<script>
    // Mencentang alat induk otomatis mencentang semua sub alatnya (tetap bisa dilepas satu per satu).
    document.querySelectorAll('.induk-cb').forEach(function (cb) {
        cb.addEventListener('change', function () {
            document.querySelectorAll('.sub-cb[data-parent="' + cb.dataset.induk + '"]:not(:disabled)')
                .forEach(function (s) { s.checked = cb.checked; });
        });
    });
</script>
@endpush
