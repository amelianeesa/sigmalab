@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah Alat & Informasi Kalibrasi</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('alat.index') }}">Alat</a></li>
        <li class="breadcrumb-item active">Tambah</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-plus-circle me-1"></i> Form Input Data Master & Kalibrasi</div>
        <div class="card-body">
            <form action="{{ route('alat.store') }}" method="POST">
                @csrf
                
                <h5 class="text-primary mb-3"><i class="fas fa-tools"></i> Informasi Spesifikasi Alat</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode Alat (CODE) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" id="kode_alat_input" name="kode_alat" class="form-control @error('kode_alat') is-invalid @enderror" placeholder="mis. CLC1204-10001" value="{{ old('kode_alat') }}" required>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#scannerModal" title="Scan Barcode">
                                <i class="fas fa-qrcode"></i> Scan
                            </button>
                        </div>
                        @error('kode_alat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Barang / Alat <span class="text-danger">*</span></label>
                        <input type="text" name="nama_alat" class="form-control @error('nama_alat') is-invalid @enderror" placeholder="mis. Sulfur Analyzer" value="{{ old('nama_alat') }}" required>
                        @error('nama_alat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Merk / Tipe</label>
                        <input type="text" name="merk_tipe" class="form-control" placeholder="mis. Labfit CS 1232" value="{{ old('merk_tipe') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Serial Number</label>
                        <input type="text" name="no_seri" class="form-control" placeholder="mis. 17050068" value="{{ old('no_seri') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Warna</label>
                        <input type="text" name="warna" class="form-control" placeholder="mis. WHITE" value="{{ old('warna') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Ukuran</label>
                        <input type="text" name="ukuran" class="form-control" placeholder="Ukuran" value="{{ old('ukuran') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Unit Kerja Pemilik</label>
                        <input type="text" name="unit_kerja_pemilik" class="form-control" placeholder="Nama Unit Kerja" value="{{ old('unit_kerja_pemilik') }}">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Kondisi Barang</label>
                        <select name="kondisi_barang" class="form-select" required>
                            <option value="">--Pilih Kondisi--</option>
                            <option value="baik" {{ old('kondisi_barang') == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak" {{ old('kondisi_barang') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status Barang</label>
                        <select name="status_barang" class="form-select" required>
                            <option value="">--Pilih Status--</option>
                            <option value="idle" {{ old('status_barang') == 'idle' ? 'selected' : '' }}>Idle</option>
                            <option value="terpakai" {{ old('status_barang') == 'terpakai' ? 'selected' : '' }}>Terpakai</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="text-primary mb-3"><i class="fas fa-certificate"></i> Informasi Kalibrasi Terakhir</h5>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">No. Sertifikat Kalibrasi / Perijinan</label>
                        <input type="text" name="no_sertifikat" class="form-control" placeholder="mis. 20059/ENBPAQ" value="{{ old('no_sertifikat') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Kalibrasi</label>
                        <select name="jenis_kalibrasi" class="form-select">
                            <option value="">--Pilih Jenis Kalibrasi--</option>
                            <option value="eksternal" {{ old('jenis_kalibrasi') == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                            <option value="internal" {{ old('jenis_kalibrasi') == 'internal' ? 'selected' : '' }}>Internal</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Kalibrasi</label>
                        <input type="date" name="tgl_kalibrasi" id="tgl_kalibrasi" class="form-control" value="{{ old('tgl_kalibrasi') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Interval Kalibrasi</label>
                        <input type="text" name="interval_kalibrasi" id="interval_kalibrasi" class="form-control" placeholder="Contoh: 1 Tahun" value="{{ old('interval_kalibrasi') }}" autocomplete="off">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Berakhirnya Masa Kalibrasi</label>
                        <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control" value="{{ old('tgl_akhir') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Lembaga Kalibrasi</label>
                        <input type="text" name="lembaga_kalibrasi" class="form-control" placeholder="mis. PT SUCOFINDO CILACAP" value="{{ old('lembaga_kalibrasi') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Range / Kapasitas</label>
                        <input type="text" name="range_kapasitas" class="form-control" placeholder="mis. 0 - 1400 °C" value="{{ old('range_kapasitas') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Faktor Koreksi</label>
                        <input type="text" name="faktor_koreksi" class="form-control" placeholder="mis. 32 °C" value="{{ old('faktor_koreksi') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Signifikan</label>
                        <select name="signifikan" class="form-select">
                            <option value="tidak" {{ old('signifikan') == 'tidak' ? 'selected' : '' }}>Tidak</option>
                            <option value="ya" {{ old('signifikan') == 'ya' ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label">Catatan / Evaluasi Kalibrasi</label>
                        <textarea name="catatan_evaluasi" class="form-control" rows="3" placeholder="Tuliskan catatan evaluasi atau hasil analisis alat di sini...">{{ old('catatan_evaluasi') }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data Alat & Kalibrasi</button>
                    <a href="{{ route('alat.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scanner Modal -->
<div class="modal fade" id="scannerModal" tabindex="-1" aria-labelledby="scannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fs-6" id="scannerModalLabel"><i class="fas fa-qrcode me-2"></i>Scan Barcode / QR Code</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="reader" width="100%"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const scannerModal = document.getElementById('scannerModal');
    let html5QrcodeScanner = null;

    scannerModal.addEventListener('shown.bs.modal', function () {
        html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: {width: 250, height: 250} }, false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    });

    scannerModal.addEventListener('hidden.bs.modal', function () {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear().catch(error => console.error("Failed to clear scanner. ", error));
        }
    });

    function onScanSuccess(decodedText) {
        document.getElementById('kode_alat_input').value = decodedText;
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear();
        }
        bootstrap.Modal.getInstance(scannerModal).hide();
    }

    function onScanFailure(error) {}

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