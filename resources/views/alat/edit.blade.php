@extends('layouts.app')
@section('title', 'Edit Data - Alat')

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
    /* Toggle "Dapat dipinjam": seluruh kotak bisa diklik, tinggi sejajar dengan select di sebelahnya */
    .alat-pinjam-box {
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 31px;
        margin: 0;
        padding: 4px 10px;
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: border-color .15s ease, background-color .15s ease;
    }
    .alat-pinjam-box:hover { border-color: #adb5bd; }
    .alat-pinjam-box:has(input:checked) { border-color: #1b3152; background: #f1f5fb; }
    .alat-pinjam-box .form-switch { display: flex; align-items: center; margin: 0; padding: 0; min-height: 0; }
    .alat-pinjam-box .form-check-input { float: none; margin: 0; flex-shrink: 0; cursor: pointer; }
    .alat-pinjam-text { display: flex; flex-direction: column; line-height: 1.2; min-width: 0; }
    .alat-pinjam-title { font-size: 11.5px; font-weight: 700; color: #333; }
    .alat-pinjam-hint { font-size: 10.5px; color: #6c757d; }
</style>
<div class="container-fluid pt-0 pb-4 px-4" style="max-width: 1050px;">
    <div class="pt-2 mb-2">
        <h5 class="fw-bold mb-1" style="font-size: 1.2rem; color: #333;">Edit Data Alat & Kalibrasi</h5>
    </div>

    <div class="card shadow-sm border-0 mb-4">
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
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Kondisi Barang</label>
                        @if($alat->kondisi_barang == 'perbaikan')
                            <input type="hidden" name="kondisi_barang" value="perbaikan">
                            <input type="text" class="form-control form-control-sm bg-light text-danger" value="Sedang Dalam Perbaikan" readonly>
                        @else
                        <select name="kondisi_barang" class="form-select form-select-sm select2-basic">
                            <option value="baik" {{ old('kondisi_barang', $alat->kondisi_barang) == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak" {{ old('kondisi_barang', $alat->kondisi_barang) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        </select>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Status Barang</label>
                        @if($alat->kondisi_barang == 'perbaikan')
                            <input type="hidden" name="status_barang" value="idle">
                            <input type="text" class="form-control form-control-sm bg-light" value="Idle" readonly>
                        @else
                        <select name="status_barang" class="form-select form-select-sm select2-basic">
                            <option value="idle" {{ old('status_barang', $alat->status_barang) == 'idle' ? 'selected' : '' }}>Idle</option>
                            <option value="terpakai" {{ old('status_barang', $alat->status_barang) == 'terpakai' ? 'selected' : '' }}>Terpakai</option>
                        </select>
                        @endif
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Peminjaman Alat</label>
                        <label class="alat-pinjam-box" for="dapatDipinjam">
                            <span class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="dapat_dipinjam" value="1" id="dapatDipinjam" {{ old('dapat_dipinjam', $alat->dapat_dipinjam) ? 'checked' : '' }}>
                            </span>
                            <span class="alat-pinjam-text">
                                <span class="alat-pinjam-title">Dapat dipinjam</span>
                                <span class="alat-pinjam-hint">Tampil di menu Peminjaman Alat (workspace Non-Lab)</span>
                            </span>
                        </label>
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
                        <select name="jenis_kalibrasi" class="form-select form-select-sm select2-basic @error('jenis_kalibrasi') is-invalid @enderror">
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
                        <input type="text" name="tgl_kalibrasi" id="tgl_kalibrasi" class="form-control form-control-sm flatpickr-date @error('tgl_kalibrasi') is-invalid @enderror" autocomplete="off" value="{{ old('tgl_kalibrasi', optional($kalibrasiTerakhir)->tgl_kalibrasi ? \Carbon\Carbon::parse($kalibrasiTerakhir->tgl_kalibrasi)->format('Y-m-d') : '') }}">
                        @error('tgl_kalibrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Interval Kalibrasi</label>
                        <input type="text" name="interval_kalibrasi" id="interval_kalibrasi" class="form-control form-control-sm @error('interval_kalibrasi') is-invalid @enderror" value="{{ old('interval_kalibrasi', optional($kalibrasiTerakhir)->interval_kalibrasi) }}" autocomplete="off">
                        @error('interval_kalibrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold mb-1" style="font-size: 11.5px;">Tanggal Berakhirnya Masa Kalibrasi</label>
                        <input type="text" name="tgl_akhir" id="tgl_akhir" class="form-control form-control-sm flatpickr-date @error('tgl_akhir') is-invalid @enderror" autocomplete="off" value="{{ old('tgl_akhir', optional($kalibrasiTerakhir)->tgl_akhir ? \Carbon\Carbon::parse($kalibrasiTerakhir->tgl_akhir)->format('Y-m-d') : '') }}">
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
                        <select name="signifikan" class="form-select form-select-sm select2-basic @error('signifikan') is-invalid @enderror">
                            <option value="">--Pilih Signifikan--</option>
                            <option value="ya" {{ old('signifikan', optional($kalibrasiTerakhir)->signifikan) == 'ya' ? 'selected' : '' }}>Ya</option>
                            <option value="tidak" {{ old('signifikan', optional($kalibrasiTerakhir)->signifikan) == 'tidak' ? 'selected' : '' }}>Tidak</option>
                        </select>
                        @error('signifikan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-12">
                        <div class="p-2 bg-light border rounded text-muted" style="font-size: 11.5px;">
                            <i class="fas fa-info-circle me-1" style="color: #1b3152;"></i> 
                            Evaluasi dan catatan keputusan dikelola sub menu terpusat.
                            <a href="{{ route('evaluasi-kalibrasi.index') }}" class="text-decoration-none fw-semibold text-primary ms-1">
                                Evaluasi Kalibrasi <i class="fas fa-external-link-alt" style="font-size: 10px;"></i>
                            </a>
                        </div>
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
});
</script>
@endpush
@endsection