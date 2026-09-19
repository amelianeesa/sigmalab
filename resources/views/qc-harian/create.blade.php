@extends('layouts.app')
@section('title', 'Tambah Baru - QC Harian')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item"><a href="{{ route('qc-harian.index') }}" class="text-decoration-none">Pengujian Harian QC</a></li>
        <li class="breadcrumb-item active">Input Data</li>
    </x-qc-breadcrumb>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">
            <i class="fas fa-plus-circle text-danger me-2"></i>Input Data Harian QC
        </h2>
        <a href="{{ route('parameter-uji.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm" target="_blank">
            <i class="fas fa-cogs me-1"></i> Master Parameter Uji
        </a>
    </div>

    @if(session('error'))
    <div class="alert alert-danger shadow-sm border-0">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('qc-harian.store') }}" method="POST" id="formQc">
        @csrf

        {{-- ============================================================ --}}
        {{-- SECTION 1: DATA DASAR PENGUJIAN --}}
        {{-- ============================================================ --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-clipboard-list me-2"></i>Section 1: Data Dasar Pengujian</h5>
            </div>
            <div class="card-body p-4">
                
                {{-- Info Batch & Tanggal --}}
                <h6 class="mb-3 text-primary border-bottom pb-2"><i class="fas fa-info-circle me-2"></i>Informasi Umum</h6>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Kode Batch Aktif</label>
                        <input type="text" class="form-control bg-light" value="{{ $activeBatch->kode_batch }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nama Sampel <span class="text-danger">*</span></label>
                        <input type="text" name="nama_sampel_uji" class="form-control" value="{{ $activeBatch->nama_sampel }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tanggal Uji <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_uji" class="form-control" value="{{ date('Y-m-d') }}" required max="{{ date('Y-m-d') }}">
                    </div>
                </div>

                {{-- Alat Digunakan --}}
                <h6 class="mb-3 text-primary border-bottom pb-2"><i class="fas fa-tools me-2"></i>Alat Digunakan</h6>
                <div class="mb-4">
                    <div class="row">
                        @foreach($alatList as $alat)
                        <div class="col-md-4 mb-2">
                            @php
                                $kalibrasiValid = true;
                                $kalibrasi = $alat->riwayatKalibrasi()->whereNull('deleted_at')->latest('tgl_akhir')->first();
                                if(!$kalibrasi || $kalibrasi->tgl_akhir < now()) {
                                    $kalibrasiValid = false;
                                }
                            @endphp
                            <div class="form-check">
                                <input class="form-check-input alat-checkbox" type="checkbox" name="alat_ids[]" value="{{ $alat->alat_id }}" id="alat_{{ $alat->alat_id }}" {{ in_array($alat->alat_id, old('alat_ids', [])) ? 'checked' : '' }} data-nama="{{ $alat->nama_alat }}" data-valid="{{ $kalibrasiValid ? 'true' : 'false' }}">
                                <label class="form-check-label {{ !$kalibrasiValid ? 'text-danger' : '' }}" for="alat_{{ $alat->alat_id }}">
                                    {{ $alat->nama_alat }} ({{ $alat->kode_alat }})
                                    @if(!$kalibrasiValid) <i class="fas fa-exclamation-triangle ms-1" title="Kedaluwarsa"></i> @endif
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Personil Terlibat --}}
                <h6 class="mb-3 text-primary border-bottom pb-2"><i class="fas fa-users me-2"></i>Personil Terlibat</h6>
                <div class="mb-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%" class="text-center">Pilih</th>
                                    <th>Nama Personil</th>
                                    <th>No Induk</th>
                                    <th>Peran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($personilList as $personil)
                                <tr>
                                    <td class="text-center align-middle">
                                        <input class="form-check-input" type="checkbox" name="personil_ids[]" value="{{ $personil->personil_id }}" id="personil_{{ $personil->personil_id }}" {{ in_array($personil->personil_id, old('personil_ids', [])) ? 'checked' : '' }}>
                                    </td>
                                    <td class="align-middle">
                                        <label for="personil_{{ $personil->personil_id }}" class="mb-0 cursor-pointer">{{ $personil->nama }}</label>
                                    </td>
                                    <td class="align-middle">{{ $personil->no_induk ?? '-' }}</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="personil_peran[{{ $personil->personil_id }}]" value="{{ old('personil_peran.'.$personil->personil_id, 'Analis') }}" placeholder="Peran (mis: Analis)">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Bahan Digunakan --}}
                <h6 class="mb-3 text-primary border-bottom pb-2"><i class="fas fa-box-open me-2"></i>Bahan Digunakan</h6>
                <div class="mb-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%" class="text-center">Pilih</th>
                                    <th>Nama Barang (Bahan)</th>
                                    <th>Sisa Stok (Saldo Akhir)</th>
                                    <th width="20%">Jumlah Digunakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangList as $barang)
                                @php
                                    $saldoAkhir = ($barang->saldo_awal + $barang->penerimaan) - $barang->pengeluaran;
                                    $habis = $saldoAkhir <= 0;
                                @endphp
                                <tr class="{{ $habis ? 'table-danger' : '' }}">
                                    <td class="text-center align-middle">
                                        <input class="form-check-input" type="checkbox" name="barang_ids[]" value="{{ $barang->barang_id }}" id="barang_{{ $barang->barang_id }}" {{ in_array($barang->barang_id, old('barang_ids', [])) ? 'checked' : '' }} {{ $habis ? 'disabled' : '' }}>
                                    </td>
                                    <td class="align-middle">
                                        <label for="barang_{{ $barang->barang_id }}" class="mb-0 cursor-pointer {{ $habis ? 'text-muted' : '' }}">
                                            {{ $barang->nama_barang }} 
                                            @if($habis) <span class="badge bg-danger ms-1">Habis</span> @endif
                                        </label>
                                    </td>
                                    <td class="align-middle">{{ number_format($saldoAkhir, 0, ',', '.') }} {{ $barang->satuan }}</td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="number" step="0.01" min="0" class="form-control barang-input" name="barang_jumlah[{{ $barang->barang_id }}]" value="{{ old('barang_jumlah.'.$barang->barang_id, '') }}" placeholder="0" {{ $habis ? 'disabled' : '' }} data-nama="{{ $barang->nama_barang }}" data-stok="{{ $saldoAkhir }}">
                                            <span class="input-group-text">{{ $barang->satuan }}</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- SECTION 2: INPUT DATA PENGUJIAN --}}
        {{-- ============================================================ --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-danger text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-table me-2"></i>Section 2: Input Data Pengujian</h5>
            </div>
            <div class="card-body p-0">
                <div class="alert alert-info m-3 rounded-0 border-start border-4 border-info">
                    <i class="fas fa-info-circle me-2"></i> Centang kotak di samping nama parameter untuk mengaktifkan tabel inputnya.
                </div>

                <div class="accordion accordion-flush" id="parameterAccordion">
                    @foreach($parameters as $param)
                    @php
                        $code = strtoupper($param->parameterUji->nama_parameter);
                        $pid = $param->parameter_uji_id;
                        $isLocked = \App\Models\QcHarian::where('sampel_inhouse_id', $activeBatch->id)
                            ->where('parameter_uji_id', $pid)
                            ->where('status_evaluasi', 'outlier')
                            ->where('status_investigasi', 'menunggu_investigasi')
                            ->exists();
                    @endphp
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-{{ $pid }}">
                            <div class="d-flex align-items-center w-100 bg-light border-bottom">
                                <div class="p-3">
                                    @if($isLocked)
                                        <i class="fas fa-lock text-danger" title="Menunggu investigasi"></i>
                                    @else
                                        <input class="form-check-input param-enable-check" type="checkbox" name="params[{{ $pid }}][selected]" value="1" data-pid="{{ $pid }}" style="transform: scale(1.5);">
                                    @endif
                                </div>
                                <button class="accordion-button collapsed py-3 {{ $isLocked ? 'text-danger' : 'fw-bold' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $pid }}" aria-expanded="false" aria-controls="collapse-{{ $pid }}">
                                    {{ $code }} - {{ $param->parameterUji->satuan }}
                                    @if($isLocked)
                                        <span class="badge bg-danger ms-3">OUTLIER - MENUNGGU INVESTIGASI (LOCKED)</span>
                                    @endif
                                </button>
                            </div>
                        </h2>
                        <div id="collapse-{{ $pid }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $pid }}" data-bs-parent="#parameterAccordion">
                            <div class="accordion-body p-3">
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle text-center param-table" id="table-{{ $pid }}" data-pid="{{ $pid }}" data-code="{{ $code }}">
                                        <thead class="table-light">
                                            @if($code === 'IM')
                                                <tr>
                                                    <th>PENGULANGAN</th>
                                                    <th>M1</th>
                                                    <th>M2</th>
                                                    <th>M3</th>
                                                    <th>A</th>
                                                    <th>B</th>
                                                    <th class="bg-warning bg-opacity-25">M%</th>
                                                    <th colspan="2">
                                                        ABSOLUTE DIFFERENCE 
                                                        <i class="fas fa-info-circle ms-1 text-primary" data-bs-toggle="tooltip" title="0.09 + (0.1 * AVG)"></i>
                                                    </th>
                                                    <th>AVERAGE %</th>
                                                </tr>
                                            @elseif($code === 'ASH')
                                                <tr>
                                                    <th>PENGULANGAN</th>
                                                    <th>M1</th>
                                                    <th>M2</th>
                                                    <th>M2-M1</th>
                                                    <th>M3</th>
                                                    <th>M3-M1</th>
                                                    <th class="bg-warning bg-opacity-25">ASH%</th>
                                                    <th colspan="2">
                                                        ABSOLUTE DIFFERENCE 
                                                        <i class="fas fa-info-circle ms-1 text-primary" data-bs-toggle="tooltip" title="0.09 + (0.1 * AVG)"></i>
                                                    </th>
                                                    <th>AVERAGE %adb</th>
                                                    <th>%db</th>
                                                    <th>db</th>
                                                </tr>
                                            @elseif($code === 'VM')
                                                <tr>
                                                    <th>PENGULANGAN</th>
                                                    <th>M1</th>
                                                    <th>M2</th>
                                                    <th>M2-M1</th>
                                                    <th>M3</th>
                                                    <th>M2-M3</th>
                                                    <th>LOSS%</th>
                                                    <th>IM</th>
                                                    <th class="bg-warning bg-opacity-25">VM%</th>
                                                    <th colspan="2">
                                                        ABSOLUTE DIFFERENCE 
                                                        <i class="fas fa-info-circle ms-1 text-primary" data-bs-toggle="tooltip" title="0.09 + (0.1 * AVG)"></i>
                                                    </th>
                                                    <th>AVERAGE %adb</th>
                                                    <th>AVERAGE %db</th>
                                                    <th>%db</th>
                                                </tr>
                                            @elseif($code === 'TS')
                                                <tr>
                                                    <th>PENGULANGAN</th>
                                                    <th style="width: 16%">Massa sample</th>
                                                    <th class="bg-warning bg-opacity-25" style="width: 16%">TS (Adb)</th>
                                                    <th style="width: 16%">Average %(adb)</th>
                                                    <th style="width: 16%">Average % (Db)</th>
                                                    <th style="width: 16%">%db</th>
                                                </tr>
                                            @elseif($code === 'CV')
                                                <tr>
                                                    <th>PENGULANGAN</th>
                                                    <th>Weight of Crucible</th>
                                                    <th>Sample Mass</th>
                                                    <th>Primary Result (cal/g)</th>
                                                    <th>Ee</th>
                                                    <th>t</th>
                                                    <th>Volume of Titrant (ml)</th>
                                                    <th>Length of Fuse (cm)</th>
                                                    <th>Total TS</th>
                                                    <th class="bg-warning bg-opacity-25" style="width: 16%">
                                                        Final Result (cal/g) adb 
                                                        <i class="fas fa-info-circle ms-1 text-primary" data-bs-toggle="tooltip" title="(PR - (14.3 * 0.0699 * VT) - (2.3 * LF) - (13.2 * TS * SM)) / SM"></i>
                                                    </th>
                                                    <th>Average Result (cal/g), adb</th>
                                                    <th>Average Result (cal/g), db</th>
                                                    <th>%db</th>
                                                </tr>
                                            @else
                                                <tr>
                                                    <th>PENGULANGAN</th>
                                                    <th class="bg-warning bg-opacity-25">Hasil Uji (adb)</th>
                                                    <th>ABSOLUTE DIFFERENCE</th>
                                                    <th>AVERAGE % (adb)</th>
                                                </tr>
                                            @endif
                                        </thead>
                                        <tbody>
                                            <!-- SIMPLO -->
                                            <tr class="row-entry">
                                                <td class="fw-bold bg-light">
                                                    Simplo (D1)
                                                    <input type="hidden" class="in-d1" name="params[{{ $pid }}][d1]">
                                                    <input type="hidden" class="in-db-1" name="params[{{ $pid }}][db1]">
                                                </td>
                                                
                                                @if($code === 'IM')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-1" name="params[{{ $pid }}][mentah][m1_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-1" name="params[{{ $pid }}][mentah][m3_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-a-1" name="params[{{ $pid }}][mentah][a_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-b-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                                                    <td rowspan="2" class="align-middle out-diff">-</td>
                                                    <td rowspan="2" class="align-middle out-tol fw-bold">-</td>
                                                    <td rowspan="2" class="align-middle out-avg-adb">-</td>
                                                @elseif($code === 'ASH')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-1" name="params[{{ $pid }}][mentah][m1_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-1" name="params[{{ $pid }}][mentah][m2m1_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-1" name="params[{{ $pid }}][mentah][m3_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m3m1-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                                                    <td rowspan="2" class="align-middle out-diff">-</td>
                                                    <td rowspan="2" class="align-middle out-tol fw-bold">-</td>
                                                    <td rowspan="2" class="align-middle out-avg-adb">-</td>
                                                    <td rowspan="2" class="align-middle out-avg-db">-</td>
                                                    <td class="align-middle out-db-1 fw-bold text-success">-</td>
                                                @elseif($code === 'VM')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-1" name="params[{{ $pid }}][mentah][m1_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-1" name="params[{{ $pid }}][mentah][m2m1_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-1" name="params[{{ $pid }}][mentah][m3_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2m3-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" class="form-control form-control-sm in-loss-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td class="bg-warning bg-opacity-10">
                                                        <input type="text" inputmode="decimal" class="form-control form-control-sm in-im-1 fw-bold bg-transparent border-0 text-center text-warning" name="params[{{ $pid }}][mentah][im_d1]" placeholder="IM D1" disabled>
                                                    </td>
                                                    <td class="bg-warning bg-opacity-25"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                                                    <td rowspan="2" class="align-middle out-diff">-</td>
                                                    <td rowspan="2" class="align-middle out-tol fw-bold">-</td>
                                                    <td rowspan="2" class="align-middle out-avg-adb">-</td>
                                                    <td rowspan="2" class="align-middle out-avg-db">-</td>
                                                    <td class="align-middle out-db-1 fw-bold text-success">-</td>
                                                @elseif($code === 'TS')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-mass-1" name="params[{{ $pid }}][mentah][mass_1]" disabled></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" disabled></td>
                                                    <td rowspan="2" class="align-middle out-avg-adb fw-bold">-</td>
                                                    <td rowspan="2" class="align-middle out-avg-db fw-bold">-</td>
                                                    <td class="align-middle out-db-1 fw-bold text-success">-</td>
                                                @elseif($code === 'CV')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-w-1" name="params[{{ $pid }}][mentah][w_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-sm-1" name="params[{{ $pid }}][mentah][sm_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-pr-1" name="params[{{ $pid }}][mentah][pr_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-ee-1" name="params[{{ $pid }}][mentah][ee_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-t-1" name="params[{{ $pid }}][mentah][t_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-vt-1" name="params[{{ $pid }}][mentah][vt_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-lf-1" name="params[{{ $pid }}][mentah][lf_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-ts-1 bg-light border-0" name="params[{{ $pid }}][mentah][ts_1]" readonly tabindex="-1" placeholder="Auto dari TS"></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                                                    <td rowspan="2" class="align-middle out-avg-adb fw-bold">-</td>
                                                    <td rowspan="2" class="align-middle out-avg-db fw-bold">-</td>
                                                    <td class="align-middle out-db-1 fw-bold text-success">-</td>
                                                @else
                                                    <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" disabled></td>
                                                    <td rowspan="2" class="align-middle out-diff">-</td>
                                                    <td rowspan="2" class="align-middle out-avg-adb">-</td>
                                                @endif
                                            </tr>

                                            <!-- DUPLO -->
                                            <tr class="row-entry">
                                                <td class="fw-bold bg-light">
                                                    Duplo (D2)
                                                    <input type="hidden" class="in-d2" name="params[{{ $pid }}][d2]">
                                                    <input type="hidden" class="in-db-2" name="params[{{ $pid }}][db2]">
                                                </td>
                                                
                                                @if($code === 'IM')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-2" name="params[{{ $pid }}][mentah][m1_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-2" name="params[{{ $pid }}][mentah][m3_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-a-2" name="params[{{ $pid }}][mentah][a_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-b-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                                                @elseif($code === 'ASH')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-2" name="params[{{ $pid }}][mentah][m1_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-2" name="params[{{ $pid }}][mentah][m2m1_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-2" name="params[{{ $pid }}][mentah][m3_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m3m1-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                                                    <td class="align-middle out-db-2 fw-bold text-success">-</td>
                                                @elseif($code === 'VM')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-2" name="params[{{ $pid }}][mentah][m1_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-2" name="params[{{ $pid }}][mentah][m2m1_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-2" name="params[{{ $pid }}][mentah][m3_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2m3-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" class="form-control form-control-sm in-loss-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td class="bg-warning bg-opacity-10">
                                                        <input type="text" inputmode="decimal" class="form-control form-control-sm in-im-2 fw-bold bg-transparent border-0 text-center text-warning" name="params[{{ $pid }}][mentah][im_d2]" placeholder="IM D2" disabled>
                                                    </td>
                                                    <td class="bg-warning bg-opacity-25"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                                                    <td class="align-middle out-db-2 fw-bold text-success">-</td>
                                                @elseif($code === 'TS')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-mass-2" name="params[{{ $pid }}][mentah][mass_2]" disabled></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" disabled></td>
                                                    <td class="align-middle out-db-2 fw-bold text-success">-</td>
                                                @elseif($code === 'CV')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-w-2" name="params[{{ $pid }}][mentah][w_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-sm-2" name="params[{{ $pid }}][mentah][sm_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-pr-2" name="params[{{ $pid }}][mentah][pr_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-ee-2" name="params[{{ $pid }}][mentah][ee_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-t-2" name="params[{{ $pid }}][mentah][t_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-vt-2" name="params[{{ $pid }}][mentah][vt_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-lf-2" name="params[{{ $pid }}][mentah][lf_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-ts-2 bg-light border-0" name="params[{{ $pid }}][mentah][ts_2]" readonly tabindex="-1" placeholder="Auto dari TS"></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
                                                    <td class="align-middle out-db-2 fw-bold text-success">-</td>
                                                @else
                                                    <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" disabled></td>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- TOMBOL SUBMIT --}}
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('qc-harian.index') }}" class="btn btn-light border px-4">Batal</a>
            <div>
                <button type="button" class="btn btn-warning px-4 rounded-pill shadow-sm me-2" id="btnDraft">
                    <i class="fas fa-save me-2"></i>Simpan Draft Lokal
                </button>
                <button type="submit" class="btn btn-danger px-5 rounded-pill shadow-sm" id="btnSubmit">
                    <i class="fas fa-check-circle me-2"></i>Simpan & Evaluasi Semua
                </button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.8.0/math.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inject ParameterUji config
    const paramConfigs = {
        @if(isset($allParameters))
            @foreach($allParameters as $param)
                "{{ $param->parameter_uji_id }}": {
                    rumus: {!! json_encode($param->rumus_kalkulasi ?? '') !!},
                    langkah: {!! json_encode($param->langkah_kalkulasi ?? []) !!},
                    toleransi: {!! json_encode($param->toleransi_duplo ?? '') !!}
                },
            @endforeach
        @elseif(isset($parameters))
            @foreach($parameters as $param)
                "{{ $param->parameter_uji_id }}": {
                    rumus: {!! json_encode($param->parameterUji->rumus_kalkulasi ?? '') !!},
                    langkah: {!! json_encode($param->parameterUji->langkah_kalkulasi ?? []) !!},
                    toleransi: {!! json_encode($param->parameterUji->toleransi_duplo ?? '') !!}
                },
            @endforeach
        @endif
    };

    function parseNum(val) {
        const n = parseFloat(val);
        return isNaN(n) ? 0 : n;
    }

        const selectCrm = document.getElementById('crm_katalog_id');
    if(selectCrm) {
        selectCrm.addEventListener('change', function() {
            const val = this.value;
            window.crmCertificates = {};
            if(!val) {
                document.querySelectorAll('.btn-evaluasi').forEach(b => b.classList.add('d-none'));
                return;
            }
            fetch('/api/crm-katalog/' + val + '/parameters')
                .then(r => r.json())
                .then(data => {
                    data.forEach(item => {
                        window.crmCertificates[item.parameter_uji_id] = { val: item.cert_value, u: item.cert_u };
                    });
                    document.querySelectorAll('.in-hasil-1').forEach(inp => inp.dispatchEvent(new Event('input')));
                });
        });
    }
    // Kalkulasi per baris
    function calculateRow(row, pid, isSyncing = false) {
        if(!row) return;
        
        let config = paramConfigs[pid];
        if(!config) return;

        let i = row.querySelector('.in-hasil-1') ? 1 : 2;
        let scope = {};

        // Extract all inputs matching .in-*
        const inputs = row.querySelectorAll('input[class*="in-"]');
        inputs.forEach(inp => {
            const match = inp.className.match(/in-([a-zA-Z0-9_]+)-\d/);
            if(match) {
                const varName = match[1].toLowerCase();
                scope[varName] = parseNum(inp.value);
            }
        });

        if(row.closest('table').dataset.code === 'CV') {
            const tsTable = document.querySelector('table[data-code="TS"]');
            if(tsTable) {
                const tsInp = tsTable.querySelector(`.in-hasil-${i}`);
                if(tsInp && tsInp.value) {
                    scope['ts'] = parseNum(tsInp.value);
                    const tsRow = row.querySelector(`.in-ts-${i}`);
                    if(tsRow) tsRow.value = tsInp.value;
                }
            }
        }

        // Inject IM value for VM calculations
        if(row.closest('table').dataset.code === 'VM') {
            const imTable = document.querySelector('table[data-code="IM"]');
            if(imTable) {
                const imInp = imTable.querySelector(`.in-hasil-${i}`);
                if(imInp && imInp.value) {
                    scope['im'] = parseNum(imInp.value);
                    // Also auto-fill the VM table's IM input field so db calculation can read it
                    const vmImInp = row.querySelector(`.in-im-${i}`);
                    if(vmImInp && !vmImInp.value) vmImInp.value = imInp.value;
                }
            }
        }

        if(config.langkah && config.langkah.length > 0) {
            config.langkah.forEach(step => {
                if(step.var && step.rumus) {
                    try {
                        let res = math.evaluate(step.rumus.toLowerCase().replace(/\|/g, ''), scope);
                        scope[step.var.toLowerCase()] = res;
                        // Also store under sanitized key (strip special chars) so formulas can find it
                        let cleanKey = step.var.toLowerCase().replace(/[^a-z0-9_]/g, '');
                        if(cleanKey !== step.var.toLowerCase()) scope[cleanKey] = res;
                        
                        // Try original key first, then sanitized key for the input element
                        let targetInp = null;
                        try { targetInp = row.querySelector(`.in-${step.var.toLowerCase()}-${i}`); } catch(ex) {}
                        if(!targetInp) targetInp = row.querySelector(`.in-${cleanKey}-${i}`);
                        if(targetInp) targetInp.value = res.toFixed(4);
                    } catch(e) { }
                }
            });
        }

        let result = 0;
        if(config.rumus) {
            try {
                result = math.evaluate(config.rumus.toLowerCase(), scope);
            } catch(e) {
                if(row.closest('table').dataset.code === 'TS') result = scope['mass'] || 0;
            }
        } else {
            const code = row.closest('table').dataset.code;
            if(code === 'IM') {
                if(scope.m2 > 0) result = ((scope.m2 - scope.m3) / scope.m2) * 100;
            } else if(code === 'ASH') {
                if(scope.m2m1 > 0) result = (scope.m3m1 / scope.m2m1) * 100;
            } else if(code === 'VM') {
                result = (scope.loss || 0) - (scope.im || 0);
            } else if(code === 'TS') {
                result = scope.mass || 0;
            }
        }

        const inHasil = row.querySelector(`.in-hasil-${i}`);
        const inD = row.querySelector(`.in-d${i}`); 
        if(inHasil && !inHasil.hasAttribute('disabled')) {
            const dec = (row.closest('table').dataset.code === 'CV') ? 0 : 2;
            inHasil.value = result.toFixed(dec);
        }
        if(inD) inD.value = result.toFixed(4);

        const tbody = row.closest('tbody');
        const d1 = parseNum(tbody.querySelector('.in-d1')?.value || tbody.querySelector('.in-hasil-1')?.value);
        const d2 = parseNum(tbody.querySelector('.in-d2')?.value || tbody.querySelector('.in-hasil-2')?.value);

        if(d1 > 0 && d2 > 0) {
            const diff = Math.abs(d1 - d2);
            const diffStr = diff.toFixed(2);
            const avg = (d1 + d2) / 2;
            const avgStr = avg.toFixed(2);

            const outDiff = tbody.querySelector('.out-diff');
            const outTol = tbody.querySelector('.out-tol');
            
            if(outDiff) outDiff.textContent = diffStr;

            if(outTol && config.toleransi) {
                let limit = 0;
                try {
                    limit = math.evaluate(config.toleransi, { AVG: avg, avg: avg });
                } catch(e) {
                    limit = parseFloat(config.toleransi) || 0;
                }
                const isYes = diff < limit;
                outTol.innerHTML = isYes ? `<span class="badge bg-success">YES</span>` : `<span class="badge bg-danger">NO</span>`;
            } else if(outTol) {
                const isYes = diff < (0.09 + (0.1 * avg));
                outTol.innerHTML = isYes ? `<span class="badge bg-success">YES</span>` : `<span class="badge bg-danger">NO</span>`;
            }
            
            const outAvg = tbody.querySelector('.out-avg-adb');
            if(outAvg) outAvg.textContent = avgStr;

            const code = tbody.closest('table').dataset.code;
            if (code === 'ASH' || code === 'VM' || code === 'TS' || code === 'CV') {
                const outDb1 = tbody.querySelector('.out-db-1');
                const outDb2 = tbody.querySelector('.out-db-2');
                const inDb1 = tbody.querySelector('.in-db-1');
                const inDb2 = tbody.querySelector('.in-db-2');
                const outAvgDb = tbody.querySelector('.out-avg-db');
                
                let im1 = 0, im2 = 0;
                if (code === 'VM') {
                    im1 = parseNum(tbody.querySelector('.in-im-1')?.value);
                    im2 = parseNum(tbody.querySelector('.in-im-2')?.value);
                    // Fallback to IM table values if VM's own IM inputs are empty
                    if(im1 === 0 || im2 === 0) {
                        const imTable = document.querySelector('table[data-code="IM"]');
                        if(imTable) {
                            const imCheck = document.querySelector(`.param-enable-check[data-pid="${imTable.dataset.pid}"]`);
                            if(imCheck && imCheck.checked) {
                                if(im1 === 0) im1 = parseNum(imTable.querySelector('.in-hasil-1')?.value);
                                if(im2 === 0) im2 = parseNum(imTable.querySelector('.in-hasil-2')?.value);
                            }
                        }
                    }
                } else {
                    const imTable = document.querySelector('table[data-code="IM"]');
                    if(imTable) {
                        const imCheck = document.querySelector(`.param-enable-check[data-pid="${imTable.dataset.pid}"]`);
                        if(imCheck && imCheck.checked) {
                            im1 = parseNum(imTable.querySelector('.in-hasil-1')?.value);
                            im2 = parseNum(imTable.querySelector('.in-hasil-2')?.value);
                        }
                    }
                }

                let db1 = 0, db2 = 0;
                if(im1 > 0) {
                    db1 = d1 * (100 / (100 - im1));
                    if(outDb1) outDb1.textContent = db1.toFixed(2);
                    if(inDb1) inDb1.value = db1.toFixed(4);
                }
                if(im2 > 0) {
                    db2 = d2 * (100 / (100 - im2));
                    if(outDb2) outDb2.textContent = db2.toFixed(2);
                    if(inDb2) inDb2.value = db2.toFixed(4);
                }

                if(im1 > 0 && im2 > 0) {
                    const avgDb = (db1 + db2) / 2;
                    if(outAvgDb) outAvgDb.textContent = avgDb.toFixed(2);
                }
            }
            
            let evalVal = parseFloat(avgStr);
            const codeEval = tbody.closest('table').dataset.code;
            if (codeEval === 'ASH' || codeEval === 'VM' || codeEval === 'TS' || codeEval === 'CV') {
                const outAvgDb = tbody.querySelector('.out-avg-db');
                if (outAvgDb && outAvgDb.textContent && !isNaN(parseFloat(outAvgDb.textContent))) {
                    evalVal = parseFloat(outAvgDb.textContent);
                }
            }

            if(window.location.href.includes('qc-harian')) {
                const trAvg = tbody.querySelector('.tr-avg');
                if(trAvg) {
                    const mean = parseFloat(trAvg.dataset.mean);
                    const sd = parseFloat(trAvg.dataset.sd);
                    const btnEval = trAvg.querySelector('.btn-evaluasi');
                    const formEval = document.getElementById('eval_' + pid);
                    
                    if(mean && sd && btnEval && formEval) {
                        btnEval.classList.remove('d-none');
                        const statusEval = formEval.querySelector('.status-eval');
                        if(statusEval) {
                            if(evalVal >= (mean - 2*sd) && evalVal <= (mean + 2*sd)) {
                                statusEval.value = 'inlier';
                                btnEval.className = 'btn btn-sm fw-bold btn-success btn-evaluasi mt-1';
                                btnEval.innerHTML = '<i class="fas fa-check-circle"></i> STATUS: INLIER';
                            } else if((evalVal >= (mean - 3*sd) && evalVal < (mean - 2*sd)) || (evalVal > (mean + 2*sd) && evalVal <= (mean + 3*sd))) {
                                statusEval.value = 'warning';
                                btnEval.className = 'btn btn-sm fw-bold btn-warning btn-evaluasi mt-1';
                                btnEval.innerHTML = '<i class="fas fa-exclamation-triangle"></i> STATUS: WARNING';
                            } else {
                                statusEval.value = 'outlier';
                                btnEval.className = 'btn btn-sm fw-bold btn-danger btn-evaluasi mt-1';
                                btnEval.innerHTML = '<i class="fas fa-times-circle"></i> STATUS: OUTLIER';
                            }
                        }
                        const avgInp = formEval.querySelector('.nilai-akhir-input');
                        if(avgInp) avgInp.value = evalVal.toFixed(2);
                    }
                }
            }
            
            if(window.location.href.includes('qc-crm')) {
                const trAvg = tbody.querySelector('.tr-avg');
                if(trAvg) {
                    const certData = window.crmCertificates[pid];
                    const btnEval = trAvg.querySelector('.btn-evaluasi');
                    const formEval = document.getElementById('eval_' + pid);

                    if (certData && btnEval && formEval) {
                        btnEval.classList.remove('d-none');
                        const statusEval = formEval.querySelector('.status-eval');
                        const v = certData.val;
                        const u = certData.u;
                        
                        if(statusEval) {
                            if(evalVal >= (v - u) && evalVal <= (v + u)) {
                                statusEval.value = 'inlier';
                                btnEval.className = 'btn btn-sm fw-bold btn-success btn-evaluasi mt-1';
                                btnEval.innerHTML = '<i class="fas fa-check-circle"></i> STATUS: INLIER';
                            } else {
                                statusEval.value = 'outlier';
                                btnEval.className = 'btn btn-sm fw-bold btn-danger btn-evaluasi mt-1';
                                btnEval.innerHTML = '<i class="fas fa-times-circle"></i> STATUS: OUTLIER';
                            }
                        }
                        const avgInp = formEval.querySelector('.nilai-akhir-input');
                        if(avgInp) avgInp.value = evalVal.toFixed(2);
                    }
                }
            }
        }
    }

    document.querySelectorAll('.param-table input').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('.row-entry');
            const pid = this.closest('table').dataset.pid;
            
            if(this.classList.contains('in-hasil-1') || this.classList.contains('in-hasil-2')) {
                const i = this.classList.contains('in-hasil-1') ? 1 : 2;
                const hiddenInp = row.querySelector(`.in-d${i}`);
                if(hiddenInp) hiddenInp.value = this.value;
            }
            
            calculateRow(row, pid, true);
        });
    });

    const formQc = document.getElementById('formQc');
    document.querySelectorAll('.param-enable-check').forEach(check => {
        check.addEventListener('change', function() {
            const pid = this.dataset.pid;
            const table = document.getElementById('table-' + pid);
            if(table) {
                const inputs = table.querySelectorAll('input:not([type="hidden"])');
                if(this.checked) {
                    inputs.forEach(inp => { if(!inp.classList.contains('bg-light')) inp.disabled = false; });
                } else {
                    inputs.forEach(inp => {
                        inp.disabled = true;
                        inp.value = '';
                    });
                }
            }
        });
    });

    if(formQc) {
        formQc.addEventListener('submit', function(e) {
            const checked = document.querySelectorAll('.param-enable-check:checked');
            if (checked.length === 0) {
                e.preventDefault();
                Swal.fire('Belum Ada Parameter', 'Silakan centang minimal 1 parameter yang ingin diuji.', 'warning');
                return;
            }
            // Clear draft on successful submit
            const batchKey = 'qc_harian_draft_{{ $activeBatch->id ?? "default" }}';
            localStorage.removeItem(batchKey);
        });
    }

    // ============================================================
    // DRAFT SAVE / LOAD FUNCTIONALITY
    // ============================================================
    const DRAFT_KEY = 'qc_harian_draft_{{ $activeBatch->id ?? "default" }}';

    // SAVE DRAFT
    const btnDraft = document.getElementById('btnDraft');
    if(btnDraft) {
        btnDraft.addEventListener('click', function() {
            const draft = {};

            // Save tanggal uji & nama sampel
            const tanggal = document.querySelector('input[name="tanggal_uji"]');
            const namaSampel = document.querySelector('input[name="nama_sampel_uji"]');
            if(tanggal) draft.tanggal_uji = tanggal.value;
            if(namaSampel) draft.nama_sampel = namaSampel.value;

            // Save checked alat
            draft.alat_ids = [];
            document.querySelectorAll('.alat-checkbox:checked').forEach(cb => {
                draft.alat_ids.push(cb.value);
            });

            // Save checked personil + peran
            draft.personil_ids = [];
            draft.personil_peran = {};
            document.querySelectorAll('input[name="personil_ids[]"]:checked').forEach(cb => {
                draft.personil_ids.push(cb.value);
            });
            document.querySelectorAll('input[name^="personil_peran["]').forEach(inp => {
                const match = inp.name.match(/personil_peran\[(\d+)\]/);
                if(match) draft.personil_peran[match[1]] = inp.value;
            });

            // Save checked barang + jumlah
            draft.barang_ids = [];
            draft.barang_jumlah = {};
            document.querySelectorAll('input[name="barang_ids[]"]:checked').forEach(cb => {
                draft.barang_ids.push(cb.value);
            });
            document.querySelectorAll('.barang-input').forEach(inp => {
                const match = inp.name.match(/barang_jumlah\[(\d+)\]/);
                if(match && inp.value) draft.barang_jumlah[match[1]] = inp.value;
            });

            // Save parameter data (checkboxes + all input values)
            draft.params = {};
            document.querySelectorAll('.param-enable-check').forEach(check => {
                const pid = check.dataset.pid;
                const table = document.getElementById('table-' + pid);
                if(!table) return;
                
                draft.params[pid] = {
                    selected: check.checked,
                    inputs: {}
                };

                // Save all input values in the table
                table.querySelectorAll('input').forEach(inp => {
                    // Use a combination of class and row to identify inputs
                    const classes = Array.from(inp.classList).filter(c => c.startsWith('in-'));
                    if(classes.length > 0) {
                        const key = classes[0];
                        draft.params[pid].inputs[key] = inp.value;
                    }
                });
            });

            draft.saved_at = new Date().toLocaleString('id-ID');

            try {
                localStorage.setItem(DRAFT_KEY, JSON.stringify(draft));
                Swal.fire({
                    icon: 'success',
                    title: 'Draft Tersimpan!',
                    html: `<p>Data berhasil disimpan ke penyimpanan lokal browser.</p><small class="text-muted">Tersimpan: ${draft.saved_at}</small>`,
                    timer: 2500,
                    showConfirmButton: false,
                    toast: false
                });
            } catch(err) {
                Swal.fire('Gagal Menyimpan', 'Terjadi kesalahan: ' + err.message, 'error');
            }
        });
    }

    // LOAD DRAFT on page load
    (function loadDraft() {
        const raw = localStorage.getItem(DRAFT_KEY);
        if(!raw) return;

        let draft;
        try { draft = JSON.parse(raw); } catch(e) { return; }
        if(!draft || !draft.params) return;

        Swal.fire({
            icon: 'question',
            title: 'Draft Ditemukan',
            html: `<p>Ada draft lokal tersimpan.</p><small class="text-muted">Tersimpan: ${draft.saved_at || '-'}</small>`,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-undo me-1"></i> Muat Draft',
            cancelButtonText: 'Abaikan',
            confirmButtonColor: '#f0ad4e',
        }).then(result => {
            if(!result.isConfirmed) return;

            // Restore tanggal & nama sampel
            if(draft.tanggal_uji) {
                const tanggal = document.querySelector('input[name="tanggal_uji"]');
                if(tanggal) tanggal.value = draft.tanggal_uji;
            }
            if(draft.nama_sampel) {
                const namaSampel = document.querySelector('input[name="nama_sampel_uji"]');
                if(namaSampel) namaSampel.value = draft.nama_sampel;
            }

            // Restore alat checkboxes
            if(draft.alat_ids) {
                draft.alat_ids.forEach(id => {
                    const cb = document.querySelector(`.alat-checkbox[value="${id}"]`);
                    if(cb) cb.checked = true;
                });
            }

            // Restore personil checkboxes + peran
            if(draft.personil_ids) {
                draft.personil_ids.forEach(id => {
                    const cb = document.getElementById('personil_' + id);
                    if(cb) cb.checked = true;
                });
            }
            if(draft.personil_peran) {
                Object.keys(draft.personil_peran).forEach(id => {
                    const inp = document.querySelector(`input[name="personil_peran[${id}]"]`);
                    if(inp) inp.value = draft.personil_peran[id];
                });
            }

            // Restore barang checkboxes + jumlah
            if(draft.barang_ids) {
                draft.barang_ids.forEach(id => {
                    const cb = document.getElementById('barang_' + id);
                    if(cb && !cb.disabled) cb.checked = true;
                });
            }
            if(draft.barang_jumlah) {
                Object.keys(draft.barang_jumlah).forEach(id => {
                    const inp = document.querySelector(`input[name="barang_jumlah[${id}]"]`);
                    if(inp && !inp.disabled) inp.value = draft.barang_jumlah[id];
                });
            }

            // Restore parameter data
            Object.keys(draft.params).forEach(pid => {
                const paramDraft = draft.params[pid];
                const check = document.querySelector(`.param-enable-check[data-pid="${pid}"]`);
                const table = document.getElementById('table-' + pid);
                if(!check || !table) return;

                if(paramDraft.selected) {
                    // Enable checkbox and enable inputs
                    check.checked = true;
                    check.dispatchEvent(new Event('change'));

                    // Restore input values after a short delay to ensure inputs are enabled
                    setTimeout(() => {
                        if(paramDraft.inputs) {
                            Object.keys(paramDraft.inputs).forEach(className => {
                                const inp = table.querySelector('.' + className);
                                if(inp && paramDraft.inputs[className]) {
                                    inp.value = paramDraft.inputs[className];
                                }
                            });

                            // Trigger recalculation for both rows
                            const rows = table.querySelectorAll('.row-entry');
                            rows.forEach(row => {
                                calculateRow(row, pid);
                            });
                        }
                    }, 100);
                }
            });

            Swal.fire({
                icon: 'success',
                title: 'Draft Dimuat!',
                text: 'Data draft berhasil dipulihkan.',
                timer: 2000,
                showConfirmButton: false
            });
        });
    })();
});
</script>



@endsection





