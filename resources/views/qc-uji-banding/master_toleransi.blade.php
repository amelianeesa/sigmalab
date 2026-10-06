@extends('layouts.app')
@section('title', 'Master Batas Toleransi - QC Uji Banding')

@section('content')
@include('qc-uji-banding.partials._style')

<style>
    .qc-page {
        padding-top: 0 !important;
        padding-left: 20px !important;
        padding-right: 20px !important;
        margin-top: -8px !important;
    }

    .qc-page nav[aria-label="breadcrumb"],
    .qc-page > nav {
        margin: 0 !important;
        padding: 0 !important;
    }

    .qc-page .breadcrumb {
        margin: 0 0 6px 0 !important;
        padding: 0 !important;
        font-size: 0.75rem !important;
        line-height: 1.4;
        flex-wrap: wrap;
        align-items: center;
        background: transparent !important;
    }

    .qc-page .breadcrumb .breadcrumb-item,
    .qc-page .breadcrumb .breadcrumb-item a {
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        color: #0d6efd !important;
        text-decoration: none;
    }

    .qc-page .breadcrumb .breadcrumb-item a:hover {
        color: #0a58ca !important;
        text-decoration: underline;
    }

    .qc-page .breadcrumb .breadcrumb-item.active {
        color: #000000 !important;
        font-weight: 700 !important;
    }

    .qc-page .breadcrumb .breadcrumb-item + .breadcrumb-item {
        padding-left: 0.4rem;
    }

    .qc-page .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        color: #6c757d !important;
        padding-right: 0.4rem;
        font-weight: 400;
    }

    .qc-page .breadcrumb .breadcrumb-item .dropdown-menu {
        min-width: 190px;
        padding: 4px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }

    .qc-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item {
        color: #000000 !important;
        font-size: 0.78rem !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        background-color: transparent;
        padding: 7px 12px;
        border-radius: 5px;
    }

    .qc-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:hover,
    .qc-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:focus,
    .qc-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:active,
    .qc-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item.active {
        background-color: rgba(27, 49, 82, 0.15) !important;
        color: #000000 !important;
        text-decoration: none !important;
    }

    .qc-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item.active {
        font-weight: 700 !important;
    }

    .qc-side-nav .nav-link:not(.active):hover {
        background-color: #e9ecef !important;
        color: #1b3152 !important;
        padding-left: 1.25rem !important;
    }

    @media (max-width: 767.98px) {
        .qc-page {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        .qc-page .breadcrumb,
        .qc-page .breadcrumb .breadcrumb-item,
        .qc-page .breadcrumb .breadcrumb-item a {
            font-size: 0.72rem !important;
        }

        .qc-page .breadcrumb .breadcrumb-item + .breadcrumb-item {
            padding-left: 0.3rem;
        }

        .qc-page .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            padding-right: 0.3rem;
        }

        .qc-page .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item {
            font-size: 0.85rem !important;
            padding: 10px 14px;
        }
    }
</style>

