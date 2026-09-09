@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="In-House">
        <li class="breadcrumb-item active">Tahap 1: Pemilihan Sampel</li>
    </x-qc-breadcrumb>

    <div class="row mt-3">
        <div class="col-xl-9 col-lg-10">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-5">
                    <h3 class="fw-bold text-dark mb-1"><i class="fas fa-clipboard-list text-primary me-2"></i>Tahap 1: Screening & Pemilihan Sampel</h3>
                    <p class="text-muted mb-4">Masukkan spesifikasi kandidat sampel standar batubara. Sistem akan memvalidasi kesesuaian rentang komoditas dan hukum fisika proksimat sebelum otomatis memaketkan 5 parameter uji (MAD, Ash, VM, TS, GCV).</p>
                    
                    @if($errors->any())
                        <div class="alert alert-danger mb-4 rounded-3">
                            <ul class="mb-0">
                                @foreach($errors->all() as $err)
                                    <li><i class="fas fa-exclamation-triangle me-1"></i> {{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('qc-inhouse.store') }}" method="POST" id="formPemilihan">
                        @csrf
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Sampel <span class="text-danger">*</span></label>
                                <input type="text" name="nama_sampel" class="form-control" value="{{ old('nama_sampel') }}" placeholder="Contoh: Coal A Agustus" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Jenis Batubara <span class="text-danger">*</span></label>
                                <select name="jenis_batubara" class="form-select" id="jenis_batubara" required>
                                    <option value="">-- Pilih Jenis Batubara --</option>
                                    <option value="lignite" {{ old('jenis_batubara') == 'lignite' ? 'selected' : '' }}>Lignite</option>
                                    <option value="sub_bituminous" {{ old('jenis_batubara') == 'sub_bituminous' ? 'selected' : '' }}>Sub Bituminous</option>
                                    <option value="bituminous" {{ old('jenis_batubara') == 'bituminous' ? 'selected' : '' }}>Bituminous</option>
                                    <option value="anthracite" {{ old('jenis_batubara') == 'anthracite' ? 'selected' : '' }}>Anthracite</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-5 mb-3 border-bottom pb-2">
                            <h5 class="fw-bold text-dark"><i class="fas fa-flask text-info me-2"></i>Data Analisis Pemilihan (Screening Kasar)</h5>
                            <p class="small text-muted mb-0">Hukum Fisika: TM harus > MAD. Total Proksimat (MAD + Ash + VM) harus &lt; 100%.</p>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-secondary">Total Moisture (TM) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="tm" id="tm" class="form-control" value="{{ old('tm') }}" required>
                                    <span class="input-group-text bg-light">% AR</span>
                                </div>
                                <div class="small text-muted mt-1"><i class="fas fa-info-circle"></i> <span id="limit_tm" class="fw-bold text-info">Pilih Jenis Batubara</span></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-secondary">Moisture in Analysis (MAD) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="mad" id="mad" class="form-control" value="{{ old('mad') }}" required>
                                    <span class="input-group-text bg-light">% ADB</span>
                                </div>
                                <div class="small text-muted mt-1"><i class="fas fa-info-circle"></i> <span id="limit_mad" class="fw-bold text-info">Pilih Jenis Batubara</span></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-secondary">Ash Content <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="ash" id="ash" class="form-control" value="{{ old('ash') }}" required>
                                    <span class="input-group-text bg-light">% ADB</span>
                                </div>
                                <div class="small text-muted mt-1"><i class="fas fa-info-circle"></i> <span id="limit_ash" class="fw-bold text-info">Pilih Jenis Batubara</span></div>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-secondary">Volatile Matter (VM) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="vm" id="vm" class="form-control" value="{{ old('vm') }}" required>
                                    <span class="input-group-text bg-light">% ADB</span>
                                </div>
                                <div class="small text-muted mt-1"><i class="fas fa-info-circle"></i> <span id="limit_vm" class="fw-bold text-info">Pilih Jenis Batubara</span></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-secondary">Total Sulfur (TS) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="ts" id="ts" class="form-control" value="{{ old('ts') }}" required>
                                    <span class="input-group-text bg-light">% ADB</span>
                                </div>
                                <div class="small text-muted mt-1"><i class="fas fa-info-circle"></i> <span id="limit_ts" class="fw-bold text-info">Pilih Jenis Batubara</span></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-secondary">Gross Calorific Value (GCV) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="1" name="gcv" id="gcv" class="form-control" value="{{ old('gcv') }}" required>
                                    <span class="input-group-text bg-light">Kcal/kg</span>
                                </div>
                                <div class="small text-muted mt-1"><i class="fas fa-info-circle"></i> <span id="limit_gcv" class="fw-bold text-info">Pilih Jenis Batubara</span></div>
                            </div>
                        </div>

                        <!-- Physics Auto-Calc Display -->
                        <div class="mt-4 p-4 bg-light border rounded">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="fw-bold mb-1">Fixed Carbon (FC) Kalkulasi Keseimbangan</h6>
                                    <p class="small text-muted mb-0">Dihitung otomatis: 100 - (MAD + Ash + VM)</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <h3 class="fw-bold text-success mb-0" id="fc_display">- %</h3>
                                </div>
                            </div>
                            <div id="physics_warning" class="alert alert-danger mt-3 mb-0 d-none">
                                <i class="fas fa-ban me-1"></i> <span id="physics_warning_text"></span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-5">
                            <button type="submit" class="btn btn-primary px-5 btn-lg shadow-sm" id="btnSubmit">
                                <i class="fas fa-save me-2"></i> Simpan & Lanjut ke Preparasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Info -->
        <div class="col-xl-3 col-lg-2">
            <div class="card shadow-sm border-0 bg-info bg-opacity-10">
                <div class="card-body">
                    <h6 class="fw-bold text-info"><i class="fas fa-info-circle me-1"></i> Informasi SOP</h6>
                    <p class="small text-muted mb-2">Pada Tahap 1, batubara akan melalui <strong>Screening Kasar</strong>.</p>
                    <p class="small text-muted mb-2">Jika lolos rentang komoditas, sistem akan otomatis menetapkan batubara ini sebagai sampel <strong>General Analysis (GA)</strong> dan memaketkan 5 parameter berikut untuk Uji Homogenitas:</p>
                    <ul class="small text-dark fw-bold mb-0 ps-3">
                        <li>MAD</li>
                        <li>Ash Content</li>
                        <li>Volatile Matter (VM)</li>
                        <li>Total Sulfur (TS)</li>
                        <li>Gross Calorific Value (GCV)</li>
                    </ul>
                    <p class="small text-danger mt-3 mb-0"><strong>Note:</strong> Total Moisture (TM) dieliminasi dari pengujian selanjutnya.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rentangBaku = @json($rentangBaku ?? []);

    const jenisDropdown = document.getElementById('jenis_batubara');
    const limitTm = document.getElementById('limit_tm');
    const limitMad = document.getElementById('limit_mad');
    const limitAsh = document.getElementById('limit_ash');
    const limitVm = document.getElementById('limit_vm');
    const limitTs = document.getElementById('limit_ts');
    const limitGcv = document.getElementById('limit_gcv');

    const tm = document.getElementById('tm');
    const mad = document.getElementById('mad');
    const ash = document.getElementById('ash');
    const vm = document.getElementById('vm');
    const fcDisplay = document.getElementById('fc_display');
    const btnSubmit = document.getElementById('btnSubmit');
    const warningBox = document.getElementById('physics_warning');
    const warningText = document.getElementById('physics_warning_text');

    function formatLimit(obj) {
        if (!obj) return 'Tidak ada standar';
        if (obj.min === null && obj.max === null) return 'Rentang bebas (Tidak dibatasi)';
        if (obj.min === null) return 'Batas Maks: ' + obj.max;
        if (obj.max === null) return 'Batas Min: ' + obj.min;
        return 'Batas: ' + obj.min + ' s.d ' + obj.max;
    }

    function updateLimits() {
        let jenis = jenisDropdown.value;
        if (!jenis || !rentangBaku[jenis]) {
            let msg = 'Pilih Jenis Batubara';
            limitTm.textContent = msg;
            limitMad.textContent = msg;
            limitAsh.textContent = msg;
            limitVm.textContent = msg;
            limitTs.textContent = msg;
            limitGcv.textContent = msg;
        } else {
            let limits = rentangBaku[jenis];
            limitTm.textContent = formatLimit(limits.tm);
            limitMad.textContent = formatLimit(limits.mad);
            limitAsh.textContent = formatLimit(limits.ash);
            limitVm.textContent = formatLimit(limits.vm);
            limitTs.textContent = formatLimit(limits.ts);
            limitGcv.textContent = formatLimit(limits.gcv);
        }
    }

    function checkPhysics() {
        let valTm = parseFloat(tm.value);
        let valMad = parseFloat(mad.value);
        let valAsh = parseFloat(ash.value);
        let valVm = parseFloat(vm.value);
        
        let hasError = false;
        let errMsg = '';

        // Check 1: TM > MAD
        if (!isNaN(valTm) && !isNaN(valMad)) {
            if (valTm <= valMad) {
                hasError = true;
                errMsg = 'Hukum Fisika Gagal: TM harus lebih besar dari MAD.';
            }
        }

        // Check 2: Proximate Balance
        if (!isNaN(valMad) && !isNaN(valAsh) && !isNaN(valVm)) {
            let total = valMad + valAsh + valVm;
            let fc = 100 - total;
            
            fcDisplay.textContent = fc.toFixed(2) + ' %';
            
            if (fc <= 0) {
                hasError = true;
                errMsg = 'Hukum Fisika Gagal: Total MAD + Ash + VM melebihi 100%. Fixed Carbon tidak boleh negatif/nol.';
                fcDisplay.className = 'fw-bold text-danger mb-0';
            } else {
                fcDisplay.className = 'fw-bold text-success mb-0';
            }
        } else {
            fcDisplay.textContent = '- %';
            fcDisplay.className = 'fw-bold text-success mb-0';
        }

        // UI Update
        if (hasError) {
            warningBox.classList.remove('d-none');
            warningText.textContent = errMsg;
            btnSubmit.disabled = true;
        } else {
            warningBox.classList.add('d-none');
            btnSubmit.disabled = false;
        }
    }

    jenisDropdown.addEventListener('change', updateLimits);
    tm.addEventListener('input', checkPhysics);
    mad.addEventListener('input', checkPhysics);
    ash.addEventListener('input', checkPhysics);
    vm.addEventListener('input', checkPhysics);

    // Initial check (in case of old input on error redirect)
    updateLimits();
    checkPhysics();
});
</script>
@endsection
