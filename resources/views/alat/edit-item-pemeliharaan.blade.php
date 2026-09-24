@extends('layouts.app')

@section('content')
<style>
    .container-fluid {
        padding-top: 4px !important;
    }
    .page-title {
        font-size: 1.3rem;
    }
    .breadcrumb-small {
        font-size: 12px;
    }
    .info-alat {
        font-size: 0.85rem;
        color: #1b3152;
        margin-bottom: 0.75rem;
    }

    .btn-tambah-baris {
        display: block;
        width: 100%;
        margin-top: 0.5rem;
        padding: 0.4rem 0.5rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: #1b3152;
        background-color: #ffffff;
        border: 1px dashed #1b3152;
        border-radius: 0.25rem;
        transition: all 0.2s ease-in-out;
    }
    .btn-tambah-baris:hover,
    .btn-tambah-baris:focus {
        background-color: #eef2f8;
        border-style: solid;
        outline: none;
    }
    .table-item {
        margin-bottom: 0;
        background-color: #ffffff;
    }
    .table-item th,
    .table-item td {
        padding: 0.35rem 0.5rem !important;
        font-size: 0.78rem !important;
        vertical-align: middle;
    }
    .table-item thead th {
        background-color: #1b3152 !important;
        color: #ffffff !important;
        border-right: 1px solid #ffffff !important;
        font-weight: 600;
    }
    .table-item .nomor-urut {
        font-weight: 400;
    }
    .table-item .form-control-sm {
        font-size: 0.78rem !important;
        height: 30px;
        padding: 0.2rem 0.5rem !important;
    }

    .btn-aksi {
        font-size: 0.72rem !important;
        font-weight: 700;
        padding: 0.3rem 0.7rem !important;
        border: 1px solid transparent !important;
        transition: all 0.2s ease-in-out !important;
    }
    .btn-aksi:hover,
    .btn-aksi:focus,
    .btn-aksi:active {
        transform: translateY(-1px) !important;
    }

    .btn-navy {
        background-color: #1b3152 !important;
        color: #ffffff !important;
    }
    .btn-navy:hover,
    .btn-navy:focus,
    .btn-navy:active {
        background-color: #3b5f93 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 8px rgba(27, 49, 82, 0.3) !important;
    }

    .btn-kembali {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: #ffffff !important;
    }
    .btn-kembali:hover,
    .btn-kembali:focus,
    .btn-kembali:active {
        background-color: #ffffff !important;
        border-color: #6c757d !important;
        color: #000000 !important;
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.25) !important;
    }

    .btn-hapus-baris {
        font-size: 0.75rem;
        padding: 0.1rem 0.4rem;
    }
</style>

<div class="container-fluid pt-1 pb-3 px-4" style="max-width: 950px;">

    <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
        <div>
            <h5 class="fw-bold mb-1 page-title">Daftar Jenis Pemeliharaan</h5>
            <ol class="breadcrumb mb-0 breadcrumb-small">
                <li class="breadcrumb-item"><a href="{{ route('alat.index') }}" class="text-decoration-none">Data Alat & Kalibrasi</a></li>
                <li class="breadcrumb-item"><a href="{{ route('alat.pemeliharaan', $alat->alat_id) }}" class="text-decoration-none">Kartu Pemeliharaan</a></li>
                <li class="breadcrumb-item text-muted active">Jenis Pemeliharaan</li>
            </ol>
        </div>
        <div>
            <a href="{{ route('alat.pemeliharaan', $alat->alat_id) }}" class="btn btn-sm btn-aksi btn-kembali">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="info-alat">
        Alat: <strong>{{ $alat->nama_alat }} ({{ $alat->kode_alat }})</strong>
    </div>

    <form action="{{ route('alat.item-pemeliharaan.update', $alat->alat_id) }}" method="POST">
        @csrf
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle table-item" id="tableItemPemeliharaan">
                <thead>
                    <tr>
                        <th style="width: 70px; background-color: #1b3152 !important; border-right: 1px solid #ffffff !important;">No. Urut</th>
                        <th class="text-start" style="background-color: #1b3152 !important; border-right: 1px solid #ffffff !important;">Nama Jenis Pemeliharaan</th>
                        <th style="width: 60px; background-color: #1b3152 !important;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $countExisting = $alat->itemPemeliharaan->count();
                        $totalRows = max(6, $countExisting);
                    @endphp

                    @for($i = 1; $i <= $totalRows; $i++)
                        @php
                            $existingItem = $alat->itemPemeliharaan->firstWhere('nomor_urut', $i);
                        @endphp
                        <tr>
                            <td class="bg-light nomor-urut">{{ $i }}</td>
                            <td>
                                <input type="hidden" name="items[{{ $i }}][nomor_urut]" class="input-nomor-urut" value="{{ $i }}">
                                <input type="text" name="items[{{ $i }}][nama_pemeliharaan]" class="form-control form-control-sm"
                                    value="{{ $existingItem ? $existingItem->nama_pemeliharaan : '' }}"
                                    placeholder="Ketik nama jenis pemeliharaan...">
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-danger btn-hapus-baris" title="Hapus Baris">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <button type="button" id="btnTambahBaris" class="btn-tambah-baris">
            <i class="fas fa-plus me-1"></i> Tambah Baris
        </button>

        <div class="mt-3 text-end">
            <button type="submit" class="btn btn-sm btn-aksi btn-navy">
                <i class="fas fa-save me-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

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
            <td class="bg-light nomor-urut">${nextNo}</td>
            <td>
                <input type="hidden" name="items[${nextNo}][nomor_urut]" class="input-nomor-urut" value="${nextNo}">
                <input type="text" name="items[${nextNo}][nama_pemeliharaan]" class="form-control form-control-sm" placeholder="Ketik nama jenis pemeliharaan...">
            </td>
            <td>
                <button type="button" class="btn btn-outline-danger btn-hapus-baris" title="Hapus Baris">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;

        tableBody.appendChild(newRow);
        reindexRows();
        newRow.querySelector("input[type='text']").focus();
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
@endsection