<div class="container-fluid qc-page pb-4">
    <x-qc-breadcrumb active="Uji Banding">
        <li class="breadcrumb-item active" aria-current="page">Master Batas Toleransi</li>
    </x-qc-breadcrumb>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="qc-title">
            <i class="fas fa-balance-scale me-1" style="color: #1b3152;"></i> Master Batas Toleransi QC Uji Banding
        </h5>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('qc-uji-banding.index') }}" class="btn btn-outline-corporate btn-sm py-1 px-3 shadow-sm fw-semibold">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Uji Banding
            </a>
        </div>
    </div>

    @php
        $chnParams = $parameters->filter(fn($p) => in_array($p->nama_parameter, ['C', 'H', 'N']));
        $chnIds = $chnParams->pluck('parameter_uji_id')->toArray();
        $chnToleransis = $chnParams->pluck('toleransis')->flatten();

        $urutanModul = [
            'Proximate Analysis', 
            'Determination of Sulfur by IR Spectrometry', 
            'Calorific Value & Sulfur', 
            'Determination of Total Moisture', 
            'Determination of Carbon, Hydrogen, Nitrogen by Instrument', 
            'Determination of Net Calorific Value', 
            'Ash Behaviour & Physical'
        ];

        $groupedParameters = $parameters->groupBy('kategori_parameter')->sortBy(function($item, $key) use ($urutanModul) {
            $pos = array_search($key, $urutanModul);
            return $pos === false ? 999 : $pos; 
        });

        $firstParamId = $groupedParameters->first() ? $groupedParameters->first()->first()->parameter_uji_id : null;
        $activeTab = session('active_tab', $firstParamId);
        $isChnActive = in_array($activeTab, $chnIds);
    @endphp

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row">

                <div class="col-12 col-md-3 border-end border-end-md">
                    <div class="nav flex-column nav-pills qc-side-nav gap-1" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        @foreach($groupedParameters as $kategori => $params)
                            
                            <div class="fw-bold text-uppercase text-secondary small mt-3 mb-1 px-2" style="letter-spacing: 0.5px;">
                                <i class="fas fa-folder-open me-1"></i> {{ $kategori ?: 'Parameter Lainnya' }}
                            </div>
                            
                            @foreach($params as $param)
                                @if(in_array($param->nama_parameter, ['H', 'N']))
                                    @continue
                                @endif
                                
                                @php 
                                    $isCHN = $param->nama_parameter == 'C';
                                    $tabId = $isCHN ? 'chn' : $param->parameter_uji_id;
                                    $isActive = $isCHN ? $isChnActive : ($param->parameter_uji_id == $activeTab);
                                    $tabLabel = $isCHN ? 'CHN' : $param->nama_parameter;
                                @endphp

                                <button class="nav-link text-start ms-2 px-3 py-2 rounded-2 {{ $isActive ? 'active fw-bold shadow-sm' : 'text-dark bg-light bg-opacity-50' }}" 
                                        id="tab-param-{{ $tabId }}" 
                                        data-bs-toggle="pill" 
                                        data-bs-target="#content-param-{{ $tabId }}" 
                                        type="button" role="tab"
                                        style="font-size: 0.9rem; transition: all 0.2s ease-in-out;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>{{ $tabLabel }}</span>
                                        <i class="fas fa-chevron-right small opacity-50"></i>
                                    </div>
                                </button>
                            @endforeach

                        @endforeach
                    </div>
                </div>

                <div class="col-12 col-md-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        
                        @foreach($parameters as $param)
                            
                            @if(in_array($param->nama_parameter, ['H', 'N']))
                                @continue
                            @endif

                            @php 
                                $isCHN = $param->nama_parameter == 'C';
                                $tabId = $isCHN ? 'chn' : $param->parameter_uji_id;
                                $isActive = $isCHN ? $isChnActive : ($param->parameter_uji_id == $activeTab);

                                $currentToleransis = $isCHN ? $chnToleransis : $param->toleransis;
                                $tabTitle = $isCHN ? 'CHN (Carbon, Hydrogen, Nitrogen)' : $param->nama_parameter;
                            @endphp

                            <div class="tab-pane fade {{ $isActive ? 'show active' : '' }}" 
                                 id="content-param-{{ $tabId }}" role="tabpanel">
                                
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                    <h5 class="fw-bold mb-0" style="color: #1b3152; font-size: 0.95rem;">Aturan Toleransi: {{ $tabTitle }}</h5>
                                    <button class="btn btn-sm btn-corporate-blue shadow-sm py-1 px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambah-{{ $tabId }}">
                                        <i class="fas fa-plus me-1"></i> Tambah Aturan
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm text-center align-middle table-corporate qc-table-min">
                                        <thead>
                                            <tr>
                                                @if(in_array($param->nama_parameter, ['IM', 'TM', 'RM']))
                                                    <th>Material / Kategori</th>
                                                @elseif(in_array($param->nama_parameter, ['ASH', 'Total Sulfur (%ad/db)']))
                                                    <th>Std Method</th>
                                                @elseif($isCHN)
                                                    <th>Std Method</th>
                                                    <th>Element</th>
                                                @elseif($param->nama_parameter == 'VM')
                                                    <th>Std Method</th>
                                                    <th>Type Sample</th>
                                                @else
                                                    <th>Std Method</th>
                                                    <th>Material / Kategori</th>
                                                @endif
                                                
                                                @if($param->nama_parameter !== 'VM')
                                                    <th>Range</th>
                                                @endif
                                                
                                                <th>Repeatability (r)</th>
                                                <th>Reproducibility (R)</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            @forelse($currentToleransis as $tol)
                                                <tr>
                                                    @if(in_array($param->nama_parameter, ['IM', 'TM', 'RM']))
                                                        <td class="fw-bold">{{ $tol->kategori_label ?? '-' }}</td>
                                                    @elseif(in_array($param->nama_parameter, ['ASH', 'Total Sulfur (%ad/db)']))
                                                        <td class="fw-bold">{{ $tol->metode ?? '-' }}</td>
                                                    @elseif($isCHN)
                                                        <td>{{ $tol->metode ?? '-' }}</td>
                                                        <td class="fw-bold text-primary">{{ $tol->sub_parameter ?? '-' }}</td>
                                                    @elseif($param->nama_parameter == 'VM')
                                                        <td>{{ $tol->metode ?? '-' }}</td>
                                                        <td class="fw-bold text-primary">{{ $tol->kategori_label ?? '-' }}</td>
                                                    @else
                                                        <td>{{ $tol->metode ?? '-' }}</td>
                                                        <td class="fw-bold">{{ $tol->kategori_label ?? '-' }}</td>
                                                    @endif
                                                    
                                                    @if($param->nama_parameter !== 'VM')
                                                        <td>{{ $tol->range_label ?? '-' }}</td>
                                                    @endif
                                                    
                                                    <td class="text-danger fw-bold">{{ $tol->formula_r }}</td>
                                                    <td class="text-success fw-bold">{{ $tol->formula_R_besar }}</td>
                                                    
                                                    <td class="text-nowrap">
                                                        <div class="d-inline-flex align-items-center gap-1">
                                                            <button type="button" class="btn btn-outline-primary btn-sm qc-action-btn" data-bs-toggle="modal" data-bs-target="#modalEdit-{{ $tol->id }}" title="Edit" aria-label="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            
                                                            <form action="{{ route('master.toleransi.destroy', $tol->id) }}" method="POST" onsubmit="return confirm('Hapus aturan ini?');">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger btn-sm qc-action-btn" title="Hapus" aria-label="Hapus"><i class="fas fa-trash"></i></button>
                                                            </form>
                                                        </div>

                                                        <div class="modal fade" id="modalEdit-{{ $tol->id }}" tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg text-start">
                                                                <form action="{{ route('master.toleransi.update', $tol->id) }}" method="POST">
                                                                    @csrf @method('PUT')
                                                                    <div class="modal-content text-start">
                                                                        <div class="modal-header bg-light">
                                                                            <h5 class="modal-title fw-bold text-dark">Edit Aturan: {{ $isCHN ? $tol->sub_parameter : $param->nama_parameter }}</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        
                                                                        <div class="modal-body">
                                                                            <div class="row mb-3 g-2">
                                                                                @if(in_array($param->nama_parameter, ['IM', 'TM', 'RM']))
                                                                                    <div class="col-md-12">
                                                                                        <label class="form-label small fw-bold">Material / Kategori</label>
                                                                                        <input type="text" class="form-control form-control-sm" name="kategori_label" value="{{ $tol->kategori_label }}">
                                                                                    </div>
                                                                                @elseif(in_array($param->nama_parameter, ['ASH', 'Total Sulfur (%ad/db)']))
                                                                                    <div class="col-md-12">
                                                                                        <label class="form-label small fw-bold">Std Method</label>
                                                                                        <input type="text" class="form-control form-control-sm" name="metode" value="{{ $tol->metode }}">
                                                                                    </div>
                                                                                @elseif($isCHN)
                                                                                    <div class="col-md-6">
                                                                                        <label class="form-label small fw-bold">Std Method</label>
                                                                                        <input type="text" class="form-control form-control-sm" name="metode" value="{{ $tol->metode }}">
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label class="form-label small fw-bold">Element</label>
                                                                                        <input type="text" class="form-control form-control-sm bg-light" name="sub_parameter" value="{{ $tol->sub_parameter }}" readonly title="Hapus dan buat aturan baru jika ingin mengganti elemen.">
                                                                                    </div>
                                                                                @elseif($param->nama_parameter == 'VM')
                                                                                    <div class="col-md-6">
                                                                                        <label class="form-label small fw-bold">Std Method</label>
                                                                                        <input type="text" class="form-control form-control-sm" name="metode" value="{{ $tol->metode }}">
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label class="form-label small fw-bold">Type Sample</label>
                                                                                        <input type="text" class="form-control form-control-sm" name="kategori_label" value="{{ $tol->kategori_label }}">
                                                                                    </div>
                                                                                @else
                                                                                    <div class="col-md-6">
                                                                                        <label class="form-label small fw-bold">Std Method</label>
                                                                                        <input type="text" class="form-control form-control-sm" name="metode" value="{{ $tol->metode }}">
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label class="form-label small fw-bold">Material / Kategori</label>
                                                                                        <input type="text" class="form-control form-control-sm" name="kategori_label" value="{{ $tol->kategori_label }}">
                                                                                    </div>
                                                                                @endif
                                                                            </div>

                                                                            @if($param->nama_parameter !== 'VM')
                                                                                <div class="row mb-3 g-2">
                                                                                    <div class="col-md-4">
                                                                                        <label class="form-label small fw-bold">Label Range (Teks)</label>
                                                                                        <input type="text" class="form-control form-control-sm" name="range_label" value="{{ $tol->range_label }}">
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label class="form-label small fw-bold">Range Min (Angka)</label>
                                                                                        <input type="number" step="any" class="form-control form-control-sm" name="range_min" value="{{ $tol->range_min }}">
                                                                                    </div>
                                                                                    <div class="col-md-4">
                                                                                        <label class="form-label small fw-bold">Range Max (Angka)</label>
                                                                                        <input type="number" step="any" class="form-control form-control-sm" name="range_max" value="{{ $tol->range_max }}">
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            <div class="row mb-3 g-2">
                                                                                <div class="col-md-6">
                                                                                    <label class="form-label small fw-bold text-danger">Formula Repeatability (r) *</label>
                                                                                    <input type="text" class="form-control form-control-sm" name="formula_r" value="{{ $tol->formula_r }}" required>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label class="form-label small fw-bold text-success">Formula Reproducibility (R) *</label>
                                                                                    <input type="text" class="form-control form-control-sm" name="formula_R_besar" value="{{ $tol->formula_R_besar }}" required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="modal-footer bg-light">
                                                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                                            <button type="submit" class="btn btn-corporate-blue btn-sm"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="{{ $param->nama_parameter == 'VM' ? '5' : (in_array($param->nama_parameter, ['IM', 'TM', 'RM', 'ASH', 'Total Sulfur (%ad/db)']) ? '5' : '6') }}" class="text-muted py-3">
                                                        Belum ada batas toleransi untuk parameter ini.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="modal fade" id="modalTambah-{{ $tabId }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <form action="{{ route('master.toleransi.store') }}" method="POST">
                                        @csrf

                                        @if($isCHN)
                                            <input type="hidden" name="parameter_uji_id" id="chn_param_id">
                                            <input type="hidden" name="sub_parameter" id="chn_sub_param">
                                        @else
                                            <input type="hidden" name="parameter_uji_id" value="{{ $param->parameter_uji_id }}">
                                        @endif
                                        
                                        <div class="modal-content text-start">
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title fw-bold text-dark">Tambah Aturan: {{ $tabTitle }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            
                                            <div class="modal-body">
                                                <div class="row mb-3 g-2">
                                                    @if(in_array($param->nama_parameter, ['IM', 'TM', 'RM']))
                                                        <div class="col-md-12">
                                                            <label class="form-label small fw-bold">Material / Kategori</label>
                                                            <input type="text" class="form-control form-control-sm" name="kategori_label" placeholder="Contoh: Coal, Coke" required>
                                                        </div>
                                                    @elseif(in_array($param->nama_parameter, ['ASH', 'Total Sulfur (%ad/db)']))
                                                        <div class="col-md-12">
                                                            <label class="form-label small fw-bold">Std Method</label>
                                                            <input type="text" class="form-control form-control-sm" name="metode" placeholder="Contoh: ASTM (db)" required>
                                                        </div>
                                                    @elseif($isCHN)
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Std Method</label>
                                                            <input type="text" class="form-control form-control-sm" name="metode" placeholder="Contoh: ASTM">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Element</label>
                                                            <select class="form-select form-select-sm" required
                                                                    onchange="document.getElementById('chn_param_id').value = this.options[this.selectedIndex].getAttribute('data-id'); document.getElementById('chn_sub_param').value = this.value;">
                                                                <option value="">-- Pilih Element --</option>
                                                                @foreach($chnParams as $chn)
                                                                    @php $elName = $chn->nama_parameter == 'C' ? 'Carbon' : ($chn->nama_parameter == 'H' ? 'Hydrogen' : 'Nitrogen'); @endphp
                                                                    <option value="{{ $elName }}" data-id="{{ $chn->parameter_uji_id }}">{{ $elName }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @elseif($param->nama_parameter == 'VM')
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Std Method</label>
                                                            <input type="text" class="form-control form-control-sm" name="metode" placeholder="Contoh: ASTM">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Type Sample</label>
                                                            <input type="text" class="form-control form-control-sm" name="kategori_label" placeholder="Contoh: Bituminus">
                                                        </div>
                                                    @else
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Std Method</label>
                                                            <input type="text" class="form-control form-control-sm" name="metode" placeholder="Contoh: ASTM">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label small fw-bold">Material / Kategori</label>
                                                            <input type="text" class="form-control form-control-sm" name="kategori_label" placeholder="Contoh: Bituminus (db)">
                                                        </div>
                                                    @endif
                                                </div>

                                                @if($param->nama_parameter !== 'VM')
                                                    <div class="row mb-3 g-2">
                                                        <div class="col-md-4">
                                                            <label class="form-label small fw-bold">Label Range (Teks)</label>
                                                            <input type="text" class="form-control form-control-sm" name="range_label" placeholder="Contoh: 1.0 - 21.9%">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label small fw-bold">Range Min (Angka)</label>
                                                            <input type="number" step="any" class="form-control form-control-sm" name="range_min" placeholder="1.0">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label small fw-bold">Range Max (Angka)</label>
                                                            <input type="number" step="any" class="form-control form-control-sm" name="range_max" placeholder="21.9">
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="row mb-3 g-2">
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold text-danger">Formula Repeatability (r) *</label>
                                                        <input type="text" class="form-control form-control-sm" name="formula_r" placeholder="Contoh: 0.09 + 0.01 * X" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label small fw-bold text-success">Formula Reproducibility (R) *</label>
                                                        <input type="text" class="form-control form-control-sm" name="formula_R_besar" placeholder="Contoh: 0.23 + 0.02 * X" required>
                                                    </div>
                                                </div>

                                                <div class="mb-2">
                                                    <label class="form-label small fw-bold">Catatan / Keterangan (Opsional)</label>
                                                    <input type="text" class="form-control form-control-sm" name="catatan" placeholder="Contoh: where X is average of two test results">
                                                </div>
                                            </div>
                                            
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-corporate-blue btn-sm"><i class="fas fa-save me-1"></i> Simpan Aturan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection