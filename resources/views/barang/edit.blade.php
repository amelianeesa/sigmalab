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

            @php
                $roleName = Auth::user()->role->nama_role ?? '';
                $isAuthorizedForPricing = in_array($roleName, [
                    \App\Enums\PeranPengguna::HR_GA_OFFICER->value, 
                    \App\Enums\PeranPengguna::ADMIN_APLIKASI->value
                ]);
            @endphp            

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
                        <label class="form-label">Kode Barang / Bahan <span class="text-danger">*</span></label>
                        <input type="text" name="kode_barang" class="form-control" value="{{ old('kode_barang', $barang->kode_barang) }}" placeholder="mis. 1.23.456" required>
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
                        <label class="form-label">Pengeluaran Terbaru</span></label>
                        <input type="number" step="any" name="pengeluaran" class="form-control" placeholder="0" min="0">
                        
                        @php
                            $sisaStok = ($barang->saldo_awal + $barang->penerimaan) - $barang->pengeluaran;
                        @endphp
                        <small class="text-muted fw-bold">Sisa stok saat ini: {{ number_format($sisaStok, 0, ',', '.') }} {{ $barang->satuan }}</small>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Harga Rata-rata Tertimbang (Rp)</label>
                        <input type="number" step="any" name="harga_rata" class="form-control" value="{{ old('harga_rata', $barang->harga_rata == 0 ? '' : 0 + $barang->harga_rata) }}" placeholder="0"
                        {{ !$isAuthorizedForPricing ? 'readonly tabindex="-1" aria-disabled="true" style=pointer-events:none;background-color:#e9ecef;' : '' }}>
                    
                        @if(!$isAuthorizedForPricing)
                            <small class="text-muted">Harga terkunci (Hanya dapat diubah oleh HR & GA / Admin).</small>
                        @endif                    
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

                <div class="alert alert-info py-2" style="font-size: 0.85rem;">
                    <i class="fas fa-info-circle me-1"></i> <strong>Catatan:</strong> Masukkan jumlah pengeluaran terbaru pada kolom di atas. Sistem akan otomatis menjumlahkannya dengan total sebelumnya dan memotong stok batch secara FEFO (Expired terdekat).
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