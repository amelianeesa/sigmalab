@extends('layouts.app')

@section('content')
<div class="container-fluid pt-0 pb-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <div>
            <h5 class="fw-bold text-dark mb-1" style="font-size: 1.2rem;">Pengadaan Bahan / Barang</h5>
            <ol class="breadcrumb mb-0" style="font-size: 12px;">
                <li class="breadcrumb-item"><a href="{{ route('barang.index') }}" class="text-decoration-none">Inventori Barang & Bahan</a></li>
                <li class="breadcrumb-item text-muted active">Pengadaan Barang & Bahan</li>
            </ol>
        </div>
        <div class="d-flex gap-2">
            @if(in_array(Auth::user()->role->nama_role ?? '', [\App\Enums\PeranPengguna::GA_OFFICER->value, \App\Enums\PeranPengguna::ADMIN_APLIKASI->value, 'GA', 'GA_OFFICER']))
                <button class="btn btn-sm btn-outline-success py-1 px-2 fw-bold" data-bs-toggle="modal" data-bs-target="#exportPdfModal" style="font-size: 0.72rem;">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </button>
            @endif
            @if(!in_array(Auth::user()->role->nama_role ?? '', [\App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value, \App\Enums\PeranPengguna::GA_OFFICER->value, 'GA', 'GA_OFFICER']))
                <button class="btn btn-sm text-white py-1 px-2 fw-bold" data-bs-toggle="modal" data-bs-target="#tambahPengadaanModal" style="font-size: 0.72rem; background-color: #1b3152;">
                    <i class="fas fa-plus me-1"></i> Ajukan Pengadaan
                </button>
            @endif
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle mb-0" style="font-size: 0.75rem; border-color: #dee2e6;">
                <thead class="text-white">
                    <tr class="text-center" style="background-color: #1b3152 !important;">
                        <th width="4%" class="py-2 text-white" style="background-color: #1b3152 !important; border-right: 1px solid #ffffff !important;">No</th>
                        <th class="py-2 text-white" style="background-color: #1b3152 !important; border-right: 1px solid #ffffff !important;">Nama Barang</th>
                        <th class="py-2 text-white" style="background-color: #1b3152 !important; border-right: 1px solid #ffffff !important;">Tanggal</th>
                        <th class="py-2 text-white" style="background-color: #1b3152 !important; border-right: 1px solid #ffffff !important;">Target Waktu</th>
                        <th class="py-2 text-white" style="background-color: #1b3152 !important; border-right: 1px solid #ffffff !important;">Diajukan Oleh</th>
                        <th class="py-2 text-white" style="background-color: #1b3152 !important; border-right: 1px solid #ffffff !important;">Jumlah</th>
                        <th class="py-2 text-white" style="background-color: #1b3152 !important; border-right: 1px solid #ffffff !important;">Status</th>
                        <th width="16%" class="py-2 text-white" style="background-color: #1b3152 !important;">Aksi</th>
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
                            <!-- 1. No -->
                            <td class="text-center">{{ $loop->iteration }}</td>
                
                            <!-- 2. Nama Barang & Foto -->
                            <td>
                                <span class="fw-semibold">{{ $p->barang ? $p->barang->nama_barang : 'Barang Dihapus' }}</span><br>
                                <span class="text-muted" style="font-size:0.7rem;">{{ $p->alasan ?? 'Tidak ada catatan khusus' }}</span>
                                @if($p->foto)
                                <div class="mt-1">
                                    <a href="{{ asset($p->foto) }}" target="_blank" class="badge bg-light text-primary border text-decoration-none" style="font-size:0.65rem;">
                                        <i class="fas fa-image me-1"></i>Foto
                                    </a>
                                </div>
                                @endif
                            </td>
                
                            <!-- 3. Tanggal Pengajuan -->
                            <td class="text-nowrap text-center">{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d M Y') }}</td>
                
                            <!-- 4. Target Waktu -->
                            <td class="text-center">
                                <span class="text-dark small fw-medium">{{ $p->format_target_waktu }}</span>
                            </td>
                
                            <!-- 5. Diajukan Oleh -->
                            <td class="text-center">{{ $p->pemohon ? $p->pemohon->username : '-' }}</td>
                
                            <!-- 6. Jumlah -->
                            <td class="text-center fw-bold text-primary">
                                {{ (float) $p->jumlah_diminta }} <span class="text-muted fw-normal" style="font-size:0.7rem;">{{ $p->barang ? $p->barang->satuan : '' }}</span>
                            </td>
                
                            <!-- 7. Status -->
                            <td class="text-center" style="min-width:170px;">
                                @if($p->status == 'menunggu_koordinator' || $p->status == 'diajukan')
                                    <span class="badge bg-secondary" style="font-size:0.68rem;">Menunggu Koordinator</span>
                                @elseif($p->status == 'menunggu_ga')
                                    <span class="badge bg-warning text-dark" style="font-size:0.68rem;">Menunggu GA</span>
                                @elseif($p->status == 'disetujui')
                                    <span class="badge bg-info text-dark" style="font-size:0.68rem;">Disetujui GA</span>
                                @elseif($p->status == 'ditolak')
                                    <span class="badge bg-danger" style="font-size:0.68rem;">Ditolak</span>
                                    <div class="mt-1 p-1 rounded text-start" style="background:#fdeaea; border:1px solid #f5c2c2; font-size:0.7rem;">
                                        @if($labelPenolak)
                                            <div class="fw-bold text-danger">{{ $labelPenolak }}</div>
                                            <div class="text-danger">Alasan: {{ $alasanText }}</div>
                                        @else
                                            <div class="text-danger">{{ $catatan }}</div>
                                        @endif
                                    </div>
                                @elseif($p->status == 'batal')
                                    <span class="badge bg-dark text-white" style="font-size:0.68rem;">Dibatalkan</span>
                                    <div class="mt-1 p-1 rounded text-start" style="background:#f1f1f1; border:1px solid #dcdcdc; color:#333; font-size:0.7rem;">
                                        @if($labelPenolak)
                                            <div class="fw-bold text-dark">{{ $labelPenolak }}</div>
                                            <div class="text-muted">Alasan: {{ $alasanText }}</div>
                                        @else
                                            <div class="text-dark">{{ $catatan }}</div>
                                        @endif
                                    </div>
                                @elseif($p->status == 'diproses_po' || $p->status == 'diproses')
                                    <span class="badge bg-primary" style="font-size:0.68rem;">Diproses</span>
                                    @if($p->catatan_po)
                                        <div class="mt-1 p-1 rounded text-start" style="background:#fff3cd; border:1px solid #ffeeba; color:#856404; font-size:0.7rem;">
                                            <i class="fas fa-info-circle me-1"></i> <b>Catatan:</b> {{ $p->catatan_po }}
                                        </div>
                                    @endif
                                @elseif($p->status == 'pembelian')
                                    <span class="badge bg-info text-dark" style="font-size:0.68rem;">Pembelian Langsung</span>
                                    @if($p->catatan_po)
                                        <div class="mt-1 p-1 rounded text-start" style="background:#fff3cd; border:1px solid #ffeeba; color:#856404; font-size:0.7rem;">
                                            <i class="fas fa-info-circle me-1"></i> <b>Catatan:</b> {{ $p->catatan_po }}
                                        </div>
                                    @endif
                                @elseif($p->status == 'selesai')
                                    <span class="badge bg-success" style="font-size:0.68rem;">Selesai (Stok Masuk)</span>
                                @endif
                            </td>
                
                            <!-- 8. Aksi -->
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @if($isKoor && ($p->status == 'menunggu_koordinator' || $p->status == 'diajukan'))
                                        <div class="d-flex gap-1">
                                            <form action="{{ route('pengadaan.approve', $p->permintaan_id) }}" method="POST" class="w-50">
                                                @csrf
                                                <input type="hidden" name="status" value="menunggu_ga">
                                                <button class="btn btn-sm btn-success w-100 py-1" style="font-size:0.7rem;"><i class="fas fa-check"></i> Setujui</button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger w-50 py-1" style="font-size:0.7rem;" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $p->permintaan_id }}">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                        </div>
                                    @endif
                
                                    @if($isGa && $p->status == 'menunggu_ga')
                                        <div class="d-flex gap-1">
                                            <form action="{{ route('pengadaan.approve', $p->permintaan_id) }}" method="POST" class="w-50">
                                                @csrf
                                                <input type="hidden" name="status" value="disetujui">
                                                <button class="btn btn-sm btn-success w-100 py-1" style="font-size:0.7rem;"><i class="fas fa-check"></i> Setujui</button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger w-50 py-1" style="font-size:0.7rem;" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $p->permintaan_id }}">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                        </div>
                                    @endif

                                    @if($isGa && in_array($p->status, ['diproses', 'diproses_po', 'pembelian']))
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle w-100 py-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size:0.7rem;">
                                                Aksi
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm small">
                                                <li>
                                                    <button type="button" class="dropdown-item py-1 text-warning fw-semibold" data-bs-toggle="modal" data-bs-target="#updateProsesModal{{ $p->permintaan_id }}">
                                                        <i class="fas fa-edit me-2"></i> Update Progres
                                                    </button>
                                                </li>
                                                <li><hr class="dropdown-divider my-1"></li>
                                                <li>
                                                    <button type="button" class="dropdown-item py-1 text-danger fw-semibold" data-bs-toggle="modal" data-bs-target="#modalBatalGa{{ $p->permintaan_id }}">
                                                        <i class="fas fa-ban me-2"></i> Batalkan Proses
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                    
                                    @if($isGa && $p->status == 'disetujui')
                                        <button type="button" class="btn btn-sm btn-primary w-100 py-1 text-white" data-bs-toggle="modal" data-bs-target="#modalMetodeGa{{ $p->permintaan_id }}" style="font-size:0.7rem; background-color: #1b3152;">
                                            <i class="fas fa-tasks me-1"></i> Proses Pengadaan
                                        </button>
                                    
                                        <div class="modal fade" id="modalMetodeGa{{ $p->permintaan_id }}" tabindex="-1">
                                            <div class="modal-dialog modal-sm">
                                                <form action="{{ route('pengadaan.approve', $p->permintaan_id) }}" method="POST" class="modal-content text-start">
                                                    @csrf
                                                    <input type="hidden" name="status" value="diproses">
                                                    <div class="modal-header text-white py-2" style="background-color: #1b3152;">
                                                        <h6 class="modal-title mb-0 fs-6"><i class="fas fa-shopping-cart me-1"></i>Pilih Metode Pengadaan</h6>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body py-2">
                                                        <div class="mb-2">
                                                            <label class="form-label fw-bold small">Metode Penanganan <span class="text-danger">*</span></label>
                                                            <select name="metode_proses" class="form-select form-select-sm" id="selectMetode{{ $p->permintaan_id }}" required onchange="toggleEstimasi(this, '{{ $p->permintaan_id }}')">
                                                                <option value="">-- Pilih Metode --</option>
                                                                <option value="PO">Purchase Order (PO)</option>
                                                                <option value="Pembelian">Pembelian Langsung</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-2" id="divEstimasi{{ $p->permintaan_id }}">
                                                            <label class="form-label fw-bold small" id="labelEstimasi{{ $p->permintaan_id }}">Catatan / Estimasi</label>
                                                            <textarea name="catatan_po" class="form-control form-control-sm" rows="2" placeholder="Contoh: Estimasi tiba 3 hari..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light py-2">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-sm text-white" style="background-color: #1b3152;">Simpan & Proses</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    
                                        <script>
                                            function toggleEstimasi(selectObj, id) {
                                                const label = document.getElementById('labelEstimasi' + id);
                                                if (selectObj.value === 'PO') {
                                                    label.innerText = 'Estimasi PO (Hari / Keterangan)';
                                                } else if (selectObj.value === 'Pembelian') {
                                                    label.innerText = 'Estimasi Pembelian Langsung';
                                                } else {
                                                    label.innerText = 'Catatan / Estimasi';
                                                }
                                            }
                                        </script>
                                    @endif            
                        
                                    <div class="modal fade" id="modalTolak{{ $p->permintaan_id }}" tabindex="-1">
                                        <div class="modal-dialog modal-sm">
                                            <form action="{{ route('pengadaan.approve', $p->permintaan_id) }}" method="POST" class="modal-content text-start">
                                                @csrf
                                                <input type="hidden" name="status" value="ditolak">
                                                <div class="modal-header bg-danger text-white py-2">
                                                    <h6 class="modal-title mb-0 fs-6"><i class="fas fa-times-circle me-1"></i>Penolakan Pengadaan</h6>
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
                                    <div class="p-1 border rounded bg-light text-start" style="font-size:0.7rem;">
                                        <span class="fw-bold text-dark">Penerima: {{ $p->nama_penerima ?? '-' }}</span><br>
                                        @if($p->waktu_diterima)
                                            <span class="text-muted" style="font-size:0.65rem;">
                                                <i class="fas fa-clock me-1 text-secondary"></i>{{ \Carbon\Carbon::parse($p->waktu_diterima)->format('d M Y, H:i') }}
                                            </span><br>
                                        @endif
                                        @if($p->foto_diterima)
                                            <a href="{{ asset($p->foto_diterima) }}" target="_blank" class="btn btn-sm btn-info mt-1 text-white py-0 px-2 w-100" style="font-size:0.65rem;">
                                                <i class="fas fa-image"></i> Lihat Bukti
                                            </a>
                                        @endif
                                    </div>
                                    @elseif($p->status == 'disetujui' || $p->status == 'diproses')
                                        <button type="button" class="btn btn-sm btn-warning fw-bold w-100 py-1" data-bs-toggle="modal" data-bs-target="#modalTerima{{ $p->permintaan_id }}" style="font-size:0.7rem;">
                                            <i class="fas fa-camera"></i> Konfirmasi Terima
                                        </button>
                                    
                                        <div class="modal fade" id="modalTerima{{ $p->permintaan_id }}" tabindex="-1">
                                            <div class="modal-dialog modal-sm">
                                                <form action="{{ route('pengadaan.konfirmasiTerima', $p->permintaan_id) }}" method="POST" enctype="multipart/form-data" class="modal-content text-start">
                                                    @csrf
                                                    <div class="modal-header text-white py-2" style="background-color: #1b3152;">
                                                        <h6 class="modal-title mb-0 fs-6"><i class="fas fa-box-open me-1"></i>Konfirmasi Diterima</h6>
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
                                                        <button type="submit" class="btn btn-sm text-white" style="background-color: #1b3152;">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                        
                                    @if(Auth::id() == $p->diajukan_oleh && in_array($p->status, ['diajukan', 'menunggu_koordinator']))
                                        <form action="{{ route('pengadaan.destroy', $p->permintaan_id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger w-100 py-1" style="font-size:0.7rem;" onclick="return confirm('Batalkan pengajuan?')"><i class="fas fa-trash"></i> Batalkan</button>
                                        </form>
                                    @endif
                        
                                    @if($isAdminAplikasi)
                                        <form action="{{ route('pengadaan.destroy', $p->permintaan_id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger w-100 py-1" style="font-size:0.7rem;" onclick="return confirm('Yakin hapus permanen data pengadaan ini?')"><i class="fas fa-trash-alt"></i> Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada riwayat pengajuan pengadaan barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Update Progres -->
@foreach($pengadaans as $p)
@if(in_array($p->status, ['diproses', 'diproses_po', 'pembelian']))
<div class="modal fade" id="updateProsesModal{{ $p->permintaan_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <form action="{{ route('pengadaan.update-progres', $p->permintaan_id) }}" method="POST" class="modal-content text-start">
            @csrf
            @method('PUT')
            <div class="modal-header text-dark py-2" style="background-color: #ffc107;">
                <h6 class="modal-title mb-0 fs-6"><i class="fas fa-edit me-1"></i>Update Catatan Progres</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <div class="mb-2">
                    <label class="form-label fw-bold small">Catatan / Keterangan:</label>
                    <textarea name="catatan_po" class="form-control form-control-sm" rows="3" required>{{ $p->catatan_po }}</textarea>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm text-white" style="background-color: #1b3152;">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endif
@endforeach

@foreach($pengadaans as $p)
@if(in_array($p->status, ['diproses', 'diproses_po', 'pembelian']))
<div class="modal fade" id="modalBatalGa{{ $p->permintaan_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <form action="{{ route('pengadaan.batal-progres', $p->permintaan_id) }}" method="POST" class="modal-content text-start">
            @csrf
            @method('PUT')
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title mb-0 fs-6"><i class="fas fa-ban me-1"></i>Batalkan Pengadaan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-2">
                <div class="mb-2">
                    <label class="form-label fw-bold small">Alasan Pembatalan <span class="text-danger">*</span></label>
                    <textarea name="alasan_batal" class="form-control form-control-sm" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-danger btn-sm">Ya, Batalkan</button>
            </div>
        </form>
    </div>
</div>
@endif
@endforeach

<div class="modal fade" id="tambahPengadaanModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('pengadaan.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header text-white py-2" style="background-color: #1b3152;">
                <h6 class="modal-title mb-0 fs-6">Form Pengajuan Pengadaan Bahan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                    <label class="form-label fw-bold small">Target Batas Waktu Pengadaan <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        <div class="col-4">
                            <div class="input-group input-group-sm">
                                <input type="number" name="target_tahun" class="form-control" min="0" placeholder="0">
                                <span class="input-group-text">Thn</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="input-group input-group-sm">
                                <input type="number" name="target_bulan" class="form-control" min="0" max="12" placeholder="0">
                                <span class="input-group-text">Bln</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="input-group input-group-sm">
                                <input type="number" name="target_hari" class="form-control" min="0" max="31" placeholder="0">
                                <span class="input-group-text">Hari</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Catatan Kebutuhan / Spesifikasi Barang</label>
                    <textarea name="alasan" class="form-control form-control-sm" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Upload Foto / Referensi Barang <small class="text-muted">(Opsional)</small></label>
                    <input type="file" name="foto" class="form-control form-control-sm" accept="image/*">
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-sm text-white" style="background-color: #1b3152;"><i class="fas fa-paper-plane me-1"></i> Ajukan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="exportPdfModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form action="{{ route('pengadaan.exportPdf') }}" method="GET" target="_blank" class="modal-content">
            <div class="modal-header text-white py-2" style="background-color: #1b3152;">
                <h6 class="modal-title mb-0 fs-6">Export Laporan Pengadaan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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