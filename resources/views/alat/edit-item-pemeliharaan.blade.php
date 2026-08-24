@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Atur Jenis Pemeliharaan</h5>
                    <a href="{{ route('alat.pemeliharaan', $alat->alat_id) }}" class="btn btn-light btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    <div class="alert alert-info py-2" style="font-size: 0.85rem;">
                        <i class="fas fa-info-circle me-1"></i> Atur item pemeliharaan untuk alat: <strong>{{ $alat->nama_alat }} ({{ $alat->kode_alat }})</strong>. Klik tombol <strong>+ Tambah Baris Pemeliharaan</strong> jika butuh lebih banyak baris.
                    </div>

                    <form action="{{ route('alat.item-pemeliharaan.update', $alat->alat_id) }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="tableItemPemeliharaan">
                                <thead class="table-secondary text-center">
                                    <tr>
                                        <th style="width: 80px;">No. Urut</th>
                                        <th>Nama Jenis Pemeliharaan</th>
                                        <th style="width: 60px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $countExisting = $alat->itemPemeliharaan->count();
                                        $totalRows = max(10, $countExisting);
                                    @endphp

                                    @for($i = 1; $i <= $totalRows; $i++)
                                        @php
                                            $existingItem = $alat->itemPemeliharaan->firstWhere('nomor_urut', $i);
                                        @endphp
                                        <tr>
                                            <td class="text-center fw-bold bg-light nomor-urut">{{ $i }}</td>
                                            <td>
                                                <input type="hidden" name="items[{{ $i }}][nomor_urut]" class="input-nomor-urut" value="{{ $i }}">
                                                <input type="text" name="items[{{ $i }}][nama_pemeliharaan]" class="form-control form-control-sm" 
                                                    value="{{ $existingItem ? $existingItem->nama_pemeliharaan : '' }}" 
                                                    placeholder="Nama jenis pemeliharaan baru...">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-hapus-baris" title="Hapus Baris">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="button" id="btnTambahBaris" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-plus me-1"></i> Tambah Baris Pemeliharaan
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Simpan Jenis Pemeliharaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
            <td class="text-center fw-bold bg-light nomor-urut">${nextNo}</td>
            <td>
                <input type="hidden" name="items[${nextNo}][nomor_urut]" class="input-nomor-urut" value="${nextNo}">
                <input type="text" name="items[${nextNo}][nama_pemeliharaan]" class="form-control form-control-sm" placeholder="Nama jenis pemeliharaan baru...">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm btn-hapus-baris" title="Hapus Baris">
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
@endsection