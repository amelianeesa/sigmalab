<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGMA-LAB - PT Sucofindo Cilacap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root{
            --sdm-50: #eef0f1;
            --sdm-500: #1d4c7a;
            --sdm-600: #1d4c7a;
            --sdm-700: #163d63;
        }

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
        #content { 
            margin-left: 260px; 
            padding: 24px; 
            padding-top: 20px; 
            min-height: 100vh; 
        }

        .top-navbar {
            background: var(--sdm-600);
            padding: 12px 28px;
            border-bottom: 1px solid var(--sdm-700);
            margin-left: 260px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1020;
            min-height: 64px;
            color: #fff;
        }
        .top-navbar .text-secondary { color: rgba(255,255,255,.75) !important; }
        .top-navbar .fw-bold { color: #fff !important; }
        .top-navbar .dropdown .btn {
            background: rgba(255,255,255,.12);
            color: #fff;
            border-color: rgba(255,255,255,.18);
        }
        .top-navbar .dropdown button {
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .card.h-100 .card-body { min-height: 120px; }
        .card .card-body p { word-break: break-word; }
        .module-card { transition: transform .12s ease, box-shadow .12s ease, border-color .12s ease; }
        .module-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(15,35,59,.08); }
        .sdm-highlight { border-color: var(--sdm-600) !important; }
        .sdm-text { color: var(--sdm-600) !important; }
        .sdm-btn { background: var(--sdm-600); border-color: var(--sdm-600); color: #fff; }
        .sdm-border { border-color: rgba(76,29,149,0.12); }
    </style>
</head>
<body>
    <nav id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-atom"></i> SigmaLab<br>
            <small style="font-size: 0.75rem; color: #94a3b8;">PT SUCOFINDO – CILACAP</small>
        </div>
        <ul class="list-unstyled components">
            <li class="{{ request()->is('/') || request()->is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') ?? url('/') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="{{ request()->is('alat*') ? 'active' : '' }}">
                <a href="{{ route('alat.index') ?? '#' }}"><i class="fas fa-tools"></i> Aset & Kalibrasi</a>
            </li>
            <li><a href="#"><i class="fas fa-boxes"></i> Inventori Barang</a></li>
            <li><a href="#"><i class="fas fa-vial"></i> QC & Parameter Uji</a></li>
            <li class="{{ request()->is('sdm*') ? 'active' : '' }}"><a href="{{ route('sdm.index') ?? '#' }}"><i class="fas fa-users"></i> SDM & Kompetensi</a></li>
            <li><a href="#"><i class="fas fa-history"></i> Audit Log</a></li>
        </ul>
    </nav>

    <div class="top-navbar shadow-sm">
        <div>
            <span class="text-uppercase text-secondary fs-7 fw-bold d-block mb-1" style="font-size: 18px; letter-spacing: 1px;">SIGMA-LAB</span>
            <div class="mb-0 fw-bold">Sistem Integrasi Manajemen Laboratorium PT Sucofindo</div>
        </div>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle border" type="button" data-bs-toggle="dropdown" title="Profil">
                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name ?? Auth::user()->username ?? 'Pengguna' }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <div id="content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>