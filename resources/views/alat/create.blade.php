@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah Alat & Informasi Kalibrasi</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('alat.index') }}">Alat</a></li>
        <li class="breadcrumb-item active">Tambah</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-plus-circle me-1"></i> Form Input Data Master & Kalibrasi</div>
        <div class="card-body">
            <form action="{{ route('alat.store') }}" method="POST">
                @csrf
                
                <h5 class="text-primary mb-3"><i class="fas fa-tools"></i> Informasi Spesifikasi Alat</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode Alat (CODE) <span class="text-danger">*</span></label>
                        <input type="text" name="kode_alat" class="form-control @error('kode_alat') is-invalid @enderror" placeholder="mis. CLC1204-10001" required>
                        @error('kode_alat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Barang / Alat <span class="text-danger">*</span></label>
                        <input type="text" name="nama_alat" class="form-control @error('nama_alat') is-invalid @enderror"  placeholder="mis. Sulfur Analyzer" required>
                        @error('nama_alat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Merk / Tipe</label>
                        <input type="text" name="merk_tipe" class="form-control" placeholder="mis. Labfit CS 1232">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Serial Number</label>
                        <input type="text" name="no_seri" class="form-control"  placeholder="mis. 17050068">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Warna</label>
                        <input type="text" name="warna" class="form-control"  placeholder="mis. WHITE">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Ukuran</label>
                        <input type="text" name="ukuran" class="form-control" placeholder="Ukuran">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Unit Kerja Pemilik</label>
                        <input type="text" name="unit_kerja_pemilik" class="form-control" placeholder="Nama Unit Kerja">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Kondisi Barang</label>
                        <select name="kondisi_barang" class="form-select">
                            <option>--Pilih Kondisi--</option>
                            <option value="baik">Baik</option>
                            <option value="rusak">Rusak</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status Barang</label>
                        <select name="status_barang" class="form-select">
                            <option>--Pilih Status--</option>
                            <option value="idle">Idle</option>
                            <option value="terpakai">Terpakai</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="text-primary mb-3"><i class="fas fa-certificate"></i> Informasi Kalibrasi Terakhir</h5>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">No. Sertifikat Kalibrasi / Perijinan</label>
                        <input type="text" name="no_sertifikat" class="form-control" placeholder="mis. 20059/ENBPAQ">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Interval Kalibrasi</label>
                        <input type="text" name="interval_kalibrasi" class="form-control" placeholder="mis. 1 TAHUN">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Kalibrasi</label>
                        <input type="date" name="tgl_kalibrasi" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Berakhirnya Masa Kalibrasi</label>
                        <input type="date" name="tgl_akhir" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Lembaga Kalibrasi</label>
                        <input type="text" name="lembaga_kalibrasi" class="form-control" placeholder="mis. PT SUCOFINDO CILACAP">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Kalibrasi</label>
                        <select name="jenis_kalibrasi" class="form-select" >
                            <option>--Pilih Jenis Kalibrasi--</option>
                            <option value="eksternal">Eksternal</option>
                            <option value="internal">Internal</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Range / Kapasitas</label>
                        <input type="text" name="range_kapasitas" class="form-control" value="{{ old('range_kapasitas') }}" placeholder="mis. 0 - 1400 °C">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Faktor Koreksi</label>
                        <input type="text" name="faktor_koreksi" class="form-control" value="{{ old('faktor_koreksi') }}" placeholder="mis. 32 °C">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Signifikan</label>
                        <select name="signifikan" class="form-select">
                            <option>--Pilih Signifikan--</option>
                            <option value="tidak">Tidak</option>
                            <option value="ya">Ya</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data Alat & Kalibrasi</button>
                    <a href="{{ route('alat.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
