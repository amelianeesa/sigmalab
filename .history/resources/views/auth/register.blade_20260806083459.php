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
            background: linear-gradient(135deg, #f4f8fc 0%, #eef4f8 100%);
            color: #172b4d;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .auth-card {
            border: 1px solid #e7edf3;
            border-radius: 24px;
            box-shadow: 0 16px 48px rgba(15, 35, 59, 0.08);
            overflow: hidden;
        }

        .auth-brand {
            background: linear-gradient(135deg, #0b1f36 0%, #12385d 100%);
            color: #fff;
            padding: 1.25rem 1.5rem;
            text-align: center;
        }

        .auth-brand .kicker {
            font-size: 0.72rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            opacity: 0.8;
        }

        .auth-brand h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0.2rem 0 0;
        }

        .form-control,
        .form-select {
            border: 1px solid #d9e4ee;
            border-radius: 12px;
            padding: 0.7rem 0.9rem;
            color: #172b4d;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0b5875;
            box-shadow: 0 0 0 0.2rem rgba(11, 88, 117, 0.15);
        }

        .btn-auth {
            background: #0b1f36;
            border: 1px solid #0b1f36;
            border-radius: 999px;
            padding: 0.75rem 1rem;
            font-weight: 600;
        }

        .btn-auth:hover {
            background: #12385d;
            border-color: #12385d;
        }

        .auth-link {
            color: #0b5875;
            font-weight: 600;
            text-decoration: none;
        }

        .auth-link:hover {
            color: #08445d;
            text-decoration: underline;
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
                    <div class="card-body p-4">
                        <p class="text-center text-muted small mb-4">Daftarkan akses pengguna dengan nuansa yang konsisten dengan fitur SDM</p>

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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>