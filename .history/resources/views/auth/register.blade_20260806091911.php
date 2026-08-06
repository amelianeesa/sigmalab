<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - SIGMALAB Sucofindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            background: #ffffff;
            max-width: 780px;
            margin: auto;
        }

        .auth-form-panel {
            padding: 1rem 0.95rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        .auth-form-panel h3 {
            font-size: 1.15rem;
            margin-bottom: 0.25rem;
            font-weight: 700;
        }

        .auth-subtitle {
            color: #6b7280;
            font-size: 0.84rem;
            margin-bottom: 0.9rem;
            line-height: 1.4;
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
            margin: 0 auto 1rem;
        }

        .auth-logo-box img {
            width: 56px;
            height: 56px;
            object-fit: contain;
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
<body class="d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="card auth-card shadow-sm">
            <div class="auth-form-panel">
                <div class="auth-logo-box">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Perusahaan">
                </div>
                <h3 class="text-center">Registrasi SIGMALAB</h3>
                <p class="auth-subtitle text-center">Daftarkan akun pengguna dengan tampilan yang konsisten dan profesional.</p>

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
                    <div class="mb-3">
                        <label class="form-label">Hubungkan ke Data Personil (Opsional)</label>
                        <select name="personil_id" class="form-select">
                            <option value="">-- Bukan Personil / Pilih nanti --</option>
                            @foreach($personil as $p)
                                <option value="{{ $p->personil_id }}">{{ $p->nama }} ({{ $p->jabatan }})</option>
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
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>