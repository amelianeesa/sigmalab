@extends('layouts.app')

@section('content')
<style>
    .btn-corporate-outline {
        color: #1b3152;
        border-color: #1b3152;
        background-color: transparent;
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
    .pagination .page-link {
        font-size: 0.72rem;
        padding: 0.2rem 0.55rem;
    }
</style>

<div class="container-fluid px-3 pt-1 pb-2" style="font-size: 0.78rem;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2 px-1">
        <div>
            <h4 class="fw-bold mb-0">Kelola User</h4>
            <p class="text-muted mb-0" style="font-size: 0.72rem;">Kelola akun login seluruh pengguna sistem (semua role).</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('sdm.index') }}" class="btn btn-outline-secondary btn-sm px-2.5 py-1 fw-semibold rounded-pill" style="font-size: 0.73rem;">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <button type="button" class="btn btn-dark btn-sm px-2.5 py-1 fw-semibold rounded-pill" style="font-size: 0.73rem;" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                <i class="fas fa-user-plus me-1"></i> Tambah Akun
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm py-1.5 mb-2 pe-5 position-relative" role="alert" style="font-size: 0.73rem;">
            <i class="fas fa-exclamation-triangle me-1"></i> Data belum dapat disimpan. Periksa isian berikut.
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mb-2 px-1">
        <form method="GET" action="{{ route('kelola-user.index') }}" class="row g-2 mb-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white" style="font-size: 0.73rem;"><i class="bi bi-search"></i></span>
                    <input type="text" name="cari" class="form-control form-control-sm py-1" style="font-size: 0.73rem;" value="{{ $cari }}" placeholder="Cari username, email, atau nama personil...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="role_id" class="form-select form-select-sm py-1" style="font-size: 0.73rem;">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->roles_id }}" {{ (string) $roleId === (string) $role->roles_id ? 'selected' : '' }}>{{ $role->nama_role }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm py-1" style="font-size: 0.73rem;">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button class="btn btn-outline-secondary btn-sm py-1 flex-grow-1 fw-semibold" type="submit" style="font-size: 0.73rem;">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
                <a href="{{ route('kelola-user.index') }}" class="btn btn-corporate-outline btn-sm py-1 px-2" title="Reset" style="font-size: 0.73rem;">
                    <i class="fas fa-rotate-left"></i>
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center mb-0 bg-white shadow-sm rounded-3">
                <thead>
                    <tr>
                        <th style="background-color: #1b3152 !important; color: #ffffff !important; padding: 10px 8px; font-size: 0.7rem; text-align: center; vertical-align: middle; width: 40px;">No.</th>
                        <th style="background-color: #1b3152 !important; color: #ffffff !important; padding: 10px 8px; font-size: 0.7rem; text-align: center; vertical-align: middle;">Username</th>
                        <th style="background-color: #1b3152 !important; color: #ffffff !important; padding: 10px 8px; font-size: 0.7rem; text-align: center; vertical-align: middle;">Email</th>
                        <th style="background-color: #1b3152 !important; color: #ffffff !important; padding: 10px 8px; font-size: 0.7rem; text-align: center; vertical-align: middle;">Personil Terkait</th>
                        <th style="background-color: #1b3152 !important; color: #ffffff !important; padding: 10px 8px; font-size: 0.7rem; text-align: center; vertical-align: middle;">Role</th>
                        <th style="background-color: #1b3152 !important; color: #ffffff !important; padding: 10px 8px; font-size: 0.7rem; text-align: center; vertical-align: middle; width: 85px;">Status</th>
                        <th style="background-color: #1b3152 !important; color: #ffffff !important; padding: 10px 8px; font-size: 0.7rem; text-align: center; vertical-align: middle; width: 85px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td style="font-size: 0.73rem; padding: 10px 8px; vertical-align: middle;">{{ $users->firstItem() + $index }}</td>
                            <td class="fw-bold text-start" style="font-size: 0.73rem; padding: 10px 8px; vertical-align: middle;">{{ $user->username }}</td>
                            <td style="font-size: 0.73rem; padding: 10px 8px; vertical-align: middle;">{{ $user->email }}</td>
                            <td style="font-size: 0.73rem; padding: 10px 8px; vertical-align: middle;">{{ $user->personil->nama ?? '-' }}</td>
                            <td style="font-size: 0.73rem; padding: 10px 8px; vertical-align: middle;">
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-1.5 py-1 text-nowrap" style="font-size: 0.65rem;">
                                    {{ $user->role->nama_role ?? '-' }}
                                </span>
                            </td>
                            <td style="font-size: 0.73rem; padding: 10px 8px; vertical-align: middle;">
                                @if($user->status_aktif)
                                    <span class="badge bg-success px-2 py-1.5 text-nowrap" style="font-size: 0.65rem;">Aktif</span>
                                @else
                                    <span class="badge bg-danger px-2 py-1.5 text-nowrap" style="font-size: 0.65rem;">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-nowrap" style="font-size: 0.73rem; padding: 10px 8px; vertical-align: middle;">
                                <button type="button" class="btn btn-warning btn-sm py-1 px-2 me-1" title="Edit" style="font-size: 0.7rem;" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $user->users_id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm py-1 px-2" title="Hapus" style="font-size: 0.7rem;" data-bs-toggle="modal" data-bs-target="#modalHapusUser{{ $user->users_id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalHapusUser{{ $user->users_id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                                <div class="modal-content border-0 shadow-lg rounded-3 text-center px-4 py-3" style="font-size: 0.78rem;">
                                    <div class="pt-3 pb-2">
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px; border: 3px solid #ffcc80; color: #f0ad4e; font-size: 30px;">
                                            <i class="fas fa-exclamation"></i>
                                        </div>
                                    </div>
                                    <div class="modal-body px-0 py-2">
                                        <h4 class="fw-bold text-dark mb-2" style="font-size: 1.15rem;">Apakah Anda yakin?</h4>
                                        <p class="text-muted mb-0" style="font-size: 0.75rem;">Hapus permanen akun <strong>{{ $user->username }}</strong>? Tindakan ini tidak dapat dibatalkan!</p>
                                    </div>
                                    <div class="modal-footer border-0 justify-content-center gap-2 pt-2 pb-2">
                                        <form action="{{ route('kelola-user.destroy', $user->users_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm py-1.5 px-4 rounded-2 fw-semibold text-white" style="font-size: 0.75rem; background-color: #d9534f;">Ya, Hapus!</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary btn-sm py-1.5 px-4 rounded-2 fw-semibold text-white" data-bs-dismiss="modal" style="font-size: 0.75rem; background-color: #6c757d;">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modalEditUser{{ $user->users_id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden" style="font-size: 0.78rem;">
                                    <form action="{{ route('kelola-user.update', $user->users_id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header text-white py-1.5 px-3" style="background-color: #1b3152 !important;">
                                            <h5 class="modal-title fw-bold text-white mb-0" id="modalEditUserLabel" style="font-size: 0.9rem;"><i class="fas fa-user-edit me-1"></i>Edit Akun — {{ $user->username }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-3">
                                            <div class="mb-2">
                                                <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Username</label>
                                                <input type="text" name="username" class="form-control form-control-sm py-1" value="{{ $user->username }}" required style="font-size: 0.73rem;">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Email</label>
                                                <input type="email" name="email" class="form-control form-control-sm py-1" value="{{ $user->email }}" required style="font-size: 0.73rem;">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Password Baru <span class="text-muted fw-normal">(kosongkan jika tidak diganti)</span></label>
                                                <input type="password" name="password" class="form-control form-control-sm py-1" minlength="6" placeholder="••••••" style="font-size: 0.73rem;">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Hubungkan ke Personil</label>
                                                <select name="personil_id" class="form-select form-select-sm py-1" style="font-size: 0.73rem;">
                                                    <option value="">— Tidak terhubung —</option>
                                                    @if($user->personil)
                                                        <option value="{{ $user->personil->personil_id }}" selected>{{ $user->personil->nama }}</option>
                                                    @endif
                                                    @foreach($personilTanpaAkun as $p)
                                                        <option value="{{ $p->personil_id }}">{{ $p->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-1">
                                                <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Hak Akses (Role)</label>
                                                <select name="role_id" class="form-select form-select-sm py-1" required style="font-size: 0.73rem;">
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->roles_id }}" {{ $user->role_id == $role->roles_id ? 'selected' : '' }}>{{ $role->nama_role }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light py-1.5 px-3">
                                            <button type="button" class="btn btn-secondary btn-sm py-1 px-3" data-bs-dismiss="modal" style="font-size: 0.73rem;">Batal</button>
                                            <button type="submit" class="btn btn-sm py-1 px-3 text-white" style="background-color: #1b3152; font-size: 0.73rem;"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4" style="font-size: 0.73rem;">Belum ada akun yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $users->withQueryString()->links('vendor.pagination.custom', ['size' => 'sm']) }}
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden" style="font-size: 0.78rem;">
            <form action="{{ route('kelola-user.store') }}" method="POST">
                @csrf
                <div class="modal-header text-white py-1.5 px-3" style="background-color: #1b3152 !important;">
                    <h5 class="modal-title fw-bold text-white mb-0" id="modalTambahUserLabel" style="font-size: 0.9rem;"><i class="fas fa-user-plus me-1"></i>Tambah Akun Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Username</label>
                        <input type="text" name="username" class="form-control form-control-sm py-1" value="{{ old('username') }}" required style="font-size: 0.73rem;">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Email</label>
                        <input type="email" name="email" class="form-control form-control-sm py-1" value="{{ old('email') }}" required style="font-size: 0.73rem;">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Password</label>
                        <input type="password" name="password" class="form-control form-control-sm py-1" minlength="6" required style="font-size: 0.73rem;">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Hubungkan ke Personil <span class="text-muted fw-normal">(opsional)</span></label>
                        <select name="personil_id" class="form-select form-select-sm py-1" style="font-size: 0.73rem;">
                            <option value="">— Tidak terhubung —</option>
                            @foreach($personilTanpaAkun as $p)
                                <option value="{{ $p->personil_id }}" {{ old('personil_id') == $p->personil_id ? 'selected' : '' }}>{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-1">
                        <label class="form-label small fw-semibold mb-1" style="font-size: 0.73rem;">Hak Akses (Role)</label>
                        <select name="role_id" class="form-select form-select-sm py-1" required style="font-size: 0.73rem;">
                            <option value="">— Pilih Role —</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->roles_id }}" {{ old('role_id') == $role->roles_id ? 'selected' : '' }}>{{ $role->nama_role }}</option>
                            @endforeach
                        </select>
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
@endsection