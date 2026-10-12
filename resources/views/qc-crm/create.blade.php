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


    @if($errors->any())
    <div class="alert alert-danger shadow-sm border-0">
        <i class="fas fa-exclamation-triangle me-2"></i> Terdapat kesalahan pada isian form:
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(isset($recentLogs) && $recentLogs->isNotEmpty())
    <div class="alert alert-success shadow-sm border-0 border-start border-4 border-success mb-4">
        <h6 class="fw-bold mb-3 text-success">
            <i class="fas fa-check-circle me-1"></i> Telah Disimpan ke Lembar Kerja: {{ old('no_lembar_kerja') }}
        </h6>
        <div class="table-responsive">
            <table class="table table-sm table-bordered bg-white mb-0 text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="15%">Botol CRM</th>
                        <th width="15%">Parameter Terisi</th>
                        <th class="text-start">Detail Inputan & Kalkulasi</th>
                        <th width="15%">Nilai Akhir</th>
                        <th width="5%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentLogs as $log)
                    <tr>
                        <td class="fw-bold">{{ $log->crmKatalog->nomor_lot ?? '-' }}</td>
                        <td>
                            <span class="badge bg-secondary fs-6">{{ $log->parameterUji->nama_parameter ?? '-' }}</span>
                        </td>
                        
                        <td class="text-start">
                            @php
                                // Decode JSON data mentah dari database
                                $mentah = is_string($log->data_mentah) ? json_decode($log->data_mentah, true) : $log->data_mentah;
                            @endphp
                            
                            @if($mentah && is_array($mentah))
                                <div class="d-flex flex-wrap gap-1 mb-2">
                                @foreach($mentah as $key => $val)
                                    @if($val !== null && $val !== '')
                                        <span class="badge bg-light text-dark border border-secondary" style="font-size: 0.75rem;">
                                            <span class="text-muted">{{ strtoupper(str_replace('_', ' ', $key)) }}:</span> 
                                            <strong>{{ $val }}</strong>
                                        </span>
                                    @endif
                                @endforeach
                                </div>
                            @endif

                            <div class="d-flex flex-wrap gap-1">
                                @if($log->nilai_d1 !== null)
                                    <span class="badge bg-warning text-dark border border-warning" style="font-size: 0.75rem;"><span class="text-muted">Hasil 1:</span> {{ $log->nilai_d1 }}</span>
                                @endif
                                @if($log->nilai_d2 !== null)
                                    <span class="badge bg-warning text-dark border border-warning" style="font-size: 0.75rem;"><span class="text-muted">Hasil 2:</span> {{ $log->nilai_d2 }}</span>
                                @endif
                                @if($log->nilai_db_1 !== null)
                                    <span class="badge bg-info text-dark border border-info" style="font-size: 0.75rem;"><span class="text-muted">DB 1:</span> {{ $log->nilai_db_1 }}</span>
                                @endif
                                @if($log->nilai_db_2 !== null)
                                    <span class="badge bg-info text-dark border border-info" style="font-size: 0.75rem;"><span class="text-muted">DB 2:</span> {{ $log->nilai_db_2 }}</span>
                                @endif
                            </div>
                        </td>

                        <td class="fw-bold fs-5 text-primary">{{ $log->nilai_akhir }}</td>

                        <td class="text-center align-middle">
                            <a href="{{ route('qc-crm.edit', $log->id) }}" class="btn btn-sm btn-outline-warning border-0" title="Edit / Perbaiki Data">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-2 small text-muted">
            <em>*Data di atas sudah aman di database. Silakan pilih botol selanjutnya di bawah ini.</em>
        </div>
    </div>
    @endif

    <form action="{{ route('qc-crm.store') }}" method="POST" id="formQc" autocomplete="off">
        @csrf

        {{-- ============================================================ --}}
        {{-- SECTION 1: DATA DASAR PENGUJIAN --}}
        {{-- ============================================================ --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header text-white py-3" style="background-color: #1b3152;">
                <h5 class="mb-0 fw-bold"><i class="fas fa-clipboard-list me-2"></i>Section 1: Data Dasar Pengujian</h5>
            </div>
            <div class="card-body p-4">
                {{-- Info Batch & Tanggal --}}
                <h6 class="mb-3 border-bottom pb-2 fw-bold" style="color: #1b3152;"><i class="fas fa-info-circle me-2"></i>Informasi Umum</h6>
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
                        <input type="date" name="tanggal_uji" class="form-control" value="{{ old('tanggal_uji', $serverDraft['tanggal_uji'] ?? date('Y-m-d')) }}" required max="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">No. Lembar Kerja <span class="text-danger">*</span></label>
                        <input type="text" name="no_lembar_kerja" id="no_lembar_kerja" class="form-control bg-light" 
                            placeholder="Contoh: WK-2026-09-001" 
                            value="{{ old('no_lembar_kerja', $serverDraft['no_lembar_kerja'] ?? '') }}">
                        <div class="form-text text-muted"><i class="fas fa-info-circle me-1"></i>Digunakan untuk menelusuri sampel batch mana yang dikerjakan bersamaan.</div>
                    </div>
                    
                </div>

                
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- SECTION 2: INPUT DATA PENGUJIAN --}}
        {{-- ============================================================ --}}
        <div class="card shadow-sm border-0 mb-4" id="section2" style="display: none;">
            <div class="card-header text-white py-3" style="background-color: #1b3152;">
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
                                </button>
                            </div>
                        </h2>
                        <div id="collapse-{{ $pid }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $pid }}">
                            <div class="accordion-body bg-white p-4">

                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-flask text-primary me-2"></i>Pengujian {{ $code }}</h5>
                                </div>

                                {{-- RINGKASAN + TOMBOL --}}
                                <div class="p-3 bg-light border rounded d-flex flex-column flex-md-row justify-content-between align-items-md-start mb-3">
                                    <div class="small">
                                        <div class="mb-1"><span class="text-muted d-inline-block" style="width: 50px;">Analis</span>: <span id="sum-personil-{{ $pid }}" class="text-danger fst-italic">Belum diatur</span></div>
                                        <div class="mb-1"><span class="text-muted d-inline-block" style="width: 50px;">Alat</span>: <span id="sum-alat-{{ $pid }}" class="text-danger fst-italic">Belum diatur</span></div>
                                        <div><span class="text-muted d-inline-block" style="width: 50px;">Bahan</span>: <span id="sum-bahan-{{ $pid }}" class="text-danger fst-italic">Belum diatur</span></div>
                                    </div>
                                    <div class="d-flex flex-column gap-2 mt-3 mt-md-0 d-print-none">
                                        <button type="button" class="btn btn-sm text-white shadow-sm" style="background-color: #1b3152;" data-bs-toggle="modal" data-bs-target="#modalResource-{{ $pid }}">
                                            <i class="fas fa-tools me-1"></i> Atur Analis, Alat & Bahan
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info shadow-sm" data-bs-toggle="modal" data-bs-target="#modalRumus-{{ $pid }}">
                                            <i class="fas fa-calculator me-1"></i> Panduan Kolom & Rumus ({{ $code }})
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary btn-copy-resource" data-pid="{{ $pid }}">
                                            <i class="fas fa-copy me-1"></i> Salin Data Sebelumnya
                                        </button>
                                    </div>
                                </div>

                                {{-- MODAL --}}
                                <div class="modal fade modal-resource" id="modalResource-{{ $pid }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header text-white" style="background-color: #1b3152;">
                                                <h5 class="modal-title fw-bold"><i class="fas fa-cogs me-2"></i>Pengaturan Pengujian: {{ $code }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-0">
                                                <ul class="nav nav-tabs nav-fill bg-light m-0" role="tablist">
                                                    <li class="nav-item"><button class="nav-link active fw-bold py-3 text-dark" data-bs-toggle="tab" data-bs-target="#tab-personil-{{ $pid }}" type="button"><i class="fas fa-users text-primary me-2"></i>Personil</button></li>
                                                    <li class="nav-item"><button class="nav-link fw-bold py-3 text-dark" data-bs-toggle="tab" data-bs-target="#tab-alat-{{ $pid }}" type="button"><i class="fas fa-tools text-warning me-2"></i>Alat</button></li>
                                                    <li class="nav-item"><button class="nav-link fw-bold py-3 text-dark" data-bs-toggle="tab" data-bs-target="#tab-bahan-{{ $pid }}" type="button"><i class="fas fa-flask text-success me-2"></i>Bahan</button></li>
                                                </ul>
                                                <div class="tab-content p-4">
                                                    <div class="tab-pane fade show active" id="tab-personil-{{ $pid }}">
                                                        <table class="table table-sm table-hover align-middle">
                                                            <thead class="table-light"><tr><th width="50">Pilih</th><th>Nama Analis</th><th width="200">Peran</th></tr></thead>
                                                            <tbody>
                                                                @foreach($personilList as $p)
                                                                <tr>
                                                                    <td class="text-center"><input class="form-check-input chk-personil" type="checkbox" name="params[{{ $pid }}][personil_ids][]" value="{{ $p->personil_id }}" data-nama="{{ $p->nama }}"></td>
                                                                    <td>{{ $p->nama }}</td>
                                                                    <td><input type="text" class="form-control form-control-sm in-peran" name="params[{{ $pid }}][personil_peran][{{ $p->personil_id }}]" placeholder="Analis / Pendamping"></td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="tab-pane fade" id="tab-alat-{{ $pid }}">
                                                        <div class="row">
                                                            @foreach($alatList as $alat)
                                                            <div class="col-md-6 mb-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input chk-alat" type="checkbox" name="params[{{ $pid }}][alat_ids][]" value="{{ $alat->alat_id }}" data-nama="{{ $alat->nama_alat }}">
                                                                    <label class="form-check-label">{{ $alat->nama_alat }} ({{ $alat->kode_alat }})</label>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="tab-bahan-{{ $pid }}">
                                                        <div class="table-responsive border rounded" style="max-height: 350px; overflow-y: auto;">
                                                            <table class="table table-hover table-sm mb-0">
                                                                <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                                                    <tr><th width="5%" class="text-center">Pilih</th><th>Nama Bahan</th><th>Sisa Stok</th><th width="30%">Jumlah Dipakai</th></tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($barangList as $b)
                                                                    @php $stok = ($b->saldo_awal + $b->penerimaan) - $b->pengeluaran; $habis = $stok <= 0; @endphp
                                                                    <tr class="{{ $habis ? 'table-danger' : '' }}">
                                                                        <td class="text-center align-middle"><input class="form-check-input chk-bahan" type="checkbox" name="params[{{ $pid }}][barang_ids][]" value="{{ $b->barang_id }}" data-nama="{{ $b->nama_barang }}" {{ $habis ? 'disabled' : '' }}></td>
                                                                        <td class="align-middle"><strong>{{ $b->nama_barang }}</strong> @if($habis)<span class="badge bg-danger ms-1">Habis</span>@endif</td>
                                                                        <td class="align-middle text-muted">{{ number_format($stok, 0, ',', '.') }} {{ $b->satuan }}</td>
                                                                        <td>
                                                                            <div class="input-group input-group-sm">
                                                                                <input type="number" step="0.01" min="0" class="form-control in-qty" name="params[{{ $pid }}][barang_jumlah][{{ $b->barang_id }}]" placeholder="0" {{ $habis ? 'disabled' : '' }}>
                                                                                <span class="input-group-text">{{ $b->satuan }}</span>
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
                                                <button type="button" class="btn px-4 text-white rounded-pill" style="background-color: #1b3152;" data-bs-dismiss="modal">Simpan & Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @include('partials.modal-panduan-inhouse', ['pid' => $pid, 'code' => $code])
                                
                                <div class="table-responsive pb-2">
                                <table class="table table-bordered table-sm align-middle text-center param-table {{ in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']) ? 'w-auto' : '' }}" id="table-{{ $pid }}" data-pid="{{ $pid }}" data-code="{{ $code }}">
                                        <thead class="table-light">
                                          <tr>
                                              <th>DISH NO</th>
                                          @if(in_array($code, ['IM','RM']))
                                              <th>M1</th><th>M2</th><th>M3</th><th>A</th><th>B</th><th class="bg-warning bg-opacity-25">M%</th>
                                          @elseif($code === 'ASH')
                                              <th>M1</th><th>M2</th><th>M2-M1</th><th>M3</th><th>M3-M1</th><th class="bg-warning bg-opacity-25">ASH%</th>
                                          @elseif($code === 'VM')
                                              <th>M1</th><th>M2-M1</th><th>M2</th><th>M3</th><th>M2-M3</th><th>LOSS%</th><th>IM</th><th class="bg-warning bg-opacity-25">VM%</th>
                                          @elseif(in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
                                              <th style="width: 16%">Massa sample</th><th class="bg-warning bg-opacity-25" style="width: 16%">TS (adb)</th>
                                          @elseif(in_array($code, ['CV','GCV']))
                                              <th>Weight of Crucible</th><th>Sample Mass</th><th>Primary Result (cal/g)</th><th>Ee</th><th>t</th><th>Volume of Titrant (ml)</th><th>Length of Fuse (cm)</th><th>Total TS</th><th class="bg-warning bg-opacity-25">Final Result (cal/g) adb <i class="fas fa-info-circle ms-1 text-primary" data-bs-toggle="tooltip" title="(PR - (14.3 * 0.0699 * VT) - (2.3 * LF) - (13.2 * TS * SM)) / SM"></i></th>
                                          @elseif($code === 'CHN')
                                              <th>Weight</th><th>N % db</th><th>C % db</th><th>H % db</th>
                                          @elseif($code === 'AFT')
                                              <th>Reducing/Oxidizing</th><th>IDT</th><th>ST</th><th>HT</th><th>FT</th>
                                          @else
                                              <th class="bg-warning bg-opacity-25">Hasil Uji (adb)</th>
                                          @endif
                                          @if(!in_array($code, ['CHN', 'AFT', 'TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)', 'CV', 'GCV']))
                                              <th colspan="2">ABSOLUTE DIFFERENCE <i class="fas fa-info-circle ms-1 text-primary" data-bs-toggle="tooltip" title="0.09 + (0.1 * AVG)"></i></th>
                                          @endif
                                          @if(!in_array($code, ['CHN', 'AFT']))
                                              @if(in_array($code, ['CV', 'GCV']))
                                                  <th class="bg-warning bg-opacity-25">Average Result (cal/g), adb</th>
                                              @elseif($code === 'VM')
                                                  <th class="bg-warning bg-opacity-25">average %adb</th>
                                              @elseif(in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
                                                  <th class="bg-warning bg-opacity-25">average % (Adb)</th>
                                              @else
                                                  <th class="bg-warning bg-opacity-25">AVERAGE</th>
                                              @endif
                                          @endif
                                          @if(in_array($code, ['ASH','VM','TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)', 'CV','GCV']))
                                              @if(in_array($code, ['CV', 'GCV']))
                                                  <th class="bg-info bg-opacity-25">Average Result (cal/g), db</th>
                                              @elseif($code === 'VM')
                                                  <th class="bg-info bg-opacity-25">average %db</th>
                                              @elseif(in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
                                                  <th class="bg-info bg-opacity-25">average % (db)</th>
                                              @else
                                                  <th class="bg-info bg-opacity-25">%db</th>
                                              @endif
                                          @endif
                                          </tr>
                                        </thead>
                                        <tbody>
                                            <!-- SIMPLO -->
                                            <tr class="row-entry">
                                                <td class="align-middle fw-bold bg-light border-bottom-0" style="width:110px;">
                                                    <input type="text" class="form-control form-control-sm text-center fw-bold in-dish-1" name="params[{{ $pid }}][mentah][dish_1]" placeholder="D1" value="Simplo">
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

                                                @elseif($code === 'VM')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-1" name="params[{{ $pid }}][mentah][m1_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-1" name="params[{{ $pid }}][mentah][m2m1_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-1" name="params[{{ $pid }}][mentah][m3_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2m3-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" class="form-control form-control-sm in-loss-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td class="bg-warning bg-opacity-10">
                                                        <input type="text" inputmode="decimal" class="form-control form-control-sm in-im-1 fw-bold bg-transparent border-0 text-center text-warning" name="params[{{ $pid }}][mentah][im_d1]" placeholder="IM D1" disabled>
                                                    </td>
                                                    <td class="bg-warning bg-opacity-25"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>

                                                @elseif(in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-mass-1" name="params[{{ $pid }}][mentah][mass_1]" disabled></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-1 fw-bold text-primary text-center" name="params[{{ $pid }}][mentah][ts_1]" disabled></td>

                                                @elseif($code === 'CV')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-w-1" name="params[{{ $pid }}][mentah][w_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-sm-1" name="params[{{ $pid }}][mentah][sm_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-pr-1" name="params[{{ $pid }}][mentah][pr_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-ee-1" name="params[{{ $pid }}][mentah][ee_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-t-1 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-vt-1" name="params[{{ $pid }}][mentah][vt_1]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-lf-1" name="params[{{ $pid }}][mentah][lf_1]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-ts-1 bg-light border-0" name="params[{{ $pid }}][mentah][ts_1]" readonly tabindex="-1" placeholder="Auto dari TS"></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>

                                                @else
                                                    <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-1 fw-bold bg-transparent border-0 text-center text-primary" disabled></td>
                                                @endif
                                                @if(!in_array($code, ['CHN', 'AFT', 'TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)', 'CV', 'GCV']))
                                                    <td rowspan="2" class="align-middle out-diff fw-bold">-</td>
                                                    <td rowspan="2" class="align-middle out-tol fw-bold">-</td>
                                                @endif
                                                @if(!in_array($code, ['CHN', 'AFT']))
                                                    <td rowspan="2" class="align-middle out-avg-adb fw-bold text-primary fs-6">-</td>
                                                @endif
                                                @if(in_array($code, ['ASH','VM','TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)', 'CV','GCV']))
                                                    <td rowspan="2" class="align-middle out-avg-db fw-bold text-success fs-6">-</td>
                                                @endif
                                            </tr>

                                            <!-- DUPLO -->
                                            <tr class="row-entry">
                                                <td class="fw-bold text-start border-start-0">
                                                    <input type="text" class="form-control form-control-sm text-center fw-bold in-dish-2" name="params[{{ $pid }}][mentah][dish_2]" placeholder="D2" value="Duplo">
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

                                                @elseif($code === 'VM')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m1-2" name="params[{{ $pid }}][mentah][m1_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m2m1-2" name="params[{{ $pid }}][mentah][m2m1_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-m3-2" name="params[{{ $pid }}][mentah][m3_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-m2m3-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" class="form-control form-control-sm in-loss-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td class="bg-warning bg-opacity-10">
                                                        <input type="text" inputmode="decimal" class="form-control form-control-sm in-im-2 fw-bold bg-transparent border-0 text-center text-warning" name="params[{{ $pid }}][mentah][im_d2]" placeholder="IM D2" disabled>
                                                    </td>
                                                    <td class="bg-warning bg-opacity-25"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>

                                                @elseif(in_array($code, ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)']))
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-mass-2" name="params[{{ $pid }}][mentah][mass_2]" disabled></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-2 fw-bold text-primary text-center" name="params[{{ $pid }}][mentah][ts_2]" disabled></td>

                                                @elseif($code === 'CV')
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-w-2" name="params[{{ $pid }}][mentah][w_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-sm-2" name="params[{{ $pid }}][mentah][sm_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-pr-2" name="params[{{ $pid }}][mentah][pr_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-ee-2" name="params[{{ $pid }}][mentah][ee_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-t-2 bg-light border-0" readonly tabindex="-1"></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-vt-2" name="params[{{ $pid }}][mentah][vt_2]" disabled></td>
                                                    <td><input type="text" inputmode="decimal" class="form-control form-control-sm in-lf-2" name="params[{{ $pid }}][mentah][lf_2]" disabled></td>
                                                    <td><input type="text" class="form-control form-control-sm in-ts-2 bg-light border-0" name="params[{{ $pid }}][mentah][ts_2]" readonly tabindex="-1" placeholder="Auto dari TS"></td>
                                                    <td class="bg-warning bg-opacity-10"><input type="text" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" readonly tabindex="-1"></td>

                                                @else
                                                    <td class="bg-warning bg-opacity-10"><input type="text" inputmode="decimal" class="form-control form-control-sm in-hasil-2 fw-bold bg-transparent border-0 text-center text-primary" disabled></td>
                                                @endif
                                            </tr>

                                            <tr class="tr-avg d-none"></tr>
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

        {{-- TOMBOL SUBMIT (RESPONSIVE) --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3">
            
            <a href="{{ route('qc-crm.index') }}" class="btn btn-light border px-4 rounded-pill order-last order-md-first">
                Batal
            </a>
        
            <div class="d-flex flex-column flex-md-row gap-2 order-first order-md-last">
                
                <button type="submit" name="action" value="draft" class="btn btn-warning px-3 rounded-pill shadow-sm" id="btnDraft">
                    <i class="fas fa-save me-1"></i>Simpan Draft
                </button>
                
                <button type="submit" name="action" value="save_and_add" class="btn btn-outline-danger px-3 rounded-pill shadow-sm">
                    <i class="fas fa-plus-circle me-1"></i>Simpan & Tambah Botol Lain
                </button>

                <button type="submit" name="action" value="final" class="btn btn-danger px-4 rounded-pill shadow-sm" id="btnSubmit">
                    <i class="fas fa-check-circle me-1"></i>Simpan & Evaluasi
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

    function clearResource(pid) {
        const modal = document.getElementById('modalResource-' + pid);
        if (!modal) return;
        modal.querySelectorAll('input[type="checkbox"]').forEach(c => c.checked = false);
        modal.querySelectorAll('.in-peran, .in-qty').forEach(i => i.value = '');
        const empty = '<span class="text-danger fst-italic">Belum diatur</span>';
        ['personil', 'alat', 'bahan'].forEach(k => {
            const el = document.getElementById('sum-' + k + '-' + pid);
            if (el) el.innerHTML = empty;
        });
    }

    function resetSection2() {
        if (section2) section2.style.display = 'none';
        document.querySelectorAll('.param-accordion-item').forEach(item => {
            item.style.display = 'none';
        });
        document.querySelectorAll('.param-enable-check').forEach(chk => {
            chk.checked = false;
            clearResource(chk.dataset.pid);
        });
        document.querySelectorAll('.cert-badge-, [class*="cert-badge-"]').forEach(b => b.classList.add('d-none'));
        document.querySelectorAll('.btn-evaluasi').forEach(b => b.classList.add('d-none'));
    }

    function showParametersForCrm(data) {
        if (section2) section2.style.display = 'block';

        document.querySelectorAll('.param-accordion-item').forEach(item => {
            item.style.display = 'none';
        });
        document.querySelectorAll('.param-enable-check').forEach(chk => {
            chk.checked = false;
            clearResource(chk.dataset.pid);
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

                    if(window.pendingDraftToLoad) {
                        window.applyDraft(window.pendingDraftToLoad);
                        window.pendingDraftToLoad = null;
                    }

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

        if(row.closest('table').dataset.code === 'CV' || row.closest('table').dataset.code === 'GCV') {
            let tsTable = document.querySelector('table[data-code="TS"]') 
                       || document.querySelector('table[data-code="TOTAL SULFUR"]')
                       || document.querySelector('table[data-code="TOTAL SULFUR (%AD/DB)"]');
            
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
                let imVal = 0;
                const imAvgOut = imTable.querySelector('.out-avg-adb');
                if(imAvgOut && imAvgOut.textContent && imAvgOut.textContent !== '-') {
                    imVal = parseNum(imAvgOut.textContent);
                } else {
                    let v1 = parseNum(imTable.querySelector('.in-hasil-1')?.value);
                    let v2 = parseNum(imTable.querySelector('.in-hasil-2')?.value);
                    if(v1 > 0 && v2 > 0) imVal = (v1 + v2) / 2;
                    else if(v1 > 0) imVal = v1;
                    else if(v2 > 0) imVal = v2;
                }
                
                if(imVal > 0) {
                    scope['im'] = imVal;
                    const vmImInp = row.querySelector(`.in-im-${i}`);
                    if(vmImInp) vmImInp.value = imVal.toFixed(2);
                }
            }
        }

        const rowCode = row.closest('table').dataset.code;
        if(rowCode === 'IM') {
            if(scope.m1 !== undefined && scope.a !== undefined) {
                scope.m2 = scope.m1 + scope.a;
                let inM2 = row.querySelector(`.in-m2-${i}`);
                if(inM2) inM2.value = scope.m2.toFixed(4);
            }
            if(scope.m3 !== undefined && scope.m1 !== undefined) {
                scope.b = scope.m3 - scope.m1;
                let inB = row.querySelector(`.in-b-${i}`);
                if(inB) inB.value = scope.b.toFixed(4);
            }
        } else if(rowCode === 'ASH') {
            if(scope.m2m1 !== undefined && scope.m1 !== undefined) {
                scope.m2 = scope.m2m1 + scope.m1;
                let inM2 = row.querySelector(`.in-m2-${i}`);
                if(inM2) inM2.value = scope.m2.toFixed(4);
            }
            if(scope.m3 !== undefined && scope.m1 !== undefined) {
                scope.m3m1 = scope.m3 - scope.m1;
                let inM3M1 = row.querySelector(`.in-m3m1-${i}`);
                if(inM3M1) inM3M1.value = scope.m3m1.toFixed(4);
            }
        } else if(rowCode === 'VM') {
            if(scope.m1 !== undefined && scope.m2m1 !== undefined) {
                scope.m2 = scope.m1 + scope.m2m1;
                let inM2 = row.querySelector(`.in-m2-${i}`);
                if(inM2) inM2.value = scope.m2.toFixed(4);
            }
            if(scope.m2 !== undefined && scope.m3 !== undefined) {
                scope.m2m3 = scope.m2 - scope.m3;
                let inM2M3 = row.querySelector(`.in-m2m3-${i}`);
                if(inM2M3) inM2M3.value = scope.m2m3.toFixed(4);
            }
            if(scope.m2m3 !== undefined && scope.m2m1 !== undefined && scope.m2m1 > 0) {
                scope.loss = (scope.m2m3 / scope.m2m1) * 100;
                let inLoss = row.querySelector(`.in-loss-${i}`);
                if(inLoss) inLoss.value = scope.loss.toFixed(4);
            }
        } else if(rowCode === 'CV' || rowCode === 'GCV') {
            if(scope.pr !== undefined && scope.ee !== undefined && scope.ee > 0 && scope.sm !== undefined) {
                scope.t = (scope.pr / scope.ee) * scope.sm;
                let inT = row.querySelector(`.in-t-${i}`);
                if(inT) inT.value = scope.t.toFixed(4);
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
        let isTS = ['TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)'].includes(row.closest('table').dataset.code);

        if(config.rumus) {
            try {
                result = math.evaluate(config.rumus.toLowerCase(), scope);
            } catch(e) {
            }
        } else {
            const code = row.closest('table').dataset.code;
            if(code === 'IM') {
                if(scope.a > 0) result = ((scope.a - scope.b) / scope.a) * 100;
            } else if(code === 'ASH') {
                if(scope.m2m1 > 0) result = (scope.m3m1 / scope.m2m1) * 100;
            } else if(code === 'VM') {
                result = (scope.loss || 0) - (scope.im || 0);
            } else if(code === 'CV' || code === 'GCV') {
                let e1 = 14.3 * 0.0699 * (scope.vt || 0);
                let e2 = 2.3 * (scope.lf || 0);
                let e3 = 13.2 * (scope.ts || 0) * (scope.sm || 0);
                if(scope.sm > 0) {
                    result = (scope.pr - e1 - e2 - e3) / scope.sm;
                }
            }
        }

        const inHasil = row.querySelector(`.in-hasil-${i}`);
        const inD = row.querySelector(`.in-d${i}`);
        
        if (!isTS) {
            if(inHasil && !inHasil.hasAttribute('disabled')) {
                const dec = (row.closest('table').dataset.code === 'CV') ? 0 : 2;
                inHasil.value = result.toFixed(dec);
            }
            if(inD) inD.value = result.toFixed(4);
        } else {
            if(inD && inHasil) inD.value = inHasil.value;
        }

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
                let isYes = false;
                const codeT = tbody.closest('table').dataset.code;
                if (codeT === 'ASH') {
                    isYes = diff < 0.22;
                } else if (codeT === 'VM') {
                    isYes = diff < 1.0;
                } else {
                    isYes = diff < (0.09 + (0.1 * avg));
                }
                outTol.innerHTML = isYes ? `<span class="badge bg-success">YES</span>` : `<span class="badge bg-danger">NO</span>`;
            }

            const outAvg = tbody.querySelector('.out-avg-adb');
            if(outAvg) outAvg.textContent = avgStr;

            const code = tbody.closest('table').dataset.code;
            if (['ASH', 'VM', 'TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)', 'CV', 'GCV'].includes(code)) {
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

                let imAvg = 0;
                if(im1 > 0 && im2 > 0) imAvg = (im1 + im2) / 2;
                else if(im1 > 0) imAvg = im1;
                else if(im2 > 0) imAvg = im2;

                let db1 = 0, db2 = 0;
                if(imAvg > 0) {
                    if (['ASH', 'VM', 'TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)'].includes(code)) {
                        db1 = Math.round(d1 * (100 / (100 - imAvg)) * 100) / 100;
                        db2 = Math.round(d2 * (100 / (100 - imAvg)) * 100) / 100;
                    } else {
                        db1 = d1 * (100 / (100 - imAvg));
                        db2 = d2 * (100 / (100 - imAvg));
                    }
                    if(outDb1) outDb1.textContent = (['CV', 'GCV'].includes(code)) ? db1.toFixed(0) : db1.toFixed(2);
                    if(inDb1) inDb1.value = db1.toFixed(4);
                    if(outDb2) outDb2.textContent = (['CV', 'GCV'].includes(code)) ? db2.toFixed(0) : db2.toFixed(2);
                    if(inDb2) inDb2.value = db2.toFixed(4);
                }

                if(imAvg > 0) {
                    let avgDb = (db1 + db2) / 2;
                    let a = (d1 + d2) / 2;
                    
                    if (['ASH', 'VM', 'TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)', 'CV', 'GCV'].includes(code)) {
                        if (['CV', 'GCV'].includes(code)) {
                            a = Math.round(a); // CV uses rounded adb average
                            avgDb = Math.round(a * (100 / (100 - imAvg)));
                        } else if (['ASH', 'VM', 'TS', 'TOTAL SULFUR', 'TOTAL SULFUR (%AD/DB)'].includes(code)) {
                            avgDb = Math.round(a * (100 / (100 - imAvg)) * 100) / 100;
                        } else {
                            avgDb = a * (100 / (100 - imAvg));
                        }
                    }
                    
                    if(outAvgDb) outAvgDb.textContent = (['CV', 'GCV'].includes(code)) ? avgDb.toFixed(0) : avgDb.toFixed(2);
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

            // Panggil fungsi perhitungan asli
            calculateRow(row, pid, true);

            // Update Live Preview Terbaru
            setTimeout(() => {
                const table = document.getElementById('table-' + pid);
                const previewBox = document.querySelector('.preview-box-' + pid);
                if(table && previewBox) {
                    const avgAdb = table.querySelector('.out-avg-adb');
                    const avgDb = table.querySelector('.out-avg-db');
                    const simplo = table.querySelector('.in-d1');
                    const duplo = table.querySelector('.in-d2');
                    
                    let valAdb = avgAdb ? avgAdb.textContent : '-';
                    let valDb = (avgDb && avgDb.textContent) ? avgDb.textContent : '-';
                    
                    let d1 = simplo ? parseFloat(simplo.value) : 0;
                    let d2 = duplo ? parseFloat(duplo.value) : 0;
                    
                    if(d1 > 0 && d2 > 0 && !isNaN(d1) && !isNaN(d2)) {
                        let diff = Math.abs(d1 - d2);
                        
                        // Cek Toleransi dari config
                        let config = paramConfigs[pid];
                        if(config && config.toleransi) {
                            let maxDiff = 0;
                            let tolStr = config.toleransi.toString().replace(/,/g, '.');
                            if(tolStr.includes('%')) {
                                let percent = parseFloat(tolStr) / 100;
                                let avg = (d1 + d2) / 2;
                                maxDiff = avg * percent;
                            } else {
                                maxDiff = parseFloat(tolStr);
                            }
                            
                            if(diff <= maxDiff) {
                                previewBox.querySelector('.out-tol-status-' + pid).innerHTML = '<span class="text-success fw-bold"><i class="fas fa-check-circle"></i> Masuk Toleransi</span>';
                            } else {
                                previewBox.querySelector('.out-tol-status-' + pid).innerHTML = '<span class="text-danger fw-bold"><i class="fas fa-times-circle"></i> Lewat Toleransi</span>';
                            }
                        } else {
                            previewBox.querySelector('.out-tol-status-' + pid).innerHTML = '-';
                        }
                    } else {
                        previewBox.querySelector('.out-tol-status-' + pid).innerHTML = '-';
                    }
                    
                    previewBox.querySelector('.out-mean-adb-' + pid).textContent = valAdb;
                    
                    // Masukkan nilai DB (hanya jika elemen HTML-nya ada)
                    const dbEl = previewBox.querySelector('.out-mean-db-' + pid);
                    if(dbEl) dbEl.textContent = valDb;
                    
                    const crmStatusEl = previewBox.querySelector('.out-crm-status-' + pid);
                    if (crmStatusEl) {
                        let evalVal = parseFloat(valDb !== '-' ? valDb : valAdb);
                        const certData = window.crmCertificates ? window.crmCertificates[pid] : null;

                        if (!isNaN(evalVal) && certData && certData.val !== undefined && certData.u !== undefined) {
                            let v = parseFloat(certData.val);
                            let u = parseFloat(certData.u);
                            let minLimit = v - u;
                            let maxLimit = v + u;

                            if (evalVal >= minLimit && evalVal <= maxLimit) {
                                crmStatusEl.innerHTML = '<span class="badge bg-success fs-6"><i class="fas fa-check-circle"></i> INLIER</span>';
                            } else {
                                crmStatusEl.innerHTML = '<span class="badge bg-danger fs-6"><i class="fas fa-times-circle"></i> OUTLIER</span>';
                            }
                        } else {
                            crmStatusEl.innerHTML = '<span class="text-muted">-</span>';
                        }
                    }
                }
            }, 100);
        });
    });

        function updateResourceSummary(pid) {
        const modal = document.getElementById('modalResource-' + pid);
        if (!modal) return;
        const empty = '<span class="text-danger fst-italic">Belum diatur</span>';
        const bold = t => `<span class="text-dark fw-bold">${t}</span>`;

        const personils = [...modal.querySelectorAll('.chk-personil:checked')].map(cb => {
            const peran = cb.closest('tr').querySelector('.in-peran').value || 'Analis';
            return `${cb.dataset.nama} (${peran})`;
        });
        document.getElementById('sum-personil-' + pid).innerHTML = personils.length ? bold(personils.join(', ')) : empty;

        const alats = [...modal.querySelectorAll('.chk-alat:checked')].map(cb => cb.dataset.nama);
        document.getElementById('sum-alat-' + pid).innerHTML = alats.length ? bold(alats.join(', ')) : empty;

        const bahans = [...modal.querySelectorAll('.chk-bahan:checked')].map(cb => {
            const qty = cb.closest('tr').querySelector('.in-qty').value || 0;
            return `${cb.dataset.nama} (${qty})`;
        });
        document.getElementById('sum-bahan-' + pid).innerHTML = bahans.length ? bold(bahans.join(', ')) : empty;
    }

    document.querySelectorAll('.modal-resource').forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function () {
            updateResourceSummary(this.id.replace('modalResource-', ''));
        });
    });

    // Isi qty otomatis mencentang bahan
    document.querySelectorAll('.modal-resource .in-qty').forEach(inp => {
        inp.addEventListener('input', function () {
            this.closest('tr').querySelector('.chk-bahan').checked = parseFloat(this.value) > 0;
        });
    });

    // Salin dari parameter sebelumnya (yang terlihat)
    document.querySelectorAll('.btn-copy-resource').forEach(btn => {
        btn.addEventListener('click', function () {
            const cur = this.dataset.pid;
            const items = [...document.querySelectorAll('.param-accordion-item')].filter(i => i.style.display !== 'none');
            const idx = items.findIndex(i => i.dataset.pid === cur);
            if (idx <= 0) { alert('Ini parameter pertama, tidak ada data sebelumnya untuk disalin.'); return; }
            const prev = items[idx - 1].dataset.pid;
            const pm = document.getElementById('modalResource-' + prev);
            const cm = document.getElementById('modalResource-' + cur);

            cm.querySelectorAll('input[type="checkbox"]').forEach(c => c.checked = false);
            cm.querySelectorAll('.in-peran, .in-qty').forEach(i => i.value = '');

            pm.querySelectorAll('.chk-personil:checked').forEach(cb => {
                const t = cm.querySelector(`.chk-personil[value="${cb.value}"]`);
                if (t) { t.checked = true; t.closest('tr').querySelector('.in-peran').value = cb.closest('tr').querySelector('.in-peran').value; }
            });
            pm.querySelectorAll('.chk-alat:checked').forEach(cb => {
                const t = cm.querySelector(`.chk-alat[value="${cb.value}"]`); if (t) t.checked = true;
            });
            pm.querySelectorAll('.chk-bahan:checked').forEach(cb => {
                const t = cm.querySelector(`.chk-bahan[value="${cb.value}"]`);
                if (t) { t.checked = true; t.closest('tr').querySelector('.in-qty').value = cb.closest('tr').querySelector('.in-qty').value; }
            });
            updateResourceSummary(cur);
            alert('Berhasil menyalin Analis, Alat, dan Bahan dari parameter sebelumnya!');
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

            const collapseEl = document.getElementById('collapse-' + pid);
            if (collapseEl) {
                const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false });
                if (this.checked) {
                    bsCollapse.show();
                } else {
                    bsCollapse.hide();
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
            localStorage.removeItem('qc_crm_draft');
        });
    }

        // --- FUNGSI INI KEMBALI KITA PASANG KARENA SEMPAT IKUT TERHAPUS ---
    window.applyDraft = function(draft) {
        if(draft.tanggal_uji) {
            const tanggal = document.querySelector('input[name="tanggal_uji"]');
            if(tanggal) tanggal.value = draft.tanggal_uji;
        }
        if(draft.analis_id) {
            const analis = document.querySelector('select[name="analis_id"]');
            if(analis) analis.value = draft.analis_id;
        }

        Object.keys(draft.params).forEach(pid => {
            const paramDraft = draft.params[pid];
            const check = document.querySelector(`.param-enable-check[data-pid="${pid}"]`);
            const table = document.getElementById('table-' + pid);
            
            if(!check || !table) return;

            if(paramDraft.selected) {
                // Centang otomatis dan munculkan form
                check.checked = true;
                check.dispatchEvent(new Event('change'));

                if (paramDraft.resource) {
                    const r = paramDraft.resource;
                    const modal = document.getElementById('modalResource-' + pid);
                    if (modal) {
                        (r.personil_ids || []).forEach(id => { const c = modal.querySelector(`.chk-personil[value="${id}"]`); if (c) c.checked = true; });
                        Object.entries(r.personil_peran || {}).forEach(([id, v]) => { const i = modal.querySelector(`input[name="params[${pid}][personil_peran][${id}]"]`); if (i) i.value = v; });
                        (r.alat_ids || []).forEach(id => { const c = modal.querySelector(`.chk-alat[value="${id}"]`); if (c) c.checked = true; });
                        (r.barang_ids || []).forEach(id => { const c = modal.querySelector(`.chk-bahan[value="${id}"]`); if (c) c.checked = true; });
                        Object.entries(r.barang_jumlah || {}).forEach(([id, v]) => { const i = modal.querySelector(`input[name="params[${pid}][barang_jumlah][${id}]"]`); if (i) i.value = v; });
                        updateResourceSummary(pid);
                    }
                }

                // Isikan nilai-nilai yang sudah pernah diketik
                if(paramDraft.inputs) {
                    Object.keys(paramDraft.inputs).forEach(className => {
                        const inp = table.querySelector('.' + className);
                        if(inp && paramDraft.inputs[className] !== null) {
                            inp.value = paramDraft.inputs[className];

                            Object.keys(paramDraft.inputs).forEach(className => {
                                const inp = table.closest('.accordion-body').querySelector('.' + className); // <-- Ubah pencarian elemennya sedikit
                                if(inp && paramDraft.inputs[className] !== null) {
                                    inp.value = paramDraft.inputs[className];
                                }
                            });
                        }
                    });

                    // Picu perhitungan otomatis agar angkanya ter-refresh
                    const rows = table.querySelectorAll('.row-entry');
                    rows.forEach(row => {
                        calculateRow(row, pid);
                    });
                }
            }
        });
    };

        // LOAD DRAFT DARI SERVER
    (function loadDraft() {
        @if(isset($serverDraft) && $serverDraft)
            let draft = {!! json_encode($serverDraft) !!};
            if(draft.crm_katalog_id) {
                const crm = document.querySelector('select[name="crm_katalog_id"]');
                if(crm) {
                    window.pendingDraftToLoad = draft; // Kirim ke fungsi AJAX yang sudah ada
                    crm.value = draft.crm_katalog_id;
                    crm.dispatchEvent(new Event('change'));
                }
            }
        @endif
    })();
});
</script>

@endsection
