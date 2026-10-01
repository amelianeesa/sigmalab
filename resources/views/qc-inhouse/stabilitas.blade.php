@extends('layouts.app')
@section('title', 'Stabilitas - QC In-House')

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
                                @php 
                                    $rawCode = trim(strtoupper($param->parameterUji->nama_parameter)); 
                            $code = in_array($rawCode, ['TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)', 'TS']) ? 'TS' : $rawCode;
                                @endphp
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold {{ $index === 0 ? 'active' : '' }}" 
                                            id="tab-{{ $param->id }}" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#pane-{{ $param->id }}" 
                                            type="button" role="tab"
                                            data-code="{{ $code }}">
                                        {{ $rawCode }}
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
                                        <div class="col-12">
                                            <label class="form-label small text-muted mb-1">Tanggal Uji</label>
                                            <input type="date" class="form-control form-control-sm" name="kondisi[tanggal]" value="{{ date('Y-m-d') }}" required>
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
                                    $rawCode = trim(strtoupper($param->parameterUji->nama_parameter)); 
                            $code = in_array($rawCode, ['TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)', 'TS']) ? 'TS' : $rawCode;
                                    $pid = $param->id;
                                @endphp
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="pane-{{ $pid }}" role="tabpanel">
                                    
                                                                        <!-- Tombol Buka Modal & Summary -->
                                    <div class="px-3 pt-3">
                                        <button type="button" class="btn btn-outline-primary btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modalResource-{{ $pid }}">
                                            <i class="fas fa-users-cog me-1"></i> Pilih Personil & Alat ({{ $code }})
                                        </button>
                                        <div id="summary_{{ $pid }}" class="p-2 border rounded bg-light small d-none">
                                            <div class="fw-bold text-secondary mb-1">Terpilih:</div>
                                            <div class="summary-content text-dark"></div>
                                        </div>
                                    </div>

                                    <!-- Modal Resource (Desain Tab) -->
                                    <div class="modal fade modal-resource" id="modalResource-{{ $pid }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title fw-bold"><i class="fas fa-box-open text-primary me-2"></i>Personil & Alat: {{ $code }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-0">
                                                    <ul class="nav nav-pills nav-justified mb-0 border-bottom" role="tablist">
                                                        <li class="nav-item" role="presentation">
                                                            <button class="nav-link active fw-bold text-dark py-3" data-bs-toggle="tab" data-bs-target="#tab-personil-{{ $pid }}" type="button" role="tab"><i class="fas fa-users text-primary me-2"></i>Personil</button>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <button class="nav-link fw-bold text-dark py-3" data-bs-toggle="tab" data-bs-target="#tab-alat-{{ $pid }}" type="button" role="tab"><i class="fas fa-tools text-warning me-2"></i>Alat</button>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <button class="nav-link fw-bold text-dark py-3" data-bs-toggle="tab" data-bs-target="#tab-bahan-{{ $pid }}" type="button" role="tab"><i class="fas fa-flask text-success me-2"></i>Bahan / Reagen</button>
                                                        </li>
                                                    </ul>
                                                    
                                                    <div class="tab-content p-4">
                                                        <div class="d-flex justify-content-end mb-3">
                                                            <button type="button" class="btn btn-sm btn-info text-white btn-copy-resource" data-pid="{{ $pid }}">
                                                                <i class="fas fa-copy me-1"></i> Salin dari Parameter Sebelumnya
                                                            </button>
                                                        </div>

                                                        <!-- Tab Personil -->
                                                        <div class="tab-pane fade show active" id="tab-personil-{{ $pid }}" role="tabpanel">
                                                            <div class="table-responsive border rounded" style="max-height: 350px; overflow-y: auto;">
                                                                <table class="table table-hover table-sm mb-0 text-nowrap">
                                                                    <thead class="table-light sticky-top">
                                                                        <tr><th width="5%" class="text-center">Pilih</th><th>Nama Personil</th><th>Peran / Tugas</th></tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($personilList as $personil)
                                                                        <tr>
                                                                            <td class="text-center align-middle">
                                                                                <input class="form-check-input chk-personil" style="transform: scale(1.3);" type="checkbox" name="resource_{{ $pid }}[personil_ids][]" value="{{ $personil->personil_id }}">
                                                                            </td>
                                                                            <td class="align-middle">{{ $personil->nama }}</td>
                                                                            <td><input type="text" class="form-control form-control-sm in-peran" name="resource_{{ $pid }}[personil_peran][{{ $personil->personil_id }}]" value="Analis" placeholder="Analis"></td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <!-- Tab Alat -->
                                                        <div class="tab-pane fade" id="tab-alat-{{ $pid }}" role="tabpanel">
                                                            <div class="row">
                                                                @foreach($alatList as $alat)
                                                                <div class="col-md-6 mb-3">
                                                                    <div class="form-check border p-2 rounded bg-light">
                                                                        <input class="form-check-input chk-alat ms-1" style="transform: scale(1.3);" type="checkbox" name="resource_{{ $pid }}[alat_ids][]" value="{{ $alat->alat_id }}">
                                                                        <label class="form-check-label ms-2 cursor-pointer w-100">
                                                                            <strong>{{ $alat->nama_alat }}</strong> <br><small class="text-muted">({{ $alat->kode_alat }})</small>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>

                                                        <!-- Tab Bahan -->
                                                        <div class="tab-pane fade" id="tab-bahan-{{ $pid }}" role="tabpanel">
                                                            <div class="table-responsive border rounded" style="max-height: 350px; overflow-y: auto;">
                                                                <table class="table table-hover table-sm mb-0 text-nowrap">
                                                                    <thead class="table-light sticky-top">
                                                                        <tr><th width="5%" class="text-center">Pilih</th><th>Nama Bahan</th><th>Sisa Stok</th><th width="30%">Jumlah Dipakai</th></tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($barangList as $barang)
                                                                        @php
                                                                            $saldoAkhir = ($barang->saldo_awal + $barang->penerimaan) - $barang->pengeluaran;
                                                                            $habis = $saldoAkhir <= 0;
                                                                        @endphp
                                                                        <tr class="{{ $habis ? 'table-danger' : '' }}">
                                                                            <td class="text-center align-middle">
                                                                                <input class="form-check-input chk-bahan" style="transform: scale(1.3);" type="checkbox" name="resource_{{ $pid }}[barang_ids][]" value="{{ $barang->barang_id }}" data-nama="{{ $barang->nama_barang }}">
                                                                            </td>
                                                                            <td class="align-middle">
                                                                                <strong>{{ $barang->nama_barang }}</strong> 
                                                                                @if($habis) <span class="badge bg-danger ms-1">Habis</span> @endif
                                                                            </td>
                                                                            <td class="align-middle {{ $habis ? 'text-danger fw-bold' : '' }}">{{ $saldoAkhir }} {{ $barang->satuan }}</td>
                                                                            <td>
                                                                                <div class="input-group input-group-sm">
                                                                                    <input type="number" step="0.01" class="form-control in-qty" name="resource_{{ $pid }}[barang_jumlah][{{ $barang->barang_id }}]" placeholder="0">
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
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-primary px-4 fw-bold" data-bs-dismiss="modal"><i class="fas fa-check me-1"></i> Simpan Pilihan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive p-3">
                                        <table class="table table-bordered table-sm align-middle text-center param-table text-nowrap" style="min-width: {{ in_array($code, ['TS']) ? '100%' : '1500px' }};" id="table-{{ $pid }}" data-pid="{{ $pid }}" data-code="{{ $code }}">
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
                                                        <th style="min-width: 80px;">%db</th>
                                                        <th style="min-width: 80px;">db</th>
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
                                                        <th style="min-width: 80px;">%db</th>
                                                    </tr>
                                                @elseif($code === 'TS')
                                                <tr>
                                                    <th width="10%">KODE SAMPEL</th>
                                                    <th>DISH NO.</th>
                                                    <th>Massa sample</th>
                                                    <th class="bg-warning bg-opacity-25">TS (Adb)</th>
                                                    <th>Average % (adb)</th>
                                                    <th class="bg-warning bg-opacity-25">Average % (db)</th>
                                                </tr>
                                                @elseif($code === 'CV')
                                                    <tr>
                                                        <th width="8%">KODE SAMPEL</th>
                                                        <th width="8%">VESSEL ID.</th>
                                                        <th>CALL ID</th>
                                                        <th>Weight of Crucible</th>
                                                        <th>Sample Mass</th>
                                                        <th>Primary Result (cal/g)</th>
                                                        <th style="min-width: 90px;">Ee</th>
                                                        <th style="min-width: 90px;">t</th>
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
                                                        <td class="bg-light p-1 text-center"><input type="text" class="form-control form-control-sm text-center fw-bold" name="data_{{ $pid }}[{{ $i-1 }}][mentah][dish_1]" value="{{ $mentah['dish_1'] ?? '' }}" ></td>
                                                        
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
                                                        <td rowspan="2" class="align-middle out-avg-db fw-bold text-success">-</td>
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

                                                    <tr class="row-duplo">
                                                        <td class="bg-light p-1 text-center"><input type="text" class="form-control form-control-sm text-center fw-bold" name="data_{{ $pid }}[{{ $i-1 }}][mentah][dish_2]" value="{{ $mentah['dish_2'] ?? '' }}"></td>
                                                        
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
                                    @if(in_array($batch->status, ['uji_stabilitas', 'gagal_stabilitas']))
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
                                    @endif
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
                            
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-5">
                    @if(in_array($batch->status, ['uji_stabilitas', 'gagal_stabilitas']))
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm" id="btnSubmit">
                        <i class="fas fa-check-double me-2"></i> Simpan Data Uji Stabilitas
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

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
                            @php 
                                $rawCode = trim(strtoupper($param->parameterUji->nama_parameter)); 
                            $code = in_array($rawCode, ['TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)', 'TS']) ? 'TS' : $rawCode;
                            @endphp
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
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>

const State = {};
@foreach($batch->parameters as $param)
    @php 
        $rawCode = trim(strtoupper($param->parameterUji->nama_parameter)); 
                            $code = in_array($rawCode, ['TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)', 'TS']) ? 'TS' : $rawCode;
    @endphp
    State["{{ $code }}"] = {
        id: {{ $param->id }},
        ready: false,
        data: Array.from({length: {{ max(3, $param->dataStabilitas->count()) }} }, () => ({ 
            simplo_adb: null, duplo_adb: null, avg_adb: null 
        })),
        limit: {{ $tolerances[$param->id] ?? 0.09 }} // Default fixed limit unless IM dynamic
    };
@endforeach

@if(session('error'))
    Swal.fire({ icon: 'error', title: 'Gagal Menyimpan', text: @json(session('error')) });
@endif
@if(session('success'))
    Swal.fire({ icon: 'success', title: 'Berhasil', text: @json(session('success')), timer: 2500, showConfirmButton: false });
@endif

document.addEventListener('DOMContentLoaded', function() {

    let invalidHandled = false;
    document.getElementById('formStabilitas').addEventListener('invalid', function(e) {
        if (invalidHandled) return;
        
        const pane = e.target.closest('.tab-pane');
        if (!pane) return;
        const btn = document.querySelector(`[data-bs-target="#${pane.id}"]`);
        if (!btn) return;

        invalidHandled = true;
        setTimeout(() => invalidHandled = false, 1500);

        Swal.fire({ 
            toast: true, 
            position: 'top-end', 
            icon: 'warning',
            title: 'Harap lengkapi semua isian botol (termasuk di tab lain).',
            showConfirmButton: false, 
            timer: 3000 
        });
        
        bootstrap.Tab.getOrCreateInstance(btn).show();
    }, true);

    const tTableMap = @json($tTabelLookup);
    
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
            $rawPCode = strtoupper($p->parameterUji->nama_parameter);
            $pCode = in_array($rawPCode, ['TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']) ? 'TS' : $rawPCode;
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

                if($v1 !== null) $dbArr[] = round((float)$v1, 2);
                if($v2 !== null) $dbArr[] = round((float)$v2, 2);
            }
        @endphp
        "{{ $pCode }}": {!! json_encode($dbArr) !!},
        @endforeach
    };
    
    const tablesByCode = {};
    document.querySelectorAll('.param-table').forEach(table => {
        const code = table.dataset.code;
        tablesByCode[code] = table;

        table.addEventListener('input', function(e) {
            if (e.target.tagName === 'INPUT') {
                if (code === 'IM') {
                    executionOrder.forEach(c => { if (tablesByCode[c]) processTable(c, tablesByCode[c]); });
                } else {
                    processTable(code, table);
                }
            }
        });
        table.addEventListener('change', function(e) {
            if(e.target.tagName === 'INPUT') {
                if (code === 'IM') {
                    executionOrder.forEach(c => { if (tablesByCode[c]) processTable(c, tablesByCode[c]); });
                } else {
                    processTable(code, table);
                }
            }
        });

        table.addEventListener('blur', function(e) {
            if(e.target.tagName === 'INPUT' && (e.target.classList.contains('in-hasil-1') || e.target.classList.contains('in-hasil-2'))) {
                if(e.target.value && !e.target.readOnly) e.target.value = rnd(e.target.value, 2);
            }
        }, true); // capture phase since blur doesn't bubble
    });

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

            cloneSimplo.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/\[\d+\]/, `[${newIndex}]`);
            });
            cloneDuplo.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/\[\d+\]/, `[${newIndex}]`);
            });

            cloneSimplo.querySelectorAll('input').forEach(el => { el.value = ''; });
            cloneDuplo.querySelectorAll('input').forEach(el => { el.value = ''; });
            cloneSimplo.querySelectorAll('select').forEach(el => { el.value = ''; });

            cloneSimplo.querySelectorAll('td').forEach(td => {
                if(td.textContent.trim() === '-') td.textContent = '-';
            });

            tbody.appendChild(cloneSimplo);
            tbody.appendChild(cloneDuplo);

            State[code].data.push({ simplo_adb: null, duplo_adb: null, avg_adb: null });

            if (code === 'IM') {
                executionOrder.forEach(c => {
                    if(tablesByCode[c]) processTable(c, tablesByCode[c]);
                });
            } else {
                processTable(code, table);
            }
        });
    });

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

            const lastIndex = simploRows.length - 1;
            tbody.removeChild(simploRows[lastIndex]);
            tbody.removeChild(duploRows[lastIndex]);

            State[code].data.pop();

            if (code === 'IM') {
                executionOrder.forEach(c => {
                    if(tablesByCode[c]) processTable(c, tablesByCode[c]);
                });
            } else {
                processTable(code, table);
            }
        });
    });

    document.querySelectorAll('.btn-save-sheet').forEach(btn => {
        btn.addEventListener('click', function() {
            const pid = this.dataset.pid;
            const code = this.dataset.code;
            const form = document.getElementById('formStabilitas');
            const url = form.action;

            const origHtml = this.innerHTML;
            this.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...`;
            this.disabled = true;

            const allFormData = new FormData(form);
            const filteredData = new FormData();

            for (let [key, value] of allFormData.entries()) {
                if (key === '_token' || key.startsWith('kondisi[') || key.startsWith(`data_${pid}[`) || key.startsWith(`resource_${pid}[`)) {
                    filteredData.append(key, value);
                }
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: filteredData
            })
            .then(async response => {
                let data = null;
                try { data = await response.json(); } catch (e) {}

                if (response.ok && data && data.success) {
                    Swal.fire({ icon: 'success', title: 'Tersimpan!',
                        text: `Data Parameter ${code} berhasil disimpan sementara.`,
                        timer: 2000, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal Menyimpan',
                        text: (data && data.message) || `Terjadi kesalahan saat menyimpan tabel ${code}.` });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error Jaringan!',
                    text: `Gagal terhubung ke server saat menyimpan tabel ${code}.`,
                    timer: 2500,
                    showConfirmButton: false
                });
            })
            .finally(() => {
                this.innerHTML = origHtml;
                this.disabled = false;
            });
        });
    });

    const executionOrder = ['IM', 'ASH', 'VM', 'TS', 'CV'];
    executionOrder.forEach(code => {
        if(tablesByCode[code]) processTable(code, tablesByCode[code]);
    });

    Object.keys(tablesByCode).forEach(code => {
        if(!executionOrder.includes(code)) processTable(code, tablesByCode[code]);
    });

    setInterval(() => {
        const activeTab = document.querySelector('.nav-link.active');
        if(activeTab) {
            const code = activeTab.dataset.code;
            const table = document.querySelector(`.param-table[data-code="${code}"]`);
            if(table) processTable(code, table);
        }
    }, 500);

    function rnd(val, dec=4) {
        if(isNaN(val) || val === null || val === '') return '-';
        return Number(val).toFixed(dec);
    }

    function getVal(el) {
        if(!el || el.value === undefined || el.value === null || el.value === '') return NaN;

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

            if(code === 'IM') {
                const m1_1 = getVal(tr1.querySelector('.in-m1-1'));
                const a_1 = getVal(tr1.querySelector('.in-a-1'));
                const m3_1 = getVal(tr1.querySelector('.in-m3-1'));

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

            else if(code === 'TS') {
                val1 = getVal(tr1.querySelector('.in-hasil-1'));
                val2 = getVal(tr2.querySelector('.in-hasil-2'));
                const mass1 = getVal(tr1.querySelector('.in-massa-1'));
                const mass2 = getVal(tr2.querySelector('.in-massa-2'));
                if(isNaN(val1) || isNaN(val2)) isComplete = false;
            }

            else if(code === 'CV') {
                const ts_s = (State['TS'] && State['TS'].data[i]) ? State['TS'].data[i].simplo_adb : null;
                const ts_d = (State['TS'] && State['TS'].data[i]) ? State['TS'].data[i].duplo_adb : null;

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
                State[code].data[i].simplo_db = null;
                State[code].data[i].duplo_db = null;
                
                tr1.querySelector('.out-diff') ? tr1.querySelector('.out-diff').textContent = '-' : null;
                tr1.querySelector('.out-tol') ? tr1.querySelector('.out-tol').textContent = '-' : null;
                tr1.querySelector('.out-avg-adb') ? tr1.querySelector('.out-avg-adb').textContent = '-' : null;
                tr1.querySelector('.out-avg-db') ? tr1.querySelector('.out-avg-db').textContent = '-' : null;
                
                if(tr1.querySelector('.in-db-1')) tr1.querySelector('.in-db-1').value = '';
                if(tr1.querySelector('.in-db-2')) tr1.querySelector('.in-db-2').value = '';
            
            }
        }

        State[code].ready = isComplete;
        renderLivePreview();
    }

    document.querySelectorAll('#parameterTabs button[data-bs-toggle="tab"]').forEach(btn => {
        btn.addEventListener('shown.bs.tab', function (e) {
            const newCode = e.target.dataset.code;
            const tbl = document.querySelector(`.param-table[data-code="${newCode}"]`);
            if (tbl) processTable(newCode, tbl); 
        });
    });

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
        
        const tTabel = tTableMap[df] ?? 1.98;
        
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
        let rowsX = '';
        for(let i=0; i<nX; i++) {
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

        const nY = yData.length;
        const meanY = nY > 0 ? yData.reduce((a,b)=>a+b, 0) / nY : 0;
        let sumSqY = 0;
        let rowsY = '';
        for(let i=0; i<nY; i++) {
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

        const maxRows = Math.max(nX, nY);
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

        const tTabel = tTableMap[df] ?? 1.98;

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
        const analis = '';
        let tglFormatted = tanggalRaw;
        if(tanggalRaw && tanggalRaw.includes('-')) {
            const parts = tanggalRaw.split('-');
            tglFormatted = `${parts[2]}/${parts[1]}/${parts[0]}`;
        }

        const logoUrl = window.location.origin + '/images/Logo_Suco_Nobg.png';

        let html = `
        <div style="font-size: 12px; line-height: 1.2; padding: 10px;">
            <table style="width:100%; border-bottom: 2px solid black; margin-bottom: 10px; page-break-inside: avoid;">
                <tr>
                    <td style="font-size: 16px; font-weight: bold; padding-bottom: 5px; vertical-align: bottom;">Perhitungan Uji Stabilitas Sampel <i>Inhouse Standard</i></td>
                    <td style="text-align: right; vertical-align: bottom; width: 150px; padding-bottom: 5px;">
                        <img src="${logoUrl}" style="width: 140px; height: auto; display: block; margin-left: auto;" alt="SUCOFINDO">
                    </td>
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

    const btnExecuteExport = document.getElementById('btnExecuteExport');

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

        setTimeout(async () => {
            try {
                if(format === 'excel') {
                    exportToExcel(selectedParams, part);
                    this.innerHTML = originalBtnHtml;
                    this.disabled = false;
                } else if(format === 'pdf') {
                    await exportToPdf(selectedParams, part);
                    this.innerHTML = originalBtnHtml;
                    this.disabled = false;
                } else if(format === 'word') {
                    exportToWord(selectedParams, part);
                    this.innerHTML = originalBtnHtml;
                    this.disabled = false;
                }
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Kesalahan: ' + e.message, 'error');
                this.innerHTML = originalBtnHtml;
                this.disabled = false;
            }
        }, 300);
    });

    function exportToExcel(params, part) {
        const wb = XLSX.utils.book_new();
        const oldCode = modalParamSelect.value;
        
        params.forEach(code => {
            const ghost = document.createElement('div');

            if(part === 'both' || part === 'main') {
                const origTable = document.querySelector(`.param-table[data-code="${code}"]`);
                if(origTable) {
                    const tableClone = origTable.cloneNode(true);

                    const origSelects = origTable.querySelectorAll('select');
                    tableClone.querySelectorAll('select').forEach((sel, idx) => {
                        const o = origSelects[idx];
                        const text = o && o.selectedIndex >= 0 ? o.options[o.selectedIndex].text : '';
                        sel.parentNode.replaceChild(document.createTextNode(text), sel);
                    });

                    tableClone.querySelectorAll('input[type="hidden"]').forEach(el => el.remove());
                    tableClone.querySelectorAll('input').forEach(inp => {
                        const text = document.createTextNode(inp.value);
                        inp.parentNode.replaceChild(text, inp);
                    });
                    ghost.appendChild(tableClone);
                }
            }

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
            .pdf-break { page-break-before: always; break-before: page; }
            .official-table { table-layout: fixed; width: 100%; }
            .official-table th, .official-table td {
                white-space: normal !important;
                word-break: break-word;
                overflow-wrap: anywhere;
                padding: 3px 2px;
                font-size: 8px;
            }
            .table-cv th, .table-cv td { font-size: 6.5px; padding: 2px 1px; }
        </style></head><body>`;

        let sectionCount = 0;
        function openSection() {
            const isFirst = sectionCount++ === 0;
            return isFirst
                ? `<div class="pdf-section">`
                : `<div class="pdf-section pdf-break" style="page-break-before: always; break-before: page;">`;
        }

        const oldCode = modalParamSelect.value;

        params.forEach((code, index) => {

            if(part === 'both' || part === 'main') {
                const origTable = document.querySelector(`.param-table[data-code="${code}"]`);
                if(origTable) {
                    html += openSection();
                    html += `<div style="font-size: 16px; font-weight: bold; text-align: center; margin-bottom: 10px;">DATA UTAMA UJI STABILITAS - ${code}</div>`;
                    const tableClone = origTable.cloneNode(true);

                    const origSelects = origTable.querySelectorAll('select');
                    tableClone.querySelectorAll('select').forEach((sel, idx) => {
                        const o = origSelects[idx];
                        const text = o && o.selectedIndex >= 0 ? o.options[o.selectedIndex].text : '';
                        sel.parentNode.replaceChild(document.createTextNode(text), sel);
                    });

                    tableClone.querySelectorAll('input').forEach(inp => {
                        if (inp.type === 'hidden') { inp.remove(); return; }
                        inp.parentNode.replaceChild(document.createTextNode(inp.value), inp);
                    });
                    tableClone.className = "official-table" + (code === 'CV' ? " table-cv" : "");
                    tableClone.removeAttribute('style');
                    tableClone.style.width = '100%';
                    tableClone.style.tableLayout = 'fixed';

                    // buang semua lebar bawaan di th/td (min-width, width="8%", dll)
                    tableClone.querySelectorAll('th, td').forEach(c => {
                        c.style.minWidth = '';
                        c.style.width = '';
                        c.removeAttribute('width');
                        const colCount = tableClone.querySelectorAll('thead tr:first-child th').length;
                    });

                    html += tableClone.outerHTML;
                    html += `</div>`;
                }
            }

            if(part === 'both' || part === 'ttest') {
                modalParamSelect.value = code;
                renderModalTTest();
                const prt = document.getElementById('modalPrintArea').innerHTML;
                html += openSection();
                html += prt;
                html += `</div>`;
            }
        });

        modalParamSelect.value = oldCode;
        renderModalTTest();

        html += `</body></html>`;
        return html;
    }

    function exportToPdf(params, part) {
        const html = buildExportHtml(params, part);

        const doc = new DOMParser().parseFromString(html, 'text/html');
        const css = Array.from(doc.querySelectorAll('style'))
            .map(s => s.textContent).join('\n');
        const sections = Array.from(doc.querySelectorAll('.pdf-section'));

        if (sections.length === 0) {
            return Promise.reject(new Error('Tidak ada data untuk dicetak.'));
        }

        const pages = sections.map(sec => {
            sec.classList.remove('pdf-break');
            sec.style.pageBreakBefore = 'auto';
            sec.style.breakBefore = 'auto';

            // buang elemen form yang tidak perlu dicetak
            sec.querySelectorAll('input[type="hidden"]').forEach(el => el.remove());
            sec.querySelectorAll('table').forEach(tbl => {
                tbl.classList.remove('text-nowrap');
                tbl.style.minWidth = '0';
                tbl.style.maxWidth = '100%';
            });

            const wrap = document.createElement('div');
            const style = document.createElement('style');
            style.textContent = css;
            wrap.appendChild(style);
            wrap.appendChild(sec);
            return wrap;
        });

        const opt = {
            margin:       [0.4, 0.4, 0.4, 0.4],
            filename:     'Laporan_Stabilitas.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' },
            pagebreak: { mode: ['css', 'legacy'], avoid: ['tr'] }
        };

        let worker = html2pdf().set(opt).from(pages[0]).toPdf();
        for (let i = 1; i < pages.length; i++) {
            worker = worker
                .get('pdf')
                .then(pdf => { pdf.addPage(); })
                .from(pages[i])
                .toContainer()
                .toCanvas()
                .toPdf();
        }
        return worker.save();
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

       
    function updateResourceSummary(pid) {
        let modal = document.getElementById('modalResource-' + pid);
        if(!modal) return;
        
        let personils = [], alats = [], bahans = [];
        
        modal.querySelectorAll('.chk-personil:checked').forEach(cb => {
            let peran = cb.closest('tr').querySelector('.in-peran').value || 'Analis';
            let text = cb.closest('tr').querySelectorAll('td')[1].innerText;
            personils.push(`${text} (${peran})`);
        });
        
                modal.querySelectorAll('.chk-alat:checked').forEach(cb => {
            let text = cb.closest('.form-check').querySelector('strong').innerText;
            alats.push(text);
        });
        
        modal.querySelectorAll('.chk-bahan:checked').forEach(cb => {
            let qty = cb.closest('tr').querySelector('.in-qty').value || '0';
            let satuan = cb.closest('tr').querySelector('.input-group-text').innerText;
            bahans.push(`${cb.dataset.nama} (${qty} ${satuan})`);
        });

        let summaryDiv = document.getElementById('summary_' + pid);
        let contentDiv = summaryDiv.querySelector('.summary-content');
        
        if (personils.length || alats.length || bahans.length) {
                let html = '';
                if(personils.length) html += `<div><strong class="text-primary">Analis:</strong> ${personils.join(', ')}</div>`;
                if(alats.length) html += `<div><strong class="text-success">Alat:</strong> ${alats.join(', ')}</div>`;
                if(bahans.length) html += `<div><strong class="text-warning">Bahan:</strong> ${bahans.join(', ')}</div>`;
            
            contentDiv.innerHTML = html;
            summaryDiv.classList.remove('d-none');
        } else {
            summaryDiv.classList.add('d-none');
        }
    }

    document.querySelectorAll('.modal-resource').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function () {
            let pid = this.id.replace('modalResource-', '');
            updateResourceSummary(pid);
        });
    });

    document.querySelectorAll('.btn-copy-resource').forEach(btn => {
        btn.addEventListener('click', function() {
            let currentPid = this.dataset.pid;
            let currentTab = document.querySelector('#parameterTabs .nav-link.active');
            let prevTabItem = currentTab.closest('li.nav-item').previousElementSibling;
            
            if (prevTabItem) {
                let prevTabLink = prevTabItem.querySelector('.nav-link');
                let prevPid = prevTabLink.dataset.pid || prevTabLink.id.replace('tab-', '');
                
                let prevModal = document.getElementById('modalResource-' + prevPid);
                let currModal = document.getElementById('modalResource-' + currentPid);
                
                if (prevModal && currModal) {
                    currModal.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
                    currModal.querySelectorAll('.in-peran, .in-qty').forEach(inpt => inpt.value = '');

                    prevModal.querySelectorAll('.chk-personil:checked').forEach(cb => {
                        let targetCb = currModal.querySelector(`.chk-personil[value="${cb.value}"]`);
                        if(targetCb) {
                            targetCb.checked = true;
                            targetCb.closest('tr').querySelector('.in-peran').value = cb.closest('tr').querySelector('.in-peran').value;
                        }
                    });

                    prevModal.querySelectorAll('.chk-alat:checked').forEach(cb => {
                        let targetCb = currModal.querySelector(`.chk-alat[value="${cb.value}"]`);
                        if(targetCb) targetCb.checked = true;
                    });

                    prevModal.querySelectorAll('.chk-bahan:checked').forEach(cb => {
                        let targetCb = currModal.querySelector(`.chk-bahan[value="${cb.value}"]`);
                        if(targetCb) {
                            targetCb.checked = true;
                            targetCb.closest('tr').querySelector('.in-qty').value = cb.closest('tr').querySelector('.in-qty').value;
                        }
                    });
                    alert('Berhasil menyalin data dari parameter sebelumnya!');
                    updateResourceSummary(currentPid);
                }
            } else {
                alert('Ini adalah parameter pertama, tidak ada data sebelumnya yang bisa disalin.');
            }
        });
    });

    document.querySelectorAll('.in-qty').forEach(input => {
        input.addEventListener('input', function() {
            let tr = this.closest('tr');
            let checkbox = tr.querySelector('.chk-bahan');
            if (this.value && parseFloat(this.value) > 0) {
                checkbox.checked = true;
            } else {
                checkbox.checked = false;
            }
        });
    });
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
