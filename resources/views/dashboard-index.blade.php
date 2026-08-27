@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Monitoring Center</h2>
            <p class="text-muted mb-0">Selamat datang, Anda login sebagai <strong class="text-primary">{{ $role }}</strong></p>
        </div>
    </div>

    <div class="row g-4 mb-4">

        @modul('tindak_lanjut')
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm border-start border-4 border-danger">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-2 fs-7 text-uppercase fw-bold">Outlier (Open)</p>
                            <h2 class="fw-bold mb-0 text-dark">{{ $outliers }}</h2>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('tindak-lanjut.index') }}" class="text-decoration-none text-danger fw-semibold">Lihat Detail <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
        @endmodul

        @modul('proses_hasil')
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm border-start border-4 border-info">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-2 fs-7 text-uppercase fw-bold">Pengujian Aktif</p>
                            <h2 class="fw-bold mb-0 text-dark">{{ $kegiatanBerjalan }}</h2>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('kegiatan.index') }}" class="text-decoration-none text-info fw-semibold">Buka Modul QC <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
        @endmodul

        @modul('alat')
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm border-start border-4 border-warning">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-2 fs-7 text-uppercase fw-bold">Peralatan & Kalibrasi</p>
                            <h2 class="fw-bold mb-0 text-dark">{{ $tenggatKalibrasi }}</h2>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('alat.index') }}" class="text-decoration-none text-warning fw-semibold">Kelola Aset <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
        @endmodul

        @modul('sdm')
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm border-start border-4 border-primary">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-2 fs-7 text-uppercase fw-bold">Sertifikasi Personil H-6 Bulan</p>
                            <h2 class="fw-bold mb-0 text-dark">{{ $sertifikasiHampirHabis }}</h2>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('sdm.index') }}" class="text-decoration-none text-primary fw-semibold">Cek Sertifikasi <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
        @endmodul
    </div>

    <div class="row g-4 mb-4">
        @modul('barang')
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0"><i class="fas fa-box-open text-danger me-2"></i> Stok Kritis</h5>
                        <span class="badge bg-danger rounded-pill">{{ $stokTipis }}</span>
                    </div>
                    <p class="text-muted small mb-3">Terdapat {{ $stokTipis }} item barang/reagen yang berada di bawah batas minimum.</p>
                    <a href="{{ route('barang.index') }}" class="btn btn-outline-danger btn-sm w-100">Cek Inventori</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0"><i class="fas fa-calendar-times text-warning me-2"></i> Akan Kedaluwarsa</h5>
                        <span class="badge bg-warning text-dark rounded-pill">{{ $barangExp }}</span>
                    </div>
                    <p class="text-muted small mb-3">Terdapat {{ $barangExp }} bahan/reagen yang kedaluwarsa dalam 30 hari ke depan.</p>
                    <a href="{{ route('barang.index') }}" class="btn btn-outline-warning btn-sm w-100">Cek Expired Date</a>
                </div>
            </div>
        </div>
        @endmodul

        @modul('pengadaan')
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0"><i class="fas fa-shopping-cart text-primary me-2"></i> Approval Pengadaan</h5>
                        <span class="badge bg-primary rounded-pill">{{ $pengadaanPending }}</span>
                    </div>
                    <p class="text-muted small mb-3">Ada {{ $pengadaanPending }} pengajuan barang/reagen yang butuh persetujuan segera.</p>
                    <a href="{{ route('pengadaan.index') ?? '#' }}" class="btn btn-outline-primary btn-sm w-100">Proses Pengajuan</a>
                </div>
            </div>
        </div>
        @endmodul
    </div>
</div>
@endsection