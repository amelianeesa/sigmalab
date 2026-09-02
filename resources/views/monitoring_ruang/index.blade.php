@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark fs-4">Pencatatan Monitoring Suhu dan Kelembaban Udara</h2>
        <div>
            <a href="{{ route('inventori.monitoring.index') }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-sync-alt me-1"></i> Refresh Data</a>
        </div>
    </div>

    <!-- Header Filter & Informasi Alat -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('inventori.monitoring.index') }}" id="filterForm" class="row g-3 align-items-center">

                <div class="col-md-3">
                    <label class="fw-bold text-muted small">PILIH ALAT</label>
                    <select name="alat_id" id="selectAlat" class="form-select">
                        <option value="">-- Pilih Alat --</option>
                        @foreach($daftarAlat as $alat)
                            <option value="{{ $alat->alat_id ?? $alat->alat_id }}" {{ $alatId == ($alat->alat_id ?? $alat->id) ? 'selected' : '' }}>
                                {{ $alat->nama_alat }} ({{ $alat->code ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="fw-bold text-muted small">NAMA RUANGAN</label>
                    <input type="text" name="nama_ruangan" value="{{ $ruangan }}" placeholder="Input Ruangan" class="form-control" onchange="this.form.submit()">
                </div>

                <div class="col-md-2">
                    <label class="fw-bold text-muted small">BULAN</label>
                    <select name="bulan" class="form-select" onchange="this.form.submit()">
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $b)
                            <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="fw-bold text-muted small">TAHUN</label>
                    <input type="number" name="tahun" value="{{ $tahun }}" class="form-control" onchange="this.form.submit()">
                </div>

                <!-- Input tersembunyi untuk membawa nilai persyaratan -->
                <input type="hidden" name="persyaratan_suhu" id="hiddenPersyaratanSuhu" value="{{ $persyaratanSuhu }}">
                <input type="hidden" name="persyaratan_kelembaban" id="hiddenPersyaratanKelembaban" value="{{ $persyaratanKelembaban }}">
            </form>
            
            <hr class="my-3">
            <div class="row text-secondary small align-items-center">
                {{-- <div class="col-md-6">
                    <p class="mb-1"><strong>Merk / Type:</strong> {{ $alatAktif->merk ?? '-' }} / {{ $alatAktif->type ?? '-' }}</p>
                    <p class="mb-0"><strong>No. Serial:</strong> {{ $alatAktif->serial_number ?? '-' }}</p>
                </div> --}}
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <strong style="width: 160px;">Persyaratan Suhu:</strong>
                        <input type="text" id="inputPersyaratanSuhu" placeholder="...°C" class="form-control form-control-sm w-50" placeholder="Contoh: 17 - 23 °C">
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <strong style="width: 160px;">Persyaratan Kelembaban:</strong>
                        <input type="text" id="inputPersyaratanKelembaban" placeholder="...%" class="form-control form-control-sm w-50" placeholder="Contoh: 45 - 65 %">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Monitoring Harian (1 - 31) -->
    <div class="card border-0 shadow-sm">
        <div class="card-body px-0 pt-0">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle small mb-0">
                    <thead class="table-light align-middle">
                        <tr>
                            <th rowspan="2" style="width: 55px;">Tanggal</th>
                            <th rowspan="2" style="width: 170px;">Waktu Pencatatan<br><small class="text-muted fw-normal">(Pagi / Sore)</small></th>
                            <th colspan="4" class="py-2">Suhu (°C)</th>
                            <th colspan="4" class="py-2">Kelembaban (%)</th>
                            <th rowspan="2" style="width: 100px;">Paraf<br>(1 / 2)</th>
                            <th rowspan="2" style="width: 140px;">Keterangan</th>
                            <th rowspan="2" style="width: 65px;">Aksi</th>
                        </tr>
                        <tr>
                            <th class="py-2">Pembacaan 1</th>
                            <th class="py-2">Koreksi 1</th>
                            <th class="py-2">Pembacaan 2</th>
                            <th class="py-2">Koreksi 2</th>
                            <th class="py-2">Pembacaan 1</th>
                            <th class="py-2">Koreksi 1</th>
                            <th class="py-2">Pembacaan 2</th>
                            <th class="py-2">Koreksi 2</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($tgl = 1; $tgl <= 31; $tgl++)
                        @php $row = $monitoringData[$tgl] ?? null; @endphp
                        <form action="{{ route('inventori.monitoring.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="alat_id" value="{{ $alatId }}">
                            <input type="hidden" name="bulan" value="{{ $bulan }}">
                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                            <input type="hidden" name="nama_ruangan" value="{{ $ruangan }}">
                            <input type="hidden" name="tanggal" value="{{ $tgl }}">
                            
                            <!-- Nilai persyaratan disinkronkan otomatis dari input atas -->
                            <input type="hidden" name="persyaratan_suhu" class="row-persyaratan-suhu" value="{{ $persyaratanSuhu }}">
                            <input type="hidden" name="persyaratan_kelembaban" class="row-persyaratan-kelembaban" value="{{ $persyaratanKelembaban }}">

                            <tr>
                                <td class="fw-bold bg-light">{{ $tgl }}</td>
                                <td>
                                    <div class="input-group input-group-sm px-1">
                                        <input type="text" name="waktu_1" value="{{ $row?->waktu_1 }}" class="form-control text-center px-1" placeholder="08:00">
                                        <span class="input-group-text px-1 bg-white text-muted">-</span>
                                        <input type="text" name="waktu_2" value="{{ $row?->waktu_2 }}" class="form-control text-center px-1" placeholder="13:00">
                                    </div>
                                </td>
                                <!-- Suhu -->
                                <td><input type="number" step="0.01" name="suhu_pembacaan_1" value="{{ $row?->suhu_pembacaan_1 }}" class="form-control form-control-sm text-center px-1"></td>
                                <td class="bg-light fw-bold text-success">{{ $row?->suhu_terkoreksi_1 ?? '-' }}</td>
                                <td><input type="number" step="0.01" name="suhu_pembacaan_2" value="{{ $row?->suhu_pembacaan_2 }}" class="form-control form-control-sm text-center px-1"></td>
                                <td class="bg-light fw-bold text-success">{{ $row?->suhu_terkoreksi_2 ?? '-' }}</td>
                                <!-- Kelembaban -->
                                <td><input type="number" step="0.01" name="kelembaban_pembacaan_1" value="{{ $row?->kelembaban_pembacaan_1 }}" class="form-control form-control-sm text-center px-1"></td>
                                <td class="bg-light fw-bold text-success">{{ $row?->kelembaban_terkoreksi_1 ?? '-' }}</td>
                                <td><input type="number" step="0.01" name="kelembaban_pembacaan_2" value="{{ $row?->kelembaban_pembacaan_2 }}" class="form-control form-control-sm text-center px-1"></td>
                                <td class="bg-light fw-bold text-success">{{ $row?->kelembaban_terkoreksi_2 ?? '-' }}</td>
                                <!-- Paraf Checkbox Sesi 1 & 2 -->
                                <td>
                                    <div class="d-flex justify-content-center gap-2 align-items-center">
                                        <input type="checkbox" name="paraf_1" value="1" {{ $row?->paraf_1 ? 'checked' : '' }} class="form-check-input" title="Paraf Sesi 1">
                                        <input type="checkbox" name="paraf_2" value="1" {{ $row?->paraf_2 ? 'checked' : '' }} class="form-check-input" title="Paraf Sesi 2">
                                    </div>
                                </td>
                                <td>
                                    <textarea name="keterangan" class="form-control form-control-sm" rows="1" placeholder="Keterangan..." style="resize: vertical; min-height: 50px; max-height: 120px;"></textarea>
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-sm btn-success w-100" title="Simpan Baris"><i class="fas fa-save"></i></button>
                                </td>
                            </tr>
                        </form>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery wajib dimuat sebelum Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('#selectAlat').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Alat --',
            allowClear: true
        });

        $('#selectAlat').on('change', function() {
            $('#filterForm').submit();
        });

        // Sinkronisasi input persyaratan di header ke seluruh input tersembunyi di tiap baris tabel
        const inputSuhu = document.getElementById('inputPersyaratanSuhu');
        const inputKelembaban = document.getElementById('inputPersyaratanKelembaban');

        inputSuhu.addEventListener('input', function() {
            document.getElementById('hiddenPersyaratanSuhu').value = this.value;
            document.querySelectorAll('.row-persyaratan-suhu').forEach(el => el.value = this.value);
        });

        inputKelembaban.addEventListener('input', function() {
            document.getElementById('hiddenPersyaratanKelembaban').value = this.value;
            document.querySelectorAll('.row-persyaratan-kelembaban').forEach(el => el.value = this.value);
        });
    });
</script>
<style>
    /* Membatasi tinggi dropdown Select2 maksimal 4 baris dan ada scroll */
    .select2-results__options {
        max-height: 160px !important;
        overflow-y: auto !important;
    }
    /* Mempercantik input dalam tabel agar tidak terlalu ketat */
    .table td input.form-control, .table td input.form-control-sm {
        min-width: 55px;
    }
</style>
@endpush