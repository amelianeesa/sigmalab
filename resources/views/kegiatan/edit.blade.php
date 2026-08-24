@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kegiatan.show', $kegiatan->kegiatan_id) }}" class="text-decoration-none">{{ $kegiatan->nama_kegiatan }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Kegiatan</li>
        </ol>
    </nav>

    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Edit Kegiatan</h1>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('kegiatan.update', $kegiatan->kegiatan_id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h5 class="mb-3 text-primary border-bottom pb-2">Informasi Umum</h5>
                
                <div class="mb-3">
                    <label for="nama_kegiatan" class="form-label">Nama / Deskripsi Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror" id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}" required placeholder="Contoh: Pengujian Kualitas Air Bersih PT. ABC">
                    @error('nama_kegiatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="jenis_kegiatan" class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
                        <select class="form-select @error('jenis_kegiatan') is-invalid @enderror" id="jenis_kegiatan" name="jenis_kegiatan" required>
                            <option value="">Pilih Jenis Kegiatan</option>
                            <option value="pengujian" {{ old('jenis_kegiatan', $kegiatan->jenis_kegiatan) == 'pengujian' ? 'selected' : '' }}>Pengujian</option>
                            <option value="kalibrasi" {{ old('jenis_kegiatan', $kegiatan->jenis_kegiatan) == 'kalibrasi' ? 'selected' : '' }}>Kalibrasi</option>
                        </select>
                        @error('jenis_kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="kode_sampel" class="form-label">Kode Sampel</label>
                        <input type="text" class="form-control bg-light @error('kode_sampel') is-invalid @enderror" id="kode_sampel" name="kode_sampel" value="{{ old('kode_sampel', $kegiatan->kode_sampel) }}" readonly>
                        @error('kode_sampel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="tanggal_kegiatan" class="form-label">Tanggal Kegiatan <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal_kegiatan') is-invalid @enderror" id="tanggal_kegiatan" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->format('Y-m-d')) }}" required>
                        @error('tanggal_kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="status_kegiatan" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status_kegiatan') is-invalid @enderror" id="status_kegiatan" name="status_kegiatan" required>
                            <option value="draft" {{ old('status_kegiatan', $kegiatan->status_kegiatan) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="berjalan" {{ old('status_kegiatan', $kegiatan->status_kegiatan) == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            <option value="selesai" {{ old('status_kegiatan', $kegiatan->status_kegiatan) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ old('status_kegiatan', $kegiatan->status_kegiatan) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        @error('status_kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <h5 class="mb-3 text-primary border-bottom pb-2">Alat Digunakan</h5>
                
                <div class="mb-4">
                    <div class="row">
                        @foreach($alatList as $alat)
                        <div class="col-md-4 mb-2">
                            @php
                                $kalibrasiValid = true;
                                $kalibrasi = $alat->riwayatKalibrasi()->whereNull('deleted_at')->latest('tgl_akhir')->first();
                                if(!$kalibrasi || $kalibrasi->tgl_akhir < now()) {
                                    $kalibrasiValid = false;
                                }
                            @endphp
                            <div class="form-check">
                                <input class="form-check-input alat-checkbox" type="checkbox" name="alat_ids[]" value="{{ $alat->alat_id }}" id="alat_{{ $alat->alat_id }}" {{ in_array($alat->alat_id, old('alat_ids', $selectedAlat)) ? 'checked' : '' }} data-nama="{{ $alat->nama_alat }}" data-valid="{{ $kalibrasiValid ? 'true' : 'false' }}">
                                <label class="form-check-label {{ !$kalibrasiValid ? 'text-danger' : '' }}" for="alat_{{ $alat->alat_id }}">
                                    {{ $alat->nama_alat }} ({{ $alat->kode_alat }})
                                    @if(!$kalibrasiValid) <i class="fas fa-exclamation-triangle ms-1" title="Kedaluwarsa"></i> @endif
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('alat_ids')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <h5 class="mb-3 text-primary border-bottom pb-2">Personil Terlibat</h5>
                
                <div class="mb-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%" class="text-center">Pilih</th>
                                    <th>Nama Personil</th>
                                    <th>No Induk</th>
                                    <th>Peran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($personilList as $personil)
                                <tr>
                                    <td class="text-center align-middle">
                                        <input class="form-check-input personil-checkbox" type="checkbox" name="personil_ids[]" value="{{ $personil->personil_id }}" id="personil_{{ $personil->personil_id }}" {{ in_array($personil->personil_id, old('personil_ids', $selectedPersonil)) ? 'checked' : '' }}>
                                    </td>
                                    <td class="align-middle">
                                        <label for="personil_{{ $personil->personil_id }}" class="mb-0 cursor-pointer">{{ $personil->nama }}</label>
                                    </td>
                                    <td class="align-middle">{{ $personil->no_induk ?? '-' }}</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="personil_peran[{{ $personil->personil_id }}]" value="{{ old('personil_peran.'.$personil->personil_id, $personilPeran[$personil->personil_id] ?? 'Analis') }}" placeholder="Peran (mis: Analis)">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @error('personil_ids')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <h5 class="mb-3 text-primary border-bottom pb-2">Bahan Digunakan</h5>
                
                <div class="mb-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
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
                                    $jumlahTerpakai = $barangJumlah[$barang->barang_id] ?? 0;
                                    $isTerpilih = in_array($barang->barang_id, old('barang_ids', $selectedBarang));
                                    // Sisa stok riil = Saldo Akhir saat ini + yang sebelumnya sudah dipotong untuk kegiatan ini
                                    $sisaStokRiil = $saldoAkhir + $jumlahTerpakai;
                                    $habis = $sisaStokRiil <= 0;
                                @endphp
                                <tr class="{{ $habis && !$isTerpilih ? 'table-danger' : '' }}">
                                    <td class="text-center align-middle">
                                        <input class="form-check-input" type="checkbox" name="barang_ids[]" value="{{ $barang->barang_id }}" id="barang_{{ $barang->barang_id }}" {{ $isTerpilih ? 'checked' : '' }} {{ $habis && !$isTerpilih ? 'disabled' : '' }}>
                                    </td>
                                    <td class="align-middle">
                                        <label for="barang_{{ $barang->barang_id }}" class="mb-0 cursor-pointer {{ $habis && !$isTerpilih ? 'text-muted' : '' }}">
                                            {{ $barang->nama_barang }} 
                                            @if($habis && !$isTerpilih)
                                                <span class="badge bg-danger ms-1">Habis</span>
                                            @endif
                                        </label>
                                    </td>
                                    <td class="align-middle">
                                        {{ number_format($sisaStokRiil, 0, ',', '.') }} {{ $barang->satuan }}
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="number" step="0.01" min="0" class="form-control barang-input" name="barang_jumlah[{{ $barang->barang_id }}]" value="{{ old('barang_jumlah.'.$barang->barang_id, $jumlahTerpakai ?: '') }}" placeholder="0" {{ $habis && !$isTerpilih ? 'disabled' : '' }} data-nama="{{ $barang->nama_barang }}" data-stok="{{ $sisaStokRiil }}">
                                            <span class="input-group-text">{{ $barang->satuan }}</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @error('barang_ids')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4 mt-5">
                    <h5 class="mb-3 text-primary border-bottom pb-2">Parameter Uji yang Dilakukan</h5>
                    <!-- Dropdown Metode Verifikasi -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            @php
                                // Infer selected method based on already checked parameters
                                $isCrm = false;
                                foreach($parameterList as $p) {
                                    if(in_array($p->parameter_uji_id, old('parameter_uji_ids', $selectedParameterUji))) {
                                        if($p->jenis_kontrol === 'crm') {
                                            $isCrm = true;
                                            break;
                                        }
                                    }
                                }
                                $defaultMetode = $isCrm ? 'crm' : 'in_house';
                            @endphp
                            <label for="metode_verifikasi" class="form-label fw-bold">Metode Verifikasi Mutu <span class="text-danger">*</span></label>
                            <select id="metode_verifikasi" name="metode_verifikasi" class="form-select border-primary" required>
                                <option value="">-- Pilih Metode Verifikasi --</option>
                                <option value="in_house" {{ old('metode_verifikasi', $defaultMetode) == 'in_house' ? 'selected' : '' }}>In-House Control (Statistik Lab)</option>
                                <option value="crm" {{ old('metode_verifikasi', $defaultMetode) == 'crm' ? 'selected' : '' }}>CRM (Sertifikat Pabrik)</option>
                            </select>
                            <small class="text-muted">Pilih metode untuk menampilkan daftar parameter uji yang sesuai.</small>
                        </div>
                    </div>

                    <div id="parameter-container" style="display: none;">
                        <p class="text-muted small mb-3">Sistem hanya menampilkan parameter yang sesuai dengan metode terpilih.</p>

                        <!-- Panel In-House -->
                        <div id="pane-inhouse" style="display: none;">
                            <div class="row">
                                @php
                                    $inhouseParams = $parameterList->where('jenis_kontrol', '!=', 'crm')->groupBy('kategori_parameter');
                                @endphp
                                @forelse($inhouseParams as $kategori => $params)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 border-light shadow-sm">
                                        <div class="card-header bg-light py-2">
                                            <h6 class="m-0 font-weight-bold text-secondary">{{ $kategori ?: 'Lain-lain' }}</h6>
                                        </div>
                                        <div class="card-body py-2">
                                            @foreach($params as $param)
                                            @php
                                                $isChecked = in_array($param->parameter_uji_id, old('parameter_uji_ids', $selectedParameterUji));
                                            @endphp
                                            <div class="form-check mb-1">
                                                <input class="form-check-input param-checkbox param-inhouse" type="checkbox" name="parameter_uji_ids[]" value="{{ $param->parameter_uji_id }}" id="param_{{ $param->parameter_uji_id }}" {{ $isChecked ? 'checked' : '' }}>
                                                <label class="form-check-label cursor-pointer" for="param_{{ $param->parameter_uji_id }}">
                                                    {{ $param->nama_parameter }}
                                                    @if($param->dependensi_parameter)
                                                        <span class="badge bg-warning text-dark ms-1" style="font-size: 0.6rem;" title="Membutuhkan: {{ implode(', ', $param->dependensi_parameter) }}">Butuh Dependensi</span>
                                                    @endif
                                                    @if($isChecked)
                                                        <span class="badge bg-success ms-1" style="font-size: 0.6rem;">Terdaftar</span>
                                                    @endif
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="alert alert-info">Belum ada parameter In-House Control yang terdaftar.</div>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Panel CRM -->
                        <div id="pane-crm" style="display: none;">
                            <div class="row">
                                @php
                                    $crmParams = $parameterList->where('jenis_kontrol', 'crm')->groupBy('kategori_parameter');
                                @endphp
                                @forelse($crmParams as $kategori => $params)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 border-info shadow-sm">
                                        <div class="card-header bg-info bg-opacity-10 py-2">
                                            <h6 class="m-0 font-weight-bold text-info">{{ $kategori ?: 'Lain-lain' }}</h6>
                                        </div>
                                        <div class="card-body py-2">
                                            @foreach($params as $param)
                                            @php
                                                $isChecked = in_array($param->parameter_uji_id, old('parameter_uji_ids', $selectedParameterUji));
                                            @endphp
                                            <div class="form-check mb-1">
                                                <input class="form-check-input param-checkbox param-crm" type="checkbox" name="parameter_uji_ids[]" value="{{ $param->parameter_uji_id }}" id="param_{{ $param->parameter_uji_id }}" {{ $isChecked ? 'checked' : '' }}>
                                                <label class="form-check-label cursor-pointer" for="param_{{ $param->parameter_uji_id }}">
                                                    {{ $param->nama_parameter }}
                                                    @if($isChecked)
                                                        <span class="badge bg-success ms-1" style="font-size: 0.6rem;">Terdaftar</span>
                                                    @endif
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="alert alert-secondary"><i class="fas fa-info-circle me-1"></i> Belum ada parameter CRM yang terdaftar. Anda dapat membuat parameter CRM baru di menu <strong>Master Data > Parameter Uji</strong>.</div>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-2" style="font-size: 0.85rem;">
                        <i class="fas fa-info-circle me-1"></i> <strong>Keamanan Data (Add-Only):</strong> Anda dapat mencentang parameter baru untuk ditambah ke Kegiatan ini. Menghapus centang pada parameter yang sudah terdaftar <b>tidak akan</b> menghapus data hasil ujinya.
                    </div>
                    @error('parameter_uji_ids')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validasi Live: Alat (Kalibrasi)
    const alatCheckboxes = document.querySelectorAll('.alat-checkbox');
    alatCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked && this.dataset.valid === 'false') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan Kalibrasi',
                    text: `Alat ${this.dataset.nama} masa kalibrasinya sudah habis atau belum dikalibrasi. Mohon pilih alat lain.`,
                    confirmButtonColor: '#d33'
                });
                this.checked = false; // Auto uncheck
            }
        });
    });

    // Validasi Live: Bahan (Stok)
    const barangInputs = document.querySelectorAll('.barang-input');
    barangInputs.forEach(input => {
        input.addEventListener('input', function() {
            const requested = parseFloat(this.value) || 0;
            const maxStok = parseFloat(this.dataset.stok) || 0;
            
            if (requested > maxStok) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Kekurangan Stok',
                    text: `Stok ${this.dataset.nama} tidak mencukupi! Sisa stok maksimal yang bisa digunakan adalah ${maxStok}.`,
                    confirmButtonColor: '#f39c12'
                });
                this.value = maxStok; // Auto-correct to max available
            }
        });
    });
    // Logic Filter Parameter berdasarkan Metode Verifikasi
    const selMetode = document.getElementById('metode_verifikasi');
    const paramContainer = document.getElementById('parameter-container');
    const paneInhouse = document.getElementById('pane-inhouse');
    const paneCrm = document.getElementById('pane-crm');
    
    function updateParameterVisibility() {
        const val = selMetode.value;
        
        if (!val) {
            paramContainer.style.display = 'none';
            paneInhouse.style.display = 'none';
            paneCrm.style.display = 'none';
        } else {
            paramContainer.style.display = 'block';
            if (val === 'in_house') {
                paneInhouse.style.display = 'block';
                paneCrm.style.display = 'none';
                // Uncheck CRM params
                document.querySelectorAll('.param-crm').forEach(cb => cb.checked = false);
            } else if (val === 'crm') {
                paneInhouse.style.display = 'none';
                paneCrm.style.display = 'block';
                // Uncheck In-House params
                document.querySelectorAll('.param-inhouse').forEach(cb => cb.checked = false);
            }
        }
    }
    
    selMetode.addEventListener('change', updateParameterVisibility);
    updateParameterVisibility();
});
</script>
@endpush
