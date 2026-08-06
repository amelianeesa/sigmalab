<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen SDM - SIGMALAB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">SIGMALAB - Sucofindo</a>
            <div class="navbar-nav ms-auto">
                <span class="nav-item nav-link text-light">Halo, <?= session()->get('username'); ?></span>
                <a href="/auth/logout" class="btn btn-outline-light btn-sm ms-2">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>Data Personil Laboratorium & Preparasi</h2>
                    <a href="/sdm/kompetensi" class="btn btn-info btn-sm text-white">Lihat Matriks Kompetensi & Sertifikasi</a>
                </div>

                <?php if(session()->getFlashdata('success')):?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif;?>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No Induk</th>
                                    <th>Nama Personil</th>
                                    <th>Jabatan</th>
                                    <th>Unit Kerja</th>
                                    <th>CV</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($personil)): ?>
                                    <?php foreach($personil as $row): ?>
                                    <tr>
                                        <td><?= $row['no_induk']; ?></td>
                                        <td><?= $row['nama']; ?></td>
                                        <td><?= $row['jabatan']; ?></td>
                                        <td><?= $row['unit_kerja']; ?></td>
                                        <td>
                                            <?php if($row['file_cv']): ?>
                                                <a href="/uploads/cv/<?= $row['file_cv']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">Unduh CV</a>
                                            <?php else: ?>
                                                <span class="text-muted small">Belum ada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Aktif</span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada data personil.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>