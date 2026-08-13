@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex flex-wrap gap-3 mb-4 mt-2">
        @if(Auth::user()->hasModulAccess('manajemen_pengguna'))
        <a href="{{ route('hak-akses.index') }}" class="btn btn-warning rounded-pill px-4 shadow-sm text-dark fw-bold" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="fas fa-shield-alt me-2"></i> Manajemen Hak Akses
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
                        <select name="kategori" class="form-select form-select-sm w-100" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriOptions as $value => $label)
                                <option value="{{ $value }}" {{ $kategori === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('sdm.competency-matrix') }}" class="btn btn-outline-dark btn-sm w-100 text-truncate">
                        <i class="bi bi-grid-3x3-gap-fill me-1"></i> Competency Matrix
                    </a>
                </div>

                <div class="col-md-3 col-sm-6">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('sdm.index', array_filter(['kategori' => $kategori])) }}" class="btn btn-{{ ! $showInactive ? 'secondary' : 'outline-secondary' }} btn-sm w-50">
                            Aktif <span class="badge bg-light text-dark ms-1">{{ $jumlahPersonilAktif }}</span>
                        </a>
                        <a href="{{ route('sdm.index', array_filter(['status' => 'nonaktif', 'kategori' => $kategori])) }}" class="btn btn-{{ $showInactive ? 'secondary' : 'outline-secondary' }} btn-sm w-50">
                            Nonaktif <span class="badge bg-light text-dark ms-1">{{ $jumlahPersonilNonaktif }}</span>
                        </a>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6">
                    @if(Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value && Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value)
                    <button type="button" class="btn btn-primary btn-sm w-100 text-truncate" data-bs-toggle="modal" data-bs-target="#modalTambahPersonil">
                        <i class="fas fa-plus me-1"></i> Tambah Personil
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchPersonil" class="form-control"
                           placeholder="Cari nama, no. induk, penempatan, unit kerja, sertifikasi...">
                    <button class="btn btn-outline-secondary" type="button" id="clearSearchPersonil" title="Bersihkan pencarian">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <small class="text-muted d-block mt-1" id="searchResultInfo"></small>
            </div>

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
                            $searchHaystack = strtolower(implode(' ', array_filter([
                                $row->nama,
                                $row->no_induk,
                                $row->jabatan,
                                $row->unit_kerja,
                                $kategoriLabel,
                                $sertifikasi->jenis_sertifikasi ?? null,
                                $sertifikasi->no_sertifikasi ?? null,
                            ])));
                        @endphp
                        <tr data-search="{{ $searchHaystack }}">
                            <td>{{ $index + 1 }}</td>
                            <td><code class="fw-bold">{{ $row->no_induk }}</code></td>
                            <td class="fw-bold text-start">
                                {{ $row->nama }}
                                @if(!$row->file_cv)
                                    <br><span class="badge bg-danger mt-1" style="font-size:0.7rem;"><i class="fas fa-exclamation-circle"></i> Lengkapi CV</span>
                                @endif
                            </td>
                            <td>
                                @if($row->kategori_personil)
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
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
                                        <a href="{{ route('sdm.kompetensi.detail', $row->personil_id) }}" class="text-decoration-none fw-semibold">{{ $sertifikasi->jenis_sertifikasi }}</a>
                                        <small class="text-muted">No: {{ $sertifikasi->no_sertifikasi ?? '-' }}</small>
                                        @if($sertifikasi->file_sertifikat)
                                            <a href="{{ route('sdm.kompetensi.file', [$row->personil_id, $sertifikasi->kompetensi_personil_id]) }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-xs px-2 py-0" style="font-size: 0.75rem;">
                                                <i class="bi bi-file-earmark-text me-1"></i> Lihat Dokumen
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <a href="{{ route('sdm.kompetensi.detail', $row->personil_id) }}" class="text-decoration-none text-muted small">Belum ada — tambah?</a>
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
                                    <a href="{{ route('sdm.cv', $row->personil_id) }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm" title="Lihat CV">
                                        <i class="fas fa-file-pdf"></i> CV
                                    </a>
                                @else
                                    <span class="text-muted small">Belum ada</span>
                                @endif
                            </td>
                            <td class="text-nowrap">
                                <a href="{{ route('sdm.kompetensi.detail', $row->personil_id) }}" class="btn btn-outline-dark btn-sm" title="Training Data Record">
                                    <i class="fas fa-history"></i>
                                </a>
                                @if(Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value && Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value)
                                    @unless($row->user)
                                        <button type="button" class="btn btn-outline-dark btn-sm" title="Buat Akun Login"
                                            data-bs-toggle="modal" data-bs-target="#modalBuatAkun"
                                            data-personil-id="{{ $row->personil_id }}"
                                            data-personil-nama="{{ $row->nama }}">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    @endunless
                                    <a href="{{ route('sdm.edit', $row->personil_id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>

                                    @if($showInactive)
                                        <form action="{{ route('sdm.activate', $row->personil_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Aktifkan kembali {{ $row->nama }}?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm" title="Aktifkan kembali">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('sdm.force-destroy', $row->personil_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus permanen {{ $row->nama }}? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus permanen">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('sdm.destroy', $row->personil_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Nonaktifkan {{ $row->nama }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Nonaktifkan">
                                                <i class="fas fa-pause-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                @if($kategori)
                                    Belum ada data personil {{ $showInactive ? 'nonaktif' : 'aktif' }} pada kategori "{{ $kategoriOptions[$kategori] ?? $kategori }}".
                                @else
                                    Belum ada data personil {{ $showInactive ? 'nonaktif' : 'aktif' }} yang ditemukan.
                                @endif
                            </td>
                        </tr>
                        @endforelse

                        <tr id="noSearchResultRow" style="display:none;">
                            <td colspan="11" class="text-center text-muted py-4">
                                <i class="bi bi-search me-1"></i>
                                Tidak ada personil yang cocok dengan pencarian "<span id="noResultQuery" class="fw-semibold"></span>".
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahPersonil" tabindex="-1" aria-labelledby="modalTambahPersonilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('sdm.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6" id="modalTambahPersonilLabel"><i class="fas fa-user-plus me-2"></i>Tambah Personil & Akun</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 bg-light">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-white fw-bold"><i class="fas fa-user-tie me-1"></i> Data Induk Personil</div>
                        <div class="card-body row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control form-control-sm" value="{{ old('nama') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nomor Pegawai</label>
                                <input type="text" name="no_induk" class="form-control form-control-sm" value="{{ old('no_induk') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Kategori Personil</label>
                                <select name="kategori_personil" class="form-select form-select-sm">
                                    <option value="">— Pilih Kategori —</option>
                                    @foreach($kategoriOptions as $value => $label)
                                        <option value="{{ $value }}" {{ old('kategori_personil') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Penempatan</label>
                                <input type="text" name="jabatan" class="form-control form-control-sm" value="{{ old('jabatan') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Unit Kerja</label>
                                <input type="text" name="unit_kerja" class="form-control form-control-sm" value="{{ old('unit_kerja') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Upload CV</label>
                                <input type="file" name="file_cv" class="form-control form-control-sm" accept="image/*,application/pdf">
                                <div class="form-text text-muted" style="font-size: 0.75rem;">Format: JPG, PNG, PDF (Maks. 2MB).</div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white fw-bold"><i class="fas fa-certificate me-1"></i> Sertifikasi & Pelatihan Terakhir</div>
                        <div class="card-body row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nama Sertifikasi / Pelatihan</label>
                                <input type="text" name="nama_sertifikasi" class="form-control form-control-sm" value="{{ old('nama_sertifikasi') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nomor Sertifikat</label>
                                <input type="text" name="no_sertifikasi" class="form-control form-control-sm" value="{{ old('no_sertifikasi') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Tanggal Terbit</label>
                                <input type="date" name="tanggal_terbit" class="form-control form-control-sm" value="{{ old('tanggal_terbit', date('Y-m-d')) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Tanggal Berakhir</label>
                                <input type="date" name="tanggal_berakhir" class="form-control form-control-sm" value="{{ old('tanggal_berakhir') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> Simpan Data</button>
                </div>
            </form>
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
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <input type="password" name="password" class="form-control form-control-sm" required minlength="6">
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
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> Buat Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalBuatAkun = document.getElementById('modalBuatAkun');
        if (! modalBuatAkun) return;

        modalBuatAkun.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const personilId = button.getAttribute('data-personil-id');
            const personilNama = button.getAttribute('data-personil-nama');

            document.getElementById('formBuatAkun').action = '{{ url('/sdm') }}/' + personilId + '/akun';
            document.getElementById('modalBuatAkunNama').textContent = personilNama;
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput   = document.getElementById('searchPersonil');
        const clearBtn      = document.getElementById('clearSearchPersonil');
        const resultInfo    = document.getElementById('searchResultInfo');
        const noResultRow   = document.getElementById('noSearchResultRow');
        const noResultQuery = document.getElementById('noResultQuery');
        const rows          = document.querySelectorAll('#personilTableBody tr[data-search]');

        if (! searchInput || rows.length === 0) return; 

        let debounceTimer = null;

        function applyFilter() {
            const q = searchInput.value.trim().toLowerCase();
            let visibleCount = 0;

            rows.forEach(function (row) {
                const haystack = row.getAttribute('data-search') || '';
                const match = q === '' || haystack.includes(q);
                row.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            resultInfo.textContent = q === ''
                ? ''
                : `Menampilkan ${visibleCount} dari ${rows.length} personil`;

            if (q !== '' && visibleCount === 0) {
                noResultQuery.textContent = searchInput.value.trim();
                noResultRow.style.display = '';
            } else {
                noResultRow.style.display = 'none';
            }
        }
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(applyFilter, 150);
        });

        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            applyFilter();
            searchInput.focus();
        });

        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                searchInput.focus();
            }
        });
    });
</script>
@endsection