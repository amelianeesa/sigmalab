<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Login - SIGMALAB Sucofindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        body {
            margin: 0;
            background-color: #f4f7fb;
            color: #1f2a37;
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 0.92rem;
        }

        .auth-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 18px rgba(15, 35, 59, 0.08);
            overflow: hidden;
            margin: auto;
            background: transparent;
            max-width: 780px;
        }

        .auth-row {
            display: grid;
            grid-template-columns: 1.25fr 1fr;
            min-height: 185px;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
        }

        .auth-side {
            position: relative;
            padding: 0.7rem 0.9rem;
            background: linear-gradient(180deg, #3c7ebd 0%, #2f6aa9 100%);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0.4rem;
        }

        .auth-side::before {
            content: "";
            position: absolute;
            right: -24px;
            top: 50%;
            transform: translateY(-50%);
            width: 100px;
            height: 200px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .auth-side::after {
            content: "";
            position: absolute;
            left: 18px;
            bottom: 18px;
            width: 55px;
            height: 55px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 50%;
        }

        .auth-side h3 {
            font-size: 1.15rem;
            margin: 0;
            font-weight: 700;
        }

        .auth-side p {
            font-size: 0.82rem;
            line-height: 1.5;
            max-width: 240px;
            opacity: 0.92;
            margin: 0;
        }

        .auth-logo-box {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.14);
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
        }

        .auth-logo-box.auth-logo-center {
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-logo-box img {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }

        .auth-form-panel {
            padding: 1rem 0.95rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .auth-form-panel h4 {
            font-size: 0.98rem;
            margin-bottom: 0.2rem;
            font-weight: 700;
        }

        .auth-form-panel p {
            color: #6b7280;
            margin-bottom: 0.7rem;
            line-height: 1.4;
            font-size: 0.84rem;
        }

        .form-label {
            color: #334155;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .form-control,
        .form-select {
            border: 1px solid #d7e1ea;
            border-radius: 10px;
            padding: 0.35rem 0.7rem;
            min-height: 30px;
            font-size: 0.92rem;
            color: #1f2a37;
            background-color: #ffffff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #1a6082;
            box-shadow: 0 0 0 0.2rem rgba(26, 96, 130, 0.12);
        }

        .btn-auth {
            background: #0c3a68;
            border: 1px solid #0c3a68;
            border-radius: 10px;
            padding: 0.55rem 0.95rem;
            font-size: 0.92rem;
            font-weight: 700;
            color: #ffffff;
            transition: all 0.2s ease;
        }

        .btn-auth:hover {
            background: #0a2a4b;
            border-color: #0a2a4b;
            color: #ffffff !important;
        }

        .auth-link {
            color: #0d4c76;
            font-weight: 600;
            text-decoration: none;
        }

        .auth-link:hover {
            color: #0b3d5d;
            text-decoration: underline;
        }

        .auth-footer {
            margin-top: 0.85rem;
            color: #7b8a9d;
            font-size: 0.78rem;
            text-align: center;
        }

        /* ===== Responsif Mobile ===== */
        @media (max-width: 767.98px) {
            html,
            body {
                height: auto;
                min-height: 100%;
                overflow-y: auto;
                overflow-x: hidden;
            }

            body {
                align-items: flex-start !important;
                padding: 0;
            }

            .container.py-4 {
                padding-top: 1.75rem !important;
                padding-bottom: 1.75rem !important;
            }

            .auth-card {
                max-width: 420px;
            }

            .auth-row {
                grid-template-columns: 1fr;
                min-height: 0;
            }

            .auth-side {
                padding: 1.5rem 1.25rem;
                border-radius: 16px 16px 0 0;
                text-align: center;
                align-items: center;
            }

            .auth-side h3 {
                font-size: 1.2rem;
            }

            .auth-side p {
                font-size: 0.85rem;
                max-width: 100%;
            }

            .auth-form-panel {
                padding: 1.5rem 1.25rem;
                border-radius: 0 0 16px 16px;
            }

            .auth-form-panel h4 {
                font-size: 1.05rem;
            }

            .auth-form-panel p {
                font-size: 0.85rem;
            }

            .form-label {
                font-size: 0.85rem;
            }

            .form-control,
            .form-select {
                font-size: 1rem;
                min-height: 42px;
                padding: 0.5rem 0.85rem;
            }

            .btn-auth {
                font-size: 0.95rem;
                padding: 0.7rem 0.95rem;
            }

            .auth-logo-box {
                width: 84px;
                height: 84px;
            }

            .auth-logo-box img {
                width: 84px;
                height: 84px;
            }
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center">
    <div class="container py-4">
        <div class="card auth-card shadow-sm">
            <div class="auth-row">
                <div class="auth-side">
                    <h3>Selamat Datang !</h3>
                    <p>Masuk untuk mengakses Sistem Integrasi Manajemen Laboratorium PT Sucofindo Cilacap.</p>
                </div>
                <div class="auth-form-panel">
                    <div class="auth-logo-box auth-logo-center">
                        <img src="{{ asset('images/Logo_Suco_Nobg.png') }}" alt="Logo Perusahaan">
                    </div>
                    <h4 class="text-center">Masuk ke SIGMA-LAB</h4>
                    <p>Gunakan username atau email yang telah terdaftar dan password Anda.</p>

                    @if(session('success'))
                        <div class="alert alert-success rounded-3">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger rounded-3">{{ $errors->first() }}</div>
                    @endif

                    <form action="{{ route('login.process') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Username atau Email</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username') }}"
                                required autofocus>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label mb-0">Password</label>
                                <a href="{{ route('password.request') }}" class="auth-link"
                                    style="font-size: 0.78rem;">Lupa Password?</a>
                            </div>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" required
                                    style="border-right: none;">
                                <button type="button" class="input-group-text bg-white" id="togglePassword"
                                    style="border-left: none; cursor: pointer; border-color: #d7e1ea;">
                                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-auth w-100">Masuk</button>
                    </form>

                    <div class="text-center mt-4">
                        <small class="text-muted">Jika Anda belum memiliki akun, hubungi HR & GA.</small>
                    </div>
                    <div class="auth-footer">© PT Sucofindo Cilacap</div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            if (type === 'password') {
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        });
    </script>
</body>

</html>