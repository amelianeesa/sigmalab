@extends('layouts.app')

@section('content')
<style>
    .container-fluid {
        padding-top: 2px !important;
    }
    .card-body {
        padding: 0.6rem 0.9rem !important;
    }
    .card-shadow-custom {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .page-title {
        font-size: 1.3rem;
    }
    .breadcrumb-small {
        font-size: 11px;
    }
    .form-label {
        font-size: 0.68rem !important;
        margin-bottom: 0.15rem !important;
    }
    .form-control-sm, .form-select-sm {
        padding: 0.12rem 0.4rem !important;
        font-size: 0.7rem !important;
        min-height: 24px;
    }

    .table-info-alat {
        font-size: 0.85rem;
        margin-bottom: 0.5rem !important;
    }
    .table-info-alat td {
        padding: 0.2rem 0.4rem !important;
    }
    .table-info-alat .label-col {
        width: 170px;
        font-weight: 700;
    }
    .jenis-pemeliharaan-list {
        font-size: 0.8rem;
        line-height: 1.45;
    }

    .table-pemeliharaan {
        margin-bottom: 0;
    }
    .table-pemeliharaan th,
    .table-pemeliharaan td {
        padding: 0.28rem 0.4rem !important;
        font-size: 0.76rem !important;
        line-height: 1.3;
        vertical-align: middle;
    }
    .table-pemeliharaan thead th {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-right: 1px solid #ffffff !important;
    }
    .table-pemeliharaan thead th.th-nowrap {
        white-space: nowrap;
    }
    .table-pemeliharaan .input-table-size {
        font-size: 0.76rem !important;
        height: 28px;
        min-height: 0;
        padding: 0.1rem 0.45rem !important;
    }
    .pemeliharaan-checkbox {
        width: 1rem;
        height: 1rem;
        margin: 0;
    }

    .btn-action-hover {
        font-size: 0.7rem !important;
        font-weight: 700;
        padding: 0.25rem 0.6rem !important;
        color: #ffffff !important;
        border: 1px solid transparent !important;
        transition: all 0.2s ease-in-out !important;
    }
    .btn-action-hover:hover,
    .btn-action-hover:focus,
    .btn-action-hover:active,
    .btn-action-hover.show {
        background-color: #3b5f93 !important;
        color: #ffffff !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 8px rgba(27, 49, 82, 0.3) !important;
    }

    .btn-action-hover.btn-kembali {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
    }
    .btn-action-hover.btn-kembali:hover,
    .btn-action-hover.btn-kembali:focus,
    .btn-action-hover.btn-kembali:active {
        background-color: #ffffff !important;
        color: #000000 !important;
        border-color: #6c757d !important;
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.25) !important;
    }

    .btn-bulan {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        min-height: 24px;
        padding: 0.12rem 0.4rem !important;
        font-size: 0.7rem !important;
        text-align: left;
        background-color: #ffffff !important;
        color: #212529 !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem;
    }
    .btn-bulan:hover,
    .btn-bulan:focus,
    .btn-bulan.show {
        background-color: #ffffff !important;
        color: #212529 !important;
        border-color: #1b3152 !important;
        box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15) !important;
    }
    .dropdown-menu-bulan {
        width: 100%;
        min-width: 0;
        padding: 0.25rem 0;
        max-height: calc(4 * 1.8rem + 0.5rem + 2px);
        overflow-y: auto;
    }
    .dropdown-menu-bulan .dropdown-item {
        font-size: 0.8rem;
        line-height: 1.5;
        padding: 0.3rem 0.75rem;
    }
    .dropdown-menu-bulan .dropdown-item:hover,
    .dropdown-menu-bulan .dropdown-item:focus {
        background-color: rgba(27, 49, 82, 0.1);
        color: #1b3152;
    }
    .dropdown-menu-bulan .dropdown-item.active,
    .dropdown-menu-bulan .dropdown-item.active:hover {
        background-color: #1b3152;
        color: #ffffff;
    }

    .dropdown-menu .dropdown-item {
        font-size: 0.9rem;
        border-radius: 4px;
        margin: 0 4px;
        width: calc(100% - 8px);
        transition: all 0.15s ease-in-out;
    }

    .dropdown-menu .dropdown-item.dropdown-item-pdf:hover,
    .dropdown-menu .dropdown-item.dropdown-item-pdf:focus,
    .dropdown-menu .dropdown-item.dropdown-item-pdf:active {
        background-color: rgba(220, 53, 69, 0.15) !important;
        color: #dc3545 !important;
        outline: none !important;
        box-shadow: none !important;
    }

    .dropdown-menu .dropdown-item.dropdown-item-excel:hover,
    .dropdown-menu .dropdown-item.dropdown-item-excel:focus,
    .dropdown-menu .dropdown-item.dropdown-item-excel:active {
        background-color: rgba(40, 167, 69, 0.15) !important;
        color: #28a745 !important;
        outline: none !important;
        box-shadow: none !important;
    }
</style>

