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
            <div class="col-md-4">
                <div class="card auth-card shadow-sm">
                    <div class="auth-brand">
                        <div class="kicker">Sistem Integrasi</div>
                        <h3>SIGMALAB</h3>
                    </div>
                    <div class="card-body auth-body">
                        <p class="auth-subtitle text-center">Masuk untuk mengakses sistem manajemen SDM dan kompetensi laboratorium.</p>

                        @if(session('success'))
                            <div class="alert alert-success rounded-3">{{ session('success') }}</div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger rounded-3">{{ $errors->first() }}</div>
                        @endif

                        <form action="{{ route('login.process') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Username atau Email</label>
                                <input type="text" name="username" class="form-control" value="{{ old('username') }}" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-auth w-100 py-2">Masuk</button>
                        </form>
                        <div class="text-center mt-3">
                            <small class="text-muted">Belum punya akun? <a href="{{ route('register') }}" class="auth-link">Register</a></small>
                        </div>
                        <div class="auth-footer">© PT Sucofindo Cilacap</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>