@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item"><a href="{{ route('qc-harian.index') }}" class="text-decoration-none">Pengujian Harian QC</a></li>
        <li class="breadcrumb-item active">Input Data</li>
    </x-qc-breadcrumb>
    <h2 class="mb-4 fw-bold text-dark">
        <i class="fas fa-plus-circle text-danger me-2"></i>Input Data Harian QC
    </h2>

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
                                                    <th colspan="2">ABSOLUTE DIFFERENCE</th>
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
                                                    <th colspan="2">ABSOLUTE DIFFERENCE</th>
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
                                                    <th colspan="2">ABSOLUTE DIFFERENCE</th>
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
                                                    <th class="bg-warning bg-opacity-25">Final Result (cal/g) adb</th>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================
    // LocalStorage Draft Logic
    // ============================================
    const formQc = document.getElementById('formQc');
    const DRAFT_KEY = 'qc_harian_draft';

    function saveDraft() {
        const formData = new FormData(formQc);
        const data = Object.fromEntries(formData.entries());
        // hapus _token csrf
        delete data['_token'];
        
        // Simpan checkbox yang dicentang secara spesifik
        const checkboxes = [];
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            if(cb.checked) checkboxes.push({ name: cb.name, value: cb.value });
        });
        
        localStorage.setItem(DRAFT_KEY, JSON.stringify({
            inputs: data,
            checkboxes: checkboxes,
            timestamp: new Date().getTime()
        }));

        Swal.fire({
            icon: 'success',
            title: 'Draft Tersimpan',
            text: 'Data tersimpan sementara di browser ini. Anda bisa melanjutkan pengisian nanti.',
            timer: 2000,
            showConfirmButton: false
        });
    }

    function loadDraft() {
        const draftStr = localStorage.getItem(DRAFT_KEY);
        if(!draftStr) return;
        
        try {
            const draft = JSON.parse(draftStr);
            const draftAge = (new Date().getTime() - draft.timestamp) / (1000 * 60 * 60); // in hours
            if(draftAge > 24) {
                localStorage.removeItem(DRAFT_KEY); // hapus jika lebih dari 24 jam
                return;
            }

            // Restore inputs
            for(const [key, value] of Object.entries(draft.inputs)) {
                const el = formQc.querySelector(`[name="${key}"]`);
                if(el && el.type !== 'checkbox' && el.type !== 'radio' && el.type !== 'hidden') {
                    el.value = value;
                }
            }

            // Restore checkboxes
            draft.checkboxes.forEach(cb => {
                const el = formQc.querySelector(`input[type="checkbox"][name="${cb.name}"][value="${cb.value}"]`);
                if(el) {
                    el.checked = true;
                    // trigger change untuk expand accordion
                    el.dispatchEvent(new Event('change'));
                }
            });

            // Trigger calculation for all restored inputs
            document.querySelectorAll('.param-table input:not([type="hidden"])').forEach(inp => {
                if(inp.value) inp.dispatchEvent(new Event('input'));
            });

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            Toast.fire({
                icon: 'info',
                title: 'Draft sebelumnya berhasil dimuat.'
            });
        } catch(e) {
            console.error("Error loading draft", e);
        }
    }

    document.getElementById('btnDraft').addEventListener('click', saveDraft);

    // Auto-save draft every 30 seconds if form has changes
    setInterval(() => {
        // Silent save without alert
        const formData = new FormData(formQc);
        const data = Object.fromEntries(formData.entries());
        delete data['_token'];
        const checkboxes = [];
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            if(cb.checked) checkboxes.push({ name: cb.name, value: cb.value });
        });
        localStorage.setItem(DRAFT_KEY, JSON.stringify({
            inputs: data,
            checkboxes: checkboxes,
            timestamp: new Date().getTime()
        }));
    }, 30000);

    // ============================================
    // Accordion Toggle & Disable Inputs
    // ============================================
    const paramEnables = document.querySelectorAll('.param-enable-check');
    paramEnables.forEach(check => {
        check.addEventListener('change', function() {
            const pid = this.dataset.pid;
            const collapseEl = document.getElementById('collapse-' + pid);
            const accordionButton = document.querySelector(`[aria-controls="collapse-${pid}"]`);
            const inputs = collapseEl.querySelectorAll('input:not([type="hidden"]):not([readonly])');

            if (this.checked) {
                new bootstrap.Collapse(collapseEl, { toggle: false }).show();
                accordionButton.classList.remove('collapsed');
                inputs.forEach(inp => { inp.disabled = false; inp.required = true; });
            } else {
                new bootstrap.Collapse(collapseEl, { toggle: false }).hide();
                accordionButton.classList.add('collapsed');
                inputs.forEach(inp => { 
                    inp.disabled = true; 
                    inp.required = false; 
                    if(inp.classList.contains('in-im-1') || inp.classList.contains('in-im-2')) {
                        // don't clear IM if auto-filled, maybe? Actually just clear it.
                    } else {
                        inp.value = ''; 
                    }
                });
                // Trigger calculation to clear results
                calculateRow(document.querySelector(`#table-${pid} .row-entry`), document.getElementById(`table-${pid}`).dataset.code);
            }
        });
    });

    // ============================================
    // Math Logic from Homogenitas
    // ============================================
    function parseNum(val) {
        if(!val) return 0;
        let v = val.toString().replace(',', '.');
        return isNaN(parseFloat(v)) ? 0 : parseFloat(v);
    }

    // Auto sync IM from IM table to VM table if IM is checked, and trigger DB calc for others
    function syncIM() {
        const imTables = document.querySelectorAll('table[data-code="IM"]');
        if(imTables.length === 0) return;

        const imTable = imTables[0];
        const imCheck = document.querySelector(`.param-enable-check[data-pid="${imTable.dataset.pid}"]`);
        
        let imSimplo = 0, imDuplo = 0;
        if(imCheck && imCheck.checked) {
            imSimplo = parseNum(imTable.querySelector('.row-entry:nth-child(1) .in-hasil-1')?.value);
            imDuplo = parseNum(imTable.querySelector('.row-entry:nth-child(2) .in-hasil-2')?.value);
        }

        // Loop ke tabel lain yang butuh DB
        document.querySelectorAll('.param-table').forEach(table => {
            const code = table.dataset.code;
            if (code === 'VM') {
                const vmIm1 = table.querySelector('.row-entry:nth-child(1) .in-im-1');
                const vmIm2 = table.querySelector('.row-entry:nth-child(2) .in-im-2');
                if(vmIm1 && vmIm2) {
                    vmIm1.value = imSimplo > 0 ? imSimplo.toFixed(2) : '';
                    vmIm2.value = imDuplo > 0 ? imDuplo.toFixed(2) : '';
                }
            }
            
            // Re-calculate the row so the absolute difference and DB update
            if (code === 'ASH' || code === 'VM' || code === 'TS' || code === 'CV') {
                calculateRow(table.querySelector('.row-entry:nth-child(1)'), code, true);
                calculateRow(table.querySelector('.row-entry:nth-child(2)'), code, true);
            }
        });
    }

    // Auto sync TS to CV if TS is checked
    function syncTS() {
        const tsTables = document.querySelectorAll('table[data-code="TS"]');
        if(tsTables.length === 0) return;

        const tsTable = tsTables[0];
        const tsCheck = document.querySelector(`.param-enable-check[data-pid="${tsTable.dataset.pid}"]`);
        
        let tsSimplo = 0, tsDuplo = 0;
        if(tsCheck && tsCheck.checked) {
            tsSimplo = parseNum(tsTable.querySelector('.row-entry:nth-child(1) .in-hasil-1')?.value);
            tsDuplo = parseNum(tsTable.querySelector('.row-entry:nth-child(2) .in-hasil-2')?.value);
        }

        document.querySelectorAll('.param-table').forEach(table => {
            const code = table.dataset.code;
            if (code === 'CV') {
                const cvTs1 = table.querySelector('.row-entry:nth-child(1) .in-ts-1');
                const cvTs2 = table.querySelector('.row-entry:nth-child(2) .in-ts-2');
                if(cvTs1 && cvTs2) {
                    cvTs1.value = tsSimplo > 0 ? tsSimplo.toFixed(2) : '';
                    cvTs2.value = tsDuplo > 0 ? tsDuplo.toFixed(2) : '';
                }
                
                // Trigger recalc on CV to update its Final Result
                calculateRow(table.querySelector('.row-entry:nth-child(1)'), 'CV', true);
                calculateRow(table.querySelector('.row-entry:nth-child(2)'), 'CV', true);
            }
        });
    }

    function calculateRow(row, code, isSyncing = false) {
        if(!row) return;
        
        let hasil = 0;
        let i = row.querySelector('.in-hasil-1') ? 1 : 2; // Simplo or Duplo

        if(code === 'IM') {
            const m1 = parseNum(row.querySelector(`.in-m1-${i}`)?.value);
            const m3 = parseNum(row.querySelector(`.in-m3-${i}`)?.value);
            const a = parseNum(row.querySelector(`.in-a-${i}`)?.value);
            
            if(m1 && a) {
                const m2 = m1 + a;
                row.querySelector(`.in-m2-${i}`).value = m2.toFixed(4);
                if(m3) {
                    const b = m2 - m3;
                    row.querySelector(`.in-b-${i}`).value = b.toFixed(4);
                    hasil = (b / a) * 100;
                }
            }
        } 
        else if(code === 'ASH') {
            const m1 = parseNum(row.querySelector(`.in-m1-${i}`)?.value);
            const m2m1 = parseNum(row.querySelector(`.in-m2m1-${i}`)?.value);
            const m3 = parseNum(row.querySelector(`.in-m3-${i}`)?.value);

            if(m1 && m2m1) {
                const m2 = m1 + m2m1;
                row.querySelector(`.in-m2-${i}`).value = m2.toFixed(4);
                if(m3) {
                    const m3m1 = m3 - m1;
                    row.querySelector(`.in-m3m1-${i}`).value = m3m1.toFixed(4);
                    hasil = (m3m1 / m2m1) * 100;
                }
            }
        }
        else if(code === 'VM') {
            const m1 = parseNum(row.querySelector(`.in-m1-${i}`)?.value);
            const m2m1 = parseNum(row.querySelector(`.in-m2m1-${i}`)?.value);
            const m3 = parseNum(row.querySelector(`.in-m3-${i}`)?.value);
            const im = parseNum(row.querySelector(`.in-im-${i}`)?.value);

            if(m1 && m2m1) {
                const m2 = m1 + m2m1;
                row.querySelector(`.in-m2-${i}`).value = m2.toFixed(4);
                if(m3) {
                    const m2m3 = m2 - m3;
                    row.querySelector(`.in-m2m3-${i}`).value = m2m3.toFixed(4);
                    const loss = (m2m3 / m2m1) * 100;
                    row.querySelector(`.in-loss-${i}`).value = loss.toFixed(4);
                    if(im > 0) {
                        hasil = loss - im;
                    }
                }
            }
        }
        else if(code === 'CV') {
            const sm = parseNum(row.querySelector(`.in-sm-${i}`)?.value);
            const pr = parseNum(row.querySelector(`.in-pr-${i}`)?.value);
            const ee = parseNum(row.querySelector(`.in-ee-${i}`)?.value);
            const t = parseNum(row.querySelector(`.in-t-${i}`)?.value);
            const vt = parseNum(row.querySelector(`.in-vt-${i}`)?.value);
            const lf = parseNum(row.querySelector(`.in-lf-${i}`)?.value);
            const ts = parseNum(row.querySelector(`.in-ts-${i}`)?.value);

            if(sm > 0 && pr > 0 && ee > 0) {
                const outT = row.querySelector(`.in-t-${i}`);
                if (outT) outT.value = ((pr / ee) * sm).toFixed(4);
            }

            const tsInput = row.querySelector(`.in-ts-${i}`)?.value;
            if(sm > 0 && pr > 0 && ee > 0 && vt > 0 && lf > 0 && tsInput !== '') {
                hasil = Math.round(((pr) - (14.3 * 0.0699 * vt) - (2.3 * lf) - (13.2 * ts * sm)) / sm);
            }
        }
        else if(code === 'TS' || true) {
            // TS atau param fallback yg diisi manual
            hasil = parseNum(row.querySelector(`.in-hasil-${i}`)?.value);
        }

        // Output Result
        const outHasil = row.querySelector(`.in-hasil-${i}`);
        if(outHasil && (code === 'IM' || code === 'ASH' || code === 'VM' || code === 'CV')) {
            outHasil.value = hasil > 0 ? hasil.toFixed(2) : '';
        }

        // Set Hidden Input for Controller
        const hiddenInp = row.querySelector(`.in-d${i}`);
        if(hiddenInp) {
            hiddenInp.value = hasil > 0 ? hasil : '';
        }

        if(code === 'IM' && !isSyncing) {
            syncIM();
        }

        if(code === 'TS' && !isSyncing) {
            syncTS();
        }

        // ==========================================
        // Hitung Absolute Difference & Average (Jika D1 dan D2 terisi)
        // ==========================================
        const tbody = row.closest('tbody');
        const d1 = parseNum(tbody.querySelector('.in-hasil-1')?.value);
        const d2 = parseNum(tbody.querySelector('.in-hasil-2')?.value);

        if(d1 > 0 && d2 > 0) {
            const diff = Math.abs(d1 - d2);
            const diffStr = diff.toFixed(2);
            const avg = (d1 + d2) / 2;
            const avgStr = avg.toFixed(2);

            const outDiff = tbody.querySelector('.out-diff');
            const outTol = tbody.querySelector('.out-tol');
            
            if(outDiff) outDiff.textContent = diffStr;

            if(outTol) {
                if (code === 'IM' || code === 'ASH' || code === 'VM') {
                    // Semua yang punya out-tol kita kasih logic toleransi, 
                    // namun user minta IM rumusnya spesifik. Jika parameter lain butuh, bisa disesuaikan.
                    if (code === 'IM') {
                        const limit = 0.09 + (0.1 * avg);
                        const isYes = diff < limit;
                        outTol.innerHTML = isYes ? `<span class="badge bg-success">YES</span>` : `<span class="badge bg-danger">NO</span>`;
                    } else {
                        outTol.textContent = '-'; // Bisa diisi logic ASH/VM nanti
                    }
                }
            }
            
            const outAvg = tbody.querySelector('.out-avg-adb');
            if(outAvg) outAvg.textContent = avgStr;

            // Hitung DB conversion jika parameter butuh DB
            if (code === 'ASH' || code === 'VM' || code === 'TS' || code === 'CV') {
                const outDb1 = tbody.querySelector('.out-db-1');
                const outDb2 = tbody.querySelector('.out-db-2');
                const inDb1 = tbody.querySelector('.in-db-1');
                const inDb2 = tbody.querySelector('.in-db-2');
                const outAvgDb = tbody.querySelector('.out-avg-db');
                
                // Ambil nilai IM dari table IM yang dicentang
                let im1 = 0, im2 = 0;
                if (code === 'VM') {
                    // VM punya input IM manual/otomatis di barisnya sendiri
                    im1 = parseNum(tbody.querySelector('.in-im-1')?.value);
                    im2 = parseNum(tbody.querySelector('.in-im-2')?.value);
                } else {
                    // Ambil dari table IM jika ada
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
                } else {
                    if(outDb1) outDb1.textContent = '-';
                    if(inDb1) inDb1.value = '';
                }

                if(im2 > 0) {
                    db2 = d2 * (100 / (100 - im2));
                    if(outDb2) outDb2.textContent = db2.toFixed(2);
                    if(inDb2) inDb2.value = db2.toFixed(4);
                } else {
                    if(outDb2) outDb2.textContent = '-';
                    if(inDb2) inDb2.value = '';
                }

                if(im1 > 0 && im2 > 0) {
                    const avgDb = ((db1 + db2) / 2).toFixed(2);
                    if(outAvgDb) outAvgDb.textContent = avgDb;
                } else {
                    if(outAvgDb) outAvgDb.textContent = '-';
                }
            }
        } else {
            const outDiff = tbody.querySelector('.out-diff');
            if(outDiff) outDiff.textContent = '-';
            const outTol = tbody.querySelector('.out-tol');
            if(outTol) outTol.textContent = '-';
            const outAvg = tbody.querySelector('.out-avg-adb');
            if(outAvg) outAvg.textContent = '-';
            const outAvgDb = tbody.querySelector('.out-avg-db');
            if(outAvgDb) outAvgDb.textContent = '-';
            
            const outDb1 = tbody.querySelector('.out-db-1');
            if(outDb1) outDb1.textContent = '-';
            const outDb2 = tbody.querySelector('.out-db-2');
            if(outDb2) outDb2.textContent = '-';
        }
    }

    // Attach Event Listeners to Inputs
    document.querySelectorAll('.param-table input').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('.row-entry');
            const code = this.closest('table').dataset.code;
            
            if(this.classList.contains('in-hasil-1') || this.classList.contains('in-hasil-2')) {
                // Update hidden input if it's entered manually (like TS)
                const i = this.classList.contains('in-hasil-1') ? 1 : 2;
                const hiddenInp = row.querySelector(`.in-d${i}`);
                if(hiddenInp) hiddenInp.value = this.value;
            }
            
            // Selalu trigger kalkulasi agar Average, Absolute Diff, DB, dan Sync (IM/TS) bisa jalan
            calculateRow(row, code);
        });
    });

    // ============================================
    // Form submit validation & loading
    // ============================================
    document.getElementById('formQc').addEventListener('submit', function(e) {
        const checked = document.querySelectorAll('.param-enable-check:checked');
        if (checked.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Belum Ada Parameter',
                text: 'Silakan centang minimal 1 parameter yang ingin diuji.',
                confirmButtonColor: '#d33'
            });
            return;
        }
        
        // Ensure inputs are filled
        let valid = true;
        checked.forEach(check => {
            const pid = check.dataset.pid;
            const collapseEl = document.getElementById('collapse-' + pid);
            const inputs = collapseEl.querySelectorAll('input:not([type="hidden"]):not([readonly]):not(:disabled)');
            inputs.forEach(inp => {
                if(!inp.value && inp.required) {
                    valid = false;
                    inp.classList.add('is-invalid');
                } else {
                    inp.classList.remove('is-invalid');
                }
            });
        });

        if(!valid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Data Belum Lengkap',
                text: 'Mohon lengkapi semua isian pada parameter yang Anda pilih.',
            });
            return;
        }

        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengevaluasi...';
        btn.disabled = true;

        // Bersihkan draft setelah klik simpan
        localStorage.removeItem(DRAFT_KEY);
    });

    // Panggil loadDraft saat halaman pertama kali dibuka
    loadDraft();
});
</script>
@endsection
