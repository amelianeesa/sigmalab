@extends('layouts.app')

@push('styles')
<style>
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
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark fs-4">Pencatatan Monitoring Suhu dan Kelembaban Udara</h2>
        <div>
            <a href="{{ route('inventori.monitoring.index') }}" class="btn btn-outline-primary btn-sm me-2"><i class="fas fa-sync-alt me-1"></i> Refresh Data</a>
            @if(isset($alatId) && $alatId && isset($ruangan) && $ruangan)
                <a href="{{ route('inventori.monitoring.exportPdf', ['alat_id' => $alatId, 'nama_ruangan' => $ruangan, 'bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf me-1"></i> Unduh Rekapan PDF</a>
            @endif
        </div>
    </div>


    <div class="card border-0 shadow-sm mb-4 bg-light">
        <div class="card-body p-3">
            <!-- HEADER DENGAN TOMBOL COLLAPSE (BUKA/TUTUP) -->
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold text-dark fs-6 mb-1"><i class="fas fa-sliders-h text-primary me-2"></i>Manajemen Titik Acuan Kalibrasi</h5>
                    <p class="text-muted small mb-0">Klik tombol di sebelah kanan untuk membuka atau menyembunyikan detail titik acuan.</p>
                </div>
                <button class="btn btn-outline-secondary btn-sm fw-bold px-3 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTitikAcuan" aria-expanded="false" aria-controls="collapseTitikAcuan">
                    <i class="fas fa-chevron-down me-1"></i> Sembunyikan / Tampilkan
                </button>
            </div>
            @if(isset($alatAktif) && $alatAktif)
                @php 
                    $idAlat = $alatAktif->alat_id ?? $alatAktif->id; 
                    $hasAnyKalibrasi = isset($titikKalibrasiList) && count($titikKalibrasiList) > 0;

                    $tglKalibVal = $alatAktif->tanggal_kalibrasi ?? date('Y-m-d');
                    $tglExpVal   = $alatAktif->tanggal_expired ?? date('Y-m-d');

                    $isExpired   = $alatAktif->tanggal_expired && \Carbon\Carbon::now()->gt($alatAktif->tanggal_expired);
                @endphp
                <div class="collapse show mt-3" id="collapseTitikAcuan">
                    <div class="bg-white p-3 rounded border shadow-sm mb-3">
                        <div class="row align-items-center g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary mb-1" style="font-size: 11px;"><i class="fas fa-calendar-alt text-primary me-1"></i> TANGGAL KALIBRASI ALAT</label>
                                <input type="text" value="{{ $tglKalibVal }}" class="form-control form-control-sm bg-light fw-bold text-dark" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary mb-1" style="font-size: 11px;"><i class="fas fa-hourglass-end text-danger me-1"></i> MASA BERLAKU (EXPIRED DATE)</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" value="{{ $tglExpVal }}" class="form-control bg-light fw-bold text-dark" readonly>
                                    @if($alatAktif->tanggal_expired)
                                        @if($isExpired)
                                            <span class="badge bg-danger d-flex align-items-center px-3" style="font-size: 10px;"><i class="fas fa-exclamation-triangle me-1"></i> EXPIRED</span>
                                        @else
                                            <span class="badge bg-success d-flex align-items-center px-3" style="font-size: 10px;"><i class="fas fa-check-circle me-1"></i> AKTIF</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                
                <form action="{{ route('inventori.monitoring.storeKalibrasi', $idAlat) }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <!-- Kolom Kiri: SUHU -->
                        <div class="col-md-6">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-warning bg-opacity-10 py-2 fw-bold text-dark small text-center">
                                    TEMPERATURE (Suhu)
                                </div>
                                <div class="card-body p-2">
                                    <div class="table-responsive bg-white rounded mb-2" style="max-height: 180px; overflow-y: auto;">
                                        <table class="table table-sm table-bordered text-center align-middle mb-0" id="tabelTemperature" style="font-size: 11px;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Equipment Reading</th>
                                                    <th>Standard Reading</th>
                                                    <th style="width: 40px;">Aksi</th>
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
                                                                    <button type="button" class="btn btn-sm text-danger p-0 border-0 bg-transparent" onclick="if(confirm('Hapus titik acuan temperature ini?')) { document.getElementById('delete-form-{{ $titik->titik_kalibrasi_id }}').submit(); }" title="Hapus"><i class="fas fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif

                                                @if(!$hasTemp)
                                                <tr>
                                                    <td><input type="number" step="0.01" name="temperature_equipment[]" class="form-control form-control-sm text-center" placeholder="0.00"></td>
                                                    <td><input type="number" step="0.01" name="temperature_standard[]" class="form-control form-control-sm text-center" placeholder="0.00"></td>
                                                    <td><button type="button" class="btn btn-sm text-danger p-0 border-0 bg-transparent hapus-baris"><i class="fas fa-times"></i></button></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        </table>
                                    </div>
                                    <button type="button" id="tambahTemperature" class="btn btn-outline-warning btn-sm w-100 text-dark fw-bold" style="font-size: 11px;"><i class="fas fa-plus me-1"></i> Tambah Baris Temperature</button>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: TEMPERATURE -->
                        <div class="col-md-6">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-success bg-opacity-10 py-2 fw-bold text-dark small text-center">
                                    HUMIDITY (Kelembaban)
                                </div>
                                <div class="card-body p-2">
                                    <div class="table-responsive bg-white rounded mb-2" style="max-height: 180px; overflow-y: auto;">
                                        <table class="table table-sm table-bordered text-center align-middle mb-0" id="tabelHumidity" style="font-size: 11px;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Equipment Reading</th>
                                                    <th>Standard Reading</th>
                                                    <th style="width: 40px;">Aksi</th>
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
                                                                    <button type="button" class="btn btn-sm text-danger p-0 border-0 bg-transparent" onclick="if(confirm('Hapus titik acuan humidity ini?')) { document.getElementById('delete-form-{{ $titik->titik_kalibrasi_id }}').submit(); }" title="Hapus"><i class="fas fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif

                                                @if(!$hasHumidity)
                                                    <tr>
                                                        <td><input type="number" step="0.01" name="humidity_equipment[]" class="form-control form-control-sm text-center" placeholder="0.00"></td>
                                                        <td><input type="number" step="0.01" name="humidity_standard[]" class="form-control form-control-sm text-center" placeholder="0.00"></td>
                                                        <td><button type="button" class="btn btn-sm text-danger p-0 border-0 bg-transparent hapus-baris"><i class="fas fa-times"></i></button></td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" id="tambahHumidity" class="btn btn-outline-success btn-sm w-100 fw-bold" style="font-size: 11px;"><i class="fas fa-plus me-1"></i> Tambah Baris Humidity</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-success px-4 py-2"><i class="fas fa-save me-1"></i> Simpan Semua Titik Acuan</button>
                    </div>
                </form>

                @if($hasAnyKalibrasi)
                    @foreach($titikKalibrasiList as $titik)
                        <form id="delete-form-{{ $titik->titik_kalibrasi_id }}" action="{{ route('inventori.monitoring.destroyKalibrasi', $titik->titik_kalibrasi_id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach
                @endif
            @else
                <div class="alert alert-warning py-2 mb-0 small">
                    <i class="fas fa-exclamation-circle me-1"></i> Silakan pilih <strong>Pilih Alat</strong> terlebih dahulu pada filter di bawah untuk mengatur titik kalibrasi alat tersebut
                </div>
            @endif
        </div>
    </div>
