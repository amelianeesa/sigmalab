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
            border-radius: 18px;
            box-shadow: 0 10px 28px rgba(15, 35, 59, 0.06);
            overflow: hidden;
            max-width: 420px;
            margin: auto;
            background: #ffffff;
        }

        .auth-header {
            background-color: #0e2f54;
            color: #fff;
            padding: 1.1rem 1.5rem;
            text-align: left;
        }

        .auth-header h3 {
            font-size: 1.35rem;
            margin: 0;
            font-weight: 700;
        }

        .auth-header p {
            margin: 0.65rem 0 0;
            opacity: 0.85;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .auth-body {
            padding: 1.4rem 1.5rem 1.6rem;
        }

        .auth-subtitle {
            color: #566477;
            font-size: 0.95rem;
            margin-bottom: 1rem;
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
            background: #0d4c76;
            border: 1px solid #0d4c76;
            border-radius: 10px;
            padding: 0.85rem 1rem;
            font-weight: 700;
            color: #ffffff;
            transition: all 0.2s ease;
        }

        .btn-auth:hover {
            background: #0b3d5d;
            border-color: #0b3d5d;
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
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card auth-card shadow-sm">
                        <div class="auth-header">
                            <h3>SIGMALAB</h3>
                            <p>Masuk untuk mengakses sistem manajemen SDM dan kompetensi laboratorium PT Sucofindo Cilacap.</p>
                        </div>
                        <div class="card-body auth-body">
                            <p class="auth-subtitle text-center">Gunakan username atau email yang telah terdaftar untuk masuk.</p>
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