@php
    $allowedRoles = ['Koordinator Laboratorium', 'Analis Lab', 'Admin Aplikasi'];
    $userRoleName = Auth::user()->role->nama_role ?? '';
    $canManagePemeliharaan = Auth::check() && in_array($userRoleName, $allowedRoles);
@endphp

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
        <div class="pt-1 mb-1">
            <h5 class="fw-bold mb-1 page-title">Kartu Pemeliharaan Harian</h5>
            <ol class="breadcrumb mb-0 breadcrumb-small">
                <li class="breadcrumb-item"><a href="{{ route('alat.index') }}" class="text-decoration-none">Data Alat & Kalibrasi</a></li>
                <li class="breadcrumb-item text-muted active">Kartu Pemeliharaan</li>
            </ol>
        </div>
        <div class="d-flex gap-2">
            @if($canManagePemeliharaan)
            <a href="{{ route('alat.item-pemeliharaan.edit', $alat->alat_id) }}" class="btn btn-sm text-white btn-action-hover" style="background-color: #1b3152;">
                <i class="fas fa-list-ol me-1"></i> Atur Jenis Pemeliharaan
            </a>
            @endif
            <a href="{{ route('alat.input-kalibrasi', $alat->alat_id) }}" class="btn btn-sm text-white btn-action-hover btn-kembali" style="background-color: #6c757d;">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card mb-3 shadow-sm card-shadow-custom border-0">
        <div class="card-body">

            <table class="table table-borderless table-info-alat">
                <tr>
                    <td class="label-col">Nama / Kode Peralatan</td>
                    <td>: {{ $alat->nama_alat }} / {{ $alat->kode_alat }}</td>
                </tr>
                <tr>
                    <td class="label-col">Merk / No. Serial</td>
                    <td>: {{ $alat->merk_tipe ?? '-' }} / {{ $alat->no_seri ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">No. Inventaris</td>
                    <td>: {{ $alat->no_inventaris ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Unit Kerja Pemilik</td>
                    <td>: {{ $alat->lokasi_alat ?? $alat->unit_kerja_pemilik ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col align-top">Jenis Pemeliharaan</td>
                    <td class="align-top">
                        :
                        <div class="d-inline-block w-75 ms-1 jenis-pemeliharaan-list">
                            @php
                                $totalItems = $alat->itemPemeliharaan->count();
                                $splitLimit = ceil($totalItems / 2);
                            @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    @foreach($alat->itemPemeliharaan->take($splitLimit) as $item)
                                        <div><strong>{{ $item->nomor_urut }}.</strong> {{ $item->nama_pemeliharaan }}</div>
                                    @endforeach
                                    @if($totalItems == 0)
                                        <span class="text-muted">Belum ada jenis pemeliharaan. Silakan klik "Atur Jenis Pemeliharaan".</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @foreach($alat->itemPemeliharaan->skip($splitLimit) as $item)
                                        <div><strong>{{ $item->nomor_urut }}.</strong> {{ $item->nama_pemeliharaan }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <form method="GET" action="" class="row g-2 mb-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">BULAN / TAHUN</label>
                    <input type="hidden" name="bulan" id="inputBulan" value="{{ $bulan }}">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-bulan dropdown-toggle" type="button" id="dropdownBulan" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                            {{ DateTime::createFromFormat('!m', $bulan)->format('F') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-bulan" aria-labelledby="dropdownBulan">
                            @for($i = 1; $i <= 12; $i++)
                                <li>
                                    <button type="button" class="dropdown-item {{ $bulan == $i ? 'active' : '' }}"
                                        onclick="document.getElementById('inputBulan').value='{{ $i }}'; this.closest('form').submit();">
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </button>
                                </li>
                            @endfor
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="number" name="tahun" class="form-control form-control-sm" value="{{ $tahun }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-7 text-end">
                    <div class="dropdown">
                        <button class="btn btn-sm text-white dropdown-toggle btn-action-hover" type="button" id="dropdownDownload" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #1b3152;">
                            <i class="fa fa-download me-1"></i> Unduh Laporan Pemeliharaan
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm py-2" aria-labelledby="dropdownDownload">
                            <li>
                                <a class="dropdown-item py-2 px-3 dropdown-item-pdf" href="{{ route('alat.pemeliharaan.pdf', ['id' => $alat->alat_id, 'bulan' => $bulan, 'tahun' => $tahun]) }}">
                                    <i class="fa fa-file-pdf text-danger me-2"></i> PDF
                                </a>
                            </li>
                            <li class="mt-1">
                                <a class="dropdown-item py-2 px-3 dropdown-item-excel" href="{{ route('alat.pemeliharaan.excel', ['id' => $alat->alat_id, 'bulan' => $bulan, 'tahun' => $tahun]) }}">
                                    <i class="fa fa-file-excel text-success me-2"></i> Excel
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </form>

            @php
                $jumlahKolom = max(1, $totalItems);
            @endphp

            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle table-pemeliharaan">
                    <thead class="text-white">
                        <tr class="text-center">
                            <th rowspan="2" class="align-middle text-white" style="width: 45px; background-color: #1b3152 !important; border-right: 1px solid #ffffff !important;">Tanggal</th>
                            <th colspan="{{ $jumlahKolom }}" class="text-white th-nowrap" style="background-color: #1b3152 !important; border-right: 1px solid #ffffff !important;">Jenis Pemeriksaan / Status</th>
                            <th rowspan="2" class="align-middle text-white" style="width: 180px; background-color: #1b3152 !important; border-right: 1px solid #ffffff !important;">Tindakan</th>
                            <th rowspan="2" class="align-middle text-white" style="width: 120px; background-color: #1b3152 !important; border-right: 1px solid #ffffff !important;">Petugas</th>
                        </tr>
                        <tr class="text-center">
                            @if($totalItems > 0)
                                @foreach($alat->itemPemeliharaan as $index => $item)
                                    <th class="text-white" style="width: 30px; background-color: #1b3152 !important; border-right: 1px solid #ffffff !important;" title="{{ $item->nama_pemeliharaan }}">{{ $item->nomor_urut }}</th>
                                @endforeach
                            @else
                                <th class="text-white" style="width: 30px; background-color: #1b3152 !important; border-right: 1px solid #ffffff !important;">-</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @for($d = 1; $d <= 31; $d++)
                            @php
                                $dateStr = sprintf('%04d-%02d-%02d', $tahun, $bulan, $d);
                                $isValidDate = checkdate($bulan, $d, $tahun);

                                $firstItem = $alat->itemPemeliharaan->first();
                                $firstLogKey = $firstItem ? $firstItem->item_id . '_' . $d : null;
                                $tindakanVal = $firstLogKey && isset($logs[$firstLogKey]) ? $logs[$firstLogKey]->tindakan : '';
                                $petugasVal = $firstLogKey && isset($logs[$firstLogKey]) ? $logs[$firstLogKey]->petugas : '';
                            @endphp
                            <tr>
                                <td class="fw-bold bg-light">{{ $d }}</td>

                                @if($totalItems > 0)
                                    @foreach($alat->itemPemeliharaan as $currentItem)
                                        @php
                                            $key = $currentItem->item_id . '_' . $d;
                                            $isChecked = isset($logs[$key]) && $logs[$key]->status == 1;
                                        @endphp
                                        <td>
                                            @if($isValidDate)
                                                <input type="checkbox" class="form-check-input pemeliharaan-checkbox"
                                                    data-item-id="{{ $currentItem->item_id }}"
                                                    data-tanggal="{{ $dateStr }}"
                                                    {{ $isChecked ? 'checked' : '' }}
                                                    {{ $canManagePemeliharaan ? '' : 'disabled' }}
                                                    style="cursor: {{ $canManagePemeliharaan ? 'pointer' : 'default' }};">
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                @else
                                    <td><span class="text-muted">-</span></td>
                                @endif

                                <td>
                                    @if($isValidDate)
                                        <input type="text" class="form-control form-control-sm tindakan-input input-table-size"
                                            data-tanggal="{{ $dateStr }}"
                                            value="{{ $tindakanVal }}"
                                            placeholder="Ketik tindakan..."
                                            {{ $canManagePemeliharaan ? '' : 'readonly' }}>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if($isValidDate)
                                    <input type="text" class="form-control form-control-sm petugas-input input-table-size"
                                        data-tanggal="{{ $dateStr }}" value="{{ $petugasVal ?: $namaPetugasLogin }}"
                                        placeholder="Nama petugas..."
                                        {{ $canManagePemeliharaan ? '' : 'readonly' }}>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@push('scripts')
@if($canManagePemeliharaan)
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.pemeliharaan-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            let itemId = this.dataset.itemId;
            let tanggal = this.dataset.tanggal;
            let status = this.checked ? 1 : 0;
            let currentCheckbox = this;

            currentCheckbox.disabled = true;

            fetch("{{ route('alat.pemeliharaan.update', $alat->alat_id)}}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    item_id: itemId,
                    tanggal: tanggal,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                currentCheckbox.disabled = false;
                if (!data.success) {
                    alert('Gagal menyimpan centang.');
                    currentCheckbox.checked = !status;
                }
            })
            .catch(error => {
                currentCheckbox.disabled = false;
                console.error('Error:', error);
                currentCheckbox.checked = !status;
            });
        });
    });

    function simpanTeksHarian(tanggal) {
        let row = document.querySelector(`.tindakan-input[data-tanggal="${tanggal}"]`).closest('tr');
        let tindakan = row.querySelector('.tindakan-input').value;
        let petugas = row.querySelector('.petugas-input').value;

        fetch("{{ route('alat.pemeliharaan.update', $alat->alat_id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                tanggal: tanggal,
                tindakan: tindakan,
                petugas: petugas
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Gagal menyimpan teks.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    document.querySelectorAll('.tindakan-input, .petugas-input').forEach(function(input) {
        input.addEventListener('blur', function() {
            let tanggal = this.dataset.tanggal;
            simpanTeksHarian(tanggal);
        });
    });
});
</script>
@endif
@endpush
@endsection