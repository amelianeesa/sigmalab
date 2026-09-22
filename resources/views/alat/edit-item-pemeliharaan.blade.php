@extends('layouts.app')

@push('styles')
<style>
    /* Styling compact & seragam */
    .container-fluid {
        padding-top: 4px !important;
    }
    .table-info-custom {
        font-size: 0.72rem !important;
    }
    .form-control-sm {
        font-size: 0.75rem !important;
        padding: 0.2rem 0.4rem !important;
        height: auto !important;
    }
    .table th, .table td {
        padding: 0.3rem 0.4rem !important;
        font-size: 0.72rem !important;
    }
    .card-shadow-custom {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
</style>
@endpush

@section('content')
<div class="container-fluid pt-1 pb-3 px-4" style="max-width: 950px;">
    
    <!-- Judul Halaman di Luar Card (Tanpa Bungkus Card Ganda) -->
    <div class="d-flex justify-content-between align-items-center mb-2 mt-1">
        <div>
            <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.1rem;">
                 ATUR JENIS PEMELIHARAAN
            </h5>
            <small class="text-muted" style="font-size: 11px;">Kelola daftar item pemeriksaan harian untuk peralatan laboratorium</small>
        </div>
        <div>
            <a href="{{ route('alat.pemeliharaan', $alat->alat_id) }}" class="btn btn-outline-secondary btn-sm fw-bold py-1 px-2 shadow-sm" style="font-size: 11px;">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Konten Utama Tanpa Card Luar -->
    <div class="card mb-3 shadow-sm card-shadow-custom border-0">
        <div class="card-body p-3">
            
            <div class="alert alert-info py-1 px-2 mb-2" style="font-size: 0.78rem;">
                <i class="fas fa-info-circle me-1"></i> Alat: <strong>{{ $alat->nama_alat }} ({{$alat->kode_alat }})</strong>. Klik tombol <strong>+ Tambah Baris</strong> jika butuh lebih banyak baris.
            </div>

            <form action="{{ route('alat.item-pemeliharaan.update', $alat->alat_id) }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle mb-2" id="tableItemPemeliharaan">
                        <thead class="table-secondary align-middle" style="font-size: 0.72rem;">
                            <tr>
                                <th style="width: 60px;" class="py-1">No. Urut</th>
                                <th class="py-1 text-start">Nama Jenis Pemeliharaan</th>
                                <th style="width: 50px;" class="py-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $countExisting =$alat->itemPemeliharaan->count();
                                $totalRows = max(6,$countExisting);
                            @endphp

                            @for($i = 1; $i <= $totalRows; $i++)
                                @php
                                    $existingItem = $alat->itemPemeliharaan->firstWhere('nomor_urut',$i);
                                @endphp
                                <tr>
                                    <td class="fw-bold bg-light py-1 nomor-urut">{{ $i }}</td>
                                    <td class="py-1">
                                        <input type="hidden" name="items[{{ $i }}][nomor_urut]" class="input-nomor-urut" value="{{ $i }}">
                                        <input type="text" name="items[{{ $i }}][nama_pemeliharaan]" class="form-control form-control-sm" 
                                            value="{{ $existingItem ? $existingItem->nama_pemeliharaan : '' }}" 
                                            placeholder="Ketik nama jenis pemeliharaan...">
                                    </td>
                                    <td class="py-1">
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-hapus-baris py-0 px-1" title="Hapus Baris" style="font-size: 11px;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-2">
                    <button type="button" id="btnTambahBaris" class="btn btn-outline-primary btn-sm fw-bold py-1 px-2" style="font-size: 11px;">
                        <i class="fas fa-plus me-1"></i> Tambah Baris Pemeliharaan
                    </button>
                    <button type="submit" class="btn btn-success btn-sm fw-bold py-1 px-3 shadow-sm" style="font-size: 11px;">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.querySelector("#tableItemPemeliharaan tbody");
    const btnTambahBaris = document.getElementById("btnTambahBaris");

    function reindexRows() {
        const rows = tableBody.querySelectorAll("tr");
        rows.forEach((row, index) => {
            const no = index + 1;
            row.querySelector(".nomor-urut").textContent = no;
            row.querySelector(".input-nomor-urut").value = no;
            row.querySelector(".input-nomor-urut").name = `items[${no}][nomor_urut]`;
            row.querySelector("input[type='text']").name = `items[${no}][nama_pemeliharaan]`;
        });
    }

    btnTambahBaris.addEventListener("click", function () {
        const nextNo = tableBody.querySelectorAll("tr").length + 1;
        const newRow = document.createElement("tr");
        
        newRow.innerHTML = `
            <td class="fw-bold bg-light py-1 nomor-urut">${nextNo}</td>
            <td class="py-1">
                <input type="hidden" name="items[${nextNo}][nomor_urut]" class="input-nomor-urut" value="${nextNo}">
                <input type="text" name="items[${nextNo}][nama_pemeliharaan]" class="form-control form-control-sm" placeholder="Ketik nama jenis pemeliharaan...">
            </td>
            <td class="py-1">
                <button type="button" class="btn btn-outline-danger btn-sm btn-hapus-baris py-0 px-1" title="Hapus Baris" style="font-size: 11px;">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;

        tableBody.appendChild(newRow);
        reindexRows();
    });

    tableBody.addEventListener("click", function (e) {
        if (e.target.closest(".btn-hapus-baris")) {
            const row = e.target.closest("tr");
            if (tableBody.querySelectorAll("tr").length > 1) {
                row.remove();
                reindexRows();
            } else {
                alert("Minimal harus ada 1 baris!");
            }
        }
    });
});
</script>
@endpush
@endsection