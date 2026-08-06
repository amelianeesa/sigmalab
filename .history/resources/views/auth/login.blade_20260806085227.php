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
            border-radius: 24px;
            box-shadow: 0 18px 45px rgba(15, 35, 59, 0.12);
            overflow: hidden;
            margin: auto;
            background: transparent;
            max-width: 820px;
        }

        .auth-row {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            min-height: 460px;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
        }

        .auth-side {
            position: relative;
            padding: 2rem 2rem;
            background: linear-gradient(180deg, #0b335e 0%, #102a52 100%);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 1rem;
        }

        .auth-side::before {
            content: "";
            position: absolute;
            right: -40px;
            top: 50%;
            transform: translateY(-50%);
            width: 160px;
            height: 320px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
        }

        .auth-side::after {
            content: "";
            position: absolute;
            left: 20px;
            bottom: 20px;
            width: 70px;
            height: 70px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }

        .auth-side h3 {
            font-size: 1.55rem;
            margin: 0;
            font-weight: 700;
        }

        .auth-side p {
            font-size: 0.95rem;
            line-height: 1.7;
            max-width: 280px;
            opacity: 0.9;
            margin: 0;
        }

        .auth-logo-box {
            width: 88px;
            height: 88px;
            background: rgba(255,255,255,0.13);
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.18);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .auth-logo-box img {
            width: 72px;
            height: 72px;
            object-fit: contain;
        }

        .auth-form-panel {
            padding: 2rem 1.8rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .auth-form-panel h4 {
            font-size: 1.18rem;
            margin-bottom: 0.35rem;
            font-weight: 700;
        }

        .auth-form-panel p {
            color: #6b7280;
            margin-bottom: 1.1rem;
            line-height: 1.5;
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
            padding: 0.75rem 0.9rem;
            color: #1f2a37;
            background-color: #ffffff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #1a6082;
            box-shadow: 0 0 0 0.2rem rgba(26, 96, 130, 0.12);
        }

        .btn-auth {
            background: #081d36;
            border: 1px solid #081d36;
            border-radius: 10px;
            padding: 0.8rem 1rem;
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
                    <h3>Selamat Datang</h3>
                    <p>Masuk untuk mengakses sistem manajemen laboratorium dan SDM PT Sucofindo Cilacap dengan antarmuka yang sederhana dan profesional.</p>
                </div>
                <div class="auth-form-panel">
                    <h4>Masuk ke SigmaLab</h4>
                    <p>Gunakan username atau email PT Sucofindo yang telah terdaftar dan password Anda.</p>

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