@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        .table thead, 
        .table thead tr, 
        .table thead th {
            background-color: #1b3152 !important;
            color: #ffffff !important;
            border-color: #ffffff !important;
            vertical-align: middle !important;
            text-align: center !important;
            padding: 8px 6px;
            font-size: 0.72rem;
            white-space: nowrap;
        }
        .table tbody td {
            vertical-align: middle;
            padding: 8px 6px;
            border-color: #dee2e6 !important;
            font-size: 0.75rem;
        }
        .table {
            border-color: #dee2e6 !important;
        }
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
        .table-action-btn {
            font-size: 0.7rem !important;
            padding: 0.2rem 0.4rem !important;
        }
        .alert-dismissible .btn-close {
            padding: 0;
            top: 50%;
            transform: translateY(-50%);
            right: 0.75rem;
        }
        .pagination .page-link {
            font-size: 0.72rem;
            padding: 0.2rem 0.55rem;
        }

        /* Styling Dropdown agar lengkung & hover persis seperti gambar 1 */
        .dropdown-menu {
            border-radius: 0.5rem !important;
            padding: 0.35rem !important;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1) !important;
            border: 1px solid rgba(0,0,0,0.08) !important;
        }
        .dropdown-menu .dropdown-item {
            border-radius: 0.35rem !important;
            padding: 0.35rem 0.65rem !important;
            font-size: 0.72rem !important;
            transition: background-color 0.15s ease, color 0.15s ease;
        }
        
        /* Hover untuk item biasa / Buat Akun -> Biru tua transparan */
        .dropdown-menu .dropdown-item:hover, 
        .dropdown-menu .dropdown-item:focus {
            background-color: rgba(27, 49, 82, 0.08) !important;
            color: #1b3152 !important;
        }
        .dropdown-menu .dropdown-item:hover i, 
        .dropdown-menu .dropdown-item:focus i {
            color: #1b3152 !important;
        }

        /* Hover khusus untuk Edit Personil -> Kuning transparan */
        .dropdown-menu .dropdown-item.text-warning:hover,
        .dropdown-menu .dropdown-item.text-warning:focus {
            background-color: rgba(255, 193, 7, 0.12) !important;
            color: #b38600 !important;
        }
        .dropdown-menu .dropdown-item.text-warning:hover i,
        .dropdown-menu .dropdown-item.text-warning:focus i {
            color: #ffc107 !important;
        }

        /* Hover untuk item berbahaya (Hapus / Nonaktifkan) -> Merah transparan lembut */
        .dropdown-menu .dropdown-item.text-danger:hover,
        .dropdown-menu .dropdown-item.text-danger:focus {
            background-color: #fdf2f2 !important;
            color: #dc3545 !important;
        }
        .dropdown-menu .dropdown-item.text-danger:hover i,
        .dropdown-menu .dropdown-item.text-danger:focus i {
            color: #dc3545 !important;
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
        <div class="d-flex flex-wrap gap-2 mb-2 mt-1">
            @if(
                Auth::user()->role &&
                in_array(Auth::user()->role->nama_role, [
                    \App\Enums\PeranPengguna::HR_OFFICER->value,
                    \App\Enums\PeranPengguna::ADMIN_APLIKASI->value
                ])
            )
                <a href="{{ route('hak-akses.index') }}" class="btn btn-warning btn-sm rounded-pill px-3 shadow-sm text-dark fw-bold"
                    style="font-size: 0.75rem; padding: 0.2rem 0.75rem; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'"
                    onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-shield-alt me-1"></i> Manajemen Hak Akses
                </a>
            @endif

            @if(
                Auth::user()->role && in_array(Auth::user()->role->nama_role, [
                    \App\Enums\PeranPengguna::HR_OFFICER->value,
                    \App\Enums\PeranPengguna::ADMIN_APLIKASI->value
                ])
            )
                <a href="{{ route('kelola-user.index') }}" class="btn btn-sm rounded-pill px-3 shadow-sm text-white fw-bold"
                    style="background-color: #1b3152; font-size: 0.75rem; padding: 0.2rem 0.75rem; transition: transform 0.2s;"
                    onmouseover="this.style.transform='translateY(-2px)'"
                    onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-user-gear me-1"></i> Kelola User
                </a>
            @endif
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm py-1.5 mb-2 pe-5 position-relative" role="alert" style="font-size: 0.75rem;">
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
            <div class="alert alert-danger alert-dismissible fade show shadow-sm py-1.5 mb-2 pe-5 position-relative" role="alert" style="font-size: 0.75rem;">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @php
            $personilWarningCount = $personil->getCollection()->filter(function ($row) {
                $label = $row->statusSertifikasi['label'] ?? '';
                return in_array($label, ['Segera Berakhir', 'Kedaluwarsa']);
            })->count();
        @endphp

        @if($personilWarningCount > 0)
            <div class="alert alert-warning alert-dismissible fade show shadow-sm py-1.5 mb-2 pe-5 position-relative" role="alert" style="font-size: 0.75rem;">
                <i class="fas fa-exclamation-triangle me-1"></i> <strong>Perhatian!</strong> Pada halaman ini terdapat
                <strong>{{ $personilWarningCount }} personil</strong> yang masa sertifikasinya sudah kedaluarsa atau akan segera
                berakhir (dalam 6 bulan ke depan).
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="mb-2">
            <h4 class="mb-2 fw-bold" style="font-size: 1.1rem;">Data Personil & Sertifikasi</h4>

            <div class="row g-2 align-items-center">
                <div class="col-md-3 col-sm-6">
                    <form method="GET" action="{{ route('sdm.index') }}" class="m-0">
                        @if($showInactive)
                            <input type="hidden" name="status" value="nonaktif">
                        @endif

                        @if($cari)
                            <input type="hidden" name="cari" value="{{ $cari }}">
                        @endif

                        <select name="kategori" id="filterKategori" class="form-select form-select-sm w-100 py-1 select2-basic" onchange="this.form.submit()" style="font-size: 0.75rem;">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriOptions as $value => $label)
                                <option value="{{ $value }}" {{ $kategori === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('sdm.competency-matrix') }}"
                        class="btn btn-outline-dark btn-sm w-100 text-truncate py-1" style="font-size: 0.75rem;">
                        <i class="bi bi-grid-3x3-gap-fill me-1"></i> Competency Matrix
                    </a>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="btn-group w-100 btn-group-sm" role="group">
                        <a href="{{ route('sdm.index', array_filter(['kategori' => $kategori, 'cari' => $cari])) }}"
                            class="btn btn-{{ !$showInactive ? 'secondary' : 'outline-secondary' }} w-50 py-1" style="font-size: 0.75rem;">
                            Aktif <span class="badge bg-light text-dark ms-1" style="font-size: 0.7rem;">{{ $jumlahPersonilAktif }}</span>
                        </a>

                        <a href="{{ route('sdm.index', array_filter(['status' => 'nonaktif', 'kategori' => $kategori, 'cari' => $cari])) }}"
                            class="btn btn-{{ $showInactive ? 'secondary' : 'outline-secondary' }} w-50 py-1" style="font-size: 0.75rem;">
                            Nonaktif <span class="badge bg-light text-dark ms-1" style="font-size: 0.7rem;">{{ $jumlahPersonilNonaktif }}</span>
                        </a>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    @if(
                        Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value &&
                        Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value
                    )
                        <button type="button" class="btn btn-corporate-dark btn-sm w-100 text-truncate py-1"
                            data-bs-toggle="modal" data-bs-target="#modalTambahPersonil" style="font-size: 0.75rem;">
                            <i class="fas fa-plus me-1"></i> Tambah Personil
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mb-2 shadow-sm border-0">
            <div class="card-body p-2">
                <form method="GET" action="{{ route('sdm.index') }}" class="mb-2">
                    @if($showInactive)
                        <input type="hidden" name="status" value="nonaktif">
                    @endif

                    @if($kategori)
                        <input type="hidden" name="kategori" value="{{ $kategori }}">
                    @endif

                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white py-1">
                            <i class="bi bi-search" style="font-size: 0.8rem;"></i>
                        </span>

                        <input type="text" name="cari" class="form-control form-control-sm py-1" value="{{ $cari }}"
                            placeholder="Cari nama, no. induk, penempatan, unit kerja, sertifikasi..." style="font-size: 0.75rem;">

                        <button class="btn btn-outline-secondary btn-sm py-1 px-3" type="submit" title="Cari" style="font-size: 0.75rem;">
                            Cari
                        </button>

                        <a href="{{ route('sdm.index', array_filter([
                            'status' => $showInactive ? 'nonaktif' : null,
                            'kategori' => $kategori
                        ])) }}"
                            class="btn btn-outline-secondary btn-sm py-1 px-2" title="Bersihkan pencarian" style="font-size: 0.75rem;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>

                    @if($cari)
                        <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">
                            Hasil pencarian untuk “{{ $cari }}”.
                        </small>
                    @endif
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th style="width: 40px;">No.</th>
                                <th style="width: 85px;">No. Induk</th>
                                <th style="width: 155px;">Nama Personil</th>
                                <th style="width: 75px;">Kategori</th>
                                <th style="width: 90px;">Penempatan</th>
                                <th style="width: 80px;">Unit Kerja</th>
                                <th style="width: 185px;">Sertifikasi Terakhir</th>
                                <th style="width: 85px;">Masa Berlaku</th>
                                <th style="width: 95px;">Status Kepatuhan</th>
                                <th style="width: 75px;">CV</th>
                                <th style="width: 65px;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="personilTableBody">
                            @forelse($personil as $index => $row)
                                @php
                                    $sertifikasi = $row->sertifikasiTerakhir;
                                    $status = $row->statusSertifikasi;
                                    $kategoriLabel = $kategoriOptions[$row->kategori_personil] ?? $row->kategori_personil ?? '';
                                @endphp

                                <tr>
                                    <td class="text-center">{{ $personil->firstItem() + $index }}</td>

                                    <td class="text-center">
                                        <code class="fw-bold text-dark" style="font-size: 0.72rem;">{{ $row->no_induk }}</code>
                                    </td>

                                    <td class="fw-bold text-start">
                                        {{ $row->nama }}

                                        @if(!$row->file_cv)
                                            <br>
                                            <span class="badge bg-danger mt-0.5" style="font-size: 0.6rem; padding: 0.1rem 0.3rem;">
                                                <i class="fas fa-exclamation-circle"></i> Lengkapi CV
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if($row->kategori_personil)
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-1 py-0.5" style="font-size: 0.65rem;">
                                                {{ $kategoriLabel }}
                                            </span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>

                                    <td class="text-center">{{ $row->jabatan ?? '-' }}</td>

                                    <td class="text-center">{{ $row->unit_kerja ?? '-' }}</td>

                                    <td class="text-start">
                                        @if($sertifikasi)
                                            <div class="d-flex flex-column align-items-start gap-0.5">
                                                <a href="{{ route('sdm.kompetensi.detail', $row->personil_id) }}"
                                                    class="text-decoration-none fw-semibold" style="font-size: 0.75rem;">
                                                    {{ $sertifikasi->jenis_sertifikasi }}
                                                </a>

                                                <small class="text-muted" style="font-size: 0.65rem;">
                                                    No: {{ $sertifikasi->no_sertifikasi ?? '-' }}
                                                </small>

                                                @if($sertifikasi->file_sertifikat)
                                                    <a href="{{ route('sdm.kompetensi.file', [
                                                        $row->personil_id,
                                                        $sertifikasi->kompetensi_personil_id
                                                    ]) }}?v={{ $sertifikasi->updated_at?->timestamp }}"
                                                        target="_blank"
                                                        rel="noopener"
                                                        class="btn btn-corporate-outline btn-sm px-1 py-0 text-nowrap"
                                                        style="font-size: 0.62rem;">
                                                        <i class="bi bi-file-earmark-text me-0.5"></i>
                                                        Lihat Dokumen
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <a href="{{ route('sdm.kompetensi.detail', $row->personil_id) }}"
                                                class="text-decoration-none text-muted small" style="font-size: 0.7rem;">
                                                Belum ada — tambah?
                                            </a>
                                        @endif
                                    </td>

                                    <td class="text-center text-nowrap">
                                        @if($sertifikasi)
                                            {{ $sertifikasi->tanggal_berakhir?->format('d/m/Y') ?? 'Tidak Terbatas' }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td class="text-center text-nowrap">
                                        <span class="badge {{ $status['class'] }} px-1.5 py-0.5" style="font-size: 0.65rem;">
                                            <i class="bi bi-{{ $status['icon'] }} me-0.5"></i>
                                            {{ $status['label'] }}
                                        </span>
                                    </td>

                                    <td class="text-center text-nowrap">
                                        @if($row->file_cv)
                                            <a href="{{ route('sdm.cv', $row->personil_id) }}?v={{ $row->updated_at?->timestamp }}"
                                                target="_blank"
                                                rel="noopener"
                                                class="btn btn-corporate-outline btn-sm px-1.5 py-0 text-nowrap d-inline-flex align-items-center"
                                                title="Lihat CV" style="font-size: 0.65rem;">
                                                <i class="bi bi-file-earmark-text me-1"></i> Lihat CV
                                            </a>
                                        @else
                                            <span class="text-muted small" style="font-size: 0.65rem;">-</span>
                                        @endif
                                    </td>

                                    <td class="text-center text-nowrap">
                                        @if(
                                            $showInactive &&
                                            Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value &&
                                            Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value
                                        )
                                            <button type="button" 
                                                class="btn btn-success btn-sm table-action-btn py-0 px-1" 
                                                title="Aktifkan Kembali"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalKonfirmasiAksi"
                                                data-action-url="{{ route('sdm.activate', $row->personil_id) }}"
                                                data-action-method="PATCH"
                                                data-action-message="Apakah Anda yakin ingin mengaktifkan kembali <strong>{{ $row->nama }}</strong>?"
                                                data-action-btn-class="btn-success"
                                                data-action-btn-text="Aktifkan">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        @else
                                            <a href="{{ route('sdm.kompetensi.detail', $row->personil_id) }}"
                                                class="btn btn-outline-dark btn-sm table-action-btn py-0 px-1"
                                                title="Riwayat" style="font-size: 0.68rem;">
                                                <i class="fas fa-history"></i>
                                            </a>
                                        @endif

                                        @if(
                                            Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value &&
                                            Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value
                                        )
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-outline-secondary btn-sm table-action-btn py-0 px-1"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false"
                                                    aria-label="Aksi administrasi" style="font-size: 0.68rem;">
                                                    <i class="fas fa-ellipsis-vertical"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    @unless($row->user)
                                                        <li>
                                                            <button type="button"
                                                                class="dropdown-item py-1"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalBuatAkun"
                                                                data-personil-id="{{ $row->personil_id }}"
                                                                data-personil-nama="{{ $row->nama }}">
                                                                <i class="fas fa-user-plus text-primary me-2"></i>
                                                                Buat Akun Login
                                                            </button>
                                                        </li>
                                                    @endunless

                                                    <li>
                                                        <a class="dropdown-item text-warning py-1"
                                                            href="{{ route('sdm.edit', $row->personil_id) }}">
                                                            <i class="fas fa-edit me-2"></i>
                                                            Edit Personil
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <hr class="dropdown-divider my-1">
                                                    </li>

                                                    @if($showInactive)
                                                        <li>
                                                            <button type="button" 
                                                                class="dropdown-item text-danger py-1"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalKonfirmasiAksi"
                                                                data-action-url="{{ route('sdm.force-destroy', $row->personil_id) }}"
                                                                data-action-method="DELETE"
                                                                data-action-message="Data personil <strong>{{ $row->nama }}</strong> ini akan dihapus secara permanen!"
                                                                data-action-btn-class="btn-danger"
                                                                data-action-btn-text="Ya, Hapus!">
                                                                <i class="fas fa-trash me-2"></i>
                                                                Hapus Permanen
                                                            </button>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <button type="button" 
                                                                class="dropdown-item text-danger py-1"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalKonfirmasiAksi"
                                                                data-action-url="{{ route('sdm.destroy', $row->personil_id) }}"
                                                                data-action-method="DELETE"
                                                                data-action-message="Data personil <strong>{{ $row->nama }}</strong> ini akan dinonaktifkan!"
                                                                data-action-btn-class="btn-danger"
                                                                data-action-btn-text="Ya, Nonaktifkan!">
                                                                <i class="fas fa-pause-circle me-2"></i>
                                                                Nonaktifkan
                                                            </button>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-3" style="font-size: 0.75rem;">
                                        @if($kategori)
                                            Belum ada data personil {{ $showInactive ? 'nonaktif' : 'aktif' }}
                                            pada kategori
                                            "{{ $kategoriOptions[$kategori] ?? $kategori }}".
                                        @else
                                            Belum ada data personil
                                            {{ $showInactive ? 'nonaktif' : 'aktif' }}
                                            yang ditemukan.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-2">
                    {{ $personil->withQueryString()->links('vendor.pagination.custom', ['size' => 'sm']) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Aksi -->
    <div class="modal fade" id="modalKonfirmasiAksi" tabindex="-1" aria-labelledby="modalKonfirmasiAksiLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
            <div class="modal-content border-0 shadow rounded-3 p-2.5" style="font-size: 0.8rem;">
                <form id="formKonfirmasiAksi" action="" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formKonfirmasiMethod" value="POST">

                    <div class="modal-body text-center py-3">
                        <div class="d-flex justify-content-center mb-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                style="width: 55px; height: 55px; border: 2px solid #ffbc6c; color: #f39c12; font-size: 26px; font-weight: 300;">
                                !
                            </div>
                        </div>

                        <h4 class="fw-bold text-dark mb-1.5" style="font-size: 1.1rem;">Apakah Anda yakin?</h4>
                        <p class="text-muted mb-3 px-2" id="modalKonfirmasiMessage" style="font-size: 0.8rem;"></p>

                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-secondary px-3 py-1.5 text-white fw-semibold" data-bs-dismiss="modal" style="background-color: #6c757d; border-radius: 4px; font-size: 0.75rem; min-width: 85px;">
                                Batal
                            </button>
                            <button type="submit" id="modalKonfirmasiBtnSubmit" class="btn px-3 py-1.5 text-white fw-semibold" style="border-radius: 4px; font-size: 0.75rem; min-width: 95px;">
                                Ya, Hapus!
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH PERSONIL -->
    <div class="modal fade"
        id="modalTambahPersonil"
        tabindex="-1"
        aria-labelledby="modalTambahPersonilLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden" style="font-size: 0.8rem; z-index: 1055;">
                <form action="{{ route('sdm.store') }}"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf

                    <div class="modal-header text-white py-1.5 px-3" style="background-color: #1b3152 !important; position: relative; z-index: 2;">
                        <h5 class="modal-title fw-bold text-white mb-0" id="modalTambahPersonilLabel" style="font-size: 0.95rem;">
                            <i class="fas fa-user-plus me-1"></i>
                            Tambah Personil & Akun
                        </h5>

                        <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                        </button>
                    </div>

                    <div class="modal-body p-3 bg-white">
                        <div class="mb-2.5">
                            <span class="fw-bold text-dark d-block mb-2 pb-1 border-bottom" style="font-size: 0.85rem;">
                                Data Induk Personil
                            </span>

                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold mb-1" style="font-size: 0.75rem;">
                                        Nama Lengkap
                                    </label>
                                    <input type="text"
                                        name="nama"
                                        class="form-control form-control-sm py-1"
                                        value="{{ old('nama') }}"
                                        required style="font-size: 0.75rem;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold mb-1" style="font-size: 0.75rem;">
                                        Nomor Pegawai
                                    </label>
                                    <input type="text"
                                        name="no_induk"
                                        class="form-control form-control-sm py-1"
                                        value="{{ old('no_induk') }}"
                                        required style="font-size: 0.75rem;">
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label small fw-semibold mb-0" style="font-size: 0.75rem;">
                                            Kategori Personil
                                        </label>
                                        <button type="button"
                                            class="btn btn-link btn-sm p-0"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalTambahKategori"
                                            style="font-size: 0.7rem;">
                                            <i class="fas fa-plus-circle"></i> Kategori Baru
                                        </button>
                                    </div>
                                    <select name="kategori_personil"
                                        id="kategoriPersonilTambah"
                                        class="form-select form-select-sm py-1 select2-in-modal" style="font-size: 0.75rem;">
                                        <option value="">— Pilih Kategori —</option>
                                        @foreach($kategoriOptions as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ old('kategori_personil') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold mb-1" style="font-size: 0.75rem;">
                                        Penempatan
                                    </label>
                                    <input type="text"
                                        name="jabatan"
                                        class="form-control form-control-sm py-1"
                                        value="{{ old('jabatan') }}"
                                        required style="font-size: 0.75rem;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold mb-1" style="font-size: 0.75rem;">
                                        Unit Kerja
                                    </label>
                                    <input type="text"
                                        name="unit_kerja"
                                        class="form-control form-control-sm py-1"
                                        value="{{ old('unit_kerja') }}"
                                        required style="font-size: 0.75rem;">
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label small fw-semibold mb-0" style="font-size: 0.75rem;">
                                            Upload CV
                                        </label>
                                        <a href="{{ asset('templates/' . rawurlencode('Template_CV_PT SUCOFINDO.docx')) }}"
                                            download
                                            class="small text-decoration-none" style="font-size: 0.7rem;">
                                            <i class="fas fa-download me-1"></i> Unduh Template CV
                                        </a>
                                    </div>
                                    <input type="file"
                                        name="file_cv"
                                        class="form-control form-control-sm py-1"
                                        accept="image/*,application/pdf" style="font-size: 0.75rem;">
                                    <div class="form-text text-muted mt-0.5" style="font-size: 0.65rem;">
                                        Format: JPG, PNG, PDF (Maks. 2MB).
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <span class="fw-bold text-dark d-block mb-2 pb-1 border-bottom" style="font-size: 0.85rem;">
                                Sertifikasi & Pelatihan Terakhir
                            </span>

                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold mb-1" style="font-size: 0.75rem;">
                                        Nama Sertifikasi / Pelatihan
                                    </label>
                                    <input type="text"
                                        name="nama_sertifikasi"
                                        class="form-control form-control-sm py-1"
                                        value="{{ old('nama_sertifikasi') }}" style="font-size: 0.75rem;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold mb-1" style="font-size: 0.75rem;">
                                        Nomor Sertifikat
                                    </label>
                                    <input type="text"
                                        name="no_sertifikasi"
                                        class="form-control form-control-sm py-1"
                                        value="{{ old('no_sertifikasi') }}" style="font-size: 0.75rem;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold mb-1" style="font-size: 0.75rem;">
                                        Tanggal Terbit
                                    </label>
                                    <input type="text"
                                        name="tanggal_terbit"
                                        class="form-control form-control-sm py-1 flatpickr-date"
                                        value="{{ old('tanggal_terbit', date('Y-m-d')) }}"
                                        autocomplete="off" style="font-size: 0.75rem;">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold mb-1" style="font-size: 0.75rem;">
                                        Tanggal Berakhir
                                    </label>
                                    <input type="text"
                                        name="tanggal_berakhir"
                                        class="form-control form-control-sm py-1 flatpickr-date"
                                        value="{{ old('tanggal_berakhir') }}"
                                        autocomplete="off" style="font-size: 0.75rem;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light py-1.5 px-3">
                        <button type="button"
                            class="btn btn-secondary btn-sm py-1 px-3"
                            data-bs-dismiss="modal" style="font-size: 0.75rem;">
                            Batal
                        </button>

                        <button type="submit"
                            class="btn btn-corporate-dark btn-sm py-1 px-3" style="font-size: 0.75rem;">
                            <i class="fas fa-save me-1"></i>
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade"
        id="modalTambahKategori"
        tabindex="-1"
        aria-labelledby="modalTambahKategoriLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden" style="font-size: 0.8rem;">
                <div class="modal-header text-white py-1.5 px-3" style="background-color: #1b3152 !important;">
                    <h5 class="modal-title fw-bold text-white mb-0" id="modalTambahKategoriLabel" style="font-size: 0.95rem;">
                        <i class="fas fa-tags me-1"></i>
                        Kelola Kategori Personil
                    </h5>

                    <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>

                <div class="modal-body p-3">
                    <form action="{{ route('sdm.kategori.store') }}"
                        method="POST"
                        class="mb-2">

                        @csrf

                        <input type="hidden"
                            name="redirect_to"
                            value="{{ url()->current() }}">

                        <label class="form-label small fw-semibold mb-1" style="font-size: 0.75rem;">
                            Nama Kategori Baru
                        </label>

                        <div class="input-group input-group-sm">
                            <input type="text"
                                name="nama_kategori"
                                class="form-control py-1"
                                placeholder="mis. Supervisor Lab, QC Inspector"
                                required style="font-size: 0.75rem;">

                            <button type="submit"
                                class="btn btn-corporate-dark px-3" style="font-size: 0.75rem;">
                                <i class="fas fa-plus me-1"></i>
                                Tambah
                            </button>
                        </div>
                    </form>

                    <hr class="my-2">

                    <label class="form-label small fw-semibold text-muted mb-1" style="font-size: 0.75rem;">
                        Kategori Saat Ini
                    </label>

                    <ul class="list-group list-group-flush" style="font-size: 0.75rem;">
                        @forelse($kategoriOptions as $kode => $label)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1">
                                <span>{{ $label }}</span>

                                <form action="{{ route('sdm.kategori.destroy', $kode) }}"
                                    method="POST"
                                    onsubmit="return confirm('Hapus kategori {{ $label }}?')">

                                    @csrf
                                    @method('DELETE')

                                    <input type="hidden"
                                        name="redirect_to"
                                        value="{{ url()->current() }}">

                                    <button type="submit"
                                        class="btn btn-sm btn-outline-danger py-0 px-1"
                                        title="Hapus kategori" style="font-size: 0.7rem;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item px-0 text-muted small py-1" style="font-size: 0.75rem;">
                                Belum ada kategori.
                            </li>
                        @endforelse
                    </ul>
                </div>

                <div class="modal-footer bg-light py-1.5 px-3">
                    <button type="button"
                        class="btn btn-secondary btn-sm py-1 px-3"
                        data-bs-dismiss="modal" style="font-size: 0.75rem;">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade"
        id="modalBuatAkun"
        tabindex="-1"
        aria-labelledby="modalBuatAkunLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden" style="font-size: 0.8rem;">
                <form id="formBuatAkun"
                    action=""
                    method="POST">

                    @csrf

                    <div class="modal-header text-white py-1.5 px-3" style="background-color: #1b3152 !important;">
                        <h5 class="modal-title fw-bold text-white mb-0" id="modalBuatAkunLabel" style="font-size: 0.95rem;">
                            <i class="fas fa-user-plus me-1"></i>
                            Buat Akun Login —
                            <span id="modalBuatAkunNama"></span>
                        </h5>

                        <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                        </button>
                    </div>

                    <div class="modal-body p-3">
                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 0.75rem;">
                                Username
                            </label>
                            <input type="text"
                                name="username"
                                class="form-control form-control-sm py-1"
                                required style="font-size: 0.75rem;">
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 0.75rem;">
                                Email
                            </label>
                            <input type="email"
                                name="email"
                                class="form-control form-control-sm py-1"
                                required style="font-size: 0.75rem;">
                            <div class="form-text text-muted mt-0.5" style="font-size: 0.68rem;">
                                Password sementara akan otomatis dibuat sistem dan dikirim ke email ini.
                            </div>
                        </div>

                        <div class="mb-1">
                            <label class="form-label small fw-semibold mb-1" style="font-size: 0.75rem;">
                                Hak Akses
                            </label>
                            <select name="role_id"
                                class="form-select form-select-sm py-1 select2-in-modal"
                                required style="font-size: 0.75rem;">
                                <option value="">— Pilih Role —</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->roles_id }}">
                                        {{ $role->nama_role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer bg-light py-1.5 px-3">
                        <button type="button"
                            class="btn btn-secondary btn-sm py-1 px-3"
                            data-bs-dismiss="modal" style="font-size: 0.75rem;">
                            Batal
                        </button>

                        <button type="submit"
                            class="btn btn-corporate-dark btn-sm py-1 px-3" style="font-size: 0.75rem;">
                            <i class="fas fa-save me-1"></i>
                            Buat Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalBuatAkun = document.getElementById('modalBuatAkun');
            if (modalBuatAkun) {
                modalBuatAkun.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const personilId = button.getAttribute('data-personil-id');
                    const personilNama = button.getAttribute('data-personil-nama');

                    document.getElementById('formBuatAkun').action =
                        '{{ url('/sdm') }}/' + personilId + '/akun';

                    document.getElementById('modalBuatAkunNama').textContent =
                        personilNama;
                });
            }

            const modalKonfirmasiAksi = document.getElementById('modalKonfirmasiAksi');
            if (modalKonfirmasiAksi) {
                modalKonfirmasiAksi.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const actionUrl = button.getAttribute('data-action-url');
                    const actionMethod = button.getAttribute('data-action-method');
                    const actionMessage = button.getAttribute('data-action-message');
                    const btnClass = button.getAttribute('data-action-btn-class');
                    const btnText = button.getAttribute('data-action-btn-text');

                    document.getElementById('formKonfirmasiAksi').action = actionUrl;
                    document.getElementById('formKonfirmasiMethod').value = actionMethod;
                    document.getElementById('modalKonfirmasiMessage').innerHTML = actionMessage;
                    
                    const submitBtn = document.getElementById('modalKonfirmasiBtnSubmit');
                    submitBtn.className = 'btn px-3 py-1.5 text-white fw-semibold ' + btnClass;
                    submitBtn.style.borderRadius = '4px';
                    submitBtn.style.fontSize = '0.75rem';
                    submitBtn.textContent = btnText;
                });
            }
        });
    </script>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <script>
        $(function () {
            // Dropdown filter kategori di luar modal
            $('#filterKategori').select2({
                theme: 'bootstrap-5',
                width: '100%',
                minimumResultsForSearch: -1
            });

            // Dropdown di dalam modal butuh dropdownParent supaya tidak tersembunyi di belakang modal
            $('.select2-in-modal').each(function () {
                const $modal = $(this).closest('.modal');
                $(this).select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownParent: $modal.length ? $modal : $(document.body)
                });
            });

            // Input tanggal jadi kalender custom, ramah disentuh di HP
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