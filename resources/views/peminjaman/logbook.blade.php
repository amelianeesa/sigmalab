@extends('layouts.app')
@section('title', 'Logbook ' . $alat->nama_alat)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1b3152;">Log Book Peminjaman {{ $alat->nama_alat }}</h4>
        <small class="text-muted">No. Alat: {{ $alat->kode_alat }}</small>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-secondary">&larr; Daftar Alat</a>
        <a href="{{ route('peminjaman.alat.pdf', $alat->alat_id) }}" target="_blank" class="btn btn-sm btn-outline-secondary fw-semibold">
            <i class="fas fa-file-pdf me-1"></i> Cetak PDF
        </a>
        @if($bisaMengisi)
            <button type="button" class="btn btn-sm text-white fw-semibold" style="background:#1b3152;" data-bs-toggle="modal" data-bs-target="#modalTambah">
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
    .lb-table thead th { background: #1b3152; color: #fff; font-weight: 600; font-size: 0.78rem; vertical-align: middle; border-color: #2a456f; }
    .lb-table td { vertical-align: middle; }
    .lb-status { font-size: 0.7rem; font-weight: 600; padding: 3px 8px; border-radius: 999px; display: inline-flex; align-items: center; gap: 4px; }
    .lb-status.ok { background: #e6f4ea; color: #1e7a3c; border: 1px solid #b7e0c3; }
    .lb-status.wait { background: #fff4d6; color: #8a6100; border: 1px solid #f3dd9a; }
    .lb-status.done { background: #e7f1fb; color: #1b5fa8; border: 1px solid #b9d4f1; }
    .lb-status.no { background: #fdeaea; color: #b02a2a; border: 1px solid #f3bcbc; }
    .lb-kembali-form { display: inline-flex; gap: 4px; align-items: center; }
    .lb-kembali-form input[type=date] { width: 122px; font-size: 0.75rem; height: 30px; }
    .lb-kembali-btn { height: 30px; padding: 0 12px; display: inline-flex; align-items: center; gap: 6px;
                      background: #1b3152; border: 1px solid #1b3152; color: #fff; border-radius: 6px;
                      font-size: 0.75rem; font-weight: 600; white-space: nowrap; transition: background .15s ease; }
    .lb-kembali-btn:hover, .lb-kembali-btn:focus { background: #14253e; color: #fff; }
    .lb-kembali-btn.koreksi { background: #fff; color: #1b3152; }
    .lb-kembali-btn.koreksi:hover, .lb-kembali-btn.koreksi:focus { background: #eef2f8; color: #1b3152; }
</style>

<div class="card border-0 shadow-sm mb-3">
    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle mb-0 lb-table" style="font-size:0.85rem;">
            <thead class="text-center">
                <tr>
                    <th style="width:50px;">No</th>
                    <th style="width:115px;">Tanggal</th>
                    <th style="width:250px;">Dikembalikan</th>
                    <th>Pekerjaan</th>
                    <th style="width:150px;">Petugas</th>
                    <th style="width:100px;">Paraf</th>
                    <th style="width:150px;">Status</th>
                    <th style="width:100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($baris as $i => $b)
                <tr class="{{ $b->status === 'ditolak' ? 'table-danger' : '' }}">
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-center">{{ $b->tanggal->format('d M Y') }}</td>
                    <td class="text-center">
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
                                        onclick="return confirm('{{ $bisaKoreksi ? 'Simpan koreksi tanggal dikembalikan?' : 'Catat alat ini sudah dikembalikan?' }}')">
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
                    </td>
                    <td>{{ $b->pekerjaan }}</td>
                    <td>{{ $b->petugas }}</td>
                    <td class="text-center">@if($b->paraf)<img src="{{ $b->paraf }}" alt="paraf" style="max-height:40px; max-width:90px;">@endif</td>
                    <td class="text-center">
                        @if($b->status === 'dikembalikan')
                            <span class="lb-status done"><i class="fas fa-box-open"></i> Dikembalikan</span>
                            <div class="small text-muted mt-1">Acc: {{ $b->penyetuju->personil->nama ?? $b->penyetuju->username ?? '' }}</div>
                        @elseif($b->status === 'disetujui')
                            <span class="lb-status ok"><i class="fas fa-check-circle"></i> Disetujui</span>
                            <div class="small text-muted mt-1">{{ $b->penyetuju->personil->nama ?? $b->penyetuju->username ?? '' }}</div>
                        @elseif($b->status === 'ditolak')
                            <span class="lb-status no"><i class="fas fa-times-circle"></i> Ditolak</span>
                            @if($b->catatan_approval)<div class="small text-muted mt-1">{{ $b->catatan_approval }}</div>@endif
                        @else
                            <span class="lb-status wait"><i class="fas fa-clock"></i> Menunggu</span>
                        @endif
                    </td>
                    <td class="text-center text-nowrap">
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
                            <form method="POST" action="{{ route('peminjaman.batal', $b->logbook_peminjaman_id) }}" onsubmit="return confirm('Tarik catatan ini?')">@csrf
                                <button class="btn btn-outline-secondary btn-sm lb-btn shadow-sm" title="Batalkan catatan" aria-label="Batalkan catatan"><i class="fas fa-undo"></i></button>
                            </form>
                        @elseif($b->status === 'ditolak' && $pengelola)
                            <form method="POST" action="{{ route('peminjaman.hapus', $b->logbook_peminjaman_id) }}" onsubmit="return confirm('Hapus catatan yang ditolak ini?')">@csrf
                                <button class="btn btn-outline-danger btn-sm lb-btn shadow-sm" title="Hapus catatan" aria-label="Hapus catatan"><i class="fas fa-trash"></i></button>
                            </form>
                        @else
                            <span class="text-muted">&ndash;</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada catatan peminjaman untuk alat ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($bisaMengisi)
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('peminjaman.store', $alat->alat_id) }}" id="formLogbook">
                @csrf
                <div class="modal-header" style="background:#1b3152; color:#fff;">
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
                                <canvas id="canvasParaf" width="480" height="140" style="display:block; width:480px; max-width:100%; height:140px; border:1px solid #adb5bd; border-radius:6px; background:#fff; touch-action:none;"></canvas>
                            </div>
                            <button type="button" id="hapusParaf" class="btn btn-outline-secondary btn-sm mt-1">Hapus paraf</button>
                            <input type="hidden" name="paraf" id="inputParaf">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm text-white fw-semibold px-4" style="background:#1b3152;">Simpan Catatan</button>
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
                <div class="modal-header" style="background:#1b3152; color:#fff;">
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
        if (!adaIsi) { e.preventDefault(); alert('Paraf wajib diisi. Gambar paraf di kotak yang tersedia.'); return; }
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