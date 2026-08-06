<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SIGMALAB Sucofindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background-color: #f4f7fb;
            color: #1f2a37;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .auth-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 10px 20px rgba(15, 35, 59, 0.08);
            overflow: hidden;
            margin: auto;
            background: transparent;
            max-width: 760px;
        }

        .auth-row {
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            min-height: 280px;
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
        }

        .auth-side {
            position: relative;
            padding: 1rem 1rem;
            background: linear-gradient(180deg, #3c7ebd 0%, #2f6aa9 100%);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0.55rem;
        }

        .auth-side::before {
            content: "";
            position: absolute;
            right: -30px;
            top: 50%;
            transform: translateY(-50%);
            width: 120px;
            height: 240px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }

        .auth-side::after {
            content: "";
            position: absolute;
            left: 18px;
            bottom: 18px;
            width: 55px;
            height: 55px;
            background: rgba(255,255,255,0.12);
            border-radius: 50%;
        }

        .auth-side h3 {
            font-size: 1.35rem;
            margin: 0;
            font-weight: 700;
        }

        .auth-side p {
            font-size: 0.92rem;
            line-height: 1.6;
            max-width: 260px;
            opacity: 0.92;
            margin: 0;
        }

        .auth-logo-box {
            width: 70px;
            height: 70px;
            background: rgba(255,255,255,0.14);
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.2);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
        }

        .auth-logo-box img {
            width: 56px;
            height: 56px;
            object-fit: contain;
        }

        .auth-form-panel {
            padding: 1.2rem 1rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .auth-form-panel h4 {
            font-size: 1.04rem;
            margin-bottom: 0.25rem;
            font-weight: 700;
        }

        .auth-form-panel p {
            color: #6b7280;
            margin-bottom: 0.8rem;
            line-height: 1.4;
        }

        .form-label {
            color: #334155;
            font-size: 0.92rem;
            font-weight: 600;
        }

        .form-control,
        .form-select {
            border: 1px solid #d7e1ea;
            border-radius: 10px;
            padding: 0.7rem 0.85rem;
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
            padding: 0.75rem 0.95rem;
            font-weight: 700;
            color: #ffffff;
            transition: all 0.2s ease;
        }

        .btn-auth:hover {
            background: #0a2a4b;
            border-color: #0a2a4b;
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
            margin-top: 1rem;
            color: #7b8a9d;
            font-size: 0.85rem;
            text-align: center;
        }
    </style>
</head>
<body class="d-flex align-items-center py-4">
    <div class="container py-5">
        <div class="card auth-card shadow-sm">
            <div class="auth-row">
                <div class="auth-side">
                    <div class="auth-logo-box">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Perusahaan">
                    </div>
                    <h3>Selamat Datang !</h3>
                    <p>Masuk untuk mengakses Sistem Integrasi Manajamen Laboratorium PT Sucofindo Cilacap.</p>
                </div>
                <div class="auth-form-panel">
                    <h4>Masuk ke SIGMA-LAB</h4>
                    <p>Gunakan username atau email   yang telah terdaftar dan password Anda.</p>

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
                            <input type="text" name="username" class="form-control" value="{{ old('username') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-auth w-100">Masuk</button>
                    </form>

                    <div class="text-center mt-4">
                        <small class="text-muted">Belum punya akun? <a href="{{ route('register') }}" class="auth-link">Daftar di sini</a></small>
                    </div>
                    <div class="auth-footer">© PT Sucofindo Cilacap</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>