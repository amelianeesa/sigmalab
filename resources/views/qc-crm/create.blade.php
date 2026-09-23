@extends('layouts.app')
@section('title', 'Tambah Baru - QC CRM')

@section('content')
<style>
    .param-table { min-width: max-content; }
    .param-table th, .param-table td { white-space: nowrap; padding-left: 10px !important; padding-right: 10px !important; }
    .param-table td input.form-control { min-width: 90px; }
</style>
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="CRM">
        <li class="breadcrumb-item active" aria-current="page">Input Data</li>
    </x-qc-breadcrumb>
    
    <h2 class="mb-4 fw-bold text-dark">
        <i class="fas fa-plus-circle text-purple me-2"></i>Input Data Harian QC CRM
    </h2>

    @if(session('error'))
    <div class="alert alert-danger shadow-sm border-0">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('qc-crm.store') }}" method="POST" id="formQc">
        @csrf

        {{-- ============================================================ --}}
        {{-- SECTION 1: DATA DASAR PENGUJIAN --}}
        {{-- ============================================================ --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-purple text-white py-3" style="background-color: #6f42c1;">
                <h5 class="mb-0 fw-bold"><i class="fas fa-clipboard-list me-2"></i>Section 1: Data Dasar Pengujian</h5>
            </div>
            <div class="card-body p-4">
                {{-- Info Batch & Tanggal --}}
                <h6 class="mb-3 text-purple border-bottom pb-2" style="color: #6f42c1;"><i class="fas fa-info-circle me-2"></i>Informasi Umum</h6>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Pilih Botol CRM <span class="text-danger">*</span></label>
                        <select name="crm_katalog_id" id="crmKatalogSelect" class="form-select form-select-lg" style="border-color: #6f42c1;" required>
                            <option value="">-- Pilih Botol / Batch CRM --</option>
                            @foreach($activeCrms as $crm)
                                <option value="{{ $crm->id }}" data-nomor="{{ $crm->nomor_lot }}" data-nama="{{ $crm->nama_produk }}">{{ $crm->nomor_lot }} - {{ $crm->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Tanggal Uji <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_uji" class="form-control" value="{{ date('Y-m-d') }}" required max="{{ date('Y-m-d') }}">
                    </div>
                </div>

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
        <div class="card shadow-sm border-0 mb-4" id="section2" style="display: none;">
            <div class="card-header bg-danger text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-table me-2"></i>Section 2: Input Data Pengujian</h5>
            </div>
            <div class="card-body p-0">
                <div class="alert alert-info m-3 rounded-0 border-start border-4 border-info">
                    <i class="fas fa-info-circle me-2"></i> Centang kotak di samping nama parameter untuk mengaktifkan tabel inputnya. Hanya parameter yang dimiliki botol CRM terpilih yang akan tampil di bawah ini.
                </div>

                <div class="accordion accordion-flush" id="parameterAccordion">
                    @foreach($allParameters as $param)
                    @php
                        $code = strtoupper($param->nama_parameter);
                        $pid = $param->parameter_uji_id;
                    @endphp
                    <div class="accordion-item param-accordion-item" id="accordion-item-{{ $pid }}" data-pid="{{ $pid }}" style="display: none;">
                        <h2 class="accordion-header" id="heading-{{ $pid }}">
                            <div class="d-flex align-items-center w-100 bg-light border-bottom">
                                <div class="p-3">
                                    <input class="form-check-input param-enable-check" type="checkbox" name="params[{{ $pid }}][selected]" value="1" data-pid="{{ $pid }}" style="transform: scale(1.5);">
                                </div>
                                <button class="accordion-button collapsed py-3 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $pid }}" aria-expanded="false" aria-controls="collapse-{{ $pid }}">
                                    {{ $code }} - {{ $param->satuan }}
                                    <span class="badge bg-purple ms-3 px-3 py-2 ms-auto d-none cert-badge-{{ $pid }}" style="background-color: #6f42c1;">
                                        True Value: <span class="cert-val-{{ $pid }}"></span> ± <span class="cert-u-{{ $pid }}"></span>
                                    </span>
                                </button>
                            </div>
                        </h2>
                        <div id="collapse-{{ $pid }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $pid }}" data-bs-parent="#parameterAccordion">
                            <div class="accordion-body bg-white p-4">

                                <div class="d-flex justify-content-between align-items-end border-bottom pb-2 mb-3">
                                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-flask text-primary me-2"></i>Pengujian {{ $code }}</h5>
                                </div>
                                <div class="table-responsive pb-2">
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
                                            @elseif(in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
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
                                                <td class="align-middle fw-bold bg-light border-bottom-0">
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
                                                @elseif($code === 'ASH')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-1" name="params[{{ $pid }}][mentah][m1_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-1" name="params[{{ $pid }}][mentah][m2m1_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-1" name="params[{{ $pid }}][mentah][m3_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m3m1-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>
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
                                                    <td class="align-middle out-db-1 fw-bold text-success">-</td>
                                                @elseif(in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-mass-1" name="params[{{ $pid }}][mentah][mass_1]" disabled></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" disabled></td>
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
                                                    <td class="align-middle out-db-1 fw-bold text-success">-</td>
                                                @else
                                                    <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" disabled></td>
                                                @endif
                                            </tr>

                                            <!-- DUPLO -->
                                            <tr class="row-entry">
                                                <td class="fw-bold text-start border-start-0">
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
                                                @elseif(in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
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

                                            <tr class="tr-avg bg-light">
                                                @php
                                                    $colSpan = 2;
                                                    if (in_array($code, ['IM', 'ASH'])) $colSpan = 7;
                                                    elseif ($code === 'VM') $colSpan = 9;
                                                    elseif (in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)'])) $colSpan = 3;
                                                    elseif (in_array($code, ['CV', 'GCV'])) $colSpan = 10;
                                                @endphp
                                                <td colspan="{{ $colSpan }}" class="text-end fw-bold pe-3">EVALUASI HASIL & RATA-RATA:</td>
                                                
                                                @if($code === 'IM')
                                                    <td class="align-middle out-diff fw-bold">-</td>
                                                    <td class="align-middle out-tol fw-bold">-</td>
                                                    <td class="align-middle out-avg-adb fw-bold text-primary fs-6">-</td>
                                                @elseif($code === 'ASH' || $code === 'VM')
                                                    <td class="align-middle out-diff fw-bold">-</td>
                                                    <td class="align-middle out-tol fw-bold">-</td>
                                                    <td class="align-middle out-avg-adb fw-bold text-primary fs-6">-</td>
                                                    <td class="align-middle out-avg-db fw-bold text-success fs-6">-</td>
                                                    <td class="bg-transparent border-0"></td>
                                                @elseif(in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
                                                    <td class="align-middle out-avg-adb fw-bold text-primary fs-6">-</td>
                                                    <td class="align-middle out-avg-db fw-bold text-success fs-6">-</td>
                                                    <td class="bg-transparent border-0"></td>
                                                @elseif(in_array($code, ['CV', 'GCV']))
                                                    <td class="align-middle out-avg-adb fw-bold text-primary fs-6">-</td>
                                                    <td class="align-middle out-avg-db fw-bold text-success fs-6">-</td>
                                                    <td class="bg-transparent border-0"></td>
                                                @else
                                                    <td class="align-middle out-diff fw-bold">-</td>
                                                    <td class="align-middle out-avg-adb fw-bold text-primary fs-6">-</td>
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

    const section2 = document.getElementById('section2');
    window.crmCertificates = {};

    function resetSection2() {
        if (section2) section2.style.display = 'none';
        document.querySelectorAll('.param-accordion-item').forEach(item => {
            item.style.display = 'none';
        });
        document.querySelectorAll('.param-enable-check').forEach(chk => {
            chk.checked = false;
        });
        document.querySelectorAll('.cert-badge-, [class*="cert-badge-"]').forEach(b => b.classList.add('d-none'));
        document.querySelectorAll('.btn-evaluasi').forEach(b => b.classList.add('d-none'));
    }

    function showParametersForCrm(data) {
        // 1. Tampilkan Section 2
        if (section2) section2.style.display = 'block';

        // 2. Sembunyikan dulu SEMUA accordion item (reset dari pilihan sebelumnya)
        document.querySelectorAll('.param-accordion-item').forEach(item => {
            item.style.display = 'none';
        });
        document.querySelectorAll('.param-enable-check').forEach(chk => {
            chk.checked = false;
            const table = document.getElementById('table-' + chk.dataset.pid);
            if (table) {
                table.querySelectorAll('input:not([type="hidden"])').forEach(inp => {
                    inp.disabled = true;
                    inp.value = '';
                });
            }
        });

        // 3. Tampilkan hanya parameter yang dimiliki botol CRM ini
        data.forEach(item => {
            const pid = String(item.parameter_uji_id);
            const accItem = document.getElementById('accordion-item-' + pid);
            if (accItem) accItem.style.display = 'block';

            const certBadge = document.querySelector(`.cert-badge-${pid}`);
            const certVal = document.querySelector(`.cert-val-${pid}`);
            const certU = document.querySelector(`.cert-u-${pid}`);
            if (certBadge) certBadge.classList.remove('d-none');
            if (certVal) certVal.textContent = item.cert_value;
            if (certU) certU.textContent = item.cert_u;
        });
    }

    const selectCrm = document.getElementById('crmKatalogSelect');
    if (selectCrm) {
        selectCrm.addEventListener('change', function() {
            const val = this.value;
            window.crmCertificates = {};

            if (!val) {
                resetSection2();
                return;
            }

            fetch('/api/crm-katalog/' + val + '/parameters')
                .then(r => r.json())
                .then(data => {
                    data.forEach(item => {
                        window.crmCertificates[item.parameter_uji_id] = { val: item.cert_value, u: item.cert_u };
                    });

                    showParametersForCrm(data);

                    document.querySelectorAll('.in-hasil-1').forEach(inp => inp.dispatchEvent(new Event('input')));
                })
                .catch(() => {
                    resetSection2();
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

        if(row.closest('table').dataset.code === 'VM') {
            const imTable = document.querySelector('table[data-code="IM"]');
            if(imTable) {
                const imInp = imTable.querySelector(`.in-hasil-${i}`);
                if(imInp && imInp.value) {
                    scope['im'] = parseNum(imInp.value);
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
                        let cleanKey = step.var.toLowerCase().replace(/[^a-z0-9_]/g, '');
                        if(cleanKey !== step.var.toLowerCase()) scope[cleanKey] = res;

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
        });
    }
});
</script>

@endsection