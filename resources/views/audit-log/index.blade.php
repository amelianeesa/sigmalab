@extends('layouts.app')
@section('title', 'Daftar - Audit Log')

@section('content')

<style>
    .container-fluid {
        padding-top: 2px !important;
        max-width: 1250px;
    }
    .card-header-custom {
        background-color: var(--sl-primary) !important;
        color: white !important;
        font-weight: bold;
        font-size: 0.9rem !important;
        padding: 0.5rem 0.75rem !important;
    }
    .table-header-custom, 
    .table-header-custom th, 
    .table-header-custom tr {
        background-color: var(--sl-primary) !important;
        color: #ffffff !important;
        border-color: #2c4975 !important;
        font-size: 0.7rem !important;
        vertical-align: middle !important;
        text-align: center !important;
    }
    .table-responsive-custom {
        font-size: 0.72rem !important;
    }
    .table th, .table td {
        padding: 0.25rem 0.35rem !important;
        font-size: 0.72rem !important;
        vertical-align: middle !important;
    }
    .form-control-sm, .form-select-sm {
        font-size: 0.72rem !important;
        padding: 0.15rem 0.3rem !important;
        height: auto !important;
    }
    .card-shadow-custom {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .badge-custom-size {
        font-size: 0.6rem !important;
        padding: 0.2rem 0.4rem !important;
    }
    .btn-custom-outline {
        color: var(--sl-primary);
        border-color: var(--sl-primary);
        background-color: transparent;
        transition: all 0.2s ease-in-out;
    }
    .btn-custom-outline:hover {
        background-color: var(--sl-primary) !important;
        color: #ffffff !important;
        border-color: var(--sl-primary) !important;
    }
    .pagination .page-link {
        font-size: 0.72rem;
        padding: 0.2rem 0.55rem;
    }

    .filter-select {
        position: relative;
        font-size: 0.72rem;
    }
    .filter-select-trigger {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        padding: 0.15rem 0.3rem;
        font-size: 0.72rem;
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
        font-size: 0.72rem;
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

    .scroll-hint-audit {
        display: none;
        font-size: 0.68rem;
        color: #6c757d;
        margin-bottom: 0.4rem;
    }

    .col-check {
        width: 32px;
        text-align: center;
    }
    .btn-hapus-terpilih:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .swal2-popup {
        font-size: 0.85rem !important;
        padding: 1.5rem 1.25rem 1.75rem !important;
        border-radius: 1rem !important;
        width: 30em !important;
    }
    .swal2-title {
        font-size: 1.15rem !important;
        font-weight: 700 !important;
        padding: 0 !important;
        margin-bottom: 0.4rem !important;
        color: #212529 !important;
    }
    .swal2-html-container {
        font-size: 0.85rem !important;
        color: #6c757d !important;
        margin: 0.4rem 0.25rem 0 !important;
    }
    .swal2-icon {
        width: 3.6em !important;
        height: 3.6em !important;
        margin: 0.3em auto 0.7em !important;
    }
    .swal2-icon .swal2-icon-content {
        font-size: 2em !important;
    }
    .swal2-actions {
        margin-top: 1.35rem !important;
        gap: 0.75rem !important;
    }
    .swal2-styled {
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        padding: 0.55rem 1.3rem !important;
        border-radius: 0.4rem !important;
        white-space: nowrap !important;
        line-height: 1.3 !important;
    }

    @media (max-width: 768px) {
        .container-fluid.px-4 {
            padding-left: 8px !important;
            padding-right: 8px !important;
        }

        .container-fluid > .d-flex.mb-2.mt-2 {
            margin-top: 0 !important;
            margin-bottom: 0.35rem !important;
            gap: 0.3rem !important;
        }
        .container-fluid > .d-flex.mb-2.mt-2 h4 {
            font-size: 0.85rem !important;
        }
        .container-fluid > .d-flex.mb-2.mt-2 .d-flex {
            gap: 0.3rem !important;
        }
        .container-fluid > .d-flex.mb-2.mt-2 .btn {
            font-size: 0.66rem !important;
            padding: 0.2rem 0.45rem !important;
        }

        .container-fluid .card.mb-3 {
            margin-bottom: 0.4rem !important;
        }
        .container-fluid .card-body {
            padding: 0.4rem !important;
        }

        #filter-form {
            --bs-gutter-x: 0.4rem;
            --bs-gutter-y: 0;
            flex-wrap: nowrap;
        }
        #filter-form > .col-md-4 {
            width: 38%;
        }
        #filter-form > .col-md-7 {
            width: 47%;
        }
        #filter-form > .col-md-1 {
            width: 15%;
        }
        #filter-form .btn {
            padding: 0.15rem 0.3rem !important;
        }

        .scroll-hint-audit {
            display: block;
            font-size: 0.6rem;
            margin-bottom: 0.2rem;
        }

        .table-responsive-custom {
            --audit-offset: 270px;
            max-height: calc(100vh - var(--audit-offset));
            max-height: calc(100dvh - var(--audit-offset));
            overflow: auto;
        }
        .table-header-custom th {
            position: sticky;
            top: 0;
            z-index: 2;
            box-shadow: inset 0 -1px 0 #2c4975;
        }
        .table th, .table td {
            font-size: 0.62rem !important;
            padding: 0.2rem 0.25rem !important;
            line-height: 1.25;
        }
        .table .btn {
            font-size: 0.6rem !important;
            padding: 0 0.3rem !important;
        }

        .container-fluid .card-body > .mt-3 {
            margin-top: 0.4rem !important;
        }
        .pagination {
            margin-bottom: 0;
        }
        .pagination .page-link {
            font-size: 0.65rem;
            padding: 0.15rem 0.45rem;
        }
    }

    @media (max-width: 575.98px) {
        #content {
            padding-top: 66px !important;
        }
    }
