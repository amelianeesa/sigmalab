@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <ol class="breadcrumb mb-1 mt-3">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
        <li class="breadcrumb-item"><a href="{{ route('parameter-uji.index') }}" class="text-decoration-none">Parameter Uji</a></li>
        <li class="breadcrumb-item active">Tambah</li>
    </ol>
    <h1 class="mb-4">Tambah Parameter Uji</h1>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus-circle me-2"></i>Form Tambah Parameter Uji</h6>
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

            <form action="{{ route('parameter-uji.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nama Parameter <span class="text-danger">*</span></label>
                        <input type="text" name="nama_parameter" class="form-control" value="{{ old('nama_parameter') }}" required maxlength="50">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="satuan" class="form-control" value="{{ old('satuan') }}" required maxlength="20">
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
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-primary" style="font-size: 0.85rem;">Mean (Rata-rata)</label>
                                <input type="number" step="0.0001" name="mean" id="inputMean" class="form-control" value="{{ old('mean') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-primary" style="font-size: 0.85rem;">SD (Standard Deviation)</label>
                                <input type="number" step="0.0001" name="sd" id="inputSd" class="form-control" value="{{ old('sd') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label class="form-label text-muted" style="font-size: 0.75rem;">LCL (-3 SD)</label>
                                <input type="text" id="calcLcl" name="lcl" class="form-control form-control-sm bg-white" readonly value="{{ old('lcl') }}">
                            </div>
                            <div class="col">
                                <label class="form-label text-muted" style="font-size: 0.75rem;">LWL (-2 SD)</label>
                                <input type="text" id="calcUwlBawah" name="uwl_bawah" class="form-control form-control-sm bg-white" readonly value="{{ old('uwl_bawah') }}">
                            </div>
                            <div class="col">
                                <label class="form-label text-muted" style="font-size: 0.75rem;">UWL (+2 SD)</label>
                                <input type="text" id="calcUwlAtas" name="uwl_atas" class="form-control form-control-sm bg-white" readonly value="{{ old('uwl_atas') }}">
                            </div>
                            <div class="col">
                                <label class="form-label text-muted" style="font-size: 0.75rem;">UCL (+3 SD)</label>
                                <input type="text" id="calcUcl" name="ucl" class="form-control form-control-sm bg-white" readonly value="{{ old('ucl') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Nilai Acuan <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" name="nilai_acuan" id="inputAcuan" class="form-control" value="{{ old('nilai_acuan') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Batas Bawah <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" name="batas_bawah" id="inputBatasBawah" class="form-control" value="{{ old('batas_bawah') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Batas Atas <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" name="batas_atas" id="inputBatasAtas" class="form-control" value="{{ old('batas_atas') }}" required>
                    </div>
                </div>

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
                        
                        
                        
                        // Langkah Kalkulasi Dynamic Form
                        const tableBody = document.querySelector('#langkahTable tbody');
                        const btnAdd = document.getElementById('btnAddLangkah');
                        let stepCount = {{ is_array(old('langkah_kalkulasi')) ? count(old('langkah_kalkulasi')) : 0 }};

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
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Metode / Kriteria</label>
                        <input type="text" name="metode_kriteria" class="form-control" value="{{ old('metode_kriteria') }}" maxlength="50">
                        <div class="form-text">Contoh: SNI 01-2891-1992</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Rumus Kalkulasi Nilai Akhir</label>
                        <div class="input-group">
                            <input type="text" name="rumus_kalkulasi" class="form-control" value="{{ old('rumus_kalkulasi') }}">
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalLangkahKalkulasi">
                                <i class="fas fa-list-ol"></i> Detail Rumus
                            </button>
                        </div>
                        <div class="form-text">Contoh: CUSTOM_TS, CUSTOM_VM, CUSTOM_IM, atau formula (M_D1 + M_D2) / 2</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-danger">Toleransi Duplo (%)</label>
                        <input type="text" name="toleransi_duplo" class="form-control" value="{{ old('toleransi_duplo') }}">
                        <div class="form-text text-danger">Gunakan angka statis (contoh: 1.5) atau formula matematika.</div>
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
                                                $langkahs = old('langkah_kalkulasi', []);
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
                                            <tr>
                                                <td class="align-middle fw-bold">{{ $katalog->nomor_lot }}</td>
                                                <td class="align-middle">{{ $katalog->produsen ? $katalog->produsen . " - " : "" }}{{ $katalog->nama_produk }}</td>
                                                <td>
                                                    <input type="number" step="0.0001" name="crm_sertifikat[{{ $katalog->id }}][cert_value]" class="form-control form-control-sm" placeholder="Contoh: 9.5000">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.0001" name="crm_sertifikat[{{ $katalog->id }}][cert_u]" class="form-control form-control-sm" placeholder="Contoh: 0.0500">
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
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Parameter</button>
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
          <input type="hidden" id="crm_parameter_uji_id" value="">
          
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
