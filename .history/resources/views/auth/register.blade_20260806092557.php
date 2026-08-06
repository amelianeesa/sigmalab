<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - SIGMALAB Sucofindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background-color: #f4f7fb;
            color: #1f2a37;
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 0.92rem;
        }

        .auth-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 24px rgba(15, 35, 59, 0.08);
            overflow: hidden;
            background: #ffffff;
            max-width: 700px;
            margin: auto;
        }

        .auth-form-panel {
            padding: 1.1rem 1rem 1.2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .auth-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1rem;
        }

        .auth-header h3 {
            font-size: 1.22rem;
            margin: 0;
            font-weight: 700;
        }

        .auth-header p {
            color: #6b7280;
            font-size: 0.85rem;
            margin: 0;
            line-height: 1.6;
            max-width: 100%;
            text-align: center;
        }

        .auth-logo-box {
            width: 72px;
            height: 72px;
            background: rgba(255,255,255,0.14);
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.2);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .auth-logo-box img {
            width: 56px;
            height: 56px;
            object-fit: contain;
        }

        .form-label {
            color: #334155;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .form-control,
        .form-select {
            border: 1px solid #d7e1ea;
            border-radius: 12px;
            padding: 0.55rem 0.85rem;
            min-height: 36px;
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
            padding: 0.65rem 0.95rem;
            font-size: 0.92rem;
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
            margin-top: 0.85rem;
            color: #7b8a9d;
            font-size: 0.78rem;
            text-align: center;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-4">
    <div class="container py-4">
        <div class="card auth-card shadow-sm">
            <div class="auth-form-panel">
                <div class="auth-logo-box">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Perusahaan">
                </div>
                <div class="auth-header">
                    <h3>Registrasi SIGMA-LAB</h3>
                    <p>Lengkapi data untuk Anda bergabung dengan SIGMA-LAB.</p>
                </div>

                <form action="{{ route('register.process') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role Akses (Peran Sistem)</label>
                        <select name="role_id" class="form-select" required>
                            <option value="">Pilih Role...</option>
                            @foreach($roles as $r)
                                <option value="{{ $r->roles_id }}">{{ $r->nama_role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-auth w-100">Daftar</button>
                </form>

                <div class="text-center mt-3">
                    <small class="text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="auth-link">Login di sini</a></small>
                </div>
                <div class="auth-footer">© PT Sucofindo Cilacap</div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>