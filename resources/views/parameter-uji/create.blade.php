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

                <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold text-primary"><i class="fas fa-sliders-h me-1"></i> Jenis Kontrol Mutu</label>
                        <select id="jenis_kontrol" name="jenis_kontrol" class="form-select bg-light">
                            <option value="in_house" {{ old('jenis_kontrol') == 'crm' ? '' : 'selected' }}>Statistik Lab (In-House Control) - Menggunakan Mean & SD</option>
                            <option value="crm" {{ old('jenis_kontrol') == 'crm' ? 'selected' : '' }}>Sertifikat Pabrik (CRM) - Input Manual Nilai Mutlak</option>
                        </select>
                        <small class="text-muted">Pilih mode In-House agar sistem menghitung Batas Peringatan/Gagal (Westgard) secara otomatis.</small>
                    </div>
                </div>

                <div class="card bg-light mb-3 border-0" id="cardInHouse">
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
                        const inputMean = document.getElementById('inputMean');
                        const inputSd = document.getElementById('inputSd');
                        const calcLcl = document.getElementById('calcLcl');
                        const calcUwlBawah = document.getElementById('calcUwlBawah');
                        const calcUwlAtas = document.getElementById('calcUwlAtas');
                        const calcUcl = document.getElementById('calcUcl');
                        
                        const inputAcuan = document.getElementById('inputAcuan');
                        const inputBatasBawah = document.getElementById('inputBatasBawah');
                        const inputBatasAtas = document.getElementById('inputBatasAtas');
                        
                        const jenisKontrol = document.getElementById('jenis_kontrol');
                        const cardInHouse = document.getElementById('cardInHouse');

                        function toggleMode() {
                            if (jenisKontrol.value === 'in_house') {
                                cardInHouse.style.display = 'block';
                                inputAcuan.readOnly = true;
                                inputBatasBawah.readOnly = true;
                                inputBatasAtas.readOnly = true;
                                inputAcuan.classList.add('bg-light');
                                inputBatasBawah.classList.add('bg-light');
                                inputBatasAtas.classList.add('bg-light');
                            } else {
                                cardInHouse.style.display = 'none';
                                inputAcuan.readOnly = false;
                                inputBatasBawah.readOnly = false;
                                inputBatasAtas.readOnly = false;
                                inputAcuan.classList.remove('bg-light');
                                inputBatasBawah.classList.remove('bg-light');
                                inputBatasAtas.classList.remove('bg-light');
                                
                                // Reset statistic fields
                                inputMean.value = '';
                                inputSd.value = '';
                                calculateLimits();
                            }
                        }

                        function calculateLimits() {
                            const mean = parseFloat(inputMean.value);
                            const sd = parseFloat(inputSd.value);

                            if (!isNaN(mean) && !isNaN(sd) && sd > 0) {
                                calcLcl.value = (mean - (3 * sd)).toFixed(4);
                                calcUwlBawah.value = (mean - (2 * sd)).toFixed(4);
                                calcUwlAtas.value = (mean + (2 * sd)).toFixed(4);
                                calcUcl.value = (mean + (3 * sd)).toFixed(4);
                                
                                // Auto sync to main inputs if in_house
                                if (jenisKontrol.value === 'in_house') {
                                    inputAcuan.value = mean.toFixed(4);
                                    inputBatasBawah.value = calcLcl.value;
                                    inputBatasAtas.value = calcUcl.value;
                                }
                            } else {
                                calcLcl.value = '';
                                calcUwlBawah.value = '';
                                calcUwlAtas.value = '';
                                calcUcl.value = '';
                                
                                if (jenisKontrol.value === 'in_house') {
                                    inputAcuan.value = '';
                                    inputBatasBawah.value = '';
                                    inputBatasAtas.value = '';
                                }
                            }
                        }

                        jenisKontrol.addEventListener('change', toggleMode);
                        inputMean.addEventListener('input', calculateLimits);
                        inputSd.addEventListener('input', calculateLimits);
                        
                        // Initialization
                        if (!inputMean.value && inputAcuan.value) {
                            jenisKontrol.value = 'crm';
                        } else {
                            jenisKontrol.value = 'in_house';
                        }
                        toggleMode();
                        
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

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('parameter-uji.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Parameter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
