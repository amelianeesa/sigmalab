@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-3 mt-1 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1"><i class="fas fa-user-gear me-2"></i>Kelola User</h4>
            <p class="text-muted mb-0">Kelola akun login seluruh pengguna sistem (semua role).</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('sdm.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                <i class="fas fa-user-plus me-1"></i> Tambah Akun
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> Data belum dapat disimpan. Periksa isian berikut.
            <ul class="mb-0 mt-2 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('kelola-user.index') }}" class="row g-2 mb-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="cari" class="form-control" value="{{ $cari }}" placeholder="Cari username, email, atau nama personil...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="role_id" class="form-select form-select-sm">
                        <option value="">Semua Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->roles_id }}" {{ (string) $roleId === (string) $role->roles_id ? 'selected' : '' }}>{{ $role->nama_role }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm flex-grow-1" type="submit">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('kelola-user.index') }}" class="btn btn-outline-danger btn-sm" title="Reset">
                        <i class="fas fa-rotate-left"></i>
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center" style="font-size: 0.85rem;">
                    <thead class="table-dark align-middle">
                        <tr>
                            <th style="width: 40px;">No.</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Personil Terkait</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $index }}</td>
                                <td class="fw-bold text-start">{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->personil->nama ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                                        {{ $user->role->nama_role ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if($user->status_aktif)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <button type="button" class="btn btn-outline-warning btn-sm" title="Edit" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $user->users_id }}">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <form action="{{ route('kelola-user.destroy', $user->users_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus permanen akun {{ $user->username }}? Tindakan ini tidak dapat dibatalkan dan akan menghapus data dari database.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalEditUser{{ $user->users_id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('kelola-user.update', $user->users_id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-dark text-white">
                                                <h5 class="modal-title fs-6"><i class="fas fa-user-edit me-2"></i>Edit Akun — {{ $user->username }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Username</label>
                                                    <input type="text" name="username" class="form-control form-control-sm" value="{{ $user->username }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Email</label>
                                                    <input type="email" name="email" class="form-control form-control-sm" value="{{ $user->email }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Password Baru <span class="text-muted fw-normal">(kosongkan jika tidak diganti)</span></label>
                                                    <input type="password" name="password" class="form-control form-control-sm" minlength="6" placeholder="••••••">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Hubungkan ke Personil</label>
                                                    <select name="personil_id" class="form-select form-select-sm">
                                                        <option value="">— Tidak terhubung —</option>
                                                        @if($user->personil)
                                                            <option value="{{ $user->personil->personil_id }}" selected>{{ $user->personil->nama }}</option>
                                                        @endif
                                                        @foreach($personilTanpaAkun as $p)
                                                            <option value="{{ $p->personil_id }}">{{ $p->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label small fw-semibold">Hak Akses (Role)</label>
                                                    <select name="role_id" class="form-select form-select-sm" required>
                                                        @foreach($roles as $role)
                                                            <option value="{{ $role->roles_id }}" {{ $user->role_id == $role->roles_id ? 'selected' : '' }}>{{ $role->nama_role }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada akun yang ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('kelola-user.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fs-6"><i class="fas fa-user-plus me-2"></i>Tambah Akun Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control form-control-sm" value="{{ old('username') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control form-control-sm" minlength="6" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Hubungkan ke Personil <span class="text-muted fw-normal">(opsional)</span></label>
                        <select name="personil_id" class="form-select form-select-sm">
                            <option value="">— Tidak terhubung —</option>
                            @foreach($personilTanpaAkun as $p)
                                <option value="{{ $p->personil_id }}" {{ old('personil_id') == $p->personil_id ? 'selected' : '' }}>{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Hak Akses (Role)</label>
                        <select name="role_id" class="form-select form-select-sm" required>
                            <option value="">— Pilih Role —</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->roles_id }}" {{ old('role_id') == $role->roles_id ? 'selected' : '' }}>{{ $role->nama_role }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
