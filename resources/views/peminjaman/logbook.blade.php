@extends('layouts.app')
@section('title', 'Logbook ' . $alat->nama_alat)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0 lb-title" style="color:var(--sl-primary);">Log Book Peminjaman {{ $alat->nama_alat }}</h4>
        <small class="text-muted">No. Alat: {{ $alat->kode_alat }}</small>
    </div>
    <div class="d-flex gap-2 flex-wrap lb-actions">
        <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-secondary">&larr; Daftar Alat</a>
        <a href="{{ route('peminjaman.alat.pdf', $alat->alat_id) }}" target="_blank" class="btn btn-sm btn-outline-secondary fw-semibold">
            <i class="fas fa-file-pdf me-1"></i> Cetak PDF
        </a>
        @if($bisaMengisi)
            <button type="button" class="btn btn-sm text-white fw-semibold lb-primary-act" style="background:var(--sl-primary);" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-plus me-1"></i> Tambah Peminjaman
            </button>
        @endif
    </div>
</div>

@if($errors->any() && !$bisaMengisi)
    <div class="alert alert-danger py-2 small"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<style>
    .lb-btn { width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;
              padding: 0 !important; border-radius: 6px; font-size: 0.75rem; }
    .lb-table thead th { background: var(--sl-primary); color: #fff; font-weight: 600; font-size: 0.78rem; vertical-align: middle; border-color: #2a456f; }
    .lb-table td { vertical-align: middle; }
    .lb-status { font-size: 0.7rem; font-weight: 600; padding: 3px 8px; border-radius: 999px; display: inline-flex; align-items: center; gap: 4px; }
    .lb-status.ok { background: #e6f4ea; color: #1e7a3c; border: 1px solid #b7e0c3; }
    .lb-status.wait { background: #fff4d6; color: #8a6100; border: 1px solid #f3dd9a; }
    .lb-status.done { background: #e7f1fb; color: #1b5fa8; border: 1px solid #b9d4f1; }
    .lb-status.no { background: #fdeaea; color: #b02a2a; border: 1px solid #f3bcbc; }
    .lb-kembali-form { display: inline-flex; gap: 4px; align-items: center; }
    .lb-kembali-form input[type=date] { width: 122px; font-size: 0.75rem; height: 30px; }
    .lb-kembali-btn { height: 30px; padding: 0 12px; display: inline-flex; align-items: center; gap: 6px;
                      background: var(--sl-primary); border: 1px solid var(--sl-primary); color: #fff; border-radius: 6px;
                      font-size: 0.75rem; font-weight: 600; white-space: nowrap; transition: background .15s ease; }
    .lb-kembali-btn:hover, .lb-kembali-btn:focus { background: #14253e; color: #fff; }
    .lb-kembali-btn.koreksi { background: #fff; color: var(--sl-primary); }
    .lb-kembali-btn.koreksi:hover, .lb-kembali-btn.koreksi:focus { background: #eef2f8; color: var(--sl-primary); }

    .pagination .page-link { font-size: 0.72rem; padding: 0.2rem 0.55rem; }

    /* Desktop/tablet: lebar kolom dikunci supaya kolom Pekerjaan (tanpa lebar tetap) mendapat sisa ruang terbesar.
       Jika layar lebih sempit dari min-width, tabel bisa digeser kiri-kanan, bukan menghimpit Pekerjaan. */
    @media (min-width: 768px) {
        .lb-table { table-layout: fixed; min-width: 1045px; }
        .lb-table td { overflow-wrap: anywhere; }
    }

    /* HP / layar kecil: tiap baris tabel jadi satu kartu (label di kiri, isi di kanan), tanpa geser kiri-kanan */
    @media (max-width: 767.98px) {
        .lb-title { font-size: 1.15rem; }
        .lb-actions { width: 100%; }
        .lb-actions .btn { flex: 1 1 auto; }
        .lb-actions .lb-primary-act { flex: 1 1 100%; }

        .lb-card-wrap { background: transparent !important; box-shadow: none !important; }
        .lb-card-wrap > .border-top { background: #fff; border-radius: 10px; margin-top: .75rem; border: 0 !important; box-shadow: 0 .125rem .25rem rgba(0,0,0,.075); }

        .lb-table thead { display: none; }
        .lb-table, .lb-table tbody, .lb-table tr, .lb-table td { display: block; width: 100%; }
        .lb-table tr { background: #fff; border: 1px solid #dee2e6; border-radius: 10px; margin: 0 0 .75rem; overflow: hidden; box-shadow: 0 .125rem .25rem rgba(0,0,0,.05); }
        .lb-table tr.table-danger { background: #f8d7da; }
        .lb-table td { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px;
                       text-align: right !important; border: 0 !important; border-bottom: 1px solid #eef1f5 !important; padding: .45rem .75rem; background: transparent; }
        .lb-table td:last-child { border-bottom: 0 !important; }
        .lb-table td::before { content: attr(data-label); flex: 0 0 34%; max-width: 34%; text-align: left; font-size: .68rem; font-weight: 700;
                               text-transform: uppercase; letter-spacing: .03em; color: #6c757d; padding-top: 2px; }
        .lb-table .lb-cell { flex: 1 1 auto; min-width: 0; }
        .lb-table td[data-label="No"] { background: #f4f6fa; }
        .lb-table td[data-label="Aksi"] .lb-cell > * { margin-left: auto; }
        .lb-table td.lb-empty { display: block; text-align: center !important; border: 0 !important; }
        .lb-table td.lb-empty::before { display: none; }
        .lb-kembali-form { display: flex; width: 100%; justify-content: flex-end; }
        .lb-kembali-form input[type=date] { width: auto; flex: 1 1 auto; min-width: 0; height: 36px; font-size: .85rem; }
        .lb-kembali-btn { height: 36px; }
        .lb-btn { width: 36px; height: 36px; }
    }
</style>

<form method="GET" class="mb-3">
    <div class="input-group input-group-sm">
        <input type="text" name="search" value="{{ $cari }}" class="form-control" placeholder="Cari pekerjaan / petugas...">
        @if($cari !== '')
            <a href="{{ route('peminjaman.alat', $alat->alat_id) }}" class="btn btn-outline-secondary">Reset</a>
        @endif
        <button type="submit" class="btn text-white fw-semibold px-4" style="background:var(--sl-primary);">
            <i class="fas fa-search me-1"></i> Cari
        </button>
    </div>
</form>

<div class="card border-0 shadow-sm mb-3 lb-card-wrap">
    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle mb-0 lb-table" style="font-size:0.85rem;">
            <thead class="text-center">
                <tr>
                    <th style="width:45px;">No</th>
                    <th style="width:105px;">Tanggal</th>
                    <th style="width:235px;">Dikembalikan</th>
                    <th>Pekerjaan</th>
                    <th style="width:140px;">Petugas</th>
                    <th style="width:90px;">Paraf</th>
                    <th style="width:140px;">Status</th>
                    <th style="width:90px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($baris as $i => $b)
                <tr class="{{ $b->status === 'ditolak' ? 'table-danger' : '' }}">
                    <td class="text-center" data-label="No"><div class="lb-cell">{{ $baris->firstItem() + $i }}</div></td>
                    <td class="text-center" data-label="Tanggal"><div class="lb-cell">{{ $b->tanggal->format('d M Y') }}</div></td>
                    <td class="text-center" data-label="Dikembalikan"><div class="lb-cell">
                        @php
                            $punyaAkses = $pengelola || $b->dibuat_oleh === auth()->id();
                            $bisaCatat = $b->status === 'disetujui' && $punyaAkses;
                            $bisaKoreksi = $b->status === 'dikembalikan' && $pengelola && !$b->dikoreksi_pada;
                            $lewatJadwal = $b->status === 'disetujui' && $b->rencana_kembali && $b->rencana_kembali->lt(today());
                        @endphp
                        @if($bisaCatat || $bisaKoreksi)
                            @if($b->rencana_kembali)
                                <div class="small {{ $lewatJadwal ? 'text-danger fw-semibold' : 'text-muted' }} mb-1">Rencana: {{ $b->rencana_kembali->format('d M Y') }}{{ $lewatJadwal ? ' (lewat)' : '' }}</div>
                            @endif
                            <form method="POST" action="{{ route('peminjaman.kembali', $b->logbook_peminjaman_id) }}" class="lb-kembali-form">
                                @csrf
                                <input type="date" name="tanggal_kembali" min="{{ $b->tanggal->toDateString() }}"
                                       value="{{ $b->tanggal_kembali ? $b->tanggal_kembali->toDateString() : date('Y-m-d') }}"
                                       class="form-control form-control-sm" required>
                                <button type="submit" class="lb-kembali-btn {{ $bisaKoreksi ? 'koreksi' : '' }} shadow-sm"
                                        title="{{ $bisaKoreksi ? 'Koreksi tanggal dikembalikan' : 'Catat alat sudah dikembalikan' }}"
                                        data-swal-confirm="{{ $bisaKoreksi ? 'Simpan koreksi tanggal dikembalikan?' : 'Catat alat ini sudah dikembalikan?' }}"
                                        data-swal-title="{{ $bisaKoreksi ? 'Koreksi Tanggal?' : 'Alat Sudah Dikembalikan?' }}"
                                        data-swal-ok="{{ $bisaKoreksi ? 'Ya, Simpan' : 'Ya, Catat' }}">
                                    <i class="fas {{ $bisaKoreksi ? 'fa-pen' : 'fa-check' }}"></i>{{ $bisaKoreksi ? 'Koreksi' : 'Kembali' }}
                                </button>
                            </form>
                        @elseif($b->status === 'dikembalikan')
                            {{ $b->tanggal_kembali->format('d M Y') }}
                            @if($b->dikoreksi_pada)<div class="small text-muted">(sudah dikoreksi)</div>@endif
                        @elseif($b->rencana_kembali)
                            <span class="text-muted small">Rencana: {{ $b->rencana_kembali->format('d M Y') }}</span>
                        @else
                            <span class="text-muted">&ndash;</span>
                        @endif
                    </div></td>
                    <td data-label="Pekerjaan"><div class="lb-cell">{{ $b->pekerjaan }}</div></td>
                    <td data-label="Petugas"><div class="lb-cell">{{ $b->petugas }}</div></td>
                    <td class="text-center" data-label="Paraf"><div class="lb-cell">@if($b->paraf)<img src="{{ $b->paraf }}" alt="paraf" style="max-height:40px; max-width:100%;">@else<span class="text-muted">&ndash;</span>@endif</div></td>
                    <td class="text-center" data-label="Status"><div class="lb-cell">
                        @if($b->status === 'dikembalikan')
                            <span class="lb-status done"><i class="fas fa-box-open"></i> Dikembalikan</span>
                            <div class="small text-muted mt-1">Disetujui oleh: {{ $b->penyetuju->personil->nama ?? $b->penyetuju->username ?? '' }}</div>
                        @elseif($b->status === 'disetujui')
                            <span class="lb-status ok"><i class="fas fa-check-circle"></i> Disetujui</span>
                            <div class="small text-muted mt-1">{{ $b->penyetuju->personil->nama ?? $b->penyetuju->username ?? '' }}</div>
                        @elseif($b->status === 'ditolak')
                            <span class="lb-status no"><i class="fas fa-times-circle"></i> Ditolak</span>
                            @if($b->catatan_approval)<div class="small text-muted mt-1">{{ $b->catatan_approval }}</div>@endif
                        @else
                            <span class="lb-status wait"><i class="fas fa-clock"></i> Menunggu</span>
                        @endif
                    </div></td>
                    <td class="text-center text-nowrap" data-label="Aksi"><div class="lb-cell">
                        @if($b->status === 'diajukan' && $pengelola)
                            <div class="d-inline-flex gap-1">
                                <form method="POST" action="{{ route('peminjaman.approve', $b->logbook_peminjaman_id) }}">@csrf
                                    <button class="btn btn-success btn-sm lb-btn shadow-sm" title="Setujui" aria-label="Setujui"><i class="fas fa-check"></i></button>
                                </form>
                                <button type="button" class="btn btn-danger btn-sm lb-btn shadow-sm js-tolak"
                                        data-action="{{ route('peminjaman.reject', $b->logbook_peminjaman_id) }}"
                                        title="Tolak" aria-label="Tolak"><i class="fas fa-times"></i></button>
                            </div>
                        @elseif($b->status === 'diajukan' && $b->dibuat_oleh === auth()->id())
                            <form method="POST" action="{{ route('peminjaman.batal', $b->logbook_peminjaman_id) }}">@csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm lb-btn shadow-sm"
                                        data-swal-confirm="Tarik catatan ini?" data-swal-title="Tarik Catatan?" data-swal-ok="Ya, Tarik" title="Batalkan catatan" aria-label="Batalkan catatan"><i class="fas fa-undo"></i></button>
                            </form>
                        @elseif($b->status === 'ditolak' && $pengelola)
                            <form method="POST" action="{{ route('peminjaman.hapus', $b->logbook_peminjaman_id) }}">@csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm lb-btn shadow-sm"
                                        data-swal-confirm="Hapus catatan yang ditolak ini?" data-swal-title="Hapus Catatan?" data-swal-ok="Ya, Hapus!" data-swal-danger title="Hapus catatan" aria-label="Hapus catatan"><i class="fas fa-trash"></i></button>
                            </form>
                        @else
                            <span class="text-muted">&ndash;</span>
                        @endif
                    </div></td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4 lb-empty">
                        @if($cari !== '')
                            Tidak ada catatan yang cocok dengan pencarian "{{ $cari }}".
                        @else
                            Belum ada catatan peminjaman untuk alat ini.
                        @endif
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($baris->hasPages())
        <div class="px-3 py-2 border-top">
            {{ $baris->withQueryString()->links('vendor.pagination.custom', ['size' => 'sm']) }}
        </div>
    @endif
</div>

@if($bisaMengisi)
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="{{ route('peminjaman.store', $alat->alat_id) }}" id="formLogbook">
                @csrf
                <div class="modal-header" style="background:var(--sl-primary); color:#fff;">
                    <h6 class="modal-title fw-bold mb-0"><i class="fas fa-plus me-1"></i> Tambah Peminjaman: {{ $alat->nama_alat }}</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert alert-danger py-2 small"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                    @endif
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold mb-1">Tanggal Pinjam</label>
                            <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold mb-1">Rencana Dikembalikan</label>
                            <input type="date" name="rencana_kembali" class="form-control form-control-sm" value="{{ old('rencana_kembali') }}">
                            <div class="form-text" style="font-size:0.7rem;">Opsional. Tanggal sebenarnya dicatat nanti lewat tombol "Kembali".</div>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label small fw-bold mb-1">Pekerjaan</label>
                            <input type="text" name="pekerjaan" class="form-control form-control-sm" value="{{ old('pekerjaan') }}" maxlength="255" placeholder="Contoh: DS Final Bunn Sagara" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold mb-1">Petugas</label>
                            <input type="text" name="petugas" class="form-control form-control-sm" value="{{ old('petugas', $namaDefault) }}" maxlength="100" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold mb-1">Paraf <span class="text-muted fw-normal">(gambar di kotak dengan mouse / jari)</span></label>
                            <div>
                                <canvas id="canvasParaf" width="480" height="140" style="display:block; width:480px; max-width:100%; height:auto; aspect-ratio:480/140; border:1px solid #adb5bd; border-radius:6px; background:#fff; touch-action:none;"></canvas>
                            </div>
                            <button type="button" id="hapusParaf" class="btn btn-outline-secondary btn-sm mt-1">Hapus paraf</button>
                            <input type="hidden" name="paraf" id="inputParaf">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm text-white fw-semibold px-4" style="background:var(--sl-primary);">Simpan Catatan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@if($pengelola)
<div class="modal fade" id="modalTolak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="formTolak" action="">
                @csrf
                <div class="modal-header" style="background:var(--sl-primary); color:#fff;">
                    <h6 class="modal-title fw-bold mb-0"><i class="fas fa-times-circle me-1"></i> Tolak Peminjaman</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label small fw-bold mb-1">Alasan penolakan</label>
                    <textarea name="catatan_approval" class="form-control form-control-sm" rows="3" maxlength="500" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-danger fw-semibold px-4">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection


@push('scripts')
@include('partials.swal-confirm')
@if($bisaMengisi)
<script>
(function () {
    var c = document.getElementById('canvasParaf'); if (!c) return;
    var ctx = c.getContext('2d'), gambar = false, adaIsi = false;
    ctx.lineWidth = 2.2; ctx.lineCap = 'round'; ctx.strokeStyle = '#000';

    function pos(e) {
        var r = c.getBoundingClientRect(), t = e.touches ? e.touches[0] : e;
        return { x: (t.clientX - r.left) * (c.width / r.width), y: (t.clientY - r.top) * (c.height / r.height) };
    }
    function mulai(e) { e.preventDefault(); gambar = true; var p = pos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); }
    function gerak(e) { if (!gambar) return; e.preventDefault(); var p = pos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); adaIsi = true; }
    function selesai() { gambar = false; }

    c.addEventListener('mousedown', mulai); c.addEventListener('mousemove', gerak);
    window.addEventListener('mouseup', selesai);
    c.addEventListener('touchstart', mulai, { passive: false });
    c.addEventListener('touchmove', gerak, { passive: false });
    c.addEventListener('touchend', selesai);

    document.getElementById('hapusParaf').addEventListener('click', function () {
        ctx.clearRect(0, 0, c.width, c.height); adaIsi = false;
    });

    document.getElementById('formLogbook').addEventListener('submit', function (e) {
        if (!adaIsi) { e.preventDefault(); Swal.fire({ title: 'Paraf Belum Diisi', text: 'Gambar paraf di kotak yang tersedia sebelum menyimpan.', icon: 'warning', confirmButtonColor: '#1a7fc4', confirmButtonText: 'Mengerti' }); return; }
        document.getElementById('inputParaf').value = c.toDataURL('image/png');
    });

    @if($errors->any())
        // Ada error validasi: buka lagi jendela form agar isiannya terlihat.
        window.addEventListener('load', function () {
            new bootstrap.Modal(document.getElementById('modalTambah')).show();
        });
    @endif
})();
</script>
@endif
@endpush

@push('scripts')
@if($pengelola)
<script>
    document.querySelectorAll('.js-tolak').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('formTolak').action = btn.dataset.action;
            document.getElementById('formTolak').catatan_approval.value = '';
            new bootstrap.Modal(document.getElementById('modalTolak')).show();
        });
    });
</script>
@endif
@endpush