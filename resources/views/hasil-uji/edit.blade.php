@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
            @if($hasilUji->kegiatan)
                <li class="breadcrumb-item"><a href="{{ route('kegiatan.show', $hasilUji->kegiatan_id) }}" class="text-decoration-none">{{ $hasilUji->kegiatan->nama_kegiatan }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">Input Data Uji</li>
        </ol>
    </nav>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Input Hasil Uji Baru</h1>
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : ($hasilUji->kegiatan ? route('kegiatan.show', $hasilUji->kegiatan_id) : route('dashboard')) }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('hasil-uji.update', $hasilUji->hasil_uji_id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Kegiatan / Kode Sampel</label>
                        <input type="text" class="form-control bg-light fw-bold" value="{{ $hasilUji->kegiatan->nama_kegiatan }} ({{ $hasilUji->kegiatan->kode_sampel }})" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Parameter Uji</label>
                        <input type="text" class="form-control bg-light fw-bold" value="{{ $hasilUji->parameterUji->nama_parameter }} ({{ $hasilUji->parameterUji->satuan ?? '-' }})" readonly>
                    </div>
                </div>

                @php
                    $vars = $hasilUji->parameterUji->variabel_input ?? [];
                    $deps = $hasilUji->parameterUji->dependensi_parameter ?? [];
                    $ucl = $hasilUji->parameterUji->ucl ?? 'null';
                    $lcl = $hasilUji->parameterUji->lcl ?? 'null';
                @endphp

                                  @if($hasilUji->parameterUji->rumus_kalkulasi === 'CUSTOM_IM')
                      <div id="runsContainer">
                          <div class="run-block p-3 mb-3 bg-light rounded border" data-run-index="0">
                              <h5 class="mb-3 text-primary border-bottom pb-2 run-title">
                                  Data Mentah - Inherent Moisture (Duplo) - Run 1
                              </h5>
                        <div class="row">
                            <!-- Dish 1 -->
                            <div class="col-md-6 border-end">
                                <h6 class="text-secondary font-weight-bold mb-3">DISH 1</h6>
                                <div class="mb-3">
                                    <label class="form-label">M1 (Massa Cawan Kosong) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][M1_D1]" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">A (Massa Sampel) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][A_D1]" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">M3 (Massa Cawan + Kering) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][M3_D1]" required>
                                </div>
                            </div>
                            <!-- Dish 2 -->
                            <div class="col-md-6">
                                <h6 class="text-secondary font-weight-bold mb-3">DISH 2</h6>
                                <div class="mb-3">
                                    <label class="form-label">M1 (Massa Cawan Kosong) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][M1_D2]" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">A (Massa Sampel) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][A_D2]" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">M3 (Massa Cawan + Kering) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][M3_D2]" required>
                                </div>
                            </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center py-2">
                                        <h6 class="m-0 fw-bold"><i class="fas fa-calculator me-1"></i> Pratinjau Hasil Kalkulasi IM (ad)</h6>
                                        <button type="button" class="btn btn-sm btn-light text-info fw-bold btn-preview-im">Hitung Sekarang</button>
                                    </div>
                                    <div class="card-body bg-light text-start preview-box-im" style="display:none;">
                                        <div class="row">
                                              <div class="col-md-4 text-center">
                                                  <small class="text-muted d-block fw-bold border-bottom pb-1 mb-2">Dish 1</small>
                                                  <div class="small">M2 (M1+A) = <span class="text-primary fw-bold resM2_D1">-</span></div>
                                                  <div class="small mb-1">B (M3-M1) = <span class="text-primary fw-bold resB_D1">-</span></div>
                                                  <div class="mt-2 text-dark fw-bold">M% = <span class="text-primary fs-5 resD1">-</span></div>
                                              </div>
                                              <div class="col-md-4 border-start border-end text-center">
                                                  <small class="text-muted d-block fw-bold border-bottom pb-1 mb-2">Dish 2</small>
                                                  <div class="small">M2 (M1+A) = <span class="text-primary fw-bold resM2_D2">-</span></div>
                                                  <div class="small mb-1">B (M3-M1) = <span class="text-primary fw-bold resB_D2">-</span></div>
                                                  <div class="mt-2 text-dark fw-bold">M% = <span class="text-primary fs-5 resD2">-</span></div>
                                              </div>
                                              <div class="col-md-4 text-center">
                                                  <small class="text-muted d-block fw-bold border-bottom pb-1">Rata-Rata Final</small>
                                                  <h4 class="text-success fw-bold mb-0 mt-3 resFinal">-</h4>
                                              </div>
                                          </div>
                                        <div id="resWarning_0" class="res-warning mt-3 text-start"></div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      </div> <!-- end run-block -->
                      </div> <!-- end runsContainer -->
                  @elseif($hasilUji->parameterUji->rumus_kalkulasi === 'CUSTOM_ASH')
                    <div class="p-3 mb-3 bg-light rounded border">
                        <h5 class="mb-3 text-primary border-bottom pb-2">
                            Data Mentah - Ash Content (Duplo)
                        </h5>
                        
                        <div class="alert alert-warning mb-3" style="font-size: 0.85rem;">
                            <i class="fas fa-exclamation-triangle me-1"></i> <strong>Dependensi IM:</strong> <br>
                            Kalkulasi %db pada ASH membutuhkan nilai Inherent Moisture (IM) dari kegiatan ini. Pastikan IM sudah diinput agar hasil tidak <b>PENDING</b>.
                        </div>

                        <div class="row">
                            <!-- Dish 1 -->
                            <div class="col-md-6 border-end">
                                <h6 class="text-secondary font-weight-bold mb-3">DISH 1</h6>
                                <div class="mb-3">
                                    <label class="form-label">M1 (Massa Cawan Kosong) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][M1_D1]" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">M2-M1 (Massa Sampel) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][M2M1_D1]" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">M3 (Massa Cawan + Abu) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][M3_D1]" required>
                                </div>
                            </div>
                            <!-- Dish 2 -->
                            <div class="col-md-6">
                                <h6 class="text-secondary font-weight-bold mb-3">DISH 2</h6>
                                <div class="mb-3">
                                    <label class="form-label">M1 (Massa Cawan Kosong) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][M1_D2]" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">M2-M1 (Massa Sampel) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][M2M1_D2]" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">M3 (Massa Cawan + Abu) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][M3_D2]" required>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($hasilUji->parameterUji->rumus_kalkulasi === 'CUSTOM_VM')
                    <div class="p-3 mb-3 bg-light rounded border">
                        <h5 class="mb-3 text-primary border-bottom pb-2">
                            Data Mentah - {{ str_contains($hasilUji->parameterUji->nama_parameter, 'Bias') ? 'Bias Test VM (Pt)' : 'Volatile Matter' }} (Duplo)
                        </h5>
                        
                        <div class="alert alert-warning mb-3" style="font-size: 0.85rem;">
                            <i class="fas fa-exclamation-triangle me-1"></i> <strong>Dependensi IM:</strong> <br>
                            Kalkulasi pada VM membutuhkan nilai Inherent Moisture (IM) dari kegiatan ini. Pastikan IM sudah diinput agar hasil tidak <b>PENDING</b>.
                        </div>

                        <div class="row">
                            <!-- Dish 1 -->
                            <div class="col-md-6 border-end">
                                <h6 class="text-secondary font-weight-bold mb-3">DISH 1</h6>
                                <div class="mb-3">
                                    <label class="form-label">M1 (Massa Cawan Kosong) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][M1_D1]" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">M2-M1 (Massa Sampel) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][M2M1_D1]" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">M3 (Massa Cawan + Residu) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][M3_D1]" required>
                                </div>
                            </div>
                            <!-- Dish 2 -->
                            <div class="col-md-6">
                                <h6 class="text-secondary font-weight-bold mb-3">DISH 2</h6>
                                <div class="mb-3">
                                    <label class="form-label">M1 (Massa Cawan Kosong) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][M1_D2]" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">M2-M1 (Massa Sampel) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][M2M1_D2]" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">M3 (Massa Cawan + Residu) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][M3_D2]" required>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                @elseif($hasilUji->parameterUji->rumus_kalkulasi === 'CUSTOM_BIAS_VM')
                    <div class="p-3 mb-3 bg-light rounded border">
                        <h5 class="mb-3 text-primary border-bottom pb-2">
                            Data Mentah - Bias Test VM (Pt) — 20 Pengulangan
                        </h5>
                        
                        <div class="alert alert-warning mb-3" style="font-size: 0.85rem;">
                            <i class="fas fa-exclamation-triangle me-1"></i> <strong>Dependensi IM:</strong> <br>
                            Kalkulasi VM membutuhkan nilai Inherent Moisture (IM) dari kegiatan ini. Pastikan IM sudah diinput agar hasil tidak <b>PENDING</b>.
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Reference Value (%db) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="runs[0][variabel][reference_value]" required
                                    value="{{ old('variabel.reference_value', $hasilUji->data_mentah['reference_value'] ?? '') }}"
                                    placeholder="Masukkan nilai referensi metode standar">
                                <small class="text-muted">Nilai acuan dari CRM / inter-lab / metode standar</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">IM Average (%) <small class="text-muted">(opsional)</small></label>
                                <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][IM_Average]"
                                    value="{{ old('variabel.IM_Average', $hasilUji->data_mentah['IM_Average'] ?? '') }}"
                                    placeholder="Rata-rata IM untuk %db">
                                <small class="text-muted">Jika kosong, sistem pakai nilai IM dari kegiatan ini</small>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-center align-middle" style="font-size: 0.85rem;">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>M1 (Massa Cawan) <span class="text-danger">*</span></th>
                                        <th>M2-M1 (Massa Sampel) <span class="text-danger">*</span></th>
                                        <th>M3 (Cawan + Residu) <span class="text-danger">*</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($r = 1; $r <= 20; $r++)
                                        <tr class="{{ $r % 2 == 0 ? 'table-light' : '' }}" style="{{ $r % 2 == 1 ? 'border-top: 2px solid #aaa;' : '' }}">
                                            <td class="fw-bold {{ $r % 2 == 1 ? 'text-primary' : 'text-secondary' }}">{{ $r }}</td>
                                            <td>
                                                <input type="number" step="0.0001" class="form-control form-control-sm"
                                                    name="runs[0][variabel][M1_R{{ $r }}]"
                                                    value="{{ old("variabel.M1_R{$r}", $hasilUji->data_mentah["M1_R{$r}"] ?? '') }}"
                                                    {{ $r <= 2 ? 'required' : '' }}>
                                            </td>
                                            <td>
                                                <input type="number" step="0.0001" class="form-control form-control-sm"
                                                    name="runs[0][variabel][M2M1_R{{ $r }}]"
                                                    value="{{ old("variabel.M2M1_R{$r}", $hasilUji->data_mentah["M2M1_R{$r}"] ?? '') }}"
                                                    {{ $r <= 2 ? 'required' : '' }}>
                                            </td>
                                            <td>
                                                <input type="number" step="0.0001" class="form-control form-control-sm"
                                                    name="runs[0][variabel][M3_R{{ $r }}]"
                                                    value="{{ old("variabel.M3_R{$r}", $hasilUji->data_mentah["M3_R{$r}"] ?? '') }}"
                                                    {{ $r <= 2 ? 'required' : '' }}>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Minimal 2 pengulangan wajib diisi. Baris yang kosong akan diabaikan.</small>
                    </div>
                @elseif($hasilUji->parameterUji->rumus_kalkulasi === 'CUSTOM_TS')
                    <div class="p-3 mb-3 bg-light rounded border">
                        <h5 class="mb-3 text-primary border-bottom pb-2">
                            Data Mentah - Total Sulfur (Duplo)
                        </h5>
                        
                        <div class="alert alert-warning mb-3" style="font-size: 0.85rem;">
                            <i class="fas fa-exclamation-triangle me-1"></i> <strong>Dependensi IM:</strong> <br>
                            Kalkulasi %db pada TS membutuhkan nilai Inherent Moisture (IM) dari kegiatan ini. Pastikan IM sudah diinput agar hasil tidak <b>PENDING</b>.
                        </div>

                        <div class="row">
                            <!-- Dish 1 -->
                            <div class="col-md-6 border-end">
                                <h6 class="text-secondary font-weight-bold mb-3">PENGUJIAN 1</h6>
                                <div class="mb-3">
                                    <label class="form-label">Massa Sampel (gram) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][Massa_D1]" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">TS % (adb) - Instrumen <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][TS_adb_D1]" required>
                                </div>
                            </div>
                            <!-- Dish 2 -->
                            <div class="col-md-6">
                                <h6 class="text-secondary font-weight-bold mb-3">PENGUJIAN 2</h6>
                                <div class="mb-3">
                                    <label class="form-label">Massa Sampel (gram) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][Massa_D2]" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">TS % (adb) - Instrumen <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][TS_adb_D2]" required>
                                </div>
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>
                @elseif($hasilUji->parameterUji->rumus_kalkulasi === 'CUSTOM_CV')
                    <div class="p-3 mb-3 bg-light rounded border">
                        <h5 class="mb-3 text-primary border-bottom pb-2">
                            Data Mentah - Calorific Value (Duplo)
                        </h5>
                        
                        <div class="alert alert-warning mb-3" style="font-size: 0.85rem;">
                            <i class="fas fa-exclamation-triangle me-1"></i> <strong>Dependensi IM dan TS:</strong> <br>
                            Kalkulasi dan validasi Kalori membutuhkan data Inherent Moisture (IM) dan Total Sulfur (TS) dari sampel ini. 
                            Apabila salah satu belum divalidasi, maka hasil CV akan berstatus <b>PENDING</b>.
                        </div>

                        <div class="row">
                            <!-- Dish 1 -->
                            <div class="col-md-6 border-end">
                                <h6 class="text-secondary font-weight-bold mb-3 border-bottom pb-1"><i class="fas fa-fire"></i> PENGUJIAN 1</h6>
                                
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Vessel No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="runs[0][variabel][Vessel_D1]" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Call ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="runs[0][variabel][Call_ID_D1]" required>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Crucible Mass (g) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.0001" class="form-control form-control-sm" name="runs[0][variabel][Crucible_D1]" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Sample Mass (g) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.0001" class="form-control form-control-sm" name="runs[0][variabel][Massa_D1]" required>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Primary Result <span class="text-danger">*</span></label>
                                        <input type="number" step="0.0001" class="form-control form-control-sm" name="runs[0][variabel][Primary_D1]" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Ee (cal/℃) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.0001" class="form-control form-control-sm" name="runs[0][variabel][Ee_D1]" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <label class="form-label small fw-bold">t (℃) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.0001" class="form-control form-control-sm" name="runs[0][variabel][t_D1]" required>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label small fw-bold">Titrant (ml) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.0001" class="form-control form-control-sm" name="runs[0][variabel][Titrant_D1]" required>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label small fw-bold">Fuse (cm) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.0001" class="form-control form-control-sm" name="runs[0][variabel][Fuse_D1]" required>
                                    </div>
                                </div>
                                
                                <div class="bg-primary text-white p-2 rounded mb-3">
                                    <label class="form-label small fw-bold mb-0">Final Result (cal/g), adb <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control form-control-sm fw-bold mt-1" name="runs[0][variabel][Final_adb_D1]" required placeholder="Input dari alat">
                                </div>
                            </div>
                            
                            <!-- Dish 2 -->
                            <div class="col-md-6">
                                <h6 class="text-secondary font-weight-bold mb-3 border-bottom pb-1"><i class="fas fa-fire"></i> PENGUJIAN 2</h6>
                                
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Vessel No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="runs[0][variabel][Vessel_D2]" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Call ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" name="runs[0][variabel][Call_ID_D2]" required>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Crucible Mass (g) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.0001" class="form-control form-control-sm" name="runs[0][variabel][Crucible_D2]" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Sample Mass (g) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.0001" class="form-control form-control-sm" name="runs[0][variabel][Massa_D2]" required>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Primary Result <span class="text-danger">*</span></label>
                                        <input type="number" step="0.0001" class="form-control form-control-sm" name="runs[0][variabel][Primary_D2]" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-bold">Ee (cal/℃) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.0001" class="form-control form-control-sm" name="runs[0][variabel][Ee_D2]" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-4">
                                        <label class="form-label small fw-bold">t (℃) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.0001" class="form-control form-control-sm" name="runs[0][variabel][t_D2]" required>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label small fw-bold">Titrant (ml) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.0001" class="form-control form-control-sm" name="runs[0][variabel][Titrant_D2]" required>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label small fw-bold">Fuse (cm) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.0001" class="form-control form-control-sm" name="runs[0][variabel][Fuse_D2]" required>
                                    </div>
                                </div>
                                
                                <div class="bg-primary text-white p-2 rounded mb-3">
                                    <label class="form-label small fw-bold mb-0">Final Result (cal/g), adb <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" class="form-control form-control-sm fw-bold mt-1" name="runs[0][variabel][Final_adb_D2]" required placeholder="Input dari alat">
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($hasilUji->parameterUji->rumus_kalkulasi === 'CUSTOM_AFT')
                    <div class="p-3 mb-3 bg-light rounded border">
                        <h5 class="mb-3 text-primary border-bottom pb-2">
                            Data Mentah - Ash Fusion Temperature (AFT)
                        </h5>
                        
                        <div class="mb-4 col-md-4">
                            <label class="form-label fw-bold">Atmosphere (Kondisi Gas) <span class="text-danger">*</span></label>
                            <select name="runs[0][variabel][Atmosphere]" class="form-select" required>
                                <option value="Reducing">Reducing</option>
                                <option value="Oxidizing">Oxidizing</option>
                            </select>
                        </div>

                        <div class="row">
                            <!-- Dish 1 -->
                            <div class="col-md-6 border-end">
                                <h6 class="text-secondary font-weight-bold mb-3 border-bottom pb-1"><i class="fas fa-thermometer-half"></i> PENGUJIAN 1</h6>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">IDT (℃) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.1" class="form-control form-control-sm" name="runs[0][variabel][IDT_D1]" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">ST (℃) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.1" class="form-control form-control-sm" name="runs[0][variabel][ST_D1]" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">HT (℃) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.1" class="form-control form-control-sm" name="runs[0][variabel][HT_D1]" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">FT (℃) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.1" class="form-control form-control-sm" name="runs[0][variabel][FT_D1]" required>
                                </div>
                            </div>
                            
                            <!-- Dish 2 -->
                            <div class="col-md-6">
                                <h6 class="text-secondary font-weight-bold mb-3 border-bottom pb-1"><i class="fas fa-thermometer-half"></i> PENGUJIAN 2</h6>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">IDT (℃) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.1" class="form-control form-control-sm" name="runs[0][variabel][IDT_D2]" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">ST (℃) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.1" class="form-control form-control-sm" name="runs[0][variabel][ST_D2]" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">HT (℃) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.1" class="form-control form-control-sm" name="runs[0][variabel][HT_D2]" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">FT (℃) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.1" class="form-control form-control-sm" name="runs[0][variabel][FT_D2]" required>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($hasilUji->parameterUji->rumus_kalkulasi === 'CUSTOM_CHN')
                    <div class="p-3 mb-3 bg-light rounded border">
                        <h5 class="mb-3 text-primary border-bottom pb-2">
                            Data Mentah - Ultimate Analysis (CHN)
                        </h5>

                        <div class="row">
                            <!-- Dish 1 -->
                            <div class="col-md-6 border-end">
                                <h6 class="text-secondary font-weight-bold mb-3 border-bottom pb-1"><i class="fas fa-flask"></i> PENGUJIAN 1</h6>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Weight (mg) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="runs[0][variabel][Weight_D1]" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Carbon, C (% db) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="runs[0][variabel][C_D1]" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Hydrogen, H (% db) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="runs[0][variabel][H_D1]" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Nitrogen, N (% db) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="runs[0][variabel][N_D1]" required>
                                </div>
                            </div>
                            
                            <!-- Dish 2 -->
                            <div class="col-md-6">
                                <h6 class="text-secondary font-weight-bold mb-3 border-bottom pb-1"><i class="fas fa-flask"></i> PENGUJIAN 2</h6>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Weight (mg) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="runs[0][variabel][Weight_D2]" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Carbon, C (% db) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="runs[0][variabel][C_D2]" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Hydrogen, H (% db) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="runs[0][variabel][H_D2]" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Nitrogen, N (% db) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="runs[0][variabel][N_D2]" required>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif(count($vars) > 0)
                    <div class="p-3 mb-3 bg-light rounded border">
                        <h6 class="font-weight-bold text-primary mb-3">Data Mentah (Variabel Kalkulasi)</h6>
                        
                        @if(count($deps) > 0)
                        <div class="alert alert-warning mb-3" style="font-size: 0.85rem;">
                            <i class="fas fa-exclamation-triangle me-1"></i> Parameter ini bergantung pada: <strong>{{ implode(', ', $deps) }}</strong>. <br>
                            Jika data dependensi belum tersedia, hasil akan berstatus <b>PENDING</b> dan dikalkulasi otomatis nanti.
                        </div>
                        @endif

                        <div class="row">
                            @foreach($vars as $v)
                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ $v }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.0001" class="form-control" name="runs[0][variabel][{{ $v }}]" required>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- Fallback input jika tidak ada variabel (atau untuk compatibilitas lama) -->
                    <div class="mb-3">
                        <label for="nilai_hasil" class="form-label">Nilai Akhir <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" class="form-control @error('nilai_hasil') is-invalid @enderror" id="nilai_hasil" name="nilai_hasil" required>
                        @error('nilai_hasil')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div class="alert alert-info mb-4" role="alert">
                    <i class="fas fa-info-circle me-1"></i> Sistem akan secara otomatis menghitung nilai akhir, mengevaluasi aturan Westgard, dan membuat notifikasi/tindak lanjut jika Out-of-Control.
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('kegiatan.show', $hasilUji->kegiatan_id) }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data Pengujian</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('btn-preview-im')) {
            const runBlock = e.target.closest('.run-block');
            if(!runBlock) return;
            const index = runBlock.getAttribute('data-run-index');
            
            const m1_d1 = parseFloat(runBlock.querySelector('input[name="runs['+index+'][variabel][M1_D1]"]').value);
            const a_d1 = parseFloat(runBlock.querySelector('input[name="runs['+index+'][variabel][A_D1]"]').value);
            const m3_d1 = parseFloat(runBlock.querySelector('input[name="runs['+index+'][variabel][M3_D1]"]').value);
            
            const m1_d2 = parseFloat(runBlock.querySelector('input[name="runs['+index+'][variabel][M1_D2]"]').value);
            const a_d2 = parseFloat(runBlock.querySelector('input[name="runs['+index+'][variabel][A_D2]"]').value);
            const m3_d2 = parseFloat(runBlock.querySelector('input[name="runs['+index+'][variabel][M3_D2]"]').value);
            
            if (isNaN(m1_d1) || isNaN(a_d1) || isNaN(m3_d1) || isNaN(m1_d2) || isNaN(a_d2) || isNaN(m3_d2)) {
                alert("Harap isi seluruh field Dish 1 dan Dish 2 dengan angka sebelum menekan tombol Hitung.");
                return;
            }
            
            const m2_d1 = m1_d1 + a_d1;
            const b_d1 = m3_d1 - m1_d1;
            const im1 = ((a_d1 - b_d1) / a_d1) * 100;
            
            const m2_d2 = m1_d2 + a_d2;
            const b_d2 = m3_d2 - m1_d2;
            const im2 = ((a_d2 - b_d2) / a_d2) * 100;
            
            const final = (im1 + im2) / 2;
            const selisih = Math.abs(im1 - im2);
            
            runBlock.querySelector('.preview-box-im').style.display = 'block';
            
            runBlock.querySelector('.resM2_D1').innerText = m2_d1.toFixed(4);
            runBlock.querySelector('.resB_D1').innerText = b_d1.toFixed(4);
            runBlock.querySelector('.resD1').innerText = im1.toFixed(4) + ' %';
            
            runBlock.querySelector('.resM2_D2').innerText = m2_d2.toFixed(4);
            runBlock.querySelector('.resB_D2').innerText = b_d2.toFixed(4);
            runBlock.querySelector('.resD2').innerText = im2.toFixed(4) + ' %';
            
            runBlock.querySelector('.resFinal').innerText = final.toFixed(4) + ' %';
            
            const warningBox = runBlock.querySelector('.res-warning');
            // Warning box dihilangkan sesuai permintaan
            warningBox.innerHTML = '<div class="alert alert-info mb-0 py-2"><i class="fas fa-info-circle me-1"></i> Kalkulasi selesai. Silakan klik <strong>Simpan Data Pengujian</strong> untuk mengevaluasi status batas kendali (Inlier/Outlier).</div>';
        }
        
        if (e.target && e.target.classList.contains('btn-add-run')) {
            e.target.style.display = 'none'; // hide the button so they don't click it twice
            
            const container = document.getElementById('runsContainer');
            const blocks = container.querySelectorAll('.run-block');
            const lastBlock = blocks[blocks.length - 1];
            
            // Clone the block
            const newBlock = lastBlock.cloneNode(true);
            const newIndex = blocks.length; // next index
            
            newBlock.setAttribute('data-run-index', newIndex);
            newBlock.querySelector('.run-title').innerText = 'Data Mentah - Inherent Moisture (Duplo) - Run ' + (newIndex + 1);
            
            // Update names of all inputs
            const inputs = newBlock.querySelectorAll('input');
            inputs.forEach(inp => {
                const oldName = inp.getAttribute('name');
                if (oldName) {
                    const newName = oldName.replace(/runs\[\d+\]/, 'runs[' + newIndex + ']');
                    inp.setAttribute('name', newName);
                }
                inp.value = ''; // clear value
            });
            
            // Reset preview box
            newBlock.querySelector('.preview-box-im').style.display = 'none';
            newBlock.querySelector('.res-warning').innerHTML = '';
            
            // Append and scroll
            container.appendChild(newBlock);
            newBlock.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });

});
</script>
@endsection
