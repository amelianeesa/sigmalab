@extends('layouts.app')

@section('content')
<div class="container-fluid pt-0 pb-4 px-4" style="max-width: 1050px;">
    <!-- Judul & Breadcrumb Lebih Compact dan Dekat ke Topbar -->
    <div class="pt-2 mb-2">
        <h5 class="fw-bold mb-1" style="font-size: 1.2rem; color: #333;">Edit Data Alat & Kalibrasi</h5>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <!-- Header Card Biru Tua Pekat Seragam dengan Topbar (#1b3152) -->
        <div class="card-header text-white py-1.5 px-3" style="background-color: #1b3152;">
            <h6 class="mb-0 fw-semibold" style="font-size: 13px;"> Form Ubah Data Alat & Kalibrasi</h6>
        </div>
        <div class="card-body px-3 py-2.5">
            <form action="{{ route('alat.update', $alat->alat_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h6 class="fw-bold mb-2 text-dark" style="font-size: 13px;"> Informasi Spesifikasi Alat</h6>
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Kode Alat (CODE) <span class="text-danger">*</span></label>
                        <input type="text" name="kode_alat" class="form-control form-control-sm @error('kode_alat') is-invalid @enderror" value="{{ old('kode_alat', $alat->kode_alat) }}" required>
                        @error('kode_alat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Nama Barang / Alat <span class="text-danger">*</span></label>
                        <input type="text" name="nama_alat" class="form-control form-control-sm @error('nama_alat') is-invalid @enderror" value="{{ old('nama_alat', $alat->nama_alat) }}" required>
                        @error('nama_alat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">No. Inventaris</label>
                        <input type="text" name="no_inventaris" class="form-control form-control-sm @error('no_inventaris') is-invalid @enderror" placeholder="mis. INV-001" value="{{ old('no_inventaris', $alat->no_inventaris) }}">
                        @error('no_inventaris') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Merk / Tipe</label>
                        <input type="text" name="merk_tipe" class="form-control form-control-sm" value="{{ old('merk_tipe', $alat->merk_tipe) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Serial Number</label>
                        <input type="text" name="no_seri" class="form-control form-control-sm" value="{{ old('no_seri', $alat->no_seri) }}">
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Warna</label>
                        <input type="text" name="warna" class="form-control form-control-sm" value="{{ old('warna', $alat->warna) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Ukuran</label>
                        <input type="text" name="ukuran" class="form-control form-control-sm" value="{{ old('ukuran', $alat->ukuran) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Unit Kerja Pemilik</label>
                        <input type="text" name="unit_kerja_pemilik" class="form-control form-control-sm" value="{{ old('unit_kerja_pemilik', $alat->unit_kerja_pemilik) }}">
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Kondisi Barang</label>
                        @if($alat->kondisi_barang == 'perbaikan')
                            <input type="hidden" name="kondisi_barang" value="perbaikan">
                            <input type="text" class="form-control form-control-sm bg-light text-danger" value="Sedang Dalam Perbaikan" readonly>
                        @else
                        <select name="kondisi_barang" class="form-select form-select-sm">
                            <option value="baik" {{ old('kondisi_barang', $alat->kondisi_barang) == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak" {{ old('kondisi_barang', $alat->kondisi_barang) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        </select>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Status Barang</label>
                        @if($alat->kondisi_barang == 'perbaikan')
                            <input type="hidden" name="status_barang" value="idle">
                            <input type="text" class="form-control form-control-sm bg-light" value="Idle" readonly>
                        @else
                        <select name="status_barang" class="form-select form-select-sm">
                            <option value="idle" {{ old('status_barang', $alat->status_barang) == 'idle' ? 'selected' : '' }}>Idle</option>
                            <option value="terpakai" {{ old('status_barang', $alat->status_barang) == 'terpakai' ? 'selected' : '' }}>Terpakai</option>
                        </select>
                        @endif
                    </div>
                </div>

                <hr class="my-2">

                <h6 class="fw-bold mb-2 text-dark" style="font-size: 13px;"> Informasi Kalibrasi Terakhir</h6>

                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">No. Sertifikat Kalibrasi / Perijinan</label>
                        <input type="text" name="no_sertifikat" class="form-control form-control-sm @error('no_sertifikat') is-invalid @enderror" value="{{ old('no_sertifikat', optional($kalibrasiTerakhir)->no_sertifikat) }}">
                        @error('no_sertifikat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Jenis Kalibrasi</label>
                        <select name="jenis_kalibrasi" class="form-select form-select-sm @error('jenis_kalibrasi') is-invalid @enderror">
                            <option value="">-- Pilih Jenis Kalibrasi --</option>
                            <option value="eksternal" {{ old('jenis_kalibrasi', optional($kalibrasiTerakhir)->jenis_kalibrasi) == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                            <option value="internal" {{ old('jenis_kalibrasi', optional($kalibrasiTerakhir)->jenis_kalibrasi) == 'internal' ? 'selected' : '' }}>Internal</option>
                        </select>
                        @error('jenis_kalibrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label for="file_sertifikat" class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Upload File / Foto Sertifikat <small class="text-muted">PDF/Gambar</small></label>
                        <input type="file" name="file_sertifikat" id="file_sertifikat" class="form-control form-control-sm @error('file_sertifikat') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text text-muted" style="font-size: 10.5px;">Format: PDF, JPG, JPEG, PNG. Maks 2MB. Kosongkan jika tidak diubah</div>
                        @error('file_sertifikat') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

                        @if(!empty(optional($kalibrasiTerakhir)->file_sertifikat))
                            <div class="mt-1">
                                <a href="{{ asset('storage/' . $kalibrasiTerakhir->file_sertifikat) }}" target="_blank" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 11px;">
                                    <i class="fas fa-file-alt"></i> Lihat Sertifikat Saat Ini
                                </a>
                            </div>
                        @else
                            <div class="mt-1 text-muted" style="font-size: 11px;">
                                <i class="fas fa-info-circle"></i> Belum ada file sertifikat tersimpan
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Lembaga Kalibrasi</label>
                        <input type="text" name="lembaga_kalibrasi" class="form-control form-control-sm @error('lembaga_kalibrasi') is-invalid @enderror" value="{{ old('lembaga_kalibrasi', optional($kalibrasiTerakhir)->lembaga_kalibrasi) }}">
                        @error('lembaga_kalibrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Tanggal Kalibrasi</label>
                        <input type="date" name="tgl_kalibrasi" id="tgl_kalibrasi" class="form-control form-control-sm @error('tgl_kalibrasi') is-invalid @enderror" value="{{ old('tgl_kalibrasi', optional($kalibrasiTerakhir)->tgl_kalibrasi ? \Carbon\Carbon::parse($kalibrasiTerakhir->tgl_kalibrasi)->format('Y-m-d') : '') }}">
                        @error('tgl_kalibrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Interval Kalibrasi</label>
                        <input type="text" name="interval_kalibrasi" id="interval_kalibrasi" class="form-control form-control-sm @error('interval_kalibrasi') is-invalid @enderror" value="{{ old('interval_kalibrasi', optional($kalibrasiTerakhir)->interval_kalibrasi) }}" autocomplete="off">
                        @error('interval_kalibrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Tanggal Berakhirnya Masa Kalibrasi</label>
                        <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control form-control-sm @error('tgl_akhir') is-invalid @enderror" value="{{ old('tgl_akhir', optional($kalibrasiTerakhir)->tgl_akhir ? \Carbon\Carbon::parse($kalibrasiTerakhir->tgl_akhir)->format('Y-m-d') : '') }}">
                        @error('tgl_akhir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Range / Kapasitas</label>
                        <input type="text" name="range_kapasitas" class="form-control form-control-sm @error('range_kapasitas') is-invalid @enderror" value="{{ old('range_kapasitas', optional($kalibrasiTerakhir)->range_kapasitas) }}">
                        @error('range_kapasitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Faktor Koreksi</label>
                        <input type="text" name="faktor_koreksi" class="form-control form-control-sm @error('faktor_koreksi') is-invalid @enderror" value="{{ old('faktor_koreksi', optional($kalibrasiTerakhir)->faktor_koreksi) }}">
                        @error('faktor_koreksi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Signifikan</label>
                        <select name="signifikan" class="form-select form-select-sm @error('signifikan') is-invalid @enderror">
                            <option value="">--Pilih Signifikan--</option>
                            <option value="ya" {{ old('signifikan', optional($kalibrasiTerakhir)->signifikan) == 'ya' ? 'selected' : '' }}>Ya</option>
                            <option value="tidak" {{ old('signifikan', optional($kalibrasiTerakhir)->signifikan) == 'tidak' ? 'selected' : '' }}>Tidak</option>
                        </select>
                        @error('signifikan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-12">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Catatan / Evaluasi Kalibrasi</label>
                        <textarea name="catatan_evaluasi" class="form-control form-control-sm" rows="2" placeholder="Tuliskan catatan evaluasi atau hasil analisis alat di sini...">{{ old('catatan_evaluasi', optional($kalibrasiTerakhir)->catatan_evaluasi) }}</textarea>
                    </div>
                </div>

                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-sm text-white px-3" style="background-color: #1b3152;"><i class="fas fa-save me-1"></i> Perbarui</button>
                    <a href="{{ route('alat.index') }}" class="btn btn-sm btn-secondary px-3">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const tglKalibrasiInput = document.getElementById('tgl_kalibrasi');
    const intervalInput = document.getElementById('interval_kalibrasi');
    const tglAkhirInput = document.getElementById('tgl_akhir');
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
@endpush
@endsection