@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
    <style>
        .page-container {
            font-size: 0.78rem;
        }

        .profile-avatar {
            width: 44px;
            height: 44px;
            font-size: 16px;
            flex-shrink: 0;
            margin-left: 8px;
        }
        .profile-back-btn {
            font-size: 0.73rem;
            margin-right: 8px;
        }

        .table thead th {
            background-color: #1b3152 !important;
            color: #ffffff !important;
            padding: 8px 6px;
            font-size: 0.7rem;
            text-align: center;
            vertical-align: middle;
        }
        .table tbody td {
            padding: 8px 6px;
            font-size: 0.73rem;
            vertical-align: middle;
        }

        .col-jenis { width: 170px; }
        .col-no { width: 95px; }
        .col-tanggal { width: 90px; }
        .col-status { width: 85px; }
        .col-dokumen { width: 230px; }
        .col-aksi { width: 95px; }

        @media (max-width: 768px) {
            .table thead th { font-size: 0.62rem; padding: 6px 4px; white-space: nowrap; }
            .table tbody td { font-size: 0.65rem; padding: 6px 4px; }

            .col-jenis { width: 130px; }
            .col-no { width: 75px; }
            .col-tanggal { width: 68px; }
            .col-status { width: 65px; }
            .col-dokumen { width: 150px; }
            .col-aksi { width: 75px; }

            .table-upload-input { width: 90px; font-size: 0.6rem; }
            .table-upload-btn { font-size: 0.62rem; padding: 0.1rem 0.35rem; }
            .table-action-btn { font-size: 0.62rem !important; padding: 0.2rem 0.35rem !important; }
        }

        .btn-corporate-outline {
            color: #1b3152;
            border-color: #1b3152;
            background-color: transparent;
            font-size: 0.68rem;
            transition: all 0.2s ease-in-out;
        }
        .btn-corporate-outline:hover,
        .btn-corporate-outline:focus,
        .btn-corporate-outline:active {
            background-color: #1b3152 !important;
            border-color: #1b3152 !important;
            color: #ffffff !important;
        }
        .btn-corporate-outline:hover i,
        .btn-corporate-outline:focus i,
        .btn-corporate-outline:hover *,
        .btn-corporate-outline:focus * {
            color: #ffffff !important;
        }

        .table-action-btn {
            font-size: 0.7rem !important;
            padding: 0.25rem 0.45rem !important;
        }

        .table-upload-input {
            font-size: 0.65rem;
            width: 140px;
            height: 28px;
        }
        .table-upload-btn {
            font-size: 0.68rem;
            height: 28px;
            padding: 0.1rem 0.5rem;
        }

        .flatpickr-input {
            font-size: 0.73rem;
        }
        .flatpickr-calendar {
            font-size: 0.8rem;
        }
    </style>

    <div class="container-fluid px-3 pt-1 pb-2 page-container">

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm py-1.5 mb-2 pe-5 position-relative" role="alert" style="font-size: 0.73rem;">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> Data belum dapat disimpan. Periksa isian berikut.
                <ul class="mb-0 mt-1 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-3 mb-2">
            <div class="card-body p-3 d-flex justify-content-between align-items-start align-items-md-center flex-column flex-md-row gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-dark text-white fw-bold d-flex align-items-center justify-content-center shadow-sm profile-avatar">
                        {{ strtoupper(substr($personil->nama, 0, 2)) }}
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 1rem;">{{ $personil->nama }}</h5>
                        <p class="text-muted mb-0" style="font-size: 0.72rem;">{{ $personil->jabatan }} — Unit Kerja: {{ $personil->unit_kerja }} | No. Pegawai: {{ $personil->no_induk }}</p>
                    </div>
                </div>
                <a href="{{ route('sdm.index') }}" class="btn btn-outline-secondary btn-sm px-2.5 py-1 fw-semibold rounded-pill profile-back-btn">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-0 pt-2.5 pb-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">Training Data Record</h6>
                    <p class="text-muted small mb-0" style="font-size: 0.68rem;">Seluruh riwayat sertifikasi &amp; pelatihan yang pernah diikuti personil ini.</p>
                </div>
                <button type="button" class="btn btn-dark btn-sm px-2.5 py-1 fw-semibold rounded-pill" style="font-size: 0.73rem;" data-bs-toggle="modal" data-bs-target="#modalTambahSertifikasi">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Sertifikasi / Pelatihan
                </button>
            </div>
            <div class="card-body px-3 pb-3 pt-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="col-jenis">Jenis Sertifikasi / Pelatihan</th>
                                <th class="col-no">No. Sertifikat</th>
                                <th class="col-tanggal">Tanggal Terbit</th>
                                <th class="col-tanggal">Masa Berlaku Berakhir</th>
                                <th class="col-status">Status</th>
                                <th class="col-dokumen">Dokumen</th>
                                <th class="col-aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($personil->kompetensi ?? [] as $komp)
                            <tr>
                                <td class="fw-semibold text-dark text-start">{{ $komp->jenis_sertifikasi }}</td>
                                <td class="text-center"><code class="text-dark" style="font-size: 0.68rem;">{{ $komp->no_sertifikasi ?? '-' }}</code></td>
                                <td class="text-center">{{ $komp->tanggal_terbit?->format('d/m/Y') ?? '-' }}</td>
                                <td class="text-center">{{ $komp->tanggal_berakhir?->format('d/m/Y') ?? 'Tidak Terbatas' }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $komp->status['class'] }} px-1.5 py-1 text-nowrap" style="font-size: 0.65rem;">
                                        <i class="bi bi-{{ $komp->status['icon'] }} me-0.5"></i>{{ $komp->status['label'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($komp->file_sertifikat)
                                        <a href="{{ route('sdm.kompetensi.file', [$personil->personil_id, $komp->kompetensi_personil_id]) }}" target="_blank" rel="noopener" class="btn btn-corporate-outline btn-sm px-2.5 py-1 text-nowrap">
                                            <i class="bi bi-file-earmark-text me-1"></i> Lihat Dokumen
                                        </a>
                                    @else
                                        <form action="{{ route('sdm.kompetensi.file.upload', [$personil->personil_id, $komp->kompetensi_personil_id]) }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center justify-content-center gap-1 m-0">
                                            @csrf
                                            <input type="file" name="file_sertifikat" class="form-control form-control-sm px-1 table-upload-input" accept="image/*,application/pdf" required>
                                            <button type="submit" class="btn btn-sm btn-dark text-nowrap table-upload-btn">Unggah</button>
                                        </form>
                                    @endif
                                </td>
                                <td class="text-center text-nowrap">
                                    <button type="button" class="btn btn-warning btn-sm table-action-btn me-1" title="Edit"
                                        data-bs-toggle="modal" data-bs-target="#modalEditSertifikasi"
                                        data-action="{{ route('sdm.kompetensi.update', [$personil->personil_id, $komp->kompetensi_personil_id]) }}"
                                        data-jenis="{{ $komp->jenis_sertifikasi }}"
                                        data-no="{{ $komp->no_sertifikasi }}"
                                        data-terbit="{{ $komp->tanggal_terbit?->format('Y-m-d') }}"
                                        data-berakhir="{{ $komp->tanggal_berakhir?->format('Y-m-d') }}"
                                        data-has-file="{{ $komp->file_sertifikat ? '1' : '0' }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('sdm.kompetensi.destroy', [$personil->personil_id, $komp->kompetensi_personil_id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus riwayat sertifikasi &quot;{{ $komp->jenis_sertifikasi }}&quot;? Dokumen terkait juga akan terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm table-action-btn" title="Hapus"
                                        data-bs-toggle="modal" data-bs-target="#modalHapusSertifikasi"
                                        data-action="{{ route('sdm.kompetensi.destroy', [$personil->personil_id, $komp->kompetensi_personil_id]) }}"
                                        data-namasertifikat="{{ $komp->jenis_sertifikasi }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-shield-exclamation fs-4 d-block mb-1"></i>
                                    Belum ada riwayat sertifikasi / pelatihan yang tercatat untuk personil ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahSertifikasi" tabindex="-1" aria-labelledby="modalTambahSertifikasiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden" style="font-size: 0.78rem;">
                <form action="{{ route('sdm.kompetensi.store', $personil->personil_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header text-white py-1.5 px-3" style="background-color: #1b3152 !important;">
                        <h5 class="modal-title fw-bold text-white mb-0" id="modalTambahSertifikasiLabel" style="font-size: 0.9rem;">Tambah Sertifikasi / Pelatihan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Jenis Sertifikasi / Pelatihan <span class="text-danger">*</span></label>
                            <input type="text" name="jenis_sertifikasi" class="form-control form-control-sm py-1" placeholder="mis. Pelatihan K3 Laboratorium" required style="font-size: 0.73rem;">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Nomor Sertifikat</label>
                            <input type="text" name="no_sertifikasi" class="form-control form-control-sm py-1" placeholder="mis. K3-LAB/2026/001" style="font-size: 0.73rem;">
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Tanggal Terbit</label>
                                <input type="text" name="tanggal_terbit" id="addTanggalTerbit" class="form-control form-control-sm py-1 flatpickr-date" value="{{ date('Y-m-d') }}" placeholder="dd/mm/yyyy" autocomplete="off" style="font-size: 0.73rem;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Tanggal Berakhir</label>
                                <input type="text" name="tanggal_berakhir" id="addTanggalBerakhir" class="form-control form-control-sm py-1 flatpickr-date" placeholder="dd/mm/yyyy" autocomplete="off" style="font-size: 0.73rem;">
                                <div class="form-text text-muted mt-0.5" style="font-size: 0.62rem;">Kosongkan bila tidak terbatas.</div>
                            </div>
                        </div>
                        <div class="mb-1">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Dokumen Sertifikat</label>
                            <input type="file" name="file_sertifikat" class="form-control form-control-sm py-1" accept="image/*,application/pdf" style="font-size: 0.73rem;">
                            <div class="form-text text-muted mt-0.5" style="font-size: 0.62rem;">Format: JPG, PNG, PDF (Maks. 2MB).</div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-1.5 px-3">
                        <button type="button" class="btn btn-secondary btn-sm py-1 px-3" data-bs-dismiss="modal" style="font-size: 0.73rem;">Batal</button>
                        <button type="submit" class="btn btn-sm py-1 px-3 text-white" style="background-color: #1b3152; font-size: 0.73rem;"><i class="fas fa-save me-1"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditSertifikasi" tabindex="-1" aria-labelledby="modalEditSertifikasiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden" style="font-size: 0.78rem;">
                <form id="formEditSertifikasi" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header text-white py-1.5 px-3" style="background-color: #1b3152 !important;">
                        <h5 class="modal-title fw-bold text-white mb-0" id="modalEditSertifikasiLabel" style="font-size: 0.9rem;"><i class="fas fa-edit me-1"></i>Edit Sertifikasi / Pelatihan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Jenis Sertifikasi / Pelatihan <span class="text-danger">*</span></label>
                            <input type="text" name="jenis_sertifikasi" id="editJenisSertifikasi" class="form-control form-control-sm py-1" required style="font-size: 0.73rem;">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Nomor Sertifikat</label>
                            <input type="text" name="no_sertifikasi" id="editNoSertifikasi" class="form-control form-control-sm py-1" style="font-size: 0.73rem;">
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Tanggal Terbit</label>
                                <input type="text" name="tanggal_terbit" id="editTanggalTerbit" class="form-control form-control-sm py-1 flatpickr-date" placeholder="dd/mm/yyyy" autocomplete="off" style="font-size: 0.73rem;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Tanggal Berakhir</label>
                                <input type="text" name="tanggal_berakhir" id="editTanggalBerakhir" class="form-control form-control-sm py-1 flatpickr-date" placeholder="dd/mm/yyyy" autocomplete="off" style="font-size: 0.73rem;">
                                <div class="form-text text-muted mt-0.5" style="font-size: 0.62rem;">Kosongkan bila tidak terbatas.</div>
                            </div>
                        </div>
                        <div class="mb-1">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Dokumen Sertifikat</label>
                            <input type="file" name="file_sertifikat" class="form-control form-control-sm py-1" accept="image/*,application/pdf" style="font-size: 0.73rem;">
                            <div class="form-text text-muted mt-0.5" style="font-size: 0.62rem;" id="editFileInfo">Kosongkan bila tidak ingin mengganti dokumen yang sudah ada.</div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light py-1.5 px-3">
                        <button type="button" class="btn btn-secondary btn-sm py-1 px-3" data-bs-dismiss="modal" style="font-size: 0.73rem;">Batal</button>
                        <button type="submit" class="btn btn-sm py-1 px-3 text-white" style="background-color: #1b3152; font-size: 0.73rem;"><i class="fas fa-save me-1"></i> Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- DITAMBAHKAN -->
<div class="modal fade" id="modalHapusSertifikasi" tabindex="-1" aria-labelledby="modalHapusSertifikasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
        <div class="modal-content border-0 shadow text-center p-3" style="font-size: 0.82rem; border-radius: 8px;">
            <div class="pt-2 pb-1">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background-color: #fff8e6; color: #f0ad4e; font-size: 24px; border: 2px solid #ffeeba;">
                    <i class="fas fa-exclamation"></i>
                </div>
            </div>
            <div class="modal-body px-2 py-2">
                <h5 class="fw-bold text-dark mb-1" style="font-size: 1rem;">Apakah Anda yakin?</h5>
                <p class="text-muted mb-0" style="font-size: 0.78rem;">Data riwayat sertifikasi <strong id="namaSertifikasiHapus" class="text-dark"></strong> ini akan dihapus secara permanen beserta dokumen terkait!</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-1 pb-2">
                <button type="button" class="btn btn-secondary btn-sm py-1.5 px-3.5 fw-semibold rounded-2" data-bs-dismiss="modal" style="font-size: 0.78rem;">Batal</button>
                <form id="formHapusSertifikasi" action="" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm py-1.5 px-3.5 fw-semibold rounded-2" style="font-size: 0.78rem;">Ya, Hapus!</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const commonOpts = {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd/m/Y',
                allowInput: true
            };

            flatpickr('#addTanggalTerbit', commonOpts);
            flatpickr('#addTanggalBerakhir', commonOpts);

            const fpEditTerbit = flatpickr('#editTanggalTerbit', commonOpts);
            const fpEditBerakhir = flatpickr('#editTanggalBerakhir', commonOpts);

            const modalEdit = document.getElementById('modalEditSertifikasi');
            if (!modalEdit) return;

            modalEdit.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                document.getElementById('formEditSertifikasi').action = button.getAttribute('data-action');
                document.getElementById('editJenisSertifikasi').value = button.getAttribute('data-jenis') || '';
                document.getElementById('editNoSertifikasi').value = button.getAttribute('data-no') || '';

                const terbit = button.getAttribute('data-terbit') || '';
                const berakhir = button.getAttribute('data-berakhir') || '';
                fpEditTerbit.setDate(terbit || null, true);
                fpEditBerakhir.setDate(berakhir || null, true);

                const hasFile = button.getAttribute('data-has-file') === '1';
                document.getElementById('editFileInfo').textContent = hasFile
                    ? 'Sudah ada dokumen tersimpan. Kosongkan bila tidak ingin menggantinya.'
                    : 'Belum ada dokumen. Unggah di sini bila tersedia.';
            });
        });
        const modalHapus = document.getElementById('modalHapusSertifikasi');
        if (modalHapus) {
            modalHapus.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const actionUrl = button.getAttribute('data-action');
                const namaSertifikasi = button.getAttribute('data-namasertifikat');
            
                document.getElementById('formHapusSertifikasi').action = actionUrl;
                document.getElementById('namaSertifikasiHapus').textContent = `"${namaSertifikasi}"`;
            });
        }
    </script>
@endsection