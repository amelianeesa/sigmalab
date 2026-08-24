@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Detail Alat: {{ $alat->nama_alat }}</h2>
            <ol class="breadcrumb mb-0 mt-2">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('alat.index') }}" class="text-decoration-none">Manajemen Peralatan</a></li>
                <li class="breadcrumb-item active">{{ $alat->kode_alat }}</li>
            </ol>
        </div>
        <div>
            <a href="{{ route('alat.index') }}" class="btn btn-outline-secondary shadow-sm rounded-pill px-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Terdapat kesalahan:
            <ul class="mb-0 mt-2 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Informasi Utama -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 80px; height: 80px; font-size: 30px;">
                            <i class="fas fa-microscope"></i>
                        </div>
                        <h4 class="fw-bold mb-1">{{ $alat->nama_alat }}</h4>
                        <p class="text-muted mb-0">{{ $alat->kode_alat }}</p>
                        
                        <div class="mt-3">
                            <span class="badge bg-{{ $alat->kondisi_barang == 'baik' ? 'success' : 'danger' }} px-3 py-2 rounded-pill shadow-sm">
                                Kondisi: {{ ucfirst($alat->kondisi_barang) }}
                            </span>
                            <span class="badge bg-{{ $alat->status_barang == 'terpakai' ? 'primary' : 'secondary' }} px-3 py-2 rounded-pill shadow-sm ms-1">
                                Status: {{ ucfirst($alat->status_barang) }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="px-2">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Merk / Tipe</span>
                            <span class="fw-semibold text-end">{{ $alat->merk_tipe ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Serial Number</span>
                            <span class="fw-semibold text-end">{{ $alat->no_seri ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Unit Pemilik</span>
                            <span class="fw-semibold text-end">{{ $alat->unit_kerja_pemilik ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Tabs -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm border-0 mb-4 rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 rounded-top-4">
                    <ul class="nav nav-tabs border-bottom" id="alatTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold text-primary" id="logbook-tab" data-bs-toggle="tab" data-bs-target="#logbook" type="button" role="tab" aria-controls="logbook" aria-selected="true">
                                <i class="fas fa-book-open me-1"></i> Logbook Penggunaan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold text-secondary" id="perbaikan-tab" data-bs-toggle="tab" data-bs-target="#perbaikan" type="button" role="tab" aria-controls="perbaikan" aria-selected="false">
                                <i class="fas fa-tools me-1"></i> Riwayat Perbaikan
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content" id="alatTabsContent">
                        
                        <!-- TAB LOGBOOK PENGGUNAAN -->
                        <div class="tab-pane fade show active" id="logbook" role="tabpanel" aria-labelledby="logbook-tab">
                            <h5 class="fw-bold mb-3"><i class="fas fa-history me-2 text-primary"></i>Histori Pemakaian Alat</h5>
                            
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>No. Order / Kegiatan</th>
                                            <th>Analis</th>
                                            <th>Status Kegiatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($alat->kegiatanAlat as $ka)
                                            @if($ka->kegiatan)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($ka->kegiatan->tgl_kegiatan)->translatedFormat('d M Y') }}</td>
                                                <td>
                                                    <strong>{{ $ka->kegiatan->no_order ?? $ka->kegiatan->kode_sampel }}</strong><br>
                                                    <span class="text-muted small">{{ ucfirst($ka->kegiatan->jenis_kegiatan) }}</span>
                                                </td>
                                                <td>{{ $ka->kegiatan->personil->nama_personil ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $ka->kegiatan->status_kegiatan == 'selesai' ? 'success' : ($ka->kegiatan->status_kegiatan == 'berjalan' ? 'primary' : 'secondary') }}">
                                                        {{ ucfirst($ka->kegiatan->status_kegiatan) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endif
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-2x mb-2 text-gray-300"></i><br>
                                                Belum ada histori pemakaian untuk alat ini di QC.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB RIWAYAT PERBAIKAN -->
                        <div class="tab-pane fade" id="perbaikan" role="tabpanel" aria-labelledby="perbaikan-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0"><i class="fas fa-wrench me-2 text-danger"></i>Catatan Kerusakan & Perbaikan</h5>
                                
                                @if(!$sedangDiperbaiki)
                                    <button type="button" class="btn btn-danger btn-sm shadow-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#laporRusakModal">
                                        <i class="fas fa-exclamation-circle me-1"></i> Lapor Kerusakan
                                    </button>
                                @else
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fas fa-tools me-1"></i> Sedang Dalam Perbaikan</span>
                                @endif
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal Lapor</th>
                                            <th>Dilaporkan Oleh</th>
                                            <th>Masalah/Kerusakan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($alat->riwayatPerbaikan->sortByDesc('tanggal_rusak') as $perbaikan)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($perbaikan->tanggal_rusak)->translatedFormat('d M Y') }}</td>
                                            <td>{{ $perbaikan->pelapor->personil->nama_personil ?? $perbaikan->pelapor->username ?? 'Sistem' }}</td>
                                            <td>{{ Str::limit($perbaikan->deskripsi_kerusakan, 50) }}</td>
                                            <td>
                                                @php
                                                    $bg = 'secondary';
                                                    if($perbaikan->status_perbaikan == 'Dalam Perbaikan') $bg = 'warning text-dark';
                                                    if($perbaikan->status_perbaikan == 'Selesai') $bg = 'success';
                                                    if($perbaikan->status_perbaikan == 'Tidak Bisa Diperbaiki') $bg = 'danger';
                                                @endphp
                                                <span class="badge bg-{{ $bg }}">{{ $perbaikan->status_perbaikan }}</span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailPerbaikanModal{{ $perbaikan->riwayat_perbaikan_id }}">
                                                    Detail
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="fas fa-check-circle fa-2x mb-2 text-success" style="opacity:0.5"></i><br>
                                                Belum ada catatan kerusakan untuk alat ini.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lapor Rusak -->
<div class="modal fade" id="laporRusakModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 bg-danger text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Lapor Kerusakan Alat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('alat.perbaikan.store', $alat->alat_id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">Status alat akan diubah menjadi <strong class="text-danger">Rusak</strong>.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Rusak <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal_rusak" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi Kerusakan / Kendala <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="deskripsi_kerusakan" rows="4" placeholder="Jelaskan secara detail masalah yang dialami alat..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">Laporkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail & Update Perbaikan -->
@foreach($alat->riwayatPerbaikan as $perbaikan)
<div class="modal fade" id="detailPerbaikanModal{{ $perbaikan->riwayat_perbaikan_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 bg-light rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-tools me-2 text-primary"></i>Detail Perbaikan Alat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('alat.perbaikan.update', [$alat->alat_id, $perbaikan->riwayat_perbaikan_id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6 border-end">
                            <h6 class="fw-bold text-muted text-uppercase small mb-3">Info Laporan</h6>
                            <p class="mb-1"><span class="text-muted" style="display:inline-block; width:120px;">Tgl Dilaporkan</span>: <strong>{{ \Carbon\Carbon::parse($perbaikan->tanggal_rusak)->translatedFormat('d M Y') }}</strong></p>
                            <p class="mb-1"><span class="text-muted" style="display:inline-block; width:120px;">Pelapor</span>: <strong>{{ $perbaikan->pelapor->personil->nama_personil ?? $perbaikan->pelapor->username ?? '-' }}</strong></p>
                            <div class="mt-2 bg-light p-2 rounded border">
                                <span class="text-muted small">Deskripsi Masalah:</span><br>
                                {{ $perbaikan->deskripsi_kerusakan }}
                            </div>
                        </div>
                        <div class="col-md-6 ps-4">
                            <h6 class="fw-bold text-muted text-uppercase small mb-3">Status Saat Ini</h6>
                            <p class="mb-1"><span class="text-muted" style="display:inline-block; width:120px;">Status</span>: 
                                <span class="badge bg-{{ $perbaikan->status_perbaikan == 'Selesai' ? 'success' : ($perbaikan->status_perbaikan == 'Tidak Bisa Diperbaiki' ? 'danger' : 'warning text-dark') }}">{{ $perbaikan->status_perbaikan }}</span>
                            </p>
                            @if($perbaikan->tanggal_selesai)
                                <p class="mb-1"><span class="text-muted" style="display:inline-block; width:120px;">Tgl Selesai</span>: <strong>{{ \Carbon\Carbon::parse($perbaikan->tanggal_selesai)->translatedFormat('d M Y') }}</strong></p>
                            @endif
                            @if($perbaikan->diverifikasi_oleh)
                                <p class="mb-1"><span class="text-muted" style="display:inline-block; width:120px;">Diverifikasi</span>: <strong>{{ $perbaikan->verifikator->personil->nama_personil ?? $perbaikan->verifikator->username ?? '-' }}</strong></p>
                            @endif
                        </div>
                    </div>
                    
                    @if($perbaikan->status_perbaikan !== 'Selesai' && $perbaikan->status_perbaikan !== 'Tidak Bisa Diperbaiki')
                    <hr>
                    <h6 class="fw-bold text-muted text-uppercase small mb-3">Update Tindakan</h6>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ubah Status <span class="text-danger">*</span></label>
                        <select name="status_perbaikan" class="form-select" required>
                            <option value="Belum Diperbaiki" {{ $perbaikan->status_perbaikan == 'Belum Diperbaiki' ? 'selected' : '' }}>Belum Diperbaiki</option>
                            <option value="Dalam Perbaikan" {{ $perbaikan->status_perbaikan == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                            @if(Auth::user()->role->nama_role === \App\Enums\PeranPengguna::KOORDINATOR_LAB->value)
                            <option value="Selesai">Selesai (Ubah kondisi jadi Baik)</option>
                            <option value="Tidak Bisa Diperbaiki">Tidak Bisa Diperbaiki</option>
                            @endif
                        </select>
                        @if(Auth::user()->role->nama_role !== \App\Enums\PeranPengguna::KOORDINATOR_LAB->value)
                        <div class="form-text text-warning"><i class="fas fa-info-circle"></i> Hanya Koordinator Lab yang dapat menutup laporan (Selesai).</div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tindakan Perbaikan yang Dilakukan</label>
                        <textarea class="form-control" name="tindakan_perbaikan" rows="3" placeholder="Sebutkan langkah perbaikan yang telah dikerjakan...">{{ $perbaikan->tindakan_perbaikan }}</textarea>
                    </div>
                    @else
                    <hr>
                    <h6 class="fw-bold text-muted text-uppercase small mb-3">Hasil Tindakan</h6>
                    <div class="bg-light p-3 rounded border">
                        {{ $perbaikan->tindakan_perbaikan ?: 'Tidak ada deskripsi tindakan yang dicatat.' }}
                    </div>
                    @endif
                </div>
                @if($perbaikan->status_perbaikan !== 'Selesai' && $perbaikan->status_perbaikan !== 'Tidak Bisa Diperbaiki')
                <div class="modal-footer border-top-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan Update</button>
                </div>
                @else
                <div class="modal-footer border-top-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Active tab styling logic if needed
    });
</script>
@endsection
