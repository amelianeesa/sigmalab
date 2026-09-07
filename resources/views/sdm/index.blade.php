@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
         <div class="d-flex flex-wrap gap-3 mb-2 mt-1">
        @if(Auth::user()->hasModulAccess('manajemen_pengguna'))
        <a href="{{ route('hak-akses.index') }}" class="btn btn-warning rounded-pill px-4 shadow-sm text-dark fw-bold" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="fas fa-shield-alt me-2"></i> Manajemen Hak Akses
        </a>
        @endif
        @if(Auth::user()->role && Auth::user()->role->nama_role === \App\Enums\PeranPengguna::HR_GA_OFFICER->value)
    <a href="{{ route('kelola-user.index') }}" class="btn rounded-pill px-4 shadow-sm text-white fw-bold" style="background-color: #0d3b66; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <i class="fas fa-user-gear me-2"></i> Kelola User
    </a>
    @endif
    </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Data belum dapat disimpan. Periksa isian berikut.
                <ul class="mb-0 mt-2 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
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
            <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert" style="margin: 0 0 0.5rem 0;">
                <i class="fas fa-exclamation-triangle me-2"></i> <strong>Perhatian!</strong> Pada halaman ini terdapat
                <strong>{{ $personilWarningCount }} personil</strong> yang masa sertifikasinya sudah kedaluarsa atau akan segera
                berakhir (dalam 6 bulan ke depan).
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header py-3">
                <div class="mb-3 fw-bold text-dark fs-6">
                    <i class="fas fa-users me-1"></i> Data Personil & Sertifikasi
                </div>

                <div class="row g-2 align-items-center">
                    <div class="col-md-3 col-sm-6">
                        <form method="GET" action="{{ route('sdm.index') }}" class="m-0">
                            @if($showInactive)
                                <input type="hidden" name="status" value="nonaktif">
                            @endif
                            @if($cari)
                                <input type="hidden" name="cari" value="{{ $cari }}">
                            @endif
                            <select name="kategori" class="form-select form-select-sm w-100" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoriOptions as $value => $label)
                                    <option value="{{ $value }}" {{ $kategori === $value ? 'selected' : '' }}>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('sdm.competency-matrix') }}"
                            class="btn btn-outline-dark btn-sm w-100 text-truncate">
                            <i class="bi bi-grid-3x3-gap-fill me-1"></i> Competency Matrix
                        </a>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('sdm.index', array_filter(['kategori' => $kategori, 'cari' => $cari])) }}"
                                class="btn btn-{{ !$showInactive ? 'secondary' : 'outline-secondary' }} btn-sm w-50">
                                Aktif <span class="badge bg-light text-dark ms-1">{{ $jumlahPersonilAktif }}</span>
                            </a>
                            <a href="{{ route('sdm.index', array_filter(['status' => 'nonaktif', 'kategori' => $kategori, 'cari' => $cari])) }}"
                                class="btn btn-{{ $showInactive ? 'secondary' : 'outline-secondary' }} btn-sm w-50">
                                Nonaktif <span class="badge bg-light text-dark ms-1">{{ $jumlahPersonilNonaktif }}</span>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        @if(Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value && Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value)
                            <button type="button" class="btn btn-primary btn-sm w-100 text-truncate" data-bs-toggle="modal"
                                data-bs-target="#modalTambahPersonil">
                                <i class="fas fa-plus me-1"></i> Tambah Personil
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('sdm.index') }}" class="mb-3">
                    @if($showInactive)<input type="hidden" name="status" value="nonaktif">@endif
                    @if($kategori)<input type="hidden" name="kategori" value="{{ $kategori }}">@endif
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="cari" class="form-control" value="{{ $cari }}"
                            placeholder="Cari nama, no. induk, penempatan, unit kerja, sertifikasi...">
                        <button class="btn btn-outline-secondary" type="submit" title="Cari">
                            <i class="bi bi-search"></i>
                        </button>
                        <a href="{{ route('sdm.index', array_filter(['status' => $showInactive ? 'nonaktif' : null, 'kategori' => $kategori])) }}"
                            class="btn btn-outline-secondary" title="Bersihkan pencarian">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                    @if($cari)<small class="text-muted d-block mt-1">Hasil pencarian untuk “{{ $cari }}”.</small>@endif
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center" style="font-size: 0.85rem;">
                        <thead class="table-dark align-middle">
                            <tr>
                                <th style="width: 40px;">No.</th>
                                <th>No. Induk</th>
                                <th>Nama Personil</th>
                                <th>Kategori</th>
                                <th>Penempatan</th>
                                <th>Unit Kerja</th>
                                <th>Sertifikasi Terakhir</th>
                                <th>Masa Berlaku</th>
                                <th>Status Kepatuhan</th>
                                <th>CV</th>
                                <th>Aksi</th>
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
                                    <td>{{ $personil->firstItem() + $index }}</td>
                                    <td><code class="fw-bold">{{ $row->no_induk }}</code></td>
                                    <td class="fw-bold text-start">
                                        {{ $row->nama }}
                                        @if(!$row->file_cv)
                                            <br><span class="badge bg-danger mt-1" style="font-size:0.7rem;"><i
                                                    class="fas fa-exclamation-circle"></i> Lengkapi CV</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($row->kategori_personil)
                                            <span
                                                class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                                                {{ $kategoriLabel }}
                                            </span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $row->jabatan ?? '-' }}</td>
                                    <td>{{ $row->unit_kerja ?? '-' }}</td>
                                    <td class="text-start">
                                        @if($sertifikasi)
                                            <div class="d-flex flex-column align-items-start gap-1">
                                                <a href="{{ route('sdm.kompetensi.detail', $row->personil_id) }}"
                                                    class="text-decoration-none fw-semibold">{{ $sertifikasi->jenis_sertifikasi }}</a>
                                                <small class="text-muted">No: {{ $sertifikasi->no_sertifikasi ?? '-' }}</small>
                                                @if($sertifikasi->file_sertifikat)
                                                    <a href="{{ route('sdm.kompetensi.file', [$row->personil_id, $sertifikasi->kompetensi_personil_id]) }}?v={{ $sertifikasi->updated_at?->timestamp }}"
                                                        target="_blank" rel="noopener" class="btn btn-outline-primary btn-xs px-2 py-0"
                                                        style="font-size: 0.75rem;">
                                                        <i class="bi bi-file-earmark-text me-1"></i> Lihat Dokumen
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <a href="{{ route('sdm.kompetensi.detail', $row->personil_id) }}"
                                                class="text-decoration-none text-muted small">Belum ada — tambah?</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($sertifikasi)
                                            {{ $sertifikasi->tanggal_berakhir?->format('d/m/Y') ?? 'Tidak Terbatas' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $status['class'] }}">
                                            <i class="bi bi-{{ $status['icon'] }} me-1"></i> {{ $status['label'] }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap">
                                        @if($row->file_cv)
                                            <a href="{{ route('sdm.cv', $row->personil_id) }}?v={{ $row->updated_at?->timestamp }}"
                                                target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm"
                                                title="Lihat CV">
                                                <i class="fas fa-file-pdf"></i> CV
                                            </a>
                                        @else
                                            <span class="text-muted small">Belum ada</span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        @if($showInactive && Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value && Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value)
                                            <form action="{{ route('sdm.activate', $row->personil_id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Aktifkan kembali {{ $row->nama }}?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm"><i
                                                        class="fas fa-undo me-1"></i> Aktifkan Kembali</button>
                                            </form>
                                        @else
                                            <a href="{{ route('sdm.kompetensi.detail', $row->personil_id) }}"
                                                class="btn btn-outline-dark btn-sm" title="Training Data Record">
                                                <i class="fas fa-history me-1"></i> Riwayat
                                            </a>
                                        @endif
                                        @if(Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value && Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value)
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false" aria-label="Aksi administrasi">
                                                    <i class="fas fa-ellipsis-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    @unless($row->user)
                                                        <li>
                                                            <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#modalBuatAkun"
                                                                data-personil-id="{{ $row->personil_id }}"
                                                                data-personil-nama="{{ $row->nama }}">
                                                                <i class="fas fa-user-plus me-2"></i>Buat Akun Login
                                                            </button>
                                                        </li>
                                                    @endunless
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('sdm.edit', $row->personil_id) }}"><i
                                                                class="fas fa-edit text-warning me-2"></i>Edit Personil</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    @if($showInactive)
                                                        <li>
                                                            <form action="{{ route('sdm.force-destroy', $row->personil_id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Hapus permanen {{ $row->nama }}? Tindakan ini tidak dapat dibatalkan.')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger"><i
                                                                        class="fas fa-trash me-2"></i>Hapus Permanen</button>
                                                            </form>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <form action="{{ route('sdm.destroy', $row->personil_id) }}" method="POST"
                                                                onsubmit="return confirm('Nonaktifkan {{ $row->nama }}?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger"><i
                                                                        class="fas fa-pause-circle me-2"></i>Nonaktifkan</button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-4">
                                        @if($kategori)
                                            Belum ada data personil {{ $showInactive ? 'nonaktif' : 'aktif' }} pada kategori
                                            "{{ $kategoriOptions[$kategori] ?? $kategori }}".
                                        @else
                                            Belum ada data personil {{ $showInactive ? 'nonaktif' : 'aktif' }} yang ditemukan.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($personil->hasPages())
                    <div class="d-flex flex-column align-items-center gap-2 mt-3">
                        <small class="text-muted">Menampilkan {{ $personil->firstItem() }}–{{ $personil->lastItem() }} dari
                            {{ $personil->total() }} personil</small>
                        {{ $personil->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahPersonil" tabindex="-1" aria-labelledby="modalTambahPersonilLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="{{ route('sdm.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title fs-6" id="modalTambahPersonilLabel"><i
                                class="fas fa-user-plus me-2"></i>Tambah Personil & Akun</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4 bg-light">
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-white fw-bold"><i class="fas fa-user-tie me-1"></i> Data Induk
                                Personil</div>
                            <div class="card-body row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control form-control-sm"
                                        value="{{ old('nama') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Nomor Pegawai</label>
                                    <input type="text" name="no_induk" class="form-control form-control-sm"
                                        value="{{ old('no_induk') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label small fw-semibold mb-1">Kategori Personil</label>
                                        <button type="button" class="btn btn-link btn-sm p-0 mb-1" data-bs-toggle="modal"
                                            data-bs-target="#modalTambahKategori" style="font-size: 0.75rem;">
                                            <i class="fas fa-plus-circle"></i> Kategori Baru
                                        </button>
                                    </div>
                                    <select name="kategori_personil" class="form-select form-select-sm">
                                        <option value="">— Pilih Kategori —</option>
                                        @foreach($kategoriOptions as $value => $label)
                                            <option value="{{ $value }}" {{ old('kategori_personil') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Penempatan</label>
                                    <input type="text" name="jabatan" class="form-control form-control-sm"
                                        value="{{ old('jabatan') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Unit Kerja</label>
                                    <input type="text" name="unit_kerja" class="form-control form-control-sm"
                                        value="{{ old('unit_kerja') }}" required>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label small fw-semibold mb-0">Upload CV</label>
                                        <a href="{{ asset('templates/' . rawurlencode('Template_CV_PT SUCOFINDO.docx')) }}"
                                            download class="small text-decoration-none">
                                            <i class="fas fa-download me-1"></i>Unduh Template CV
                                        </a>
                                    </div>
                                    <input type="file" name="file_cv" class="form-control form-control-sm"
                                        accept="image/*,application/pdf">
                                    <div class="form-text text-muted" style="font-size: 0.75rem;">Format: JPG, PNG, PDF
                                        (Maks. 2MB). Isi sesuai template, lalu unggah di sini.</div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white fw-bold"><i class="fas fa-certificate me-1"></i> Sertifikasi &
                                Pelatihan Terakhir</div>
                            <div class="card-body row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Nama Sertifikasi / Pelatihan</label>
                                    <input type="text" name="nama_sertifikasi" class="form-control form-control-sm"
                                        value="{{ old('nama_sertifikasi') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Nomor Sertifikat</label>
                                    <input type="text" name="no_sertifikasi" class="form-control form-control-sm"
                                        value="{{ old('no_sertifikasi') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Tanggal Terbit</label>
                                    <input type="date" name="tanggal_terbit" class="form-control form-control-sm"
                                        value="{{ old('tanggal_terbit', date('Y-m-d')) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Tanggal Berakhir</label>
                                    <input type="date" name="tanggal_berakhir" class="form-control form-control-sm"
                                        value="{{ old('tanggal_berakhir') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-white">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> Simpan
                            Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-labelledby="modalTambahKategoriLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6" id="modalTambahKategoriLabel"><i class="fas fa-tags me-2"></i>Kelola
                        Kategori Personil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <form action="{{ route('sdm.kategori.store') }}" method="POST" class="mb-4">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                        <label class="form-label small fw-semibold">Nama Kategori Baru</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="nama_kategori" class="form-control"
                                placeholder="mis. Supervisor Lab, QC Inspector" required>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah</button>
                        </div>
                    </form>

                    <hr>

                    <label class="form-label small fw-semibold text-muted">Kategori Saat Ini</label>
                    <ul class="list-group list-group-flush">
                        @forelse($kategoriOptions as $kode => $label)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span>{{ $label }}</span>
                                <form action="{{ route('sdm.kategori.destroy', $kode) }}" method="POST"
                                    onsubmit="return confirm('Hapus kategori {{ $label }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus kategori">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item px-0 text-muted small">Belum ada kategori.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBuatAkun" tabindex="-1" aria-labelledby="modalBuatAkunLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formBuatAkun" action="" method="POST">
                    @csrf
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title fs-6" id="modalBuatAkunLabel">
                            <i class="fas fa-user-plus me-2"></i>Buat Akun Login — <span id="modalBuatAkunNama"></span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Username</label>
                            <input type="text" name="username" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control form-control-sm" required
                                minlength="6">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Hak Akses</label>
                            <select name="role_id" class="form-select form-select-sm" required>
                                <option value="">— Pilih Role —</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->roles_id }}">{{ $role->nama_role }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> Buat
                            Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalBuatAkun = document.getElementById('modalBuatAkun');
            if (!modalBuatAkun) return;

            modalBuatAkun.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const personilId = button.getAttribute('data-personil-id');
                const personilNama = button.getAttribute('data-personil-nama');

                document.getElementById('formBuatAkun').action = '{{ url('/sdm') }}/' + personilId + '/akun';
                document.getElementById('modalBuatAkunNama').textContent = personilNama;
            });
        });
    </script>
@endsection