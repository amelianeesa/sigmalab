@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $batch->sampel_inhouse_id) }}" class="text-decoration-none">{{ $batch->nama_sampel }}</a></li>
        <li class="breadcrumb-item active">Tahap 5: Uji Stabilitas</li>
    </x-qc-breadcrumb>

    <div class="row mt-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1"><i class="fas fa-balance-scale-right text-primary me-2"></i>Uji Stabilitas (Tahap 5)</h3>
                    <p class="text-muted mb-0">Masukkan data mentah penimbangan harian untuk dikalkulasi menjadi M% dan divalidasi terhadap <strong>Data Target</strong> (t-Test).</p>
                </div>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalExport">
                    <i class="fas fa-print me-2"></i>Cetak / Export
                </button>
            </div>
            
            <form action="{{ route('qc-inhouse.stabilitas.store', $batch->sampel_inhouse_id) }}" method="POST" id="formStabilitas">
                @csrf
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white pt-3 border-bottom-0">
                        <ul class="nav nav-tabs card-header-tabs" id="parameterTabs" role="tablist">
                            @foreach($batch->parameters as $index => $param)
                                @php $code = strtoupper($param->parameterUji->nama_parameter); @endphp
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold {{ $index === 0 ? 'active' : '' }}" 
                                            id="tab-{{ $param->id }}" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#pane-{{ $param->id }}" 
                                            type="button" role="tab"
                                            data-code="{{ $code }}">
                                        {{ $code }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-body p-0">
                        <div class="row m-3 g-3">
                            <div class="col-12">
                                <div class="border rounded p-3 bg-light h-100">
                                    <h6 class="fw-bold mb-3"><i class="fas fa-clipboard-check text-primary me-2"></i>Kondisi Pengujian (Repeatability)</h6>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label small text-muted mb-1">Tanggal Uji</label>
                                            <input type="date" class="form-control form-control-sm" name="kondisi[tanggal]" value="{{ date('Y-m-d') }}" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small text-muted mb-1">Nama Analis</label>
                                            <input type="text" class="form-control form-control-sm" name="kondisi[analis]" placeholder="Satu analis..." required>
                                        </div>
                                    </div>
                                    <div class="alert alert-info py-2 px-3 mt-3 mb-0 small">
                                        <i class="fas fa-info-circle me-1"></i> Nilai <strong>IM</strong> wajib diisi karena parameter lain menggunakannya untuk konversi ke Basis Kering (db).
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content" id="parameterTabsContent">
                            @foreach($batch->parameters as $index => $param)
                                @php 
                                    $code = strtoupper($param->parameterUji->nama_parameter); 
                                    $pid = $param->id;
                                @endphp
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="pane-{{ $pid }}" role="tabpanel">
                                    
                                    <div class="table-responsive p-3">
                                        <table class="table table-bordered table-sm align-middle text-center param-table" id="table-{{ $pid }}" data-pid="{{ $pid }}" data-code="{{ $code }}">
                                            <thead class="table-light">
                                                @if($code === 'IM')
                                                    <tr>
                                                        <th width="10%">KODE SAMPEL</th>
                                                        <th>DISH NO.</th>
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
                                                        <th width="10%">KODE SAMPEL</th>
                                                        <th>DISH NO.</th>
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
                                                        <th width="10%">KODE SAMPEL</th>
                                                        <th>DISH NO.</th>
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
                                                        <th style="width: 15%">KODE SAMPEL</th>
                                                        <th style="width: 5%">DISH NO.</th>
                                                        <th style="width: 16%">Massa sample</th>
                                                        <th class="bg-warning bg-opacity-25" style="width: 16%">TS (Adb)</th>
                                                        <th style="width: 16%">Average %(adb)</th>
                                                        <th style="width: 16%">Average % (Db)</th>
                                                        <th style="width: 16%">%db</th>
                                                    </tr>
                                                @elseif($code === 'CV')
                                                    <tr>
                                                        <th width="8%">KODE SAMPEL</th>
                                                        <th width="5%">DISH NO.</th>
                                                        <th>CALL ID</th>
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
                                                    <!-- Fallback generic -->
                                                    <tr>
                                                        <th>KODE SAMPEL</th>
                                                        <th>DISH NO.</th>
                                                        <th class="bg-warning bg-opacity-25">Hasil Uji (adb)</th>
                                                        <th>ABSOLUTE DIFFERENCE</th>
                                                        <th>AVERAGE % (adb)</th>
                                                    </tr>
                                                @endif
                                            </thead>
                                            <tbody>
                                                @php $rowCount = max(3, $param->dataStabilitas->count()); @endphp
                                                @for($i = 1; $i <= $rowCount; $i++)
                                                    @php 
                                                        $dh = $param->dataStabilitas->where('nomor_pengujian', $i)->first();
                                                        $mentah = $dh ? $dh->data_mentah : [];
                                                        $botolNomor = $dh ? $dh->nomor_botol_fisik : '';
                                                    @endphp
                                                    <!-- SIMPLO ROW -->
                                                    <tr class="row-simplo">
                                                        <td rowspan="2" class="fw-bold align-middle bg-light border-end">
                                                            <select class="form-select form-select-sm text-danger fw-bold" name="data_{{ $pid }}[{{ $i-1 }}][nomor_botol_fisik]" required>
                                                                <option value="">Botol...</option>
                                                                @foreach($sisaBotol as $b)
                                                                    <option value="{{ $b }}" {{ $botolNomor == $b ? 'selected' : '' }}>Botol {{ $b }}</option>
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" class="in-db-1" name="data_{{ $pid }}[{{ $i-1 }}][nilai_db_1]">
                                                            <input type="hidden" class="in-db-2" name="data_{{ $pid }}[{{ $i-1 }}][nilai_db_2]">
                                                        </td>
                                                        <td class="bg-light fw-bold text-center">1</td>
                                                        
                                                        @if($code === 'IM')
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m1_1]" value="{{ $mentah['m1_1'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-m2-1 bg-light border-0" readonly tabindex="-1"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m3_1]" value="{{ $mentah['m3_1'] ?? '' }}"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-a-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][a_1]" value="{{ $mentah['a_1'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-b-1 bg-light border-0" readonly tabindex="-1"></td>
                                                            <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d1]" readonly tabindex="-1"></td>
                                                            <td rowspan="2" class="align-middle out-diff">-</td>
                                                            <td rowspan="2" class="align-middle out-tol fw-bold">-</td>
                                                            <td rowspan="2" class="align-middle out-avg-adb">-</td>
                                                        @elseif($code === 'ASH')
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m1_1]" value="{{ $mentah['m1_1'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-m2-1 bg-light border-0" readonly tabindex="-1"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m2m1_1]" value="{{ $mentah['m2m1_1'] ?? '' }}"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m3_1]" value="{{ $mentah['m3_1'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-m3m1-1 bg-light border-0" readonly tabindex="-1"></td>
                                                            <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d1]" readonly tabindex="-1"></td>
                                                            <td rowspan="2" class="align-middle out-diff">-</td>
                                                            <td rowspan="2" class="align-middle out-tol fw-bold">-</td>
                                                            <td rowspan="2" class="align-middle out-avg-adb">-</td>
                                                            <td rowspan="2" class="align-middle out-avg-db">-</td>
                                                            <td class="align-middle out-db-1 fw-bold text-success">-</td>
                                                        @elseif($code === 'VM')
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m1_1]" value="{{ $mentah['m1_1'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-m2-1 bg-light border-0" readonly tabindex="-1"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m2m1_1]" value="{{ $mentah['m2m1_1'] ?? '' }}"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m3_1]" value="{{ $mentah['m3_1'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-m2m3-1 bg-light border-0" readonly tabindex="-1"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-loss-1 bg-light border-0" readonly tabindex="-1"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-im-1 bg-light border-0 text-secondary" readonly tabindex="-1"></td>
                                                            <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d1]" readonly tabindex="-1"></td>
                                                            <td rowspan="2" class="align-middle out-diff">-</td>
                                                            <td rowspan="2" class="align-middle out-tol fw-bold">-</td>
                                                            <td rowspan="2" class="align-middle out-avg-adb">-</td>
                                                            <td rowspan="2" class="align-middle out-avg-db">-</td>
                                                            <td class="align-middle out-db-1 fw-bold text-success">-</td>
                                                        @elseif($code === 'TS')
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-massa-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][massa_1]" value="{{ $mentah['massa_1'] ?? '' }}"></td>
                                                            <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d1]" value="{{ $dh->nilai_d1 ?? '' }}"></td>
                                                            <td rowspan="2" class="align-middle out-avg-adb fw-bold">-</td>
                                                            <td rowspan="2" class="align-middle out-avg-db fw-bold">-</td>
                                                            <td class="align-middle out-db-1 fw-bold text-success">-</td>
                                                        @elseif($code === 'CV')
                                                            <td><input type="text" class="form-control form-control-sm in-callid-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][callid_1]" value="{{ $mentah['callid_1'] ?? '' }}"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-weight-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][weight_1]" value="{{ $mentah['weight_1'] ?? '' }}"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-massa-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][massa_1]" value="{{ $mentah['massa_1'] ?? '' }}"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-primary-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][primary_1]" value="{{ $mentah['primary_1'] ?? '' }}"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-ee-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][ee_1]" value="{{ $mentah['ee_1'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-t-1 bg-light border-0" readonly tabindex="-1"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-titrant-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][titrant_1]" value="{{ $mentah['titrant_1'] ?? '' }}"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-length-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][length_1]" value="{{ $mentah['length_1'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-ts-1 bg-light border-0 text-secondary" readonly tabindex="-1"></td>
                                                            <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d1]" readonly tabindex="-1"></td>
                                                            <td rowspan="2" class="align-middle out-avg-adb fw-bold">-</td>
                                                            <td rowspan="2" class="align-middle out-avg-db fw-bold">-</td>
                                                            <td class="align-middle out-db-1 fw-bold text-success">-</td>
                                                        @else
                                                            <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d1]" value="{{ $dh->nilai_d1 ?? '' }}"></td>
                                                            <td rowspan="2" class="align-middle out-diff">-</td>
                                                            <td rowspan="2" class="align-middle out-avg-adb">-</td>
                                                        @endif
                                                    </tr>

                                                    <!-- DUPLO ROW -->
                                                    <tr class="row-duplo">
                                                        <td class="bg-light fw-bold text-center">2</td>
                                                        
                                                        @if($code === 'IM')
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m1_2]" value="{{ $mentah['m1_2'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-m2-2 bg-light border-0" readonly tabindex="-1"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m3_2]" value="{{ $mentah['m3_2'] ?? '' }}"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-a-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][a_2]" value="{{ $mentah['a_2'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-b-2 bg-light border-0" readonly tabindex="-1"></td>
                                                            <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d2]" readonly tabindex="-1"></td>
                                                        @elseif($code === 'ASH')
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m1_2]" value="{{ $mentah['m1_2'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-m2-2 bg-light border-0" readonly tabindex="-1"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m2m1_2]" value="{{ $mentah['m2m1_2'] ?? '' }}"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m3_2]" value="{{ $mentah['m3_2'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-m3m1-2 bg-light border-0" readonly tabindex="-1"></td>
                                                            <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d2]" readonly tabindex="-1"></td>
                                                            <td class="align-middle out-db-2 fw-bold text-success">-</td>
                                                        @elseif($code === 'VM')
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m1_2]" value="{{ $mentah['m1_2'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-m2-2 bg-light border-0" readonly tabindex="-1"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m2m1_2]" value="{{ $mentah['m2m1_2'] ?? '' }}"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][m3_2]" value="{{ $mentah['m3_2'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-m2m3-2 bg-light border-0" readonly tabindex="-1"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-loss-2 bg-light border-0" readonly tabindex="-1"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-im-2 bg-light border-0 text-secondary" readonly tabindex="-1"></td>
                                                            <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d2]" readonly tabindex="-1"></td>
                                                            <td class="align-middle out-db-2 fw-bold text-success">-</td>
                                                        @elseif($code === 'TS')
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-massa-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][massa_2]" value="{{ $mentah['massa_2'] ?? '' }}"></td>
                                                            <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d2]" value="{{ $dh->nilai_d2 ?? '' }}"></td>
                                                            <td class="align-middle out-db-2 fw-bold text-success">-</td>
                                                        @elseif($code === 'CV')
                                                            <td><input type="text" class="form-control form-control-sm in-callid-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][callid_2]" value="{{ $mentah['callid_2'] ?? '' }}"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-weight-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][weight_2]" value="{{ $mentah['weight_2'] ?? '' }}"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-massa-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][massa_2]" value="{{ $mentah['massa_2'] ?? '' }}"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-primary-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][primary_2]" value="{{ $mentah['primary_2'] ?? '' }}"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-ee-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][ee_2]" value="{{ $mentah['ee_2'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-t-2 bg-light border-0" readonly tabindex="-1"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-titrant-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][titrant_2]" value="{{ $mentah['titrant_2'] ?? '' }}"></td>
                                                            <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-length-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][length_2]" value="{{ $mentah['length_2'] ?? '' }}"></td>
                                                            <td><input type="text" class="form-control form-control-sm in-ts-2 bg-light border-0 text-secondary" readonly tabindex="-1"></td>
                                                            <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d2]" readonly tabindex="-1"></td>
                                                            <td class="align-middle out-db-2 fw-bold text-success">-</td>
                                                        @else
                                                            <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" name="data_{{ $pid }}[{{ $i-1 }}][nilai_d2]" value="{{ $dh->nilai_d2 ?? '' }}"></td>
                                                        @endif
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="px-3 pb-3 d-flex justify-content-between">
                                        <div>
                                            <button type="button" class="btn btn-outline-primary btn-tambah-kemasan fw-bold me-2" data-pid="{{ $pid }}" data-code="{{ $code }}">
                                                <i class="fas fa-plus me-1"></i> Tambah Baris
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-hapus-kemasan fw-bold" data-pid="{{ $pid }}" data-code="{{ $code }}">
                                                <i class="fas fa-trash me-1"></i> Hapus Baris
                                            </button>
                                        </div>
                                        <button type="button" class="btn btn-outline-success btn-save-sheet fw-bold" data-pid="{{ $pid }}" data-code="{{ $code }}">
                                            <i class="fas fa-save me-1"></i> Simpan Tabel {{ $code }} Saja
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="card-footer bg-light p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold mb-1 text-primary">Live T-Test Summary (Preview)</h5>
                                <p class="small text-muted mb-0">Rangkuman hasil kalkulasi statistik uji stabilitas secara *real-time*.</p>
                            </div>
                            <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#modalDetailTTest">
                                <i class="fas fa-table me-2"></i>Lihat Detail Kalkulasi
                            </button>
                        </div>
                        <div class="row text-center mt-3" id="ttestPreviewBoxes">
                            <!-- Injected by JS -->
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-5">
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm" id="btnSubmit">
                        <i class="fas fa-check-double me-2"></i> Simpan Data Uji Stabilitas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EXPORT -->
<div class="modal fade" id="modalExport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-print me-2"></i>Cetak / Export Hasil Uji</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <label class="form-label fw-bold">Pilih Format Cetak:</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportFormat" id="fmtExcel" value="excel" checked>
                            <label class="form-check-label" for="fmtExcel"><i class="fas fa-file-excel text-success me-1"></i> Excel</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportFormat" id="fmtPdf" value="pdf">
                            <label class="form-check-label" for="fmtPdf"><i class="fas fa-file-pdf text-danger me-1"></i> PDF</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportFormat" id="fmtWord" value="word">
                            <label class="form-check-label" for="fmtWord"><i class="fas fa-file-word text-primary me-1"></i> Word</label>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Bagian yang Dicetak:</label>
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportPart" id="partBoth" value="both" checked>
                            <label class="form-check-label" for="partBoth">Keduanya (Tabel Data Utama & Detail Perhitungan Stabilitas)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportPart" id="partMain" value="main">
                            <label class="form-check-label" for="partMain">Tabel Data Utama Saja</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportPart" id="partTTest" value="ttest">
                            <label class="form-check-label" for="partTTest">Detail Perhitungan Stabilitas Saja</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Parameter (Bisa pilih lebih dari satu):</label>
                    <div class="form-check mb-2 pb-2 border-bottom">
                        <input class="form-check-input" type="checkbox" id="chkExportAll" checked>
                        <label class="form-check-label fw-bold" for="chkExportAll">Semua Parameter</label>
                    </div>
                    <div id="exportParamCheckboxes">
                        @foreach($batch->parameters as $param)
                            @php $code = strtoupper($param->parameterUji->nama_parameter); @endphp
                            <div class="form-check">
                                <input class="form-check-input chk-export-param" type="checkbox" value="{{ $code }}" id="chkExport{{ $code }}" checked>
                                <label class="form-check-label" for="chkExport{{ $code }}">{{ $code }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnExecuteExport"><i class="fas fa-download me-2"></i>Download</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DETAIL T-TEST -->
<div class="modal fade" id="modalDetailTTest" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="fas fa-chart-bar text-primary me-2"></i>Detail Perhitungan Uji Stabilitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Pilih Parameter</label>
                        <select class="form-select" id="modalParamSelect">
                            @foreach($batch->parameters as $p)
                                <option value="{{ strtoupper($p->parameterUji->nama_parameter) }}">{{ $p->parameterUji->nama_parameter }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="bg-white p-4 border rounded shadow-sm" id="modalPrintArea">
                    <!-- Injected by JS -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan script SweetAlert2 untuk notif elegan -->
<!-- Tambahkan script SweetAlert2 untuk notif elegan -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Tambahkan script SheetJS dan html2pdf untuk Export -->
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
// STATE STORE
const State = {};
@foreach($batch->parameters as $param)
    State["{{ strtoupper($param->parameterUji->nama_parameter) }}"] = {
        id: {{ $param->id }},
        ready: false,
        data: Array.from({length: {{ max(3, $param->dataStabilitas->count()) }} }, () => ({ 
            simplo_adb: null, duplo_adb: null, avg_adb: null 
        })),
        limit: {{ $tolerances[$param->id] ?? 0.09 }} // Default fixed limit unless IM dynamic
    };
@endforeach

document.addEventListener('DOMContentLoaded', function() {
    
    // Bind Event Listeners
    const rawXData = {
        @php
            $imParam = $batch->parameters->filter(function($p) {
                return strtoupper($p->parameterUji->nama_parameter) === 'IM';
            })->first();
            
            $imData = [];
            if ($imParam) {
                foreach($imParam->dataHomogenitas as $dh) {
                    $imData[$dh->nomor_sampel] = [
                        'd1' => $dh->nilai_d1 !== null ? (float)$dh->nilai_d1 : null,
                        'd2' => $dh->nilai_d2 !== null ? (float)$dh->nilai_d2 : null
                    ];
                }
            }
        @endphp
        @foreach($batch->parameters as $p)
        @php 
            $pCode = strtoupper($p->parameterUji->nama_parameter);
            $dbArr = [];
            foreach($p->dataHomogenitas as $dh) {
                $m = $dh->data_mentah;
                
                $v1 = isset($m['nilai_db_1']) && $m['nilai_db_1'] !== '' ? (float)$m['nilai_db_1'] : null;
                if ($v1 === null && $pCode !== 'IM' && isset($imData[$dh->nomor_sampel]) && $dh->nilai_d1 !== null) {
                    $im1 = $imData[$dh->nomor_sampel]['d1'];
                    if ($im1 !== null && $im1 < 100) {
                        $v1 = (100 / (100 - $im1)) * (float)$dh->nilai_d1;
                    }
                }
                if ($v1 === null) $v1 = $dh->nilai_d1;

                $v2 = isset($m['nilai_db_2']) && $m['nilai_db_2'] !== '' ? (float)$m['nilai_db_2'] : null;
                if ($v2 === null && $pCode !== 'IM' && isset($imData[$dh->nomor_sampel]) && $dh->nilai_d2 !== null) {
                    $im2 = $imData[$dh->nomor_sampel]['d2'];
                    if ($im2 !== null && $im2 < 100) {
                        $v2 = (100 / (100 - $im2)) * (float)$dh->nilai_d2;
                    }
                }
                if ($v2 === null) $v2 = $dh->nilai_d2;

                if($v1 !== null) $dbArr[] = (float)$v1;
                if($v2 !== null) $dbArr[] = (float)$v2;
            }
        @endphp
        "{{ $pCode }}": {!! json_encode($dbArr) !!},
        @endforeach
    };
    
    const tablesByCode = {};
    document.querySelectorAll('.param-table').forEach(table => {
        const code = table.dataset.code;
        tablesByCode[code] = table;
        // Bind Event Listeners using Event Delegation for maximum robustness
        table.addEventListener('input', function(e) {
            if(e.target.tagName === 'INPUT') {
                processTable(code, table);
            }
        });
        table.addEventListener('change', function(e) {
            if(e.target.tagName === 'INPUT') {
                processTable(code, table);
            }
        });
        
        // Trigger format on blur for manual inputs if they are results (like TS adb)
        table.addEventListener('blur', function(e) {
            if(e.target.tagName === 'INPUT' && (e.target.classList.contains('in-hasil-1') || e.target.classList.contains('in-hasil-2'))) {
                if(e.target.value && !e.target.readOnly) e.target.value = rnd(e.target.value, 2);
            }
        }, true); // capture phase since blur doesn't bubble
    });

    // Event listener for Tambah Kemasan button
    document.querySelectorAll('.btn-tambah-kemasan').forEach(btn => {
        btn.addEventListener('click', function() {
            const code = this.dataset.code;
            const pid = this.dataset.pid;
            const table = tablesByCode[code];
            if(!table) return;

            const tbody = table.querySelector('tbody');
            const simploRows = tbody.querySelectorAll('.row-simplo');
            const duploRows = tbody.querySelectorAll('.row-duplo');
            
            const newIndex = simploRows.length; // 0-indexed
            const cloneSimplo = simploRows[0].cloneNode(true);
            const cloneDuplo = duploRows[0].cloneNode(true);

            // Update Name attributes
            cloneSimplo.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/\[\d+\]/, `[${newIndex}]`);
            });
            cloneDuplo.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/\[\d+\]/, `[${newIndex}]`);
            });

            // Clear inputs
            cloneSimplo.querySelectorAll('input').forEach(el => { if(el.type !== 'hidden') el.value = ''; });
            cloneDuplo.querySelectorAll('input').forEach(el => { if(el.type !== 'hidden') el.value = ''; });
            cloneSimplo.querySelectorAll('select').forEach(el => { el.value = ''; });

            // Clear texts
            cloneSimplo.querySelectorAll('td').forEach(td => {
                if(td.textContent.trim() === '-') td.textContent = '-';
            });

            tbody.appendChild(cloneSimplo);
            tbody.appendChild(cloneDuplo);

            // Add to State
            State[code].data.push({ simplo_adb: null, duplo_adb: null, avg_adb: null });

            // Process specific table, and also update dependents if IM
            if (code === 'IM') {
                executionOrder.forEach(c => {
                    if(tablesByCode[c]) processTable(c, tablesByCode[c]);
                });
            } else {
                processTable(code, table);
            }
        });
    });

    // Event listener for Hapus Baris button
    document.querySelectorAll('.btn-hapus-kemasan').forEach(btn => {
        btn.addEventListener('click', function() {
            const code = this.dataset.code;
            const table = tablesByCode[code];
            if(!table) return;

            const tbody = table.querySelector('tbody');
            const simploRows = tbody.querySelectorAll('.row-simplo');
            const duploRows = tbody.querySelectorAll('.row-duplo');
            
            if (simploRows.length <= 3) {
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'error',
                    title: 'Minimal harus ada 3 baris pengujian.',
                    showConfirmButton: false, timer: 2000
                });
                return;
            }

            // Hapus baris terakhir
            const lastIndex = simploRows.length - 1;
            tbody.removeChild(simploRows[lastIndex]);
            tbody.removeChild(duploRows[lastIndex]);

            // Hapus dari State
            State[code].data.pop();

            // Process specific table, and also update dependents if IM
            if (code === 'IM') {
                executionOrder.forEach(c => {
                    if(tablesByCode[c]) processTable(c, tablesByCode[c]);
                });
            } else {
                processTable(code, table);
            }
        });
    });

    // Event listener for Simpan Tabel Saja button
    document.querySelectorAll('.btn-save-sheet').forEach(btn => {
        btn.addEventListener('click', function() {
            const pid = this.dataset.pid;
            const code = this.dataset.code;
            const form = document.getElementById('formStabilitas');
            const url = form.action;
            
            // Show loading state
            const origHtml = this.innerHTML;
            this.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...`;
            this.disabled = true;

            const allFormData = new FormData(form);
            const filteredData = new FormData();

            // Only keep _token, kondisi, and data_{pid}
            for (let [key, value] of allFormData.entries()) {
                if (key === '_token' || key.startsWith('kondisi[') || key.startsWith(`data_${pid}[`)) {
                    filteredData.append(key, value);
                }
            }

            // AJAX request
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: filteredData
            })
            .then(response => {
                if (response.ok) {
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success',
                        title: `Tabel ${code} berhasil disimpan!`,
                        showConfirmButton: false, timer: 2500
                    });
                } else {
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'error',
                        title: `Terjadi kesalahan saat menyimpan tabel ${code}.`,
                        showConfirmButton: false, timer: 2500
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'error',
                    title: `Error network saat menyimpan tabel ${code}.`,
                    showConfirmButton: false, timer: 2500
                });
            })
            .finally(() => {
                this.innerHTML = origHtml;
                this.disabled = false;
            });
        });
    });

    // Trigger preview update when switching tabs
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(btn => {
        btn.addEventListener('shown.bs.tab', function (e) {
            renderLivePreview();
        });
    });

    // Initial process to calculate pre-filled data in DEPENDENCY ORDER
    const executionOrder = ['IM', 'ASH', 'VM', 'TS', 'CV'];
    executionOrder.forEach(code => {
        if(tablesByCode[code]) processTable(code, tablesByCode[code]);
    });
    // Process any remaining parameters not in the explicit list
    Object.keys(tablesByCode).forEach(code => {
        if(!executionOrder.includes(code)) processTable(code, tablesByCode[code]);
    });
    
    // Ultimate fallback for extensions/autofill that bypass DOM events:
    // Check the active table every 500ms
    setInterval(() => {
        const activeTab = document.querySelector('.nav-link.active');
        if(activeTab) {
            const code = activeTab.dataset.code;
            const table = document.querySelector(`.param-table[data-code="${code}"]`);
            if(table) processTable(code, table);
        }
    }, 500);

    // Helper: format number
    function rnd(val, dec=4) {
        if(isNaN(val) || val === null || val === '') return '-';
        return Number(val).toFixed(dec);
    }

    // Helper: get safe float value, handle commas and hidden characters
    function getVal(el) {
        if(!el || el.value === undefined || el.value === null || el.value === '') return NaN;
        // Hapus semua karakter yang BUKAN angka, titik, koma, atau minus.
        // Ini memastikan karakter aneh seperti Left-to-Right mark (\u200E) terhapus.
        let v = el.value.toString().replace(/[^\d.,\-]/g, '');
        v = v.replace(/,/g, '.');
        return parseFloat(v);
    }

    function processTable(code, table) {
        let isComplete = true;
        const simploRows = table.querySelectorAll('.row-simplo');
        
        for(let i=0; i<simploRows.length; i++) {
            const tr1 = simploRows[i];
            const tr2 = table.querySelectorAll('.row-duplo')[i];
            
            let val1 = null;
            let val2 = null;

            // -------- IM --------
            if(code === 'IM') {
                const m1_1 = getVal(tr1.querySelector('.in-m1-1'));
                const a_1 = getVal(tr1.querySelector('.in-a-1'));
                const m3_1 = getVal(tr1.querySelector('.in-m3-1'));
                
                // Kalkulasi independen agar terlihat live walaupun baru isi 2 input
                if(!isNaN(m1_1) && !isNaN(a_1)) tr1.querySelector('.in-m2-1').value = rnd(m1_1 + a_1, 4);
                if(!isNaN(m1_1) && !isNaN(m3_1)) tr1.querySelector('.in-b-1').value = rnd(m3_1 - m1_1, 4);

                if(!isNaN(m1_1) && !isNaN(a_1) && !isNaN(m3_1)) {
                    val1 = ((a_1 - (m3_1 - m1_1)) / a_1) * 100;
                    tr1.querySelector('.in-hasil-1').value = rnd(val1, 2);
                } else { isComplete = false; tr1.querySelector('.in-hasil-1').value = ''; }

                const m1_2 = getVal(tr2.querySelector('.in-m1-2'));
                const a_2 = getVal(tr2.querySelector('.in-a-2'));
                const m3_2 = getVal(tr2.querySelector('.in-m3-2'));
                
                if(!isNaN(m1_2) && !isNaN(a_2)) tr2.querySelector('.in-m2-2').value = rnd(m1_2 + a_2, 4);
                if(!isNaN(m1_2) && !isNaN(m3_2)) tr2.querySelector('.in-b-2').value = rnd(m3_2 - m1_2, 4);

                if(!isNaN(m1_2) && !isNaN(a_2) && !isNaN(m3_2)) {
                    val2 = ((a_2 - (m3_2 - m1_2)) / a_2) * 100;
                    tr2.querySelector('.in-hasil-2').value = rnd(val2, 2);
                } else { isComplete = false; tr2.querySelector('.in-hasil-2').value = ''; }
            }
            // -------- ASH --------
            else if(code === 'ASH') {
                const m1_1 = getVal(tr1.querySelector('.in-m1-1'));
                const m2m1_1 = getVal(tr1.querySelector('.in-m2m1-1'));
                const m3_1 = getVal(tr1.querySelector('.in-m3-1'));
                
                if(!isNaN(m1_1) && !isNaN(m2m1_1)) {
                    tr1.querySelector('.in-m2-1').value = rnd(m1_1 + m2m1_1, 4);
                } else {
                    tr1.querySelector('.in-m2-1').value = '';
                }

                if(!isNaN(m3_1) && !isNaN(m1_1)) tr1.querySelector('.in-m3m1-1').value = rnd(m3_1 - m1_1, 4);

                if(!isNaN(m1_1) && !isNaN(m2m1_1) && !isNaN(m3_1)) {
                    val1 = ((m3_1 - m1_1) / m2m1_1) * 100;
                    tr1.querySelector('.in-hasil-1').value = rnd(val1, 2);
                    
                    const im_s = (State['IM'] && State['IM'].data[i]) ? State['IM'].data[i].simplo_adb : null;
                    if(im_s !== null) {
                        tr1.querySelector('.out-db-1').textContent = rnd((100 / (100 - im_s)) * val1, 2);
                    } else {
                        tr1.querySelector('.out-db-1').textContent = '-';
                    }
                } else { 
                    isComplete = false; 
                    tr1.querySelector('.in-hasil-1').value = ''; 
                    if(tr1.querySelector('.out-db-1')) tr1.querySelector('.out-db-1').textContent = '-';
                }

                const m1_2 = getVal(tr2.querySelector('.in-m1-2'));
                const m2m1_2 = getVal(tr2.querySelector('.in-m2m1-2'));
                const m3_2 = getVal(tr2.querySelector('.in-m3-2'));
                
                if(!isNaN(m1_2) && !isNaN(m2m1_2)) {
                    tr2.querySelector('.in-m2-2').value = rnd(m1_2 + m2m1_2, 4);
                } else {
                    tr2.querySelector('.in-m2-2').value = '';
                }

                if(!isNaN(m3_2) && !isNaN(m1_2)) tr2.querySelector('.in-m3m1-2').value = rnd(m3_2 - m1_2, 4);

                if(!isNaN(m1_2) && !isNaN(m2m1_2) && !isNaN(m3_2)) {
                    val2 = ((m3_2 - m1_2) / m2m1_2) * 100;
                    tr2.querySelector('.in-hasil-2').value = rnd(val2, 2);
                    
                    const im_d = (State['IM'] && State['IM'].data[i]) ? State['IM'].data[i].duplo_adb : null;
                    if(im_d !== null) {
                        tr2.querySelector('.out-db-2').textContent = rnd((100 / (100 - im_d)) * val2, 2);
                    } else {
                        tr2.querySelector('.out-db-2').textContent = '-';
                    }
                } else { 
                    isComplete = false; 
                    tr2.querySelector('.in-hasil-2').value = ''; 
                    if(tr2.querySelector('.out-db-2')) tr2.querySelector('.out-db-2').textContent = '-';
                }
            }
            // -------- VM --------
            else if(code === 'VM') {
                const im_s = (State['IM'] && State['IM'].data[i]) ? State['IM'].data[i].simplo_adb : null;
                const im_d = (State['IM'] && State['IM'].data[i]) ? State['IM'].data[i].duplo_adb : null;

                tr1.querySelector('.in-im-1').value = rnd(im_s, 2);
                tr2.querySelector('.in-im-2').value = rnd(im_d, 2);

                const m1_1 = getVal(tr1.querySelector('.in-m1-1'));
                const m2m1_1 = getVal(tr1.querySelector('.in-m2m1-1'));
                const m3_1 = getVal(tr1.querySelector('.in-m3-1'));
                
                if(!isNaN(m1_1) && !isNaN(m2m1_1)) tr1.querySelector('.in-m2-1').value = rnd(m2m1_1 + m1_1, 4);
                if(!isNaN(m1_1) && !isNaN(m2m1_1) && !isNaN(m3_1)) {
                    const m2 = m2m1_1 + m1_1;
                    const m2m3 = m2 - m3_1;
                    const loss = (m2m3 / m2m1_1) * 100;
                    tr1.querySelector('.in-m2m3-1').value = rnd(m2m3, 4);
                    tr1.querySelector('.in-loss-1').value = rnd(loss, 2);
                    
                    if(im_s !== null) {
                        val1 = loss - im_s;
                        tr1.querySelector('.in-hasil-1').value = rnd(val1, 2);
                        
                        tr1.querySelector('.out-db-1').textContent = rnd((100 / (100 - im_s)) * val1, 2);
                    } else {
                        tr1.querySelector('.out-db-1').textContent = '-';
                    }
                } else { 
                    isComplete = false; 
                    tr1.querySelector('.in-hasil-1').value = ''; 
                    if(tr1.querySelector('.out-db-1')) tr1.querySelector('.out-db-1').textContent = '-';
                }

                const m1_2 = getVal(tr2.querySelector('.in-m1-2'));
                const m2m1_2 = getVal(tr2.querySelector('.in-m2m1-2'));
                const m3_2 = getVal(tr2.querySelector('.in-m3-2'));
                
                if(!isNaN(m1_2) && !isNaN(m2m1_2)) tr2.querySelector('.in-m2-2').value = rnd(m2m1_2 + m1_2, 4);
                if(!isNaN(m1_2) && !isNaN(m2m1_2) && !isNaN(m3_2)) {
                    const m2 = m2m1_2 + m1_2;
                    const m2m3 = m2 - m3_2;
                    const loss = (m2m3 / m2m1_2) * 100;
                    tr2.querySelector('.in-m2m3-2').value = rnd(m2m3, 4);
                    tr2.querySelector('.in-loss-2').value = rnd(loss, 2);

                    if(im_d !== null) {
                        val2 = loss - im_d;
                        tr2.querySelector('.in-hasil-2').value = rnd(val2, 2);
                        
                        tr2.querySelector('.out-db-2').textContent = rnd((100 / (100 - im_d)) * val2, 2);
                    } else {
                        tr2.querySelector('.out-db-2').textContent = '-';
                    }
                } else { 
                    isComplete = false; 
                    tr2.querySelector('.in-hasil-2').value = ''; 
                    if(tr2.querySelector('.out-db-2')) tr2.querySelector('.out-db-2').textContent = '-';
                }
            }
            // -------- TS --------
            else if(code === 'TS') {
                val1 = getVal(tr1.querySelector('.in-hasil-1'));
                val2 = getVal(tr2.querySelector('.in-hasil-2'));
                const mass1 = getVal(tr1.querySelector('.in-massa-1'));
                const mass2 = getVal(tr2.querySelector('.in-massa-2'));
                if(isNaN(val1) || isNaN(val2)) isComplete = false;
            }
            // -------- CV --------
            else if(code === 'CV') {
                const ts_s = State['TS'] ? State['TS'].data[i].simplo_adb : null;
                const ts_d = State['TS'] ? State['TS'].data[i].duplo_adb : null;

                tr1.querySelector('.in-ts-1').value = rnd(ts_s, 2);
                tr2.querySelector('.in-ts-2').value = rnd(ts_d, 2);

                const mass1 = getVal(tr1.querySelector('.in-massa-1'));
                const pri1 = getVal(tr1.querySelector('.in-primary-1'));
                const ee1 = getVal(tr1.querySelector('.in-ee-1'));
                const tit1 = getVal(tr1.querySelector('.in-titrant-1'));
                const len1 = getVal(tr1.querySelector('.in-length-1'));

                if(!isNaN(mass1) && !isNaN(pri1) && !isNaN(ee1)) {
                    tr1.querySelector('.in-t-1').value = rnd((pri1 / ee1) * mass1, 4);
                }

                if(!isNaN(mass1) && !isNaN(pri1) && !isNaN(ee1) && !isNaN(tit1) && !isNaN(len1) && ts_s !== null) {
                    val1 = Math.round(((pri1) - (14.3 * 0.0699 * tit1) - (2.3 * len1) - (13.2 * ts_s * mass1)) / mass1);
                    tr1.querySelector('.in-hasil-1').value = val1;
                } else { isComplete = false; tr1.querySelector('.in-hasil-1').value = ''; }

                const mass2 = getVal(tr2.querySelector('.in-massa-2'));
                const pri2 = getVal(tr2.querySelector('.in-primary-2'));
                const ee2 = getVal(tr2.querySelector('.in-ee-2'));
                const tit2 = getVal(tr2.querySelector('.in-titrant-2'));
                const len2 = getVal(tr2.querySelector('.in-length-2'));

                if(!isNaN(mass2) && !isNaN(pri2) && !isNaN(ee2)) {
                    tr2.querySelector('.in-t-2').value = rnd((pri2 / ee2) * mass2, 4);
                }

                if(!isNaN(mass2) && !isNaN(pri2) && !isNaN(ee2) && !isNaN(tit2) && !isNaN(len2) && ts_d !== null) {
                    val2 = Math.round(((pri2) - (14.3 * 0.0699 * tit2) - (2.3 * len2) - (13.2 * ts_d * mass2)) / mass2);
                    tr2.querySelector('.in-hasil-2').value = val2;
                } else { isComplete = false; tr2.querySelector('.in-hasil-2').value = ''; }
            }

            // ---- SUMMARY ROW & DB CONVERSION ----
            if(val1 !== null && val2 !== null && !isNaN(val1) && !isNaN(val2)) {
                val1 = parseFloat(rnd(val1, code === 'CV' ? 0 : 2));
                val2 = parseFloat(rnd(val2, code === 'CV' ? 0 : 2));
                
                State[code].data[i].simplo_adb = val1;
                State[code].data[i].duplo_adb = val2;
                
                let avg_adb = (val1 + val2) / 2.0;
                avg_adb = parseFloat(rnd(avg_adb, code === 'CV' ? 0 : 2));
                State[code].data[i].avg_adb = avg_adb;
                
                if (code === 'ASH' || code === 'VM' || code === 'TS' || code === 'CV') {
                    const im_s = (State['IM'] && State['IM'].data[i]) ? State['IM'].data[i].simplo_adb : null;
                    State[code].data[i].simplo_db = im_s !== null ? parseFloat(rnd((100 / (100 - im_s)) * val1, code === 'CV' ? 0 : 2)) : null;
                    const im_d = (State['IM'] && State['IM'].data[i]) ? State['IM'].data[i].duplo_adb : null;
                    State[code].data[i].duplo_db = im_d !== null ? parseFloat(rnd((100 / (100 - im_d)) * val2, code === 'CV' ? 0 : 2)) : null;
                    
                    if (code === 'TS' || code === 'CV') {
                        tr1.querySelector('.out-db-1') ? tr1.querySelector('.out-db-1').textContent = State[code].data[i].simplo_db ?? '-' : null;
                        tr2.querySelector('.out-db-2') ? tr2.querySelector('.out-db-2').textContent = State[code].data[i].duplo_db ?? '-' : null;
                    }
                    if(tr1.querySelector('.in-db-1')) tr1.querySelector('.in-db-1').value = State[code].data[i].simplo_db ?? '';
                    if(tr1.querySelector('.in-db-2')) tr1.querySelector('.in-db-2').value = State[code].data[i].duplo_db ?? '';
                }
                const diff = Math.abs(val1 - val2);

                tr1.querySelector('.out-diff') ? tr1.querySelector('.out-diff').textContent = rnd(diff, (code==='CV'?0:2)) : null;
                tr1.querySelector('.out-avg-adb') ? tr1.querySelector('.out-avg-adb').textContent = rnd(avg_adb, (code==='CV'?0:2)) : null;

                if(code === 'IM' || code === 'ASH' || code === 'VM' || code === 'TS') {
                    let isOk = false;
                    if(code === 'IM') {
                        const limit = 0.09 + (0.1 * avg_adb);
                        isOk = diff < limit;
                    } else if(code === 'ASH') {
                        isOk = diff < 0.22;
                    } else if(code === 'VM') {
                        isOk = diff < 1;
                    } else if(code === 'TS') {
                        isOk = diff < 0.05; // Dummy tolerance for TS for now, wait, what is TS tolerance? 0.05 is commonly used for low TS. I will set it to 0.05 but user might complain. Actually, wait! In excel does TS have a YES/NO? Let me check line 810. Wait, I should just set isOk = true so it says YES. Or I can check tolerance from $tolerances[$param->id]. Let me use `limit` from State.
                        isOk = diff < State[code].limit;
                    }
                    const outTolEl = tr1.querySelector('.out-tol');
                    if(outTolEl) {
                        outTolEl.textContent = isOk ? 'YES' : 'NO';
                        outTolEl.classList.remove('text-success', 'text-danger');
                        outTolEl.classList.add(isOk ? 'text-success' : 'text-danger');
                    }
                } 
                
                if(code !== 'IM') {
                    // Convert to DB
                    const im_avg = (State['IM'] && State['IM'].data[i]) ? State['IM'].data[i].avg_adb : null;
                    if(im_avg !== null && !isNaN(im_avg)) {
                        const avg_db = (100 / (100 - im_avg)) * avg_adb;
                        tr1.querySelector('.out-avg-db') ? tr1.querySelector('.out-avg-db').textContent = rnd(avg_db, (code==='CV'?0:2)) : null;
                    } else {
                        tr1.querySelector('.out-avg-db') ? tr1.querySelector('.out-avg-db').textContent = '-' : null;
                    }
                }
            } else {
                State[code].data[i].simplo_adb = null;
                State[code].data[i].duplo_adb = null;
                State[code].data[i].avg_adb = null;
                
                tr1.querySelector('.out-diff') ? tr1.querySelector('.out-diff').textContent = '-' : null;
                tr1.querySelector('.out-tol') ? tr1.querySelector('.out-tol').textContent = '-' : null;
                tr1.querySelector('.out-avg-adb') ? tr1.querySelector('.out-avg-adb').textContent = '-' : null;
                tr1.querySelector('.out-avg-db') ? tr1.querySelector('.out-avg-db').textContent = '-' : null;
            }
        }

        State[code].ready = isComplete;
        renderLivePreview();
    }

    // Listener for Tab Change to Update Target Table
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(btn => {
        btn.addEventListener('shown.bs.tab', function (e) {
            const newCode = e.target.dataset.code;
            // Retrigger processTable for this specific tab to pull IM db dependencies if needed
            processTable(newCode, document.querySelector(`.param-table[data-code="${newCode}"]`));
        });
    });

    // ============================================
    // MODAL T-TEST LOGIC
    // ============================================
    const modalParamSelect = document.getElementById('modalParamSelect');
    const modalPrintArea = document.getElementById('modalPrintArea');
    const modalDetailTTestEl = document.getElementById('modalDetailTTest');
    
    if (modalDetailTTestEl) {
        modalDetailTTestEl.addEventListener('show.bs.modal', function () {
            const activeTab = document.querySelector('button[data-bs-toggle="tab"].active');
            if (activeTab) {
                modalParamSelect.value = activeTab.dataset.code;
            }
            renderModalTTest();
        });
    }

    modalParamSelect.addEventListener('change', renderModalTTest);

    function renderLivePreview() {
        const activeTabBtn = document.querySelector('button[data-bs-toggle="tab"].active');
        if (!activeTabBtn) return;
        const code = activeTabBtn.dataset.code;
        
        let xData = rawXData[code] || [];
        let yData = [];
        if (State[code]) {
            for(let i=0; i<State[code].data.length; i++) {
                let y1, y2;
                if(code === 'IM') {
                    y1 = State[code].data[i].simplo_adb;
                    y2 = State[code].data[i].duplo_adb;
                } else {
                    y1 = State[code].data[i].simplo_db;
                    y2 = State[code].data[i].duplo_db;
                }
                if(y1 !== null && !isNaN(y1)) yData.push(y1);
                if(y2 !== null && !isNaN(y2)) yData.push(y2);
            }
        }
        
        const nX = xData.length;
        const meanX = nX > 0 ? xData.reduce((a,b)=>a+b, 0) / nX : 0;
        let sumSqX = 0;
        for(let i=0; i<nX; i++) {
            sumSqX += Math.pow(xData[i] - meanX, 2);
        }
        
        const nY = yData.length;
        const meanY = nY > 0 ? yData.reduce((a,b)=>a+b, 0) / nY : 0;
        let sumSqY = 0;
        for(let i=0; i<nY; i++) {
            sumSqY += Math.pow(yData[i] - meanY, 2);
        }
        
        const df = nX + nY - 2;
        let sGab = 0;
        let tHitung = 0;
        if(df > 0 && nX > 0 && nY > 0) {
            sGab = Math.sqrt((sumSqX + sumSqY) / df);
            tHitung = Math.abs(meanX - meanY) / sGab;
        }
        
        const tTableMap = {
            20: 2.086, 21: 2.080, 22: 2.074, 23: 2.069, 24: 2.064, 25: 2.060,
            26: 2.056, 27: 2.052, 28: 2.048, 29: 2.045, 30: 2.042, 31: 2.040,
            32: 2.037, 33: 2.035, 34: 2.032, 35: 2.030, 36: 2.028, 37: 2.026,
            38: 2.024, 39: 2.023, 40: 2.021, 60: 2.000, 120: 1.980
        };
        const tTabel = tTableMap[df] || 2.0;
        
        const isStabil = tHitung < tTabel;
        const html = `
            <div class="col-md-3 mb-2">
                <div class="border rounded p-2 bg-white shadow-sm h-100">
                    <div class="small text-muted mb-1 fw-bold">S<sub>gabungan</sub></div>
                    <div class="fs-4 text-dark fw-bold">${rnd(sGab, 4)}</div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="border rounded p-2 bg-white shadow-sm h-100">
                    <div class="small text-muted mb-1 fw-bold">t hitung</div>
                    <div class="fs-4 text-dark fw-bold">${rnd(tHitung, 4)}</div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="border rounded p-2 bg-white shadow-sm h-100">
                    <div class="small text-muted mb-1 fw-bold">t tabel</div>
                    <div class="fs-4 text-dark fw-bold">${rnd(tTabel, 4)}</div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="border rounded p-2 bg-white shadow-sm h-100 ${nY < 6 ? 'border-warning' : (isStabil ? 'border-success' : 'border-danger')}">
                    <div class="small text-muted mb-1 fw-bold">Kesimpulan</div>
                    <div class="fs-5 fw-bold ${nY < 6 ? 'text-warning' : (isStabil ? 'text-success' : 'text-danger')} mt-1" style="line-height: 1.2;">
                        ${nY < 6 ? 'Belum Selesai' : (isStabil ? 'STABIL' : 'TIDAK STABIL')}
                    </div>
                </div>
            </div>
        `;
        document.getElementById('ttestPreviewBoxes').innerHTML = html;
    }

    function renderModalTTest() {
        const code = modalParamSelect.value;
        const xData = rawXData[code] || [];
        
        let yData = [];
        if (State[code]) {
            for(let i=0; i<State[code].data.length; i++) {
                // Determine whether to use db or adb for Y based on code
                let y1, y2;
                if(code === 'IM') {
                    y1 = State[code].data[i].simplo_adb;
                    y2 = State[code].data[i].duplo_adb;
                } else {
                    y1 = State[code].data[i].simplo_db;
                    y2 = State[code].data[i].duplo_db;
                }
                if(y1 !== null && !isNaN(y1)) yData.push(y1);
                if(y2 !== null && !isNaN(y2)) yData.push(y2);
            }
        }
        
        // Let's build the HTML structure matching the user's image exactly.
        
        // Kalkulasi X
        const nX = xData.length;
        const meanX = nX > 0 ? xData.reduce((a,b)=>a+b, 0) / nX : 0;
        let sumSqX = 0;
        let rowsX = '';
        for(let i=0; i<Math.max(nX, 20); i++) {
            if(i < nX) {
                const val = xData[i];
                const diff = val - meanX;
                const sq = Math.pow(diff, 2);
                sumSqX += sq;
                let no = Math.floor(i/2) + 1;
                let sub = (i%2) + 1;
                rowsX += `<tr>
                    <td>${no}.${sub}</td>
                    <td>${rnd(val, 2)}</td>
                    <td>${rnd(diff, 2)}</td>
                    <td>${rnd(sq, 6)}</td>
                </tr>`;
            } else {
                rowsX += `<tr><td></td><td></td><td></td><td></td></tr>`;
            }
        }
        
        // Kalkulasi Y
        const nY = yData.length;
        const meanY = nY > 0 ? yData.reduce((a,b)=>a+b, 0) / nY : 0;
        let sumSqY = 0;
        let rowsY = '';
        for(let i=0; i<Math.max(nY, 6); i++) { // Paling tidak 6 baris agar sejajar dikit
            if(i < nY) {
                const val = yData[i];
                const diff = val - meanY;
                const sq = Math.pow(diff, 2);
                sumSqY += sq;
                rowsY += `<tr>
                    <td>${i+1}</td>
                    <td>${rnd(val, 2)}</td>
                    <td>${rnd(diff, 2)}</td>
                    <td>${rnd(sq, 6)}</td>
                </tr>`;
            } else {
                rowsY += `<tr><td style="color:transparent;">-</td><td></td><td></td><td></td></tr>`;
            }
        }
        
        // Fill empty rows to make them equal length physically
        const maxRows = Math.max(nX, nY, 20);
        let combinedRows = '';
        const docX = document.createElement('tbody'); docX.innerHTML = rowsX;
        const docY = document.createElement('tbody'); docY.innerHTML = rowsY;
        
        for(let i=0; i<maxRows; i++) {
            let trX = docX.children[i] ? docX.children[i].innerHTML : '<td></td><td></td><td></td><td></td>';
            let trY = docY.children[i] ? docY.children[i].innerHTML : '<td></td><td></td><td></td><td></td>';
            combinedRows += `<tr>${trX}${trY}</tr>`;
        }

        const df = nX + nY - 2;
        let sGab = 0;
        let tHitung = 0;
        if(df > 0 && nX > 0 && nY > 0) {
            sGab = Math.sqrt((sumSqX + sumSqY) / df);
            tHitung = Math.abs(meanX - meanY) / sGab;
        }
        
        // Simple T-Tabel map for alpha=0.05 two-tailed
        const tTableMap = {
            20: 2.086, 21: 2.080, 22: 2.074, 23: 2.069, 24: 2.064, 25: 2.060,
            26: 2.056, 27: 2.052, 28: 2.048, 29: 2.045, 30: 2.042, 31: 2.040,
            32: 2.037, 33: 2.035, 34: 2.032, 35: 2.030, 36: 2.028, 37: 2.026,
            38: 2.024, 39: 2.023, 40: 2.021, 60: 2.000, 120: 1.980
        };
        const tTabel = tTableMap[df] || 2.0; // fallback

        const paramNames = {
            'IM': 'Moisture in the analysis sample',
            'ASH': 'Ash Content',
            'VM': 'Volatile Matter',
            'TS': 'Total Sulfur',
            'CV': 'Gross Calorific Value'
        };
        const paramFullName = paramNames[code] || code;

        const isStabil = tHitung < tTabel;
        const kesimpulan = (nY < 6) ? '<span class="text-danger">Belum Selesai (Input Stabilitas Kosong)</span>' : (isStabil ? 'Stabil' : 'Tidak Stabil');

        let sumX = xData.reduce((a,b)=>a+b, 0);
        let sumY = yData.reduce((a,b)=>a+b, 0);

        const kodeSampel = {!! json_encode($batch->nama_sampel) !!};
        const tanggalRaw = document.querySelector('input[name="kondisi[tanggal]"]').value;
        const analis = document.querySelector('input[name="kondisi[analis]"]').value;
        let tglFormatted = tanggalRaw;
        if(tanggalRaw && tanggalRaw.includes('-')) {
            const parts = tanggalRaw.split('-');
            tglFormatted = `${parts[2]}/${parts[1]}/${parts[0]}`;
        }

        let html = `
        <div style="font-size: 12px; line-height: 1.2; padding: 10px;">
            <table style="width:100%; border-bottom: 2px solid black; margin-bottom: 10px;">
                <tr>
                    <td style="font-size: 16px; font-weight: bold; padding-bottom: 5px;">Perhitungan Uji Stabilitas Sampel <i>Inhouse Standard</i></td>
                    <td style="text-align: right; color: #004b87; font-weight: 900; font-size: 16px; font-style: italic;">SUCOFINDO</td>
                </tr>
            </table>

            <table style="width: 70%; margin-bottom: 15px;">
                <tr>
                    <td style="width: 30%;">Parameter Uji</td>
                    <td style="width: 5%;">:</td>
                    <td style="background-color: #e9ecef; padding: 2px 5px;">${paramFullName}</td>
                </tr>
                <tr>
                    <td>Kode Sampel Inhouse Standard</td>
                    <td>:</td>
                    <td style="background-color: #e9ecef; padding: 2px 5px;">${kodeSampel}</td>
                </tr>
            </table>

            <table class="table-t-test" style="width: 100%; border-collapse: collapse; text-align: center; border: 2px solid black;">
                <thead>
                    <tr>
                        <th colspan="4" style="border: 1px solid black; background-color: #f8f9fa;">Uji Homogenitas</th>
                        <th colspan="4" style="border: 1px solid black; background-color: #f8f9fa;">Uji Stabilitas</th>
                    </tr>
                    <tr>
                        <th style="border: 1px solid black; font-style: italic; font-weight:normal;">Kode<br><br>Contoh</th>
                        <th style="border: 1px solid black; font-weight:normal;">${paramFullName}</th>
                        <th style="border: 1px solid black; font-weight:normal;">Xi - X̄</th>
                        <th style="border: 1px solid black; font-weight:normal;">(Xi - X̄)²</th>
                        <th style="border: 1px solid black; font-style: italic; font-weight:normal;">Kode<br><br>Contoh</th>
                        <th style="border: 1px solid black; font-weight:normal;">${paramFullName}</th>
                        <th style="border: 1px solid black; font-weight:normal;">Yi - Ȳ</th>
                        <th style="border: 1px solid black; font-weight:normal;">(Yi - Ȳ)²</th>
                    </tr>
                </thead>
                <tbody>
                    ${combinedRows}
                </tbody>
                <tfoot style="font-weight: bold; border-top: 2px solid black;">
                    <tr>
                        <td style="border: 1px solid black; text-align: left; padding: 2px;">Banyaknya Grup<br>(nx) =<br>Jumlah (Σ) =<br>Rata-rata (X̄) =</td>
                        <td style="border: 1px solid black;"><br>${nX}<br>${rnd(sumX, 2)}<br>${rnd(meanX, 3)}</td>
                        <td style="border: 1px solid black;"></td>
                        <td style="border: 1px solid black; vertical-align: bottom;">${rnd(sumSqX, 6)}</td>
                        
                        <td style="border: 1px solid black; text-align: left; padding: 2px;">Banyaknya Grup<br>(ny) =<br>Jumlah (Σ) =<br>Rata-rata (Ȳ) =</td>
                        <td style="border: 1px solid black;"><br>${nY}<br>${rnd(sumY, 2)}<br>${rnd(meanY, 3)}</td>
                        <td style="border: 1px solid black;"></td>
                        <td style="border: 1px solid black; vertical-align: bottom;">${rnd(sumSqY, 6)}</td>
                    </tr>
                </tfoot>
            </table>

            <table style="width: 100%; margin-top: 15px; font-weight: bold;">
                <tr>
                    <td style="width: 15%;">S<sub>gab</sub> =</td>
                    <td style="width: 15%; background-color: #e9ecef; text-align: center;">${rnd(sGab, 3)}</td>
                    <td colspan="4"></td>
                </tr>
                <tr>
                    <td style="padding-top: 10px;">t <sub>hitung</sub> =</td>
                    <td style="padding-top: 10px; background-color: #e9ecef; text-align: center;">${rnd(tHitung, 3)}</td>
                    <td style="padding-top: 10px; text-align: center; width: 5%;">${isStabil ? '<' : '>'}</td>
                    <td style="padding-top: 10px; width: 15%;">t<sub>tabel 95% db</sub> =</td>
                    <td style="padding-top: 10px; background-color: #e9ecef; text-align: center; width: 15%;">${rnd(tTabel, 3)}</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="padding-top: 15px; font-weight: normal;">Kesimpulan</td>
                    <td colspan="5" style="padding-top: 15px; font-weight: bold;">${kesimpulan}</td>
                </tr>
            </table>

            <table style="width: 100%; margin-top: 30px; text-align: left;">
                <tr>
                    <td style="width: 10%;">Disusun oleh</td>
                    <td style="width: 25%; background-color: #e9ecef; text-align: center; padding: 5px;">${analis}</td>
                    <td style="width: 15%; text-align: center;">Ttd</td>
                    <td style="width: 10%;">Tanggal :</td>
                    <td style="width: 20%; background-color: #e9ecef; text-align: center; padding: 5px;">${tglFormatted}</td>
                    <td></td>
                </tr>
                <tr><td colspan="6" style="height: 10px;"></td></tr>
                <tr>
                    <td>Diperiksa oleh</td>
                    <td style="background-color: #e9ecef; text-align: center; padding: 5px;"></td>
                    <td style="text-align: center;">Ttd</td>
                    <td>Tanggal :</td>
                    <td style="background-color: #e9ecef; text-align: center; padding: 5px;"></td>
                    <td></td>
                </tr>
            </table>
        </div>
        <style>
            .table-t-test td { border: 1px solid black; padding: 1px 4px; }
        </style>
        `;
        modalPrintArea.innerHTML = html;
    }

    // === EXPORT LOGIC ===
    const btnExecuteExport = document.getElementById('btnExecuteExport');
    
    // Toggle warning when checkboxes change
    const chkExportParams = document.querySelectorAll('.chk-export-param');
    const chkExportAll = document.getElementById('chkExportAll');
    
    chkExportAll.addEventListener('change', function() {
        chkExportParams.forEach(cb => cb.checked = this.checked);
    });
    chkExportParams.forEach(cb => {
        cb.addEventListener('change', function() {
            if(!this.checked) chkExportAll.checked = false;
            if(document.querySelectorAll('.chk-export-param:checked').length === chkExportParams.length) {
                chkExportAll.checked = true;
            }
        });
    });

    btnExecuteExport.addEventListener('click', function() {
        const format = document.querySelector('input[name="exportFormat"]:checked').value;
        const part = document.querySelector('input[name="exportPart"]:checked').value;
        const selectedParams = Array.from(document.querySelectorAll('.chk-export-param:checked')).map(cb => cb.value);
        
        if(selectedParams.length === 0) {
            Swal.fire('Peringatan', 'Pilih minimal satu parameter untuk dicetak.', 'warning');
            return;
        }

        const originalBtnHtml = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        this.disabled = true;

        setTimeout(() => {
            try {
                if(format === 'excel') exportToExcel(selectedParams, part);
                else if(format === 'pdf') exportToPdf(selectedParams, part);
                else if(format === 'word') exportToWord(selectedParams, part);
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Kesalahan: ' + e.message, 'error');
            }
            this.innerHTML = originalBtnHtml;
            this.disabled = false;
        }, 300);
    });

    function exportToExcel(params, part) {
        const wb = XLSX.utils.book_new();
        const oldCode = modalParamSelect.value;
        
        params.forEach(code => {
            const ghost = document.createElement('div');
            
            // 1. Data Input Table
            if(part === 'both' || part === 'main') {
                const origTable = document.querySelector(`.param-table[data-code="${code}"]`);
                if(origTable) {
                    const tableClone = origTable.cloneNode(true);
                    tableClone.querySelectorAll('input').forEach(inp => {
                        const text = document.createTextNode(inp.value);
                        inp.parentNode.replaceChild(text, inp);
                    });
                    ghost.appendChild(tableClone);
                }
            }
            
            // 2. Data T-Test
            if(part === 'both' || part === 'ttest') {
                modalParamSelect.value = code;
                renderModalTTest();
                
                const ttestTable = document.querySelector('#modalPrintArea .table-t-test');
                if(ttestTable) {
                    const ttestClone = ttestTable.cloneNode(true);
                    ghost.appendChild(ttestClone);
                }
            }
            
            let combinedWsData = [];
            let tableIndex = 0;
            const tablesInGhost = ghost.querySelectorAll('table');
            
            if(part === 'both' || part === 'main') {
                if(tablesInGhost[tableIndex]) {
                    const ws1 = XLSX.utils.table_to_sheet(tablesInGhost[tableIndex]);
                    const json1 = XLSX.utils.sheet_to_json(ws1, {header:1, raw:false});
                    combinedWsData = combinedWsData.concat([[`HASIL DATA MENTAH STABILITAS - ${code}`], []]);
                    combinedWsData = combinedWsData.concat(json1);
                    tableIndex++;
                }
            }
            
            if(part === 'both' || part === 'ttest') {
                if(tablesInGhost[tableIndex]) {
                    if(combinedWsData.length > 0) {
                        combinedWsData.push([]);
                        combinedWsData.push([]);
                    }
                    combinedWsData.push([`DETAIL PERHITUNGAN T-TEST STABILITAS - ${code}`]);
                    combinedWsData.push([]);
                    const ws2 = XLSX.utils.table_to_sheet(tablesInGhost[tableIndex]);
                    const json2 = XLSX.utils.sheet_to_json(ws2, {header:1, raw:false});
                    combinedWsData = combinedWsData.concat(json2);
                }
            }
            
            const finalWs = XLSX.utils.aoa_to_sheet(combinedWsData);
            XLSX.utils.book_append_sheet(wb, finalWs, code);
        });
        
        // Restore T-Test modal
        modalParamSelect.value = oldCode;
        renderModalTTest();
        
        XLSX.writeFile(wb, `Laporan_Stabilitas.xlsx`);
    }

    function buildExportHtml(params, part) {
        let html = `<html><head><meta charset="utf-8"><style>
            body { font-family: 'Arial', sans-serif; font-size: 11px; color: black; }
            .official-table { border-collapse: collapse; width: 100%; margin-bottom: 20px; font-size: 10px; }
            .official-table th, .official-table td { border: 1px solid black; padding: 4px; text-align: center; }
            .official-table th { background-color: #f8f9fa; font-weight: bold; }
            .no-border { border: none !important; }
            .no-border td { border: none !important; }
            .header-title { font-size: 18px; font-weight: bold; }
            .title-box { background-color: #e9ecef; font-weight: bold; padding: 3px 5px; }
            .bg-grey { background-color: #f0f0f0; }
            .page-break { page-break-after: always; }
        </style></head><body>`;
        
        const oldCode = modalParamSelect.value;
        
        params.forEach((code, index) => {
            if (index > 0) html += `<div class="page-break"></div>`;
            
            if(part === 'both' || part === 'main') {
                const origTable = document.querySelector(`.param-table[data-code="${code}"]`);
                if(origTable) {
                    html += `<div style="font-size: 16px; font-weight: bold; text-align: center; margin-bottom: 10px;">DATA UTAMA UJI STABILITAS - ${code}</div>`;
                    const tableClone = origTable.cloneNode(true);
                    tableClone.querySelectorAll('input').forEach(inp => {
                        const text = document.createTextNode(inp.value);
                        inp.parentNode.replaceChild(text, inp);
                    });
                    tableClone.className = "official-table";
                    html += tableClone.outerHTML;
                    if(part === 'both') html += `<div class="page-break"></div>`;
                }
            }
            
            if(part === 'both' || part === 'ttest') {
                modalParamSelect.value = code;
                renderModalTTest();
                const prt = document.getElementById('modalPrintArea').innerHTML;
                html += prt;
            }
        });
        
        modalParamSelect.value = oldCode;
        renderModalTTest();
        
        html += `</body></html>`;
        return html;
    }

    function exportToPdf(params, part) {
        const html = buildExportHtml(params, part);
        const container = document.createElement('div');
        container.innerHTML = html;
        
        var opt = {
            margin:       [0.4, 0.4, 0.4, 0.4],
            filename:     'Laporan_Stabilitas.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
        };
        
        html2pdf().set(opt).from(container).save();
    }

    function exportToWord(params, part) {
        const htmlContent = buildExportHtml(params, part);
        const blob = new Blob(['\ufeff', htmlContent], {
            type: 'application/msword'
        });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'Laporan_Stabilitas.doc';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

});
</script>

<style>
.param-table input.form-control-sm {
    font-size: 0.8rem;
    padding: 0.2rem 0.4rem;
    border-radius: 0;
}
.param-table th {
    font-size: 0.75rem;
    vertical-align: middle;
}
.param-table td {
    padding: 0.2rem;
}
/* Hilangkan validasi bawaan browser krn kita main manual + draft */
input:invalid {
    box-shadow: none;
}
</style>
@endsection