</div>
    <!-- Header Filter & Informasi Alat -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('inventori.monitoring.index') }}" id="filterForm" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="fw-bold text-muted small">PILIH ALAT</label>
                    <select name="alat_id" id="selectAlat" class="form-select">
                        <option value="">-- Pilih Alat --</option>
                        @foreach($daftarAlat as $alat)
                            <option value="{{ $alat->alat_id ?? $alat->id }}" {{ $alatId == ($alat->alat_id ?? $alat->id) ? 'selected' : '' }}>
                                {{ $alat->nama_alat }} ({{ $alat->kode_alat ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="fw-bold text-muted small">NAMA RUANGAN</label>
                    <input type="text" name="nama_ruangan" value="{{ $ruangan }}" placeholder="Input Ruangan" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="fw-bold text-muted small">BULAN</label>
                    <select name="bulan" class="form-select">
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $b)
                            <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="fw-bold text-muted small">TAHUN</label>
                    <input type="number" name="tahun" value="{{ $tahun }}" class="form-control">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Tampilkan</button>
                </div>

                <input type="hidden" name="persyaratan_suhu" id="hiddenPersyaratanSuhu" value="{{ $persyaratanSuhu }}">
                <input type="hidden" name="persyaratan_kelembaban" id="hiddenPersyaratanKelembaban" value="{{ $persyaratanKelembaban }}">
            </form>
            
            <hr class="my-3">
            <div class="row text-secondary small align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <strong style="width: 160px;">Persyaratan Suhu:</strong>
                        <input type="text" id="inputPersyaratanSuhu" value="{{ $persyaratanSuhu }}" class="form-control form-control-sm w-50" placeholder="Otomatis dari acuan suhu">
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <strong style="width: 160px;">Persyaratan Kelembaban:</strong>
                        <input type="text" id="inputPersyaratanKelembaban" value="{{ $persyaratanKelembaban }}" class="form-control form-control-sm w-50" placeholder="Otomatis dari acuan humidity">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Monitoring Harian (1 - 31) -->
    <div class="card border-0 shadow-sm">
        <div class="card-body px-0 pt-0">
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle small mb-0">
                    <thead class="table-light align-middle">
                        <tr>
                            <th rowspan="2" style="width: 55px;" class="bg-light">Tanggal</th>
                            <th colspan="2" class="py-1 bg-light">Waktu Pencatatan</th>
                            <th colspan="4" class="py-1 bg-sesi-pagi">Suhu (°C)</th>
                            <th colspan="4" class="py-1 bg-sesi-pagi">Kelembaban (%)</th>
                            <th colspan="2" class="py-1 bg-light">Paraf</th>
                            <th rowspan="2" style="width: 140px;" class="bg-white">Keterangan</th>
                            <th rowspan="2" style="width: 65px;" class="bg-light">Aksi</th>
                        </tr>
                        <tr>
                            <!-- Waktu -->
                            <th class="py-2 bg-sesi-pagi" style="width: 85px;">Pagi</th>
                            <th class="py-2 bg-sesi-sore" style="width: 85px;">Sore</th>
                            
                            <!-- Suhu Sesi 1 & 2 -->
                            <th class="py-2 bg-sesi-pagi">Pembacaan 1</th>
                            <th class="py-2 bg-sesi-pagi">Koreksi 1</th>
                            <th class="py-2 bg-sesi-sore">Pembacaan 2</th>
                            <th class="py-2 bg-sesi-sore">Koreksi 2</th>
                            
                            <!-- Kelembaban Sesi 1 & 2 -->
                            <th class="py-2 bg-sesi-pagi">Pembacaan 1</th>
                            <th class="py-2 bg-sesi-pagi">Koreksi 1</th>
                            <th class="py-2 bg-sesi-sore">Pembacaan 2</th>
                            <th class="py-2 bg-sesi-sore">Koreksi 2</th>
                            
                            <!-- Paraf -->
                            <th class="py-2 bg-sesi-pagi" style="width: 50px;">1</th>
                            <th class="py-2 bg-sesi-sore" style="width: 50px;">2</th>
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
                
                            // Cek apakah nilai pembacaan melampaui batas acuan database
                            $isS1Out = ($minTemp !== null && $row?->suhu_pembacaan_1 !== null && ($row->suhu_pembacaan_1 < $minTemp || $row->suhu_pembacaan_1 > $maxTemp));
                            $isS2Out = ($minTemp !== null && $row?->suhu_pembacaan_2 !== null && ($row->suhu_pembacaan_2 < $minTemp || $row->suhu_pembacaan_2 > $maxTemp));
                            $isH1Out = ($minHum !== null && $row?->kelembaban_pembacaan_1 !== null && ($row->kelembaban_pembacaan_1 < $minHum || $row->kelembaban_pembacaan_1 > $maxHum));
                            $isH2Out = ($minHum !== null && $row?->kelembaban_pembacaan_2 !== null && ($row->kelembaban_pembacaan_2 < $minHum || $row->kelembaban_pembacaan_2 > $maxHum));
                        @endphp
                        <tr class="align-middle">
                            <!-- Form dibungkus melingkupi satu baris penuh agar seluruh input & tombol aksi bersatu -->
                            <form action="{{ route('inventori.monitoring.updateBaris') }}" method="POST" id="form-tgl-{{ $tgl }}" class="m-0 p-0 form-monitoring" data-sudah-ada="{{ $sudahAdaData ? 'true' : 'false' }}">
                                @csrf
                                <input type="hidden" name="alat_id" value="{{ $alatId }}">
                                <input type="hidden" name="bulan" value="{{ $bulan }}">
                                <input type="hidden" name="tahun" value="{{ $tahun }}">
                                <input type="hidden" name="nama_ruangan" value="{{ $ruangan }}">
                                <input type="hidden" name="tanggal" value="{{ $tgl }}">
                                
                                <input type="hidden" name="persyaratan_suhu" class="row-persyaratan-suhu" value="{{ $persyaratanSuhu ?: $otomatisSuhu }}">
                                <input type="hidden" name="persyaratan_kelembaban" class="row-persyaratan-kelembaban" value="{{ $persyaratanKelembaban ?: $otomatisKelembaban }}">

                                <!-- Tanggal -->
                                <td class="fw-bold bg-light">{{ $tgl }}</td>
                                
                                <!-- Waktu Pagi (Biru) -->
                                <td class="bg-sesi-pagi">
                                    <input type="text" name="waktu_1" value="{{ $row?->waktu_1 }}" class="form-control form-control-sm text-center px-1 bg-white" placeholder="08:00">
                                </td>
                                <!-- Waktu Sore (Hijau) -->
                                <td class="bg-sesi-sore">
                                    <input type="text" name="waktu_2" value="{{ $row?->waktu_2 }}" class="form-control form-control-sm text-center px-1 bg-white" placeholder="13:00">
                                </td>

                                <!-- Suhu Pembacaan 1 (Pagi - Biru) -->
                                <td class="bg-sesi-pagi">
                                    <input type="number" step="0.01" name="suhu_pembacaan_1" value="{{ $row?->suhu_pembacaan_1 }}" class="form-control form-control-sm text-center px-1 input-suhu-1 bg-white {{ $isS1Out ? 'is-invalid text-danger fw-bold' : '' }}" data-tgl="{{ $tgl }}" placeholder="0.00">
                                </td>
                                <!-- Suhu Koreksi 1 (Pagi - Biru) -->
                                <td class="fw-bold text-primary bg-sesi-pagi">
                                    <span id="suhu-terkoreksi-1-text-{{ $tgl }}">{{ $row?->suhu_terkoreksi_1 ?? '-' }}</span>
                                    <input type="hidden" name="suhu_terkoreksi_1" id="suhu-terkoreksi-1-input-{{ $tgl }}" value="{{ $row?->suhu_terkoreksi_1 }}">
                                </td>

                                <!-- Suhu Pembacaan 2 (Sore - Hijau) -->
                                <td class="bg-sesi-sore">
                                    <input type="number" step="0.01" name="suhu_pembacaan_2" value="{{ $row?->suhu_pembacaan_2 }}" class="form-control form-control-sm text-center px-1 input-suhu-2 bg-white {{ $isS2Out ? 'is-invalid text-danger fw-bold' : '' }}" data-tgl="{{ $tgl }}" placeholder="0.00">
                                </td>
                                <!-- Suhu Koreksi 2 (Sore - Hijau) -->
                                <td class="fw-bold text-success bg-sesi-sore">
                                    <span id="suhu-terkoreksi-2-text-{{ $tgl }}">{{ $row?->suhu_terkoreksi_2 ?? '-' }}</span>
                                    <input type="hidden" name="suhu_terkoreksi_2" id="suhu-terkoreksi-2-input-{{ $tgl }}" value="{{ $row?->suhu_terkoreksi_2 }}">
                                </td>

                                <!-- Kelembaban Pembacaan 1 (Pagi - Biru) -->
                                <td class="bg-sesi-pagi">
                                    <input type="number" step="0.01" name="kelembaban_pembacaan_1" value="{{ $row?->kelembaban_pembacaan_1 }}" class="form-control form-control-sm text-center px-1 input-lembap-1 bg-white {{ $isH1Out ? 'is-invalid text-danger fw-bold' : '' }}" data-tgl="{{ $tgl }}" placeholder="0.00">
                                </td>
                                <!-- Kelembaban Koreksi 1 (Pagi - Biru) -->
                                <td class="fw-bold text-primary bg-sesi-pagi">
                                    <span id="lembap-terkoreksi-1-text-{{ $tgl }}">{{ $row?->kelembaban_terkoreksi_1 ?? '-' }}</span>
                                    <input type="hidden" name="kelembaban_terkoreksi_1" id="lembap-terkoreksi-1-input-{{ $tgl }}" value="{{ $row?->kelembaban_terkoreksi_1 }}">
                                </td>

                                <!-- Kelembaban Pembacaan 2 (Sore - Hijau) -->
                                <td class="bg-sesi-sore">
                                    <input type="number" step="0.01" name="kelembaban_pembacaan_2" value="{{ $row?->kelembaban_pembacaan_2 }}" class="form-control form-control-sm text-center px-1 input-lembap-2 bg-white {{ $isH2Out ? 'is-invalid text-danger fw-bold' : '' }}" data-tgl="{{ $tgl }}" placeholder="0.00">
                                </td>
                                <!-- Kelembaban Koreksi 2 (Sore - Hijau) -->
                                <td class="fw-bold text-success bg-sesi-sore">
                                    <span id="lembap-terkoreksi-2-text-{{ $tgl }}">{{ $row?->kelembaban_terkoreksi_2 ?? '-' }}</span>
                                    <input type="hidden" name="kelembaban_terkoreksi_2" id="lembap-terkoreksi-2-input-{{ $tgl }}" value="{{ $row?->kelembaban_terkoreksi_2 }}">
                                </td>

                                <!-- Paraf 1 (Pagi - Biru) -->
                                <td class="bg-sesi-pagi text-center">
                                    <input type="checkbox" name="paraf_1" value="1" {{ $row?->paraf_1 ? 'checked' : '' }} class="form-check-input" title="Paraf Sesi 1">
                                </td>
                                <!-- Paraf 2 (Sore - Hijau) -->
                                <td class="bg-sesi-sore text-center">
                                    <input type="checkbox" name="paraf_2" value="1" {{ $row?->paraf_2 ? 'checked' : '' }} class="form-check-input" title="Paraf Sesi 2">
                                </td>

                                <!-- Keterangan -->
                                <td class="bg-white">
                                    <textarea name="keterangan" class="form-control form-control-sm" rows="1" placeholder="Keterangan..." style="resize: vertical; min-height: 35px; max-height: 120px;">{{ $row?->keterangan }}</textarea>
                                </td>

                                <!-- Tombol Aksi -->
                                <td class="bg-light">
                                    <button type="submit" class="btn btn-sm btn-success w-100" title="Simpan Baris"><i class="fas fa-save"></i></button>
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
    $(document).ready(function() {
        if ($.fn.select2) {
            $('#selectAlat').select2({
                theme: 'bootstrap-5',
                placeholder: '-- Pilih Alat --',
                allowClear: false
            });
        }

        function updateRentangOtomatis() {
            let suhuVals = [];
            $('#tabelTemperature tbody tr').each(function() {
                // Ambil teks dari kolom pertama (pastikan bukan input field kosong)
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

        // TAMBAHAN: Fungsi untuk membaca rentang angka dari teks persyaratan (contoh: "10 - 52.3 °C")
        function parseRentang(text) {
            if (!text) return null;
            let parts = text.replace(/[^\d.-]/g, ' ').trim().split(/\s+/);
            if (parts.length >= 2) {
                let min = parseFloat(parts[0]);
                let max = parseFloat(parts[1]);
                if (!isNaN(min) && !isNaN(max)) {
                    return { min: Math.min(min, max), max: Math.max(min, max) };
                }
            }
            return null;
        }

        function cekBatasPersyaratan(tgl) {
            let pointsSuhu = getTitikAcuan('#tabelTemperature');
            let pointsHum = getTitikAcuan('#tabelHumidity');

            // --- SUHU SESI 1 ---
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

            // --- SUHU SESI 2 ---
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

            // --- KELEMBABAN SESI 1 ---
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

            // --- KELEMBABAN SESI 2 ---
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

        // Panggil otomatis untuk mengecek semua tanggal saat halaman pertama kali dibuka/muat ulang
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
                let konfirmasi = confirm("Yakin ingin mengubah data?");
                if (!konfirmasi) {
                    e.preventDefault(); // Batalkan submit jika user klik Cancel
                }
            }
        });

        $(document).on('click', '#tambahHumidity', function(e) {
            e.preventDefault();
            let row = `<tr>
                <td><input type="number" step="0.01" name="humidity_equipment[]" class="form-control form-control-sm text-center" placeholder="0.00"></td>
                <td><input type="number" step="0.01" name="humidity_standard[]" class="form-control form-control-sm text-center" placeholder="0.00"></td>
                <td><button type="button" class="btn btn-sm text-danger p-0 border-0 bg-transparent hapus-baris"><i class="fas fa-times"></i></button></td>
            </tr>`;
            $('#tabelHumidity tbody').append(row);
        });

        $(document).on('click', '#tambahTemperature', function(e) {
            e.preventDefault();
            let row = `<tr>
                <td><input type="number" step="0.01" name="temperature_equipment[]" class="form-control form-control-sm text-center" placeholder="0.00"></td>
                <td><input type="number" step="0.01" name="temperature_standard[]" class="form-control form-control-sm text-center" placeholder="0.00"></td>
                <td><button type="button" class="btn btn-sm text-danger p-0 border-0 bg-transparent hapus-baris"><i class="fas fa-times"></i></button></td>
            </tr>`;
            $('#tabelTemperature tbody').append(row);
        });

        $(document).on('click', '.hapus-baris', function() {
            $(this).closest('tr').remove();
        });

        $('#inputPersyaratanSuhu').on('input', function() {
            let val = $(this).val();
            $('#hiddenPersyaratanSuhu').val(val);
            $('.row-persyaratan-suhu').val(val);
        });

        $('#inputPersyaratanKelembaban').on('input', function() {
            let val = $(this).val();
            $('#hiddenPersyaratanKelembaban').val(val);
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