</style>

@php
    $canDelete = (Auth::user()->role->nama_role ?? '') === 'Admin Aplikasi';
@endphp

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2 mt-2">
        <h4 class="fw-bold text-dark mb-0" style="font-size: 1rem;"><i class="fas fa-history me-1"></i> Audit Trail (Aktif)</h4>
        <div class="d-flex gap-2">
            @if($canDelete)
                <button type="button" id="btnHapusTerpilih" class="btn btn-sm btn-warning btn-hapus-terpilih" style="font-size: 0.75rem;" disabled>
                    <i class="fas fa-trash me-1"></i> Hapus Terpilih (<span id="jumlahTerpilih">0</span>)
                </button>
                <button type="button" id="btnHapusSemua" class="btn btn-sm btn-danger" style="font-size: 0.75rem;">
                    <i class="fas fa-trash-alt me-1"></i> Hapus Semua
                </button>
            @endif
            <a href="{{ route('audit-log.archive') }}" class="btn btn-sm btn-custom-outline" style="font-size: 0.75rem;">
                <i class="fas fa-archive me-1"></i> Arsip Log
            </a>
        </div>
    </div>

    @if($canDelete)
        <form id="formHapusTerpilih" action="{{ route('audit-log.destroy-bulk') }}" method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>
        <form id="formHapusSemua" action="{{ route('audit-log.destroy-all') }}" method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endif
    
    <div class="card shadow-sm border-0 mb-3 card-shadow-custom">
        <div class="card-body p-2.5">
            <form action="{{ route('audit-log.index') }}" method="GET" class="row g-2 align-items-center" id="filter-form">
                <div class="col-md-4">
                    @php
                    $eventLabels = [
                        '' => '-- Semua Event --',
                        'login' => 'Login',
                        'logout' => 'Logout',
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ];
                        $selectedEvent = request('event', '');
                    @endphp
                    <div class="filter-select" id="selectEvent">
                        <input type="hidden" name="event" value="{{ $selectedEvent }}">
                        <button type="button" class="filter-select-trigger">{{ $eventLabels[$selectedEvent] ?? '-- Semua Event --' }}</button>
                        <ul class="filter-select-options">
                            @foreach($eventLabels as $value => $label)
                                <li data-value="{{ $value }}" class="{{ $selectedEvent === $value ? 'selected' : '' }}">{{ $label }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-7">
                    <input type="text" name="subject_type" class="form-control form-control-sm" placeholder="Ketik nama entitas (Contoh: Barang, Kegiatan)..." value="{{ request('subject_type') }}" autocomplete="off">
                </div>
                <div class="col-md-1">
                    <a href="{{ route('audit-log.index') }}" class="btn btn-sm btn-outline-secondary w-100 py-1" title="Reset"><i class="fas fa-sync-alt"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 card-shadow-custom">
        <div class="card-body p-2.5">
            <div class="scroll-hint-audit"><i class="fas fa-arrows-alt-h me-1"></i> Geser tabel ke samping untuk melihat kolom lainnya</div>
            <div class="table-responsive table-responsive-custom">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-header-custom">
                        <tr>
                            @if($canDelete)
                                <th class="col-check"><input type="checkbox" id="checkAll" title="Pilih semua di halaman ini"></th>
                            @endif
                            <th style="width: 15%;">Waktu Kejadian</th>
                            <th style="width: 15%;">Aktor (User)</th>
                            <th style="width: 10%;">Event</th>
                            <th style="width: 30%;">Deskripsi (Keterangan)</th>
                            <th style="width: 15%;">Model Terkait</th>
                            <th style="width: 15%;" class="text-center">Perubahan Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                @if($canDelete)
                                    <td class="col-check"><input type="checkbox" class="check-item" name="ids[]" value="{{ $log->id }}" form="formHapusTerpilih"></td>
                                @endif
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}
                                    <br><small class="text-muted" style="font-size: 0.6rem;">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $log->causer ? ($log->causer->personil->nama_personil ?? $log->causer->username) : 'Sistem (Otomatis)' }}</span>
                                    @if($log->causer && $log->causer->role)
                                        <br><small class="text-muted" style="font-size: 0.6rem;">{{ $log->causer->role->nama_role }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($log->event === 'login')
                                        <span class="badge bg-info text-dark badge-custom-size">Login</span>
                                    @elseif($log->event === 'logout')
                                        <span class="badge bg-secondary badge-custom-size">Logout</span>
                                    @elseif($log->event === 'created')
                                        <span class="badge bg-success badge-custom-size">Created</span>
                                    @elseif($log->event === 'updated')
                                        <span class="badge bg-warning text-dark badge-custom-size">Updated</span>
                                    @elseif($log->event === 'deleted')
                                        <span class="badge bg-danger badge-custom-size">Deleted</span>
                                    @else
                                        <span class="badge bg-secondary badge-custom-size">{{ ucfirst($log->event) }}</span>
                                    @endif
                                </td>
                                <td class="text-start">{{ $log->description }}</td>
                                <td class="text-center">
                                    @php
                                        $modelPath = explode('\\', $log->subject_type);
                                        $modelName = end($modelPath);
                                    @endphp
                                    <span class="badge bg-light text-dark border badge-custom-size">{{ $modelName ?: '-' }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('audit-log.show', $log->id) }}" class="btn btn-sm py-0 px-1 btn-custom-outline" style="font-size: 0.68rem;">
                                        <i class="fas fa-search me-1"></i> Lihat Data
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $canDelete ? 7 : 6 }}" class="text-center text-muted py-3">Belum ada rekam jejak yang tercatat</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3" style="font-size: 0.72rem;">
                {{ $logs->withQueryString()->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('filter-form');
        const inputSubject = form.querySelector('input[name="subject_type"]');

        let timeout = null;
        inputSubject.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                form.submit();
            }, 500);
        });

        document.querySelectorAll('.filter-select').forEach(function (wrapper) {
            const trigger = wrapper.querySelector('.filter-select-trigger');
            const hiddenInput = wrapper.querySelector('input[type="hidden"]');
            const options = wrapper.querySelectorAll('.filter-select-options li');
            const parentForm = wrapper.closest('form');

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
                    if (parentForm) parentForm.submit();
                });
            });
        });

        document.addEventListener('click', function () {
            document.querySelectorAll('.filter-select.open').forEach(function (wrapper) {
                wrapper.classList.remove('open');
            });
        });

        const checkAll = document.getElementById('checkAll');
        const btnTerpilih = document.getElementById('btnHapusTerpilih');
        const btnSemua = document.getElementById('btnHapusSemua');
        const labelJumlah = document.getElementById('jumlahTerpilih');

        if (btnTerpilih && btnSemua) {
            const items = document.querySelectorAll('.check-item');

            function refreshTerpilih() {
                const jumlah = document.querySelectorAll('.check-item:checked').length;
                labelJumlah.textContent = jumlah;
                btnTerpilih.disabled = jumlah === 0;
                checkAll.checked = items.length > 0 && jumlah === items.length;
            }

            checkAll.addEventListener('change', function () {
                items.forEach(function (item) { item.checked = checkAll.checked; });
                refreshTerpilih();
            });

            items.forEach(function (item) {
                item.addEventListener('change', refreshTerpilih);
            });

            btnTerpilih.addEventListener('click', function () {
                const jumlah = document.querySelectorAll('.check-item:checked').length;
                Swal.fire({
                    title: 'Hapus log terpilih?',
                    text: jumlah + ' log akan dihapus permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        document.getElementById('formHapusTerpilih').submit();
                    }
                });
            });

            btnSemua.addEventListener('click', function () {
                Swal.fire({
                    title: 'Hapus SEMUA log?',
                    text: 'Seluruh data audit trail aktif akan dihapus permanen dan tidak bisa dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus Semua!',
                    cancelButtonText: 'Batal'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        document.getElementById('formHapusSemua').submit();
                    }
                });
            });
        }
    });
</script>
@endpush