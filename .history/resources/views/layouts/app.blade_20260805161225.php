<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - SIGMALAB Sucofindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        /* Sidebar Styling */
        #sidebar {
            min-height: 100vh;
            background-color: #0d1b2a;
            color: #fff;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }

        #sidebar .brand {
            padding: 20px;
            font-weight: bold;
            font-size: 1.1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        #sidebar .nav-link {
            color: #adb5bd;
            padding: 12px 20px;
            margin: 4px 10px;
            border-radius: 6px;
        }

        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            color: #fff;
            background-color: #1b263b;
        }

        /* Main Content Styling */
        #content {
            margin-left: 260px;
            padding: 30px;
        }

        .top-navbar {
            background: #fff;
            padding: 15px 30px;
            border-bottom: 1px solid #dee2e6;
            margin-left: 260px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div id="sidebar" class="d-flex flex-column">
        <div class="brand">
            <i class="bi bi-gear-fill text-info"></i> SigmaLab<br>
            <small class="text-muted" style="font-size: 0.75rem;">PT SUCOFINDO – CILACAP</small>
        </div>
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link active"><i class="bi bi-speedometer2 me-2"></i>
                    Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="bi bi-cpu me-2"></i> Aset & Kalibrasi</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="bi bi-check2-square me-2"></i> QC & Parameter Uji</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="bi bi-box-seam me-2"></i> Inventori Bahan</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('sdm.index') }}" class="nav-link {{ request()->routeIs('sdm*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> SDM & Kompetensi
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"><i class="bi bi-journal-text me-2"></i> Audit Log</a>
            </li>
        </ul>
    </div>

    <!-- Top Navbar -->
    <div class="top-navbar shadow-sm">
        <div>
            <span class="text-uppercase text-secondary fs-7 fw-bold d-block mb-1" style="font-size: 12px; letter-spacing: 1px;">MANAJEMEN SDM</span>
            <h5 class="mb-0 fw-bold">Kelola profil personil, kompetensi, dan kepatuhan sertifikasi laboratorium.</h5>
        </div>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle border" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->username ?? 'Admin' }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i
                                class="bi bi-box-arrow-right me-2"></i> Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div id="content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>