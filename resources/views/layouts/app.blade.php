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

        @php
            $useSidebar = auth()->check() && auth()->user()->role && in_array(auth()->user()->role->nama_role, ['Admin Aplikasi', 'Admin Lab']);
        @endphp

        body { background-color: #f8f9fa; overflow-x: hidden; }
        
        #sidebar {
            width: 260px; height: 100vh; position: fixed; top: 0; left: 0;
            background-color: #0b1c3d; color: #fff; z-index: 1040;
            transition: transform 0.3s ease;
        }
        
        #content { 
            transition: margin-left 0.3s ease;
            margin-left: {{ $useSidebar ? '260px' : '0' }}; 
            padding: 24px; 
            padding-top: 20px; 
            min-height: 100vh; 
        }

        .top-navbar {
            background: var(--sdm-600);
            padding: 12px 28px;
            border-bottom: 1px solid var(--sdm-700);
            transition: margin-left 0.3s ease;
            margin-left: {{ $useSidebar ? '260px' : '0' }};
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1020;
            min-height: 64px;
            color: #fff;
        }

        /* Sidebar Responsive Toggle CSS */
        body.sidebar-toggled #sidebar { transform: translateX(-100%); }
        body.sidebar-toggled #content { margin-left: 0; }
        body.sidebar-toggled .top-navbar { margin-left: 0; }

        @media (max-width: 991.98px) {
            #sidebar { transform: translateX(-100%); }
            #content { margin-left: 0; }
            .top-navbar { margin-left: 0; }
            
            body.sidebar-toggled #sidebar { transform: translateX(0); }
            body.sidebar-toggled #content { margin-left: 0; }
            body.sidebar-toggled .top-navbar { margin-left: 0; }
        }

        #sidebar-overlay {
            display: none;
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.5); z-index: 1030;
        }
        body.sidebar-toggled #sidebar-overlay { display: block; }
        @media (min-width: 992px) {
            body.sidebar-toggled #sidebar-overlay { display: none; }
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
        #sidebar .sidebar-header { padding: 20px; background: #08142c; font-weight: bold; font-size: 1.1rem; }
        #sidebar ul.components { padding: 10px 0; }
        #sidebar ul li a { padding: 12px 20px; font-size: 0.95rem; display: block; color: #cfd8dc; text-decoration: none; }
        #sidebar ul li a:hover, #sidebar ul li.active > a { color: #fff; background: #1e3a6e; }
        #sidebar ul li a i { margin-right: 10px; }
        #sidebar .sidebar-divider { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: #64748b; padding: 15px 20px 5px; margin-top: 5px; border-top: 1px solid #1e3a6e; }
        
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
    @if($useSidebar)
    <div id="sidebar-overlay" onclick="document.body.classList.remove('sidebar-toggled')"></div>
    <nav id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-atom"></i> SigmaLab<br>
            <small style="font-size: 0.75rem; color: #94a3b8;">PT SUCOFINDO – CILACAP</small>
        </div>
        <ul class="list-unstyled components" style="overflow-y: auto; max-height: calc(100vh - 80px);">
            <li class="{{ request()->is('/') || request()->is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>

        
            <li class="sidebar-divider">Modul Umum</li>
            
            @modul('alat')
            <li class="{{ request()->is('alat*') ? 'active' : '' }}">
                <a href="{{ route('alat.index') ?? '#' }}"><i class="fas fa-tools"></i> Aset & Kalibrasi</a>
            </li>
            @endmodul

            @modul('barang')
            <li class="{{ request()->is('barang*') ? 'active' : '' }}">
                <a href="{{ route('barang.index') }}"><i class="fas fa-boxes"></i> Inventori Barang</a>
            </li>
            @endmodul

            @modul('pengadaan')
            <li class="{{ request()->is('pengadaan*') ? 'active' : '' }}">
                <a href="{{ route('pengadaan.index') ?? '#' }}"><i class="fas fa-shopping-cart"></i> Pengadaan Bahan</a>
            </li>
            @endmodul

            @modul('sdm')
            <li class="{{ request()->is('sdm*') ? 'active' : '' }}"><a href="{{ route('sdm.index') ?? '#' }}"><i class="fas fa-users"></i> SDM & Kompetensi</a></li>
            @endmodul

            @modul('audit_log')
            <li class="{{ request()->is('audit-log*') ? 'active' : '' }}">
                <a href="{{ route('audit-log.index') }}"><i class="fas fa-history"></i> Audit Log</a>
            </li>
            @endmodul

            @modul('manajemen_pengguna')
            <li class="sidebar-divider">Konfigurasi</li>
            <li class="{{ request()->is('hak-akses*') ? 'active' : '' }}">
                <a href="{{ route('hak-akses.index') }}"><i class="fas fa-user-shield"></i> Manajemen Hak Akses</a>
            </li>
            @endmodul

        
            <li class="sidebar-divider">QC & Proses</li>
            
            @modul('parameter_uji')
            <li class="{{ request()->is('parameter-uji*') ? 'active' : '' }}">
                <a href="{{ route('parameter-uji.index') }}"><i class="fas fa-vial"></i> Parameter Uji</a>
            </li>
            @endmodul

            @modul('proses_hasil')
            <li class="{{ request()->routeIs('kegiatan.*') ? 'active' : '' }}">
                <a href="{{ route('kegiatan.index') }}"><i class="fas fa-clipboard-list"></i> Proses & Hasil Pengujian</a>
            </li>
            @endmodul

            @modul('tindak_lanjut')
            <li class="{{ request()->is('tindak-lanjut*') ? 'active' : '' }}">
                <a href="{{ route('tindak-lanjut.index') }}"><i class="fas fa-clipboard-check"></i> Tindak Lanjut</a>
            </li>
            @endmodul

            {{-- Reporting --}}
            <li class="sidebar-divider">Reporting</li>
            @modul('reporting')
            <li class="{{ request()->is('reporting*') ? 'active' : '' }}">
                <a href="{{ route('reporting.index') }}"><i class="fas fa-chart-bar"></i> Laporan QC</a>
            </li>
            @endmodul
        </ul>
    </nav>
    @endif

    <div class="top-navbar shadow-sm">
        <div class="d-flex align-items-center">
            @if($useSidebar)
            <button class="btn text-white me-3 d-flex align-items-center justify-content-center p-1" id="sidebarToggleBtn" style="border:1px solid rgba(255,255,255,0.3); border-radius:6px; background:rgba(0,0,0,0.1); width:36px; height:36px;" onclick="document.body.classList.toggle('sidebar-toggled')">
                <i class="fas fa-bars"></i>
            </button>
            @endif
            <div>
                <span class="text-uppercase text-secondary fs-7 fw-bold d-block mb-1" style="font-size: 18px; letter-spacing: 1px;">SIGMA-LAB</span>
                <div class="mb-0 fw-bold d-none d-sm-block">Sistem Integrasi Manajemen Laboratorium PT Sucofindo</div>
            </div>
        </div>
        <div class="d-flex align-items-center">
            @if(isset($pendingPengadaan) && $pendingPengadaan > 0)
                <a href="{{ route('pengadaan.index') }}" class="btn btn-warning position-relative me-3 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;" title="{{ $pendingPengadaan }} Pengajuan Pengadaan">
                    <i class="fas fa-bell text-dark"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
                        {{ $pendingPengadaan }}
                        <span class="visually-hidden">pengadaan belum diproses</span>
                    </span>
                </a>
            @endif
            <div class="dropdown">
            <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" title="Profil" style="border-color: rgba(255,255,255,0.2);">
                <i class="bi bi-person-circle me-1 text-white"></i> 
                <div class="d-none d-sm-flex flex-column text-start ms-1 me-1 text-white" style="line-height: 1.2;">
                    <span class="fw-bold" style="font-size: 0.9rem;">{{ Auth::user()->personil->nama_personil ?? Auth::user()->username ?? 'Pengguna' }}</span>
                    <small style="font-size: 0.75rem; color: rgba(255,255,255,0.85);">{{ Auth::user()->role->nama_role ?? '-' }}</small>
                </div>
                <span class="d-inline d-sm-none text-white">{{ substr(Auth::user()->personil->nama_personil ?? Auth::user()->username ?? 'U', 0, 5) }}</span>
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
    </div>

    <div id="content">
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

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
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
            
            targetContainer.style.opacity = '0.5';
            
            const formData = new FormData(form);
            const searchParams = new URLSearchParams(formData);
            const fetchUrl = url || `${form.action || window.location.pathname}?${searchParams.toString()}`;
            
            
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