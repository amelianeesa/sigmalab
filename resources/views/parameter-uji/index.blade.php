@extends('layouts.app')
@section('title', 'Daftar - Parameter Uji')

@section('content')
<style>
    .pu-container { padding-top: 2px; }
    .pu-breadcrumb { font-size: 0.72rem; }
    .pu-title { font-size: 1.1rem; }

    .card-header-custom {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.85rem !important;
    }
    .btn-corporate-blue {
        background-color: #1b3152 !important;
        border-color: #1b3152 !important;
        color: #ffffff !important;
    }
    .btn-corporate-blue:hover,
    .btn-corporate-blue:focus,
    .btn-corporate-blue:active {
        background-color: #14253e !important;
        border-color: #14253e !important;
        color: #ffffff !important;
    }

    .card-header-custom .btn-corporate-blue {
        background-color: #ffffff !important;
        border-color: #ffffff !important;
        color: #1b3152 !important;
        font-weight: 600;
    }
    .card-header-custom .btn-corporate-blue:hover,
    .card-header-custom .btn-corporate-blue:focus,
    .card-header-custom .btn-corporate-blue:active {
        background-color: #e9eef5 !important;
        border-color: #e9eef5 !important;
        color: #14253e !important;
    }

    .form-label { font-size: 0.75rem !important; font-weight: 600; }
    .form-control, .form-select,
    .form-control-sm, .form-select-sm { font-size: 0.8rem !important; }

    .pu-table-header th {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-color: #2c4975 !important;
        font-size: 0.72rem !important;
        vertical-align: middle !important;
    }
    .pu-table td, .pu-table th {
        font-size: 0.78rem !important;
        padding: 0.45rem 0.55rem !important;
        vertical-align: middle !important;
    }
    .pagination .page-link { font-size: 0.75rem; padding: 0.25rem 0.6rem; }

    .pu-action-btn {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        padding: 0 !important;
        border-radius: 6px;
    }

    .filter-select {
        position: relative;
        font-size: 0.8rem;
    }
    .filter-select-trigger {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.32rem 0.6rem;
        font-size: 0.8rem;
        cursor: pointer;
        text-align: left;
        color: #212529;
    }
    .filter-select-trigger:after {
        content: "";
        width: 0; height: 0;
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
        top: 100%; left: 0; right: 0;
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
    .filter-select.open .filter-select-options { display: block; }
    .filter-select-options li {
        padding: 6px 10px;
        font-size: 0.8rem;
        cursor: pointer;
    }
    .filter-select-options li:hover { background-color: #f1f3f5; }
    .filter-select-options li.selected { background-color: #0d6efd; color: #fff; }

    .scroll-hint-pu {
        display: none;
        font-size: 0.68rem;
        color: #6c757d;
        margin-bottom: 0.4rem;
    }

    .pu-confirm-modal .modal-content {
        font-size: 0.82rem;
        border-radius: 8px;
    }
    .pu-confirm-icon {
        width: 56px;
        height: 56px;
        background-color: #fff8e6;
        color: #f0ad4e;
        font-size: 24px;
        border: 2px solid #ffeeba;
    }

    @media (max-width: 768px) {
        .pu-table td, .pu-table th { font-size: 0.68rem !important; padding: 0.35rem 0.4rem !important; }
        .scroll-hint-pu { display: block; }
    }
    @media (max-width: 575.98px) {
        .pu-header-row { flex-direction: column; align-items: stretch !important; }
        .pu-header-row .btn { width: 100%; text-align: center; }
        .pu-filter-form .col-md-2 a { width: 100%; }
    }
</style>

<div class="container-fluid px-4 pu-container">
    <ol class="breadcrumb mb-1 mt-1 pu-breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('kegiatan.index') }}" class="text-decoration-none">Verifikasi Mutu</a></li>
        <li class="breadcrumb-item active">Parameter Uji</li>
    </ol>
    <h4 class="fw-bold text-dark mb-3 pu-title"><i class="fas fa-vial me-1"></i> Parameter Uji</h4>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header card-header-custom d-flex justify-content-between align-items-center flex-wrap gap-2 pu-header-row">
            <span><i class="fas fa-vial me-2"></i>Data Master Parameter Uji</span>
            @can('create', App\Models\ParameterUji::class)
                <a href="{{ route('parameter-uji.create') }}" class="btn btn-corporate-blue btn-sm shadow-sm"><i class="fas fa-plus me-1"></i> Tambah Parameter</a>
            @endcan
        </div>
        <div class="card-body">
            <form action="{{ route('parameter-uji.index') }}" method="GET" id="filterFormPU" class="row g-2 mb-3 align-items-center live-search-form pu-filter-form" data-target="#table-container">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari Nama Parameter..." value="{{ $search ?? '' }}">
                    </div>
                </div>
                <div class="col-md-3">
                    @php
                        $statusLabels = ['semua' => 'Semua Status', 'aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'];
                    @endphp
                    <div class="filter-select" id="selectFilterStatus">
                        <input type="hidden" name="filter_status" value="{{ $filterStatus }}">
                        <button type="button" class="filter-select-trigger">{{ $statusLabels[$filterStatus] ?? 'Semua Status' }}</button>
                        <ul class="filter-select-options">
                            @foreach($statusLabels as $value => $label)
                                <li data-value="{{ $value }}" class="{{ $filterStatus === $value ? 'selected' : '' }}">{{ $label }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('parameter-uji.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter"><i class="fas fa-sync-alt"></i> Reset</a>
                </div>
            </form>

            <div id="table-container">
                <div class="scroll-hint-pu"><i class="fas fa-arrows-alt-h me-1"></i> Geser tabel ke samping untuk melihat kolom lainnya</div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered align-middle mb-0 pu-table">
                        <thead class="pu-table-header">
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th>Nama Parameter</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Nilai Acuan</th>
                                <th class="text-center">Range Batas (Min - Max)</th>
                                <th>Metode/Kriteria</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" style="width: 130px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($parameterUji as $index => $item)
                            <tr>
                                <td class="text-center">{{ $parameterUji->firstItem() + $index }}</td>
                                <td class="fw-bold">{{ $item->nama_parameter }}</td>
                                <td class="text-center">{{ $item->satuan }}</td>
                                <td class="text-center">{{ number_format($item->nilai_acuan, 2) }}</td>
                                <td class="text-center">{{ number_format($item->batas_bawah, 2) }} - {{ number_format($item->batas_atas, 2) }}</td>
                                <td>{{ $item->metode_kriteria ?? '-' }}</td>
                                <td class="text-center">
                                    @if($item->status_aktif)
                                        <span class="badge bg-success" style="font-size: 0.68rem;">Aktif</span>
                                    @else
                                        <span class="badge bg-danger" style="font-size: 0.68rem;">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-center text-nowrap">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <a href="{{ route('parameter-uji.show', $item->parameter_uji_id) }}" class="btn btn-corporate-blue btn-sm pu-action-btn shadow-sm" title="Detail" aria-label="Detail"><i class="fas fa-eye"></i></a>

                                        @can('update', $item)
                                            <a href="{{ route('parameter-uji.edit', $item->parameter_uji_id) }}" class="btn btn-warning btn-sm pu-action-btn shadow-sm" title="Edit" aria-label="Edit"><i class="fas fa-edit"></i></a>
                                        @endcan

                                        @can('delete', $item)
                                            <button type="button" class="btn btn-danger btn-sm pu-action-btn shadow-sm btn-hapus-pu" title="Hapus / Nonaktifkan" aria-label="Hapus / Nonaktifkan" data-action="{{ route('parameter-uji.destroy', $item->parameter_uji_id) }}" data-nama="{{ $item->nama_parameter }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Data parameter uji tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $parameterUji->links('vendor.pagination.custom', ['size' => 'sm']) }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade pu-confirm-modal" id="modalHapusPU" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
        <div class="modal-content border-0 shadow text-center p-3">
            <div class="pt-2 pb-1">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center pu-confirm-icon">
                    <i class="fas fa-exclamation"></i>
                </div>
            </div>
            <div class="modal-body px-2 py-2">
                <h5 class="fw-bold text-dark mb-1" style="font-size: 1rem;">Apakah Anda yakin?</h5>
                <p class="text-muted mb-1" style="font-size: 0.78rem;">Parameter <strong id="hapusPuNama"></strong></p>
                <p class="text-muted mb-0" style="font-size: 0.78rem;">Jika parameter uji sudah digunakan di Hasil Uji, maka hanya akan di-nonaktifkan. Lanjutkan?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-1 pb-2">
                <form id="formHapusPU" action="" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm py-1 px-3 fw-semibold rounded-2" style="font-size: 0.78rem;">Ya, Hapus!</button>
                </form>
                <button type="button" class="btn btn-secondary btn-sm py-1 px-3 fw-semibold rounded-2" data-bs-dismiss="modal" style="font-size: 0.78rem;">Batal</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.filter-select').forEach(function (wrapper) {
            const trigger = wrapper.querySelector('.filter-select-trigger');
            const hiddenInput = wrapper.querySelector('input[type="hidden"]');
            const options = wrapper.querySelectorAll('.filter-select-options li');
            const form = wrapper.closest('form');

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
                    if (form) form.submit();
                });
            });
        });

        document.addEventListener('click', function () {
            document.querySelectorAll('.filter-select.open').forEach(function (wrapper) {
                wrapper.classList.remove('open');
            });
        });

        const modalHapusEl = document.getElementById('modalHapusPU');
        const formHapus = document.getElementById('formHapusPU');
        const namaHapus = document.getElementById('hapusPuNama');
        const modalHapus = bootstrap.Modal.getOrCreateInstance(modalHapusEl);

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-hapus-pu');
            if (!btn) return;
            e.preventDefault();
            formHapus.setAttribute('action', btn.dataset.action);
            namaHapus.textContent = btn.dataset.nama;
            modalHapus.show();
        });
    });
</script>
@endpush
@endsection