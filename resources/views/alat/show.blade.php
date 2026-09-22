@extends('layouts.app')

@push('styles')
<style>
    .container-fluid {
        padding-top: 2px !important;
    }
    .card-body {
        padding: 0.85rem 1.1rem !important;
        overflow: visible !important;
    }
    .table-responsive {
        overflow-x: visible !important;
        overflow-y: visible !important;
    }
    .table td, .table th {
        padding: 6px 10px !important;
        font-size: 0.78rem !important;
        vertical-align: middle;
    }
    .card-shadow-custom {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
        border: 1px solid #e3e6f0;
        overflow: visible !important;
    }
    .btn-corporate-blue {
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
        color: #ffffff !important;
    }
    .btn-corporate-blue:hover, 
    .btn-corporate-blue:focus, 
    .btn-corporate-blue:active {
        background-color: #14253e !important;
        border-color: #14253e !important;
        color: #ffffff !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid pt-0 pb-3 px-4" style="max-width: 1100px;">
    
    <div class="d-flex justify-content-between align-items-center mb-2 mt-1">
        <div>
            <h5 class="fw-bold mb-0 text-dark" style="font-size: 1rem; letter-spacing: 0.5px;">
                Detail Peralatan & Perbaikan Kerusakan
            </h5>
        </div>
        <div>
            <a href="{{ route('alat.index') }}" class="btn btn-outline-secondary btn-sm fw-bold py-1 px-2 shadow-sm" style="font-size: 11px;">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger py-2 px-3 mb-2 shadow-sm" style="font-size: 0.78rem;" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> Terdapat kesalahan:
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow-sm card-shadow-custom bg-white">
                <div class="card-header text-white fw-bold py-2 px-3 d-flex align-items-center" style="background-color: #1b3152; font-size: 0.85rem;">
                    <i class="fas fa-info-circle me-2"></i> Informasi Alat
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="rounded-circle text-white d-inline-flex align-items-center justify-content-center mb-2 shadow-sm" style="width: 55px; height: 55px; font-size: 22px; background-color: #1b3152;">
                            <i class="fas fa-microscope"></i>
                        </div>
                        <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.95rem;">{{ $alat->nama_alat }}</h6>
                        <span class="text-muted" style="font-size: 0.78rem;">{{ $alat->kode_alat }}</span>
                        
                        <div class="mt-2">
                            <span class="badge bg-{{ $alat->kondisi_barang == 'baik' ? 'success' : 'danger' }} px-2 py-1 shadow-sm" style="font-size: 0.7rem;">
                                Kondisi: {{ ucfirst($alat->kondisi_barang) }}
                            </span>
                            <span class="badge bg-{{ $alat->status_barang == 'terpakai' ? 'primary' : 'secondary' }} px-2 py-1 shadow-sm ms-1" style="font-size: 0.7rem;">
                                Status: {{ ucfirst($alat->status_barang) }}
                            </span>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="px-1" style="font-size: 0.78rem;">
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">Merk / Tipe</span>
                            <span class="fw-semibold text-dark text-end">{{ $alat->merk_tipe ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom">
                            <span class="text-muted">Serial Number</span>
                            <span class="fw-semibold text-dark text-end">{{ $alat->no_seri ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span class="text-muted">Unit Pemilik</span>
                            <span class="fw-semibold text-dark text-end">{{ $alat->unit_kerja_pemilik ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-sm card-shadow-custom bg-white">
                <div class="card-header text-white fw-bold py-2 px-3 d-flex justify-content-between align-items-center" style="background-color: #1b3152; font-size: 0.85rem;">
                    <span><i class="fas fa-tools me-2"></i> Catatan Kerusakan & Perbaikan</span>
                    
                    <div>
                        @if(!$sedangDiperbaiki)
                            <button type="button" class="btn btn-danger btn-sm fw-bold py-1 px-2 shadow-sm text-white" style="font-size: 11px; background-color: #dc3545; border-color: #dc3545;" data-bs-toggle="modal" data-bs-target="#laporRusakModal">
                                <i class="fas fa-exclamation-circle me-1"></i> Lapor Kerusakan
                            </button>
                        @else
                            <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 10px;"><i class="fas fa-tools me-1"></i> Sedang Dalam Perbaikan</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr style="font-size: 0.75rem;">
                                    <th>Tanggal Lapor</th>
                                    <th>Dilaporkan Oleh</th>
                                    <th>Masalah/Kerusakan</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width: 70px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alat->riwayatPerbaikan->sortByDesc('tanggal_rusak') as $perbaikan)
                                <tr style="font-size: 0.78rem;">
                                    <td>{{ \Carbon\Carbon::parse($perbaikan->tanggal_rusak)->translatedFormat('d M Y') }}</td>
                                    <td>{{ $perbaikan->pelapor->personil->nama_personil ?? $perbaikan->pelapor->username ?? 'Sistem' }}</td>
                                    <td>{{ Str::limit($perbaikan->deskripsi_kerusakan, 40) }}</td>
                                    <td>
                                        @php
                                            $bg = 'secondary';
                                            if($perbaikan->status_perbaikan == 'Dalam Perbaikan') $bg = 'warning text-dark';
                                            if($perbaikan->status_perbaikan == 'Selesai') $bg = 'success';
                                            if($perbaikan->status_perbaikan == 'Tidak Bisa Diperbaiki') $bg = 'danger';
                                        @endphp
                                        <span class="badge bg-{{ $bg }}" style="font-size: 0.68rem; padding: 0.25rem 0.5rem;">{{ $perbaikan->status_perbaikan }}</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-corporate-blue py-1 px-2 shadow-sm text-white" style="background-color: #1b3152 !important; border-color: #1b3152 !important; color: #ffffff !important; font-size: 11px;" data-bs-toggle="modal" data-bs-target="#detailPerbaikanModal{{ $perbaikan->riwayat_perbaikan_id }}">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3" style="font-size: 0.78rem;">
                                        <i class="fas fa-check-circle fa-lg mb-1 text-success" style="opacity:0.6"></i><br>
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

<div class="modal fade" id="laporRusakModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3 shadow">
            <div class="modal-header text-white py-2 px-3" style="background-color: #1b3152;">
                <h6 class="modal-title fw-bold" style="font-size: 0.85rem;"><i class="fas fa-exclamation-triangle me-2"></i>Lapor Kerusakan Alat</h6>
                <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('alat.perbaikan.store', $alat->alat_id) }}" method="POST">
                @csrf
                <div class="modal-body p-3" style="font-size: 0.78rem;">
                    <p class="text-muted mb-2">Status alat akan diubah menjadi <strong class="text-danger">Rusak</strong>.</p>
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Tanggal Rusak <span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-sm" name="tanggal_rusak" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Deskripsi Kerusakan / Kendala <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-sm" name="deskripsi_kerusakan" rows="3" placeholder="Jelaskan secara detail masalah yang dialami alat..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 bg-light py-2 px-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal" style="font-size: 0.75rem;">Batal</button>
                    <button type="submit" class="btn btn-corporate-blue btn-sm px-3 shadow-sm text-white" style="background-color: #1b3152 !important; border-color: #1b3152 !important; color: #ffffff !important; font-size: 0.75rem;">Laporkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($alat->riwayatPerbaikan as $perbaikan)
<div class="modal fade" id="detailPerbaikanModal{{ $perbaikan->riwayat_perbaikan_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-3 shadow">
            <div class="modal-header text-white py-2 px-3" style="background-color: #1b3152;">
                <h6 class="modal-title fw-bold" style="font-size: 0.85rem;"><i class="fas fa-tools me-2"></i>Detail Perbaikan Alat</h6>
                <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('alat.perbaikan.update', [$alat->alat_id, $perbaikan->riwayat_perbaikan_id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-3" style="font-size: 0.78rem;">
                    <div class="row mb-3">
                        <div class="col-md-6 border-end">
                            <h6 class="fw-bold text-muted text-uppercase small mb-2" style="font-size: 0.72rem;">Info Laporan</h6>
                            <p class="mb-1"><span class="text-muted" style="display:inline-block; width:110px;">Tgl Dilaporkan</span>: <strong>{{ \Carbon\Carbon::parse($perbaikan->tanggal_rusak)->translatedFormat('d M Y') }}</strong></p>
                            <p class="mb-1"><span class="text-muted" style="display:inline-block; width:110px;">Pelapor</span>: <strong>{{ $perbaikan->pelapor->personil->nama_personil ?? $perbaikan->pelapor->username ?? '-' }}</strong></p>
                            <div class="mt-2 bg-light p-2 rounded border">
                                <span class="text-muted small">Deskripsi Masalah:</span><br>
                                {{ $perbaikan->deskripsi_kerusakan }}
                            </div>
                        </div>
                        <div class="col-md-6 ps-3">
                            <h6 class="fw-bold text-muted text-uppercase small mb-2" style="font-size: 0.72rem;">Status Saat Ini</h6>
                            <p class="mb-1"><span class="text-muted" style="display:inline-block; width:110px;">Status</span>: 
                                <span class="badge bg-{{ $perbaikan->status_perbaikan == 'Selesai' ? 'success' : ($perbaikan->status_perbaikan == 'Tidak Bisa Diperbaiki' ? 'danger' : 'warning text-dark') }}" style="font-size: 0.68rem;">{{ $perbaikan->status_perbaikan }}</span>
                            </p>
                            @if($perbaikan->tanggal_selesai)
                                <p class="mb-1"><span class="text-muted" style="display:inline-block; width:110px;">Tgl Selesai</span>: <strong>{{ \Carbon\Carbon::parse($perbaikan->tanggal_selesai)->translatedFormat('d M Y') }}</strong></p>
                            @endif
                            @if($perbaikan->diverifikasi_oleh)
                                <p class="mb-1"><span class="text-muted" style="display:inline-block; width:110px;">Diverifikasi</span>: <strong>{{ $perbaikan->verifikator->personil->nama_personil ?? $perbaikan->verifikator->username ?? '-' }}</strong></p>
                            @endif
                        </div>
                    </div>
                    
                    @if($perbaikan->status_perbaikan !== 'Selesai' && $perbaikan->status_perbaikan !== 'Tidak Bisa Diperbaiki')
                    <hr class="my-2">
                    <h6 class="fw-bold text-muted text-uppercase small mb-2" style="font-size: 0.72rem;">Update Tindakan</h6>
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Ubah Status <span class="text-danger">*</span></label>
                        <select name="status_perbaikan" class="form-select form-select-sm" required>
                            <option value="Belum Diperbaiki" {{ $perbaikan->status_perbaikan == 'Belum Diperbaiki' ? 'selected' : '' }}>Belum Diperbaiki</option>
                            <option value="Dalam Perbaikan" {{ $perbaikan->status_perbaikan == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                            @if(Auth::user()->role->nama_role === \App\Enums\PeranPengguna::KOORDINATOR_LAB->value)
                            <option value="Selesai">Selesai (Ubah kondisi jadi Baik)</option>
                            <option value="Tidak Bisa Diperbaiki">Tidak Bisa Diperbaiki</option>
                            @endif
                        </select>
                        @if(Auth::user()->role->nama_role !== \App\Enums\PeranPengguna::KOORDINATOR_LAB->value)
                        <div class="form-text text-warning" style="font-size: 0.68rem;"><i class="fas fa-info-circle"></i> Hanya Koordinator Lab yang dapat menutup laporan (Selesai).</div>
                        @endif
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Tindakan Perbaikan yang Dilakukan</label>
                        <textarea class="form-control form-control-sm" name="tindakan_perbaikan" rows="2" placeholder="Sebutkan langkah perbaikan yang telah dikerjakan...">{{ $perbaikan->tindakan_perbaikan }}</textarea>
                    </div>
                    @else
                    <hr class="my-2">
                    <h6 class="fw-bold text-muted text-uppercase small mb-2" style="font-size: 0.72rem;">Hasil Tindakan</h6>
                    <div class="bg-light p-2 rounded border">
                        {{ $perbaikan->tindakan_perbaikan ?: 'Tidak ada deskripsi tindakan yang dicatat.' }}
                    </div>
                    @endif
                </div>
                <div class="modal-footer border-top-0 bg-light py-2 px-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal" style="font-size: 0.75rem;">Tutup</button>
                    @if($perbaikan->status_perbaikan !== 'Selesai' && $perbaikan->status_perbaikan !== 'Tidak Bisa Diperbaiki')
                    <button type="submit" class="btn btn-corporate-blue btn-sm px-3 shadow-sm text-white" style="background-color: #1b3152 !important; border-color: #1b3152 !important; color: #ffffff !important; font-size: 0.75rem;">Simpan Update</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection