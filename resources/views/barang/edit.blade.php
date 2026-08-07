@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Inventori Barang</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Inventori Barang</a></li>
        <li class="breadcrumb-item active">Edit Barang</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-edit me-1"></i> Form Edit Data Barang Persediaan</div>
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

            <form action="{{ route('barang.update', $barang->barang_id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h5 class="text-primary mb-3"><i class="fas fa-box"></i> Identitas Barang</h5>
                <div class="row mb-3">
                    <div class="col-md-5">
                        <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang', $barang->nama_barang) }}" placeholder="mis. PLASTIK SEAL MERAH" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="satuan" class="form-control" value="{{ old('satuan', $barang->satuan) }}" placeholder="mis. Pieces" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kode Barang <span class="text-danger">*</span></label>
                        <input type="text" name="kode_barang" class="form-control bg-light" value="{{ old('kode_barang', $barang->kode_barang) }}" placeholder="mis. 1.23.456" required readonly>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="text-primary mb-3"><i class="fas fa-calculator"></i> Data Stok, Harga & Kondisi</h5>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Minimal Stock</label>
                        <input type="number" step="any" name="minimal_stok" class="form-control" value="{{ old('minimal_stok', $barang->minimal_stok == 0 ? '' : 0 + $barang->minimal_stok) }}" placeholder="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Saldo Awal</label>
                        <input type="number" step="any" name="saldo_awal" class="form-control" value="{{ old('saldo_awal', $barang->saldo_awal == 0 ? '' : 0 + $barang->saldo_awal) }}" placeholder="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Penerimaan</label>
                        <input type="number" step="any" name="penerimaan" class="form-control" value="{{ old('penerimaan', $barang->penerimaan == 0 ? '' : 0 + $barang->penerimaan) }}" placeholder="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Pengeluaran</label>
                        <input type="number" step="any" name="pengeluaran" class="form-control" value="{{ old('pengeluaran', $barang->pengeluaran == 0 ? '' : 0 + $barang->pengeluaran) }}" placeholder="0">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Harga Rata-rata Tertimbang (Rp)</label>
                        <input type="number" step="any" name="harga_rata" class="form-control" value="{{ old('harga_rata', $barang->harga_rata == 0 ? '' : 0 + $barang->harga_rata) }}" placeholder="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kondisi Barang</label>
                        <select name="kondisi" class="form-select" required>
                            <option value="">--Pilih Kondisi--</option>
                            <option value="baik" {{ old('kondisi', $barang->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak" {{ old('kondisi', $barang->kondisi) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Expired Date</label>
                        <input type="date" name="tgl_exp" class="form-control" value="{{ old('tgl_exp', $barang->tgl_exp) }}">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui Data Barang</button>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection