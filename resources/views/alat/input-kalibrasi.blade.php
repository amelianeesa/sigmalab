@extends('layouts.app')

@push('styles')
<style>
    .card-header-primary {
        background-color: #0d6efd;
        color: white;
    }
    .card-header-dark {
        background-color: #212529;
        color: white;
    }
    .table-info-alat td {
        padding: 8px 12px;
    }
    .custom-card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }
</style>
@endpush

@section('content')
<div class="container py-1">
    <h1 class="mt-1">Pemeliharaan</h1>
    @auth
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('alat.index') }}">Alat & Kalibrasi</a></li>
            <li class="breadcrumb-item active">Pemeliharaan</li>
        </ol>
    @else
        <p class="text-muted mb-4">Informasi resmi identitas dan status kalibrasi alat laboratorium</p>
    @endauth
    
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- 1. INFORMASI UTAMA ALAT --}}
            <div class="card custom-card mb-4">
                <div class="card-header card-header-primary d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informasi Alat</h5>
                    
                    {{-- Tombol Buka Kartu Pemeliharaan Harian HANYA MUNCUL JIKA SUDAH LOGIN --}}
                    @auth
                        <a href="{{ route('alat.pemeliharaan', $alat->alat_id) }}" class="btn btn-info btn-sm text-white fw-bold shadow-sm">
                            <i class="fas fa-clipboard-list me-1"></i> Buka Kartu Pemeliharaan Harian
                        </a>
                    @else
                        <a href="{{ route('login') }}?redirect={{ route('alat.pemeliharaan', $alat->alat_id) }}" class="btn btn-light btn-sm text-dark fw-bold shadow-sm">
                            <i class="fas fa-sign-in-alt me-1"></i> Kartu Pemeliharaan Harian
                        </a>
                    @endauth
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0 table-info-alat" style="font-size: 0.9rem;">
                        <tr>
                            <td style="width: 160px;" class="fw-bold">Nama Alat</td>
                            <td>: {{ $alat->nama_alat }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Kode Alat</td>
                            <td>: <code>{{ $alat->kode_alat }}</code></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Merk / Tipe</td>
                            <td>: {{ $alat->merk_tipe ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Nomor Seri</td>
                            <td>: {{ $alat->no_seri ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Unit Kerja Pemilik</td>
                            <td>: {{ $alat->unit_kerja_pemilik ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- 2. RIWAYAT KALIBRASI (Muncul untuk siapa saja / Publik & Admin) --}}
            <div class="card custom-card mb-4">
                <div class="card-header card-header-dark">
                    <h6 class="mb-0"><i class="fas fa-history me-2"></i> Riwayat History Kalibrasi & Evaluasi</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center align-middle" style="font-size: 0.8rem;">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Urutan</th>
                                    <th>Jenis</th>
                                    <th>Tanggal Kalibrasi s/d Akhir</th>
                                    <th>Lembaga & No. Sertifikat</th>
                                    <th>Range & Faktor Koreksi</th>
                                    <th>Signifikan</th>
                                    <th>Catatan / Evaluasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alat->riwayatKalibrasi as $index => $riwayat)
                                <tr>
                                    <td class="fw-bold text-primary">Kalibrasi ke-{{ $loop->iteration }}</td>
                                    <td><span class="badge bg-info text-dark">{{ ucfirst($riwayat->jenis_kalibrasi) }}</span></td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($riwayat->tgl_kalibrasi)->format('d/m/Y') }} <br>
                                        <small class="text-muted">s/d {{ \Carbon\Carbon::parse($riwayat->tgl_akhir)->format('d/m/Y') }}</small>
                                    </td>
                                    <td class="text-start">
                                        <strong>{{ $riwayat->lembaga_kalibrasi }}</strong><br>
                                        <small class="text-muted">Sertifikat: {{ $riwayat->no_sertifikat }}</small>
                                    </td>
                                    <td>
                                        <small>Range: {{ $riwayat->range_kapasitas ?? '-' }}</small><br>
                                        <small>Koreksi: {{ $riwayat->faktor_koreksi ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $riwayat->signifikan == 'ya' ? 'success' : 'secondary' }}">
                                            {{ strtoupper($riwayat->signifikan) }}
                                        </span>
                                    </td>
                                    <td class="text-start">
                                        {{ $riwayat->catatan_evaluasi ?? '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">Belum ada riwayat kalibrasi untuk alat ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 3. FORM INPUT KALIBRASI BARU (HANYA MUNCUL JIKA SUDAH LOGIN) --}}
            @auth
            <div class="card custom-card mb-4">
                <div class="card-header card-header-primary">
                    <h6 class="mb-0"><i class="fas fa-plus-circle me-2"></i> Form Input Pengecekan / Kalibrasi Baru</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('alat.store-input-kalibrasi', $alat->alat_id) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Jenis Kalibrasi</label>
                                <select name="jenis_kalibrasi" class="form-select" required>
                                    <option value="internal">Internal</option>
                                    <option value="eksternal">Eksternal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor Sertifikat</label>
                                <input type="text" name="no_sertifikat" class="form-control" required autocomplete="off">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Kalibrasi</label>
                                <input type="date" name="tgl_kalibrasi" id="tgl_kalibrasi" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Interval Kalibrasi</label>
                                <input type="text" name="interval_kalibrasi" id="interval_kalibrasi" class="form-control" placeholder="Contoh: 1 Tahun atau 6 Bulan" required autocomplete="off">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Berakhir Kalibrasi</label>
                                <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lembaga Kalibrasi</label>
                                <input type="text" name="lembaga_kalibrasi" class="form-control" required autocomplete="off">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Range Kapasitas</label>
                                <input type="text" name="range_kapasitas" class="form-control" placeholder="Contoh: 1-90" autocomplete="off">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Faktor Koreksi</label>
                                <input type="text" name="faktor_koreksi" class="form-control" autocomplete="off">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Signifikan</label>
                                <select name="signifikan" class="form-select" required>
                                    <option value="tidak">Tidak</option>
                                    <option value="ya">Ya</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="catatan_evaluasi" class="form-label">Catatan / Evaluasi Kalibrasi</label>
                                <textarea name="catatan_evaluasi" id="catatan_evaluasi" class="form-control" rows="3" placeholder="Tuliskan catatan evaluasi atau hasil analisis alat di sini..." autocomplete="off"></textarea>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Kalibrasi Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @else
            {{-- TAMPILAN JIKA BELUM LOGIN (GUEST / PUBLIK) --}}
            <div class="alert alert-secondary text-center shadow-sm py-4 mb-4">
                <i class="fas fa-lock fa-2x mb-2 text-muted"></i>
                <h5>Form Input Pengecekan / Kalibrasi Dikunci</h5>
                <p class="text-muted mb-3">Anda sedang mengakses halaman publik. Silakan login terlebih dahulu untuk mengisi form atau membuka kartu pemeliharaan harian</p>
                <a href="{{ route('login') }}?redirect={{ url()->current() }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-sign-in-alt me-1"></i> Login Sekarang
                </a>
            </div>
            @endauth

        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const tglKalibrasiInput = document.getElementById('tgl_kalibrasi');
    const intervalInput = document.getElementById('interval_kalibrasi');
    const tglAkhirInput = document.getElementById('tgl_akhir');

    if (!tglKalibrasiInput) return; // Mencegah error jika elemen form tidak ada karena belum login

    let isManualEdit = false;

    function updateMinTanggalAkhir() {
        if (tglKalibrasiInput.value) {
            tglAkhirInput.min = tglKalibrasiInput.value;
            if (tglAkhirInput.value && tglAkhirInput.value < tglKalibrasiInput.value) {
                tglAkhirInput.value = '';
            }
        }
    }

    function hitungTanggalAkhir() {
        if (isManualEdit) return;
        const tglVal = tglKalibrasiInput.value;
        const intervalVal = intervalInput.value.trim().toLowerCase();

        if (!tglVal || !intervalVal) return;

        let tanggal = new Date(tglVal);
        if (isNaN(tanggal.getTime())) return;

        const match = intervalVal.match(/(\d+)\s*(tahun|bulan|hari)/);
        if (!match) return;

        const jumlah = parseInt(match[1]);
        const satuan = match[2];

        if (satuan === 'tahun') {
            tanggal.setFullYear(tanggal.getFullYear() + jumlah);
        } else if (satuan === 'bulan') {
            tanggal.setMonth(tanggal.getMonth() + jumlah);
        } else if (satuan === 'hari') {
            tanggal.setDate(tanggal.getDate() + jumlah);
        }

        let tahun = tanggal.getFullYear();
        let bulan = String(tanggal.getMonth() + 1).padStart(2, '0');
        let hari = String(tanggal.getDate()).padStart(2, '0');

        tglAkhirInput.value = `${tahun}-${bulan}-${hari}`;
    }

    function hitungInterval() {
        const tglMulaiVal = tglKalibrasiInput.value;
        const tglAkhirVal = tglAkhirInput.value;

        if (!tglMulaiVal || !tglAkhirVal) return;

        let start = new Date(tglMulaiVal);
        let end = new Date(tglAkhirVal);

        if (end <= start) return;

        isManualEdit = true;

        let diffYears = end.getFullYear() - start.getFullYear();
        let diffMonths = end.getMonth() - start.getMonth() + (diffYears * 12);
        let diffDays = Math.floor((end - start) / (1000 * 60 * 60 * 24));

        if (diffMonths >= 12 && diffMonths % 12 === 0) {
            let tahun = diffMonths / 12;
            intervalInput.value = tahun + " Tahun";
        } else if (diffMonths > 0) {
            intervalInput.value = diffMonths + " Bulan";
        } else {
            intervalInput.value = diffDays + " Hari";
        }

        isManualEdit = false;
    }

    tglKalibrasiInput.addEventListener('change', function() {
        updateMinTanggalAkhir();
        if (tglAkhirInput.value) {
            hitungInterval();
        } else {
            hitungTanggalAkhir();
        }
    });

    intervalInput.addEventListener('input', function() {
        isManualEdit = false;
        hitungTanggalAkhir();
    });

    tglAkhirInput.addEventListener('change', function() {
        if (tglKalibrasiInput.value && tglAkhirInput.value < tglKalibrasiInput.value) {
            alert("Tanggal berakhir tidak boleh lebih awal dari tanggal kalibrasi!");
            tglAkhirInput.value = '';
            return;
        }
        hitungInterval();
    });
    updateMinTanggalAkhir();
});
</script>
@endsection