@extends('layouts.app')

@section('content')
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
        .profil-header {
            background: linear-gradient(135deg, #1b3152 0%, #14253e 100%);
            border-radius: 0.375rem 0.375rem 0 0;
        }
        .profil-avatar {
            width: 56px;
            height: 56px;
            font-size: 1.3rem;
            color: #1b3152;
        }
        .profil-info-label {
            font-size: 0.68rem;
            color: #6c757d;
        }
        .profil-info-value {
            font-size: 0.78rem;
            font-weight: 600;
            color: #212529;
        }
        .profil-info-icon {
            width: 18px;
            font-size: 0.78rem;
            color: #6c757d;
        }
        .form-control:focus {
            border-color: #1b3152;
            box-shadow: 0 0 0 0.15rem rgba(27, 49, 82, 0.15);
        }
        .alert-dismissible .btn-close {
            padding: 0;
            top: 50%;
            transform: translateY(-50%);
            right: 0.75rem;
        }
    </style>

    <div class="container-fluid px-3 pt-1 pb-2" style="font-size: 0.8rem;">

        @if(session('force_password') || Auth::user()->must_change_password)
            <div class="alert alert-warning alert-dismissible fade show shadow-sm py-1.5 mb-2 pe-5 position-relative" role="alert" style="font-size: 0.75rem;">
                <i class="fas fa-exclamation-triangle me-1"></i>
                <strong>Wajib Ganti Password.</strong>
                Anda sedang menggunakan password sementara. Silakan buat password baru sebelum melanjutkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm py-1.5 mb-2 pe-5 position-relative" role="alert" style="font-size: 0.75rem;">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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

        <div class="mb-2 mt-1">
            <h4 class="mb-0 fw-bold" style="font-size: 1.1rem;">
                <i class="fas fa-user-circle me-1" style="color: #1b3152;"></i> Profil Saya
            </h4>
            <small class="text-muted" style="font-size: 0.72rem;">Kelola informasi akun dan keamanan login Anda.</small>
        </div>

        <div class="row g-2">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-0">
                        <div class="profil-header d-flex flex-column align-items-center text-center py-3">
                            <div class="profil-avatar d-flex align-items-center justify-content-center rounded-circle bg-white fw-bold mb-2">
                                {{ strtoupper(substr($user->personil->nama ?? $user->username, 0, 1)) }}
                            </div>
                            <div class="fw-bold text-white" style="font-size: 0.9rem;">
                                {{ $user->personil->nama ?? $user->username }}
                            </div>
                            <span class="badge bg-white mt-1 px-3 py-1 rounded-pill" style="font-size: 0.68rem; color: #1b3152;">
                                {{ $user->role->nama_role ?? '-' }}
                            </span>
                        </div>

                        <div class="px-3 py-2">
                            <div class="d-flex align-items-center py-2 border-bottom">
                                <i class="fas fa-user profil-info-icon me-3"></i>
                                <div>
                                    <div class="profil-info-label">Username</div>
                                    <div class="profil-info-value">{{ $user->username }}</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center py-2 border-bottom">
                                <i class="fas fa-envelope profil-info-icon me-3"></i>
                                <div>
                                    <div class="profil-info-label">Email</div>
                                    <div class="profil-info-value">{{ $user->email }}</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center py-2 {{ $user->personil ? 'border-bottom' : '' }}">
                                <i class="fas fa-shield-alt profil-info-icon me-3"></i>
                                <div>
                                    <div class="profil-info-label">Hak Akses</div>
                                    <div class="profil-info-value">{{ $user->role->nama_role ?? '-' }}</div>
                                </div>
                            </div>

                            @if($user->personil)
                                <div class="d-flex align-items-center py-2 border-bottom">
                                    <i class="fas fa-address-card profil-info-icon me-3"></i>
                                    <div>
                                        <div class="profil-info-label">Nomor Pegawai</div>
                                        <div class="profil-info-value">{{ $user->personil->no_induk ?? '-' }}</div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center py-2 border-bottom">
                                    <i class="fas fa-id-badge profil-info-icon me-3"></i>
                                    <div>
                                        <div class="profil-info-label">Penempatan</div>
                                        <div class="profil-info-value">{{ $user->personil->jabatan ?? '-' }}</div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center py-2">
                                    <i class="fas fa-building profil-info-icon me-3"></i>
                                    <div>
                                        <div class="profil-info-label">Unit Kerja</div>
                                        <div class="profil-info-value">{{ $user->personil->unit_kerja ?? '-' }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header text-white d-flex align-items-center py-2 px-3" style="background-color: #1b3152;">
                        <i class="fas fa-key me-2" style="font-size: 0.85rem;"></i>
                        <span class="fw-bold" style="font-size: 0.9rem;">Ganti Password</span>
                    </div>

                    <div class="card-body p-3">
                        <p class="text-muted mb-3" style="font-size: 0.72rem;">
                            Gunakan minimal 6 karakter. Kombinasikan huruf dan angka agar lebih aman.
                        </p>

                        <form action="{{ route('profil.password.update') }}" method="POST" id="formGantiPassword">
                            @csrf
                            @method('PUT')

                            <div class="mb-2">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.75rem;">Password Lama</label>
                                <div class="input-group input-group-sm has-validation">
                                    <span class="input-group-text bg-white py-1"><i class="fas fa-lock text-muted" style="font-size: 0.75rem;"></i></span>
                                    <input type="password"
                                        name="password_lama"
                                        class="form-control py-1 @error('password_lama') is-invalid @enderror"
                                        id="passwordLama"
                                        style="font-size: 0.75rem;"
                                        required>
                                    <button class="btn btn-outline-secondary py-1 px-2 toggle-password" type="button" data-target="passwordLama">
                                        <i class="fas fa-eye" style="font-size: 0.75rem;"></i>
                                    </button>
                                    @error('password_lama')
                                        <div class="invalid-feedback" style="font-size: 0.7rem;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-3">

                            <div class="mb-2">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.75rem;">Password Baru</label>
                                <div class="input-group input-group-sm has-validation">
                                    <span class="input-group-text bg-white py-1"><i class="fas fa-key text-muted" style="font-size: 0.75rem;"></i></span>
                                    <input type="password"
                                        name="password_baru"
                                        class="form-control py-1 @error('password_baru') is-invalid @enderror"
                                        id="passwordBaru"
                                        minlength="6"
                                        style="font-size: 0.75rem;"
                                        required>
                                    <button class="btn btn-outline-secondary py-1 px-2 toggle-password" type="button" data-target="passwordBaru">
                                        <i class="fas fa-eye" style="font-size: 0.75rem;"></i>
                                    </button>
                                    @error('password_baru')
                                        <div class="invalid-feedback" style="font-size: 0.7rem;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.75rem;">Konfirmasi Password Baru</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white py-1"><i class="fas fa-key text-muted" style="font-size: 0.75rem;"></i></span>
                                    <input type="password"
                                        name="password_baru_confirmation"
                                        class="form-control py-1"
                                        id="passwordBaruConfirmation"
                                        minlength="6"
                                        style="font-size: 0.75rem;"
                                        required>
                                    <button class="btn btn-outline-secondary py-1 px-2 toggle-password" type="button" data-target="passwordBaruConfirmation">
                                        <i class="fas fa-eye" style="font-size: 0.75rem;"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-corporate-dark btn-sm py-1 px-3" style="font-size: 0.75rem;">
                                    <i class="fas fa-save me-1"></i> Simpan Password Baru
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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
@endpush