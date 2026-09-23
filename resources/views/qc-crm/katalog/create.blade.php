@extends('layouts.app')
@section('title', 'Tambah Baru - crm-katalog')

@section('content')
<div class="container-fluid px-4 pb-5">
    <x-qc-breadcrumb active="CRM">
        <li class="breadcrumb-item"><a href="{{ route('crm-katalog.index') }}" class="text-decoration-none">Master Botol CRM</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Botol Baru</li>
    </x-qc-breadcrumb>

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1"><i class="fas fa-plus-circle text-primary me-2"></i> Tambah Master Botol CRM</h2>
        <p class="text-muted mb-0">Masukkan data botol beserta nilai sertifikat untuk setiap parameter sekaligus.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('crm-katalog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-5 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white pt-4 pb-0 border-0">
                        <h5 class="fw-bold"><i class="fas fa-info-circle text-primary me-2"></i>Informasi Botol & Sertifikat</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nomor Lot / Kode Botol <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nomor_lot" required value="{{ old('nomor_lot') }}" placeholder="Contoh: CRM-COAL-001">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Sertifikat</label>
                            <input type="text" class="form-control" name="nomor_sertifikat" value="{{ old('nomor_sertifikat') }}" placeholder="Contoh: CERT-99123">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_produk" required value="{{ old('nama_produk') }}" placeholder="Contoh: Coal Reference Material">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Produsen / Penerbit</label>
                            <input type="text" class="form-control" name="produsen" value="{{ old('produsen') }}" placeholder="Contoh: NCS / NIST">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sertifikat CoA (Certificate of Analysis)</label>
                            <input type="file" class="form-control" name="coa_file" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text">Format PDF/JPG/PNG, maksimal 5MB.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Expired</label>
                            <input type="date" class="form-control" name="tanggal_expired" value="{{ old('tanggal_expired') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white pt-4 pb-0 border-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold"><i class="fas fa-list-check text-success me-2"></i>Daftar Parameter Bersertifikat</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addParamRow"><i class="fas fa-plus"></i> Tambah Parameter</button>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info py-2 small">
                            <i class="fas fa-info-circle"></i> Jika botol ini (CRM Multi-Parameter) memiliki nilai acuan untuk banyak parameter (IM, ASH, CV, dll), masukkan semuanya di sini. Jika hanya Single Parameter (cth: HGI), cukup masukkan 1 saja.
                        </div>

                        <div id="paramWrapper">
                            <!-- Rows will be dynamically added here -->
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Simpan Botol & Nilai Acuan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Template for dynamically adding parameters -->
<template id="paramRowTemplate">
    <div class="row align-items-end mb-3 param-row p-3 bg-light rounded border border-light">
        <div class="col-md-4">
            <label class="form-label small text-muted mb-1">Parameter Uji <span class="text-danger">*</span></label>
            <select name="parameters[__INDEX__][parameter_uji_id]" class="form-select form-select-sm" required>
                <option value="">-- Pilih --</option>
                @foreach($parameterList as $p)
                    <option value="{{ $p->parameter_uji_id }}">{{ $p->nama_parameter }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small text-muted mb-1">True Value <span class="text-danger">*</span></label>
            <input type="number" step="0.0001" name="parameters[__INDEX__][cert_value]" class="form-control form-select-sm" required placeholder="0.00">
        </div>
        <div class="col-md-4">
            <label class="form-label small text-muted mb-1">Ketidakpastian (±) <span class="text-danger">*</span></label>
            <div class="input-group input-group-sm">
                <span class="input-group-text">±</span>
                <input type="number" step="0.0001" name="parameters[__INDEX__][cert_u]" class="form-control" required placeholder="0.00">
            </div>
        </div>
        <div class="col-md-1 text-end">
            <button type="button" class="btn btn-sm btn-outline-danger remove-param"><i class="fas fa-times"></i></button>
        </div>
    </div>
</template>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paramWrapper = document.getElementById('paramWrapper');
        const addBtn = document.getElementById('addParamRow');
        const template = document.getElementById('paramRowTemplate').innerHTML;
        let paramIndex = 0;

        // Function to add a new parameter row
        function addRow() {
            const rowHtml = template.replace(/__INDEX__/g, paramIndex);
            paramWrapper.insertAdjacentHTML('beforeend', rowHtml);
            paramIndex++;
        }

        // Add 1 default row on load
        addRow();

        // Handle Add Button
        addBtn.addEventListener('click', addRow);

        // Handle Remove Button (Event Delegation)
        paramWrapper.addEventListener('click', function(e) {
            if (e.target.closest('.remove-param')) {
                const row = e.target.closest('.param-row');
                // Prevent removing the very last row to avoid confusion, or let them remove all
                if(paramWrapper.querySelectorAll('.param-row').length > 1) {
                    row.remove();
                } else {
                    alert('Minimal harus ada 1 parameter uji.');
                }
            }
        });
    });
</script>
@endpush
