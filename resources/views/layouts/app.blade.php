<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - SIGMALAB Sucofindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
        #sidebar .sidebar-divider { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: #64748b; padding: 15px 20px 5px; margin-top: 5px; border-top: 1px solid #1e3a6e; }
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
        .btn-logout {
            border-color: rgba(255,255,255,.45);
            color: #fff;
            transition: background .15s ease, color .15s ease, border-color .15s ease;
        }
        .btn-logout:hover,
        .btn-logout:focus {
            background: rgba(255,255,255,.95);
            color: #1d4c7a;
            border-color: rgba(255,255,255,.85);
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
        <ul class="list-unstyled components" style="overflow-y: auto; max-height: calc(100vh - 80px);">
            <li class="{{ request()->is('/') || request()->is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') ?? url('/') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>

            {{-- Modul Tim Lain --}}
            <li class="sidebar-divider">Modul Umum</li>
            <li class="{{ request()->is('alat*') ? 'active' : '' }}">
                <a href="{{ route('alat.index') ?? '#' }}"><i class="fas fa-tools"></i> Aset & Kalibrasi</a>
            </li>
            <li><a href="#"><i class="fas fa-boxes"></i> Inventori Barang</a></li>
            <li class="{{ request()->is('sdm*') ? 'active' : '' }}"><a href="{{ route('sdm.index') ?? '#' }}"><i class="fas fa-users"></i> SDM & Kompetensi</a></li>
            <li><a href="#"><i class="fas fa-history"></i> Audit Log</a></li>

            {{-- Modul QC & Proses (domain Amel) --}}
            <li class="sidebar-divider">QC & Proses</li>
            <li class="{{ request()->is('parameter-uji*') ? 'active' : '' }}">
                <a href="{{ route('parameter-uji.index') }}"><i class="fas fa-vial"></i> Parameter Uji</a>
            </li>
            <li class="{{ request()->routeIs('kegiatan.*') ? 'active' : '' }}">
                <a href="{{ route('kegiatan.index') }}"><i class="fas fa-clipboard-list"></i> Proses & Hasil Pengujian</a>
            </li>
            <li class="{{ request()->is('tindak-lanjut*') ? 'active' : '' }}">
                <a href="{{ route('tindak-lanjut.index') }}"><i class="fas fa-clipboard-check"></i> Tindak Lanjut</a>
            </li>

            {{-- Reporting --}}
            <li class="sidebar-divider">Reporting</li>
            <li class="{{ request()->is('reporting*') ? 'active' : '' }}">
                <a href="{{ route('reporting.index') }}"><i class="fas fa-chart-bar"></i> Laporan QC</a>
            </li>
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
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 px-3 rounded">
            <div class="container-fluid">
                <!-- Role Switcher Form -->
                <div class="d-flex align-items-center ms-auto">
                    <span class="me-2 text-muted small">Simulasi Role:</span>
                    <form action="{{ route('switch-role') }}" method="POST" class="m-0">
                        @csrf
                        <select name="role_name" class="form-select form-select-sm fw-bold border-0 bg-light" onchange="this.form.submit()">
                            <option value="">-- Pilih Role --</option>
                            @foreach(App\Enums\PeranPengguna::cases() as $role)
                                <option value="{{ $role->value }}" {{ (Auth::check() && Auth::user()->role && Auth::user()->role->nama_role === $role->value) ? 'selected' : '' }}>
                                    {{ $role->value }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    @if(Auth::check())
                        <span class="ms-3 badge bg-primary">Logged in as: {{ Auth::user()->username }}</span>
                    @endif
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light btn-logout px-3 py-2">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>
        </div>
    </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    <div id="content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Live Search Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.live-search-form');
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, select');
            const targetSelector = form.dataset.target || '#table-container';
            const targetContainer = document.querySelector(targetSelector);
            
            if (!targetContainer) return;
            
            let timeout = null;
            
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        executeSearch(form, targetContainer);
                    }, 400); // 400ms debounce
                });
            });
            
            // Tangkap klik pagination agar juga via AJAX
            targetContainer.addEventListener('click', function(e) {
                const link = e.target.closest('.pagination a');
                if (link) {
                    e.preventDefault();
                    executeSearch(form, targetContainer, link.href);
                }
            });
        });
        
        function executeSearch(form, targetContainer, url = null) {
            // Visual feedback loading
            targetContainer.style.opacity = '0.5';
            
            const formData = new FormData(form);
            const searchParams = new URLSearchParams(formData);
            const fetchUrl = url || `${form.action || window.location.pathname}?${searchParams.toString()}`;
            
            // Update URL bar (biar bisa di-copy paste)
            window.history.pushState({}, '', fetchUrl);
            
            fetch(fetchUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const targetSelector = form.dataset.target || '#table-container';
                const newContent = doc.querySelector(targetSelector);
                
                if (newContent) {
                    targetContainer.innerHTML = newContent.innerHTML;
                }
                targetContainer.style.opacity = '1';
            })
            .catch(error => {
                console.error('Live search error:', error);
                targetContainer.style.opacity = '1';
            });
        }
    });
    </script>
    @stack('scripts')
</body>
</html>