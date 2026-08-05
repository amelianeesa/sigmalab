<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - SIGMALAB Sucofindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-3 fw-bold text-primary">Registrasi Akun</h3>
                        <p class="text-center text-muted small mb-4">SIGMALAB - PT Sucofindo</p>
                        
                        <form action="/auth/processRegister" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" value="<?= old('username') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Role Akses</label>
                                <select name="role_id" class="form-select" required>
                                    <option value="">Pilih Role...</option>
                                    <?php foreach($roles as $r): ?>
                                        <option value="<?= $r['roles_id'] ?>"><?= $r['nama_role'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hubungkan ke Data Personil (Opsional)</label>
                                <select name="personil_id" class="form-select">
                                    <option value="">-- Bukan Personil / Pilih nanti --</option>
                                    <?php foreach($personil as $p): ?>
                                        <option value="<?= $p['personil_id'] ?>"><?= $p['nama'] ?> (<?= $p['jabatan'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100 py-2">Daftar</button>
                        </form>
                        <div class="text-center mt-3">
                            <small class="text-muted">Sudah punya akun? <a href="/login">Login di sini</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>