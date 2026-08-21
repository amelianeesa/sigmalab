@extends('layouts.app')

@push('styles')
<style>
    .card-header-custom {
        background-color: #0d6efd;
        color: white;
    }
    .table-header-custom {
        background-color: #e9ecef;
    }
    .input-table-size {
        font-size: 0.75rem;
    }
    .card-shadow-custom {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="card mb-4 mt-4 shadow-sm card-shadow-custom">
        <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-clipboard-check me-1"></i> KARTU PEMELIHARAAN PERALATAN</h5>
            <div>
                <a href="{{ route('alat.item-pemeliharaan.edit', $alat->alat_id) }}" class="btn btn-warning btn-sm text-dark fw-bold me-1">
                    <i class="fas fa-list-ol me-1"></i> Atur Jenis Pemeliharaan
                </a>
                <a href="{{ route('alat.input-kalibrasi', $alat->alat_id) }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            
            <table class="table table-borderless mb-3" style="font-size: 0.9rem;">
                <tr>
                    <td style="width: 200px;" class="fw-bold">Nama / Kode Peralatan</td>
                    <td>: {{ $alat->nama_alat }} / {{ $alat->kode_alat }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Merk / No. Serial</td>
                    <td>: {{ $alat->merk_tipe ?? '-' }} / {{ $alat->no_seri ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">No. Inventaris</td>
                    <td>: {{ $alat->no_inventaris ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Unit Kerja Pemilik</td>
                    <td>: {{ $alat->lokasi_alat ?? $alat->unit_kerja_pemilik ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="fw-bold align-top">Jenis Pemeliharaan</td>
                    <td class="align-top">
                        : 
                        <div class="row d-inline-block w-75 ms-1" style="font-size: 0.85rem;">
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

            <form method="GET" action="" class="row g-3 mb-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">BULAN / TAHUN</label>
                    <select name="bulan" class="form-select" onchange="this.form.submit()">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <!-- Input tahun tetap mempertahankan fungsi onchange submit form -->
                    <input type="number" name="tahun" class="form-control" value="{{ $tahun }}" onchange="this.form.submit()">
                </div>
                <!-- Tombol Dropdown Unduh Laporan (PDF & Excel) -->
                <div class="col-md-6 text-end">
                    <div class="dropdown">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownDownload" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-download"></i> Unduh Laporan Pemeliharaan
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownDownload">
                            <li>
                                <a class="dropdown-item" href="{{ route('alat.pemeliharaan.pdf', ['id' => $alat->alat_id, 'bulan' => $bulan, 'tahun' => $tahun]) }}">
                                    <i class="fa fa-file-pdf text-danger me-2"></i> PDF
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('alat.pemeliharaan.excel', ['id' => $alat->alat_id, 'bulan' => $bulan, 'tahun' => $tahun]) }}">
                                    <i class="fa fa-file-excel text-success me-2"></i> Excel
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>             
            </form>

            @php
                // Kolom tabel menyesuaikan jumlah item yang diinput (minimal 1 agar layout tidak rusak)
                $jumlahKolom = max(1, $totalItems);
            @endphp

            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle" style="font-size: 0.75rem;">
                    <thead class="table-header-custom">
                        <tr>
                            <th rowspan="2" class="align-middle" style="width: 60px;">Tanggal</th>
                            <th colspan="{{ $jumlahKolom }}">Jenis Pemeriksaan / Status</th>
                            <th rowspan="2" class="align-middle" style="width: 220px;">Tindakan</th>
                            <th rowspan="2" class="align-middle" style="width: 140px;">Petugas</th>
                        </tr>
                        <tr>
                            @if($totalItems > 0)
                                @foreach($alat->itemPemeliharaan as $item)
                                    <th style="width: 35px;" title="{{ $item->nama_pemeliharaan }}">{{ $item->nomor_urut }}</th>
                                @endforeach
                            @else
                                <th style="width: 35px;">-</th>
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
                                                    style="cursor: pointer;">
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
                                            placeholder="Ketik tindakan...">
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if($isValidDate)
                                    <input type="text" class="form-control form-control-sm petugas-input input-table-size" 
                                        data-tanggal="{{ $dateStr }}" value="{{ $petugasVal ?: $namaPetugasLogin }}" 
                                        placeholder="Nama petugas...">
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
@endpush
@endsection