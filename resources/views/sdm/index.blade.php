@extends('layouts.app')

@section('content')
<style>
    .sdm-page { color: #172b4d; font-size: .9rem; }
    .sdm-page h2 { font-size: 1.65rem; }
    .sdm-page h5 { font-size: 1.05rem; }
    .sdm-page .card-body { padding: 1rem !important; }
    .sdm-page .btn { font-size: .8rem; }
    .sdm-page .badge { font-size: .68rem; }
    .sdm-page .section-card { border: 1px solid #e7edf3; box-shadow: 0 8px 24px rgba(15, 35, 59, .06) !important; }
    .sdm-page .section-kicker { color: #64748b; font-size: .72rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
    .sdm-page .table-shell { border: 1px solid #e8edf3; border-radius: 14px; overflow: hidden; }
    .sdm-page .personil-table { margin: 0; }
    .sdm-page .personil-table thead th { background: #f7f9fc; border-bottom: 1px solid #dfe6ee; color: #64748b; font-size: .68rem; font-weight: 700; letter-spacing: .06em; padding: 11px 14px; text-transform: uppercase; white-space: nowrap; }
    @media (max-width: 768px) {
        .sdm-page .personil-table thead th { white-space: normal; font-size: .64rem; }
    }
    .sdm-page .personil-table tbody td { border-color: #edf1f5; padding: 11px 14px; vertical-align: middle; }
    .sdm-page .personil-table tbody tr:hover { background: #f8fbfd; }
    .sdm-page .personil-avatar { align-items: center; background: #e5f2f7; border-radius: 50%; color: #0b5875; display: inline-flex; flex: 0 0 32px; font-size: .68rem; font-weight: 700; height: 32px; justify-content: center; width: 32px; }
    .sdm-page .status-review { background: #fff1f2; border: 1px solid #fecdd3; color: #be123c; font-size: .75rem; font-weight: 700; }
    .sdm-page .status-active { background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; font-size: .75rem; font-weight: 700; }
    .sdm-page .status-warning { background: #fffbeb; border: 1px solid #fde68a; color: #b45309; font-size: .75rem; font-weight: 700; }
    .sdm-page .status-expired { background: #fff1f2; border: 1px solid #fecdd3; color: #be123c; font-size: .75rem; font-weight: 700; }
    .sdm-page .status-empty { background: #f1f5f9; border: 1px solid #d8e0ea; color: #64748b; font-size: .75rem; font-weight: 700; }
    .sdm-page .add-personil-btn { background: #0b1f36; border-color: #0b1f36; }
    .sdm-page .add-personil-btn:hover { background: #12385d; border-color: #12385d; }
    .sdm-page .action-btn { align-items: center; border-radius: 7px; display: inline-flex; font-size: .76rem; height: 28px; justify-content: center; width: 28px; }
    .sdm-page .form-select-lg { font-size: .9rem; min-height: 42px; padding-bottom: .45rem; padding-top: .45rem; }
</style>
<div class="sdm-page">
<div class="container-fluid px-3 py-3">
    

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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

    <div class="card section-card border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="section-kicker d-block mb-1">Data induk</span>
                    <h5 class="fw-bold text-dark mb-0">Profil Personil</h5>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <a href="{{ route('dashboard') }}" class="btn btn-light border shadow-sm px-3 fw-semibold rounded-pill">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="button" class="btn btn-dark add-personil-btn shadow-sm px-3 fw-semibold rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambahPersonil">
                        <i class="bi bi-person-plus me-1"></i> Tambah Personil
                    </button>
                </div>
            </div>

            <div class="d-none">
                <select class="form-select form-select-lg rounded-3 border-secondary-subtle" onchange="window.location.href = this.value ? '?personil_id=' + this.value : '{{ route('sdm.index') }}'">
                    <option value="">— Pilih personil —</option>
                    @foreach($personil as $p)
                        <option value="{{ $p->personil_id }}" {{ isset($selectedPersonil) && $selectedPersonil->personil_id == $p->personil_id ? 'selected' : '' }}>
                            {{ $p->nama }} — {{ $p->jabatan }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if($selectedPersonil)
            <div class="p-3 border rounded-4 bg-light bg-opacity-50">
                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="rounded-circle bg-dark text-white fw-bold d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; font-size: 18px;">
                        {{ strtoupper(substr($selectedPersonil->nama, 0, 2)) }}
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">{{ $selectedPersonil->nama }}</h5>
                        <p class="text-muted small mb-1">{{ $selectedPersonil->jabatan }}</p>
                        @if($selectedPersonil->file_cv)
                            <a href="{{ route('sdm.cv', $selectedPersonil->personil_id) }}" target="_blank" class="text-decoration-none small text-primary fw-semibold">
                                <i class="bi bi-file-earmark-pdf me-1"></i> CV: Lihat Dokumen/Foto
                            </a>
                        @else
                            <span class="text-muted small">CV: Belum diunggah</span>
                        @endif
                    </div>
                </div>
                <div class="mb-4 d-none">
                    <span class="text-uppercase text-secondary fs-7 fw-bold d-block mb-2" style="font-size: 11px; letter-spacing: 0.5px;">Matriks Kompetensi</span>
                    <div class="bg-white p-3 rounded-3 border d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold text-dark">Audit Mutu Internal</span>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill">Ahli</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-semibold">
                        + Tambah Kompetensi
                    </button>
                </div>

                <div class="d-none">
                    <span class="text-uppercase text-secondary fs-7 fw-bold d-block mb-2" style="font-size: 11px; letter-spacing: 0.5px;">Sertifikasi & Pelatihan</span>
                    <div class="bg-white p-3 rounded-3 border d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Pelatihan K3 Laboratorium</h6>
                            <p class="text-muted small mb-0">Terbit 01 Mei 2023 • Berlaku s.d 01 Mei 2025</p>
                        </div>
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">
                            Harus sertifikasi ulang
                        </span>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-semibold">
                        + Tambah Sertifikasi / Pelatihan
                    </button>
                </div>

                <div class="mt-3 pt-3 border-top">
                    <div class="mb-2">
                        <div>
                            <span class="section-kicker d-block mb-1">Data personil</span>
                            <h6 class="fw-bold text-dark mb-0">Sertifikasi & Kompetensi</h6>
                        </div>
                    </div>

                    @forelse($selectedPersonil->kompetensi as $kompetensi)
                        <div class="bg-white border rounded-3 px-3 py-2 mb-2">
                            <div>
                                <span class="d-block fw-semibold text-dark small">{{ $kompetensi->jenis_sertifikasi }}</span>
                                <div class="row g-1 mt-1 small text-muted">
                                    <div class="col-md-4">No. sertifikat: {{ $kompetensi->no_sertifikasi ?? 'Belum dicatat' }}</div>
                                    <div class="col-md-4">Terbit: {{ $kompetensi->tanggal_terbit?->format('d/m/Y') ?? 'Tidak ditentukan' }}</div>
                                    <div class="col-md-4">Berlaku s.d.: {{ $kompetensi->tanggal_berakhir?->format('d/m/Y') ?? 'Tidak ditentukan' }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center border rounded-3 bg-white py-3 text-muted small">
                            <i class="bi bi-award me-1"></i> Belum ada sertifikasi atau kompetensi yang tercatat.
                        </div>
                    @endforelse
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="card section-card border-0 rounded-4">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
                <div>
                    <span class="section-kicker d-block mb-1">Direktori personil</span>
                    <h5 class="fw-bold text-dark mb-1">{{ $showInactive ? 'Personil Nonaktif' : 'Daftar Personil Lab & Kalibrasi' }}</h5>
                    <p class="text-muted small mb-0">{{ $personil->count() }} personil {{ $showInactive ? 'nonaktif' : 'aktif' }} terdaftar dalam sistem.</p>
                </div>
                <div class="btn-group btn-group-sm" role="group" aria-label="Filter status personil">
                    <a href="{{ route('sdm.index') }}" class="btn {{ ! $showInactive ? 'btn-dark' : 'btn-outline-secondary' }}">Aktif <span class="ms-1">{{ $jumlahPersonilAktif }}</span></a>
                    <a href="{{ route('sdm.index', ['status' => 'nonaktif']) }}" class="btn {{ $showInactive ? 'btn-dark' : 'btn-outline-secondary' }}">Nonaktif <span class="ms-1">{{ $jumlahPersonilNonaktif }}</span></a>
                </div>
            </div>
            <div class="table-responsive table-shell">
                <table class="table personil-table align-middle">
                    <thead>
                        <tr>
                            <th>Personil</th>
                            <th>Posisi & Unit</th>
                            <th>Sertifikasi Terakhir</th>
                            <th>Status Kepatuhan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($personil as $row)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="personil-avatar">{{ strtoupper(substr($row->nama, 0, 2)) }}</span>
                                    <div>
                                        <span class="d-block fw-semibold text-dark">{{ $row->nama }}</span>
                                        <span class="small text-muted">ID: {{ $row->no_induk }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="d-block fw-semibold text-dark small">{{ $row->jabatan ?? '—' }}</span>
                                <span class="small text-muted">{{ $row->unit_kerja ?? 'Unit belum ditetapkan' }}</span>
                            </td>
                            @php($sertifikasi = $row->sertifikasiTerakhir)
                            <td>
                                @if($sertifikasi)
                                    <span class="d-block fw-semibold text-dark small">{{ $sertifikasi->jenis_sertifikasi }}</span>
                                    <span class="small text-muted">Berlaku s.d. {{ $sertifikasi->tanggal_berakhir?->format('d/m/Y') ?? 'Tidak ditentukan' }}</span>
                                @else
                                    <span class="small text-muted"><i class="bi bi-dash-circle me-1"></i> Belum ada sertifikat</span>
                                @endif
                            </td>
                            <td>
                                @php($status = $row->statusSertifikasi)
                                <span class="badge rounded-pill {{ $status['class'] }} px-3 py-2" style="font-size: 0;">
                                    <span style="font-size: .75rem;"><i class="bi bi-{{ $status['icon'] }} me-1"></i>{{ $status['label'] }}</span>
                                    • Perlu Sertifikasi Ulang
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    @if($selectedPersonil && $selectedPersonil->personil_id === $row->personil_id)
                                        <a href="{{ route('sdm.index', $showInactive ? ['status' => 'nonaktif'] : []) }}" class="btn btn-sm btn-light border text-secondary action-btn" title="Tutup profil" aria-label="Tutup profil {{ $row->nama }}">
                                            <i class="bi bi-eye-slash"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('sdm.index', $showInactive ? ['status' => 'nonaktif', 'personil_id' => $row->personil_id] : ['personil_id' => $row->personil_id]) }}" class="btn btn-sm btn-light border text-secondary action-btn" title="Lihat profil" aria-label="Lihat profil {{ $row->nama }}">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('sdm.edit', $row->personil_id) }}" class="btn btn-sm btn-light border text-primary action-btn" title="Ubah personil" aria-label="Ubah {{ $row->nama }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @if($showInactive)
                                        <form action="{{ route('sdm.activate', $row->personil_id) }}" method="POST" onsubmit="return confirm('Aktifkan kembali {{ $row->nama }}?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-light border text-success action-btn" title="Aktifkan kembali" aria-label="Aktifkan kembali {{ $row->nama }}">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('sdm.force-destroy', $row->personil_id) }}" method="POST" onsubmit="return confirm('Hapus permanen {{ $row->nama }}? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger action-btn" title="Hapus permanen" aria-label="Hapus permanen {{ $row->nama }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('sdm.destroy', $row->personil_id) }}" method="POST" onsubmit="return confirm('Hapus {{ $row->nama }} dari daftar personil? Data akan dinonaktifkan, bukan dihapus permanen.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger action-btn" title="Hapus dari daftar" aria-label="Hapus {{ $row->nama }}">
                                                <i class="bi bi-pause-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-people d-block fs-3 mb-2 text-secondary"></i>
                                Belum ada personil {{ $showInactive ? 'nonaktif' : 'aktif' }} terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="modalTambahPersonil" tabindex="-1" aria-labelledby="modalTambahPersonilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="height: min(85vh, 850px);">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden h-100">
            <form action="{{ route('sdm.store') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column h-100">
                @csrf

                <div class="modal-header bg-white px-4 py-3 border-bottom flex-shrink-0">
                    <h5 class="modal-title fw-bold text-dark" id="modalTambahPersonilLabel">Tambah Personil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4 bg-white flex-grow-1" style="min-height: 0; overflow-y: auto;">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Nama</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="mis. Budi Santoso" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Nomor Induk</label>
                        <input type="text" name="no_induk" class="form-control" value="{{ old('no_induk') }}" placeholder="mis. 2026001" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Posisi / Lab</label>
                        <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}" placeholder="mis. Analis Lab Kimia" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Unit Kerja</label>
                        <input type="text" name="unit_kerja" class="form-control" value="{{ old('unit_kerja') }}" placeholder="mis. Laboratorium Kimia" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Upload CV / Foto Dokumen</label>
                        <input type="file" name="file_cv" class="form-control" accept="image/*,application/pdf">
                        <div class="form-text text-muted" style="font-size: 11px;">Format yang didukung: JPG, PNG, atau PDF (Maks. 2MB).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Nama Sertifikasi / Pelatihan</label>
                        <input type="text" name="nama_sertifikasi" class="form-control" value="{{ old('nama_sertifikasi') }}" placeholder="mis. Pelatihan K3 Laboratorium">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Nomor Sertifikat</label>
                        <input type="text" name="no_sertifikasi" class="form-control" value="{{ old('no_sertifikasi') }}" placeholder="mis. K3-LAB/2026/001">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary small fw-semibold">Tanggal Terbit</label>
                            <input type="date" name="tanggal_terbit" class="form-control" value="{{ old('tanggal_terbit', date('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-secondary small fw-semibold">Tanggal Berakhir</label>
                            <input type="date" name="tanggal_berakhir" class="form-control" value="{{ old('tanggal_berakhir') }}">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-white px-4 py-3 border-top flex-shrink-0 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light border px-4 text-secondary fw-semibold rounded-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark px-4 fw-semibold rounded-2" style="background-color: #0b1f36;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection