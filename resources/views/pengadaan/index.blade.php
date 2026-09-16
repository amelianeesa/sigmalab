@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <ol class="breadcrumb mb-1 mt-3 small">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('barang.index') }}" class="text-decoration-none">Inventori & Fasilitas</a></li>
                <li class="breadcrumb-item active">Pengadaan Bahan</li>
            </ol>
            <h4 class="fw-bold text-dark mb-1">Pengadaan Bahan / Barang</h4>
            <p class="text-muted mb-0 small">Manajemen permintaan pengadaan barang dengan alur approval berjenjang.</p>
        </div>
        <div class="d-flex gap-2">
            @if(in_array(Auth::user()->role->nama_role ?? '', [\App\Enums\PeranPengguna::GA_OFFICER->value, \App\Enums\PeranPengguna::ADMIN_APLIKASI->value, 'GA', 'GA_OFFICER']))
                <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#exportPdfModal">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </button>
            @endif
            @if(!in_array(Auth::user()->role->nama_role ?? '', [\App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value, \App\Enums\PeranPengguna::GA_OFFICER->value, 'GA', 'GA_OFFICER']))
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPengadaanModal">
                    <i class="fas fa-plus me-1"></i> Ajukan Pengadaan
                </button>
            @endif
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th width="4%">No</th>
                            <th>Nama Barang</th>
                            <th>Tanggal</th>
                            <th>Diajukan Oleh</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th width="16%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengadaans as $p)
                            @php
                                $roleUser = Auth::user()->role->nama_role ?? '';
                                $isKoor = in_array($roleUser, ['Koordinator Lab', 'Koordinator Laboratorium']);
                                $isGa = in_array($roleUser, [\App\Enums\PeranPengguna::GA_OFFICER->value, \App\Enums\PeranPengguna::ADMIN_APLIKASI->value, 'GA', 'GA_OFFICER']);
                                $isAdminAplikasi = $roleUser === \App\Enums\PeranPengguna::ADMIN_APLIKASI->value;

                                $catatan = $p->catatan_approval ?? '';
                                $labelPenolak = null;
                                $alasanText = $catatan;
                                if (str_contains($catatan, 'Alasan:')) {
                                    [$labelPenolak, $alasanText] = explode('. Alasan:', $catatan, 2);
                                    $alasanText = trim($alasanText);
                                }
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>

                                <td>
                                    <span class="fw-semibold">{{ $p->barang ? $p->barang->nama_barang : 'Barang Dihapus' }}</span><br>
                                    <span class="text-muted" style="font-size:0.75rem;">{{ $p->alasan ?? 'Tidak ada catatan khusus' }}</span>
                                    @if($p->foto)
                                        <div class="mt-1">
                                            <a href="{{ asset($p->foto) }}" target="_blank" class="badge bg-light text-primary border text-decoration-none" style="font-size:0.68rem;">
                                                <i class="fas fa-image me-1"></i>Foto
                                            </a>
                                        </div>
                                    @endif
                                </td>

                                <td class="text-nowrap">{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d M Y') }}</td>
                                <td>{{ $p->pemohon ? $p->pemohon->username : '-' }}</td>
                                <td class="text-center fw-bold text-primary">
                                    {{ (float) $p->jumlah_diminta }} <span class="text-muted fw-normal" style="font-size:0.72rem;">{{ $p->barang ? $p->barang->satuan : '' }}</span>
                                </td>

                                <td class="text-center" style="min-width:170px;">
                                    @if($p->status == 'menunggu_koordinator' || $p->status == 'diajukan')
                                        <span class="badge bg-secondary" style="font-size:0.7rem;">Menunggu Koordinator</span>
                                    @elseif($p->status == 'menunggu_ga')
                                        <span class="badge bg-warning text-dark" style="font-size:0.7rem;">Menunggu GA</span>
                                    @elseif($p->status == 'disetujui')
                                        <span class="badge bg-info text-dark" style="font-size:0.7rem;">Disetujui GA</span>
                                    @elseif($p->status == 'ditolak')
                                        <span class="badge bg-danger" style="font-size:0.7rem;">Ditolak</span>
                                        <div class="mt-1 p-1 rounded text-start" style="background:#fdeaea; border:1px solid #f5c2c2; font-size:0.72rem;">
                                            @if($labelPenolak)
                                                <div class="fw-bold text-danger">{{ $labelPenolak }}</div>
                                                <div class="text-danger">Alasan: {{ $alasanText }}</div>
                                            @else
                                                <div class="text-danger">{{ $catatan }}</div>
                                            @endif
                                        </div>
                                    @elseif($p->status == 'diproses')
                                        <span class="badge bg-primary" style="font-size:0.7rem;">Diproses (PO)</span>
                                    @elseif($p->status == 'selesai')
                                        <span class="badge bg-success" style="font-size:0.7rem;">Selesai (Stok Masuk)</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex flex-column gap-1">

                                        @if($isKoor && ($p->status == 'menunggu_koordinator' || $p->status == 'diajukan'))
                                            <div class="d-flex gap-1">
                                                <form action="{{ route('pengadaan.approve', $p->permintaan_id) }}" method="POST" class="w-50">
                                                    @csrf
                                                    <input type="hidden" name="status" value="menunggu_ga">
                                                    <button class="btn btn-sm btn-success w-100 py-1" style="font-size:0.72rem;"><i class="fas fa-check"></i> Setujui</button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-danger w-50 py-1" style="font-size:0.72rem;" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $p->permintaan_id }}">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </div>
                                        @endif

                                        @if($isGa && $p->status == 'menunggu_ga')
                                            <div class="d-flex gap-1">
                                                <form action="{{ route('pengadaan.approve', $p->permintaan_id) }}" method="POST" class="w-50">
                                                    @csrf
                                                    <input type="hidden" name="status" value="disetujui">
                                                    <button class="btn btn-sm btn-success w-100 py-1" style="font-size:0.72rem;"><i class="fas fa-check"></i> Setujui</button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-danger w-50 py-1" style="font-size:0.72rem;" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $p->permintaan_id }}">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </div>
                                        @endif

                                        @if($isGa && $p->status == 'disetujui')
                                            <form action="{{ route('pengadaan.approve', $p->permintaan_id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="diproses">
                                                <button class="btn btn-sm btn-primary w-100 py-1" style="font-size:0.72rem;"><i class="fas fa-spinner"></i> Proses (PO)</button>
                                            </form>
                                        @endif

                                        <div class="modal fade" id="modalTolak{{ $p->permintaan_id }}" tabindex="-1">
                                            <div class="modal-dialog modal-sm">
                                                <form action="{{ route('pengadaan.approve', $p->permintaan_id) }}" method="POST" class="modal-content text-start">
                                                    @csrf
                                                    <input type="hidden" name="status" value="ditolak">
                                                    <div class="modal-header bg-danger text-white py-2">
                                                        <h6 class="modal-title mb-0"><i class="fas fa-times-circle me-1"></i>Penolakan Pengadaan</h6>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body py-2">
                                                        <label class="form-label fw-bold small">Alasan Penolakan <span class="text-danger">*</span></label>
                                                        <textarea name="catatan_approval" class="form-control form-control-sm" rows="3" required placeholder="Masukkan alasan penolakan..."></textarea>
                                                    </div>
                                                    <div class="modal-footer bg-light py-2">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger btn-sm">Kirim</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        @if($p->status == 'selesai' || $p->foto_diterima)
                                            <div class="p-1 border rounded bg-light text-start" style="font-size:0.72rem;">
                                                <span class="fw-bold text-dark">Penerima: {{ $p->nama_penerima ?? '-' }}</span><br>
                                                @if($p->foto_diterima)
                                                    <a href="{{ asset($p->foto_diterima) }}" target="_blank" class="btn btn-sm btn-info mt-1 text-white py-0 px-2 w-100" style="font-size:0.68rem;">
                                                        <i class="fas fa-image"></i> Lihat Bukti
                                                    </a>
                                                @endif
                                            </div>
                                        @elseif($p->status == 'disetujui' || $p->status == 'diproses')
                                            <button type="button" class="btn btn-sm btn-warning fw-bold w-100 py-1" data-bs-toggle="modal" data-bs-target="#modalTerima{{ $p->permintaan_id }}" style="font-size:0.72rem;">
                                                <i class="fas fa-camera"></i> Konfirmasi Terima
                                            </button>

                                            <div class="modal fade" id="modalTerima{{ $p->permintaan_id }}" tabindex="-1">
                                                <div class="modal-dialog modal-sm">
                                                    <form action="{{ route('pengadaan.konfirmasiTerima', $p->permintaan_id) }}" method="POST" enctype="multipart/form-data" class="modal-content text-start">
                                                        @csrf
                                                        <div class="modal-header bg-dark text-white py-2">
                                                            <h6 class="modal-title mb-0"><i class="fas fa-box-open me-1"></i>Konfirmasi Diterima</h6>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body py-2">
                                                            <div class="mb-2">
                                                                <label class="form-label fw-bold small">Nama Penerima <span class="text-danger">*</span></label>
                                                                <input type="text" name="nama_penerima" class="form-control form-control-sm" required>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="form-label fw-bold small">Bukti Foto <span class="text-danger">*</span></label>
                                                                <input type="file" name="foto_diterima" class="form-control form-control-sm" accept="image/*" capture="environment" required>
                                                            </div>
                                                            <div class="mb-1">
                                                                <label class="form-label fw-bold small">Tgl Expired (Opsional)</label>
                                                                <input type="date" name="tgl_exp" class="form-control form-control-sm">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light py-2">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif

                                        @if(Auth::id() == $p->diajukan_oleh && in_array($p->status, ['diajukan', 'menunggu_koordinator']))
                                            <form action="{{ route('pengadaan.destroy', $p->permintaan_id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger w-100 py-1" style="font-size:0.72rem;" onclick="return confirm('Batalkan pengajuan?')"><i class="fas fa-trash"></i> Batalkan</button>
                                            </form>
                                        @endif

                                        @if($isAdminAplikasi)
                                            <form action="{{ route('pengadaan.destroy', $p->permintaan_id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger w-100 py-1" style="font-size:0.72rem;" onclick="return confirm('Yakin hapus permanen data pengadaan ini? Tindakan ini tidak bisa dibatalkan.')"><i class="fas fa-trash-alt"></i> Hapus</button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat pengajuan pengadaan barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambahPengadaanModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('pengadaan.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header py-2">
                <h6 class="modal-title mb-0">Form Pengajuan Pengadaan Bahan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Pilih Barang/Bahan <span class="text-danger">*</span></label>
                    <select name="barang_id" class="form-select form-select-sm" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barangList as $b)
                            @php
                                $saldoAkhir = ($b->saldo_awal + $b->penerimaan) - $b->pengeluaran;
                            @endphp
                            <option value="{{ $b->barang_id }}">
                                {{ $b->nama_barang }} (Stok saat ini: {{ $saldoAkhir }} {{ $b->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Jumlah Diminta <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0.1" name="jumlah_diminta" class="form-control form-control-sm" required placeholder="Contoh: 100">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Catatan Kebutuhan / Spesifikasi Barang</label>
                    <textarea name="alasan" class="form-control form-control-sm" rows="3" placeholder="Contoh: Stok untuk reagen menipis untuk pengujian air limbah..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Upload Foto / Referensi Barang <small class="text-muted">(Opsional)</small></label>
                    <input type="file" name="foto" class="form-control form-control-sm" accept="image/*">
                </div>
                <div class="alert alert-info py-2 mb-0 mt-3 small">
                    <i class="fas fa-info-circle me-1"></i> Pengajuan oleh Analis akan diverifikasi oleh Koordinator Lab terlebih dahulu sebelum diteruskan ke GA.
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-paper-plane me-1"></i> Ajukan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="exportPdfModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form action="{{ route('pengadaan.exportPdf') }}" method="GET" target="_blank" class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title mb-0">Export Laporan Pengadaan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Bulan</label>
                    <select name="bulan" class="form-select form-select-sm" required>
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ date('m') == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Tahun</label>
                    <select name="tahun" class="form-select form-select-sm" required>
                        @for($i=date('Y'); $i>=2020; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-download me-1"></i> Download PDF</button>
            </div>
        </form>
    </div>
</div>
@endsection