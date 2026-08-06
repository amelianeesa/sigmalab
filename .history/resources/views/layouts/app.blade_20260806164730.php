<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - SIGMALAB Sucofindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        :root{
            --sdm-50: #eef0f1;
            --sdm-500: #1d4c7a;
            --sdm-600: #1d4c7a;
            --sdm-700: #163d63;
        }

        #content {
            margin-left: 0;
            padding: 24px;
            padding-top: 20px; 
        }

        .top-navbar {
            background: var(--sdm-600);
            padding: 12px 28px;
            border-bottom: 1px solid var(--sdm-700);
            margin-left: 0;
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

    <div class="top-navbar shadow-sm">
        <div>
            <span class="text-uppercase text-secondary fs-7 fw-bold d-block mb-1" style="font-size: 18px; letter-spacing: 1px;">SIGMA-LAB</span>
            <div class="mb-0 fw-bold">Sistem Integrasi Manajemen Laboratorium PT Sucofindo</div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="d-flex align-items-center gap-1 px-3 py-2 rounded-3" style="background: rgba(255,255,255,.95); color: #0f3b68;">
                <i class="bi bi-person-circle"></i>
                <span class="fw-semibold">{{ Auth::user()->name ?? Auth::user()->username ?? 'Pengguna' }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light text-white px-3 py-2">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div id="content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
</body>
</html>