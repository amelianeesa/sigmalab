@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Inventori Barang</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Inventori Barang</a></li>
        <li class="breadcrumb-item active">Tambah Barang</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-plus-circle me-1"></i> Form Input Data Barang Persediaan</div>
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

            <form action="{{ route('barang.store') }}" method="POST">
                @csrf
                
                <h5 class="text-primary mb-3"><i class="fas fa-box"></i> Identitas Barang</h5>
                <div class="row mb-3">
                    <div class="col-md-5">
                        <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang" class="form-control" placeholder="mis. PLASTIK SEAL MERAH" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="satuan" class="form-control" placeholder="mis. Pieces" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kode Barang <span class="text-danger">*</span></label>
                        <input type="text" name="kode_barang" class="form-control" placeholder="mis. 1.23.456" required>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="text-primary mb-3"><i class="fas fa-calculator"></i> Data Stok, Harga & Kondisi</h5>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Minimal Stock</label>
                        <input type="number" step="0" name="minimal_stok" class="form-control" placeholder="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Saldo Awal</label>
                        <input type="number" step="0" name="saldo_awal" class="form-control" placeholder="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Penerimaan</label>
                        <input type="number" step="0" name="penerimaan" class="form-control" placeholder="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Pengeluaran</label>
                        <input type="number" step="0" name="pengeluaran" class="form-control" placeholder="0">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Harga Rata-rata Tertimbang (Rp)</label>
                        <input type="number" step="0.01" name="harga_rata" class="form-control" placeholder="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kondisi Barang</label>
                        <select name="kondisi" class="form-select" required>
                            <option>--Pilih Kondisi--</option>
                            <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak" {{ old('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Expired Date</label>
                        <input type="date" name="tgl_exp" class="form-control" value="{{ old('tgl_exp') }}">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data Barang</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection