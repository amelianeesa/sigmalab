@extends('layouts.app')

@push('styles')
<style>
    .dashboard-container {
        padding: 10px 20px !important;
    }
    .bg-sesi-pagi {
        background-color: #e3f2fd !important; 
    }
    .bg-sesi-sore {
        background-color: #e8f5e9 !important; 
    }
    .select2-results__options {
        max-height: 160px !important;
        overflow-y: auto !important;
    }
    .table td input.form-control, .table td input.form-control-sm {
        min-width: 55px;
    }
    .card-body {
        padding: 10px !important;
    }

    .table-responsive[style*="max-height"] {
        max-height: 110px !important; 
    }
    .select2-container--bootstrap-5 .select2-dropdown {
        top: 100% !important;
        bottom: auto !important;
        margin-top: 2px !important;
    }

    .select2-container--bootstrap-5 .select2-dropdown .select2-search {
        display: none !important;
    }
    .select2-container--bootstrap-5 .select2-search--dropdown {
        padding: 0 !important;
        display: none !important;
    }

    input[type="date"].form-control-sm {
        padding: 2px 6px !important;
        font-size: 0.72rem !important;
    }
    #tabelTemperature th, #tabelTemperature td,
    #tabelHumidity th, #tabelHumidity td {
        padding: 2px 4px !important;
        font-size: 0.68rem !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid dashboard-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                 Pencatatan Monitoring Suhu dan Kelembaban Udara
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 0.75rem;">
                    <li class="breadcrumb-item"><a href="{{ url('alat') }}" class="text-decoration-none">Data Alat & Kalibrasi</a></li>
                    <li class="breadcrumb-item active text-muted" aria-current="page">Monitoring Ruangan</li>
                </ol>
            </nav>
        </div>    
        <div>
            <a href="{{ route('inventori.monitoring.index') }}" class="btn text-white btn-sm py-1 px-2 me-2 shadow-sm" style="font-size: 0.72rem; background-color: #1b3152;"><i class="fas fa-sync-alt me-1"></i> Refresh Data</a>
            @if(isset($alatId) && $alatId && isset($ruangan) && $ruangan)
                <a href="{{ route('inventori.monitoring.exportPdf', ['alat_id' => $alatId, 'nama_ruangan' => $ruangan, 'bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank" class="btn btn-danger btn-sm py-1 px-2" style="font-size: 0.72rem;"><i class="fas fa-file-pdf me-1"></i> Unduh Rekapan PDF</a>
            @endif
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3 bg-light">
        <div class="card-body p-2">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.85rem;"><i class="fas fa-sliders-h text-primary me-2"></i>Manajemen Titik Acuan Kalibrasi & Dokumen Referensi</h6>
                    <p class="text-muted small mb-0" style="font-size: 0.7rem;">Klik tombol di sebelah kanan untuk membuka atau menyembunyikan detail titik acuan dan dokumen.</p>
                </div>    
                <button class="btn btn-outline-secondary btn-sm fw-bold px-2 py-1 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTitikAcuan" aria-expanded="false" aria-controls="collapseTitikAcuan" style="font-size: 0.7rem;">
                    <i class="fas fa-chevron-down me-1"></i> Sembunyikan / Tampilkan
                </button>
            </div>
            <div class="collapse show mt-2" id="collapseTitikAcuan">
                @if(isset($alatAktif) && $alatAktif)
                    @php 
                        $idAlat = $alatAktif->alat_id ?? $alatAktif->id; 
                        $hasAnyKalibrasi = isset($titikKalibrasiList) && count($titikKalibrasiList) > 0;

                        $tglKalibVal = $alatAktif->tanggal_kalibrasi ?? date('Y-m-d');
                        $tglExpVal   = $alatAktif->tanggal_expired ?? date('Y-m-d');

                        $isExpired   = $alatAktif->tanggal_expired && \Carbon\Carbon::now()->gt($alatAktif->tanggal_expired);
                    @endphp
                    <form action="{{ route('inventori.monitoring.storeKalibrasi', $idAlat) }}" method="POST">
                        @csrf
                        <div class="bg-white p-2 rounded border shadow-sm mb-2">
                            <div class="row align-items-center g-2">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary mb-1" style="font-size: 10px;"><i class="fas fa-calendar-alt text-primary me-1"></i> TANGGAL KALIBRASI ALAT</label>
                                    <input type="date" name="tanggal_kalibrasi" value="{{ $tglKalibVal }}" class="form-control form-control-sm bg-light fw-bold text-dark py-1" style="font-size: 0.75rem;" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary mb-1" style="font-size: 10px;"><i class="fas fa-hourglass-end text-danger me-1"></i> MASA BERLAKU (EXPIRED DATE)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="date" name="tanggal_expired" value="{{ $tglExpVal }}" class="form-control form-control-sm bg-light fw-bold text-dark py-1" style="font-size: 0.75rem;" readonly>
                                        @if($alatAktif->tanggal_expired)
                                            @if($isExpired)
                                                <span class="badge bg-danger d-flex align-items-center px-2" style="font-size: 9px;"><i class="fas fa-exclamation-triangle me-1"></i> EXPIRED</span>
                                            @else
                                                <span class="badge bg-success d-flex align-items-center px-2" style="font-size: 9px;"><i class="fas fa-check-circle me-1"></i> AKTIF</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="card border shadow-sm">
                                    <div class="card-header bg-warning bg-opacity-10 py-1 fw-bold text-dark small text-center" style="font-size: 0.75rem;">
                                        TEMPERATURE (Suhu)
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="table-responsive bg-white rounded mb-2" style="max-height: 150px; overflow-y: auto;">
                                            <table class="table table-sm table-bordered text-center align-middle mb-0" id="tabelTemperature" style="font-size: 0.7rem;">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Equipment Reading</th>
                                                        <th>Standard Reading</th>
                                                        <th style="width: 35px;">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="align-middle">
                                                    @php $hasTemp = false; @endphp
                                                    @if(isset($titikKalibrasiList) && count($titikKalibrasiList) > 0)
                                                        @foreach($titikKalibrasiList as $titik)
                                                            @if(strtolower($titik->kategori) == 'temperature')
                                                                @php $hasTemp = true; @endphp
                                                                <tr>
                                                                    <td>{{ $titik->equipment_reading }}</td>
                                                                    <td>{{ $titik->standard_reading }}</td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-sm text-danger p-0 border-0 bg-transparent btn-hapus-titik" data-id="{{ $titik->titik_kalibrasi_id }}" title="Hapus"><i class="fas fa-trash"></i></button>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                    @if(!$hasTemp)
                                                    <tr>
                                                        <td><input type="number" step="0.01" name="temperature_equipment[]" class="form-control form-control-sm text-center py-0" placeholder="0.00" style="font-size: 0.7rem;"></td>
                                                        <td><input type="number" step="0.01" name="temperature_standard[]" class="form-control form-control-sm text-center py-0" placeholder="0.00" style="font-size: 0.7rem;"></td>
                                                        <td><button type="button" class="btn btn-sm text-danger p-0 border-0 bg-transparent hapus-baris"><i class="fas fa-times"></i></button></td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" id="tambahTemperature" class="btn btn-outline-warning btn-sm w-100 text-dark fw-bold py-1" style="font-size: 0.7rem;"><i class="fas fa-plus me-1"></i> Tambah Baris Temperature</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border shadow-sm">
                                    <div class="card-header bg-success bg-opacity-10 py-1 fw-bold text-dark small text-center" style="font-size: 0.75rem;">
                                        HUMIDITY (Kelembaban)
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="table-responsive bg-white rounded mb-2" style="max-height: 150px; overflow-y: auto;">
                                            <table class="table table-sm table-bordered text-center align-middle mb-0" id="tabelHumidity" style="font-size: 0.7rem;">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Equipment Reading</th>
                                                        <th>Standard Reading</th>
                                                        <th style="width: 35px;">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="align-middle">
                                                    @php $hasHumidity = false; @endphp
                                                    @if(isset($titikKalibrasiList) && count($titikKalibrasiList) > 0)
                                                        @foreach($titikKalibrasiList as $titik)
                                                            @if(strtolower($titik->kategori) == 'humidity')
                                                                @php $hasHumidity = true; @endphp
                                                                <tr>
                                                                    <td>{{ $titik->equipment_reading }}</td>
                                                                    <td>{{ $titik->standard_reading }}</td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-sm text-danger p-0 border-0 bg-transparent btn-hapus-titik" data-id="{{ $titik->titik_kalibrasi_id }}" title="Hapus"><i class="fas fa-trash"></i></button>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                    @if(!$hasHumidity)
                                                    <tr>
                                                        <td><input type="number" step="0.01" name="humidity_equipment[]" class="form-control form-control-sm text-center py-0" placeholder="0.00" style="font-size: 0.7rem;"></td>
                                                        <td><input type="number" step="0.01" name="humidity_standard[]" class="form-control form-control-sm text-center py-0" placeholder="0.00" style="font-size: 0.7rem;"></td>
                                                        <td><button type="button" class="btn btn-sm text-danger p-0 border-0 bg-transparent hapus-baris"><i class="fas fa-times"></i></button></td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" id="tambahHumidity" class="btn btn-outline-success btn-sm w-100 fw-bold py-1" style="font-size: 0.7rem;"><i class="fas fa-plus me-1"></i> Tambah Baris Humidity</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                   
                        <div class="mt-2 text-end">
                            <button type="submit" class="btn btn-success btn-sm px-3 py-1 fw-bold" style="font-size: 0.72rem;"><i class="fas fa-save me-1"></i> Simpan Semua Titik Acuan</button>
                        </div>
                    </form>

                    @if(isset($titikKalibrasiList) && count($titikKalibrasiList) > 0)
                        @foreach($titikKalibrasiList as $titik)
                            <form id="delete-form-{{ $titik->titik_kalibrasi_id }}" action="{{ route('inventori.monitoring.destroyKalibrasi', $titik->titik_kalibrasi_id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endforeach
                    @endif

                    <!-- DOKUMEN REFERENSI RUANGAN -->
                    <div class="card border bg-white shadow-sm mt-3">
                        <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark small" style="font-size: 0.75rem;"><i class="fas fa-file-alt text-primary me-1"></i> DOKUMEN REFERENSI RUANGAN</span>
                            <span class="badge bg-primary" style="font-size: 10px;">{{ isset($dokumenList) ? count($dokumenList) : 0 }} File</span>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-5 border-end pe-md-3">
                                    <form action="{{ route('inventori.monitoring.uploadReferensi') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="nama_ruangan" value="{{ $ruangan }}">
                                        <input type="hidden" name="bulan" value="{{ $bulan }}">
                                        <input type="hidden" name="tahun" value="{{ $tahun }}">
                                        <input type="hidden" name="alat_id" value="{{ $alatId }}">

                                        <label class="form-label fw-bold text-secondary mb-1" style="font-size: 11px;">UNGGAH DOKUMEN BARU</label>
                                        <div class="input-group input-group-sm mb-1">
                                            <input type="file" class="form-control form-control-sm" name="dokumen_referensi" required accept=".pdf,.doc,.docx,.jpg,.png" {{ empty($ruangan) ? 'disabled' : '' }} style="font-size: 0.72rem;">
                                            <button type="submit" class="btn text-white btn-sm fw-bold px-3 shadow-sm" {{ empty($ruangan) ? 'disabled' : '' }} style="font-size: 0.72rem; background-color: #1b3152;">
                                                <i class="fas fa-upload me-1"></i> Unggah
                                            </button>
                                        </div>
                                        @if(empty($ruangan))
                                            <small class="text-danger" style="font-size: 10px;">Pilih nama ruangan terlebih dahulu pada filter di bawah.</small>
                                        @endif
                                    </form>
                                </div>

                                <div class="col-md-7 ps-md-3">
                                    <label class="form-label fw-bold text-secondary mb-1" style="font-size: 11px;">DAFTAR DOKUMEN TERSEDIA</label>
                                    <div class="table-responsive rounded border" style="max-height: 120px; overflow-y: auto;">
                                        <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.72rem;">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th class="ps-2">Nama File</th>
                                                    <th class="text-center" style="width: 120px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($dokumenList) && count($dokumenList) > 0)
                                                    @foreach($dokumenList as $doc)
                                                        <tr>
                                                            <td class="ps-2 text-truncate" style="max-width: 200px;" title="{{ $doc->nama_file_asli ?? basename($doc->file_path) }}">
                                                                <i class="fas fa-file-alt text-secondary me-1"></i> {{ $doc->nama_file_asli ?? basename($doc->file_path) }}
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm text-white px-1.5 py-0" style="font-size: 10px; background-color: #1b3152;" title="Lihat">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <button type="button" class="btn btn-warning btn-sm text-dark px-1.5 py-0" style="font-size: 10px;" data-bs-toggle="modal" data-bs-target="#editDocModal-{{ $doc->dokumen_id }}" title="Ganti/Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-danger btn-sm px-1.5 py-0 btn-hapus-doc" data-id="{{ $doc->dokumen_id }}" style="font-size: 10px;" title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="2" class="text-center text-muted py-2">Belum ada dokumen referensi.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(isset($dokumenList))
                    @foreach($dokumenList as $doc)
                        <form id="delete-doc-{{ $doc->dokumen_id }}" action="{{ route('inventori.monitoring.destroyReferensi', $doc->dokumen_id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>

                        <div class="modal fade" id="editDocModal-{{ $doc->dokumen_id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('inventori.monitoring.updateReferensi', $doc->dokumen_id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header py-1 px-3 text-white" style="background-color: #1b3152;">
                                            <h6 class="modal-title fw-bold" style="font-size: 0.85rem;">Edit / Ganti Dokumen Referensi</h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-start p-3">
                                            <div class="mb-2">
                                                <label class="form-label small fw-bold">Nama / Keterangan File</label>
                                                <input type="text" class="form-control form-control-sm" name="nama_file_asli" value="{{ $doc->nama_file_asli ?? basename($doc->file_path) }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small fw-bold">Ganti File Baru (Opsional)</label>
                                                <input type="file" class="form-control form-control-sm" name="dokumen_referensi" accept=".pdf,.doc,.docx,.jpg,.png">
                                                <small class="text-muted" style="font-size: 10px;">Biarkan kosong jika tidak ingin mengganti file fisiknya.</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer py-1 px-3">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn text-white btn-sm shadow-sm" style="background-color: #1b3152;">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @endif
                @else
                    <div class="alert alert-warning py-1 px-2 mb-0 small" style="font-size: 0.75rem;">
                        <i class="fas fa-exclamation-circle me-1"></i> Silakan pilih <strong>Pilih Alat</strong> terlebih dahulu pada filter di bawah untuk mengatur titik kalibrasi alat tersebut
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-2">
            <form method="GET" action="{{ route('inventori.monitoring.index') }}" id="filterForm" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="fw-bold text-muted small" style="font-size: 0.7rem;">PILIH ALAT</label>
                    <select name="alat_id" id="selectAlat" class="form-select form-select-sm">
                        <option value="">-- Pilih Alat --</option>
                        @foreach($daftarAlat as $alat)
                            <option value="{{ $alat->alat_id ?? $alat->id }}" {{ $alatId == ($alat->alat_id ?? $alat->id) ? 'selected' : '' }}>
                                {{ $alat->nama_alat }} ({{ $alat->kode_alat ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="fw-bold text-muted small" style="font-size: 0.7rem;">NAMA RUANGAN</label>
                    <input type="text" name="nama_ruangan" value="{{ $ruangan }}" placeholder="Input Ruangan" class="form-control form-control-sm py-1" style="font-size: 0.75rem;">
                </div>

                <div class="col-md-2">
                    <label class="fw-bold text-muted small" style="font-size: 0.7rem;">BULAN</label>
                    <select name="bulan" class="form-select form-select-sm py-1" style="font-size: 0.75rem;">
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $b)
                            <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="fw-bold text-muted small" style="font-size: 0.7rem;">TAHUN</label>
                    <input type="number" name="tahun" value="{{ $tahun }}" class="form-control form-control-sm py-1" style="font-size: 0.75rem;">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn text-white btn-sm w-100 py-1 shadow-sm" style="font-size: 0.72rem; background-color: #1b3152;"><i class="fas fa-search me-1"></i> Tampilkan</button>
                </div>

                <input type="hidden" name="persyaratan_suhu" id="hiddenPersyaratanSuhu" value="{{ $persyaratanSuhu }}">
                <input type="hidden" name="persyaratan_kelembaban" id="hiddenPersyaratanKelembaban" value="{{ $persyaratanKelembaban }}">
            </form>
            
            <hr class="my-2">
            <div class="row text-secondary small align-items-center" style="font-size: 0.72rem;">
                <div class="col-md-12">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <strong style="width: 160px;">Persyaratan Suhu:</strong>
                        <input type="text" id="inputPersyaratanSuhu" value="{{ $persyaratanSuhu }}" class="form-control form-control-sm w-50 py-0.5" placeholder="Otomatis dari acuan suhu" style="font-size: 0.72rem;">
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-0">
                        <strong style="width: 160px;">Persyaratan Kelembaban:</strong>
                        <input type="text" id="inputPersyaratanKelembaban" value="{{ $persyaratanKelembaban }}" class="form-control form-control-sm w-50 py-0.5" placeholder="Otomatis dari acuan humidity" style="font-size: 0.72rem;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body px-0 pt-0">
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle small mb-0" style="font-size: 0.68rem;">
                    <thead class="table-light align-middle">
                        <tr>
                            <th rowspan="2" style="width: 45px;" class="bg-light">Tanggal</th>
                            <th colspan="2" class="py-1 bg-light">Waktu Pencatatan</th>
                            <th colspan="4" class="py-1 bg-sesi-pagi">Suhu (°C)</th>
                            <th colspan="4" class="py-1 bg-sesi-pagi">Kelembaban (%)</th>
                            <th colspan="2" class="py-1 bg-white">Status</th>
                            <th colspan="2" class="py-1 bg-light">Paraf</th>
                            <th rowspan="2" style="width: 55px;" class="bg-light">Aksi</th>
                        </tr>
                        <tr>
                            <th class="py-1 bg-sesi-pagi" style="width: 70px;">Pagi</th>
                            <th class="py-1 bg-sesi-sore" style="width: 70px;">Sore</th>
                            
                            <th class="py-1 bg-sesi-pagi">Pembacaan 1</th>
                            <th class="py-1 bg-sesi-pagi">Koreksi 1</th>
                            <th class="py-1 bg-sesi-sore">Pembacaan 2</th>
                            <th class="py-1 bg-sesi-sore">Koreksi 2</th>
                            
                            <th class="py-1 bg-sesi-pagi">Pembacaan 1</th>
                            <th class="py-1 bg-sesi-pagi">Koreksi 1</th>
                            <th class="py-1 bg-sesi-sore">Pembacaan 2</th>
                            <th class="py-1 bg-sesi-sore">Koreksi 2</th>
                            
                            <th class="py-1 bg-white" style="width: 60px;">Diterima</th>
                            <th class="py-1 bg-white" style="width: 60px;">Ditolak</th>
                
                            <th class="py-1 bg-sesi-pagi" style="width: 65px;">1</th>
                            <th class="py-1 bg-sesi-sore" style="width: 65px;">2</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $minTemp = isset($titikKalibrasiList) ? collect($titikKalibrasiList)->filter(fn($t) => strtolower($t->kategori) == 'temperature')->min('equipment_reading') : null;
                            $maxTemp = isset($titikKalibrasiList) ? collect($titikKalibrasiList)->filter(fn($t) => strtolower($t->kategori) == 'temperature')->max('equipment_reading') : null;
                            $minHum  = isset($titikKalibrasiList) ? collect($titikKalibrasiList)->filter(fn($t) => strtolower($t->kategori) == 'humidity')->min('equipment_reading') : null;
                            $maxHum  = isset($titikKalibrasiList) ? collect($titikKalibrasiList)->filter(fn($t) => strtolower($t->kategori) == 'humidity')->max('equipment_reading') : null;
                        @endphp
               
                        @for($tgl = 1; $tgl <= 31; $tgl++)
                        @php 
                            $row = $monitoringData[$tgl] ?? null; 
                            $sudahAdaData = $row ? true : false;
               
                            $isS1Out = ($minTemp !== null && $row?->suhu_pembacaan_1 !== null && ($row->suhu_pembacaan_1 < $minTemp || $row->suhu_pembacaan_1 > $maxTemp));
                            $isS2Out = ($minTemp !== null && $row?->suhu_pembacaan_2 !== null && ($row->suhu_pembacaan_2 < $minTemp || $row->suhu_pembacaan_2 > $maxTemp));
                            $isH1Out = ($minHum !== null && $row?->kelembaban_pembacaan_1 !== null && ($row->kelembaban_pembacaan_1 < $minHum || $row->kelembaban_pembacaan_1 > $maxHum));
                            $isH2Out = ($minHum !== null && $row?->kelembaban_pembacaan_2 !== null && ($row->kelembaban_pembacaan_2 < $minHum || $row->kelembaban_pembacaan_2 > $maxHum));
                        @endphp
                        <tr class="align-middle">
                            <form action="{{ route('inventori.monitoring.updateBaris') }}" method="POST" id="form-tgl-{{ $tgl }}" class="m-0 p-0 form-monitoring" data-sudah-ada="{{ $sudahAdaData ? 'true' : 'false' }}">
                                @csrf
                                <input type="hidden" name="alat_id" value="{{ $alatId }}">
                                <input type="hidden" name="bulan" value="{{ $bulan }}">
                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                <input type="hidden" name="nama_ruangan" value="{{ $ruangan }}">
                                <input type="hidden" name="tanggal" value="{{ $tgl }}">
                                
                                <input type="hidden" name="persyaratan_suhu" class="row-persyaratan-suhu" value="{{ $persyaratanSuhu ?: $otomatisSuhu }}">
                                <input type="hidden" name="persyaratan_kelembaban" class="row-persyaratan-kelembaban" value="{{ $persyaratanKelembaban ?: $otomatisKelembaban }}">
                    
                                <td class="fw-bold bg-light">{{ $tgl }}</td>
                                
                                <td class="bg-sesi-pagi">
                                    <input type="text" name="waktu_1" value="{{ $row?->waktu_1 }}" class="form-control form-control-sm text-center px-1 bg-white py-0" placeholder="08:00" style="font-size: 0.68rem;">
                                </td>
                                <td class="bg-sesi-sore">
                                    <input type="text" name="waktu_2" value="{{ $row?->waktu_2 }}" class="form-control form-control-sm text-center px-1 bg-white py-0" placeholder="13:00" style="font-size: 0.68rem;">
                                </td>
                    
                                <td class="bg-sesi-pagi">
                                    <input type="number" step="0.01" name="suhu_pembacaan_1" value="{{ $row?->suhu_pembacaan_1 }}" class="form-control form-control-sm text-center px-1 input-suhu-1 bg-white py-0 {{ $isS1Out ? 'is-invalid text-danger fw-bold' : '' }}" data-tgl="{{ $tgl }}" placeholder="0.00" style="font-size: 0.68rem;">
                                </td>
                                <td class="fw-bold text-primary bg-sesi-pagi">
                                    <span id="suhu-terkoreksi-1-text-{{ $tgl }}">{{ $row?->suhu_terkoreksi_1 ?? '-' }}</span>
                                    <input type="hidden" name="suhu_terkoreksi_1" id="suhu-terkoreksi-1-input-{{ $tgl }}" value="{{ $row?->suhu_terkoreksi_1 }}">
                                </td>
                    
                                <td class="bg-sesi-sore">
                                    <input type="number" step="0.01" name="suhu_pembacaan_2" value="{{ $row?->suhu_pembacaan_2 }}" class="form-control form-control-sm text-center px-1 input-suhu-2 bg-white py-0 {{ $isS2Out ? 'is-invalid text-danger fw-bold' : '' }}" data-tgl="{{ $tgl }}" placeholder="0.00" style="font-size: 0.68rem;">
                                </td>
                                <td class="fw-bold text-success bg-sesi-sore">
                                    <span id="suhu-terkoreksi-2-text-{{ $tgl }}">{{ $row?->suhu_terkoreksi_2 ?? '-' }}</span>
                                    <input type="hidden" name="suhu_terkoreksi_2" id="suhu-terkoreksi-2-input-{{ $tgl }}" value="{{ $row?->suhu_terkoreksi_2 }}">
                                </td>
                    
                                <td class="bg-sesi-pagi">
                                    <input type="number" step="0.01" name="kelembaban_pembacaan_1" value="{{ $row?->kelembaban_pembacaan_1 }}" class="form-control form-control-sm text-center px-1 input-lembap-1 bg-white py-0 {{ $isH1Out ? 'is-invalid text-danger fw-bold' : '' }}" data-tgl="{{ $tgl }}" placeholder="0.00" style="font-size: 0.68rem;">
                                </td>
                                <td class="fw-bold text-primary bg-sesi-pagi">
                                    <span id="lembap-terkoreksi-1-text-{{ $tgl }}">{{ $row?->kelembaban_terkoreksi_1 ?? '-' }}</span>
                                    <input type="hidden" name="kelembaban_terkoreksi_1" id="lembap-terkoreksi-1-input-{{ $tgl }}" value="{{ $row?->kelembaban_terkoreksi_1 }}">
                                </td>
                    
                                <td class="bg-sesi-sore">
                                    <input type="number" step="0.01" name="kelembaban_pembacaan_2" value="{{ $row?->kelembaban_pembacaan_2 }}" class="form-control form-control-sm text-center px-1 input-lembap-2 bg-white py-0 {{ $isH2Out ? 'is-invalid text-danger fw-bold' : '' }}" data-tgl="{{ $tgl }}" placeholder="0.00" style="font-size: 0.68rem;">
                                </td>
                                <td class="fw-bold text-success bg-sesi-sore">
                                    <span id="lembap-terkoreksi-2-text-{{ $tgl }}">{{ $row?->kelembaban_terkoreksi_2 ?? '-' }}</span>
                                    <input type="hidden" name="kelembaban_terkoreksi_2" id="lembap-terkoreksi-2-input-{{ $tgl }}" value="{{ $row?->kelembaban_terkoreksi_2 }}">
                                </td>
                    
                                <td class="bg-white text-center align-middle">
                                    <input class="form-check-input" type="radio" name="status" value="Diterima" {{ ($row?->status == 'Diterima') ? 'checked' : '' }}>
                                </td>
                    
                                <td class="bg-white text-center align-middle">
                                    <input class="form-check-input" type="radio" name="status" value="Ditolak" {{ ($row?->status == 'Ditolak') ? 'checked' : '' }}>
                                </td>
                    
                                <td class="bg-sesi-pagi text-center small fw-bold text-dark text-truncate" style="max-width: 65px;" title="{{ $row?->paraf_1 }}">
                                    {{ $row?->paraf_1 ?? '-' }}
                                </td>

                                <td class="bg-sesi-sore text-center small fw-bold text-dark text-truncate" style="max-width: 65px;" title="{{ $row?->paraf_2 }}">
                                    {{ $row?->paraf_2 ?? '-' }}
                                </td>
                    
                                <td class="bg-light text-center">
                                    <button type="submit" class="btn btn-sm btn-success px-1.5 py-0.5" style="font-size: 0.65rem;" title="Simpan Baris"><i class="fas fa-save"></i></button>
                                </td>
                            </form>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof jQuery !== 'undefined') {
            $(document).ready(function() {
                if ($.fn.select2) {
                    $('#selectAlat').select2({
                        theme: 'bootstrap-5',
                        placeholder: '-- Pilih Alat --',
                        allowClear: false
                    });
                }
            });
        }

        document.querySelectorAll('.btn-hapus-titik').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: "Hapus titik acuan temperature ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = document.getElementById('delete-form-' + id);
                        if (form) form.submit();
                    }
                });
            });
        });

        document.querySelectorAll('.btn-hapus-doc').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: "Yakin ingin menghapus dokumen ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = document.getElementById('delete-doc-' + id);
                        if (form) form.submit();
                    }
                });
            });
        });
    });

    $(document).ready(function() {
        function updateRentangOtomatis() {
            let suhuVals = [];
            $('#tabelTemperature tbody tr').each(function() {
                let cellText = $(this).find('td:eq(0)').text().trim();
                let val = parseFloat(cellText);
                if (!isNaN(val)) suhuVals.push(val);
            });

            if (suhuVals.length > 0) {
                let minSuhu = Math.min(...suhuVals);
                let maxSuhu = Math.max(...suhuVals);
                let textSuhu = minSuhu + ' - ' + maxSuhu + ' °C';
                $('#inputPersyaratanSuhu').val(textSuhu);
                $('#hiddenPersyaratanSuhu').val(textSuhu);
                $('.row-persyaratan-suhu').val(textSuhu);
            }

            let humVals = [];
            $('#tabelHumidity tbody tr').each(function() {
                let cellText = $(this).find('td:eq(0)').text().trim();
                let val = parseFloat(cellText);
                if (!isNaN(val)) humVals.push(val);
            });

            if (humVals.length > 0) {
                let minHum = Math.min(...humVals);
                let maxHum = Math.max(...humVals);
                let textHum = minHum + ' - ' + maxHum + ' %';
                $('#inputPersyaratanKelembaban').val(textHum);
                $('#hiddenPersyaratanKelembaban').val(textHum);
                $('.row-persyaratan-kelembaban').val(textHum);
            }
        }

        updateRentangOtomatis();

        function getTitikAcuan(kategoriTable) {
            let points = [];
            $(kategoriTable + ' tbody tr').each(function() {
                let eq = parseFloat($(this).find('td:eq(0)').text());
                let std = parseFloat($(this).find('td:eq(1)').text());
                if (!isNaN(eq) && !isNaN(std)) {
                    points.push({ x: eq, y: std });
                }
            });
            points.sort((a, b) => a.x - b.x);
            return points;
        }

        function hitungInterpolasi(nilaiInput, points) {
            if (isNaN(nilaiInput) || points.length === 0) return '';

            if (points.length === 1) {
                return points[0].y.toFixed(2);
            }

            if (nilaiInput <= points[0].x) return points[0].y.toFixed(2);
            if (nilaiInput >= points[points.length - 1].x) return points[points.length - 1].y.toFixed(2);

            let p1 = points[0];
            let p2 = points[1];

            for (let i = 0; i < points.length - 1; i++) {
                if (nilaiInput >= points[i].x && nilaiInput <= points[i+1].x) {
                    p1 = points[i];
                    p2 = points[i+1];
                    break;
                }
            }

            let x = nilaiInput;
            let x1 = p1.x, y1 = p1.y;
            let x2 = p2.x, y2 = p2.y;

            if (x2 === x1) return y1.toFixed(2);

            let hasil = y1 + ((x - x1) / (x2 - x1)) * (y2 - y1);
            return hasil.toFixed(2);
        }

        function cekBatasPersyaratan(tgl) {
            let pointsSuhu = getTitikAcuan('#tabelTemperature');
            let pointsHum = getTitikAcuan('#tabelHumidity');

            let inputS1 = $('.input-suhu-1[data-tgl="' + tgl + '"]');
            let valS1 = parseFloat(inputS1.val());
            let textS1 = $('#suhu-terkoreksi-1-text-' + tgl);
            let hidS1 = $('#suhu-terkoreksi-1-input-' + tgl);

            if (!isNaN(valS1) && pointsSuhu.length > 0) {
                let minEq = pointsSuhu[0].x;
                let maxEq = pointsSuhu[pointsSuhu.length - 1].x;
                if (valS1 < minEq || valS1 > maxEq) {
                    inputS1.addClass('is-invalid text-danger fw-bold');
                    textS1.text('-').addClass('text-danger fw-bold').removeClass('text-primary');
                    hidS1.val('');
                } else {
                    inputS1.removeClass('is-invalid text-danger fw-bold');
                    textS1.removeClass('text-danger fw-bold').addClass('text-primary');
                }
            } else {
                inputS1.removeClass('is-invalid text-danger fw-bold');
            }

            let inputS2 = $('.input-suhu-2[data-tgl="' + tgl + '"]');
            let valS2 = parseFloat(inputS2.val());
            let textS2 = $('#suhu-terkoreksi-2-text-' + tgl);
            let hidS2 = $('#suhu-terkoreksi-2-input-' + tgl);

            if (!isNaN(valS2) && pointsSuhu.length > 0) {
                let minEq = pointsSuhu[0].x;
                let maxEq = pointsSuhu[pointsSuhu.length - 1].x;
                if (valS2 < minEq || valS2 > maxEq) {
                    inputS2.addClass('is-invalid text-danger fw-bold');
                    textS2.text('-').addClass('text-danger fw-bold').removeClass('text-success');
                    hidS2.val('');
                } else {
                    inputS2.removeClass('is-invalid text-danger fw-bold');
                    textS2.removeClass('text-danger fw-bold').addClass('text-success');
                }
            } else {
                inputS2.removeClass('is-invalid text-danger fw-bold');
            }

            let inputH1 = $('.input-lembap-1[data-tgl="' + tgl + '"]');
            let valH1 = parseFloat(inputH1.val());
            let textH1 = $('#lembap-terkoreksi-1-text-' + tgl);
            let hidH1 = $('#lembap-terkoreksi-1-input-' + tgl);

            if (!isNaN(valH1) && pointsHum.length > 0) {
                let minEq = pointsHum[0].x;
                let maxEq = pointsHum[pointsHum.length - 1].x;
                if (valH1 < minEq || valH1 > maxEq) {
                    inputH1.addClass('is-invalid text-danger fw-bold');
                    textH1.text('-').addClass('text-danger fw-bold').removeClass('text-primary');
                    hidH1.val('');
                } else {
                    inputH1.removeClass('is-invalid text-danger fw-bold');
                    textH1.removeClass('text-danger fw-bold').addClass('text-primary');
                }
            } else {
                inputH1.removeClass('is-invalid text-danger fw-bold');
            }

            let inputH2 = $('.input-lembap-2[data-tgl="' + tgl + '"]');
            let valH2 = parseFloat(inputH2.val());
            let textH2 = $('#lembap-terkoreksi-2-text-' + tgl);
            let hidH2 = $('#lembap-terkoreksi-2-input-' + tgl);

            if (!isNaN(valH2) && pointsHum.length > 0) {
                let minEq = pointsHum[0].x;
                let maxEq = pointsHum[pointsHum.length - 1].x;
                if (valH2 < minEq || valH2 > maxEq) {
                    inputH2.addClass('is-invalid text-danger fw-bold');
                    textH2.text('-').addClass('text-danger fw-bold').removeClass('text-success');
                    hidH2.val('');
                } else {
                    inputH2.removeClass('is-invalid text-danger fw-bold');
                    textH2.removeClass('text-danger fw-bold').addClass('text-success');
                }
            } else {
                inputH2.removeClass('is-invalid text-danger fw-bold');
            }
        }

        for (let i = 1; i <= 31; i++) {
            cekBatasPersyaratan(i);
        }

        $(document).on('input', '.input-suhu-1, .input-suhu-2, .input-lembap-1, .input-lembap-2', function() {
            let tgl = $(this).data('tgl');
            let val = parseFloat($(this).val());

            let pointsSuhu = getTitikAcuan('#tabelTemperature');
            let pointsHum = getTitikAcuan('#tabelHumidity');

            if ($(this).hasClass('input-suhu-1')) {
                let res = hitungInterpolasi(val, pointsSuhu);
                $('#suhu-terkoreksi-1-text-' + tgl).text(res !== '' ? res : '-');
                $('#suhu-terkoreksi-1-input-' + tgl).val(res);
            } else if ($(this).hasClass('input-suhu-2')) {
                let res = hitungInterpolasi(val, pointsSuhu);
                $('#suhu-terkoreksi-2-text-' + tgl).text(res !== '' ? res : '-');
                $('#suhu-terkoreksi-2-input-' + tgl).val(res);
            } else if ($(this).hasClass('input-lembap-1')) {
                let res = hitungInterpolasi(val, pointsHum);
                $('#lembap-terkoreksi-1-text-' + tgl).text(res !== '' ? res : '-');
                $('#lembap-terkoreksi-1-input-' + tgl).val(res);
            } else if ($(this).hasClass('input-lembap-2')) {
                let res = hitungInterpolasi(val, pointsHum);
                $('#lembap-terkoreksi-2-text-' + tgl).text(res !== '' ? res : '-');
                $('#lembap-terkoreksi-2-input-' + tgl).val(res);
            }

            cekBatasPersyaratan(tgl);
        });

        $(document).on('submit', '.form-monitoring', function(e) {
            let sudahAda = $(this).data('sudah-ada');
            if (sudahAda === true || sudahAda === 'true') {
                e.preventDefault(); 
                let form = this;
                Swal.fire({
                    title: 'Konfirmasi Perubahan',
                    text: "Yakin ingin mengubah data?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Ubah!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });

        $(document).off('click', '#tambahHumidity').on('click', '#tambahHumidity', function(e) {
            e.preventDefault();
            let row = `<tr>
                <td><input type="number" step="0.01" name="humidity_equipment[]" class="form-control form-control-sm text-center py-0" placeholder="0.00" style="font-size: 0.7rem;"></td>
                <td><input type="number" step="0.01" name="humidity_standard[]" class="form-control form-control-sm text-center py-0" placeholder="0.00" style="font-size: 0.7rem;"></td>
                <td><button type="button" class="btn btn-sm text-danger p-0 border-0 bg-transparent hapus-baris"><i class="fas fa-times"></i></button></td>
            </tr>`;
            $('#tabelHumidity tbody').append(row);
        });

        $(document).off('click', '#tambahTemperature').on('click', '#tambahTemperature', function(e) {
            e.preventDefault();
            let row = `<tr>
                <td><input type="number" step="0.01" name="temperature_equipment[]" class="form-control form-control-sm text-center py-0" placeholder="0.00" style="font-size: 0.7rem;"></td>
                <td><input type="number" step="0.01" name="temperature_standard[]" class="form-control form-control-sm text-center py-0" placeholder="0.00" style="font-size: 0.7rem;"></td>
                <td><button type="button" class="btn btn-sm text-danger p-0 border-0 bg-transparent hapus-baris"><i class="fas fa-times"></i></button></td>
            </tr>`;
            $('#tabelTemperature tbody').append(row);
        });

        $(document).on('click', '.hapus-baris', function() {$(this).closest('tr').remove();
        });

        $('#inputPersyaratanSuhu').on('input', function() {
            let val = $(this).val();$('#hiddenPersyaratanSuhu').val(val);
            $('.row-persyaratan-suhu').val(val);
        });

        $('#inputPersyaratanKelembaban').on('input', function() {
            let val = $(this).val();$('#hiddenPersyaratanKelembaban').val(val);
            $('.row-persyaratan-kelembaban').val(val);
        });

        $('#selectAlat').on('change', function() {
            $('input[name="nama_ruangan"]').val('');
            $('#inputPersyaratanSuhu').val('');
            $('#hiddenPersyaratanSuhu').val('');
            $('#inputPersyaratanKelembaban').val('');
            $('#hiddenPersyaratanKelembaban').val('');
        });            
    });
</script>
@endpush