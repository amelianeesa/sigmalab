@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Data Alat & Kalibrasi</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('alat.index') }}">Alat</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-edit me-1"></i> Form Ubah Data Alat & Kalibrasi</div>
        <div class="card-body">
            <form action="{{ route('alat.update', $alat->alat_id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h5 class="text-primary mb-3"><i class="fas fa-tools"></i> 1. Informasi Spesifikasi Alat</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode Alat (CODE) <span class="text-danger">*</span></label>
                        <input type="text" name="kode_alat" class="form-control bg-light @error('kode_alat') is-invalid @enderror" value="{{ old('kode_alat', $alat->kode_alat) }}" required readonly>
                        @error('kode_alat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Barang / Alat <span class="text-danger">*</span></label>
                        <input type="text" name="nama_alat" class="form-control @error('nama_alat') is-invalid @enderror" value="{{ old('nama_alat', $alat->nama_alat) }}" required>
                        @error('nama_alat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Merk / Tipe</label>
                        <input type="text" name="merk_tipe" class="form-control" value="{{ old('merk_tipe', $alat->merk_tipe) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Serial Number</label>
                        <input type="text" name="no_seri" class="form-control" value="{{ old('no_seri', $alat->no_seri) }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Warna</label>
                        <input type="text" name="warna" class="form-control" value="{{ old('warna', $alat->warna) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Ukuran</label>
                        <input type="text" name="ukuran" class="form-control" value="{{ old('ukuran', $alat->ukuran) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Unit Kerja Pemilik</label>
                        <input type="text" name="unit_kerja_pemilik" class="form-control" value="{{ old('unit_kerja_pemilik', $alat->unit_kerja_pemilik) }}">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Kondisi Barang</label>
                        <select name="kondisi_barang" class="form-select">
                            <option value="baik" {{ old('kondisi_barang', $alat->kondisi_barang) == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak" {{ old('kondisi_barang', $alat->kondisi_barang) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status Barang</label>
                        <select name="status_barang" class="form-select">
                            <option value="idle" {{ old('status_barang', $alat->status_barang) == 'idle' ? 'selected' : '' }}>Idle</option>
                            <option value="terpakai" {{ old('status_barang', $alat->status_barang) == 'terpakai' ? 'selected' : '' }}>Terpakai</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="text-primary mb-3"><i class="fas fa-certificate"></i> 2. Informasi Kalibrasi Terakhir</h5>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">No. Sertifikat Kalibrasi / Perijinan</label>
                        <input type="text" name="no_sertifikat" class="form-control @error('no_sertifikat') is-invalid @enderror" value="{{ old('no_sertifikat', optional($kalibrasiTerakhir)->no_sertifikat) }}">
                        @error('no_sertifikat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Kalibrasi</label>
                        <select name="jenis_kalibrasi" class="form-select @error('jenis_kalibrasi') is-invalid @enderror">
                            <option value="">-- Pilih Jenis Kalibrasi --</option>
                            <option value="eksternal" {{ old('jenis_kalibrasi', optional($kalibrasiTerakhir)->jenis_kalibrasi) == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                            <option value="internal" {{ old('jenis_kalibrasi', optional($kalibrasiTerakhir)->jenis_kalibrasi) == 'internal' ? 'selected' : '' }}>Internal</option>
                        </select>
                        @error('jenis_kalibrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Kalibrasi</label>
                        <input type="date" name="tgl_kalibrasi" id="tgl_kalibrasi" class="form-control @error('tgl_kalibrasi') is-invalid @enderror" value="{{ old('tgl_kalibrasi', optional($kalibrasiTerakhir)->tgl_kalibrasi) }}">
                        @error('tgl_kalibrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Interval Kalibrasi</label>
                        <input type="text" name="interval_kalibrasi" id="interval_kalibrasi" class="form-control @error('interval_kalibrasi') is-invalid @enderror" value="{{ old('interval_kalibrasi', optional($kalibrasiTerakhir)->interval_kalibrasi) }}" autocomplete="off">
                        @error('interval_kalibrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Berakhirnya Masa Kalibrasi</label>
                        <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control @error('tgl_akhir') is-invalid @enderror" value="{{ old('tgl_akhir', optional($kalibrasiTerakhir)->tgl_akhir) }}">
                        @error('tgl_akhir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Lembaga Kalibrasi</label>
                        <input type="text" name="lembaga_kalibrasi" class="form-control @error('lembaga_kalibrasi') is-invalid @enderror" value="{{ old('lembaga_kalibrasi', optional($kalibrasiTerakhir)->lembaga_kalibrasi) }}">
                        @error('lembaga_kalibrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Range / Kapasitas</label>
                        <input type="text" name="range_kapasitas" class="form-control @error('range_kapasitas') is-invalid @enderror" value="{{ old('range_kapasitas', optional($kalibrasiTerakhir)->range_kapasitas) }}">
                        @error('range_kapasitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Faktor Koreksi</label>
                        <input type="text" name="faktor_koreksi" class="form-control @error('faktor_koreksi') is-invalid @enderror" value="{{ old('faktor_koreksi', optional($kalibrasiTerakhir)->faktor_koreksi) }}">
                        @error('faktor_koreksi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Signifikan</label>
                        <select name="signifikan" class="form-select @error('signifikan') is-invalid @enderror">
                            <option value="tidak" {{ old('signifikan', optional($kalibrasiTerakhir)->signifikan) == 'tidak' ? 'selected' : '' }}>Tidak</option>
                            <option value="ya" {{ old('signifikan', optional($kalibrasiTerakhir)->signifikan) == 'ya' ? 'selected' : '' }}>Ya</option>
                        </select>
                        @error('signifikan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label">Catatan / Evaluasi Kalibrasi</label>
                        <textarea name="catatan_evaluasi" class="form-control" rows="3" placeholder="Tuliskan catatan evaluasi atau hasil analisis alat di sini...">{{ old('catatan_evaluasi', optional($kalibrasiTerakhir)->catatan_evaluasi) }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui Data Alat & Kalibrasi</button>
                    <a href="{{ route('alat.index') }}" class="btn btn-secondary">Kembali</a>
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