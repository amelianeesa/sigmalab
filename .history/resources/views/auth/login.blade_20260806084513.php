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
            border: 1px solid #e4ebf2;
            border-radius: 16px;
            box-shadow: 0 8px 22px rgba(15, 35, 59, 0.06);
            overflow: hidden;
            margin: auto;
            background: #ffffff;
            max-width: 760px;
        }

        .auth-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 440px;
        }

        .auth-side {
            position: relative;
            padding: 1.6rem 1.6rem;
            background: linear-gradient(180deg, #0f3b63 0%, #113e63 100%);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .auth-side h3 {
            font-size: 1.4rem;
            margin-bottom: 0.6rem;
            font-weight: 700;
        }

        .auth-side p {
            font-size: 0.95rem;
            line-height: 1.6;
            max-width: 300px;
            opacity: 0.9;
        }

        .auth-badges {
            margin-top: 1.4rem;
            display: flex;
            gap: 0.45rem;
            flex-wrap: wrap;
        }

        .auth-badge {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 999px;
            padding: 0.35rem 0.75rem;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .auth-side-image {
            position: absolute;
            right: 1.25rem;
            bottom: 1.25rem;
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.08);
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.16);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-side-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .auth-form-panel {
            padding: 1.8rem 1.6rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #fafcff;
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
            padding: 0.85rem 1rem;
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
                    <div>
                        <h3>SIGMALAB</h3>
                        <p>Sistem Manajemen Lab & Kalibrasi terintegrasi untuk PT Sucofindo Cilacap. Kelola aset, SDM, dan hasil uji dalam satu platform yang aman.</p>
                        <div class="auth-badges">
                            <span class="auth-badge">Admin</span>
                            <span class="auth-badge">Analis</span>
                            <span class="auth-badge">QC</span>
                        </div>
                    </div>
                    <div class="auth-side-image">
                        <img src="https://via.placeholder.com/150x150.png?text=Logo" alt="Logo Perusahaan">
                    </div>
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