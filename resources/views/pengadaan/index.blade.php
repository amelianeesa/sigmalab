@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Pengadaan Bahan / Barang</h2>
            <p class="text-muted mb-0">Manajemen permintaan pengadaan barang dan persetujuan Kabid Dukungan Bisnis.</p>
            <ol class="breadcrumb mb-0 mt-2">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('barang.index') }}" class="text-decoration-none">Inventori & Fasilitas</a></li>
                <li class="breadcrumb-item active">Pengadaan Bahan</li>
            </ol>
        </div>
        <div class="d-flex gap-2">
            @if(Auth::user()->role->nama_role === \App\Enums\PeranPengguna::HR_GA_OFFICER->value)
                <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exportPdfModal">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </button>
            @endif
            @if(Auth::user()->role->nama_role !== \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value && Auth::user()->role->nama_role !== \App\Enums\PeranPengguna::HR_GA_OFFICER->value)
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPengadaanModal">
                    <i class="fas fa-plus me-1"></i> Ajukan Pengadaan
                </button>
            @endif
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th width="5%" class="text-center">No</th>
                            <th>Nama Barang</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Diajukan Oleh</th>
                            <th>Jumlah Diminta</th>
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
                                    @if($p->foto)
                                        <div class="mt-1">
                                            <a href="{{ asset($p->foto) }}" target="_blank" class="badge bg-light text-primary border text-decoration-none">
                                                <i class="fas fa-image me-1"></i> Lihat Foto
                                            </a>
                                        </div>
                                    @endif
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
                                    <div class="d-flex flex-column gap-2">
                                        @php
                                            $roleName = Auth::user()->role->nama_role ?? '';
                                            $isHrGa = in_array($roleName, [\App\Enums\PeranPengguna::HR_GA_OFFICER->value, \App\Enums\PeranPengguna::ADMIN_APLIKASI->value]);
                                        @endphp
                                    
                                        @if($isHrGa && $p->status != 'selesai' && $p->status != 'ditolak')
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Aksi HR & GA
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
                                                                <input type="hidden" name="status" value="disetujui">
                                                                <button class="dropdown-item text-info"><i class="fas fa-undo me-2"></i>Kembalikan ke Disetujui</button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif

                                        <!-- Bagian Tombol Konfirmasi Diterima / Lihat Bukti -->
                                        @if($p->status == 'selesai' || $p->foto_diterima)
                                            <div class="p-2 border rounded bg-light">
                                                <span class="badge bg-success mb-1">Selesai</span><br>
                                                <small class="fw-bold text-dark">Penerima: {{ $p->nama_penerima ?? '-' }}</small><br>
                                                <a href="{{ asset($p->foto_diterima) }}" target="_blank" class="btn btn-sm btn-info mt-1 text-decoration-none text-white py-0 px-2 w-100" style="font-size: 0.75rem;">
                                                    <i class="fas fa-image"></i> Lihat Bukti
                                                </a>
                                                <div class="text-muted mt-1" style="font-size: 10px;">
                                                    <i class="far fa-clock"></i> {{ $p->waktu_diterima ? \Carbon\Carbon::parse($p->waktu_diterima)->format('d/m/Y H:i') : '-' }}
                                                </div>
                                            </div>
                                        @elseif($p->status == 'disetujui' || $p->status == 'diproses')
                                            <button type="button" class="btn btn-sm btn-warning fw-bold w-100 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTerima{{ $p->permintaan_id }}" style="font-size: 0.75rem;">
                                                <i class="fas fa-camera me-1"></i> Konfirmasi Diterima
                                            </button>

                                            <!-- Modal Konfirmasi Terima, Nama Penerima & Tanggal Expired -->
                                            <div class="modal fade" id="modalTerima{{ $p->permintaan_id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <form action="{{ route('pengadaan.terima', $p->permintaan_id) }}" method="POST" enctype="multipart/form-data" class="modal-content text-start">
                                                        @csrf
                                                        <div class="modal-header bg-dark text-white">
                                                            <h5 class="modal-title fs-6"><i class="fas fa-box-open me-2"></i>Konfirmasi Barang Diterima</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Nama Penerima Barang <span class="text-danger">*</span></label>
                                                                <input type="text" name="nama_penerima" class="form-control" required placeholder="Masukkan nama lengkap penerima...">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Bukti Foto Penerimaan <span class="text-danger">*</span></label>
                                                                <input type="file" name="foto_diterima" class="form-control" accept="image/*" capture="environment" required>
                                                                <small class="text-muted">Format yang didukung: JPG, JPEG, PNG, WEBP (Maksimal 2MB)</small>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Tanggal Expired Barang Baru <span class="text-danger">*</span></label>
                                                                <input type="date" name="tgl_exp" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> Simpan & Update Stok</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif

                                        @if(!$isHrGa && $p->status == 'diajukan')
                                            <div>
                                                <span class="text-muted small">Menunggu HR & GA</span>
                                                <form action="{{ route('pengadaan.destroy', $p->permintaan_id) }}" method="POST" class="d-inline ms-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Batalkan pengajuan?')"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        @elseif(!$isHrGa && $p->status != 'diajukan' && $p->status != 'disetujui' && $p->status != 'diproses')
                                            <div>
                                                <span class="text-muted small">
                                                    Tgl: {{ $p->tanggal_keputusan ? \Carbon\Carbon::parse($p->tanggal_keputusan)->format('d/m/Y') : '-' }}<br>
                                                    Oleh: {{ $p->penyetuju ? $p->penyetuju->username : '-' }}
                                                </span>
                                            </div>
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

<!-- Modal Tambah Pengajuan -->
<div class="modal fade" id="tambahPengadaanModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('pengadaan.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
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
                            @endphp
                            <option value="{{ $b->barang_id }}">
                                {{ $b->nama_barang }} (Stok saat ini: {{ $saldoAkhir }} {{ $b->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Jumlah Diminta <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0.1" name="jumlah_diminta" class="form-control" required placeholder="Contoh: 100">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Catatan Kebutuhan / Spesifikasi Barang</label>
                    <textarea name="alasan" class="form-control" rows="3" placeholder="Contoh: Stok untuk reagen menipis untuk pengujian air limbah..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Upload Foto / Referensi Barang <small class="text-muted">(Opsional)</small></label>
                    <input type="file" name="foto" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp" capture="environment">
                    <div class="form-text">Format yang didukung: JPG, JPEG, PNG, WEBP (Maksimal 2MB)</div>
                </div>
                <div class="alert alert-info py-2 mb-0 mt-3" style="font-size:0.85rem;">
                    <i class="fas fa-info-circle me-1"></i> Pengajuan ini akan langsung diteruskan ke HR & GA Officer untuk di-*approve*.
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Ajukan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Export PDF -->
<div class="modal fade" id="exportPdfModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('pengadaan.pdf') }}" method="GET" target="_blank" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Laporan Pengadaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Bulan</label>
                    <select name="bulan" class="form-select" required>
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ date('m') == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Tahun</label>
                    <select name="tahun" class="form-select" required>
                        @for($i=date('Y'); $i>=2020; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-download me-1"></i> Download PDF</button>
            </div>
        </form>
    </div>
</div>
@endsection