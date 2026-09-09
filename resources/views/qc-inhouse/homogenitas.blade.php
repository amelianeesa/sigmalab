@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item"><a href="{{ route('qc-inhouse.show', $batch->sampel_inhouse_id) }}">{{ $batch->nama_sampel }}</a></li>
        <li class="breadcrumb-item active">Input Uji Homogenitas (Tahap 3)</li>
    </x-qc-breadcrumb>

    <div class="row mt-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1"><i class="fas fa-edit text-primary me-2"></i>Input Uji Homogenitas (Tahap 3)</h3>
                    <p class="text-muted mb-0">Metode Acuan: <strong>{{ strtoupper($batch->metode_acuan) }}</strong> | Jenis Batubara: <strong>{{ $batch->jenis_batubara }}</strong></p>
                </div>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalExport">
                    <i class="fas fa-print me-2"></i>Cetak / Export
                </button>
            </div>
            
            <form action="{{ route('qc-inhouse.homogenitas.store', $batch->sampel_inhouse_id) }}" method="POST" id="formHomogenitas">
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
                        <div class="alert alert-info m-3 rounded-0 border-start border-4 border-info">
                            <i class="fas fa-info-circle me-2"></i> Ketergantungan Data: Tab <strong>IM</strong> wajib diisi lebih dulu karena parameter lain membutuhkan nilai IM untuk konversi ke basis Dry Basis (db). Tab <strong>CV</strong> juga membutuhkan nilai dari tab <strong>TS</strong>.
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
                                                @for($i = 1; $i <= 10; $i++)
                                                    @php 
                                                        $botolNomor = $tabelAcak[$i]->nomor_botol ?? $i; 
                                                        $dh = $param->dataHomogenitas->where('nomor_sampel', $i)->first();
                                                        $mentah = $dh ? $dh->data_mentah : [];
                                                    @endphp
                                                    <!-- SIMPLO ROW -->
                                                    <tr class="row-simplo">
                                                        <td rowspan="2" class="fw-bold align-middle bg-light border-end">Botol {{ $botolNomor }}
                                                            <input type="hidden" name="data_{{ $pid }}[{{ $i-1 }}][nomor_botol_fisik]" value="{{ $botolNomor }}">
                                                            <input type="hidden" class="in-db-1" name="data_{{ $pid }}[{{ $i-1 }}][mentah][nilai_db_1]" value="{{ $mentah['nilai_db_1'] ?? '' }}">
                                                            <input type="hidden" class="in-db-2" name="data_{{ $pid }}[{{ $i-1 }}][mentah][nilai_db_2]" value="{{ $mentah['nilai_db_2'] ?? '' }}">
                                                        </td>
                                                        <td class="bg-light fw-bold">1</td>
                                                        
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
                                                        <td class="bg-light fw-bold">2</td>
                                                        
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
                                    <div class="px-3 pb-3 d-flex justify-content-end">
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
                                <h5 class="fw-bold mb-1 text-primary">Live ANOVA Summary (Preview)</h5>
                                <p class="small text-muted mb-0">Klik tombol di sebelah kanan untuk melihat detail tabel perhitungan (Ai+Bi) seperti di Excel.</p>
                            </div>
                            <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#modalDetailAnova">
                                <i class="fas fa-table me-2"></i>Lihat Detail Kalkulasi Statistik
                            </button>
                        </div>
                        <div class="row text-center mt-3" id="anovaPreviewBoxes">
                            <!-- Injected by JS -->
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-5">
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm" id="btnSubmit">
                        <i class="fas fa-check-double me-2"></i> Kunci Semua & Lanjut ke Penetapan Target
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
                            <label class="form-check-label" for="partBoth">Keduanya (Tabel Data Utama & Detail Perhitungan ANOVA)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportPart" id="partMain" value="main">
                            <label class="form-check-label" for="partMain">Tabel Data Utama Saja</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportPart" id="partAnova" value="anova">
                            <label class="form-check-label" for="partAnova">Detail Perhitungan ANOVA Saja</label>
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
                    <small class="text-danger mt-2 d-block" id="exportWarning" style="display: none !important;"><i class="fas fa-exclamation-triangle"></i> Hanya parameter yang datanya sudah lengkap terisi yang akan dicetak.</small>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnExecuteExport"><i class="fas fa-download me-2"></i>Download</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DETAIL ANOVA -->
