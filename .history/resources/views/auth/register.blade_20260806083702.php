<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - SIGMALAB Sucofindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #f5f8fc 0%, #eef4f8 100%);
            color: #1f2a37;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .auth-card {
            border: 1px solid #e3eaf0;
            border-radius: 20px;
            box-shadow: 0 12px 36px rgba(15, 35, 59, 0.08);
            overflow: hidden;
        }

        .auth-brand {
            background: linear-gradient(135deg, #11253f 0%, #163b5f 100%);
            color: #fff;
            padding: 1.2rem 1.4rem;
            text-align: left;
        }

        .auth-brand .kicker {
            font-size: 0.72rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            opacity: 0.8;
            margin-bottom: 0.2rem;
        }

        .auth-brand h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
        }

        .auth-body {
            padding: 1.4rem 1.4rem 1.2rem;
        }

        .auth-subtitle {
            color: #64748b;
            font-size: 0.92rem;
            margin-bottom: 1.1rem;
        }

        .form-label {
            color: #334155;
            font-size: 0.92rem;
            font-weight: 600;
            margin-bottom: 0.45rem;
        }

        .form-control,
        .form-select {
            border: 1px solid #d6e0ea;
            border-radius: 10px;
            padding: 0.72rem 0.85rem;
            color: #1f2a37;
            background-color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #1c6a8e;
            box-shadow: 0 0 0 0.2rem rgba(28, 106, 142, 0.14);
        }

        .btn-auth {
            background: #0f6c8f;
            border: 1px solid #0f6c8f;
            border-radius: 999px;
            padding: 0.72rem 1rem;
            font-weight: 600;
            color: #ffffff;
            transition: all 0.2s ease;
        }

        .btn-auth:hover {
            background: #0d5874;
            border-color: #0d5874;
            color: #ffffff;
        }

        .auth-link {
            color: #136b91;
            font-weight: 600;
            text-decoration: none;
        }

        .auth-link:hover {
            color: #0f5674;
            text-decoration: underline;
        }

        .auth-footer {
            margin-top: 0.8rem;
            color: #94a3b8;
            font-size: 0.8rem;
            text-align: center;
        }
    </style>
</head>
<body class="d-flex align-items-center py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card auth-card shadow-sm">
                    <div class="auth-brand">
                        <div class="kicker">Buat akun baru</div>
                        <h3>Registrasi SIGMALAB</h3>
                    </div>
                    <div class="card-body auth-body">
                        <p class="auth-subtitle text-center">Daftarkan akun pengguna dengan tampilan yang konsisten dan profesional.</p>

                        <form action="{{ route('register.process') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Username</label>
                                <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Role Akses (Peran Sistem)</label>
                                <select name="role_id" class="form-select" required>
                                    <option value="">Pilih Role...</option>
                                    @foreach($roles as $r)
                                        <option value="{{ $r->roles_id }}">{{ $r->nama_role }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Hubungkan ke Data Personil (Opsional)</label>
                                <select name="personil_id" class="form-select">
                                    <option value="">-- Bukan Personil / Pilih nanti --</option>
                                    @foreach($personil as $p)
                                        <option value="{{ $p->personil_id }}">{{ $p->nama }} ({{ $p->jabatan }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-auth w-100 py-2">Daftar</button>
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