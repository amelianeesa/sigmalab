@extends('layouts.app')
@section('title', 'Tambah Baru - QC Uji Banding')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="Uji Banding">
        <li class="breadcrumb-item active">Input Data</li>
    </x-qc-breadcrumb>
    <h2 class="mb-4 fw-bold text-dark">
        <i class="fas fa-plus-circle text-danger me-2"></i>Input Data Blind Test Uji Banding
    </h2>

    @if(session('error'))
        <div class="alert alert-danger shadow-sm border-0">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('qc-uji-banding.store') }}" method="POST" id="formQc">
        @csrf
        <input type="hidden" name="draft_id" id="draft_id_input" value="{{ $draftId ?? '' }}">

        
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-clipboard-list me-2"></i>Section 1: Data Dasar Pengujian</h5>
            </div>
            <div class="card-body p-4">

                {{-- Informasi Umum Program --}}
                <h6 class="mb-3 text-primary border-bottom pb-2"><i class="fas fa-info-circle me-2"></i>Informasi Umum Program Uji Banding</h6>
                <div class="row mb-4 g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nama Program Uji Banding <span class="text-danger">*</span></label>
                        <input type="text" name="nama_program" class="form-control" placeholder="Contoh: Proficiency Testing D-QA 2026" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Penyelenggara (Vendor) <span class="text-danger">*</span></label>
                        <input type="text" name="penyelenggara" class="form-control" placeholder="Contoh: D-QA / FAPAS" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kode Sampel <span class="text-danger">*</span></label>
                        <input type="text" name="kode_sampel" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tanggal Terima <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_terima" class="form-control" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="2"></textarea>
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
                                            <input class="form-check-input" type="checkbox" name="barang_ids[]" value="{{ $barang->barang_id }}" id="barang_{{ $barang->barang_id }}" {{ $habis ? 'disabled' : '' }}>
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
                                                <input type="number" step="0.01" min="0" class="form-control barang-input" name="barang_jumlah[{{ $barang->barang_id }}]" placeholder="0" {{ $habis ? 'disabled' : '' }}>
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

        {{-- SECTION 2: INPUT DATA PENGUJIAN --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-danger text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-table me-2"></i>Section 2: Input Data Pengujian</h5>
            </div>
            <div class="card-body p-0">
                {{-- NAV TABS: 4 Sheet --}}
                <ul class="nav nav-tabs px-3 pt-3" id="section2Tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold" id="tab-input-data-tab" data-bs-toggle="tab" data-bs-target="#tab-input-data" type="button" role="tab">
                            <i class="fas fa-edit me-1"></i> Input data
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="tab-proximate-tab" data-bs-toggle="tab" data-bs-target="#tab-proximate" type="button" role="tab">
                            <i class="fas fa-chart-bar me-1"></i> Proximate
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="tab-cetak-form-tab" data-bs-toggle="tab" data-bs-target="#tab-cetak-form" type="button" role="tab">
                            <i class="fas fa-print me-1"></i> Cetak form
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="tab-aft-correction-tab" data-bs-toggle="tab" data-bs-target="#tab-aft-correction" type="button" role="tab">
                            <i class="fas fa-thermometer-half me-1"></i> AFT correction
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="section2TabContent">
                    {{-- TAB PANE 1: INPUT DATA --}}
                    <div class="tab-pane fade show active" id="tab-input-data" role="tabpanel">
                        <div class="alert alert-info m-3 rounded-0 border-start border-4 border-info">
                            <i class="fas fa-info-circle me-2"></i> Centang kotak di samping nama parameter untuk mengaktifkan form inputnya.
                        </div>

                        @php
                            $groupedParameters = $allParameters->groupBy(function($item) {
                            return $item->kategori_parameter ?: 'Lain-lain';
                            });
                        @endphp

                        <div class="accordion accordion-flush" id="moduleAccordion">
                            @foreach($groupedParameters as $kategori => $params)
                                @if(!in_array($kategori, [
                                    'Proximate Analysis', 
                                    'Determination of Sulfur by IR Spectrometry', 
                                    'Determination of Gross Calorific Value', 
                                    'Calorific Value & Sulfur', 
                                    'Residual Moisture', 
                                    'Determination of Total Moisture', 
                                    'Determination of Carbon, Hydrogen, Nitrogen by Instrument', 
                                    'Ultimate Analysis',
                                    'Determination of Net Calorific Value'])) @continue 
                                @endif
                                @php
                                    if ($kategori === 'Proximate Analysis') {
                                        $order = ['IM' => 1, 'ASH' => 2, 'VM' => 3, 'FC' => 4];
                                        $params = $params->sortBy(function($p) use ($order) {
                                            $code = strtoupper(str_replace(' - %', '', $p->nama_parameter));
                                            return $order[$code] ?? 99;
                                        })->values();
                                    }
                                    $catId = Str::slug($kategori);
                                @endphp

                                <div class="accordion-item border-bottom mb-2">
                                    <h2 class="accordion-header" id="heading-cat-{{ $catId }}">
                                        <div class="d-flex align-items-center w-100 bg-light">
                                            <div class="p-3">
                                                <input class="form-check-input cat-enable-check" type="checkbox" data-cat="{{ $catId }}" style="transform: scale(1.5);">
                                            </div>
                                            <button class="accordion-button collapsed py-3 fw-bold bg-transparent text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-cat-{{ $catId }}" aria-expanded="false">
                                                <i class="fas fa-cubes me-2"></i> Modul: {{ $kategori }}
                                            </button>
                                        </div>
                                    </h2>

                                    <div id="collapse-cat-{{ $catId }}" class="accordion-collapse collapse" aria-labelledby="heading-cat-{{ $catId }}" data-bs-parent="#moduleAccordion">
                                        <div class="accordion-body p-4 bg-white">

                                            @if($kategori === 'Determination of Carbon, Hydrogen, Nitrogen by Instrument')
                                                @php
                                                    $pid_c = $params->where('nama_parameter', 'C')->first()->parameter_uji_id ?? 4;
                                                    $pid_h = $params->where('nama_parameter', 'H')->first()->parameter_uji_id ?? 5;
                                                    $pid_n = $params->where('nama_parameter', 'N')->first()->parameter_uji_id ?? 6;
                                                    $pid   = $pid_c; // Default ke C
                                                @endphp
                                                <div class="col-12 param-container" data-pid="{{ $pid }}">
                                                    <div class="d-flex align-items-center mb-3 border-bottom pb-2">
                                                        <input class="form-check-input param-enable-check me-2" type="checkbox" name="params[{{ $pid }}][selected]" value="1" data-pid="{{ $pid }}" data-cat="{{ $catId }}" style="transform: scale(1.3);">
                                                        <h5 class="fw-bold text-dark mb-0 ms-2 text-primary">Aktifkan Pengujian CHN</h5>
                                                    </div>
                                                    
                                                    <div class="param-form-wrapper" style="opacity: 0.5; pointer-events: none;">
                                                        <!-- ANALIS & ALAT (CHN) -->
                                                        <div class="row g-3 bg-light p-3 rounded mb-3 border border-info align-items-center">
                                                            <div class="col-md-6">
                                                                <label class="form-label small fw-bold mb-1">Analis / Personil</label>
                                                                <select name="params[{{ $pid }}][analis_id]" class="form-select form-select-sm param-input-ext" disabled>
                                                                    <option value="">-- Pilih Analis --</option>
                                                                    @foreach($personilList as $personil)
                                                                        <option value="{{ $personil->personil_id }}">{{ $personil->nama }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label small fw-bold mb-1">Instrumen/Alat</label>
                                                                <select name="params[{{ $pid }}][alat_id]" class="form-select form-select-sm param-input-ext" disabled>
                                                                    <option value="">-- Pilih Alat --</option>
                                                                    <option value="1">CLC1204-10001 - Sulfur Analyzer</option>
                                                                    <option value="2">CLC1206-10001 - Calorimeter</option>
                                                                    <option value="3">CLC1156-10002 - MFS/1 ASTM Oven</option>
                                                                    <option value="4">CLC3208-10001 - MFS/1 ASTM Oven</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        @include('qc-uji-banding.partials.chn_table')

                                                        @php
                                                            $chnToleransis = \App\Models\ParameterToleransi::whereIn('parameter_uji_id', [$pid_c, $pid_h, $pid_n])->get();
                                                        @endphp

                                                        @if($chnToleransis->count() > 0)
                                                            <input type="hidden" id="tol-data-{{ $pid_c }}" value='{{ $chnToleransis->toJson() }}'>
                                                            <input type="hidden" id="tol-data-{{ $pid_h }}" value='{{ $chnToleransis->toJson() }}'>
                                                            <input type="hidden" id="tol-data-{{ $pid_n }}" value='{{ $chnToleransis->toJson() }}'>
                                                            <div class="mt-3 p-3 bg-light border rounded">
                                                                <h6 class="fw-bold text-secondary mb-2" style="font-size: 0.85rem;">
                                                                    <i class="fas fa-info-circle me-1"></i> Referensi Batas Toleransi Master CHN
                                                                </h6>
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-sm text-center align-middle mb-0 bg-white" style="font-size: 0.8rem;">
                                                                        <thead class="table-secondary">
                                                                            <tr>
                                                                                <th>Std Method</th>
                                                                                <th>Element</th>
                                                                                <th>Range</th>
                                                                                <th class="text-danger">Repeatability (r)</th>
                                                                                <th class="text-success">Reproducibility (R)</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($chnToleransis as $tol)
                                                                                <tr>
                                                                                    <td>{{ $tol->metode ?? '-' }}</td>
                                                                                    <td class="fw-bold text-primary">{{ $tol->sub_parameter ?? '-' }}</td>
                                                                                    <td>{{ $tol->range_label ?? '-' }}</td>
                                                                                    <td class="text-danger fw-bold">{{ $tol->formula_r }}</td>
                                                                                    <td class="text-success fw-bold">{{ $tol->formula_R_besar }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        
                                                    </div>
                                                </div>

                                            @else
                                                <ul class="nav nav-tabs mb-4" id="tabs-cat-{{ $catId }}" role="tablist">
                                                    @foreach($params as $index => $param)
                                                        @php
                                                            $pid = $param->parameter_uji_id;
                                                            $code = strtoupper(str_replace(' - %', '', $param->nama_parameter));
                                                        @endphp
                                                        <li class="nav-item" role="presentation">
                                                            <button class="nav-link fw-bold {{ $index === 0 ? 'active' : '' }}" id="tab-{{ $pid }}" data-bs-toggle="tab" data-bs-target="#pane-{{ $pid }}" type="button" role="tab">
                                                                {{ $code }}
                                                            </button>
                                                        </li>
                                                    @endforeach
                                                </ul>

                                                <div class="tab-content" id="content-cat-{{ $catId }}">
                                                    @foreach($params as $index => $param)
                                                        @php
                                                            $code = strtoupper(str_replace(' - %', '', $param->nama_parameter));
                                                            $pid = $param->parameter_uji_id;
                                                        @endphp
                                                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="pane-{{ $pid }}" role="tabpanel">
                                                            <div class="col-12 param-container" data-pid="{{ $pid }}">
                                                                <div class="d-flex align-items-center mb-3 border-bottom pb-2">
                                                                    <input class="form-check-input param-enable-check me-2" type="checkbox" name="params[{{ $pid }}][selected]" value="1" data-pid="{{ $pid }}" data-cat="{{ $catId }}" style="transform: scale(1.3);">
                                                                    <h5 class="fw-bold text-dark mb-0 ms-2 text-primary">Aktifkan Pengujian {{ $code }} <span class="badge bg-secondary ms-2">{{ $param->satuan }}</span></h5>
                                                                </div>
                                                                <div class="param-form-wrapper" style="opacity: 0.5; pointer-events: none;">
                                                                    <!-- ANALIS & ALAT (UMUM) -->
                                                                    <div class="row g-3 bg-light p-3 rounded mb-3 border border-info align-items-center">
                                                                        <div class="col-md-6">
                                                                            <label class="form-label small fw-bold mb-1">Analis / Personil</label>
                                                                            <select name="params[{{ $pid }}][analis_id]" class="form-select form-select-sm param-input-ext" disabled>
                                                                                <option value="">-- Pilih Analis --</option>
                                                                                @foreach($personilList as $personil)
                                                                                    <option value="{{ $personil->personil_id }}">{{ $personil->nama }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label small fw-bold mb-1">Instrumen/Alat</label>
                                                                            <select name="params[{{ $pid }}][alat_id]" class="form-select form-select-sm param-input-ext" disabled>
                                                                                <option value="">-- Pilih Alat --</option>
                                                                                <option value="1">CLC1204-10001 - Sulfur Analyzer</option>
                                                                                <option value="2">CLC1206-10001 - Calorimeter</option>
                                                                                <option value="3">CLC1156-10002 - MFS/1 ASTM Oven</option>
                                                                                <option value="4">CLC3208-10001 - MFS/1 ASTM Oven</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    @if(in_array($code, ['IM', 'ASH', 'VM', 'FC', 'RM']))
                                                                        @include('qc-uji-banding.partials.proximate_table')
                                                                    @elseif($kategori === 'Determination of Sulfur by IR Spectrometry')
                                                                        @include('qc-uji-banding.partials.sulfur_table')
                                                                    @elseif($kategori === 'Determination of Gross Calorific Value' || $kategori === 'Calorific Value & Sulfur' || $code === 'GCV' || $code === 'CV')
                                                                        @include('qc-uji-banding.partials.gcv_table')
                                                                    @elseif($kategori === 'Determination of Total Moisture')
                                                                        @include('qc-uji-banding.partials.total_moisture_table')
                                                                    @elseif($kategori === 'Determination of Net Calorific Value' || $code === 'NCV')
                                                                        @include('qc-uji-banding.partials.ncv_table')
                                                                    @else
                                                                        @include('qc-uji-banding.partials.generic_table')
                                                                    @endif

                                                                    @php
                                                                        $toleransiList = \App\Models\ParameterToleransi::where('parameter_uji_id', $pid)->get();
                                                                    @endphp

                                                                    @if($toleransiList->count() > 0)
                                                                        <input type="hidden" id="tol-data-{{ $pid }}" value='{{ $toleransiList->toJson() }}'>
                                                                        <div class="mt-3 p-3 bg-light border rounded">
                                                                            <h6 class="fw-bold text-secondary mb-2" style="font-size: 0.85rem;">
                                                                                <i class="fas fa-info-circle me-1"></i> Referensi Batas Toleransi Master (ASTM / ISO)
                                                                            </h6>
                                                                            <div class="table-responsive">
                                                                                <table class="table table-bordered table-sm text-center align-middle mb-0 bg-white" style="font-size: 0.8rem;">
                                                                                    <thead class="table-secondary">
                                                                                        <tr>
                                                                                            @if(in_array($code, ['IM', 'TM', 'RM']))
                                                                                                <th>Material / Kategori</th>
                                                                                            @elseif(in_array($code, ['ASH', 'TS', 'TOTAL SULFUR (%AD/DB)']))
                                                                                                <th>Std Method</th>
                                                                                            @elseif($code == 'VM')
                                                                                                <th>Std Method</th>
                                                                                                <th>Type Sample</th>
                                                                                            @else
                                                                                                <th>Std Method</th>
                                                                                                <th>Material / Kategori</th>
                                                                                            @endif

                                                                                            @if($code !== 'VM')
                                                                                                <th>Range</th>
                                                                                            @endif
                                                                                            <th class="text-danger">Repeatability (r)</th>
                                                                                            <th class="text-success">Reproducibility (R)</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @foreach($toleransiList as $tol)
                                                                                            <tr>
                                                                                                @if(in_array($code, ['IM', 'TM', 'RM']))
                                                                                                    <td class="fw-bold">{{ $tol->kategori_label ?? '-' }}</td>
                                                                                                @elseif(in_array($code, ['ASH', 'TS', 'TOTAL SULFUR (%AD/DB)']))
                                                                                                    <td class="fw-bold">{{ $tol->metode ?? '-' }}</td>
                                                                                                @elseif($code == 'VM')
                                                                                                    <td>{{ $tol->metode ?? '-' }}</td>
                                                                                                    <td class="fw-bold text-primary">{{ $tol->kategori_label ?? '-' }}</td>
                                                                                                @else
                                                                                                    <td>{{ $tol->metode ?? '-' }}</td>
                                                                                                    <td class="fw-bold">{{ $tol->kategori_label ?? '-' }}</td>
                                                                                                @endif

                                                                                                @if($code !== 'VM')
                                                                                                    <td>{{ $tol->range_label ?? '-' }}</td>
                                                                                                @endif

                                                                                                <td class="text-danger fw-bold">{{ $tol->formula_r }}</td>
                                                                                                <td class="text-success fw-bold">{{ $tol->formula_R_besar }}</td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif  
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>{{-- /tab-pane input-data --}}

                    {{-- TAB PANE 2: PROXIMATE --}}
                    <div class="tab-pane fade" id="tab-proximate" role="tabpanel">
                        @include('qc-uji-banding.partials.proximate_sheet')
                    </div>

                    {{-- TAB PANE 3: CETAK FORM --}}
                    <div class="tab-pane fade" id="tab-cetak-form" role="tabpanel">
                        @include('qc-uji-banding.partials.cetak_form')
                    </div>

                    {{-- TAB PANE 4: AFT CORRECTION --}}
                    <div class="tab-pane fade" id="tab-aft-correction" role="tabpanel">
                        @include('qc-uji-banding.partials.aft_correction')
                    </div>
                </div>{{-- /tab-content --}}
            </div>{{-- /card-body --}}
        </div>{{-- /card Section 2 --}}

        {{-- TOMBOL SUBMIT & DRAFT --}}
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('qc-uji-banding.index') }}" class="btn btn-light border px-4">Batal</a>
            <div>
                <button type="button" class="btn btn-warning px-4 rounded-pill shadow-sm me-2" id="btnDraft">
                    <i class="fas fa-save me-2"></i>Simpan Draft
                </button>
                <button type="submit" class="btn btn-danger px-5 rounded-pill shadow-sm" id="btnSubmit">
                    <i class="fas fa-check-circle me-2"></i>Simpan Data Uji Banding
                </button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.8.0/math.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function parseNum(val) {
        const n = parseFloat(val);
        return isNaN(n) ? 0 : n;

    };
    
    window.updateYesNoColor = function(selectEl) {
        if (selectEl.value === 'YES') {
            selectEl.className = 'form-select form-select-sm fw-bold bg-success text-white border-success';
        } else if (selectEl.value === 'NO') {
            selectEl.className = 'form-select form-select-sm fw-bold bg-danger text-white border-danger';
        } else {
            selectEl.className = 'form-select form-select-sm fw-bold'; // Kembali normal
        }
    }

    function runToleranceValidation(container, pid, code) {
        // 1. Deteksi kelas out-diff atau out-abs (untuk Total Moisture)
        let diffNode = container.querySelector('.out-diff') || container.querySelector('.out-abs');
        let diffText = diffNode?.textContent;
        
        let avgText = '-';
        if (code === 'TM' || code === 'TOTAL MOISTURE') {
            avgText = container.querySelector('.out-avg-ar')?.textContent;
        } else if (code === 'CHN') {
            avgText = container.querySelector('.out-avg-txt')?.textContent;
        } else {
            avgText = container.querySelector('.out-avg-adb')?.textContent;
        }

        let selectYesNo = container.querySelector('select[name$="[yesno]"]');
        if(!selectYesNo) return; 

        if(!diffText || diffText === '-' || !avgText || avgText === '-') {
            selectYesNo.value = '';
            updateYesNoColor(selectYesNo);
            return;
        }

        let diff = parseFloat(diffText);
        let avg = parseFloat(avgText);

        let tolInput = document.getElementById('tol-data-' + pid);
        if(!tolInput) return;

        let tolData = [];
        try { tolData = JSON.parse(tolInput.value); } catch(e) { return; }

        let matchedRule = null;
        let rowType = container.dataset.type; // Untuk CHN ('carbon', 'hydrogen', 'nitrogen')

        for(let rule of tolData) {
            if (code === 'CHN') {
                let targetSub = '';
                if (rowType === 'carbon') targetSub = 'C';
                else if (rowType === 'hydrogen') targetSub = 'H';
                else if (rowType === 'nitrogen') targetSub = 'N';

                let sub = (rule.sub_parameter || '').toUpperCase();
                // Dibuat fleksibel agar bisa membaca 'C', 'CARBON', 'H', 'HYDROGEN', dst.
                if (sub === targetSub || sub.startsWith(targetSub) || sub.includes(targetSub)) {
                    let min = (rule.range_min !== null && rule.range_min !== '') ? parseFloat(rule.range_min) : -Infinity;
                    let max = (rule.range_max !== null && rule.range_max !== '') ? parseFloat(rule.range_max) : Infinity;
                    if (avg >= min && avg <= max) { matchedRule = rule; break; }
                }
            } else {
                if (code !== 'VM') {
                    let min = (rule.range_min !== null && rule.range_min !== '') ? parseFloat(rule.range_min) : -Infinity;
                    let max = (rule.range_max !== null && rule.range_max !== '') ? parseFloat(rule.range_max) : Infinity;
                    if (avg >= min && avg <= max) { matchedRule = rule; break; }
                } else {
                    matchedRule = rule; break;
                }
            }
        }

        if(!matchedRule || !matchedRule.formula_r) {
             selectYesNo.value = '';
             updateYesNoColor(selectYesNo);
             return;
        }

        let formula = matchedRule.formula_r;
        formula = formula.replace(/(\d)\s*[Xu]/gi, '$1 * X'); 
        let evalFormula = formula.replace(/X/gi, avg).replace(/u/gi, avg);
        let limitR = 0;
        
        try { limitR = math.evaluate(evalFormula); } 
        catch(e) { limitR = parseFloat(formula); }

        selectYesNo.value = (diff <= limitR) ? 'YES' : 'NO';
        updateYesNoColor(selectYesNo);
    }

    // Kalkulasi per baris khusus Uji Banding
    function calculateRow(row, pid) {
        if(!row) return;
        let i = row.querySelector('.in-hasil-1') ? 1 : 2;
        let scope = {};
        const table = row.closest('table');
        if(!table) return;
        const code = table.dataset.code;

        // Ambil semua input
        const inputs = row.querySelectorAll('input[class*="in-"]');
        inputs.forEach(inp => {
            const match = inp.className.match(/in-([a-zA-Z0-9_]+)-\d/);
            if(match) {
                const varName = match[1].toLowerCase();
                scope[varName] = parseNum(inp.value);
            }
        });

        const tbody = row.closest('tbody');
        if(!tbody) return;

        let result = 0;

        // Hitung otomatis untuk IM
        if (code === 'IM' || code === 'RM') {
            if (scope.m1 > 0 && scope.a > 0) {
                scope.m2 = scope.m1 + scope.a;
                let m2Inp = row.querySelector(`.in-m2-${i}`);
                if (m2Inp) m2Inp.value = scope.m2.toFixed(4);
            }
            if (scope.m1 > 0 && scope.b > 0) {
                scope.m3 = scope.m1 + scope.b;
                let m3Inp = row.querySelector(`.in-m3-${i}`);
                if (m3Inp) m3Inp.value = scope.m3.toFixed(4);
            }
            if (scope.a > 0) result = ((scope.a - scope.b) / scope.a) * 100;
        }
        // Hitung otomatis untuk ASH
        else if (code === 'ASH') {
            if (scope.m1 > 0 && scope.m2m1 > 0) {
                let m2 = scope.m1 + scope.m2m1;
                let m2Inp = row.querySelector(`.in-m2-${i}`);
                if (m2Inp) m2Inp.value = m2.toFixed(4);
            }
            if (scope.m1 > 0 && scope.m3m1 > 0) {
                let m3 = scope.m1 + scope.m3m1;
                let m3Inp = row.querySelector(`.in-m3-${i}`);
                if (m3Inp) m3Inp.value = m3.toFixed(4);
            }
            if (scope.m2m1 > 0) result = (scope.m3m1 / scope.m2m1) * 100;
        }
        // Hitung otomatis untuk VM
        else if (code === 'VM') {
            let vmInp = parseNum(row.querySelector('.in-hasil-' + i)?.value); // %VM input

            if (scope.m1 > 0 && scope.m2m1 > 0) {
                // M2 = M1 + (M2-M1)
                let m2 = scope.m1 + scope.m2m1;
                let m2Inp = row.querySelector('.in-m2-' + i);
                if (m2Inp) m2Inp.value = m2.toFixed(4);
            }

            // Get %Mad from IM table
            let imValue = 0;
            const imTable = document.querySelector('table[data-code="IM"]');
            if (imTable) {
                const targetTbody = imTable.querySelectorAll('tbody')[tbody.dataset.index || 0];
                if (targetTbody) {
                    imValue = parseNum(targetTbody.querySelector('.out-avg-adb')?.textContent);
                }
            }

            let imInp = row.querySelector('.in-im-' + i);
            if (imInp) imInp.value = imValue > 0 ? imValue.toFixed(4) : '';

            if (vmInp > 0 && imValue > 0 && scope.m2m1 > 0) {
                // %LOSS = %VM + %Mad
                let loss = vmInp + imValue;
                let ml = row.querySelector('.in-loss-' + i);
                if (ml) ml.value = loss.toFixed(4);

                // M2-M3 = (%LOSS * (M2-M1)) / 100
                let m2m3 = (loss * scope.m2m1) / 100;
                let m2m3Inp = row.querySelector('.in-m2m3-' + i);
                if (m2m3Inp) m2m3Inp.value = m2m3.toFixed(4);

                // M3 = M2 - (M2-M3)
                if (scope.m1 > 0) {
                    let m2 = scope.m1 + scope.m2m1;
                    let m3 = m2 - m2m3;
                    let m3Inp = row.querySelector('.in-m3-' + i);
                    if (m3Inp) m3Inp.value = m3.toFixed(4);
                }
            }
        }
        else if (code === 'FC') {
            const myTbodyIndex = tbody.dataset.index || 0;

            let im1 = 0, im2 = 0;
            const imTable = document.querySelector('table[data-code="IM"]');
            if(imTable) {
                const targetTbody = imTable.querySelectorAll('.proximate-tbody')[myTbodyIndex];
                if(targetTbody) {
                    im1 = parseNum(targetTbody.querySelector('.in-hasil-1')?.value);
                    im2 = parseNum(targetTbody.querySelector('.in-hasil-2')?.value);
                }
            }

            let ash1 = 0, ash2 = 0;
            const ashTable = document.querySelector('table[data-code="ASH"]');
            if(ashTable) {
                const targetTbody = ashTable.querySelectorAll('.proximate-tbody')[myTbodyIndex];
                if(targetTbody) {
                    ash1 = parseNum(targetTbody.querySelector('.in-hasil-1')?.value);
                    ash2 = parseNum(targetTbody.querySelector('.in-hasil-2')?.value);
                }
            }

            let vm1 = 0, vm2 = 0;
            const vmTable = document.querySelector('table[data-code="VM"]');
            if(vmTable) {
                const targetTbody = vmTable.querySelectorAll('.proximate-tbody')[myTbodyIndex];
                if(targetTbody) {
                    vm1 = parseNum(targetTbody.querySelector('.in-hasil-1')?.value);
                    vm2 = parseNum(targetTbody.querySelector('.in-hasil-2')?.value);
                }
            }

            if (i === 1) {
                let inIm1 = tbody.querySelector('.in-im-1');
                let inAsh1 = tbody.querySelector('.in-ash-1');
                let inVm1 = tbody.querySelector('.in-vm-1');
                if(inIm1) inIm1.value = im1 > 0 ? im1.toFixed(4) : '';
                if(inAsh1) inAsh1.value = ash1 > 0 ? ash1.toFixed(4) : '';
                if(inVm1) inVm1.value = vm1 > 0 ? vm1.toFixed(4) : '';

                if (im1 > 0 && ash1 > 0 && vm1 > 0) result = 100 - im1 - ash1 - vm1;
            } else {
                let inIm2 = tbody.querySelector('.in-im-2');
                let inAsh2 = tbody.querySelector('.in-ash-2');
                let inVm2 = tbody.querySelector('.in-vm-2');
                if(inIm2) inIm2.value = im2 > 0 ? im2.toFixed(4) : '';
                if(inAsh2) inAsh2.value = ash2 > 0 ? ash2.toFixed(4) : '';
                if(inVm2) inVm2.value = vm2 > 0 ? vm2.toFixed(4) : '';

                if (im2 > 0 && ash2 > 0 && vm2 > 0) result = 100 - im2 - ash2 - vm2;
            }
        }
        else if (code === 'TS') {
            result = scope.mass || 0;
        }
        // --- TAMBAHKAN BLOK KODE TM DI SINI ---
        else if (code === 'TM' || code === 'TOTAL MOISTURE') {
            let adl1Inp = row.querySelector('.in-adl1');
            let adl2Inp = row.querySelector('.in-adl2');
            let rmInp = row.querySelector('.in-rm');
            let tmPrimeInp = row.querySelector('.in-tm-prime');

            let adl1 = parseNum(adl1Inp?.value);
            let adl2 = parseNum(adl2Inp?.value);
            let rm = parseNum(rmInp?.value);

            let tmPrime = 0;
            // Hitung TM' jika ADL2 dan RM ada (atau diisi 0 jika memang nilainya 0)
            if (rmInp?.value !== '' && adl2Inp?.value !== '') {
                tmPrime = (rm * (100 - adl2) / 100) + adl2;
                if (tmPrimeInp) tmPrimeInp.value = tmPrime.toFixed(2);
            } else {
                if (tmPrimeInp) tmPrimeInp.value = '';
            }

            let finalResult = 0;
            if (tmPrime > 0) {
                if (adl1Inp?.value !== '') {
                    // Skenario 2: Dengan ADL 1
                    finalResult = (tmPrime * (100 - adl1) / 100) + adl1;
                } else {
                    // Skenario 1: Tanpa ADL 1
                    finalResult = tmPrime;
                }
            }

            let outTm = row.querySelector('.out-tm');
            if (outTm) {
                outTm.textContent = finalResult > 0 ? finalResult.toFixed(2) : '-';
                outTm.dataset.val = finalResult; // Simpan nilai asli di belakang layar untuk rata-rata
            }

            // Hitung Absolute Difference dan Average secara instan
            const tbody = row.closest('tbody');
            if (tbody) {
                const outTmSimplo = tbody.querySelector('tr[data-type="simplo"] .out-tm');
                const outTmDuplo = tbody.querySelector('tr[data-type="duplo"] .out-tm');

                let sVal = outTmSimplo && outTmSimplo.dataset.val ? parseFloat(outTmSimplo.dataset.val) : 0;
                let dVal = outTmDuplo && outTmDuplo.dataset.val ? parseFloat(outTmDuplo.dataset.val) : 0;

                let outAbs = tbody.querySelector('.out-abs');
                let outAvg = tbody.querySelector('.out-avg-ar');

                if (sVal > 0 && dVal > 0) {
                    let diff = Math.abs(sVal - dVal);
                    let avg = (sVal + dVal) / 2;

                    if (outAbs) outAbs.textContent = diff.toFixed(2);
                    if (outAvg) outAvg.textContent = avg.toFixed(2);

                    // Update LANGSUNG ke tab Proximate, tanpa nunggu bubbling/observer
                    const proxTmCell = document.querySelector('tr[data-param="TM"] .prox-ar');
                    if (proxTmCell) proxTmCell.textContent = avg.toFixed(2);

                    // Trigger ulang perhitungan basis lain di Proximate (FC dsb butuh TM)
                    if (typeof calculateProximateBases === 'function') {
                        calculateProximateBases();
                    }

                    // Tetap panggil syncTab1ToTab2 untuk sinkronkan parameter lain (opsional, tetap dipertahankan)
                    if (typeof syncTab1ToTab2 === 'function') {
                        setTimeout(syncTab1ToTab2, 100);
                    }
                } else {
                    if (outAbs) outAbs.textContent = '-';
                    if (outAvg) outAvg.textContent = '-';

                    const proxTmCell = document.querySelector('tr[data-param="TM"] .prox-ar');
                    if (proxTmCell) proxTmCell.textContent = '-';
                }
            }

            runToleranceValidation(tbody, pid, code); 
            return; 
        }
        else if (code === 'CHN') {
            const tbody = row.closest('tbody');
            if (!tbody) return;

            const chnRows = [
                { type: 'weight',   in1: '.in-w-1', in2: '.in-w-2' },
                { type: 'carbon',   in1: '.in-c-1', in2: '.in-c-2' },
                { type: 'hydrogen', in1: '.in-h-1', in2: '.in-h-2' },
                { type: 'nitrogen', in1: '.in-n-1', in2: '.in-n-2' },
            ];

            chnRows.forEach(r => {
                const tr = tbody.querySelector(`tr[data-type="${r.type}"]`);
                if (!tr) return;

                const v1 = parseNum(tr.querySelector(r.in1)?.value);
                const v2 = parseNum(tr.querySelector(r.in2)?.value);

                const elDiff   = tr.querySelector('.out-diff');
                const elAvgTxt = tr.querySelector('.out-avg-txt');
                const elHasil1 = tr.querySelector('.in-hasil-1');
                const elHasil2 = tr.querySelector('.in-hasil-2');

                if (v1 > 0 && v2 > 0) {
                    const diff = Math.abs(v1 - v2);
                    const avg  = (v1 + v2) / 2;

                    if (elDiff) elDiff.textContent = diff.toFixed(2);
                    if (elAvgTxt) { elAvgTxt.textContent = avg.toFixed(2); elAvgTxt.dataset.val = avg; }
                    if (elHasil1) elHasil1.value = v1;
                    if (elHasil2) elHasil2.value = v2;
                } else {
                    if (elDiff) elDiff.textContent = '-';
                    if (elAvgTxt) { elAvgTxt.textContent = '-'; elAvgTxt.dataset.val = ''; }
                    if (elHasil1) elHasil1.value = '';
                    if (elHasil2) elHasil2.value = '';
                }

                // PANGGIL VALIDASI UNTUK CARBON, HYDROGEN, NITROGEN
                if (r.type !== 'weight') {
                    let rowPid = pid;
                    let anyInput = tr.querySelector('[name^="params["]');
                    if (anyInput) {
                        let match = anyInput.name.match(/params\[(\d+)\]/);
                        if (match) rowPid = match[1];
                    }
                    runToleranceValidation(tr, rowPid, code);
                }
            });

            if (typeof syncTab1ToTab2 === 'function') {
                setTimeout(syncTab1ToTab2, 100);
            }
            return;
        }

        else if (code === 'GCV') {
            // Weight of Crucible + Sample = weight of crucible + sample mass
            const wcs = scope.wc + scope.mass;
            const wcsInp = row.querySelector(`.in-wcs-${i}`);
            if (wcsInp) wcsInp.value = wcs > 0 ? wcs.toFixed(4) : '';

            // t = preliminary result / Ee * sample mass
            const t = scope.ee > 0 ? (scope.pre / scope.ee) * scope.mass : 0;
            const tInp = row.querySelector(`.in-t-${i}`);
            if (tInp) tInp.value = t !== 0 ? t.toFixed(4) : '';

            // e1 = 14.3 * volume of titrant * normality of titrant
            const e1 = 14.3 * scope.vt * scope.nt;
            const e1Inp = row.querySelector(`.in-e1-${i}`);
            if (e1Inp) e1Inp.value = e1 !== 0 ? e1.toFixed(4) : '';

            // e2 = length of fuse * heat of combustion of fuse
            const e2 = scope.lf * scope.hf;
            const e2Inp = row.querySelector(`.in-e2-${i}`);
            if (e2Inp) e2Inp.value = e2 !== 0 ? e2.toFixed(4) : '';

            // e3 = total sulfur * 13.3 * sample mass
            const e3 = scope.ts * 13.3 * scope.mass;
            const e3Inp = row.querySelector(`.in-e3-${i}`);
            if (e3Inp) e3Inp.value = e3 !== 0 ? e3.toFixed(4) : '';

            // Final Result = preliminary result - e1 - e2 - e3
            if (scope.pre > 0) {
                result = scope.pre - e1 - e2 - e3;
            }
        }

        else if (code === 'NCV') {
            syncAndCalculateNcv();
            return;
        }

        const inHasil = row.querySelector(`.in-hasil-${i}`);
        const inD = row.querySelector(`.in-d${i}`);
        if(inHasil && !inHasil.hasAttribute('disabled')) {
            const dec = (code === 'CV' || code === 'GCV') ? 0 : 2;
            if (code !== 'VM' && code !== 'TOTAL SULFUR (%AD/DB)') {
                inHasil.value = result > 0 ? result.toFixed(dec) : '';
                if(inD) inD.value = result > 0 ? result.toFixed(dec) : '';
            } else {
                if(inD) inD.value = inHasil.value;
            }
        }

        let im1 = 0, im2 = 0;
        if (code === 'VM') {
            const imTable = document.querySelector('table[data-code="IM"]');
            if(imTable) {
                const targetTbody = imTable.querySelectorAll('.proximate-tbody')[tbody.dataset.index || 0];
                if(targetTbody) {
                    im1 = parseNum(targetTbody.querySelector('.in-hasil-1')?.value);
                    im2 = parseNum(targetTbody.querySelector('.in-hasil-2')?.value);
                }
            }
        } else {
            const imTable = document.querySelector('table[data-code="IM"]');
            if(imTable && document.querySelector(`.param-enable-check[data-pid="${imTable.dataset.pid}"]`)?.checked) {
                const myTbodyIndex = tbody.dataset.index || 0;
                const targetTbody = imTable.querySelectorAll('.proximate-tbody')[myTbodyIndex];
                if(targetTbody) {
                    im1 = parseNum(targetTbody.querySelector('.in-hasil-1')?.value);
                    im2 = parseNum(targetTbody.querySelector('.in-hasil-2')?.value);
                }
            }
        }

        let d1 = parseNum(tbody.querySelector('.in-d1')?.value || tbody.querySelector('.in-hasil-1')?.value);
        let d2 = parseNum(tbody.querySelector('.in-d2')?.value || tbody.querySelector('.in-hasil-2')?.value);
        let avgDb = 0;

        const outDiff = tbody.querySelector('.out-diff');
        if(outDiff && d1 > 0 && d2 > 0) {
            const diff = Math.abs(d1 - d2);
            outDiff.textContent = diff.toFixed(2);
        }

        if(code === 'CV' || code === 'TS' || code === 'ASH' || code === 'VM') {
            const inDb1 = tbody.querySelector('.in-db-1');
            const outDb1 = tbody.querySelector('.out-db-1');
            const inDb2 = tbody.querySelector('.in-db-2');
            const outDb2 = tbody.querySelector('.out-db-2');
            const outAvgDb = tbody.querySelector('.out-avg-db');

            let db1 = 0, db2 = 0;
            if(im1 > 0 && d1 > 0) {
                db1 = d1 * (100 / (100 - im1));
                if(outDb1) outDb1.textContent = db1.toFixed(2);
                if(inDb1) inDb1.value = db1.toFixed(4);
            }
            if(im2 > 0 && d2 > 0) {
                db2 = d2 * (100 / (100 - im2));
                if(outDb2) outDb2.textContent = db2.toFixed(2);
                if(inDb2) inDb2.value = db2.toFixed(4);
            }

            if(im1 > 0 && im2 > 0 && d1 > 0 && d2 > 0) {
                avgDb = (db1 + db2) / 2;
                if(outAvgDb) outAvgDb.textContent = avgDb.toFixed(2);
            }
        }

        const outAvgAdb = tbody.querySelector('.out-avg-adb');
        if(outAvgAdb && d1 > 0 && d2 > 0) {
            const avgAdb = (d1 + d2) / 2;
            outAvgAdb.textContent = avgAdb.toFixed(2);
        }

        runToleranceValidation(tbody, pid, code);
    }

    function syncAndCalculateNcv() {
        const ncvTable = document.querySelector('table[data-code="NCV"]');
        if (!ncvTable) return;
        const tbody = ncvTable.querySelector('tbody');
        if (!tbody) return;

        // 1. Bomb No, Call ID, Qv(ad) gross → ditarik dari modul GCV
        const gcvTable = document.querySelector('table[data-code="GCV"]');
        if (gcvTable) {
            const gcvSimplo = gcvTable.querySelector('.simplo-row');
            const gcvDuplo = gcvTable.querySelector('.duplo-row');

            const tBomb1 = tbody.querySelector('.in-bomb-1');
            const tBomb2 = tbody.querySelector('.in-bomb-2');
            const tCall1 = tbody.querySelector('.in-callid-1');
            const tCall2 = tbody.querySelector('.in-callid-2');
            const tQv1 = tbody.querySelector('.in-qvad-1');
            const tQv2 = tbody.querySelector('.in-qvad-2');

            if (tBomb1) tBomb1.value = gcvSimplo?.querySelector('.in-bomb-1')?.value || '';
            if (tBomb2) tBomb2.value = gcvDuplo?.querySelector('.in-bomb-2')?.value || '';
            if (tCall1) tCall1.value = gcvSimplo?.querySelector('.in-call-1')?.value || '';
            if (tCall2) tCall2.value = gcvDuplo?.querySelector('.in-call-2')?.value || '';
            if (tQv1) tQv1.value = gcvSimplo?.querySelector('.in-hasil-1')?.value || '';
            if (tQv2) tQv2.value = gcvDuplo?.querySelector('.in-hasil-2')?.value || '';
        }

        // 2. Avg. Qv (ad) gross → average (cal/g) dari modul GCV
        let avgQv = gcvTable ? parseNum(gcvTable.querySelector('.out-avg-adb')?.textContent) : 0;
        const avgQvInp = tbody.querySelector('.in-avgqv');
        if (avgQvInp) avgQvInp.value = avgQv > 0 ? avgQv.toFixed(2) : '';

        // 3. Total Moisture → average dari modul TM
        const tmTable = document.querySelector('table[data-code="TM"]');
        let tmVal = tmTable ? parseNum(tmTable.querySelector('.out-avg-ar')?.textContent) : 0;
        const tmInp = tbody.querySelector('.in-tm');
        if (tmInp) tmInp.value = tmVal > 0 ? tmVal.toFixed(2) : '';

        // 4. Moisture in Analysis → average dari modul IM (Proximate Analysis)
        const imTable = document.querySelector('table[data-code="IM"]');
        let imVal = imTable ? parseNum(imTable.querySelector('.out-avg-adb')?.textContent) : 0;
        const imInp = tbody.querySelector('.in-im');
        if (imInp) imInp.value = imVal > 0 ? imVal.toFixed(2) : '';

        // 5. Hydrogen/Nitrogen/Oxygen (ad) → tab Proximate, kolom Air Dry Basis
        const hVal = parseNum(document.querySelector('tr[data-param="H"] .prox-adb')?.textContent);
        const nVal = parseNum(document.querySelector('tr[data-param="N"] .prox-adb')?.textContent);
        const oVal = parseNum(document.querySelector('tr[data-param="O"] .prox-adb')?.textContent);

        const hInp = tbody.querySelector('.in-h');
        const nInp = tbody.querySelector('.in-n');
        const oInp = tbody.querySelector('.in-o');
        if (hInp) hInp.value = hVal > 0 ? hVal.toFixed(2) : '';
        if (nInp) nInp.value = nVal > 0 ? nVal.toFixed(2) : '';
        if (oInp) oInp.value = oVal > 0 ? oVal.toFixed(2) : '';

        // 6. R, T, Hvap → manual (dibaca langsung dari input di tabel ini)
        const rVal = parseNum(tbody.querySelector('.in-r')?.value);
        const tVal = parseNum(tbody.querySelector('.in-t')?.value);
        const hvapVal = parseNum(tbody.querySelector('.in-hvap')?.value);

        // 7. Rumus kalkulasi otomatis
        let qvp = 0, qh = 0, qmar = 0, qvadj = 0, qparj = 0, qparcal = 0;

        if (rVal > 0 && tVal > 0) {
            qvp = Math.round((0.01 * rVal * tVal * ((hVal / (2 * 2.016)) - (oVal / 31.9988) - (nVal / 28.0134))) * 100) / 100;
        }
        const qvpInp = tbody.querySelector('.in-qvp');
        if (qvpInp) qvpInp.value = (rVal > 0 && tVal > 0) ? qvp.toFixed(2) : '';

        if (hvapVal > 0) {
            qh = Math.round((0.01 * hvapVal * (hVal / 2.016)) * 100) / 100;
            qmar = Math.round((0.01 * hvapVal * (tmVal / 18.0154)) * 100) / 100;
        }
        const qhInp = tbody.querySelector('.in-qh');
        if (qhInp) qhInp.value = hvapVal > 0 ? qh.toFixed(2) : '';
        const qmarInp = tbody.querySelector('.in-qmar');
        if (qmarInp) qmarInp.value = hvapVal > 0 ? qmar.toFixed(2) : '';

        if (avgQv > 0) {
            qvadj = Math.round((avgQv / 0.239) * 100) / 100;
        }
        const qvadjInp = tbody.querySelector('.in-qvadj');
        if (qvadjInp) qvadjInp.value = avgQv > 0 ? qvadj.toFixed(2) : '';

        if (qvadj > 0 && imVal < 100) {
            qparj = Math.round((((qvadj + qvp) * (100 - tmVal) / (100 - imVal)) - qh) * 100) / 100;
        }
        const qparjInp = tbody.querySelector('.in-qparj');
        if (qparjInp) qparjInp.value = qvadj > 0 ? qparj.toFixed(2) : '';

        if (qparj !== 0) {
            qparcal = Math.round((0.239 * qparj) * 100) / 100;
        }

        const hasilInp = tbody.querySelector('.in-hasil-1');
        if (hasilInp) hasilInp.value = qparj !== 0 ? qparcal.toFixed(2) : '';

        const d1Inp = tbody.querySelector('.in-d1');
        const d2Inp = tbody.querySelector('.in-d2');
        if (d1Inp) d1Inp.value = qparj !== 0 ? qparcal.toFixed(2) : '';
        if (d2Inp) d2Inp.value = qparj !== 0 ? qparcal.toFixed(2) : '';
    }

    function setupInputListener(input) {
        input.addEventListener('input', function() {
            const table = this.closest('table');
            if(!table) return;
            calculateRow(this.closest('.row-entry'), table.dataset.pid);

            const myTbody = this.closest('tbody');
            if(myTbody) {
                const myIndex = myTbody.dataset.index;
                if(table.dataset.code === 'IM') {
                    const vmTable = document.querySelector('table[data-code="VM"]');
                    if(vmTable) {
                        const vmtbody = vmTable.querySelectorAll('.proximate-tbody')[myIndex];
                        if(vmtbody) {
                            calculateRow(vmtbody.querySelector('.simplo-row'), vmTable.dataset.pid);
                            calculateRow(vmtbody.querySelector('.duplo-row'), vmTable.dataset.pid);
                        }
                    }
                }
                if(['IM', 'ASH', 'VM'].includes(table.dataset.code)) {
                    const fcTable = document.querySelector('table[data-code="FC"]');
                    if(fcTable) {
                        const fctbody = fcTable.querySelectorAll('.proximate-tbody')[myIndex];
                        if(fctbody) {
                            calculateRow(fctbody.querySelector('.simplo-row'), fcTable.dataset.pid);
                            calculateRow(fctbody.querySelector('.duplo-row'), fcTable.dataset.pid);
                        }
                    }
                }
            }
        });
    }

    document.querySelectorAll('.param-table input, .input-table input').forEach(input => {
        setupInputListener(input);
    });

    document.addEventListener('click', function(e) {
        if(e.target.closest('.btn-add-row')) {
            const btn = e.target.closest('.btn-add-row');
            const table = btn.closest('table');
            const tbodies = table.querySelectorAll('tbody');
            if(tbodies.length === 0) return;
            const lastTbody = tbodies[tbodies.length - 1];

            const newTbody = lastTbody.cloneNode(true);
            const newIndex = parseInt(lastTbody.dataset.index) + 1;
            newTbody.dataset.index = newIndex;

            newTbody.querySelectorAll('input, select').forEach(inp => {
                if(inp.name) {
                    inp.name = inp.name.replace(/\[data\]\[\d+\]/, `[data][${newIndex}]`);
                }
                if(inp.type !== 'hidden' && !inp.hasAttribute('readonly') && inp.type !== 'date') {
                    inp.value = '';
                }
            });

            newTbody.querySelectorAll('.out-diff, .out-avg-adb').forEach(el => el.textContent = '-');

            table.insertBefore(newTbody, table.querySelector('tfoot'));

            newTbody.querySelectorAll('input').forEach(input => {
                setupInputListener(input);
            });
        }

        if(e.target.closest('.btn-remove-last-row')) {
            const btn = e.target.closest('.btn-remove-last-row');
            const table = btn.closest('table');
            const tbodies = table.querySelectorAll('tbody');
            if(tbodies.length > 1) {
                tbodies[tbodies.length - 1].remove();
            } else {
                alert('Minimal harus ada 1 pengujian.');
            }
        }
    });

    document.querySelectorAll('.cat-enable-check').forEach(cb => {
        cb.addEventListener('change', function() {
            const catId = this.dataset.cat;
            const isChecked = this.checked;

            document.querySelectorAll(`.param-enable-check[data-cat="${catId}"]`).forEach(paramCb => {
                if(paramCb.checked !== isChecked) {
                    paramCb.checked = isChecked;
                    paramCb.dispatchEvent(new Event('change'));
                }
            });

            const collapseEl = document.getElementById('collapse-cat-' + catId);
            if(collapseEl && isChecked) {
                const bsCollapse = bootstrap.Collapse.getInstance(collapseEl) || new bootstrap.Collapse(collapseEl, {toggle: false});
                bsCollapse.show();
            }
        });
    });

    document.querySelectorAll('.param-enable-check').forEach(cb => {
        cb.addEventListener('change', function() {
            const container = this.closest('.param-container');
            if(container) {
                const wrapper = container.querySelector('.param-form-wrapper');
                if(this.checked) {
                    wrapper.style.opacity = '1';
                    wrapper.style.pointerEvents = 'auto';
                    wrapper.querySelectorAll('input, select, button').forEach(el => el.removeAttribute('disabled'));
                    wrapper.querySelectorAll('input.bg-light').forEach(el => el.setAttribute('readonly', true));
                } else {
                    wrapper.style.opacity = '0.5';
                    wrapper.style.pointerEvents = 'none';
                    wrapper.querySelectorAll('input, select, button').forEach(el => el.setAttribute('disabled', true));
                }
            }
        });
    });

    // DRAFT LOGIC
    const DRAFT_KEY = 'qc_uji_banding_draft_form';
    const btnDraft = document.getElementById('btnDraft');
    if(btnDraft) {
        btnDraft.addEventListener('click', function() {
            const draft = {};
            draft.nama_program = document.querySelector('input[name="nama_program"]')?.value || '';
            draft.penyelenggara = document.querySelector('input[name="penyelenggara"]')?.value || '';
            draft.kode_sampel = document.querySelector('input[name="kode_sampel"]')?.value || '';
            draft.tanggal_terima = document.querySelector('input[name="tanggal_terima"]')?.value || '';
            draft.keterangan = document.querySelector('textarea[name="keterangan"]')?.value || '';
            draft.proximate_adl = document.getElementById('proximate-adl')?.value || '';
            draft.proximate_methods = {};
            document.querySelectorAll('.prox-method').forEach(inp => {
                const param = inp.closest('tr').dataset.param;
                if(inp.value) draft.proximate_methods[param] = inp.value;
            });

            draft.alat_ids = [];
            document.querySelectorAll('input[name="alat_ids[]"]:checked').forEach(cb => draft.alat_ids.push(cb.value));

            draft.barang_ids = [];
            draft.barang_jumlah = {};
            document.querySelectorAll('input[name="barang_ids[]"]:checked').forEach(cb => draft.barang_ids.push(cb.value));
            document.querySelectorAll('.barang-input').forEach(inp => {
                const match = inp.name.match(/barang_jumlah\[(\d+)\]/);
                if(match && inp.value) draft.barang_jumlah[match[1]] = inp.value;
            });

            draft.params = {};
            document.querySelectorAll('.param-enable-check').forEach(check => {
                const pid = check.dataset.pid;
                const table = document.getElementById('table-' + pid);
                if(!table) return;

                draft.params[pid] = {
                    selected: check.checked,
                    tanggal_uji: document.querySelector(`input[name="params[${pid}][tanggal_uji]"]`)?.value || '',
                    analis_id: document.querySelector(`select[name="params[${pid}][analis_id]"]`)?.value || '',
                    alat_id: document.querySelector(`select[name="params[${pid}][alat_id]"]`)?.value || '',
                    metode_uji: document.querySelector(`input[name="params[${pid}][metode_uji]"]`)?.value || '',
                    uncertainty_lab: document.querySelector(`input[name="params[${pid}][uncertainty_lab]"]`)?.value || '',
                    ref_no: document.querySelector(`input[name="params[${pid}][ref_no]"]`)?.value || '',
                    blnc_id: document.querySelector(`input[name="params[${pid}][blnc_id]"]`)?.value || document.querySelector(`.param-container[data-pid="${pid}"] input[data-meta="blnc_id"]`)?.value || '',
                    time: document.querySelector(`input[name="params[${pid}][time]"]`)?.value || document.querySelector(`.param-container[data-pid="${pid}"] input[data-meta="time"]`)?.value || '',
                    furnace_id: document.querySelector(`input[name="params[${pid}][furnace_id]"]`)?.value || document.querySelector(`.param-container[data-pid="${pid}"] input[data-meta="oven_id"]`)?.value || '',
                    std_method: document.querySelector(`input[name="params[${pid}][std_method]"]`)?.value || document.querySelector(`.param-container[data-pid="${pid}"] input[data-meta="std_method"]`)?.value || '',
                    indicate_t: document.querySelector(`input[name="params[${pid}][indicate_t]"]`)?.value || '',
                    inputs: {}
                };

                table.querySelectorAll('input, select').forEach(inp => {
                    const classes = Array.from(inp.classList).filter(c => c.startsWith('in-') || c === 'form-select');
                    if(classes.length > 0 && inp.name) {
                        draft.params[pid].inputs[inp.name] = inp.value;
                    }
                });
            });

            draft.saved_at = new Date().toLocaleString('id-ID');

            const draftId = document.getElementById('draft_id_input')?.value || '';
            draft.draft_id = draftId;

            fetch('{{ route("qc-uji-banding.draft.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(draft)
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    if (document.getElementById('draft_id_input')) {
                        document.getElementById('draft_id_input').value = data.draft_id;
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Draft Tersimpan!',
                        html: `<p>Draft berhasil disimpan ke Database Server.</p>`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan draft ke server.', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Gagal', 'Terjadi kesalahan jaringan/sistem.', 'error');
            });
        });
    }

    (function loadDraft() {
        @if(isset($draftData) && $draftData)
        try {
            const draft = {!! json_encode($draftData) !!};

            if(draft.nama_program) document.querySelector('input[name="nama_program"]').value = draft.nama_program;
            if(draft.penyelenggara) document.querySelector('input[name="penyelenggara"]').value = draft.penyelenggara;
            if(draft.kode_sampel) document.querySelector('input[name="kode_sampel"]').value = draft.kode_sampel;
            if(draft.tanggal_terima) document.querySelector('input[name="tanggal_terima"]').value = draft.tanggal_terima;
            if(draft.keterangan) document.querySelector('textarea[name="keterangan"]').value = draft.keterangan;
            if(draft.proximate_adl && document.getElementById('proximate-adl')) { document.getElementById('proximate-adl').value = draft.proximate_adl; setTimeout(calculateProximateBases, 100); }
            if(draft.proximate_methods) {
                Object.keys(draft.proximate_methods).forEach(param => {
                    const row = document.querySelector(`tr[data-param="${param}"]`);
                    if(row) {
                        const inp = row.querySelector('.prox-method');
                        if(inp) inp.value = draft.proximate_methods[param];
                    }
                });
            }

            if(draft.alat_ids) {
                draft.alat_ids.forEach(id => {
                    const cb = document.querySelector(`input[name="alat_ids[]"][value="${id}"]`);
                    if(cb) cb.checked = true;
                });
            }

            if(draft.barang_ids) {
                draft.barang_ids.forEach(id => {
                    const cb = document.querySelector(`input[name="barang_ids[]"][value="${id}"]`);
                    if(cb) {
                        cb.checked = true;
                        cb.dispatchEvent(new Event('change'));
                    }
                });
            }
            if(draft.barang_jumlah) {
                setTimeout(() => {
                    Object.keys(draft.barang_jumlah).forEach(id => {
                        const inp = document.querySelector(`input[name="barang_jumlah[${id}]"]`);
                        if(inp) inp.value = draft.barang_jumlah[id];
                    });
                }, 100);
            }

            if(draft.params) {
                Object.keys(draft.params).forEach(pid => {
                    const pData = draft.params[pid];
                    const check = document.querySelector(`.param-enable-check[data-pid="${pid}"]`);
                    const table = document.getElementById('table-' + pid);
                    if(!check || !table) return;

                    if(pData.selected) {
                        check.checked = true;
                        check.dispatchEvent(new Event('change'));

                        setTimeout(() => {
                            const tglInp = document.querySelector(`input[name="params[${pid}][tanggal_uji]"]`);
                            const analisSel = document.querySelector(`select[name="params[${pid}][analis_id]"]`);
                            const alatSel = document.querySelector(`select[name="params[${pid}][alat_id]"]`);
                            const metInp = document.querySelector(`input[name="params[${pid}][metode_uji]"]`);
                            const uInp = document.querySelector(`input[name="params[${pid}][uncertainty_lab]"]`);
                            const refNoInp = document.querySelector(`input[name="params[${pid}][ref_no]"]`);
                            const blncInp = document.querySelector(`input[name="params[${pid}][blnc_id]"]`) || document.querySelector(`.param-container[data-pid="${pid}"] input[data-meta="blnc_id"]`);
                            const timeInp = document.querySelector(`input[name="params[${pid}][time]"]`) || document.querySelector(`.param-container[data-pid="${pid}"] input[data-meta="time"]`);
                            const furnaceInp = document.querySelector(`input[name="params[${pid}][furnace_id]"]`) || document.querySelector(`.param-container[data-pid="${pid}"] input[data-meta="oven_id"]`);
                            const stdInp = document.querySelector(`input[name="params[${pid}][std_method]"]`) || document.querySelector(`.param-container[data-pid="${pid}"] input[data-meta="std_method"]`);
                            const indInp = document.querySelector(`input[name="params[${pid}][indicate_t]"]`);

                            if(tglInp && pData.tanggal_uji) tglInp.value = pData.tanggal_uji;
                            if(analisSel && pData.analis_id) analisSel.value = pData.analis_id;
                            if(alatSel && pData.alat_id) alatSel.value = pData.alat_id;
                            if(metInp && pData.metode_uji) metInp.value = pData.metode_uji;
                            if(uInp && pData.uncertainty_lab) uInp.value = pData.uncertainty_lab;
                            if(refNoInp && pData.ref_no) refNoInp.value = pData.ref_no;
                            if(blncInp && pData.blnc_id) blncInp.value = pData.blnc_id;
                            if(timeInp && pData.time) timeInp.value = pData.time;
                            if(furnaceInp && pData.furnace_id) furnaceInp.value = pData.furnace_id;
                            if(stdInp && pData.std_method) stdInp.value = pData.std_method;
                            if(indInp && pData.indicate_t) indInp.value = pData.indicate_t;

                            if(pData.inputs) {
                                Object.keys(pData.inputs).forEach(name => {
                                    const inp = table.querySelector(`[name="${name}"]`);
                                    if(inp) inp.value = pData.inputs[name];
                                });
                                table.querySelectorAll('.row-entry').forEach(row => {
                                    const rowInp = row.querySelector('input');
                                    if(rowInp) rowInp.dispatchEvent(new Event('input'));
                                });
                            }
                        }, 50);
                    }
                });
            }
        } catch(e) {
            console.error('Failed to parse draft', e);
        }
        @endif
    })();

    const formQc = document.getElementById('formQc');
    if(formQc) {
        formQc.addEventListener('submit', function(e) {
            if (document.querySelectorAll('.param-enable-check:checked').length === 0) {
                e.preventDefault();
                alert('Silakan centang minimal 1 parameter yang ingin diuji.');
                return;
            }
            localStorage.removeItem(DRAFT_KEY);
        });
    }

    // ========== TAB PROXIMATE LOGIC ==========
    const tab2SampleId = document.getElementById('proximate-sample-id');
    const tab2Adl = document.getElementById('proximate-adl');

    // Function to calculate all bases in Tab 2
        function calculateProximateBases() {
        const adl = parseFloat(tab2Adl?.value) || 0;

        let tmAr = parseFloat(document.querySelector('tr[data-param="TM"] .prox-ar')?.textContent) || 0;
        let imAdb = parseFloat(document.querySelector('tr[data-param="IM"] .prox-adb')?.textContent) || 0;
        let ashAdb = parseFloat(document.querySelector('tr[data-param="ASH"] .prox-adb')?.textContent) || 0;
        let ashDb = 0; // Needed for VM, FC, TS, GCV, C/H/N, O DAF calculation
        let vmDb = 0; // Needed for FC db

        // --- 1. ASH CONTENT ---
        const ashRow = document.querySelector('tr[data-param="ASH"]');
        if (ashRow && ashAdb > 0) {
            let ar = (100 - tmAr) / (100 - imAdb) * ashAdb;
            let db = (100 / (100 - imAdb)) * ashAdb;
            ashDb = parseFloat(db.toFixed(2));

            ashRow.querySelector('.prox-ar').textContent = ar.toFixed(2);
            ashRow.querySelector('.prox-db').textContent = db.toFixed(2);
            ashRow.querySelector('.prox-daf').textContent = '-'; // strip
        }

        // --- 2. VOLATILE MATTER ---
        const vmRow = document.querySelector('tr[data-param="VM"]');
        let vmAdb = parseFloat(vmRow?.querySelector('.prox-adb')?.textContent) || 0;
        if (vmRow && vmAdb > 0) {
            let ar = (100 - tmAr) / (100 - imAdb) * vmAdb;
            let db = (100 / (100 - imAdb)) * vmAdb;
            vmDb = parseFloat(db.toFixed(2));
            let daf = (100 / (100 - ashDb)) * db;

            vmRow.querySelector('.prox-ar').textContent = ar.toFixed(2);
            vmRow.querySelector('.prox-db').textContent = db.toFixed(2);
            vmRow.querySelector('.prox-daf').textContent = daf.toFixed(2);
        }

        // --- 3. FIXED CARBON ---
        const fcRow = document.querySelector('tr[data-param="FC"]');
        if (fcRow) {
            let ar = 100 - tmAr - parseFloat(ashRow?.querySelector('.prox-ar')?.textContent || 0) - parseFloat(vmRow?.querySelector('.prox-ar')?.textContent || 0);
            let adb = 100 - imAdb - ashAdb - vmAdb;
            let db = 100 - ashDb - vmDb;
            let daf = 100 - parseFloat(vmRow?.querySelector('.prox-daf')?.textContent || 0);

            fcRow.querySelector('.prox-ar').textContent = ar.toFixed(2);
            fcRow.querySelector('.prox-adb').textContent = adb.toFixed(2);
            fcRow.querySelector('.prox-db').textContent = db.toFixed(2);
            fcRow.querySelector('.prox-daf').textContent = daf.toFixed(2);
        }

        // --- 3.5 CARBON / HYDROGEN / NITROGEN (sumber: Dry Basis, dari average CHN) ---
        ['C', 'H', 'N'].forEach(param => {
            const row = document.querySelector(`tr[data-param="${param}"]`);
            if (!row) return;
            let db = parseFloat(row.querySelector('.prox-db')?.textContent) || 0;
            if (db > 0) {
                let adb = ((100 - imAdb) / 100) * db;
                let ar  = (100 - tmAr) / (100 - imAdb) * adb;
                let daf = (100 / (100 - ashDb)) * db;

                row.querySelector('.prox-adb').textContent = adb.toFixed(2);
                row.querySelector('.prox-ar').textContent  = ar.toFixed(2);
                row.querySelector('.prox-daf').textContent = daf.toFixed(2);
            }
        });

        // --- 4. TOTAL SULFUR & GCV ---
        const otherRows = ['TS', 'GCV'];
        otherRows.forEach(param => {
            const row = document.querySelector(`tr[data-param="${param}"]`);
            if (!row) return;
            let adb = parseFloat(row.querySelector('.prox-adb')?.textContent) || 0;
            if (adb > 0) {
                let ar = (100 - tmAr) / (100 - imAdb) * adb;
                let db = (100 / (100 - imAdb)) * adb;
                let daf = (100 / (100 - ashDb)) * db;

                row.querySelector('.prox-ar').textContent = ar.toFixed(2);
                row.querySelector('.prox-db').textContent = db.toFixed(2);
                row.querySelector('.prox-daf').textContent = daf.toFixed(2);
            }
        });

        // --- 5. OXYGEN (by difference) ---
        const oRow = document.querySelector('tr[data-param="O"]');
        if (oRow) {
            const g = (param, basis) => parseFloat(document.querySelector(`tr[data-param="${param}"] .prox-${basis}`)?.textContent) || 0;

            const oAr  = 100 - g('C', 'ar')  - g('H', 'ar')  - g('N', 'ar')  - tmAr  - g('TS', 'ar')  - g('ASH', 'ar');
            const oAdb = 100 - g('C', 'adb') - g('H', 'adb') - g('N', 'adb') - imAdb - g('TS', 'adb') - ashAdb;
            const oDb  = 100 - g('C', 'db')  - g('H', 'db')  - g('N', 'db')  - g('TS', 'db')  - ashDb;
            const oDaf = 100 - g('C', 'daf') - g('H', 'daf') - g('N', 'daf') - g('TS', 'daf');

            oRow.querySelector('.prox-ar').textContent  = oAr.toFixed(2);
            oRow.querySelector('.prox-adb').textContent = oAdb.toFixed(2);
            oRow.querySelector('.prox-db').textContent  = oDb.toFixed(2);
            oRow.querySelector('.prox-daf').textContent = oDaf.toFixed(2);
        }
    }

    // Listen to ADL input changes
    if (tab2Adl) {
        // Kita buat fungsi khusus agar bisa dipanggil kapan saja
        function syncAdlToTm() {
            const adlVal = tab2Adl.value;

            // 1. Tembakkan angka dari Tab Proximate ke semua kolom %ADL 2 di layar
            document.querySelectorAll('.in-adl2').forEach(inp => {
                if (inp.value !== adlVal) {
                    inp.value = adlVal;
                }

                inp.dispatchEvent(new Event('input', { bubbles: true }));
            });

            // 2. Pancing kalkulasi tabel TM (tanpa bergantung pada data-code)
            const firstAdl2 = document.querySelector('.in-adl2');
            if (firstAdl2) {
                // Memaksa sistem merasa ada "ketikan" agar rumus otomatis berjalan
                firstAdl2.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }

        // Jalankan SECARA REAL-TIME setiap analis mengetik di kotak ADL
        tab2Adl.addEventListener('input', () => {
            calculateProximateBases(); // Hitung proximate
            syncAdlToTm();             // Lempar datanya ke modul TM
        });

        // Jalankan OTOMATIS saat halaman pertama kali load
        // (Ini menuntaskan masalah "belum sinkron" saat memuat data dari Draft)
        setTimeout(syncAdlToTm, 800);
    }

    // Function to sync averages from Tab 1 to Tab 2
    function syncTab1ToTab2() {
        const mappings = [
            { code: 'TM', paramRow: 'TM', targetBasis: 'ar' },
            { code: 'IM', paramRow: 'IM', targetBasis: 'adb' },
            { code: 'ASH', paramRow: 'ASH', targetBasis: 'adb' },
            { code: 'VM', paramRow: 'VM', targetBasis: 'adb' },
            { code: 'FC', paramRow: 'FC', targetBasis: 'adb' },
            { code: 'TOTAL SULFUR (%AD/DB)', paramRow: 'TS', targetBasis: 'adb' },
            { code: 'GCV', paramRow: 'GCV', targetBasis: 'adb' },
            { code: 'O', paramRow: 'O', targetBasis: 'adb' }
        ];

        let updated = false;

        // Sync TM Average to TM_ar in Tab 2
        const tmTable = document.querySelector('table[data-code="TM"]'); // Need to find TM code
        if (tmTable) {
            const tbody = tmTable.querySelector('tbody');
            if (tbody) {
                const avgArStr = tbody.querySelector('.out-avg-ar')?.textContent || '-';
                // Wait, in Proximate table TM is not row! It's just used for calculations. We don't have TM in Proximate table row. Wait, yes we do, if the user requested it? No, the user didn't request a TM row in Proximate. They requested TM_ar to be used in calculations. Let's store it globally or hidden input.
                // Ah, the user didn't ask for a TM row in Proximate. They just said "Total moisture: kolom as received basis: nilai datanya ambil nilai data average di modul determination of total moisture".
                // Wait, if there's no row, where do we put it? Let's assume there IS a row, or we just put it in window.tmAverage
                window.tmAverage = parseFloat(avgArStr) || 0;
            }
        }

        // Sync RM Average to RM input in Tab 2
        const rmTable = document.querySelector('table[data-code="RM"]');
        if (rmTable) {
            const tbody = rmTable.querySelector('tbody');
            if (tbody) {
                const avgAdbStr = tbody.querySelector('.out-avg-adb')?.textContent || '-';
                const rmInput = document.getElementById('proximate-rm');
                if (rmInput && rmInput.value !== avgAdbStr) {
                    rmInput.value = avgAdbStr;
                    updated = true;
                    // Trigger calculate row for TM if RM changed
                    const inRms = document.querySelectorAll('.in-rm');
                    inRms.forEach((inRm, idx) => {
                        // wait, rm needs simplo/duplo results, not just average!
                    });
                }
            }
        }

        // Let's actually pull RM simplo/duplo/average results for TM directly here
        if (rmTable) {
            // 1. Ambil nilai M% Simplo dan Duplo dari RM
            const simploOut = rmTable.querySelector('.simplo-row .in-hasil-1')?.value;
            const duploOut = rmTable.querySelector('.duplo-row .in-hasil-2')?.value;

            // 2. Ambil nilai Average dari RM
            let rmAverage = rmTable.querySelector('.out-avg-adb')?.textContent;
            if (rmAverage === '-') rmAverage = ''; // Bersihkan jika belum ada hasil

            const tmTableNode = document.querySelector('table[data-code="TM"]');
            if (tmTableNode) {
                const tmSimploRM = tmTableNode.querySelector('tr[data-type="simplo"] .in-rm');
                const tmDuploRM = tmTableNode.querySelector('tr[data-type="duplo"] .in-rm');

                // 3. LOGIKA BARU SESUAI EXCEL:

                // A. Simplo TM mengambil dari Average RM.
                // (Catatan: Jika average belum muncul, ia akan mengambil simploOut sementara agar kolom tidak kosong)
                if (tmSimploRM) {
                    let targetValSimplo = rmAverage ? rmAverage : (simploOut || '');
                    if (tmSimploRM.value !== targetValSimplo) tmSimploRM.value = targetValSimplo;
                }

                // B. Duplo TM TETAP mengambil dari M% RM Duplo.
                if (tmDuploRM && tmDuploRM.value !== duploOut) {
                    tmDuploRM.value = duploOut || '';
                }

                // Trigger TM recalculation
                if (tmTableNode.dataset.code) {
                    // Kalkulasi TM otomatis tereksekusi saat input berubah
                }
            }
        }

        // Sync CHN (Carbon/Hydrogen/Nitrogen) averages ke baris C/H/N di Proximate
        const chnTable = document.querySelector('table[data-code="CHN"]');
        if (chnTable) {
            const chnCheck = document.querySelector(`.param-enable-check[data-pid="${chnTable.dataset.pid}"]`);
            if (!chnCheck || chnCheck.checked) {
                const chnTbody = chnTable.querySelector('tbody');
                if (chnTbody) {
                    const chnMap = [
                        { type: 'carbon',   paramRow: 'C' },
                        { type: 'hydrogen', paramRow: 'H' },
                        { type: 'nitrogen', paramRow: 'N' },
                    ];
                    chnMap.forEach(c => {
                        const tr = chnTbody.querySelector(`tr[data-type="${c.type}"]`);
                        const avgStr = tr?.querySelector('.out-avg-txt')?.textContent || '-';
                        const proxRow = document.querySelector(`tr[data-param="${c.paramRow}"]`);
                        if (proxRow) {
                            const cell = proxRow.querySelector('.prox-db');
                            if (cell && cell.textContent !== avgStr) {
                                cell.textContent = avgStr;
                                updated = true;
                            }
                        }
                    });
                }
            }
        }

        // Sync Methods from Tab 1 to Tab 2
        const methodMap = {
            'TM': 'TM', 'IM': 'IM', 'ASH': 'ASH', 'VM': 'VM', 'FC': 'FC', 'TS': 'TOTAL SULFUR (%AD/DB)', 'GCV': 'GCV', 'C': 'C', 'H': 'H', 'N': 'N', 'O': 'O'
        };

        Object.keys(methodMap).forEach(proxParam => {
            const tab1Code = methodMap[proxParam];
            const tab1Table = document.querySelector(`table[data-code="${tab1Code}"]`);

            if (tab1Table) {
                // 1. Kunci pencarian hanya di dalam kotak modul (container) tabel ini saja
                const container = tab1Table.closest('.param-container');

                if (container) {
                    // 2. Kode Sapu Jagat: Cari input yang namanya mengandung "[std_method]" ATAU memiliki "data-meta='std_method'"
                    const stdMethodInput = container.querySelector('input[name*="[std_method]"], input[data-meta="std_method"]');

                    if (stdMethodInput) {
                        const proxRow = document.querySelector(`tr[data-param="${proxParam}"]`);
                        if (proxRow) {
                            const methodInp = proxRow.querySelector('.prox-method');
                            // 3. Jika ketemu dan isinya beda, langsung tembak/sinkronkan ke Tab Proximate
                            if (methodInp && methodInp.value !== stdMethodInput.value) {
                                methodInp.value = stdMethodInput.value;
                            }
                        }
                    }
                }
            }
        });

        mappings.forEach(m => {
            const tab1Table = document.querySelector(`table[data-code="${m.code}"]`);
            if (tab1Table) {
                const check = document.querySelector(`.param-enable-check[data-pid="${tab1Table.dataset.pid}"]`);
                if (check && !check.checked) return;

                const tbody = tab1Table.querySelector('tbody');
                if (tbody) {
                    let avgStr = '-';
                    if (m.code === 'TM') {
                        avgStr = tbody.querySelector('.out-avg-ar')?.textContent || '-';
                    } else {
                        avgStr = tbody.querySelector('.out-avg-adb')?.textContent || '-';
                    }

                    const row = document.querySelector(`tr[data-param="${m.paramRow}"]`);
                    if (row) {
                        const cell = row.querySelector(`.prox-${m.targetBasis}`);
                        if (cell && cell.textContent !== avgStr) {
                            cell.textContent = avgStr;
                            updated = true;
                        }
                    }
                }
            }
        });

        if (updated) {
            calculateProximateBases();
        }

        syncAndCalculateNcv(); // Sync NCV if needed
        
        // Run once on load to catch any existing data
        setTimeout(syncTab1ToTab2, 1000);
    }

    // We need to run syncTab1ToTab2 whenever any input in Tab 1 changes
    // We already have `calculateRow` which updates the DOM. Let's observe the DOM or just attach an event listener to tab-input-data
    const tabInputData = document.getElementById('tab-input-data');
    if (tabInputData) {
        // Use event listeners for immediate input
        tabInputData.addEventListener('input', () => {
            setTimeout(syncTab1ToTab2, 200);
        });
        tabInputData.addEventListener('change', () => {
            setTimeout(syncTab1ToTab2, 200);
        });

        // Use MutationObserver to catch any textContent changes (like out-avg-adb)
        const observer = new MutationObserver((mutations) => {
            let shouldSync = false;
            for (const mutation of mutations) {
                if (mutation.type === 'characterData' || (mutation.type === 'childList' && mutation.target.tagName === 'TD')) {
                    shouldSync = true;
                    break;
                }
            }
            if (shouldSync) {
                setTimeout(syncTab1ToTab2, 200);
            }
        });
        observer.observe(tabInputData, {
            childList: true,
            subtree: true,
            characterData: true
        });
    }

    // ========== AFT KALIBRASI LOGIC ==========
    const aftBody = document.getElementById('aft-calib-body');
    const btnAftAdd = document.getElementById('aft-add-row');
    const btnAftRemove = document.getElementById('aft-remove-row');
    const btnAftGenerate = document.getElementById('aft-generate');
    const aftInterpContainer = document.getElementById('aft-interpolation-container');
    const btnAftSave = document.getElementById('aft-save-calib');
    const aftSelect = document.getElementById('aft-history-select');

    if (aftBody) {
        // Add row
        btnAftAdd.addEventListener('click', () => {
            const tr = document.createElement('tr');
            tr.className = 'aft-calib-row';
            tr.innerHTML = `
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm text-center aft-eq"></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm text-center aft-std"></td>
                <td><input type="text" inputmode="decimal" class="form-control form-control-sm text-center aft-corr"></td>
            `;
            aftBody.appendChild(tr);
        });

        // Remove row
        btnAftRemove.addEventListener('click', () => {
            if (aftBody.children.length > 2) {
                aftBody.lastElementChild.remove();
            } else {
                alert('Minimal 2 titik kalibrasi diperlukan.');
            }
        });

        // Generate Interpolation
        btnAftGenerate.addEventListener('click', generateAftInterpolation);

        function generateAftInterpolation() {
            const rows = document.querySelectorAll('.aft-calib-row');
            let points = [];
            let isValid = true;

            rows.forEach(row => {
                const eq = parseFloat(row.querySelector('.aft-eq').value);
                const std = parseFloat(row.querySelector('.aft-std').value);
                const corr = parseFloat(row.querySelector('.aft-corr').value);
                if (isNaN(eq) || isNaN(std) || isNaN(corr)) {
                    isValid = false;
                } else {
                    points.push({ eq, std, corr });
                }
            });

            if (!isValid || points.length < 2) {
                alert('Harap isi semua kolom kalibrasi dengan angka valid.');
                return;
            }

            points.sort((a, b) => a.eq - b.eq);

            // Simpan data points globally supaya bisa dipakai Tab 1
            window.aftCalibrationPoints = points;

            let html = '<div class="row g-2">';

            for (let i = 0; i < points.length - 1; i++) {
                let p1 = points[i];
                let p2 = points[i+1];

                html += `<div class="col-md-3 mb-3">
                            <table class="table table-bordered table-sm text-center">
                                <thead class="table-secondary">
                                    <tr><th>Eq. Sett</th><th>Std. Reading</th></tr>
                                </thead>
                                <tbody>`;

                for (let temp = p1.eq + 5; temp < p2.eq; temp += 5) {
                    // Linear interpolation formula: Y = Y1 + ((X - X1) * (Y2 - Y1)) / (X2 - X1)
                    let Y = p1.std + ((temp - p1.eq) * (p2.std - p1.std)) / (p2.eq - p1.eq);
                    html += `<tr><td>${temp}</td><td>${Y.toFixed(2)}</td></tr>`;
                }

                html += `</tbody></table></div>`;
            }
            html += '</div>';
            aftInterpContainer.innerHTML = html;
        }

        // Global AFT Lookup function for Tab 1
        window.getAftCorrection = function(suhu) {
            if (!window.aftCalibrationPoints || window.aftCalibrationPoints.length < 2) return 0;
            const points = window.aftCalibrationPoints;
            let temp = parseFloat(suhu);
            if (isNaN(temp)) return 0;

            for (let i = 0; i < points.length - 1; i++) {
                let p1 = points[i];
                let p2 = points[i+1];
                if (temp >= p1.eq && temp <= p2.eq) {
                    let Y = p1.std + ((temp - p1.eq) * (p2.std - p1.std)) / (p2.eq - p1.eq);
                    return Y; // return Std Reading
                }
            }
            return 0; // Out of bounds
        };

        // Load History List
        function loadAftHistory() {
            fetch('/aft-kalibrasi')
                .then(res => res.json())
                .then(data => {
                    aftSelect.innerHTML = '<option value="">-- Buat Kalibrasi Baru --</option>';
                    data.forEach(item => {
                        const label = `${item.nama_kalibrasi} (${item.tanggal_kalibrasi})`;
                        const opt = document.createElement('option');
                        opt.value = item.id;
                        opt.textContent = (item.is_active ? '[AKTIF] ' : '') + label;
                        opt.dataset.active = item.is_active ? '1' : '0';
                        opt.dataset.nama = item.nama_kalibrasi;
                        if (item.is_active) opt.selected = true;
                        aftSelect.appendChild(opt);
                    });

                    // Auto load the active one
                    const active = data.find(i => i.is_active);
                    if (active) renderAftData(active.data_points);
                    setAftViewingBadge(false);
                });
        }

        function setAftViewingBadge(show, name) {
            const badge = document.getElementById('aft-viewing-badge');
            const nameEl = document.getElementById('aft-viewing-name');
            if (!badge) return;
            if (show) {
                if (nameEl) nameEl.textContent = name || '-';
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }
        }

        function renderAftData(points) {
            aftBody.innerHTML = '';
            points.forEach(p => {
                const tr = document.createElement('tr');
                tr.className = 'aft-calib-row';
                tr.innerHTML = `
                    <td><input type="text" class="form-control form-control-sm text-center aft-eq" value="${p.eq_sett}"></td>
                    <td><input type="text" class="form-control form-control-sm text-center aft-std" value="${p.std_read}"></td>
                    <td><input type="text" class="form-control form-control-sm text-center aft-corr" value="${p.correction}"></td>
                `;
                aftBody.appendChild(tr);
            });
            generateAftInterpolation();
        }

        aftSelect.addEventListener('change', function() {
            if (!this.value) {
                aftBody.innerHTML = `<tr class="aft-calib-row">
                    <td><input type="text" class="form-control form-control-sm text-center aft-eq" placeholder="e.g. 1000"></td>
                    <td><input type="text" class="form-control form-control-sm text-center aft-std" placeholder="e.g. 993.6949"></td>
                    <td><input type="text" class="form-control form-control-sm text-center aft-corr" placeholder="e.g. -6.3"></td>
                </tr>`;
                btnAftAdd.click(); // give at least 2 rows
                aftInterpContainer.innerHTML = '<div class="text-muted text-center py-4">Isi poin kalibrasi...</div>';
                setAftViewingBadge(false);
            } else {
                const selectedOpt = this.options[this.selectedIndex];
                const isActive = selectedOpt?.dataset.active === '1';

                fetch('/aft-kalibrasi/' + this.value)
                    .then(res => res.json())
                    .then(data => {
                        renderAftData(data.data_points);
                        setAftViewingBadge(!isActive, selectedOpt?.dataset.nama);
                    });
            }
        });

        // Save new Calibration
        btnAftSave.addEventListener('click', () => {
            const rows = document.querySelectorAll('.aft-calib-row');
            let points = [];
            rows.forEach(row => {
                points.push({
                    eq_sett: parseFloat(row.querySelector('.aft-eq').value),
                    std_read: parseFloat(row.querySelector('.aft-std').value),
                    correction: parseFloat(row.querySelector('.aft-corr').value),
                });
            });

            if (points.some(p => isNaN(p.eq_sett) || isNaN(p.std_read) || isNaN(p.correction))) {
                alert('Terdapat data tidak valid.');
                return;
            }

            fetch('/aft-kalibrasi', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    nama_kalibrasi: prompt('Masukkan nama kalibrasi (Kosongkan untuk nama otomatis):'),
                    tanggal_kalibrasi: new Date().toISOString().split('T')[0],
                    data_points: points
                })
            })
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    Swal.fire('Berhasil', 'Kalibrasi AFT baru disimpan & diaktifkan.', 'success');
                    loadAftHistory();
                } else {
                    Swal.fire('Gagal', 'Gagal menyimpan kalibrasi.', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Gagal', 'Terjadi kesalahan sistem.', 'error');
            });
        });

        // Initial load
        loadAftHistory();
    }

    
    setTimeout(function() {
        document.querySelectorAll('.param-table, .input-table').forEach(table => {
            const pid = table.dataset.pid;
            const check = document.querySelector(`.param-enable-check[data-pid="${pid}"]`);
            
            if (check && check.checked) {
                const firstInput = table.querySelector('.row-entry input[type="text"]');
                if (firstInput) {
                    firstInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }
        });
    }, 1500);
});
</script>
@endsection