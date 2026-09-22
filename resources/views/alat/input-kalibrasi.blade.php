@extends('layouts.app')

@push('styles')
<style>
    .table-info-alat td {
        padding: 6px 10px;
    }
    .custom-card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }
</style>
@endpush

@section('content')
<div class="container-fluid pt-0 pb-4 px-4" style="max-width: 1050px;">
    <div class="mb-2">
        <h4 class="fw-bold mb-1">Pemeliharaan</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 12px;">
                @auth
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('alat.index') }}" class="text-decoration-none">Data Alat & Kalibrasi</a></li>
                @else
                    <li class="breadcrumb-item text-muted">Informasi resmi identitas dan status kalibrasi alat laboratorium</li>
                @endauth
                <li class="breadcrumb-item text-muted active" aria-current="page">Pemeliharaan</li>
            </ol>
        </nav>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-12">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm py-2 px-3 small" role="alert" style="font-size: 0.78rem;">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card custom-card mb-3">
                <div class="card-header text-white d-flex justify-content-between align-items-center py-2 px-3" style="background-color: #1b3152 !important;">
                    <h6 class="mb-0 fw-bold text-white" style="font-size: 13px;"><i class="fas fa-info-circle me-2"></i> Informasi Alat</h6>
                    
                    <div class="d-flex align-items-center gap-2">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm fw-bold dropdown-toggle shadow-sm text-dark py-0.5 px-2" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 11px;">
                                <i class="fas fa-download me-1 text-primary"></i> Unduh Laporan
                            </button>
                            <ul class="dropdown-menu shadow" aria-labelledby="dropdownMenuButton" style="font-size: 0.75rem;">
                                <li>
                                    <a class="dropdown-item text-danger py-1" href="{{ route('alat.export-pdf', $alat->alat_id) }}" target="_blank">
                                        <i class="fas fa-file-pdf me-2"></i> PDF
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-success py-1" href="{{ route('alat.export-excel', $alat->alat_id) }}">
                                        <i class="fas fa-file-excel me-2"></i> Excel
                                    </a>
                                </li>
                            </ul>
                        </div>
                        @auth
                        @if (Route::has('alat.pemeliharaan'))
                            <a href="{{ route('alat.pemeliharaan', $alat->alat_id) }}" class="btn btn-primary btn-sm text-white fw-bold shadow-sm py-0.5 px-2" style="font-size: 11px;">
                                <i class="fas fa-clipboard-list me-1"></i> Kartu Pemeliharaan Harian
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}?redirect={{ url()->current() }}" class="btn btn-light btn-sm text-dark fw-bold shadow-sm py-0.5 px-2" style="font-size: 11px;">
                            <i class="fas fa-sign-in-alt me-1"></i> Kartu Pemeliharaan Harian (Login)
                        </a>
                    @endauth
                    </div>
                </div>
                <div class="card-body px-3 py-2">
                    <table class="table table-borderless mb-0 table-info-alat" style="font-size: 0.8rem;">
                        <tr>
                            <td style="width: 160px;" class="fw-bold py-1">Nama Alat</td>
                            <td class="py-1">: {{ $alat->nama_alat }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold py-1">Kode Alat</td>
                            <td class="py-1">: <code>{{ $alat->kode_alat }}</code></td>
                        </tr>
                        <tr>
                            <td class="fw-bold py-1">Merk / Tipe</td>
                            <td class="py-1">: {{ $alat->merk ?? $alat->merk_tipe ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold py-1">Nomor Seri</td>
                            <td class="py-1">: {{ $alat->no_seri ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold py-1">Unit Kerja Pemilik</td>
                            <td class="py-1">: {{ $alat->unit_pemilik ?? $alat->unit_kerja_pemilik ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card custom-card mb-3">
                <div class="card-header bg-dark text-white py-2 px-3">
                    <h6 class="mb-0 fw-bold text-white" style="font-size: 13px;"><i class="fas fa-history me-2"></i> Riwayat History Kalibrasi & Evaluasi</h6>
                </div>
                <div class="card-body px-3 py-2">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center align-middle mb-0" style="font-size: 0.72rem;">
                            <thead class="table-secondary align-middle">
                                <tr>
                                    <th class="py-1">Urutan</th>
                                    <th class="py-1">Jenis Kalibrasi</th>
                                    <th class="py-1">Tanggal Kalibrasi s/d Akhir</th>
                                    <th class="py-1">Lembaga & Sertifikat</th>
                                    <th class="py-1">Range & Faktor Koreksi</th>
                                    <th class="py-1">Signifikan</th>
                                    <th class="py-1">Catatan / Evaluasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alat->riwayatKalibrasi as $index =>$riwayat)
                                <tr>
                                    <td class="fw-bold text-primary py-1">Kalibrasi ke-{{ $loop->iteration }}</td>
                                    <td class="py-1"><span class="badge bg-info text-dark" style="font-size: 9px;">{{ ucfirst($riwayat->jenis_kalibrasi) }}</span></td>
                                    <td class="py-1">
                                        {{ \Carbon\Carbon::parse($riwayat->tgl_kalibrasi)->format('d/m/Y') }} <br>
                                        <small class="text-muted">s/d {{ \Carbon\Carbon::parse($riwayat->tgl_akhir)->format('d/m/Y') }}</small>
                                    </td>
                                    <td class="text-start py-1">
                                        <strong>Lembaga: {{ $riwayat->lembaga_kalibrasi }}</strong><br>
                                        <small class="text-muted">Sertifikat: {{ $riwayat->no_sertifikat }}
                                            @if(!empty($riwayat->file_sertifikat))
                                            <div class="mt-1">
                                                <a href="{{ asset('storage/' . $riwayat->file_sertifikat) }}" target="_blank" class="btn btn-outline-primary btn-sm fw-bold shadow-sm" style="font-size: 10.5px; padding: 2px 6px;" title="Lihat Sertifikat">
                                                    <i class="fas fa-file-pdf me-1"></i> Lihat Sertifikat
                                                </a>
                                            </div>
                                        @endif
                                        </small>
                                    </td>
                                    <td class="py-1">
                                        <small>Range: {{ $riwayat->range_kapasitas ?? '-' }}</small><br>
                                        <small>Koreksi: {{ $riwayat->faktor_koreksi ?? '-' }}</small>
                                    </td>
                                    <td class="py-1">
                                        <span class="badge bg-{{ $riwayat->signifikan == 'ya' ? 'success' : 'secondary' }}" style="font-size: 9px;">
                                            {{ strtoupper($riwayat->signifikan) }}
                                        </span>
                                    </td>
                                    <td class="text-start py-1">
                                        {{ $riwayat->catatan_evaluasi ?? '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-2">Belum ada riwayat kalibrasi untuk alat ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 3. FORM INPUT KALIBRASI BARU (Header Biru Pekat Sucofindo, Teks Putih) --}}
            @auth
            <div class="card custom-card mb-3">
                <div class="card-header text-white py-2 px-3" style="background-color: #1b3152 !important;">
                    <h6 class="mb-0 fw-bold text-white" style="font-size: 13px;"><i class="fas fa-plus-circle me-2"></i> Form Input Pengecekan / Kalibrasi Baru</h6>
                </div>
                <div class="card-body px-3 py-2.5">
                    <form action="{{ route('alat.input-kalibrasi', $alat->alat_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="alat_id" value="{{ $alat->alat_id }}">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small mb-1" style="font-size: 11px;">Jenis Kalibrasi</label>
                                <select name="jenis_kalibrasi" class="form-select form-select-sm" required style="font-size: 0.75rem;">
                                    <option>--Pilih Jenis--</option>
                                    <option value="internal">Internal</option>
                                    <option value="eksternal">Eksternal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small mb-1" style="font-size: 11px;">Nomor Sertifikat</label>
                                <input type="text" name="no_sertifikat" class="form-control form-control-sm" required autocomplete="off" style="font-size: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small mb-1" style="font-size: 11px;">Upload File Sertifikat <small class="text-muted">(PDF/Gambar)</small></label>
                                <input type="file" name="file_sertifikat" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png" style="font-size: 0.75rem;">
                                <div class="form-text text-muted" style="font-size: 9px;">Format: PDF, JPG, JPEG, PNG (Maks. 2MB)</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small mb-1" style="font-size: 11px;">Tanggal Kalibrasi</label>
                                <input type="date" name="tgl_kalibrasi" id="tgl_kalibrasi" class="form-control form-control-sm" required style="font-size: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small mb-1" style="font-size: 11px;">Interval Kalibrasi</label>
                                <input type="text" name="interval_kalibrasi" id="interval_kalibrasi" class="form-control form-control-sm" placeholder="Contoh: 1 Tahun atau 6 Bulan" required autocomplete="off" style="font-size: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small mb-1" style="font-size: 11px;">Tanggal Berakhir Kalibrasi</label>
                                <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control form-control-sm" required style="font-size: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small mb-1" style="font-size: 11px;">Lembaga Kalibrasi</label>
                                <input type="text" name="lembaga_kalibrasi" class="form-control form-control-sm" required autocomplete="off" style="font-size: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small mb-1" style="font-size: 11px;">Range Kapasitas</label>
                                <input type="text" name="range_kapasitas" class="form-control form-control-sm" placeholder="Contoh: 1-90" autocomplete="off" style="font-size: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small mb-1" style="font-size: 11px;">Faktor Koreksi</label>
                                <input type="text" name="faktor_koreksi" class="form-control form-control-sm" autocomplete="off" style="font-size: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small mb-1" style="font-size: 11px;">Signifikan</label>
                                <select name="signifikan" class="form-select form-select-sm" required style="font-size: 0.75rem;">
                                    <option>--Pilih Signifikan--</option>
                                    <option value="ya">Ya</option>
                                    <option value="tidak">Tidak</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="catatan_evaluasi" class="form-label fw-bold small mb-1" style="font-size: 11px;">Catatan / Evaluasi Kalibrasi</label>
                                <textarea name="catatan_evaluasi" id="catatan_evaluasi" class="form-control form-control-sm" rows="2" placeholder="Tuliskan catatan evaluasi atau hasil analisis alat di sini..." autocomplete="off" style="font-size: 0.75rem;"></textarea>
                            </div>
                        </div>
                        <div class="mt-3 text-end">
                            <button type="submit" class="btn text-white btn-sm px-3 py-1 shadow-sm fw-bold" style="font-size: 0.72rem; background-color: #1b3152;">
                                <i class="fas fa-save me-1"></i> Simpan Kalibrasi Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @else
            {{-- TAMPILAN UNTUK PUBLIK --}}
            <div class="card custom-card mb-3 border-secondary">
                <div class="card-header bg-secondary text-white py-2 px-3">
                    <h6 class="mb-0 fw-bold text-white" style="font-size: 13px;"><i class="fas fa-lock me-2"></i> Form Input Pengecekan / Kalibrasi Baru</h6>
                </div>
                <div class="card-body text-center py-3">
                    <i class="fas fa-user-lock fa-2x text-muted mb-2"></i>
                    <h6 class="text-dark fw-bold mb-1" style="font-size: 0.9rem;">Akses Terbatas</h6>
                    <p class="text-muted small mb-2" style="font-size: 0.75rem;">Formulir input kalibrasi baru hanya dapat diisi oleh petugas atau admin yang telah masuk ke sistem.</p>
                    <a href="{{ route('login') }}?redirect={{ url()->current() }}" class="btn text-white btn-sm px-3 py-1 shadow-sm fw-bold" style="font-size: 0.72rem; background-color: #1b3152;">
                        <i class="fas fa-sign-in-alt me-1"></i> Login untuk Mengisi Form Kalibrasi
                    </a>
                </div>
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

    if (!tglKalibrasiInput) return;

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

    if (tglKalibrasiInput) {
        tglKalibrasiInput.addEventListener('change', function() {
            updateMinTanggalAkhir();
            if (tglAkhirInput.value) {
                hitungInterval();
            } else {
                hitungTanggalAkhir();
            }
        });
    }

    if (intervalInput) {
        intervalInput.addEventListener('input', function() {
            isManualEdit = false;
            hitungTanggalAkhir();
        });
    }

    if (tglAkhirInput) {
        tglAkhirInput.addEventListener('change', function() {
            if (tglKalibrasiInput.value && tglAkhirInput.value < tglKalibrasiInput.value) {
                alert("Tanggal berakhir tidak boleh lebih awal dari tanggal kalibrasi!");
                tglAkhirInput.value = '';
                return;
            }
            hitungInterval();
        });
        updateMinTanggalAkhir();
    }
});
</script>
@endsection