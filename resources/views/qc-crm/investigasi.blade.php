@extends('layouts.app')
@section('title', 'Investigasi Outlier - Pengujian CRM')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="CRM">
        <li class="breadcrumb-item">
            <a href="{{ route('qc-crm.index', ['tab' => 'harian']) }}" class="text-decoration-none">Pengujian Harian CRM</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Investigasi CAPA</li>
    </x-qc-breadcrumb>

    <h2 class="mb-4 fw-bold text-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>Tindakan Perbaikan (CAPA) Pengujian Harian CRM Outlier
    </h2>

    <div class="row">
        <!-- Kolom Kiri: Detail Pengujian -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0 border-top border-danger border-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-flask text-danger me-2"></i>Detail Parameter Outlier</h5>
                    
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted w-50">Nomor Lot</td>
                            <td class="fw-bold">{{ $qc->crmKatalog->nomor_lot ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nama CRM</td>
                            <td class="fw-bold">{{ $qc->crmKatalog->nama_produk ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Parameter</td>
                            <td class="fw-bold text-primary fs-5">{{ strtoupper($qc->parameterUji->nama_parameter ?? '-') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Certified Value (TV)</td>
                            <td class="fw-bold">{{ number_format($qc->cert_value, 4) }} &plusmn; {{ number_format($qc->cert_u, 4) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Rentang Diterima</td>
                            <td class="fw-bold text-success">{{ number_format($qc->cert_value - $qc->cert_u, 4) }} - {{ number_format($qc->cert_value + $qc->cert_u, 4) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nilai Rata-rata Uji</td>
                            <td class="fw-bold text-danger fs-5">{{ number_format($qc->nilai_akhir, 4) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal Uji</td>
                            <td class="fw-bold">{{ $qc->tanggal_uji ? \Carbon\Carbon::parse($qc->tanggal_uji)->format('d M Y') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h6 class="fw-bold text-secondary mb-2"><i class="fas fa-shield-alt me-2"></i>Aturan Mutu ISO 17025</h6>
                    <p class="small text-muted mb-0">
                        Hasil pengujian parameter ini berada di luar rentang sertifikat CRM. Anda diwajibkan melakukan investigasi akar masalah serta menentukan tindakan perbaikan sebelum pengujian dinyatakan selesai.
                    </p>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Form CAPA -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-clipboard-check text-primary me-2"></i>Laporan Tindakan Perbaikan (CAPA)</h5>
                    
                    <form action="{{ route('qc-crm.investigasi.store', $qc->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Akar Masalah <span class="text-danger">*</span></label>
                            <select name="akar_masalah" class="form-select" required>
                                <option value="">-- Pilih Kategori Akar Masalah --</option>
                                <option value="Kondisi Instrumen">Kondisi Instrumen / Alat</option>
                                <option value="Human Error">Human Error (Analis / Preparasi)</option>
                                <option value="Kondisi Reagen / Bahan">Kondisi Reagen / Bahan Kimia</option>
                                <option value="Kondisi Lingkungan">Kondisi Lingkungan Pengujian</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Detail Investigasi (Temuan) <span class="text-danger">*</span></label>
                            <p class="small text-muted mb-2">Jelaskan temuan spesifik mengapa pengujian bisa menyimpang (outlier).</p>
                            <textarea name="catatan_investigasi" rows="4" class="form-control" placeholder="Contoh: Suhu furnace pembakaran belum stabil saat sampel dimasukkan..." required minlength="10"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Tindakan Perbaikan (Corrective Action) <span class="text-danger">*</span></label>
                            <p class="small text-muted mb-2">Tindakan nyata yang dilakukan agar pengujian selanjutnya valid.</p>
                            <textarea name="tindakan_perbaikan" rows="4" class="form-control" placeholder="Contoh: Melakukan kalibrasi ulang thermocouple dan preparasi ulang..." required minlength="10"></textarea>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('qc-crm.index', ['tab' => 'harian']) }}" class="btn btn-light border px-4">Batal</a>
                            <button type="submit" class="btn btn-danger px-4 shadow-sm fw-bold">
                                <i class="fas fa-save me-2"></i>Simpan Laporan CAPA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection