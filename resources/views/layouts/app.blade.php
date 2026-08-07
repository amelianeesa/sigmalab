<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - SIGMALAB Sucofindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }

        :root{
            --sdm-600: #1d4c7a;
            --sdm-700: #163d63;
        }

        #content { padding: 24px; padding-top: 20px; }

        .top-navbar {
            background: var(--sdm-600);
            padding: 12px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1020;
            min-height: 64px;
            color: #fff;
        }

        .custom-dropdown-profile .btn {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.35) !important;
            border-radius: 6px !important;
            padding: 6px 14px;
            color: white !important;
        }
        .custom-dropdown-profile .btn:hover {
            background-color: rgba(255, 255, 255, 0.2) !important;
        }

        .custom-dropdown-profile .dropdown-menu {
            background-color: #ffffff !important;
            border: 1px solid rgba(0, 0, 0, 0.15) !important;
            border-radius: 6px !important;
            margin-top: -2px !important;
            padding: 0 !important;
            min-width: 100% !important;
            width: 100% !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15) !important;
            overflow: hidden;
        }

        .custom-dropdown-profile .dropdown-item {
            color: var(--sdm-600) !important;
            background: #ffffff !important;
            padding: 10px 14px;
            font-size: 0.9rem;
            transition: all 0.2s;
            font-weight: 500;
        }
        .custom-dropdown-profile .dropdown-item:hover {
            background-color: #e9ecef !important;
            color: #dc3545 !important;
        }
        .card.h-100 .card-body { min-height: 120px; }
        .module-card { transition: transform .12s ease, box-shadow .12s ease; }
        .module-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(15,35,59,.08); }
        .sdm-highlight { border-color: var(--sdm-600) !important; }
        .sdm-text { color: var(--sdm-600) !important; }
        .sdm-btn { background: var(--sdm-600); border-color: var(--sdm-600); color: #fff; }
        .sdm-border { border-color: rgba(76,29,149,0.12); }
    </style>
</head>

<body>

    <div class="top-navbar shadow-sm">
        <div>
            <span class="text-uppercase fw-bold d-block mb-1" style="font-size: 18px; letter-spacing: 1px; color: #fff;">SIGMA-LAB</span>
            <div class="mb-0 fw-bold" style="color: #fff;">Sistem Integrasi Manajemen Laboratorium PT Sucofindo</div>
        </div>

        <div class="dropdown custom-dropdown-profile">
            <button class="btn dropdown-toggle d-flex align-items-center" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle me-2 fs-5"></i> 
                <span>{{ Auth::user()->name ?? Auth::user()->username ?? 'Pengguna' }}</span>
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

    <div id="content">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</body>
</html>
</body>
</html>

