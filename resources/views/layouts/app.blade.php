<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - SIGMALAB Sucofindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        :root{
            --sdm-50: #eef0f1;
            --sdm-500: #1d4c7a;
            --sdm-600: #1d4c7a;
            --sdm-700: #163d63;
        }

        @php
            $useSidebar = auth()->check();
        @endphp
        
        #sidebar { 
            min-width: 260px; max-width: 260px; 
            height: 100vh; position: fixed; top: 0; left: 0; 
            background-color: #ffffff; color: #334155; z-index: 1040;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 24px rgba(0,0,0,0.03);
            border-right: none;
        }

        #content { 
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-left: {{ $useSidebar ? '260px' : '0' }}; 
            padding: 24px; 
            padding-top: 20px; 
            min-height: 100vh; 
        }

        .top-navbar {
            background: var(--sdm-600);
            padding: 12px 28px;
            border-bottom: 1px solid var(--sdm-700);
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-left: {{ $useSidebar ? '260px' : '0' }};
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1020;
            min-height: 64px;
            color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        body.sidebar-toggled #sidebar { transform: translateX(-100%); }
        body.sidebar-toggled #content { margin-left: 0; }
        body.sidebar-toggled .top-navbar { margin-left: 0; }

        @media (max-width: 991.98px) {
            #sidebar { transform: translateX(-100%); }
            #content { margin-left: 0; }
            .top-navbar { margin-left: 0; }
            body.sidebar-toggled #sidebar { transform: translateX(0); box-shadow: 0 0 15px rgba(0,0,0,0.1); }
            body.sidebar-toggled #content { margin-left: 0; }
            body.sidebar-toggled .top-navbar { margin-left: 0; }
            body.sidebar-toggled #sidebar-overlay { display: block; }
        }

        #sidebar-overlay { display: none; position: fixed; width: 100vw; height: 100vh; background: rgba(15,23,42,0.4); z-index: 1030; top: 0; left: 0; cursor: pointer; transition: opacity .2s ease; backdrop-filter: blur(2px); }
        
        #sidebar .sidebar-header { 
            padding: 22px 20px; 
            border-bottom: 1px solid #f1f5f9; 
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        #sidebar .sidebar-header:hover {
            background-color: #f8fafc;
        }

        #sidebar ul.components { padding: 20px 0; }
        
        /* Premium Menu Item Styles */
        #sidebar ul li a { 
            padding: 12px 20px 12px 24px; 
            font-size: 0.92rem; 
            font-weight: 500;
            display: flex; align-items: center; gap: 12px;
            color: #64748b; 
            text-decoration: none; 
            transition: all 0.2s ease; 
            border-left: 3px solid transparent; 
            margin-right: 12px; 
            border-radius: 0 8px 8px 0; 
            margin-bottom: 2px;
        }
        
        #sidebar ul li a:hover { 
            color: #2563eb; 
            background: #eff6ff; 
            transform: translateX(4px); 
        }
        
        #sidebar ul li.active > a { 
            color: #1d4ed8; 
            background: #eff6ff; 
            border-left-color: #2563eb; 
            font-weight: 600; 
        }
        
        #sidebar ul li a i { font-size: 1.1rem; opacity: 0.75; margin-right: 10px; }
        #sidebar ul li a:hover i, #sidebar ul li.active > a i { opacity: 1; color: #2563eb; }
        
        /* Subtle Dividers */
        #sidebar .sidebar-divider { 
            font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.2px; 
            color: #94a3b8; padding: 18px 24px 8px; margin-top: 5px; 
        }

        /* Sidebar Dropdown Accordion */
        #sidebar ul li > a.dropdown-toggle::after {
            display: inline-block;
            margin-left: 0.255em;
            vertical-align: 0.255em;
            content: "\f107"; /* FontAwesome caret-down */
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            border: none;
            transition: transform 0.2s ease;
        }
        #sidebar ul li > a.dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(-180deg);
        }
        #sidebar ul.collapse li a {
            padding-left: 3rem !important;
            font-size: 0.92rem;
            background: #f8fafc;
            border-left: 4px solid transparent;
        }
        #sidebar ul.collapse li.active > a {
            background: #eff6ff;
            border-left-color: #2563eb;
            color: #1d4ed8;
            font-weight: 600;
        }
        #sidebar ul.collapse li a:hover {
            transform: none;
            padding-left: 3.25rem !important;
            transition: padding-left 0.2s ease, background 0.2s ease, color 0.2s ease;
        }
        
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
        .pagination svg, 
        .card-body svg {
            width: 1rem !important;
            height: 1rem !important;
            max-width: 1rem !important;
            max-height: 1rem !important;
            display: inline-block;
        }
        /* .card-body > div:not(.table-responsive):not(.mt-3) {
            display: none !important;
        } */

        /* .card-body > div:has(.pagination) > nav:first-child,
        .card-body > nav:first-of-type:has(a.previous),
        .card-body > div > div:has(> a.previous) {
            display: none !important;
            
        } */

        .card-body > div > div.d-flex.justify-content-between.flex-fill.align-items-center.d-sm-none,
        .card-body > div > nav > div.d-flex.justify-content-between.flex-fill.d-sm-none {
            display: none !important;
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
    <script>
        // Mencegah FOUC (Flash of Unstyled Content) saat load/refresh
        // Script ini dieksekusi sebelum elemen lain dimuat
        if (window.innerWidth >= 992) {
            const sidebarState = localStorage.getItem('desktopSidebarState');
            if (sidebarState === 'collapsed') {
                document.body.classList.add('sidebar-toggled');
            }
        }
    </script>
    @auth
        <div id="sidebar-overlay" onclick="toggleSidebar()"></div>
        <nav id="sidebar">
            <!-- Fungsi toggle dipindah ke header ini -->
            <div class="sidebar-header d-flex justify-content-between align-items-center" onclick="toggleSidebar()" title="Klik untuk Buka/Tutup Sidebar" style="cursor:pointer;">
                <div class="d-flex align-items-center gap-2">
                    <!-- Atribut onerror ditambahkan untuk memunculkan placeholder jika gambar gagal dimuat -->
                    <img src="{{ asset('images/logo-sucofindo.png') }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=SL&background=0D8ABC&color=fff';" alt="Logo" style="height: 40px; width: auto; object-fit: contain;">
                    <div style="line-height: 1.2;">
                        <span class="text-dark" style="font-size: 1.2rem; font-weight: 800; letter-spacing: -0.5px;">SIGMA LAB</span><br>
                        <small class="text-muted fw-bold" style="font-size: 0.7rem;">PT Sucofindo - Cilacap</small>
                    </div>
                </div>
                <!-- Ikon panah kecil opsional untuk memperjelas interaksi -->
                <i class="fas fa-chevron-left text-muted opacity-50"></i>
            </div>

            <ul class="list-unstyled components" id="sidebar-accordion" style="overflow-y: auto; max-height: calc(100vh - 80px);">
                <li class="{{ request()->is('/') || request()->is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') ?? url('/') }}"><i class="fas fa-home"></i> Dashboard</a>
                </li>

                {{-- 1. Manajemen Peralatan (Aset) --}}
                @if(Auth::check() && Auth::user()->hasModulAccess('alat'))
                <li class="{{ request()->is('alat*') ? 'active' : '' }}">
                    <a href="{{ route('alat.index') }}"><i class="fas fa-tools"></i> Manajemen Peralatan</a>
                </li>
                @endif

                {{-- 2. Personel dan Kompetensi --}}
                @if(Auth::check() && (Auth::user()->hasModulAccess('sdm') || Auth::user()->hasModulAccess('manajemen_pengguna')))
                <li class="{{ request()->is('sdm*') || request()->is('hak-akses*') ? 'active' : '' }}">
                    <a href="{{ route('sdm.index') }}"><i class="fas fa-users"></i> Personel & Kompetensi</a>
                </li>
                @endif

                {{-- 3. Proses dan Hasil Pengujian (QC) --}}
                @if(Auth::check() && (Auth::user()->hasModulAccess('parameter_uji') || Auth::user()->hasModulAccess('proses_hasil') || Auth::user()->hasModulAccess('tindak_lanjut') || Auth::user()->hasModulAccess('reporting')))
                <li class="{{ request()->is('parameter-uji*') || request()->is('kegiatan*') || request()->is('tindak-lanjut*') || request()->is('reporting*') ? 'active' : '' }}">
                    <a href="{{ route('kegiatan.index') }}"><i class="fas fa-flask"></i> Verifikasi Mutu (QC)</a>
                </li>
                @endif

                {{-- 4. Inventori & Fasilitas --}}
                @if(Auth::check() && (Auth::user()->hasModulAccess('barang') || Auth::user()->hasModulAccess('pengadaan')))
                <li class="{{ request()->is('barang*') || request()->is('pengadaan*') ? 'active' : '' }}">
                    <a href="{{ route('barang.index') }}"><i class="fas fa-boxes"></i> Inventori & Fasilitas</a>
                </li>
                @endif

                {{-- 5. Audit Log --}}
                @if(Auth::check() && Auth::user()->hasModulAccess('audit_log'))
                <li class="{{ request()->is('audit-log*') ? 'active' : '' }}">
                    <a href="{{ route('audit-log.index') }}"><i class="fas fa-history"></i> Audit Trail</a>
                </li>
                @endif
            </ul>
        </nav>
    @endauth

    <div class="top-navbar shadow-sm">
        <div class="d-flex align-items-center">
            <!-- Class d-lg-none dihapus agar toggle navbar selalu muncul untuk mengembalikan sidebar -->
            @auth
                <button class="btn text-white me-3 d-flex align-items-center justify-content-center p-1" onclick="toggleSidebar()" style="border:1px solid rgba(255,255,255,0.3); border-radius:6px; background:rgba(0,0,0,0.1); width:36px; height:36px;">
                    <i class="fas fa-bars"></i>
                </button>
            @endauth
            <div>
                <span class="text-uppercase text-secondary fs-7 fw-bold d-block mb-1" style="font-size: 18px; letter-spacing: 1px;">SIGMA-LAB</span>
                <div class="mb-0 fw-bold d-none d-sm-block">Sistem Integrated General Management Analytics of Lab</div>
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
    
    <!-- Sidebar State Script -->
    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-toggled');
            // Hanya simpan state untuk tampilan desktop (>= 992px)
            if (window.innerWidth >= 992) {
                const isCollapsed = document.body.classList.contains('sidebar-toggled');
                localStorage.setItem('desktopSidebarState', isCollapsed ? 'collapsed' : 'expanded');
            }
        }
    </script>

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