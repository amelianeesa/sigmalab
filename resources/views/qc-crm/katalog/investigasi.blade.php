@extends('layouts.app')
@section('title', 'Investigasi Outlier - ' . $katalog->nomor_lot)

@section('content')
@include('qc-crm._qc-compact')

<style>
    .qc-page {
        padding: 0 20px !important;
        margin-top: -8px !important;
    }

    .qc-page .table-detail td {
        padding: 6px 8px !important;
        font-size: 0.78rem !important;
    }

    .qc-page .table-detail td.fs-5 {
        font-size: 0.85rem !important;
    }

    @media (max-width: 767.98px) {
        .qc-page { padding: 0 10px !important; }
    }
</style>

<div class="container-fluid qc-page qc-compact pb-4">
    <x-qc-breadcrumb active="Investigasi Outlier">
        <li class="breadcrumb-item"><a href="{{ route('qc-crm.index') }}" class="text-decoration-none">Master Botol CRM</a></li>
        <li class="breadcrumb-item"><a href="{{ route('crm-katalog.show', $katalog->id) }}" class="text-decoration-none">{{ $katalog->nomor_lot }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Investigasi CAPA</li>
    </x-qc-breadcrumb>

    <h5 class="fw-bold text-danger mt-2 mb-3" style="font-size: 1.1rem;">
        Tindakan Perbaikan (CAPA) Verifikasi Teknis Outlier
    </h5>

    <div class="row g-3">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 border-top border-danger border-4 mb-3">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-2"><i class="fas fa-flask text-danger me-2"></i>Detail Parameter Outlier</h6>

                    <table class="table table-borderless table-detail mb-0">
                        <tr>
                            <td class="text-muted w-50">Nomor Lot</td>
                            <td class="fw-bold">{{ $katalog->nomor_lot }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nama CRM</td>
                            <td class="fw-bold">{{ $katalog->nama_produk }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Parameter</td>
                            <td class="fw-bold text-primary fs-5">{{ strtoupper($verif->parameterUji->nama_parameter) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Certified Value (TV)</td>
                            <td class="fw-bold">{{ number_format($verif->cert_value, 4) }} &plusmn; {{ number_format($verif->cert_u, 4) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Rentang Diterima</td>
                            <td class="fw-bold text-success">{{ number_format($verif->batas_bawah, 4) }} - {{ number_format($verif->batas_atas, 4) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nilai Rata-rata Uji</td>
                            <td class="fw-bold text-danger fs-5">{{ number_format($verif->nilai_akhir, 4) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal Uji</td>
                            <td class="fw-bold">{{ $verif->tanggal_uji ? \Carbon\Carbon::parse($verif->tanggal_uji)->format('d M Y') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body p-3">
                    <h6 class="fw-bold text-secondary mb-2"><i class="fas fa-shield-alt me-2"></i>Aturan Mutu ISO 17025</h6>
                    <p class="small text-muted mb-0">
                        Parameter ini berada di luar rentang sertifikat CRM. Anda diwajibkan melakukan investigasi akar masalah serta menentukan tindakan perbaikan sebelum diperbolehkan melakukan <strong>Uji Ulang (Retest)</strong>.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-3"><i class="fas fa-clipboard-check me-2" style="color: var(--sl-primary);"></i>Laporan Tindakan Perbaikan (CAPA)</h6>

                    <form action="{{ route('crm-katalog.verifikasi-teknis.investigasi.store', [$katalog->id, $verif->id]) }}" method="POST">
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
                            <p class="small text-muted mb-1">Jelaskan temuan spesifik mengapa pengujian bisa menyimpang (outlier).</p>
                            <textarea name="catatan_investigasi" rows="4" class="form-control" placeholder="Contoh: Suhu furnace pembakaran belum stabil saat sampel dimasukkan..." required minlength="10"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tindakan Perbaikan (Corrective Action) <span class="text-danger">*</span></label>
                            <p class="small text-muted mb-1">Tindakan nyata yang dilakukan agar pengujian ulang valid.</p>
                            <textarea name="tindakan_perbaikan" rows="4" class="form-control" placeholder="Contoh: Melakukan kalibrasi ulang thermocouple dan preparasi ulang..." required minlength="10"></textarea>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('qc-crm.index') }}" class="btn btn-light border btn-sm px-3">Batal</a>
                            <button type="submit" class="btn btn-danger btn-sm px-3 shadow-sm fw-bold">
                                <i class="fas fa-save me-1"></i>Simpan Laporan & Buka Kunci Uji Ulang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection