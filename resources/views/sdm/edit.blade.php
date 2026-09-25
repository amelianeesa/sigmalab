@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        .btn-corporate-dark {
            background-color: #1b3152 !important;
            border-color: #1b3152 !important;
            color: #ffffff !important;
        }
        .btn-corporate-dark:hover, 
        .btn-corporate-dark:focus, 
        .btn-corporate-dark:active {
            background-color: #14253e !important;
            border-color: #14253e !important;
            color: #ffffff !important;
        }
        .btn-corporate-outline {
            color: #1b3152;
            border-color: #1b3152;
            background-color: transparent;
        }
        .btn-corporate-outline:hover,
        .btn-corporate-outline:focus,
        .btn-corporate-outline:active {
            background-color: #1b3152 !important;
            border-color: #1b3152 !important;
            color: #ffffff !important;
        }
        .alert-dismissible .btn-close {
            padding: 0;
            top: 50%;
            transform: translateY(-50%);
            right: 1rem;
        }

        /* ===== Select2 & Flatpickr custom, konsisten dengan tema corporate ===== */
        .select2-container--bootstrap-5 .select2-selection {
            font-size: 0.75rem !important;
            min-height: 31px !important;
        }
        .select2-container--bootstrap-5 .select2-selection__rendered {
            padding-top: 0 !important;
        }
        .select2-container--bootstrap-5 .select2-results__option {
            font-size: 0.8rem !important;
        }
        .flatpickr-input {
            background-color: #fff !important;
        }
    </style>

    <div class="container-fluid px-3 pt-1 pb-2" style="font-size: 0.8rem;">
        <div class="mb-2">
            <h4 class="mb-0 fw-bold">Edit Data Personil</h4>
            <p class="text-muted small mb-0" style="font-size: 0.72rem;">Perbarui profil, dokumen, dan sertifikasi terakhir personil.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm py-1 mb-2 pe-5 position-relative" role="alert" style="font-size: 0.75rem;">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> Data belum dapat disimpan. Periksa isian berikut.
                <ul class="mb-0 mt-1 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm py-1 mb-2 pe-5 position-relative" role="alert" style="font-size: 0.75rem;">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('sdm.update', $personil->personil_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card border-0 shadow-sm mb-2">
                <div class="card-body p-2.5">
                    <div class="mb-2 pb-1 border-bottom">
                        <span class="fw-bold text-dark">
                            Data Induk Personil
                        </span>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">
                                Nama Lengkap
                            </label>
                            <input type="text" name="nama" class="form-control form-control-sm py-1" value="{{ old('nama', $personil->nama) }}" required style="font-size: 0.75rem;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">
                                Nomor Pegawai
                            </label>
                            <input type="text" name="no_induk" class="form-control form-control-sm py-1" value="{{ old('no_induk', $personil->no_induk) }}" required style="font-size: 0.75rem;">
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label small fw-semibold mb-0" style="font-size: 0.73rem;">
                                    Kategori Personil
                                </label>
                                <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="modal" data-bs-target="#modalTambahKategori" style="font-size: 0.68rem;">
                                    <i class="fas fa-plus-circle"></i> Kategori Baru
                                </button>
                            </div>
                            <select name="kategori_personil" class="form-select form-select-sm py-1 select2-basic" style="font-size: 0.75rem;">
                                <option value="">— Pilih Kategori —</option>
                                @foreach($kategoriOptions as $value => $label)
                                    <option value="{{ $value }}" {{ old('kategori_personil', $personil->kategori_personil) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">
                                Penempatan
                            </label>
                            <input type="text" name="jabatan" class="form-control form-control-sm py-1" value="{{ old('jabatan', $personil->jabatan) }}" required style="font-size: 0.75rem;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">
                                Unit Kerja
                            </label>
                            <input type="text" name="unit_kerja" class="form-control form-control-sm py-1" value="{{ old('unit_kerja', $personil->unit_kerja) }}" required style="font-size: 0.75rem;">
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label small fw-semibold mb-0" style="font-size: 0.73rem;">
                                    Ganti Dokumen CV <span class="text-muted fw-normal">(Opsional)</span>
                                </label>
                                <a href="{{ asset('templates/' . rawurlencode('Template_CV_PT SUCOFINDO.docx')) }}" download class="small text-decoration-none" style="font-size: 0.68rem;">
                                    <i class="fas fa-download me-1"></i> Unduh Template CV
                                </a>
                            </div>

                            <div class="input-group input-group-sm">
                                <input type="file" name="file_cv" class="form-control py-1" accept="image/*,application/pdf" style="font-size: 0.73rem;">
                                @if($personil->file_cv)
                                    <a href="{{ route('sdm.cv', $personil->personil_id) }}?v={{ $personil->updated_at?->timestamp }}" target="_blank" rel="noopener" class="btn btn-corporate-outline px-3 d-flex align-items-center" style="font-size: 0.73rem;">
                                        <i class="bi bi-file-earmark-text me-1"></i> Lihat CV Saat Ini
                                    </a>
                                @endif
                            </div>

                            <div class="form-text text-muted mt-1" style="font-size: 0.65rem;">
                                Format: JPG, PNG, PDF (Maks. 2MB).
                            </div>
                        </div>
                    </div>

                    <div class="mb-2 pb-1 border-bottom">
                        <span class="fw-bold">
                            Sertifikasi & Pelatihan Terakhir
                        </span>
                    </div>

                    <div class="row g-2 mb-1">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">
                                Nama Sertifikasi / Pelatihan
                            </label>
                            <input type="text" name="nama_sertifikasi" class="form-control form-control-sm py-1" value="{{ old('nama_sertifikasi', $sertifikasi?->jenis_sertifikasi) }}" placeholder="mis. Pelatihan K3 Laboratorium" style="font-size: 0.75rem;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">
                                Nomor Sertifikat
                            </label>
                            <input type="text" name="no_sertifikasi" class="form-control form-control-sm py-1" value="{{ old('no_sertifikasi', $sertifikasi?->no_sertifikasi) }}" placeholder="mis. K3-LAB/2026/001" style="font-size: 0.75rem;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">
                                Tanggal Terbit
                            </label>
                            <input type="text" name="tanggal_terbit" class="form-control form-control-sm py-1 flatpickr-date" autocomplete="off" value="{{ old('tanggal_terbit', $sertifikasi?->tanggal_terbit?->format('Y-m-d')) }}" style="font-size: 0.75rem;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">
                                Tanggal Berakhir
                            </label>
                            <input type="text" name="tanggal_berakhir" class="form-control form-control-sm py-1 flatpickr-date" autocomplete="off" value="{{ old('tanggal_berakhir', $sertifikasi?->tanggal_berakhir?->format('Y-m-d')) }}" style="font-size: 0.75rem;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end align-items-center gap-2 mb-2">
                <a href="{{ route('sdm.index') }}" class="btn btn-secondary btn-sm py-1 px-3" style="font-size: 0.73rem;">Kembali</a>
                <button type="submit" class="btn btn-corporate-dark btn-sm py-1 px-3" style="font-size: 0.73rem;">
                    <i class="fas fa-save me-1"></i> Perbarui Data
                </button>
            </div>
        </form>
    </div>

    <div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-labelledby="modalTambahKategoriLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="font-size: 0.8rem;">
                <div class="modal-header bg-corporate-dark text-white py-2">
                    <h5 class="modal-title fs-6" id="modalTambahKategoriLabel">
                        <i class="fas fa-tags me-1"></i> Kelola Kategori Personil
                    </h5>
                    <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-3">
                    <form action="{{ route('sdm.kategori.store') }}" method="POST" class="mb-3">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                        <label class="form-label small fw-semibold mb-1" style="font-size: 0.75rem;">Nama Kategori Baru</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="nama_kategori" class="form-control py-1" placeholder="mis. Supervisor Lab, QC Inspector" required style="font-size: 0.75rem;">
                            <button type="submit" class="btn btn-corporate-dark px-3" style="font-size: 0.75rem;">
                                <i class="fas fa-plus me-1"></i> Tambah
                            </button>
                        </div>
                    </form>

                    <hr class="my-2">

                    <label class="form-label small fw-semibold text-muted mb-1" style="font-size: 0.75rem;">Kategori Saat Ini</label>
                    <ul class="list-group list-group-flush" style="font-size: 0.75rem;">
                        @forelse($kategoriOptions as $kode => $label)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1">
                                <span>{{ $label }}</span>
                                <form action="{{ route('sdm.kategori.destroy', $kode) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $label }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-1" title="Hapus kategori" style="font-size: 0.7rem;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item px-0 text-muted small py-1">Belum ada kategori.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm py-1 px-3" data-bs-dismiss="modal" style="font-size: 0.75rem;">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script>
        $(function () {
            $('.select2-basic').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            flatpickr.localize(flatpickr.l10ns.id);
            $('.flatpickr-date').flatpickr({
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd F Y',
                allowInput: true,
                disableMobile: true
            });
        });
    </script>
    @endpush
@endsection