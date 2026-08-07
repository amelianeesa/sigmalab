@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen SDM & Kompetensi</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">SDM & Personil</li>
    </ol>

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
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div><i class="fas fa-users me-1"></i> Data Personil & Sertifikasi</div>
            <div>
                <a href="{{ route('sdm.index') }}" class="btn btn-{{ ! $showInactive ? 'secondary' : 'outline-secondary' }} btn-sm me-1">Aktif <span class="badge bg-light text-dark ms-1">{{ $jumlahPersonilAktif }}</span></a>
                <a href="{{ route('sdm.index', ['status' => 'nonaktif']) }}" class="btn btn-{{ $showInactive ? 'secondary' : 'outline-secondary' }} btn-sm me-2">Nonaktif <span class="badge bg-light text-dark ms-1">{{ $jumlahPersonilNonaktif }}</span></a>
                @if(Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value && Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value)
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPersonil">
                    <i class="fas fa-plus"></i> Tambah Personil
                </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center" style="font-size: 0.85rem;">
                    <thead class="table-dark align-middle">
                        <tr>
                            <th style="width: 40px;">No.</th>
                            <th>No. Induk</th>
                            <th>Nama Personil</th>
                            <th>Posisi / Lab</th>
                            <th>Unit Kerja</th>
                            <th>Sertifikasi Terakhir</th>
                            <th>Masa Berlaku</th>
                            <th>Status Kepatuhan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($personil as $index => $row)
                        @php
                            $sertifikasi = $row->sertifikasiTerakhir;
                            $status = $row->statusSertifikasi;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><code class="fw-bold">{{ $row->no_induk }}</code></td>
                            <td class="fw-bold text-start">{{ $row->nama }}</td>
                            <td>{{ $row->jabatan ?? '-' }}</td>
                            <td>{{ $row->unit_kerja ?? '-' }}</td>
                            <td class="text-start">
                                @if($sertifikasi)
                                    {{ $sertifikasi->jenis_sertifikasi }}<br>
                                    <small class="text-muted">No: {{ $sertifikasi->no_sertifikasi ?? '-' }}</small>
                                @else
                                    -
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
                                @if(Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_DUKUNGAN_BISNIS->value && Auth::user()->role->nama_role != \App\Enums\PeranPengguna::KABID_INSPEKSI->value)
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
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Belum ada data personil {{ $showInactive ? 'nonaktif' : 'aktif' }} yang ditemukan.</td>
                        </tr>
                        @endforelse
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
                                <label class="form-label small fw-semibold">Nomor Induk / NIK</label>
                                <input type="text" name="no_induk" class="form-control form-control-sm" value="{{ old('no_induk') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Posisi / Lab</label>
                                <input type="text" name="jabatan" class="form-control form-control-sm" value="{{ old('jabatan') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Unit Kerja</label>
                                <input type="text" name="unit_kerja" class="form-control form-control-sm" value="{{ old('unit_kerja') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Upload CV / File Pendukung (Opsional)</label>
                                <input type="file" name="file_cv" class="form-control form-control-sm" accept="image/*,application/pdf">
                                <div class="form-text text-muted" style="font-size: 0.75rem;">Format: JPG, PNG, PDF (Maks. 2MB).</div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4 border-0 shadow-sm">
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

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-sign-in-alt me-1"></i> Akun Pengguna</span>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" name="buat_akun" id="buatAkunCheck" value="1" {{ old('buat_akun') ? 'checked' : '' }} onchange="document.getElementById('formAkun').style.display = this.checked ? 'flex' : 'none'">
                                <label class="form-check-label text-dark small ms-1" for="buatAkunCheck">Buat Akun Login</label>
                            </div>
                        </div>
                        <div class="card-body row g-3" id="formAkun" style="display: {{ old('buat_akun') ? 'flex' : 'none' }};">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Username</label>
                                <input type="text" name="username" class="form-control form-control-sm" value="{{ old('username') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Password</label>
                                <input type="password" name="password" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Hak Akses / Role</label>
                                <select name="role_id" class="form-select form-select-sm">
                                    <option value="">— Pilih Role —</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->roles_id }}" {{ old('role_id') == $role->roles_id ? 'selected' : '' }}>
                                            {{ $role->nama_role }}
                                        </option>
                                    @endforeach
                                </select>
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
@endsection
