@extends('layouts.app')
@section('title', 'Daftar - QC In-House')

@section('content')
<style>
    .dashboard-container {
        padding: 0 20px !important;
        margin-top: -8px !important;
    }

    .dashboard-container nav[aria-label="breadcrumb"],
    .dashboard-container > nav {
        margin: 0 !important;
        padding: 0 !important;
    }

    .dashboard-container .breadcrumb {
        margin: 0 0 6px 0 !important;
        padding: 0 !important;
        font-size: 0.75rem !important;
        line-height: 1.4;
        flex-wrap: wrap;
        align-items: center;
        background: transparent !important;
    }

    .dashboard-container .breadcrumb .breadcrumb-item,
    .dashboard-container .breadcrumb .breadcrumb-item a {
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        color: #0d6efd !important;
        text-decoration: none;
    }

    .dashboard-container .breadcrumb .breadcrumb-item a:hover {
        color: #0a58ca !important;
        text-decoration: underline;
    }

    .dashboard-container .breadcrumb .breadcrumb-item.active {
        color: #000000 !important;
        font-weight: 700 !important;
    }

    .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item {
        padding-left: 0.4rem;
    }

    .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        color: #6c757d !important;
        padding-right: 0.4rem;
        font-weight: 400;
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu {
        min-width: 190px;
        padding: 4px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item {
        color: #000000 !important;
        font-size: 0.78rem !important;
        font-weight: 500 !important;
        text-decoration: none !important;
        background-color: transparent;
        padding: 7px 12px;
        border-radius: 5px;
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:hover,
    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:focus,
    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item:active,
    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item.active {
        background-color: rgba(27, 49, 82, 0.15) !important;
        color: #000000 !important;
        text-decoration: none !important;
    }

    .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item.active {
        font-weight: 700 !important;
    }

    .card-body {
        padding: 10px !important;
    }

    .table th, .table td {
        padding: 8px 10px !important;
        vertical-align: middle !important;
        font-size: 0.72rem !important;
    }

    .table thead th {
        font-size: 0.75rem !important;
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-color: #ffffff !important;
        text-align: center !important;
    }

    .table-bordered > :not(caption) > * > * {
        border-color: #dee2e6;
    }

    .table thead th {
        border-color: #ffffff !important;
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

    .btn-outline-corporate {
        color: #1b3152 !important;
        border-color: #1b3152 !important;
    }

    .btn-outline-corporate:hover,
    .btn-outline-corporate:focus {
        background-color: #1b3152 !important;
        color: #ffffff !important;
    }

    .pagination .page-link {
        font-size: 0.72rem;
        padding: 0.2rem 0.55rem;
    }

    .filter-label {
        font-size: 0.7rem;
        color: #6c757d;
        margin-bottom: 2px;
        display: block;
    }

    .qc-table {
        min-width: 780px;
    }

    .kode-batch-cell {
        width: 95px;
        max-width: 95px;
        padding: 8px 6px !important;
        word-break: break-all;
        line-height: 1.2;
    }

    .kode-batch-cell code {
        font-size: 0.7rem;
        font-weight: 700;
    }

    .input-group-sm > .input-group-text {
        padding: 0.25rem 0.55rem;
    }

    .flatpickr-calendar {
        font-family: inherit;
        font-size: 12px;
        width: 250px;
        max-width: calc(100vw - 20px);
    }

    .flatpickr-calendar .flatpickr-innerContainer,
    .flatpickr-calendar .flatpickr-rContainer,
    .flatpickr-calendar .flatpickr-days,
    .flatpickr-calendar .dayContainer {
        width: 250px;
        min-width: 250px;
        max-width: 250px;
    }

    .flatpickr-calendar .flatpickr-day {
        max-width: 35px;
        height: 32px;
        line-height: 32px;
        font-size: 12px;
    }

    .flatpickr-calendar .flatpickr-months .flatpickr-month {
        height: 34px;
    }

    .flatpickr-calendar .flatpickr-current-month {
        font-size: 100%;
        height: 34px;
        padding-top: 5px;
    }

    .flatpickr-calendar .flatpickr-months .flatpickr-prev-month,
    .flatpickr-calendar .flatpickr-months .flatpickr-next-month {
        height: 34px;
        padding: 8px 10px;
    }

    .flatpickr-calendar .flatpickr-weekdays {
        height: 26px;
    }

    .flatpickr-calendar span.flatpickr-weekday {
        font-size: 11px;
    }

    @media (max-width: 767.98px) {
        .flatpickr-calendar {
            width: 260px;
        }

        .flatpickr-calendar .flatpickr-innerContainer,
        .flatpickr-calendar .flatpickr-rContainer,
        .flatpickr-calendar .flatpickr-days,
        .flatpickr-calendar .dayContainer {
            width: 260px;
            min-width: 260px;
            max-width: 260px;
        }
    }

    .input-group > .flatpickr-input.form-control,
    .input-group > input.form-control[readonly] {
        background-color: #ffffff;
        cursor: pointer;
    }

    .select2-container--bootstrap-5 .select2-selection {
        min-height: calc(1.5em + 0.5rem + 3px);
        font-size: 0.82rem;
    }

    @media (max-width: 767.98px) {
        .dashboard-container {
            padding: 0 10px !important;
        }

        .dashboard-container .breadcrumb,
        .dashboard-container .breadcrumb .breadcrumb-item,
        .dashboard-container .breadcrumb .breadcrumb-item a {
            font-size: 0.72rem !important;
        }

        .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item {
            padding-left: 0.3rem;
        }

        .dashboard-container .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            padding-right: 0.3rem;
        }

        .dashboard-container .breadcrumb .breadcrumb-item .dropdown-menu .dropdown-item {
            font-size: 0.85rem !important;
            padding: 10px 14px;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            width: 100%;
        }

        .select2-container {
            width: 100% !important;
        }

        .form-control,
        .form-select {
            min-width: 0;
        }
    }
</style>

<div class="container-fluid dashboard-container" style="font-size: 0.82rem;">
    <x-qc-breadcrumb active="In-House" />

    @php
        $allowedRoles = ['Admin Aplikasi', 'Analis Lab', 'Koordinator Laboratorium'];
        $userRoleName = Auth::user()->role->nama_role ?? '';
        $canCreateSampel = in_array($userRoleName, $allowedRoles);
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="fw-bold mb-0" style="font-size: 1.1rem;">
            <i class="fas fa-flask me-2" style="color: #1b3152;"></i>QC In-House
        </h5>

        <div class="header-actions d-grid d-md-flex align-items-center gap-2">
            @if($canCreateSampel)
                <a href="{{ route('qc-inhouse.create') }}" class="btn btn-outline-corporate btn-sm py-1.5 px-3 shadow-sm fw-semibold" style="font-size: 0.8rem;">
                    <i class="fas fa-plus me-1"></i> Buat Sampel Baru
                </a>
            @endif
            <a href="{{ route('qc-harian.create') }}" class="btn btn-corporate-blue btn-sm py-1.5 px-3 shadow-sm fw-semibold" style="font-size: 0.8rem;">
                <i class="fas fa-play me-1"></i> Mulai Pengujian Harian QC
            </a>
        </div>
    </div>

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">

            <form action="{{ route('qc-inhouse.index') }}" method="GET" id="filterForm" class="row g-2 mb-3 align-items-end">
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" id="search" class="form-control form-control-sm py-1.5" placeholder="Cari kode atau nama sampel..." value="{{ request('search') }}" autocomplete="off" style="font-size: 0.82rem;">
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <select name="jenis_batubara" id="jenis_batubara" class="form-select form-select-sm py-1.5">
                        <option value="">-- Semua Jenis Batubara --</option>
                        <option value="sub_bituminous" {{ request('jenis_batubara') == 'sub_bituminous' ? 'selected' : '' }}>Sub Bituminous</option>
                        <option value="bituminous" {{ request('jenis_batubara') == 'bituminous' ? 'selected' : '' }}>Bituminous</option>
                        <option value="anthracite" {{ request('jenis_batubara') == 'anthracite' ? 'selected' : '' }}>Anthracite</option>
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <label for="start_date" class="filter-label">Tanggal Mulai</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="fas fa-calendar-alt text-muted"></i></span>
                        <input type="text" name="start_date" id="start_date" class="form-control form-control-sm py-1.5" value="{{ request('start_date') }}" placeholder="Pilih tanggal" autocomplete="off" style="font-size: 0.82rem;">
                    </div>
                </div>

                <div class="col-6 col-md-2">
                    <label for="end_date" class="filter-label">Tanggal Sampai</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="fas fa-calendar-alt text-muted"></i></span>
                        <input type="text" name="end_date" id="end_date" class="form-control form-control-sm py-1.5" value="{{ request('end_date') }}" placeholder="Pilih tanggal" autocomplete="off" style="font-size: 0.82rem;">
                    </div>
                </div>

                <div class="col-12 col-md-1 d-flex gap-1">
                    <a href="{{ route('qc-inhouse.index') }}" class="btn btn-outline-secondary btn-sm w-100 py-1.5" title="Reset"><i class="fas fa-sync-alt"></i><span class="d-md-none ms-1">Reset</span></a>
                </div>
            </form>

            <div class="table-responsive" id="table-container">
                <table class="table table-bordered table-striped align-middle text-center mb-0 qc-table" style="font-size: 0.78rem;">
                    <thead class="align-middle">
                        <tr>
                            <th style="width: 45px;">No.</th>
                            <th style="width: 95px; max-width: 95px;">Kode Batch</th>
                            <th>Nama Sampel</th>
                            <th>Jenis / Metode</th>
                            <th style="width: 110px;">Jumlah Parameter</th>
                            <th>Pembuat</th>
                            <th>Tanggal Buat</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 90px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sampels as $index => $s)
                            <tr>
                                <td>{{ $sampels->firstItem() + $index }}</td>
                                <td class="kode-batch-cell"><code class="text-dark fw-bold">{{ $s->kode_batch ?? '-' }}</code></td>
                                <td class="text-start fw-bold">{{ $s->nama_sampel }}</td>
                                <td>
                                    <span class="d-block text-uppercase fw-bold" style="font-size: 0.7rem;">{{ str_replace('_', ' ', $s->jenis_batubara) }}</span>
                                    <span class="d-block text-muted" style="font-size: 0.65rem;">{{ strtoupper($s->metode_acuan) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary" style="font-size: 0.7rem;">{{ $s->parameters->count() }} Parameter</span>
                                </td>
                                <td>{{ $s->pembuat->username ?? '-' }}</td>
                                <td class="text-nowrap">{{ $s->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $s->status_color }}" style="font-size: 0.7rem;">{{ $s->status_label }}</span>
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{ route('qc-inhouse.show', $s->sampel_inhouse_id) }}" class="btn btn-corporate-blue btn-sm py-1 px-2 shadow-sm" style="font-size: 0.75rem;" title="Detail" aria-label="Detail">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-3">Belum ada data sampel In-House.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $sampels->withQueryString()->links('vendor.pagination.custom', ['size' => 'sm']) }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const filterForm = document.getElementById('filterForm');

    $('#jenis_batubara').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    let timeout = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            filterForm.submit();
        }, 500);
    });

    $('#jenis_batubara').on('change', function() {
        filterForm.submit();
    });

    function loadStyle(href) {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = href;
        document.head.appendChild(link);
    }

    function loadScript(src) {
        return new Promise(function(resolve, reject) {
            const script = document.createElement('script');
            script.src = src;
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }

    async function ensureFlatpickr() {
        if (!window.flatpickr) {
            loadStyle('https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css');
            await loadScript('https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js');
        }
        if (!window.flatpickr.l10ns || !window.flatpickr.l10ns.id) {
            await loadScript('https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/id.js');
        }
    }

    ensureFlatpickr().then(function() {
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');

        function submitIfReady() {
            const start = startInput.value;
            const end = endInput.value;
            if ((start && end) || (!start && !end)) {
                filterForm.submit();
            }
        }

        const baseConfig = {
            locale: 'id',
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'j F Y',
            altInputClass: 'form-control form-control-sm py-1.5',
            monthSelectorType: 'dropdown',
            disableMobile: true,
            allowInput: false
        };

        const endPicker = flatpickr(endInput, Object.assign({}, baseConfig, {
            minDate: startInput.value || null,
            onChange: function() {
                submitIfReady();
            }
        }));

        flatpickr(startInput, Object.assign({}, baseConfig, {
            maxDate: endInput.value || null,
            onChange: function(selectedDates, dateStr) {
                endPicker.set('minDate', dateStr || null);
                submitIfReady();
            }
        }));
    });
});
</script>
@endsection