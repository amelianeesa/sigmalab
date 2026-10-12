@extends('layouts.app')
@section('title', 'Edit Data - Kegiatan')

@section('content')
@include('kegiatan._kegiatan-style')

@php
    // Cek kalibrasi tiap alat sekali saja (dipakai untuk notifikasi & daftar alat)
    $alatValid = [];
    $alatExpiredCount = 0;
    foreach ($alatList as $a) {
        $k = $a->riwayatKalibrasi()->whereNull('deleted_at')->latest('tgl_akhir')->first();
        $valid = $k && !($k->tgl_akhir < now());
        $alatValid[$a->alat_id] = $valid;
        if (!$valid) $alatExpiredCount++;
    }
@endphp

<div class="container-fluid kg-page pb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('verifikasi-mutu.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
            <li class="breadcrumb-item"><a href="{{ route('kegiatan.show', $kegiatan->kegiatan_id) }}" class="text-decoration-none">{{ $kegiatan->nama_kegiatan }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Kegiatan</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="page-title"><i class="fas fa-edit me-2" style="color: #1b3152;"></i>Edit Kegiatan</h5>
    </div>

    {{-- NOTIFIKASI --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm ps-3 pe-5 mb-2" role="alert">
            <i class="fas fa-times-circle me-1"></i> <strong>Gagal menyimpan!</strong> Periksa kembali isian yang ditandai merah di bawah.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.65rem; padding: 0.9rem;"></button>
        </div>
    @endif
    @if($alatExpiredCount > 0)
        <div class="alert alert-warning alert-dismissible fade show shadow-sm ps-3 pe-5 mb-2" role="alert">
            <i class="fas fa-exclamation-triangle me-1"></i> <strong>Perhatian!</strong>
            Terdapat <strong>{{ $alatExpiredCount }} alat</strong> yang kalibrasinya kedaluwarsa atau belum ada, sehingga tidak bisa dipilih.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.65rem; padding: 0.9rem;"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('kegiatan.update', $kegiatan->kegiatan_id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- INFORMASI UMUM --}}
                <h6 class="section-title">Informasi Umum</h6>

                <div class="mb-3">
                    <label for="nama_kegiatan" class="form-label">Nama / Deskripsi Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm @error('nama_kegiatan') is-invalid @enderror" id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}" required placeholder="Contoh: Pengujian Kualitas Air Bersih PT. ABC">
                    @error('nama_kegiatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-2 mb-4">
                    <div class="col-12 col-md-4">
                        <label for="kode_sampel" class="form-label">Kode Sampel</label>
                        <input type="text" class="form-control form-control-sm bg-light @error('kode_sampel') is-invalid @enderror" id="kode_sampel" name="kode_sampel" value="{{ old('kode_sampel', $kegiatan->kode_sampel) }}" readonly>
                        @error('kode_sampel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="tanggal_kegiatan" class="form-label">Tanggal Kegiatan <span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-sm @error('tanggal_kegiatan') is-invalid @enderror" id="tanggal_kegiatan" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->format('Y-m-d')) }}" required>
                        @error('tanggal_kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="status_kegiatan" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm @error('status_kegiatan') is-invalid @enderror" id="status_kegiatan" name="status_kegiatan" required>
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

                {{-- ALAT --}}
                <h6 class="section-title">Alat Digunakan</h6>
                <div class="mb-4">
                    <div class="row g-2">
                        @foreach($alatList as $alat)
                            @php $kalibrasiValid = $alatValid[$alat->alat_id]; @endphp
                            <div class="col-12 col-sm-6 col-xl-4">
                                <div class="form-check alat-item">
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

                {{-- PERSONIL --}}
                <h6 class="section-title">Personil Terlibat</h6>
                <div class="mb-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0 table-stack">
                            <thead>
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
                                    <td data-label="Pilih" class="text-center">
                                        <input class="form-check-input check-lg personil-checkbox" type="checkbox" name="personil_ids[]" value="{{ $personil->personil_id }}" id="personil_{{ $personil->personil_id }}" {{ in_array($personil->personil_id, old('personil_ids', $selectedPersonil)) ? 'checked' : '' }}>
                                    </td>
                                    <td data-label="Nama Personil">
                                        <label for="personil_{{ $personil->personil_id }}" class="mb-0 cursor-pointer">{{ $personil->nama }}</label>
                                    </td>
                                    <td data-label="No Induk">{{ $personil->no_induk ?? '-' }}</td>
                                    <td data-label="Peran">
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

                {{-- BAHAN --}}
                <h6 class="section-title">Bahan Digunakan</h6>
                <div class="mb-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0 table-stack">
                            <thead>
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
                                    <td data-label="Pilih" class="text-center">
                                        <input class="form-check-input check-lg" type="checkbox" name="barang_ids[]" value="{{ $barang->barang_id }}" id="barang_{{ $barang->barang_id }}" {{ $isTerpilih ? 'checked' : '' }} {{ $habis && !$isTerpilih ? 'disabled' : '' }}>
                                    </td>
                                    <td data-label="Nama Barang">
                                        <label for="barang_{{ $barang->barang_id }}" class="mb-0 cursor-pointer {{ $habis && !$isTerpilih ? 'text-muted' : '' }}">
                                            {{ $barang->nama_barang }}
                                            @if($habis && !$isTerpilih)
                                                <span class="badge bg-danger ms-1">Habis</span>
                                            @endif
                                        </label>
                                    </td>
                                    <td data-label="Sisa Stok">{{ number_format($sisaStokRiil, 0, ',', '.') }} {{ $barang->satuan }}</td>
                                    <td data-label="Jumlah">
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

                {{-- PARAMETER UJI --}}
                <h6 class="section-title">Parameter Uji yang Dilakukan</h6>
                <div class="mb-4">
                    <div id="parameter-container">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle mb-0 table-stack">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Nama Parameter Uji</th>
                                        <th class="text-center" style="width: 20%;">Sampel Klien (Reguler)</th>
                                        <th class="text-center" style="width: 20%;">In-House Control</th>
                                        <th class="text-center" style="width: 30%;">Sertifikat CRM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($parameterList as $param)
                                    @php $crmLocked = in_array($param->parameter_uji_id, $selectedCrm ?? []); @endphp
                                    <tr>
                                        <td data-label="Parameter" class="fw-semibold">{{ $param->nama_parameter }}</td>
                                        <td data-label="Reguler" class="text-center">
                                            <input class="form-check-input check-lg param-checkbox param-normal" type="checkbox" name="normal_parameter_uji_ids[]" value="{{ $param->parameter_uji_id }}" id="param_normal_{{ $param->parameter_uji_id }}" {{ in_array($param->parameter_uji_id, old('normal_parameter_uji_ids', $selectedNormal ?? [])) ? 'checked' : '' }}>
                                        </td>
                                        <td data-label="In-House" class="text-center">
                                            <input class="form-check-input check-lg param-checkbox param-inhouse" type="checkbox" name="inhouse_parameter_uji_ids[]" value="{{ $param->parameter_uji_id }}" id="param_inhouse_{{ $param->parameter_uji_id }}" {{ in_array($param->parameter_uji_id, old('inhouse_parameter_uji_ids', $selectedInhouse ?? [])) ? 'checked' : '' }}>
                                        </td>
                                        <td data-label="Sertifikat CRM" class="text-center td-crm">
                                            <div class="crm-cell">
                                                <input class="form-check-input check-lg param-checkbox param-crm {{ $crmLocked ? 'crm-locked' : '' }}" type="checkbox" name="crm_parameter_uji_ids[]" value="{{ $param->parameter_uji_id }}" id="param_crm_{{ $param->parameter_uji_id }}" {{ in_array($param->parameter_uji_id, old('crm_parameter_uji_ids', $selectedCrm ?? [])) ? 'checked' : '' }} @if($crmLocked) onclick="return false;" @endif>

                                                <div class="crm-dropdown-wrapper" id="crm_wrapper_{{ $param->parameter_uji_id }}" style="display: none;">
                                                    <select class="form-select form-select-sm" name="crm_katalog_ids[{{ $param->parameter_uji_id }}]" id="crm_select_{{ $param->parameter_uji_id }}" {{ $crmLocked ? 'disabled' : '' }}>
                                                        <option value="">-- Pilih Botol CRM --</option>
                                                        @foreach($crmKatalogList as $katalog)
                                                            @php
                                                                $isExpired = $katalog->tanggal_expired && \Carbon\Carbon::parse($katalog->tanggal_expired)->isPast();
                                                                $hasParam = $katalog->sertifikats->contains('parameter_uji_id', $param->parameter_uji_id);
                                                                $isSelected = isset($selectedCrmKatalogs[$param->parameter_uji_id]) && $selectedCrmKatalogs[$param->parameter_uji_id] == $katalog->id;
                                                            @endphp
                                                            @if($hasParam)
                                                                <option value="{{ $katalog->id }}" data-expired="{{ $isExpired ? 'true' : 'false' }}" {{ $isSelected ? 'selected' : '' }}>
                                                                    {{ $katalog->nomor_lot }} - {{ $katalog->nama_produk }} {{ $isExpired ? '(KADALUARSA)' : '' }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3 td-empty">Belum ada parameter uji yang terdaftar di master data.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @error('inhouse_parameter_uji_ids')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error('crm_parameter_uji_ids')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                    <div class="alert alert-info mt-2 mb-0">
                        <i class="fas fa-info-circle me-1"></i> <strong>Keamanan Data (Add-Only):</strong> Anda dapat mencentang parameter baru untuk ditambah ke Kegiatan ini. Menghapus centang pada parameter yang sudah terdaftar <b>tidak akan</b> menghapus data hasil ujinya.
                    </div>
                </div>

                <div class="d-flex flex-column-reverse flex-md-row justify-content-md-end gap-2">
                    <a href="{{ route('verifikasi-mutu.index') }}" class="btn btn-outline-secondary btn-sm py-1.5 px-3 fw-semibold">Kembali</a>
                    <button type="submit" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold"><i class="fas fa-save me-1"></i> Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Validasi Live: Alat (Kalibrasi)
    document.querySelectorAll('.alat-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
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
    document.querySelectorAll('.barang-input').forEach(input => {
        input.addEventListener('input', function () {
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

    // Matriks CRM: tampilkan dropdown botol hanya jika checkbox CRM dicentang
    const crmCheckboxes = document.querySelectorAll('.param-crm');

    function updateCrmDropdowns() {
        crmCheckboxes.forEach(cb => {
            const wrapper = document.getElementById('crm_wrapper_' + cb.value);
            const select = document.getElementById('crm_select_' + cb.value);

            if (cb.checked) {
                if (wrapper) wrapper.style.display = 'block';
                if (select && !select.disabled) select.setAttribute('required', 'required');
            } else {
                if (wrapper) wrapper.style.display = 'none';
                if (select && !select.disabled) {
                    select.removeAttribute('required');
                    select.value = '';
                }
            }
        });
    }

    crmCheckboxes.forEach(cb => cb.addEventListener('change', updateCrmDropdowns));
    updateCrmDropdowns();

    // Peringatan bila botol CRM yang dipilih sudah kedaluwarsa
    document.querySelectorAll('.crm-dropdown-wrapper select').forEach(sel => {
        sel.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.dataset.expired === 'true') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Botol CRM Kedaluwarsa',
                    text: 'Botol CRM yang dipilih sudah melewati tanggal kedaluwarsa. Sistem tidak menyarankan botol ini untuk uji akurasi. Gunakan botol lot lain yang masih valid.',
                    confirmButtonColor: '#d33'
                });
            }
        });
    });
});
</script>
@endpush