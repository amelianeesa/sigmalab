@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 pb-5">
    
    <x-qc-breadcrumb active="Uji Banding">
        <li class="breadcrumb-item active" aria-current="page">Master Batas Toleransi</li>
    </x-qc-breadcrumb>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">
            <i class="fas fa-balance-scale text-primary me-2"></i>Master Batas Toleransi QC Uji Banding
        </h2>
    </div>

    @php
        // 1. Ekstrak data khusus C, H, N untuk digabung
        $chnParams = $parameters->filter(fn($p) => in_array($p->nama_parameter, ['C', 'H', 'N']));
        $chnIds = $chnParams->pluck('parameter_uji_id')->toArray();
        $chnToleransis = $chnParams->pluck('toleransis')->flatten();
        
        // 2. Urutan Modul
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

        // 3. Tentukan Active Tab (Termasuk deteksi jika yang aktif adalah salah satu dari CHN)
        $firstParamId = $groupedParameters->first() ? $groupedParameters->first()->first()->parameter_uji_id : null;
        $activeTab = session('active_tab', $firstParamId);
        $isChnActive = in_array($activeTab, $chnIds);
    @endphp

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <!-- SISI KIRI: Daftar Modul & Parameter -->
                <div class="col-md-3 border-end">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        @foreach($groupedParameters as $kategori => $params)
                            
                            <div class="fw-bold text-uppercase text-secondary small mt-3 mb-1 px-2" style="letter-spacing: 0.5px;">
                                <i class="fas fa-folder-open me-1"></i> {{ $kategori ?: 'Parameter Lainnya' }}
                            </div>
                            
                            @foreach($params as $param)
                                <!-- Sembunyikan tab H dan N, biarkan C yang menjadi wakil tab Gabungan -->
                                @if(in_array($param->nama_parameter, ['H', 'N']))
                                    @continue
                                @endif
                                
                                @php 
                                    $isCHN = $param->nama_parameter == 'C';
                                    $tabId = $isCHN ? 'chn' : $param->parameter_uji_id;
                                    $isActive = $isCHN ? $isChnActive : ($param->parameter_uji_id == $activeTab);
                                    $tabLabel = $isCHN ? 'CHN' : $param->nama_parameter;
                                @endphp

                                <button class="nav-link text-start ms-2 {{ $isActive ? 'active' : '' }}" 
                                        id="tab-param-{{ $tabId }}" 
                                        data-bs-toggle="pill" 
                                        data-bs-target="#content-param-{{ $tabId }}" 
                                        type="button" role="tab">
                                    {{ $tabLabel }}
                                </button>
                            @endforeach

                        @endforeach
                    </div>
                </div>

                <!-- SISI KANAN: Konten Tabel & Modal -->
                <div class="col-md-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        
                        @foreach($parameters as $param)
                            <!-- Sama seperti sidebar, lewati loop H dan N -->
                            @if(in_array($param->nama_parameter, ['H', 'N']))
                                @continue
                            @endif

                            @php 
                                $isCHN = $param->nama_parameter == 'C';
                                $tabId = $isCHN ? 'chn' : $param->parameter_uji_id;
                                $isActive = $isCHN ? $isChnActive : ($param->parameter_uji_id == $activeTab);
                                // Gunakan data gabungan jika ini tab CHN
                                $currentToleransis = $isCHN ? $chnToleransis : $param->toleransis;
                                $tabTitle = $isCHN ? 'CHN (Carbon, Hydrogen, Nitrogen)' : $param->nama_parameter;
                            @endphp

                            <div class="tab-pane fade {{ $isActive ? 'show active' : '' }}" 
                                 id="content-param-{{ $tabId }}" role="tabpanel">
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-primary fw-bold">Aturan Toleransi: {{ $tabTitle }}</h5>
                                    <button class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah-{{ $tabId }}">
                                        <i class="fas fa-plus me-1"></i> Tambah Aturan
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm text-center align-middle">
                                        <thead class="table-light">
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
                                            <!-- Loop menggunakan $currentToleransis agar CHN tampil semua -->
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
                                                    
                                                    <td class="d-flex justify-content-center gap-1">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEdit-{{ $tol->id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        
                                                        <form action="{{ route('master.toleransi.destroy', $tol->id) }}" method="POST" onsubmit="return confirm('Hapus aturan ini?');">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                        </form>

                                                        <!-- MODAL EDIT -->
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
                                                                            <div class="row mb-3">
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
                                                                                        <!-- Readonly saat edit agar ID parameter tidak inkonsisten -->
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
                                                                                <div class="row mb-3">
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

                                                                            <div class="row mb-3">
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
                                                                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
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

                            <!-- MODAL TAMBAH ATURAN -->
                            <div class="modal fade" id="modalTambah-{{ $tabId }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <form action="{{ route('master.toleransi.store') }}" method="POST">
                                        @csrf
                                        
                                        <!-- Penanganan ID Parameter Khusus CHN -->
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
                                                <div class="row mb-3">
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
                                                            <!-- Dropdown CHN yang langsung mengisi ID parameter di background -->
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
                                                    <div class="row mb-3">
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

                                                <div class="row mb-3">
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
                                                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> Simpan Aturan</button>
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