<div class="modal fade" id="modalDetailAnova" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-excel text-success me-2"></i>Detail Perhitungan Uji Homogenitas Sampel Inhouse Standard</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <!-- Select Parameter in Modal -->
                <div class="mb-3 w-25">
                    <label class="fw-bold form-label">Pilih Parameter:</label>
                    <select class="form-select" id="modalParamSelect">
                        @foreach($batch->parameters as $param)
                            <option value="{{ strtoupper($param->parameterUji->nama_parameter) }}">{{ strtoupper($param->parameterUji->nama_parameter) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-white p-4 border rounded shadow-sm">
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="fw-bold mb-0" style="width: 25%;">I. DATA:</h6>
                        <h6 class="fw-bold mb-0 text-start" style="width: 75%;">II. PERHITUNGAN:</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center align-middle" style="min-width: 900px; border-color: #212529;">
                            <thead class="table-light align-middle border-dark">
                                <tr>
                                    <th rowspan="2">Kode<br>Contoh</th>
                                    <th colspan="2" id="modalValParamName" class="border-end border-dark">PARAMETER</th>
                                    <th colspan="3">Untuk Perhitungan MSB</th>
                                    <th colspan="3">Untuk Perhitungan MSW</th>
                                </tr>
                                <tr>
                                    <th>A (simplo)</th>
                                    <th class="border-end border-dark">B (duplo)</th>
                                    <th>(Ai + Bi)</th>
                                    <th>(Ai + Bi) - Xab</th>
                                    <th>[(Ai + Bi) - Xab]&sup2;</th>
                                    <th>(Ai - Bi)</th>
                                    <th>(Ai - Bi) - Xab</th>
                                    <th>[(Ai - Bi) - Xab]&sup2;</th>
                                </tr>
                            </thead>
                            <tbody id="modalTableCombined"></tbody>
                            <tfoot class="bg-light border-dark" id="modalTableCombinedFoot"></tfoot>
                        </table>
                    </div>

                    <!-- RUMUS -->
                    <div class="row mt-4 align-items-center border-bottom pb-4 mb-4">
                        <div class="col-md-8">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="text-end align-middle fw-bold w-25">MSB =</td>
                                    <td class="text-center align-middle border-bottom border-dark" style="width: 250px;">
                                        E [ (Ai + Bi) - Xab ]&sup2;
                                    </td>
                                    <td class="align-middle text-center w-25" rowspan="2">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <span>=</span>
                                            <div class="d-flex flex-column text-center">
                                                <span class="border-bottom border-dark px-2" id="modalValMsbFormulaAtas">-</span>
                                                <span>18</span>
                                            </div>
                                            <span>=</span>
                                            <span class="fw-bold" id="modalValMsbLengkap">-</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center align-middle">2 . (n-1)</td>
                                </tr>
                            </table>
                            
                            <table class="table table-borderless table-sm mt-3 mb-0">
                                <tr>
                                    <td class="text-end align-middle fw-bold w-25">MSW =</td>
                                    <td class="text-center align-middle border-bottom border-dark" style="width: 250px;">
                                        E [ (Ai - Bi) - Xab ]&sup2;
                                    </td>
                                    <td class="align-middle text-center w-25" rowspan="2">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <span>=</span>
                                            <div class="d-flex flex-column text-center">
                                                <span class="border-bottom border-dark px-2" id="modalValMswFormulaAtas">-</span>
                                                <span>20</span>
                                            </div>
                                            <span>=</span>
                                            <span class="fw-bold" id="modalValMswLengkap">-</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center align-middle">2 . (n)</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-white border shadow-sm text-center h-100 d-flex flex-column justify-content-center mx-auto" style="max-width: 250px;">
                                <div class="fs-5 mb-3 fw-bold text-muted">
                                    SD<sub><small>sampel in-house</small></sub> = &radic;<span style="border-top: 1px solid black; padding-top: 2px;">&nbsp;&nbsp;<frac>(MSB - MSW)<br><span style="border-top: 1px solid black; display:block">2</span></frac>&nbsp;&nbsp;</span>
                                </div>
                                <div class="d-flex justify-content-between mt-3 px-3">
                                    <span class="fw-bold text-muted">SD =</span>
                                    <span class="fw-bold text-dark" id="modalValSd">-</span>
                                </div>
                                <div class="d-flex justify-content-between mt-1 px-3">
                                    <span class="fw-bold text-muted">mean =</span>
                                    <span class="fw-bold text-dark" id="modalValMean">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KESIMPULAN -->
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <table class="table table-bordered table-sm text-center fw-bold">
                                <tr>
                                    <td class="bg-light w-25">MSB</td>
                                    <td id="modalValMsb">-</td>
                                </tr>
                                <tr>
                                    <td class="bg-light">MSW</td>
                                    <td id="modalValMsw">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-7">
                            <div class="p-3 bg-light border rounded">
                                <h6 class="fw-bold mb-3">III. KESIMPULAN F-TEST</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-center">
                                        <div class="small text-muted">F Hitung</div>
                                        <h4 class="fw-bold text-primary mb-0" id="modalValFhitung">-</h4>
                                    </div>
                                    <div class="text-center">
                                        <h4 class="fw-bold mb-0 text-secondary" id="modalValKesimpulanOp">?</h4>
                                    </div>
                                    <div class="text-center">
                                        <div class="small text-muted">F Tabel (v1=9, v2=10)</div>
                                        <h4 class="fw-bold text-dark mb-0">3.020</h4>
                                    </div>
                                </div>
                                <hr>
                                <div class="fs-6">
                                    <div class="mb-1">a) <span id="modalValKesimpulanTextA">-</span></div>
                                    <div>b) Hal ini artinya, bahwa contoh tersebut <strong id="modalValKesimpulanTextB">-</strong></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

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
        data: Array.from({length: 10}, () => ({ 
            simplo_adb: null, duplo_adb: null, avg_adb: null 
        })),
        limit: {{ $tolerances[$param->id] ?? 0.09 }} // Default fixed limit unless IM dynamic
    };
@endforeach

document.addEventListener('DOMContentLoaded', function() {
    
    // Bind Event Listeners
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
        
        for(let i=0; i<10; i++) {
            const tr1 = table.querySelectorAll('.row-simplo')[i];
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
                    
                    const im_s = State['IM'] ? State['IM'].data[i].simplo_adb : null;
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
                    
                    const im_d = State['IM'] ? State['IM'].data[i].duplo_adb : null;
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
                const im_s = State['IM'] ? State['IM'].data[i].simplo_adb : null;
                const im_d = State['IM'] ? State['IM'].data[i].duplo_adb : null;

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
                    const im_s = State['IM'] ? State['IM'].data[i].simplo_adb : null;
                    State[code].data[i].simplo_db = im_s !== null ? parseFloat(rnd((100 / (100 - im_s)) * val1, code === 'CV' ? 0 : 2)) : null;
                    const im_d = State['IM'] ? State['IM'].data[i].duplo_adb : null;
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
                    const im_avg = State['IM'] ? State['IM'].data[i].avg_adb : null;
                    if(im_avg !== null) {
                        const avg_db = (100 / (100 - im_avg)) * avg_adb;
                        tr1.querySelector('.out-avg-db') ? tr1.querySelector('.out-avg-db').textContent = rnd(avg_db, (code==='CV'?0:2)) : null;
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
        
        // Update Dependencies
        updateDependencies();
        
        // Calculate ANOVA
        if(isComplete) {
            calculateAnovaPreview(code);
        } else {
            // Clear ANOVA preview if not complete
            State[code].anova = null;
            if(document.querySelector('.nav-link.active') && document.querySelector('.nav-link.active').dataset.code === code) {
                renderPreview();
            }
        }
    }

    function updateDependencies() {
        const isImReady = State['IM'] ? State['IM'].ready : true;
        const isTsReady = State['TS'] ? State['TS'].ready : true;

        // Unlock TS if IM is ready
        if(State['TS']) {
            const pane = document.getElementById('pane-' + State['TS'].id);
            if(!isImReady) {
                pane.style.opacity = '0.4'; pane.style.pointerEvents = 'none';
            } else {
                pane.style.opacity = '1'; pane.style.pointerEvents = 'auto';
            }
        }

        // Unlock ASH, VM if IM ready
        ['ASH', 'VM'].forEach(c => {
            if(State[c]) {
                const pane = document.getElementById('pane-' + State[c].id);
                if(!isImReady) {
                    pane.style.opacity = '0.4'; pane.style.pointerEvents = 'none';
                } else {
                    pane.style.opacity = '1'; pane.style.pointerEvents = 'auto';
                    // We DO NOT call processTable(c) here to avoid infinite recursion!
                    // Instead, we just visually let the user click on it.
                }
            }
        });

        // Unlock CV if IM and TS ready
        if(State['CV']) {
            const pane = document.getElementById('pane-' + State['CV'].id);
            if(!isImReady || !isTsReady) {
                pane.style.opacity = '0.4'; pane.style.pointerEvents = 'none';
            } else {
                pane.style.opacity = '1'; pane.style.pointerEvents = 'auto';
            }
        }
    }

    function calculateAnovaPreview(code) {
        // Pure JS ANOVA Calculation
        let sumA = 0, sumB = 0;
        const n = 10;
        let ai = [], bi = [];

        for(let i=0; i<n; i++) {
            let a = State[code].data[i].simplo_adb;
            let b = State[code].data[i].duplo_adb;
            
            if (code === 'ASH' || code === 'VM' || code === 'TS' || code === 'CV') {
                a = State[code].data[i].simplo_db;
                b = State[code].data[i].duplo_db;
            }
            
            if(a === null || b === null || a === undefined || b === undefined || isNaN(a) || isNaN(b)) return; // Prevent calc if data is corrupt
            ai.push(a); bi.push(b);
            sumA += a; sumB += b;
        }

        const grandMean = (sumA + sumB) / (2 * n);

        let sumSqBetween = 0;
        let sumSqWithin = 0;

        for(let i=0; i<n; i++) {
            let groupMean = (ai[i] + bi[i]) / 2;
            sumSqBetween += Math.pow(groupMean - grandMean, 2);
            sumSqWithin += Math.pow(ai[i] - groupMean, 2) + Math.pow(bi[i] - groupMean, 2);
        }

        let msbSum = 0;
        let mswSum = 0;
        for(let i=0; i<n; i++) {
            msbSum += Math.pow((ai[i]+bi[i]) - (sumA+sumB)/n, 2);
            mswSum += Math.pow((ai[i]-bi[i]) - (sumA-sumB)/n, 2);
        }
        let MSB = msbSum / (2 * (n-1));
        let MSW = mswSum / (2 * n);
        
        let F = MSB / MSW;
        let FTabel = 3.020;
        let isHomogen = F < FTabel;

        State[code].anova = { ai, bi, msbSum, mswSum, MSB, MSW, F, isHomogen };
        renderPreview();
    }

    function renderPreview() {
        const activeTab = document.querySelector('.nav-link.active');
        if(!activeTab) return;
        const code = activeTab.dataset.code;
        
        const elParamName = document.getElementById('modalParamName');
        if (elParamName) elParamName.textContent = code;

        const previewDiv = document.getElementById('anovaPreviewBoxes');
        if(State[code].ready && State[code].anova) {
            const a = State[code].anova;
            previewDiv.innerHTML = `
                <div class="col-3">
                    <div class="small text-muted">MSB</div>
                    <h4 class="fw-bold text-dark">${rnd(a.MSB)}</h4>
                </div>
                <div class="col-3">
                    <div class="small text-muted">MSW</div>
                    <h4 class="fw-bold text-dark">${rnd(a.MSW)}</h4>
                </div>
                <div class="col-3">
                    <div class="small text-muted">F-Hitung</div>
                    <h4 class="fw-bold text-primary">${rnd(a.F)}</h4>
                </div>
                <div class="col-3">
                    <div class="small text-muted">Keputusan</div>
                    <h4 class="fw-bold ${a.isHomogen ? 'text-success' : 'text-danger'}">
                        ${a.isHomogen ? '<i class="fas fa-check-circle me-1"></i> HOMOGEN' : '<i class="fas fa-times-circle me-1"></i> TIDAK'}
                    </h4>
                </div>
            `;
        } else {
            previewDiv.innerHTML = `<div class="col-12"><p class="text-muted fst-italic">Lengkapi 10 botol (simplo & duplo) untuk melihat Live ANOVA.</p></div>`;
        }
    }

    // Modal Builder
    const modalParamSelect = document.getElementById('modalParamSelect');
    modalParamSelect.addEventListener('change', renderModalAnova);
    
    document.getElementById('modalDetailAnova').addEventListener('show.bs.modal', function () {
        const activeTabCode = document.querySelector('.nav-link.active').dataset.code;
        modalParamSelect.value = activeTabCode;
        renderModalAnova();
    });

    function renderModalAnova() {
        const code = modalParamSelect.value;
        if(!State[code] || !State[code].ready || !State[code].anova) {
            document.getElementById('modalTableCombined').innerHTML = `<tr><td colspan="9" class="text-danger fw-bold py-3 text-center">Data parameter ${code} belum lengkap atau gagal dihitung karena ada data dependensi (IM/TS) yang belum lengkap.</td></tr>`;
            document.getElementById('modalTableCombinedFoot').innerHTML = '';
            document.getElementById('modalValMsbFormulaAtas').textContent = '-';
            document.getElementById('modalValMsbLengkap').textContent = '-';
            document.getElementById('modalValMswFormulaAtas').textContent = '-';
            document.getElementById('modalValMswLengkap').textContent = '-';
            document.getElementById('modalValMsb2').textContent = '-';
            document.getElementById('modalValMsw2').textContent = '-';
            document.getElementById('modalValMean').textContent = '-';
            document.getElementById('modalValMsb').textContent = '-';
            document.getElementById('modalValMsw').textContent = '-';
            document.getElementById('modalValFhitung').textContent = '-';
            document.getElementById('modalValKesimpulanOp').textContent = '-';
            document.getElementById('modalValKesimpulanTextA').textContent = '-';
            document.getElementById('modalValKesimpulanTextB').innerHTML = '-';
            return;
        }

        const a = State[code].anova;
        const n = 10;
        let sumA = 0, sumB = 0;
        let ai = a.ai, bi = a.bi;
        
        for(let i=0; i<n; i++) { sumA += ai[i]; sumB += bi[i]; }
        const Xab = (sumA + sumB) / n;
        const XabMinus = (sumA - sumB) / n;

        let htmlCombined = '';
        let decData = code === 'CV' ? 0 : 2;

        for(let i=0; i<n; i++) {
            let aib = ai[i] + bi[i];
            let aibX = aib - Xab;
            let aibX2 = Math.pow(aibX, 2);

            let amb = ai[i] - bi[i];
            let ambX = amb - XabMinus;
            let ambX2 = Math.pow(ambX, 2);

            htmlCombined += `<tr>
                <td class="fst-italic">${i+1}</td>
                <td>${rnd(ai[i], decData)}</td>
                <td class="border-end border-dark">${rnd(bi[i], decData)}</td>
                <td>${rnd(aib, decData)}</td>
                <td>${rnd(aibX, 3)}</td>
                <td>${rnd(aibX2, 4)}</td>
                <td>${rnd(amb, 2)}</td>
                <td>${rnd(ambX, 3)}</td>
                <td>${rnd(ambX2, 4)}</td>
            </tr>`;
        }
        
        document.getElementById('modalTableCombined').innerHTML = htmlCombined;

        document.getElementById('modalTableCombinedFoot').innerHTML = `
            <tr class="text-start">
                <td colspan="3" class="border-end border-dark fw-bold">Banyaknya Grup (n) =</td>
                <td class="text-center fw-bold">${n}</td>
                <td colspan="2"></td>
                <td class="text-center fw-bold">${n}</td>
                <td colspan="2"></td>
            </tr>
            <tr class="text-start">
                <td colspan="3" class="border-end border-dark fw-bold">Jumlah (&Sigma;) =</td>
                <td class="text-center fw-bold">${rnd(sumA + sumB, decData)}</td>
                <td></td>
                <td class="text-center fw-bold">${rnd(a.msbSum, 4)}</td>
                <td class="text-center fw-bold">${rnd(XabMinus * n, 1)}</td>
                <td></td>
                <td class="text-center fw-bold">${rnd(a.mswSum, 4)}</td>
            </tr>
            <tr class="text-start">
                <td colspan="3" class="border-end border-dark fw-bold">Rata-rata (X) =</td>
                <td class="text-center fw-bold">${rnd((sumA + sumB) / n, decData)}</td>
                <td colspan="2"></td>
                <td class="text-center fw-bold">${rnd(XabMinus, 2)}</td>
                <td colspan="2"></td>
            </tr>
        `;
        
        const paramNames = {
            'IM': 'Inherent Moisture',
            'ASH': 'Ash Content',
            'VM': 'Volatile Matter',
            'TS': 'Total Sulfur',
            'CV': 'Calorific Value'
        };
        document.getElementById('modalValParamName').textContent = paramNames[code] || code;

        document.getElementById('modalValMsbFormulaAtas').textContent = rnd(a.msbSum);
        document.getElementById('modalValMsbLengkap').textContent = rnd(a.MSB, 6);
        
        document.getElementById('modalValMswFormulaAtas').textContent = rnd(a.mswSum);
        document.getElementById('modalValMswLengkap').textContent = rnd(a.MSW, 6);
        
        let sdVal = Math.sqrt(Math.max(0, (a.MSB - a.MSW) / 2));
        document.getElementById('modalValSd').textContent = rnd(sdVal);
        document.getElementById('modalValMean').textContent = rnd((sumA+sumB)/(2*n), 2);

        document.getElementById('modalValMsb').textContent = rnd(a.MSB);
        document.getElementById('modalValMsw').textContent = rnd(a.MSW);
        
        const fHitungVal = rnd(a.F);
        const fTabelVal = '3.020';
        document.getElementById('modalValFhitung').textContent = fHitungVal;
        
        const opText = a.isHomogen ? '<' : '>';
        document.getElementById('modalValKesimpulanOp').textContent = opText;
        
        document.getElementById('modalValKesimpulanTextA').textContent = `F hitung (${fHitungVal}) ${opText} F tabel (${fTabelVal})`;
        document.getElementById('modalValKesimpulanTextB').innerHTML = a.isHomogen ? '<span class="text-success">HOMOGEN</span>' : '<span class="text-danger">TIDAK HOMOGEN</span>';
    }

    // Trigger initial locks and preview listener
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(btn => {
        btn.addEventListener('shown.bs.tab', function (e) {
            renderPreview();
            // retrigger processTable for this specific tab to pull IM db dependencies if needed
            const newCode = e.target.dataset.code;
            processTable(newCode, document.querySelector(`.param-table[data-code="${newCode}"]`));
        });
    });
    
    // AJAX Save Per Sheet
    document.querySelectorAll('.btn-save-sheet').forEach(btn => {
        btn.addEventListener('click', function() {
            const pid = this.dataset.pid;
            const code = this.dataset.code;
            const table = document.getElementById('table-' + pid);
            
            // HAPUS validasi required() agar bisa nyimpen setengah data (Draft)
            
            // Indicate loading
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
            this.disabled = true;

            // Serialize data for just this parameter + tandai sbg draft
            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('is_draft', '1');
            
            table.querySelectorAll('input').forEach(inp => {
                if (inp.name) {
                    formData.append(inp.name, inp.value);
                }
            });

            fetch(document.getElementById('formHomogenitas').action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan!',
                        text: 'Data Parameter ' + code + ' berhasil disimpan sementara.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error', data.message || 'Gagal menyimpan.', 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error');
            })
            .finally(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });

    // ==========================================
    // EXPORT LOGIC
    // ==========================================
    const chkExportAll = document.getElementById('chkExportAll');
    const paramCheckboxes = document.querySelectorAll('.chk-export-param');
    
    chkExportAll.addEventListener('change', function() {
        paramCheckboxes.forEach(chk => chk.checked = this.checked);
    });
    paramCheckboxes.forEach(chk => {
        chk.addEventListener('change', function() {
            if(!this.checked) chkExportAll.checked = false;
            else if(document.querySelectorAll('.chk-export-param:checked').length === paramCheckboxes.length) chkExportAll.checked = true;
        });
    });

    document.getElementById('btnExecuteExport').addEventListener('click', function() {
        const format = document.querySelector('input[name="exportFormat"]:checked').value;
        const part = document.querySelector('input[name="exportPart"]:checked').value;
        const selectedParams = Array.from(paramCheckboxes).filter(chk => chk.checked).map(chk => chk.value);
        
        if(selectedParams.length === 0) {
            Swal.fire('Peringatan', 'Pilih minimal satu parameter untuk dicetak.', 'warning');
            return;
        }

        // Validate readiness
        let allReady = true;
        let notReadyParams = [];
        selectedParams.forEach(code => {
            if(!State[code] || !State[code].ready) {
                allReady = false;
                notReadyParams.push(code);
            }
        });

        if(!allReady) {
            Swal.fire('Tidak Dapat Mencetak', 'Parameter berikut belum lengkap: ' + notReadyParams.join(', ') + '. Lengkapi semua data Simplo dan Duplo terlebih dahulu.', 'error');
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
            
            // 2. Data ANOVA
            if(part === 'both' || part === 'anova') {
                modalParamSelect.value = code;
                renderModalAnova();
                
                const anovaTable = document.getElementById('modalTableCombined').closest('table');
                if(anovaTable) {
                    const anovaClone = anovaTable.cloneNode(true);
                    ghost.appendChild(anovaClone);
                }
            }
            
            let combinedWsData = [];
            let tableIndex = 0;
            const tablesInGhost = ghost.querySelectorAll('table');
            
            if(part === 'both' || part === 'main') {
                if(tablesInGhost[tableIndex]) {
                    const ws1 = XLSX.utils.table_to_sheet(tablesInGhost[tableIndex]);
                    const json1 = XLSX.utils.sheet_to_json(ws1, {header:1, raw:false});
                    combinedWsData = combinedWsData.concat([[`HASIL UJI HOMOGENITAS - ${code}`], []]);
                    combinedWsData = combinedWsData.concat(json1);
                    tableIndex++;
                }
            }
            
            if(part === 'both' || part === 'anova') {
                if(tablesInGhost[tableIndex]) {
                    if(combinedWsData.length > 0) {
                        combinedWsData.push([]);
                        combinedWsData.push([]);
                    }
                    combinedWsData.push([`DETAIL PERHITUNGAN ANOVA - ${code}`]);
                    combinedWsData.push([]);
                    const ws2 = XLSX.utils.table_to_sheet(tablesInGhost[tableIndex]);
                    const json2 = XLSX.utils.sheet_to_json(ws2, {header:1, raw:false});
                    combinedWsData = combinedWsData.concat(json2);
                    
                    combinedWsData.push([]);
                    combinedWsData.push(['KESIMPULAN F-TEST']);
                    combinedWsData.push(['MSB = ', document.getElementById('modalValMsbLengkap').textContent]);
                    combinedWsData.push(['MSW = ', document.getElementById('modalValMswLengkap').textContent]);
                    combinedWsData.push(['F Hitung = ', document.getElementById('modalValFhitung').textContent]);
                    combinedWsData.push(['F Tabel = ', '3.020']);
                    combinedWsData.push(['Status = ', document.getElementById('modalValKesimpulanTextB').innerText]);
                }
            }
            
            const finalWs = XLSX.utils.aoa_to_sheet(combinedWsData);
            XLSX.utils.book_append_sheet(wb, finalWs, code);
        });
        
        // Restore ANOVA modal
        modalParamSelect.value = oldCode;
        renderModalAnova();
        
        XLSX.writeFile(wb, `Hasil_Uji_Homogenitas.xlsx`);
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
                    html += `<div style="font-size: 16px; font-weight: bold; text-align: center; margin-bottom: 10px;">DATA UTAMA UJI HOMOGENITAS - ${code}</div>`;
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
            
            if(part === 'both' || part === 'anova') {
                modalParamSelect.value = code;
                renderModalAnova();
                
                const a = State[code].anova;
                if(!a) return;
                
                const anovaTable = document.getElementById('modalTableCombined').closest('table');
                let tableHtml = '';
                if(anovaTable) {
                    const anovaClone = anovaTable.cloneNode(true);
                    anovaClone.className = 'official-table';
                    anovaClone.style.minWidth = '100%';
                    tableHtml = anovaClone.outerHTML;
                }
                
                const msbFormulaAtas = document.getElementById('modalValMsbFormulaAtas').textContent;
                const mswFormulaAtas = document.getElementById('modalValMswFormulaAtas').textContent;
                const msb = document.getElementById('modalValMsbLengkap').textContent;
                const msw = document.getElementById('modalValMswLengkap').textContent;
                const fHitung = document.getElementById('modalValFhitung').textContent;
                const isHomogen = a.isHomogen;
                const opText = isHomogen ? '<' : '>';
                
                let paramFull = code;
                if(code === 'CV') paramFull = 'Gross Calorific Value';
                else if(code === 'IM') paramFull = 'Inherent Moisture';
                else if(code === 'ASH') paramFull = 'Ash Content';
                else if(code === 'VM') paramFull = 'Volatile Matter';
                else if(code === 'TS') paramFull = 'Total Sulfur';

                html += `
                <div style="width: 100%; margin: 0 auto;">
                    <!-- Header -->
                    <table style="width:100%; border-bottom: 3px solid black; margin-bottom: 15px;" class="no-border">
                        <tr>
                            <td style="font-size: 16px; font-weight: bold; padding-bottom: 10px; text-align:left;">Perhitungan Uji Homogenitas Sampel <span style="font-style: italic;">Inhouse Standard</span></td>
                            <td style="text-align: right; padding-bottom: 10px; color: #004b87; font-weight: 900; font-size: 18px; font-style: italic;">
                                SUCOFINDO
                            </td>
                        </tr>
                    </table>
                    
                    <!-- Param Info -->
                    <table style="width:80%; font-size: 11px; margin-bottom: 15px;" class="no-border">
                        <tr>
                            <td style="width: 30%; text-align:left;">Parameter Uji</td>
                            <td style="width: 5%;">:</td>
                            <td style="width: 45%;" class="title-box">${paramFull}</td>
                            <td style="width: 20%; text-align:left; padding-left:10px;">(db)</td>
                        </tr>
                        <tr><td colspan="4" style="height:3px;"></td></tr>
                        <tr>
                            <td style="text-align:left;">Kode Sampel Inhouse Standard</td>
                            <td>:</td>
                            <td class="title-box"></td>
                            <td></td>
                        </tr>
                    </table>
                    
                    <!-- Data & Perhitungan Title -->
                    <table style="width:100%; font-size:11px; font-weight:bold; margin-bottom:5px;" class="no-border">
                        <tr>
                            <td style="width:25%; text-align:left;">I. DATA:</td>
                            <td style="width:75%; text-align:left;">II. PERHITUNGAN:</td>
                        </tr>
                    </table>
                    
                    <!-- Main Table -->
                    ${tableHtml}
                    
                    <!-- MSB Formula -->
                    <table style="width: 70%; font-size: 11px; margin-bottom: 15px; background-color: #f0f0f0; padding: 5px;" class="no-border">
                        <tr>
                            <td style="font-weight: bold; width: 10%; text-align: right; vertical-align: middle;">MSB = </td>
                            <td style="width: 30%; text-align: center; vertical-align: middle;">
                                <div style="border-bottom: 1px solid black; margin: 0 auto; width: 80%;">E [ (Ai + Bi) - Xab ]&sup2;</div>
                                <div>2 . (n-1)</div>
                            </td>
                            <td style="width: 5%; text-align: center; vertical-align: middle;">=</td>
                            <td style="width: 20%; text-align: center; vertical-align: middle;">
                                <div style="border-bottom: 1px solid black; margin: 0 auto; width: 80%;">${msbFormulaAtas}</div>
                                <div>18</div>
                            </td>
                            <td style="width: 5%; text-align: center; vertical-align: middle;">=</td>
                            <td style="width: 30%; text-align: left; vertical-align: middle;">${msb}</td>
                        </tr>
                    </table>
                    
                    <!-- MSW Formula -->
                    <table style="width: 70%; font-size: 11px; margin-bottom: 25px; background-color: #f0f0f0; padding: 5px;" class="no-border">
                        <tr>
                            <td style="font-weight: bold; width: 10%; text-align: right; vertical-align: middle;">MSW = </td>
                            <td style="width: 30%; text-align: center; vertical-align: middle;">
                                <div style="border-bottom: 1px solid black; margin: 0 auto; width: 80%;">E [ (Ai - Bi) - Xab ]&sup2;</div>
                                <div>2 . (n)</div>
                            </td>
                            <td style="width: 5%; text-align: center; vertical-align: middle;">=</td>
                            <td style="width: 20%; text-align: center; vertical-align: middle;">
                                <div style="border-bottom: 1px solid black; margin: 0 auto; width: 80%;">${mswFormulaAtas}</div>
                                <div>20</div>
                            </td>
                            <td style="width: 5%; text-align: center; vertical-align: middle;">=</td>
                            <td style="width: 30%; text-align: left; vertical-align: middle;">${msw}</td>
                        </tr>
                    </table>
                    
                    <!-- Section III -->
                    <table style="width: 100%; font-size: 11px; margin-bottom: 5px;" class="no-border">
                        <tr>
                            <td style="font-weight: bold; width: 50%; text-align:left;">III. PERHITUNGAN Nilai "F hitung" dan "F tabel":</td>
                            <td style="font-weight: bold; font-style: italic; width: 50%; text-align:left;">( Asumsinya, Ho='contoh homogen' )</td>
                        </tr>
                    </table>
                    <table style="width: 100%; font-size: 11px; margin-bottom: 25px; background-color: #e9ecef; padding: 10px;" class="no-border">
                        <tr>
                            <td style="font-weight: bold; width: 15%; text-align: right; vertical-align: middle;">F hitung = </td>
                            <td style="font-weight: bold; width: 15%; text-align: center; vertical-align: middle;">
                                <div style="border-bottom: 1px solid black; margin: 0 auto; width: 60%;">MSB</div>
                                <div>MSW</div>
                            </td>
                            <td style="font-weight: bold; width: 5%; text-align: center; vertical-align: middle;">=</td>
                            <td style="font-weight: bold; width: 15%; text-align: center; vertical-align: middle;"><span style="background-color: #d3d3d3; padding: 2px 15px;">${fHitung}</span></td>
                            <td style="font-weight: bold; width: 50%; text-align: right; vertical-align: middle;">F tabel (p=0.05 ; v1= 9; v2= 10 ) = &nbsp;&nbsp;&nbsp; <span style="background-color: #d3d3d3; padding: 2px 15px;">3.020</span></td>
                        </tr>
                    </table>
                    
                    <!-- Section IV -->
                    <table style="width: 100%; font-size: 11px; margin-bottom: 40px;" class="no-border">
                        <tr>
                            <td style="font-weight: bold; width: 20%; text-decoration: underline; vertical-align: top; text-align:left;">IV. KESIMPULAN:</td>
                            <td style="width: 80%; text-align:left;">
                                <div>a). F hitung &nbsp;&nbsp; ${opText} &nbsp;&nbsp; F tabel &nbsp;&nbsp; <span style="color: red; font-style: italic; font-size: 9px;">[isilah dengan tanda "<" atau ">"]</span></div>
                                <div style="margin-top:4px;">b). Hal ini artinya, bahwa contoh tersebut <span style="font-weight:bold;">${isHomogen ? 'HOMOGEN / <span style="text-decoration: line-through; font-weight:normal;">TIDAK Homogen</span>' : '<span style="text-decoration: line-through; font-weight:normal;">HOMOGEN</span> / TIDAK Homogen'}</span> * &nbsp;&nbsp; <span style="color: red; font-style: italic; font-size: 9px;">[ *coret yg tdk perlu ]</span></div>
                                <div style="color: red; font-style: italic; font-size: 9px; margin-top: 5px;">( apabila <span style="font-weight: bold;">F hitung &lt; F tabel</span> maka Homogen, dan jika sebaliknya maka Tidak Homogen )</div>
                            </td>
                        </tr>
                    </table>
                    
                    <!-- Signatures -->
                    <table style="width: 100%; font-size: 11px; margin-bottom: 20px;" class="no-border">
                        <tr>
                            <td style="width: 15%; text-align:left;">Disusun oleh</td>
                            <td style="width: 5%;">:</td>
                            <td style="width: 30%;" class="title-box"></td>
                            <td style="width: 10%; text-align:right;">Tanggal</td>
                            <td style="width: 5%;">:</td>
                            <td style="width: 20%;" class="title-box"></td>
                            <td style="width: 15%;"></td>
                        </tr>
                        <tr><td colspan="7" style="height: 10px;"></td></tr>
                        <tr>
                            <td style="text-align:left;">Diperiksa oleh</td>
                            <td>:</td>
                            <td class="title-box"></td>
                            <td style="text-align:right;">Tanggal</td>
                            <td>:</td>
                            <td class="title-box"></td>
                            <td></td>
                        </tr>
                    </table>
                    
                    <!-- Document Footer -->
                    <table style="width: 100%; font-size: 9px; margin-top: 50px;" class="no-border">
                        <tr>
                            <td style="width: 30%; text-align:left;">FOR/COAL-OPS/214</td>
                            <td style="width: 20%; text-align: center;">Rev. 01</td>
                            <td style="width: 30%; text-align: center;">Tgl. berlaku: 12/08/2023</td>
                            <td style="width: 20%; text-align: right;">Hal 1 dari 1 hal</td>
                        </tr>
                    </table>
                </div>
                `;
            }
        });
        
        modalParamSelect.value = oldCode;
        renderModalAnova();
        
        html += `</body></html>`;
        return html;
    }

    function exportToPdf(params, part) {
        const html = buildExportHtml(params, part);
        const container = document.createElement('div');
        container.innerHTML = html;
        
        var opt = {
            margin:       [0.4, 0.4, 0.4, 0.4],
            filename:     'Hasil_Uji_Homogenitas.pdf',
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
        link.download = 'Hasil_Uji_Homogenitas.doc';
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
