@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">

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

    .flatpickr-input {
        font-size: 0.75rem;
    }
    .flatpickr-calendar {
        font-size: 0.8rem;
    }

    .filter-select {
        position: relative;
        font-size: 0.75rem;
    }
    .filter-select-trigger {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        cursor: pointer;
        text-align: left;
        color: #212529;
    }
    .filter-select-trigger:after {
        content: "";
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 5px solid #6c757d;
        margin-left: 6px;
        flex-shrink: 0;
    }
    .filter-select.open .filter-select-trigger {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
    }
    .filter-select-options {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1050;
        margin-top: 2px;
        max-height: 220px;
        overflow-y: auto;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.12);
        list-style: none;
        padding: 4px 0;
    }
    .filter-select.open .filter-select-options {
        display: block;
    }
    .filter-select-options li {
        padding: 6px 10px;
        font-size: 0.75rem;
        cursor: pointer;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .filter-select-options li:hover {
        background-color: #f1f3f5;
    }
    .filter-select-options li.selected {
        background-color: #0d6efd;
        color: #fff;
    }

    @media (max-width: 576px) {
        .form-action-row {
            flex-direction: column-reverse;
        }
        .form-action-row a,
        .form-action-row button {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="container-fluid px-4">
    <h5 class="fw-bold mb-2">Inventori Barang/Bahan</h5>

    <div class="card mb-3 shadow-sm">
        <div class="card-header card-header-custom"><i class="fas fa-plus-circle me-1"></i> Form Input Data Barang Persediaan</div>
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

            <form action="{{ route('barang.store') }}" method="POST">
                @csrf
                
                <h6 class="fw-bold mb-2 text-dark" style="font-size: 0.85rem;">Identitas Barang</h6>
                <div class="row mb-2">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control form-control-sm" placeholder="mis. PLASTIK SEAL MERAH" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Satuan</label>
                        <input type="text" name="satuan" class="form-control form-control-sm" placeholder="mis. Pieces" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Kode Barang / Bahan</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="kode_barang_input" name="kode_barang" class="form-control form-control-sm" placeholder="mis. 1.23.456" required>
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#scannerModal" title="Scan Barcode">
                                <i class="fas fa-qrcode"></i> Scan
                            </button>
                        </div>
                    </div>
                </div>

                <hr class="my-2">

                <h6 class="fw-bold mb-2 text-dark" style="font-size: 0.85rem;">Data Stok, Harga & Kondisi</h6>
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Minimal Stock</label>
                        <input type="number" step="any" name="minimal_stok" class="form-control form-control-sm" placeholder="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Saldo Awal</label>
                        <input type="number" step="any" name="saldo_awal" class="form-control form-control-sm" placeholder="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Penerimaan</label>
                        <input type="number" step="any" name="penerimaan" class="form-control form-control-sm" placeholder="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Pengeluaran</label>
                        <input type="number" step="any" name="pengeluaran" class="form-control form-control-sm" placeholder="0">
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Harga Rata-rata Tertimbang (Rp)</label>
                        <input type="number" step="any" name="harga_rata" class="form-control form-control-sm" placeholder="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Kondisi Barang</label>
                        @php
                            $kondisiLabels = ['' => '--Pilih Kondisi--', 'baik' => 'Baik', 'rusak' => 'Rusak'];
                            $selectedKondisi = old('kondisi', '');
                        @endphp
                        <div class="filter-select" id="selectKondisi">
                            <input type="hidden" name="kondisi" id="kondisiInput" value="{{ $selectedKondisi }}" required>
                            <button type="button" class="filter-select-trigger">{{ $kondisiLabels[$selectedKondisi] }}</button>
                            <ul class="filter-select-options">
                                @foreach($kondisiLabels as $value => $label)
                                    <li data-value="{{ $value }}" class="{{ $selectedKondisi === $value ? 'selected' : '' }}">{{ $label }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tanggal Expired Date</label>
                        <input type="text" name="tgl_exp" id="tglExp" class="form-control form-control-sm flatpickr-date" value="{{ old('tgl_exp') }}" placeholder="dd/mm/yyyy" autocomplete="off">
                    </div>
                </div>

                <div class="mt-2 d-flex justify-content-end gap-2 form-action-row">
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary shadow-sm" style="font-size: 0.78rem; padding: 0.3rem 0.85rem; border-radius: 0.375rem;"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
                    <button type="submit" class="btn btn-brand-standard shadow-sm"><i class="fas fa-save me-1"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="scannerModal" tabindex="-1" aria-labelledby="scannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white py-2">
                <h5 class="modal-title fs-6" id="scannerModalLabel"><i class="fas fa-qrcode me-2"></i>Scan Barcode / QR Code</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="reader" width="100%"></div>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr('#tglExp', {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            allowInput: true
        });

        document.querySelectorAll('.filter-select').forEach(function (wrapper) {
            const trigger = wrapper.querySelector('.filter-select-trigger');
            const hiddenInput = wrapper.querySelector('input[type="hidden"]');
            const options = wrapper.querySelectorAll('.filter-select-options li');

            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                document.querySelectorAll('.filter-select.open').forEach(function (other) {
                    if (other !== wrapper) other.classList.remove('open');
                });
                wrapper.classList.toggle('open');
            });

            options.forEach(function (li) {
                li.addEventListener('click', function () {
                    hiddenInput.value = li.getAttribute('data-value');
                    trigger.textContent = li.textContent;
                    options.forEach(function (o) { o.classList.remove('selected'); });
                    li.classList.add('selected');
                    wrapper.classList.remove('open');
                });
            });
        });

        document.addEventListener('click', function () {
            document.querySelectorAll('.filter-select.open').forEach(function (wrapper) {
                wrapper.classList.remove('open');
            });
        });

        const scannerModal = document.getElementById('scannerModal');
        let html5QrcodeScanner = null;

        scannerModal.addEventListener('shown.bs.modal', function () {
            html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: {width: 250, height: 250} }, /* verbose= */ false);
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });

        scannerModal.addEventListener('hidden.bs.modal', function () {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear().catch(error => {
                    console.error("Failed to clear html5QrcodeScanner. ", error);
                });
            }
        });

        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById('kode_barang_input').value = decodedText;
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
            }
            const modal = bootstrap.Modal.getInstance(scannerModal);
            modal.hide();
        }

        function onScanFailure(error) {

        }
    });
</script>
@endpush
@endsection