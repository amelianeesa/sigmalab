@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Tambah Kegiatan</h1>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('kegiatan.store') }}" method="POST">
                @csrf
                
                <h5 class="mb-3 text-primary border-bottom pb-2">Informasi Umum</h5>
                
                <div class="mb-3">
                    <label for="nama_kegiatan" class="form-label">Nama / Deskripsi Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror" id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required placeholder="Contoh: Pengujian Kualitas Air Bersih PT. ABC">
                    @error('nama_kegiatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="jenis_kegiatan" class="form-label">Jenis Kegiatan <span class="text-danger">*</span></label>
                        <select class="form-select @error('jenis_kegiatan') is-invalid @enderror" id="jenis_kegiatan" name="jenis_kegiatan" required>
                            <option value="">Pilih Jenis Kegiatan</option>
                            <option value="pengujian" {{ old('jenis_kegiatan') == 'pengujian' ? 'selected' : '' }}>Pengujian</option>
                            <option value="kalibrasi" {{ old('jenis_kegiatan') == 'kalibrasi' ? 'selected' : '' }}>Kalibrasi</option>
                        </select>
                        @error('jenis_kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="kode_sampel" class="form-label">Kode Sampel</label>
                        <input type="text" class="form-control bg-light @error('kode_sampel') is-invalid @enderror" id="kode_sampel" name="kode_sampel" value="{{ old('kode_sampel', $nextKodeSampel) }}" readonly>
                        @error('kode_sampel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="tanggal_kegiatan" class="form-label">Tanggal Kegiatan <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal_kegiatan') is-invalid @enderror" id="tanggal_kegiatan" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', date('Y-m-d')) }}" required>
                        @error('tanggal_kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="status_kegiatan" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status_kegiatan') is-invalid @enderror" id="status_kegiatan" name="status_kegiatan" required>
                            <option value="draft" {{ old('status_kegiatan') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="berjalan" {{ old('status_kegiatan') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            <option value="selesai" {{ old('status_kegiatan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ old('status_kegiatan') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
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
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="alat_ids[]" value="{{ $alat->alat_id }}" id="alat_{{ $alat->alat_id }}" {{ in_array($alat->alat_id, old('alat_ids', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="alat_{{ $alat->alat_id }}">
                                    {{ $alat->nama_alat }} ({{ $alat->kode_alat }})
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
                                        <input class="form-check-input personil-checkbox" type="checkbox" name="personil_ids[]" value="{{ $personil->pengguna_id }}" id="personil_{{ $personil->pengguna_id }}" {{ in_array($personil->pengguna_id, old('personil_ids', [])) ? 'checked' : '' }}>
                                    </td>
                                    <td class="align-middle">
                                        <label for="personil_{{ $personil->pengguna_id }}" class="mb-0 cursor-pointer">{{ $personil->nama }}</label>
                                    </td>
                                    <td class="align-middle">{{ $personil->no_induk ?? '-' }}</td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="personil_peran[{{ $personil->pengguna_id }}]" value="{{ old('personil_peran.'.$personil->pengguna_id, 'Analis') }}" placeholder="Peran (mis: Analis)">
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
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
