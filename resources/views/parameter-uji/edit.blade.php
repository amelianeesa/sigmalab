@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-1 mt-3">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
        <li class="breadcrumb-item"><a href="{{ route('parameter-uji.index') }}" class="text-decoration-none">Parameter Uji</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    <h1 class="mb-4">Edit Parameter Uji</h1>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-edit me-2"></i>Form Edit Parameter Uji</h6>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('parameter-uji.update', $parameterUji->parameter_uji_id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nama Parameter <span class="text-danger">*</span></label>
                        <input type="text" name="nama_parameter" class="form-control" value="{{ old('nama_parameter', $parameterUji->nama_parameter) }}" required maxlength="50">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="satuan" class="form-control" value="{{ old('satuan', $parameterUji->satuan) }}" required maxlength="20">
                    </div>
                </div>

                

                                <ul class="nav nav-tabs mb-4" id="configTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active fw-bold" id="inhouse-tab" data-bs-toggle="tab" href="#inhouse" role="tab">In-House Control & Pengaturan Umum</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" id="crm-tab" data-bs-toggle="tab" href="#crm" role="tab">Sertifikat Pabrik (CRM)</a>
                    </li>
                </ul>
                <div class="tab-content border-start border-end border-bottom p-4 mb-4" id="configTabsContent" style="margin-top: -25px; background: white;">
                    <div class="tab-pane fade show active" id="inhouse" role="tabpanel">

                <div class="card bg-light mb-3 border-0">
                    <div class="card-body py-2">
                        <p class="mb-2 text-muted fw-bold" style="font-size: 0.85rem;"><i class="fas fa-chart-line"></i> Input Data Statistik (In-House)</p>
                        <div class="alert alert-info py-1 px-2 mb-2 d-flex justify-content-between align-items-center" style="font-size: 0.8rem;">
                            <span>Sistem dapat menghitung <strong>Mean (Rata-rata)</strong> dan <strong>SD (Standar Deviasi)</strong> secara otomatis dari kumpulan baris data histori pengujian (setara dengan rumus <code>AVERAGE</code> dan <code>STDEV</code> di Excel).</span>
                                                        <button type="button" class="btn btn-sm btn-primary py-0" id="btnCalculateStats">
                                <i class="fas fa-magic"></i> Hitung dari Histori
                            </button>
                            <button type="button" class="btn btn-sm btn-warning py-0 text-dark fw-bold ms-2" id="btnUseCrm">
                                <i class="fas fa-certificate"></i> Gunakan Nilai CRM
                            </button>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-primary" style="font-size: 0.85rem;">Mean (Rata-rata)</label>
                                <input type="number" step="0.0001" name="mean" id="inputMean" class="form-control" value="{{ old('mean', $parameterUji->mean ? number_format($parameterUji->mean, 4, '.', '') : '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-primary" style="font-size: 0.85rem;">SD (Standard Deviation)</label>
                                <input type="number" step="0.0001" name="sd" id="inputSd" class="form-control" value="{{ old('sd', $parameterUji->sd ? number_format($parameterUji->sd, 4, '.', '') : '') }}">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col">
                                <label class="form-label text-muted" style="font-size: 0.75rem;">LCL (-3 SD)</label>
                                <input type="text" id="calcLcl" name="lcl" class="form-control form-control-sm bg-white" readonly value="{{ old('lcl', $parameterUji->lcl ? number_format($parameterUji->lcl, 4, '.', '') : '') }}">
                            </div>
                            <div class="col">
                                <label class="form-label text-muted" style="font-size: 0.75rem;">LWL (-2 SD)</label>
                                <input type="text" id="calcUwlBawah" name="uwl_bawah" class="form-control form-control-sm bg-white" readonly value="{{ old('uwl_bawah', $parameterUji->uwl_bawah ? number_format($parameterUji->uwl_bawah, 4, '.', '') : '') }}">
                            </div>
                            <div class="col">
                                <label class="form-label text-muted" style="font-size: 0.75rem;">UWL (+2 SD)</label>
                                <input type="text" id="calcUwlAtas" name="uwl_atas" class="form-control form-control-sm bg-white" readonly value="{{ old('uwl_atas', $parameterUji->uwl_atas ? number_format($parameterUji->uwl_atas, 4, '.', '') : '') }}">
                            </div>
                            <div class="col">
                                <label class="form-label text-muted" style="font-size: 0.75rem;">UCL (+3 SD)</label>
                                <input type="text" id="calcUcl" name="ucl" class="form-control form-control-sm bg-white" readonly value="{{ old('ucl', $parameterUji->ucl ? number_format($parameterUji->ucl, 4, '.', '') : '') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Nilai Acuan <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" name="nilai_acuan" id="inputAcuan" class="form-control" value="{{ old('nilai_acuan', number_format($parameterUji->nilai_acuan, 4, '.', '')) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Batas Bawah <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" name="batas_bawah" id="inputBatasBawah" class="form-control" value="{{ old('batas_bawah', number_format($parameterUji->batas_bawah, 4, '.', '')) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Batas Atas <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" name="batas_atas" id="inputBatasAtas" class="form-control" value="{{ old('batas_atas', number_format($parameterUji->batas_atas, 4, '.', '')) }}" required>
                    </div>
                </div>

                <script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if there is a hash in the URL and switch tab
    if(window.location.hash) {
        var hash = window.location.hash;
        // Search for either button or a tag with href or data-bs-target matching the hash
        var tabTrigger = document.querySelector('[data-bs-target="' + hash + '"]') || document.querySelector('[href="' + hash + '"]');
        if (tabTrigger) {
            var tab = new bootstrap.Tab(tabTrigger);
            tab.show();
        }
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-delete-crm').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const lot = this.dataset.lot;
            if (confirm(`Apakah Anda yakin ingin menghapus botol CRM Lot ${lot} ini? Penghapusan ini bersifat permanen dan akan menghapus nilai sertifikatnya di semua parameter.`)) {
                fetch(`/parameter-uji/crm-katalog/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Botol CRM berhasil dihapus.');
                        window.location.reload();
                    } else {
                        alert('Gagal menghapus: ' + data.message);
                    }
                });
            }
        });
    });
                        const inputMean = document.getElementById('inputMean');
                        const inputSd = document.getElementById('inputSd');
                        const calcLcl = document.getElementById('calcLcl');
                        const calcUwlBawah = document.getElementById('calcUwlBawah');
                        const calcUwlAtas = document.getElementById('calcUwlAtas');
                        const calcUcl = document.getElementById('calcUcl');
                        
                        const inputAcuan = document.getElementById('inputAcuan');
                        const inputBatasBawah = document.getElementById('inputBatasBawah');
                        const inputBatasAtas = document.getElementById('inputBatasAtas');
                        
                        

                                                const btnCalculateStats = document.getElementById('btnCalculateStats');
                        
                        function calculateLimits() {
                            const mean = parseFloat(inputMean.value) || 0;
                            const sd = parseFloat(inputSd.value) || 0;
                            
                            if (calcLcl) calcLcl.textContent = (mean - 3 * sd).toFixed(4);
                            if (calcUwlBawah) calcUwlBawah.textContent = (mean - 2 * sd).toFixed(4);
                            if (calcUwlAtas) calcUwlAtas.textContent = (mean + 2 * sd).toFixed(4);
                            if (calcUcl) calcUcl.textContent = (mean + 3 * sd).toFixed(4);

                            if (mean !== 0) {
                                inputAcuan.value = mean.toFixed(4);
                                inputBatasBawah.value = (mean - 3 * sd).toFixed(4);
                                inputBatasAtas.value = (mean + 3 * sd).toFixed(4);
                            }
                        }
                        
                        inputMean.addEventListener('input', calculateLimits);
                        inputSd.addEventListener('input', calculateLimits);

                        const btnUseCrm = document.getElementById('btnUseCrm');
                        if (btnUseCrm) {
                            btnUseCrm.addEventListener('click', function() {
                                const certVals = document.querySelectorAll('input[name*="[cert_value]"]');
                                const certUs = document.querySelectorAll('input[name*="[cert_u]"]');
                                let found = false;
                                for (let i = 0; i < certVals.length; i++) {
                                    const val = parseFloat(certVals[i].value);
                                    const u = parseFloat(certUs[i].value) || 0;
                                    if (!isNaN(val)) {
                                        inputAcuan.value = val.toFixed(4);
                                        inputBatasBawah.value = (val - u).toFixed(4);
                                        inputBatasAtas.value = (val + u).toFixed(4);
                                        
                                        // Also clear mean and SD since we are using CRM
                                        inputMean.value = 0;
                                        inputSd.value = 0;
                                        if (calcLcl) {
                                            calcLcl.textContent = '0';
                                            calcUwlBawah.textContent = '0';
                                            calcUwlAtas.textContent = '0';
                                            calcUcl.textContent = '0';
                                        }
                                        
                                        alert('Berhasil! Nilai Acuan, Batas Bawah, dan Batas Atas telah diisi otomatis menggunakan nilai Sertifikat CRM.');
                                        found = true;
                                        break;
                                    }
                                }
                                if (!found) {
                                    alert('Tidak ada nilai Sertifikat CRM yang terisi. Silakan isi nilai sertifikat di tab CRM terlebih dahulu.');
                                }
                            });
                        }
                        if (btnCalculateStats) {
                            btnCalculateStats.addEventListener('click', function() {
                                const originalText = this.innerHTML;
                                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghitung...';
                                this.disabled = true;

                                fetch(`{{ route('parameter-uji.calculate-stats', $parameterUji->parameter_uji_id) }}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.count < 2) {
                                            alert('Data histori kurang dari 2. Tidak bisa menghitung Standar Deviasi.');
                                        } else {
                                            inputMean.value = data.mean;
                                            inputSd.value = data.sd;
                                            calculateLimits();
                                            alert(`Berhasil dihitung dari ${data.count} data histori Inhouse Control.`);
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Terjadi kesalahan saat menghitung histori.');
                                    })
                                    .finally(() => {
                                        this.innerHTML = originalText;
                                        this.disabled = false;
                                    });
                            });
                        }
                        // Langkah Kalkulasi Dynamic Form
                        const tableBody = document.querySelector('#langkahTable tbody');
                        const btnAdd = document.getElementById('btnAddLangkah');
                        let stepCount = {{ is_array(old('langkah_kalkulasi', $parameterUji->langkah_kalkulasi ?? [])) ? count(old('langkah_kalkulasi', $parameterUji->langkah_kalkulasi ?? [])) : 0 }};

                        btnAdd.addEventListener('click', function() {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td><input type="text" name="langkah_kalkulasi[${stepCount}][var]" class="form-control form-control-sm" placeholder="Contoh: M2_D1"></td>
                                <td><input type="text" name="langkah_kalkulasi[${stepCount}][rumus]" class="form-control form-control-sm" placeholder="Contoh: M1_D1 + A_D1"></td>
                                <td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-remove-langkah"><i class="fas fa-trash"></i></button></td>
                            `;
                            tableBody.appendChild(tr);
                            stepCount++;
                        });

                        if(tableBody) {
                            tableBody.addEventListener('click', function(e) {
                                if (e.target.closest('.btn-remove-langkah')) {
                                    e.target.closest('tr').remove();
                                }
                            });
                        }
                    });
                </script>

                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Metode / Kriteria</label>
                        <input type="text" name="metode_kriteria" class="form-control" value="{{ old('metode_kriteria', $parameterUji->metode_kriteria) }}" maxlength="50">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Rumus Kalkulasi Nilai Akhir</label>
                        <div class="input-group">
                            <input type="text" name="rumus_kalkulasi" class="form-control" value="{{ old('rumus_kalkulasi', $parameterUji->rumus_kalkulasi) }}" placeholder="Contoh: (M1 - M2) / M3 * 100">
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalLangkahKalkulasi">
                                <i class="fas fa-list-ol"></i> Detail Rumus
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1">Gunakan nama variabel input (contoh: <code>M1</code>, <code>M2</code>, <code>Avg_C</code>).</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-danger">Formula Toleransi Duplo</label>
                        <input type="text" name="toleransi_duplo" class="form-control" value="{{ old('toleransi_duplo', $parameterUji->toleransi_duplo) }}" placeholder="Contoh: 0.09 + (0.1 * Avg_M)">
                        <small class="text-muted d-block mt-1">Gunakan angka statis (contoh: <code>1.5</code>) atau formula matematika.</small>
                    </div>
                </div>

                <!-- Modal Langkah Kalkulasi -->
                <div class="modal fade" id="modalLangkahKalkulasi" tabindex="-1" aria-labelledby="modalLangkahKalkulasiLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="modalLangkahKalkulasiLabel"><i class="fas fa-list-ol me-2"></i> Detail Langkah Kalkulasi Per Kolom</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted small">Jika Anda mengisi langkah kalkulasi di bawah ini, mesin akan menghitung variabel secara berurutan sebelum mencapai Nilai Akhir. Sangat berguna untuk perhitungan yang bertahap (multi-step).</p>
                                <div class="table-responsive mb-2">
                                    <table class="table table-bordered table-sm" id="langkahTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="35%">Nama Variabel Output</th>
                                                <th width="55%">Rumus Matematika</th>
                                                <th width="10%" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $langkahs = old('langkah_kalkulasi', $parameterUji->langkah_kalkulasi ?? []);
                                            @endphp
                                            @if(is_array($langkahs) && count($langkahs) > 0)
                                                @foreach($langkahs as $index => $langkah)
                                                <tr>
                                                    <td><input type="text" name="langkah_kalkulasi[{{$index}}][var]" class="form-control form-control-sm" value="{{ $langkah['var'] ?? '' }}" placeholder="Contoh: M2_D1"></td>
                                                    <td><input type="text" name="langkah_kalkulasi[{{$index}}][rumus]" class="form-control form-control-sm" value="{{ $langkah['rumus'] ?? '' }}" placeholder="Contoh: M1_D1 + A_D1"></td>
                                                    <td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-remove-langkah"><i class="fas fa-trash"></i></button></td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-sm btn-success" id="btnAddLangkah"><i class="fas fa-plus"></i> Tambah Baris Rumus</button>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Status Aktif</label>
                        <select name="status_aktif" class="form-select">
                            <option value="1" {{ old('status_aktif', $parameterUji->status_aktif) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status_aktif', $parameterUji->status_aktif) == 0 ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                                    </div> <!-- end inhouse tab -->
                    
                    <div class="tab-pane fade" id="crm" role="tabpanel">
                        <p class="mb-3 text-muted fw-bold" style="font-size: 0.85rem;"><i class="fas fa-certificate"></i> Pengaturan Nilai Sertifikat per Lot CRM</p>
                        <div class="alert alert-warning py-1 px-2 mb-3 d-flex justify-content-between align-items-center" style="font-size: 0.8rem;">
                            <span>Anda dapat mengisi nilai Sertifikat (Cert Value) dan Uncertainty (U) untuk masing-masing kode Lot CRM yang saat ini aktif. Nilai ini akan digunakan untuk mengevaluasi akurasi hasil uji.</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="m-0 font-weight-bold text-secondary">Daftar Botol / Lot CRM</h6>
                            <button type="button" class="btn btn-sm btn-info text-white fw-bold" data-bs-toggle="modal" data-bs-target="#modalAddCrm">
                                <i class="fas fa-plus"></i> Tambah Lot CRM
                            </button>
                        </div>
                        
                        @if(isset($katalogCrmList) && $katalogCrmList->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kode Lot CRM</th>
                                            <th>Produsen / Jenis</th>
                                            <th class="text-center" style="width: 25%">Nilai Sertifikat (Cert Value)</th>
                                            <th class="text-center" style="width: 25%">Uncertainty (U)</th>
                                            <th class="text-center" style="width: 10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($katalogCrmList as $katalog)
                                            @php
                                                $sert = $parameterUji->sertifikatCrm->where('crm_katalog_id', $katalog->id)->first();
                                            @endphp
                                            <tr>
                                                <td class="align-middle fw-bold">{{ $katalog->nomor_lot }}</td>
                                                <td class="align-middle">{{ $katalog->produsen ? $katalog->produsen . " - " : "" }}{{ $katalog->nama_produk }}</td>
                                                <td>
                                                    <input type="number" step="0.0001" name="crm_sertifikat[{{ $katalog->id }}][cert_value]" class="form-control form-control-sm" value="{{ $sert ? number_format($sert->cert_value, 4, '.', '') : '' }}" placeholder="Contoh: 9.5000">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.0001" name="crm_sertifikat[{{ $katalog->id }}][cert_u]" class="form-control form-control-sm" value="{{ $sert && $sert->cert_u ? number_format($sert->cert_u, 4, '.', '') : '' }}" placeholder="Contoh: 0.0500">
                                                  </td>
                                                  <td class="text-center align-middle">
                                                      <button type="button" class="btn btn-sm btn-outline-danger btn-delete-crm" data-id="{{ $katalog->id }}" data-lot="{{ $katalog->nomor_lot }}">
                                                          <i class="fas fa-trash"></i>
                                                      </button>
                                                  </td>
                                              </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div> <!-- end crm tab -->
                </div> <!-- end tab content -->

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('parameter-uji.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Tambah CRM -->
<div class="modal fade" id="modalAddCrm" tabindex="-1" aria-labelledby="modalAddCrmLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="modalAddCrmLabel"><i class="fas fa-certificate"></i> Tambah Lot CRM Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formAddCrm">
          <input type="hidden" id="crm_parameter_uji_id" value="{{ $parameterUji->parameter_uji_id }}">
          
          <div class="mb-3">
            <label class="form-label fw-bold">Nomor Lot / Kode Sampel <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="crm_nomor_lot" required>
          </div>
          
          <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Produsen / Brand</label>
                <input type="text" class="form-control" id="crm_produsen" placeholder="Contoh: Alpha Resources">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Jenis / Tipe Material <span class="text-danger">*</span></label>
                <select class="form-select" id="crm_nama_produk" required>
                    <option value="" disabled selected>Pilih Tipe Material...</option>
                    <option value="lignite coal standard">Lignite Coal Standard</option>
                    <option value="sub-bituminous coal standard">Sub-Bituminous Coal Standard</option>
                    <option value="bituminous coal standard">Bituminous Coal Standard</option>
                    <option value="lainnya">Lainnya...</option>
                </select>
              </div>
          </div>
          
          <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Nilai Sertifikat (Cert Value)</label>
                <input type="number" step="0.0001" class="form-control" id="crm_cert_value" placeholder="Contoh: 9.5000">
                <small class="text-muted">Untuk parameter ini</small>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Ketidakpastian (U)</label>
                <input type="number" step="0.0001" class="form-control" id="crm_cert_u" placeholder="Contoh: 0.0500">
              </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Tanggal Kadaluarsa (Exp)</label>
            <input type="date" class="form-control" id="crm_tanggal_expired">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-info text-white" id="btnSaveCrm">Simpan CRM</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if there is a hash in the URL and switch tab
    if(window.location.hash) {
        var hash = window.location.hash;
        // Search for either button or a tag with href or data-bs-target matching the hash
        var tabTrigger = document.querySelector('[data-bs-target="' + hash + '"]') || document.querySelector('[href="' + hash + '"]');
        if (tabTrigger) {
            var tab = new bootstrap.Tab(tabTrigger);
            tab.show();
        }
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-delete-crm').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const lot = this.dataset.lot;
            if (confirm(`Apakah Anda yakin ingin menghapus botol CRM Lot ${lot} ini? Penghapusan ini bersifat permanen dan akan menghapus nilai sertifikatnya di semua parameter.`)) {
                fetch(`/parameter-uji/crm-katalog/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Botol CRM berhasil dihapus.');
                        window.location.reload();
                    } else {
                        alert('Gagal menghapus: ' + data.message);
                    }
                });
            }
        });
    });
    const btnSaveCrm = document.getElementById('btnSaveCrm');
    if (btnSaveCrm) {
        btnSaveCrm.addEventListener('click', function() {
            const btn = this;
            const originalText = btn.innerHTML;
            
            // Validate required fields
            const noLot = document.getElementById('crm_nomor_lot').value;
            const namaProd = document.getElementById('crm_nama_produk').value;
            const paramId = document.getElementById('crm_parameter_uji_id').value;
            
            if (!noLot || !namaProd) {
                alert('Nomor Lot dan Jenis Material wajib diisi!');
                return;
            }
            if (!paramId) {
                alert('Parameter Uji ID belum tersedia. Harap simpan Parameter Uji terlebih dahulu sebelum menambah Lot CRM.');
                return;
            }

            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;

            const data = {
                _token: '{{ csrf_token() }}',
                nomor_lot: noLot,
                nama_produk: namaProd,
                produsen: document.getElementById('crm_produsen').value,
                tanggal_expired: document.getElementById('crm_tanggal_expired').value,
                cert_value: document.getElementById('crm_cert_value').value,
                cert_u: document.getElementById('crm_cert_u').value,
                parameter_uji_id: paramId
            };

            fetch('{{ route('parameter-uji.store-crm') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    alert('CRM Baru berhasil ditambahkan beserta nilai sertifikatnya! Halaman akan dimuat ulang.');
                    window.location.reload();
                } else {
                    alert('Gagal menambahkan CRM: ' + (res.message || 'Cek kembali isian Anda.'));
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(err => {
                alert('Terjadi kesalahan sistem.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    }
});
</script>
@endsection
