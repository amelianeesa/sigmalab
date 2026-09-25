@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<style>
    .select2-container--bootstrap-5 .select2-selection {
        font-size: 0.8rem !important;
        min-height: 31px !important;
    }
    .select2-container--bootstrap-5 .select2-selection__rendered {
        padding-top: 0 !important;
    }
    .select2-container--bootstrap-5 .select2-results__option {
        font-size: 0.82rem !important;
    }
    .flatpickr-input {
        background-color: #fff !important;
    }
</style>
<div class="container-fluid pt-0 pb-4 px-4" style="max-width: 1050px;">
    <div class="pt-2 mb-2">
        <h5 class="fw-bold mb-1" style="font-size: 1.2rem; color: #333;">Tambah Alat & Informasi Kalibrasi</h5>
        <ol class="breadcrumb mb-0" style="font-size: 12px;">
            <li class="breadcrumb-item"><a href="{{ route('alat.index') }}" class="text-decoration-none">Data Alat & Kalibrasi</a></li>
            <li class="breadcrumb-item text-muted active">Tambah</li>
        </ol>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header text-white py-1.5 px-3" style="background-color: #1b3152;">
            <h6 class="mb-0 fw-semibold" style="font-size: 13px;"> Form Input Data Master & Kalibrasi</h6>
        </div>
        <div class="card-body px-3 py-2.5">
            <form action="{{ route('alat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <h6 class="fw-bold mb-2 text-dark" style="font-size: 13px;">Informasi Spesifikasi Alat</h6>
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Kode Alat (CODE) <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="kode_alat_input" name="kode_alat" class="form-control form-control-sm @error('kode_alat') is-invalid @enderror" placeholder="mis. CLC1204-10001" value="{{ old('kode_alat') }}">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#scannerModal" title="Scan Barcode">
                                 Scan
                            </button>
                        </div>
                        @error('kode_alat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Nama Barang / Alat <span class="text-danger">*</span></label>
                        <input type="text" name="nama_alat" class="form-control form-control-sm @error('nama_alat') is-invalid @enderror" placeholder="mis. Sulfur Analyzer" value="{{ old('nama_alat') }}" required>
                        @error('nama_alat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>    

                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">No. Inventaris</label>
                        <input type="text" name="no_inventaris" class="form-control form-control-sm @error('no_inventaris') is-invalid @enderror" placeholder="mis. INV-001" value="{{ old('no_inventaris') }}">
                        @error('no_inventaris') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Merk / Tipe</label>
                        <input type="text" name="merk_tipe" class="form-control form-control-sm" placeholder="mis. Labfit CS 1232" value="{{ old('merk_tipe') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Serial Number</label>
                        <input type="text" name="no_seri" class="form-control form-control-sm" placeholder="mis. 17050068" value="{{ old('no_seri') }}">
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Warna</label>
                        <input type="text" name="warna" class="form-control form-control-sm" placeholder="mis. WHITE" value="{{ old('warna') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Ukuran</label>
                        <input type="text" name="ukuran" class="form-control form-control-sm" placeholder="Ukuran" value="{{ old('ukuran') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Unit Kerja Pemilik</label>
                        <input type="text" name="unit_kerja_pemilik" class="form-control form-control-sm" placeholder="Nama Unit Kerja" value="{{ old('unit_kerja_pemilik') }}">
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Kondisi Barang</label>
                        <select name="kondisi_barang" class="form-select form-select-sm select2-basic" required>
                            <option value="">--Pilih Kondisi--</option>
                            <option value="baik" {{ old('kondisi_barang') == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak" {{ old('kondisi_barang') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Status Barang</label>
                        <select name="status_barang" class="form-select form-select-sm select2-basic" required>
                            <option value="">--Pilih Status--</option>
                            <option value="idle" {{ old('status_barang') == 'idle' ? 'selected' : '' }}>Idle</option>
                            <option value="terpakai" {{ old('status_barang') == 'terpakai' ? 'selected' : '' }}>Terpakai</option>
                        </select>
                    </div>
                </div>

                <hr class="my-2">

                <h6 class="fw-bold mb-2 text-dark" style="font-size: 13px;">Informasi Kalibrasi Terakhir</h6>
                
                <div class="alert d-flex align-items-center mb-2 bg-white shadow-sm py-1.5 px-3" style="border-left: 4px solid #1b3152 !important;">
                    
                    <div class="w-100">
                        <h6 class="mb-0 fw-bold small text-dark" style="font-size: 11.5px;">Auto-Fill dari Sertifikat (OCR)</h6>
                        <p class="mb-1 text-muted" style="font-size: 11px;">Unggah dokumen PDF sertifikat kalibrasi untuk mengisi form secara otomatis.</p>
                        <div class="input-group input-group-sm w-75">
                            <input type="file" class="form-control form-control-sm" id="sertifikat_ocr" accept=".pdf">
                            <button class="btn btn-sm text-white" type="button" id="btn_ocr_scan" style="background-color: #1b3152;"><i class="fas fa-search me-1"></i> Pindai</button>
                        </div>
                        <small class="text-danger d-none mt-1" id="ocr_error"></small>
                        <small class="text-success d-none mt-1" id="ocr_success"></small>
                    </div>
                </div>
                
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">No. Sertifikat Kalibrasi / Perijinan</label>
                        <input type="text" name="no_sertifikat" class="form-control form-control-sm" placeholder="mis. 20059/ENBPAQ" value="{{ old('no_sertifikat') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Jenis Kalibrasi</label>
                        <select name="jenis_kalibrasi" class="form-select form-select-sm select2-basic">
                            <option value="">--Pilih Jenis Kalibrasi--</option>
                            <option value="eksternal" {{ old('jenis_kalibrasi') == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                            <option value="internal" {{ old('jenis_kalibrasi') == 'internal' ? 'selected' : '' }}>Internal</option>
                        </select>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label for="file_sertifikat" class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Upload File / Foto Sertifikat Kalibrasi <small class="text-muted">(PDF/Gambar)</small></label>
                        <input type="file" name="file_sertifikat" id="file_sertifikat" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text text-muted" style="font-size: 10.5px;">Format: PDF, JPG, JPEG, PNG. Maks: 2MB</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Lembaga Kalibrasi</label>
                        <input type="text" name="lembaga_kalibrasi" class="form-control form-control-sm" placeholder="mis. PT SUCOFINDO" value="{{ old('lembaga_kalibrasi') }}">
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Tanggal Kalibrasi</label>
                        <input type="text" name="tgl_kalibrasi" id="tgl_kalibrasi" class="form-control form-control-sm flatpickr-date" autocomplete="off" value="{{ old('tgl_kalibrasi') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Interval Kalibrasi</label>
                        <input type="text" name="interval_kalibrasi" id="interval_kalibrasi" class="form-control form-control-sm" placeholder="mis. 1 Tahun" value="{{ old('interval_kalibrasi') }}" autocomplete="off">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Tanggal Berakhirnya Masa Kalibrasi</label>
                        <input type="text" name="tgl_akhir" id="tgl_akhir" class="form-control form-control-sm flatpickr-date" autocomplete="off" value="{{ old('tgl_akhir') }}">
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Range / Kapasitas</label>
                        <input type="text" name="range_kapasitas" class="form-control form-control-sm" placeholder="mis. 0 - 1400 °C" value="{{ old('range_kapasitas') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Faktor Koreksi</label>
                        <input type="text" name="faktor_koreksi" class="form-control form-control-sm" placeholder="mis. 32 °C" value="{{ old('faktor_koreksi') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Signifikan</label>
                        <select name="signifikan" class="form-select form-select-sm select2-basic">
                            <option value="">--Pilih Signifikan--</option>
                            <option value="ya" {{ old('signifikan') == 'ya' ? 'selected' : '' }}>Ya</option>
                            <option value="tidak" {{ old('signifikan') == 'tidak' ? 'selected' : '' }}>Tidak</option>
                        </select>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-12">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Catatan / Evaluasi Kalibrasi</label>
                        <textarea name="catatan_evaluasi" class="form-control form-control-sm" rows="2" placeholder="Tuliskan catatan evaluasi atau hasil analisis alat di sini...">{{ old('catatan_evaluasi') }}</textarea>
                    </div>
                </div>

                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-sm text-white px-3" style="background-color: #1b3152;"><i class="fas fa-save me-1"></i> Simpan</button>
                    <a href="{{ route('alat.index') }}" class="btn btn-sm btn-secondary px-3">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="scannerModal" tabindex="-1" aria-labelledby="scannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white py-2" style="background-color: #1b3152;">
                <h5 class="modal-title fs-6" id="scannerModalLabel"><i class="fas fa-qrcode me-2"></i>Scan Barcode / QR Code</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="reader" width="100%"></div>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
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
});
</script>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
$(function () {
    $('.select2-basic').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    flatpickr.localize(flatpickr.l10ns.id);
    $('.flatpickr-date').flatpickr({
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd F Y',
        allowInput: true,
        disableMobile: true
    });

    // Helper: set nilai input tanggal dengan aman, baik dia sudah jadi flatpickr atau belum
    function setDateValue(input, value) {
        if (input._flatpickr) {
            input._flatpickr.setDate(value || null, true);
        } else {
            input.value = value || '';
        }
    }

    function setMinDate(input, value) {
        if (input._flatpickr) {
            input._flatpickr.set('minDate', value || null);
        }
    }

    const tglKalibrasiInput = document.getElementById('tgl_kalibrasi');
    const intervalInput = document.getElementById('interval_kalibrasi');
    const tglAkhirInput = document.getElementById('tgl_akhir');
    let isManualEdit = false;

    function updateMinTanggalAkhir() {
        if (tglKalibrasiInput.value) {
            setMinDate(tglAkhirInput, tglKalibrasiInput.value);
            if (tglAkhirInput.value && tglAkhirInput.value < tglKalibrasiInput.value) {
                setDateValue(tglAkhirInput, '');
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

        setDateValue(tglAkhirInput, `${tahun}-${bulan}-${hari}`);
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
            Swal.fire({
                icon: 'warning',
                title: 'Tanggal tidak valid',
                text: 'Tanggal berakhir tidak boleh lebih awal dari tanggal kalibrasi!'
            });
            setDateValue(tglAkhirInput, '');
            return;
        }
        hitungInterval();
    });

    updateMinTanggalAkhir();

    // ===== OCR Auto-Fill =====
    document.getElementById('btn_ocr_scan').addEventListener('click', function() {
        let fileInput = document.getElementById('sertifikat_ocr');
        if (!fileInput.files.length) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silakan pilih file PDF Sertifikat Kalibrasi terlebih dahulu.',
            });
            return;
        }

        let formData = new FormData();
        formData.append('sertifikat', fileInput.files[0]);
        formData.append('_token', '{{ csrf_token() }}');

        let btn = this;
        let originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memindai...';
        btn.disabled = true;
        
        let errorEl = document.getElementById('ocr_error');
        let successEl = document.getElementById('ocr_success');
        errorEl.classList.add('d-none');
        successEl.classList.add('d-none');

        fetch('{{ route('alat.parse-sertifikat') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
            
            if (data.success) {
                if (data.data.tgl_kalibrasi) setDateValue(tglKalibrasiInput, data.data.tgl_kalibrasi);
                if (data.data.tgl_akhir) setDateValue(tglAkhirInput, data.data.tgl_akhir);
                if (data.data.sertifikat_oleh) {
                    let lembaga = document.querySelector('input[name="lembaga_kalibrasi"]');
                    if (lembaga) lembaga.value = data.data.sertifikat_oleh;
                }

                updateMinTanggalAkhir();
                
                successEl.textContent = 'Berhasil membaca dokumen! Form telah diisi.';
                successEl.classList.remove('d-none');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Auto-Fill Berhasil',
                    text: 'Data berhasil diekstrak dari dokumen PDF. Silakan periksa kembali.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000
                });
            } else {
                errorEl.textContent = data.message;
                errorEl.classList.remove('d-none');
            }
        })
        .catch(err => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
            errorEl.textContent = 'Terjadi kesalahan sistem saat menghubungi server.';
            errorEl.classList.remove('d-none');
        });
    });
});
</script>
@endpush
@endsection