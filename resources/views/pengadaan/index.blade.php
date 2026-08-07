@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Pengadaan Bahan / Barang</h2>
            <p class="text-muted mb-0">Manajemen permintaan pengadaan barang dan persetujuan Kabid Dukungan Bisnis.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPengadaanModal">
            <i class="fas fa-plus me-1"></i> Ajukan Pengadaan
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Nama Barang</th>
                            <th>Tgl Pengajuan</th>
                            <th>Diajukan Oleh</th>
                            <th class="text-center">Jml Diminta</th>
                            <th>Status</th>
                            <th>Persetujuan (Aksi)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengadaans as $p)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="fw-bold">{{ $p->barang ? $p->barang->nama_barang : 'Barang Dihapus' }}</span><br>
                                    <small class="text-muted">{{ $p->alasan ?? 'Tidak ada alasan khusus' }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d M Y') }}</td>
                                <td>{{ $p->pemohon ? $p->pemohon->username : '-' }}</td>
                                <td class="text-center fw-bold fs-5 text-primary">
                                    {{ (float) $p->jumlah_diminta }} <small class="text-muted fs-6">{{ $p->barang ? $p->barang->satuan : '' }}</small>
                                </td>
                                <td>
                                    @if($p->status == 'diajukan')
                                        <span class="badge bg-secondary p-2">Menunggu Approval</span>
                                    @elseif($p->status == 'disetujui')
                                        <span class="badge bg-info text-dark p-2">Disetujui</span>
                                    @elseif($p->status == 'ditolak')
                                        <span class="badge bg-danger p-2">Ditolak</span>
                                    @elseif($p->status == 'diproses')
                                        <span class="badge bg-warning text-dark p-2">Diproses (PO)</span>
                                    @elseif($p->status == 'selesai')
                                        <span class="badge bg-success p-2">Selesai (Stok Masuk)</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $roleName = Auth::user()->role->nama_role ?? '';
                                        $isKabid = in_array($roleName, [\App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value, \App\Enums\PeranPengguna::ADMIN_APLIKASI->value]);
                                    @endphp
                                    
                                    @if($isKabid && $p->status != 'selesai' && $p->status != 'ditolak')
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Aksi Kabid
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if($p->status == 'diajukan')
                                                <li>
                                                    <form action="{{ route('pengadaan.approve', $p->permintaan_id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="disetujui">
                                                        <button class="dropdown-item text-info"><i class="fas fa-check me-2"></i>Setujui</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('pengadaan.approve', $p->permintaan_id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="ditolak">
                                                        <button class="dropdown-item text-danger"><i class="fas fa-times me-2"></i>Tolak</button>
                                                    </form>
                                                </li>
                                            @elseif($p->status == 'disetujui')
                                                <li>
                                                    <form action="{{ route('pengadaan.approve', $p->permintaan_id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="diproses">
                                                        <button class="dropdown-item text-warning"><i class="fas fa-spinner me-2"></i>Ubah jadi Diproses</button>
                                                    </form>
                                                </li>
                                            @elseif($p->status == 'diproses')
                                                <li>
                                                    <form action="{{ route('pengadaan.approve', $p->permintaan_id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="selesai">
                                                        <button class="dropdown-item text-success" onclick="return confirm('Menandai selesai akan OTOMATIS menambah stok barang. Lanjutkan?')"><i class="fas fa-box-open me-2"></i>Barang Diterima (Selesai)</button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    @else
                                        @if($p->status == 'diajukan')
                                            <span class="text-muted small">Menunggu Kabid</span>
                                            <form action="{{ route('pengadaan.destroy', $p->permintaan_id) }}" method="POST" class="d-inline ms-1">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Batalkan pengajuan?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @else
                                            <span class="text-muted small">
                                                Tgl: {{ $p->tanggal_keputusan ? \Carbon\Carbon::parse($p->tanggal_keputusan)->format('d/m/Y') : '-' }}<br>
                                                Oleh: {{ $p->penyetuju ? $p->penyetuju->username : '-' }}
                                            </span>
                                        @endif
                                    @endif
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

<!-- Modal Tambah Pengajuan -->
<div class="modal fade" id="tambahPengadaanModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('pengadaan.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Form Pengajuan Pengadaan Bahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Barang/Bahan <span class="text-danger">*</span></label>
                    <select name="barang_id" class="form-select" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barangList as $b)
                            @php
                                $saldoAkhir = ($b->saldo_awal + $b->penerimaan) - $b->pengeluaran;
                                $isMenipis = $saldoAkhir <= $b->minimal_stok;
                            @endphp
                            <option value="{{ $b->barang_id }}">
                                {{ $b->nama_barang }} (Stok saat ini: {{ $saldoAkhir }} {{ $b->satuan }}) {{ $isMenipis ? '⚠️' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Jumlah Diminta <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0.1" name="jumlah_diminta" class="form-control" required placeholder="Contoh: 100">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Alasan / Catatan Kebutuhan</label>
                    <textarea name="alasan" class="form-control" rows="3" placeholder="Contoh: Stok untuk reagen menipis untuk pengujian air limbah..."></textarea>
                </div>
                <div class="alert alert-info py-2 mb-0 mt-3" style="font-size:0.85rem;">
                    <i class="fas fa-info-circle me-1"></i> Pengajuan ini akan langsung diteruskan ke Kabid Dukungan Bisnis untuk di-*approve*.
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Ajukan</button>
            </div>
        </form>
    </div>
</div>
@endsection
