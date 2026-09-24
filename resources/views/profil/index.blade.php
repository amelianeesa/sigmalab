@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">

        @if(session('force_password') || Auth::user()->must_change_password)
            <div class="alert alert-warning alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>
                    <strong>Wajib Ganti Password.</strong>
                    Anda sedang menggunakan password sementara. Silakan buat password baru sebelum melanjutkan.
                </div>
            </div>
        @endif



        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="mb-3">
            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-user-circle me-2 text-primary"></i>Profil Saya</h5>
            <small class="text-muted">Kelola informasi akun dan keamanan login Anda.</small>
        </div>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-0">
                        <div class="d-flex flex-column align-items-center text-center py-4"
                            style="background: linear-gradient(135deg, #1d4c7a 0%, #163d63 100%); border-radius: 0.375rem 0.375rem 0 0;">
                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-white text-primary fw-bold mb-2"
                                style="width: 72px; height: 72px; font-size: 1.6rem;">
                                {{ strtoupper(substr($user->personil->nama ?? $user->username, 0, 1)) }}
                            </div>
                            <div class="fw-bold text-white fs-6">
                                {{ $user->personil->nama ?? $user->username }}
                            </div>
                            <span class="badge bg-white text-primary mt-1 px-3 py-1 rounded-pill" style="font-size: 0.72rem;">
                                {{ $user->role->nama_role ?? '-' }}
                            </span>
                        </div>

                        <div class="px-4 py-3">
                            <div class="d-flex align-items-center py-2 border-bottom">
                                <i class="fas fa-user text-muted me-3" style="width: 18px;"></i>
                                <div>
                                    <div class="text-muted" style="font-size: 0.72rem;">Username</div>
                                    <div class="fw-semibold" style="font-size: 0.9rem;">{{ $user->username }}</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center py-2 border-bottom">
                                <i class="fas fa-envelope text-muted me-3" style="width: 18px;"></i>
                                <div>
                                    <div class="text-muted" style="font-size: 0.72rem;">Email</div>
                                    <div class="fw-semibold" style="font-size: 0.9rem;">{{ $user->email }}</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center py-2 {{ $user->personil ? 'border-bottom' : '' }}">
                                <i class="fas fa-shield-alt text-muted me-3" style="width: 18px;"></i>
                                <div>
                                    <div class="text-muted" style="font-size: 0.72rem;">Hak Akses</div>
                                    <div class="fw-semibold" style="font-size: 0.9rem;">{{ $user->role->nama_role ?? '-' }}</div>
                                </div>
                            </div>

                            @if($user->personil)
                                <div class="d-flex align-items-center py-2 border-bottom">
                                    <i class="fas fa-address-card text-muted me-3" style="width: 18px;"></i>
                                    <div>
                                        <div class="text-muted" style="font-size: 0.72rem;">Nomor Pegawai</div>
                                        <div class="fw-semibold" style="font-size: 0.9rem;">{{ $user->personil->no_induk ?? '-' }}</div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center py-2 border-bottom">
                                    <i class="fas fa-id-badge text-muted me-3" style="width: 18px;"></i>
                                    <div>
                                        <div class="text-muted" style="font-size: 0.72rem;">Penempatan</div>
                                        <div class="fw-semibold" style="font-size: 0.9rem;">{{ $user->personil->jabatan ?? '-' }}</div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center py-2">
                                    <i class="fas fa-building text-muted me-3" style="width: 18px;"></i>
                                    <div>
                                        <div class="text-muted" style="font-size: 0.72rem;">Unit Kerja</div>
                                        <div class="fw-semibold" style="font-size: 0.9rem;">{{ $user->personil->unit_kerja ?? '-' }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-dark text-white d-flex align-items-center py-3">
                        <i class="fas fa-key me-2"></i>
                        <span class="fw-bold fs-6">Ganti Password</span>
                    </div>

                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">
                            Gunakan minimal 6 karakter. Kombinasikan huruf dan angka agar lebih aman.
                        </p>

                        <form action="{{ route('profil.password.update') }}" method="POST" id="formGantiPassword">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Password Lama</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password"
                                        name="password_lama"
                                        class="form-control @error('password_lama') is-invalid @enderror"
                                        id="passwordLama"
                                        required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="passwordLama">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @error('password_lama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Password Baru</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white"><i class="fas fa-key text-muted"></i></span>
                                    <input type="password"
                                        name="password_baru"
                                        class="form-control @error('password_baru') is-invalid @enderror"
                                        id="passwordBaru"
                                        minlength="6"
                                        required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="passwordBaru">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @error('password_baru')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-semibold">Konfirmasi Password Baru</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white"><i class="fas fa-key text-muted"></i></span>
                                    <input type="password"
                                        name="password_baru_confirmation"
                                        class="form-control"
                                        id="passwordBaruConfirmation"
                                        minlength="6"
                                        required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="passwordBaruConfirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-sm px-4">
                                    <i class="fas fa-save me-1"></i> Simpan Password Baru
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const targetInput = document.getElementById(btn.dataset.target);
                const icon = btn.querySelector('i');
                if (targetInput.type === 'password') {
                    targetInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    targetInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    </script>
@endsection