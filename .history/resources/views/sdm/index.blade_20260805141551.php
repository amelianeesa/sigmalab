@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">SDM & Kompetensi</h2>
            <p class="text-muted mb-0">Profil personil, matriks kompetensi & sertifikasi</p>
        </div>
        <div>
            <!-- Tombol Pemicu Modal Sederhana -->
            <button type="button" class="btn btn-dark shadow-sm px-3 fw-semibold rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambahPersonil">
                + Tambah Personil
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-secondary">
                        <tr>
                            <th class="py-3">Nama</th>
                            <th class="py-3">Posisi</th>
                            <th class="py-3">Sertifikasi Terakhir</th>
                            <th class="py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($personil as $row)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $row->nama }}</td>
                            <td><span class="text-muted">{{ $row->jabatan }}</span></td>
                            <td>{{ $row->nama_sertifikasi ?? 'Belum ada' }}</td>
                            <td>
                                <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-3">
                                    • Perlu Sertifikasi Ulang
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                Belum ada data personil terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL TAMBAH PERSONIL (SIMPLE & CLEAN) ================= -->
<div class="modal fade" id="modalTambahPersonil" tabindex="-1" aria-labelledby="modalTambahPersonilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-white px-4 py-3 border-bottom">
                <h5 class="modal-title fw-bold text-dark" id="modalTambahPersonilLabel">Tambah Personil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('sdm.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 bg-white">
                    
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Nama</label>
                        <input type="text" name="nama" class="form-control" placeholder="mis. Budi Santoso" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Posisi / Lab</label>
                        <input type="text" name="jabatan" class="form-control" placeholder="mis. Analis Lab Kimia" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Link CV</label>
                        <input type="text" name="link_cv" class="form-control" placeholder="mis. drive.google.com/... (opsional)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Nama Sertifikasi / Pelatihan Awal</label>
                        <input type="text" name="nama_sertifikasi" class="form-control" placeholder="mis. Pelatihan K3 Laboratorium">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary small fw-semibold">Tgl Terbit</label>
                            <input type="date" name="tgl_terbit" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary small fw-semibold">Masa Berlaku (bulan)</label>
                            <input type="number" name="masa_berlaku" class="form-control" value="24">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Reminder Sebelum Jatuh Tempo</label>
                        <select name="reminder" class="form-select">
                            <option value="H-30">H-30</option>
                            <option value="H-60">H-60</option>
                            <option value="H-90">H-90</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer bg-white px-4 py-3 border-top">
                    <button type="button" class="btn btn-light border px-4 text-secondary fw-semibold rounded-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark px-4 fw-semibold rounded-2" style="background-color: #0b1f36;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection