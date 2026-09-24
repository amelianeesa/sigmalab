@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')

<style>
    :root {
        --brand-navy: #1b3a5c;
        --brand-navy-light: #2c5282;
    }

    .card-header-custom {
        background-color: var(--brand-navy) !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.82rem;
        padding: 0.4rem 0.8rem;
    }

    .btn-brand-standard {
        background-color: var(--brand-navy);
        border-color: var(--brand-navy);
        color: #ffffff;
        font-size: 0.78rem;
        padding: 0.3rem 0.85rem;
        border-radius: 0.375rem; 
        transition: transform 0.15s, background-color 0.15s;
    }
    .btn-brand-standard:hover {
        background-color: var(--brand-navy-light);
        border-color: var(--brand-navy-light);
        color: #ffffff;
        transform: translateY(-2px);
    }

    .form-control-sm,
    .form-select-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .form-label {
        margin-bottom: 0.2rem;
        font-size: 0.75rem;
    }
</style>

<div class="container-fluid px-4">
    <h5 class="fw-bold mb-2">Inventori Barang/Bahan</h5>

    <div class="card mb-3 shadow-sm">
        <div class="card-header card-header-custom"><i class="fas fa-edit me-1"></i> Form Edit Data Barang Persediaan</div>
        <div class="card-body py-3" style="font-size: 0.8rem;">
            
            @if ($errors->any())
                <div class="alert alert-danger py-1 mb-2" style="font-size: 0.75rem;">
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
                    'GA', 
                    'Admin Aplikasi'
                ]);
            @endphp          

            <form action="{{ route('barang.update', $barang->barang_id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <h6 class="fw-bold mb-2 text-dark" style="font-size: 0.85rem;">Identitas Barang</h6>
                <div class="row mb-2">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control form-control-sm" value="{{ old('nama_barang', $barang->nama_barang) }}" placeholder="mis. PLASTIK SEAL MERAH" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Satuan</label>
                        <input type="text" name="satuan" class="form-control form-control-sm" value="{{ old('satuan', $barang->satuan) }}" placeholder="mis. Pieces" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Kode Barang / Bahan</label>
                        <input type="text" name="kode_barang" class="form-control form-control-sm" value="{{ old('kode_barang', $barang->kode_barang) }}" placeholder="mis. 1.23.456" required>
                    </div>
                </div>

                <hr class="my-2">

                <h6 class="fw-bold mb-2 text-dark" style="font-size: 0.85rem;">Data Stok, Harga & Kondisi</h6>
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Minimal Stock</label>
                        <input type="number" step="any" name="minimal_stok" class="form-control form-control-sm" value="{{ old('minimal_stok', $barang->minimal_stok == 0 ? '' : 0 + $barang->minimal_stok) }}" placeholder="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Saldo Awal</label>
                        <input type="number" step="any" name="saldo_awal" class="form-control form-control-sm" value="{{ old('saldo_awal', $barang->saldo_awal == 0 ? '' : 0 + $barang->saldo_awal) }}" placeholder="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Penerimaan</label>
                        <input type="number" step="any" name="penerimaan" class="form-control form-control-sm" value="{{ old('penerimaan', $barang->penerimaan == 0 ? '' : 0 + $barang->penerimaan) }}" placeholder="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Pengeluaran Terbaru</label>
                        <input type="number" step="any" name="pengeluaran" class="form-control form-control-sm" placeholder="0" min="0">
                        
                        @php
                            $sisaStok = ($barang->saldo_awal + $barang->penerimaan) - $barang->pengeluaran;
                        @endphp
                        <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Sisa stok: <strong>{{ number_format($sisaStok, 0, ',', '.') }} {{ $barang->satuan }}</strong></small>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Harga Rata-rata Tertimbang (Rp)</label>
                        <input type="number" step="any" name="harga_rata" class="form-control form-control-sm" value="{{ old('harga_rata', $barang->harga_rata == 0 ? '' : 0 + $barang->harga_rata) }}" placeholder="0"
                        {{ !$isAuthorizedForPricing ? 'readonly tabindex="-1" aria-disabled="true" style=pointer-events:none;background-color:#e9ecef;' : '' }}>
                    
                        @if(!$isAuthorizedForPricing)
                            <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Harga terkunci (Hanya dapat diubah oleh GA & Admin Aplikasi).</small>
                        @endif                    
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Kondisi Barang</label>
                        <select name="kondisi" class="form-select form-select-sm" required>
                            <option value="">--Pilih Kondisi--</option>
                            <option value="baik" {{ old('kondisi', $barang->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="rusak" {{ old('kondisi', $barang->kondisi) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tanggal Expired Date</label>
                        <input type="date" name="tgl_exp" class="form-control form-control-sm" value="{{ old('tgl_exp', $barang->tgl_exp) }}">
                    </div>
                </div>

                <div class="alert alert-info py-1 px-2 mb-2" style="font-size: 0.73rem;">
                    <i class="fas fa-info-circle me-1"></i> <strong>Catatan:</strong> Masukkan jumlah pengeluaran terbaru pada kolom di atas. Sistem akan otomatis menjumlahkannya dengan total sebelumnya dan memotong stok batch secara FEFO (Expired terdekat).
                </div>

                <div class="mt-2 d-flex justify-content-end gap-2">
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary shadow-sm" style="font-size: 0.78rem; padding: 0.3rem 0.85rem; border-radius: 0.375rem;"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
                    <button type="submit" class="btn btn-brand-standard shadow-sm"><i class="fas fa-save me-1"></i> Perbarui Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection