<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGMA-LAB - PT Sucofindo Cilacap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        #sidebar {
            width: 260px; height: 100vh; position: fixed; top: 0; left: 0;
            background-color: #0b1c3d; color: #fff; z-index: 1000;
        }
        #sidebar .sidebar-header { padding: 20px; background: #08142c; font-weight: bold; font-size: 1.1rem; }
        #sidebar ul.components { padding: 10px 0; }
        #sidebar ul li a { padding: 12px 20px; font-size: 0.95rem; display: block; color: #cfd8dc; text-decoration: none; }
        #sidebar ul li a:hover, #sidebar ul li.active > a { color: #fff; background: #1e3a6e; }
        #sidebar ul li a i { margin-right: 10px; }
        #content { margin-left: 260px; padding: 20px; min-height: 100vh; }
    </style>
</head>
<body>
    <nav id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-atom"></i> SigmaLab<br>
            <small style="font-size: 0.75rem; color: #94a3b8;">PT SUCOFINDO – CILACAP</small>
        </div>
        <ul class="list-unstyled components">
            <li class="{{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="{{ request()->is('alat*') ? 'active' : '' }}">
                <a href="{{ route('alat.index') }}"><i class="fas fa-tools"></i> Aset & Kalibrasi</a>
            </li>
            <li><a href="#"><i class="fas fa-boxes"></i> Inventori Barang</a></li>
            <li><a href="#"><i class="fas fa-vial"></i> QC & Parameter Uji</a></li>
            <li><a href="#"><i class="fas fa-users"></i> SDM & Kompetensi</a></li>
            <li><a href="#"><i class="fas fa-history"></i> Audit Log</a></li>
        </ul>
    </nav>

    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 px-3 rounded">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h6 text-secondary">Sistem Integrasi Manajemen Laboratorium</span>
                <span class="fw-bold text-dark"><i class="fas fa-user-circle"></i> Admin Sistem</span>
            </div>
        </nav>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
</body>
</html>