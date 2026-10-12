@extends('layouts.app')
@section('title', 'Tambah Baru - crm-katalog')

@section('content')
@include('qc-crm._qc-compact')

<style>
    .qc-page { padding: 4px 20px !important; }

    .qc-page .btn-corporate-blue {
        background-color: #1b3152 !important; border-color: #1b3152 !important; color: #fff !important;
    }
    .qc-page .btn-corporate-blue:hover, .qc-page .btn-corporate-blue:focus, .qc-page .btn-corporate-blue:active {
        background-color: #14253e !important; border-color: #14253e !important; color: #fff !important;
    }
    .qc-page .btn-outline-corporate { color: #1b3152 !important; border: 1px solid #1b3152 !important; background: #fff !important; }
    .qc-page .btn-outline-corporate:hover { background-color: #1b3152 !important; color: #fff !important; }

    .qc-page .select2-container--bootstrap-5 .select2-selection { font-size: 0.8rem; min-height: 0; padding: 0.2rem 0.4rem; }

    @media (max-width: 767.98px) {
        .qc-page { padding: 4px 10px !important; }
    }
</style>

<div class="container-fluid qc-page qc-compact pb-4">
    <x-qc-breadcrumb active="CRM">
        <li class="breadcrumb-item"><a href="{{ route('crm-katalog.index') }}" class="text-decoration-none">Master Botol CRM</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Botol Baru</li>
    </x-qc-breadcrumb>

    <div class="mt-2 mb-3">
        <h5 class="fw-bold mb-0" style="font-size: 1.1rem;">
            <i class="fas fa-plus-circle me-2" style="color: #1b3152;"></i>Tambah Master Botol CRM
        </h5>
        <div class="text-muted" style="font-size: 0.78rem;">Masukkan data botol beserta nilai sertifikat untuk setiap parameter sekaligus.</div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm border-0 py-2">
            <i class="fas fa-exclamation-triangle me-1"></i> Terdapat kesalahan pada isian form:
            <ul class="mb-0 mt-1 ps-3">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('crm-katalog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            {{-- Informasi Botol --}}
            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-3 pb-0">
                        <h5 class="fw-bold mb-0"><i class="fas fa-info-circle me-2" style="color: #1b3152;"></i>Informasi Botol & Sertifikat</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="form-label">Nomor Lot / Kode Botol <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="nomor_lot" required value="{{ old('nomor_lot') }}" placeholder="Contoh: CRM-COAL-001">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Nomor Sertifikat</label>
                            <input type="text" class="form-control form-control-sm" name="nomor_sertifikat" value="{{ old('nomor_sertifikat') }}" placeholder="Contoh: CERT-99123">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="nama_produk" required value="{{ old('nama_produk') }}" placeholder="Contoh: Coal Reference Material">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Produsen / Penerbit</label>
                            <input type="text" class="form-control form-control-sm" name="produsen" value="{{ old('produsen') }}" placeholder="Contoh: NCS / NIST">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Sertifikat CoA (Certificate of Analysis)</label>
                            <input type="file" class="form-control form-control-sm" name="coa_file" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text">Format PDF/JPG/PNG, maksimal 5MB.</div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Tanggal Expired</label>
                            <input type="date" class="form-control form-control-sm" name="tanggal_expired" value="{{ old('tanggal_expired') }}">
                            <div id="expiredHint" class="form-text d-none"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Daftar Parameter --}}
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-3 pb-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <h5 class="fw-bold mb-0"><i class="fas fa-list-check me-2" style="color: #1b3152;"></i>Daftar Parameter Bersertifikat</h5>
                        <button type="button" class="btn btn-sm btn-corporate-blue shadow-sm fw-semibold" id="addParamRow"><i class="fas fa-plus me-1"></i> Tambah Parameter</button>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info py-2 small">
                            <i class="fas fa-info-circle me-1"></i> Jika botol ini (CRM Multi-Parameter) memiliki nilai acuan untuk banyak parameter (IM, ASH, CV, dll), masukkan semuanya di sini. Jika hanya Single Parameter (cth: HGI), cukup masukkan 1 saja.
                        </div>

                        <div id="paramWrapper">
                            {{-- Baris parameter ditambahkan lewat JavaScript --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol aksi --}}
        <div class="d-grid d-md-flex justify-content-md-end gap-2 mt-3">
            <a href="{{ route('crm-katalog.index') }}" class="btn btn-light border order-last order-md-first">Batal</a>
            <button type="submit" class="btn btn-corporate-blue shadow-sm fw-semibold px-4"><i class="fas fa-save me-1"></i> Simpan Botol & Nilai Acuan</button>
        </div>
    </form>
</div>

{{-- Template baris parameter --}}
<template id="paramRowTemplate">
    <div class="row g-2 align-items-end mb-2 param-row p-2 bg-light rounded border">
        <div class="col-12 col-md-4">
            <label class="form-label small text-muted mb-1">Parameter Uji <span class="text-danger">*</span></label>
            <select name="parameters[__INDEX__][parameter_uji_id]" class="form-select form-select-sm" required>
                <option value="">-- Pilih --</option>
                @foreach($parameterList as $p)
                    <option value="{{ $p->parameter_uji_id }}">{{ $p->nama_parameter }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-3">
            <label class="form-label small text-muted mb-1">True Value <span class="text-danger">*</span></label>
            <input type="number" step="0.0001" name="parameters[__INDEX__][cert_value]" class="form-control form-control-sm" required placeholder="0.00" inputmode="decimal">
        </div>
        <div class="col-6 col-md-4">
            <label class="form-label small text-muted mb-1">Ketidakpastian (±) <span class="text-danger">*</span></label>
            <div class="input-group input-group-sm">
                <span class="input-group-text">±</span>
                <input type="number" step="0.0001" name="parameters[__INDEX__][cert_u]" class="form-control" required placeholder="0.00" inputmode="decimal">
            </div>
        </div>
        <div class="col-12 col-md-1 d-grid d-md-block text-md-end">
            <button type="button" class="btn btn-sm btn-outline-danger remove-param" title="Hapus baris">
                <i class="fas fa-times"></i><span class="d-md-none ms-1">Hapus baris</span>
            </button>
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

        function addRow(data = null) {
            paramWrapper.insertAdjacentHTML('beforeend', template.replace(/__INDEX__/g, paramIndex));
            const row = paramWrapper.lastElementChild;

            if (data) {
                if (data.parameter_uji_id) {
                    row.querySelector(`select[name="parameters[${paramIndex}][parameter_uji_id]"]`).value = data.parameter_uji_id;
                }
                if (data.cert_value !== undefined) {
                    row.querySelector(`input[name="parameters[${paramIndex}][cert_value]"]`).value = data.cert_value;
                }
                if (data.cert_u !== undefined) {
                    row.querySelector(`input[name="parameters[${paramIndex}][cert_u]"]`).value = data.cert_u;
                }
            }

            // Select2 dipasang setelah nilai awal terisi
            $(row).find('select').select2({ theme: 'bootstrap-5', width: '100%' });

            paramIndex++;
        }

        // Kembalikan isian lama jika validasi gagal, kalau tidak: 1 baris kosong
        const oldParams = @json(old('parameters', []));
        const oldParamsArray = Object.values(oldParams || {});

        if (oldParamsArray.length > 0) {
            oldParamsArray.forEach(param => addRow(param));
        } else {
            addRow();
        }

        addBtn.addEventListener('click', () => addRow());
        paramWrapper.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-param');
            if (!btn) return;

            if (paramWrapper.querySelectorAll('.param-row').length > 1) {
                btn.closest('.param-row').remove();
            } else if (typeof Swal !== 'undefined') {
                Swal.fire('Tidak Bisa Dihapus', 'Minimal harus ada 1 parameter uji.', 'warning');
            } else {
                alert('Minimal harus ada 1 parameter uji.');
            }
        });

        const expInput = document.querySelector('input[name="tanggal_expired"]');
        const expHint = document.getElementById('expiredHint');

        function checkExpired() {
            expHint.classList.add('d-none');
            expHint.classList.remove('text-danger', 'text-warning');
            if (!expInput.value) return;

            const today = new Date(); today.setHours(0, 0, 0, 0);
            const diff = Math.round((new Date(expInput.value) - today) / 86400000);

            if (diff < 0) {
                expHint.textContent = 'Tanggal ini sudah lewat — botol tergolong kedaluwarsa.';
                expHint.classList.add('text-danger');
            } else if (diff <= 90) {
                expHint.textContent = 'Botol akan kedaluwarsa dalam ' + diff + ' hari.';
                expHint.classList.add('text-warning');
            } else {
                return;
            }
            expHint.classList.remove('d-none');
        }

        expInput.addEventListener('change', checkExpired);
        checkExpired();
    });
</script>
@endpush