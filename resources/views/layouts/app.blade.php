<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - SIGMALAB Sucofindo</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-sucofindo.png') }}?v=1">
   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: {{ auth()->check() ? '260px' : '0' }};
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
        #sidebar .sidebar-header:hover { background-color: #f8fafc; }

        #sidebar ul.components { padding: 20px 0; }
        
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
        
        .pagination svg, .card-body svg {
            width: 1rem !important; height: 1rem !important;
            max-width: 1rem !important; max-height: 1rem !important;
            display: inline-block;
        }
    </style>
</head>

<body>
    <script>
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
            <div class="sidebar-header d-flex justify-content-between align-items-center" onclick="toggleSidebar()" title="Klik untuk Buka/Tutup Sidebar" style="cursor:pointer;">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/logo-sucofindo.png') }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=SL&background=0D8ABC&color=fff';" alt="Logo" style="height: 40px; width: auto; object-fit: contain;">
                    <div style="line-height: 1.2;">
                        <span class="text-dark" style="font-size: 1.2rem; font-weight: 800; letter-spacing: -0.5px;">SIGMA LAB</span><br>
                        <small class="text-muted fw-bold" style="font-size: 0.7rem;">PT Sucofindo - Cilacap</small>
                    </div>
                </div>
                <i class="fas fa-chevron-left text-muted opacity-50"></i>
            </div>

            <ul class="list-unstyled components" id="sidebar-accordion" style="overflow-y: auto; max-height: calc(100vh - 80px);">
                <li class="{{ request()->is('/') || request()->is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') ?? url('/') }}"><i class="fas fa-home"></i> Dashboard</a>
                </li>

                {{-- 1. Manajemen Peralatan
                @if(Auth::check() && (Auth::user()->hasModulAccess('alat') || (Auth::user()->role && Auth::user()->role->nama_role == 'HR & GA')))
                <li class="{{ request()->is('alat*') || request()->routeIs('inventori.monitoring.*') ? 'active' : '' }}">
                    <a href="{{ route('alat.index') }}"><i class="fas fa-tools"></i> Manajemen Peralatan</a>
                </li>
                @endif --}}
                {{-- 1. Peralatan & Monitoring --}}
                @if(Auth::check() && (Auth::user()->hasModulAccess('alat') || (Auth::user()->role && Auth::user()->role->nama_role == 'HR & GA')))
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ request()->is('alat*') || request()->routeIs('inventori.monitoring.*') ? 'active text-primary fw-bold' : 'collapsed' }}" 
                       data-bs-toggle="collapse" 
                       href="#menuManajemenPeralatan" 
                       role="button" 
                       aria-expanded="{{ request()->is('alat*') || request()->routeIs('inventori.monitoring.*') ? 'true' : 'false' }}" 
                       aria-controls="menuManajemenPeralatan">
                        <span><i class="fas fa-tools me-2"></i> Peralatan & Monitoring</span>
                        <i class="fas fa-chevron-down small"></i>
                    </a>
                
                    <div class="collapse {{ request()->is('alat*') || request()->routeIs('inventori.monitoring.*') ? 'show' : '' }}" id="menuManajemenPeralatan">
                        <ul class="nav flex-column ms-3 ps-2 border-start mt-1" style="font-size: 0.9rem;">
                            <li class="nav-item">
                                <a class="nav-link py-1 {{ request()->is('alat*') && !request()->routeIs('inventori.monitoring.*') ? 'text-primary fw-bold' : 'text-muted' }}" href="{{ route('alat.index') }}">
                                    <span style="font-size: 14px; line-height: 1;" class="me-2">•</span>Data Alat & Kalibrasi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-1 {{ request()->routeIs('inventori.monitoring.*') ? 'text-primary fw-bold' : 'text-muted' }}" href="{{ route('inventori.monitoring.index') }}">
                                    <span style="font-size: 14px; line-height: 1;" class="me-2">•</span>Monitoring Ruangan
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                
                {{-- 2. Personil & Kompetensi --}}
                @if(Auth::check() && (Auth::user()->hasModulAccess('sdm') || Auth::user()->hasModulAccess('manajemen_pengguna')))
                <li class="{{ request()->is('sdm*') || request()->is('hak-akses*') ? 'active' : '' }}">
                    <a href="{{ route('sdm.index') }}"><i class="fas fa-users"></i> Personil & Kompetensi</a>
                </li>
                @endif

                {{-- 3. Verifikasi Mutu (QC) --}}
                @if(Auth::check() && (Auth::user()->hasModulAccess('parameter_uji') || Auth::user()->hasModulAccess('proses_hasil') || Auth::user()->hasModulAccess('tindak_lanjut') || Auth::user()->hasModulAccess('reporting')))
                <li class="{{ request()->is('parameter-uji*') || request()->is('kegiatan*') || request()->is('tindak-lanjut*') || request()->is('reporting*') ? 'active' : '' }}">
                    <a href="{{ route('kegiatan.index') }}"><i class="fas fa-flask"></i> Verifikasi Mutu (QC)</a>
                </li>
                @endif

                {{-- 4. Inventori Bahan/Barang --}}
                @if(Auth::check() && (Auth::user()->hasModulAccess('barang') || Auth::user()->hasModulAccess('pengadaan')))
                <li class="{{ request()->is('barang*') || request()->is('pengadaan*') ? 'active' : '' }}">
                    <a href="{{ route('barang.index') }}"><i class="fas fa-boxes"></i> Inventori Bahan/Barang</a>
                </li>
                @endif

                {{-- 5. Library Digital --}}
                @if(Auth::check() && Auth::user()->hasModulAccess('library_manage'))
                <li class="{{ request()->is('library*') ? 'active' : '' }}">
                    <a href="{{ route('library.index') }}"><i class="fas fa-book-open"></i> Library Digital</a>
                </li>
                @endif

                {{-- 6. Audit Trail --}}
                @if(Auth::check() && Auth::user()->hasModulAccess('audit_log'))
                <li class="{{ request()->is('audit-log*') ? 'active' : '' }}">
                    <a href="{{ route('audit-log.index') }}"><i class="fas fa-history"></i> Audit Trail</a>
                </li>
                @endif
            </ul>
        </nav>
    @endauth

    {{-- TOP NAVBAR UTAMA --}}
    <div class="top-navbar shadow-sm">
        <div class="d-flex align-items-center">
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
            @auth
            {{-- Dropdown Notifikasi --}}
            <div class="dropdown me-3">
                <a href="#" class="btn btn-warning position-relative rounded-circle p-2 d-flex align-items-center justify-content-center dropdown-toggle" style="width: 38px; height: 38px;" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell text-white"></i>
                    @if(isset($unreadNotifCount) && $unreadNotifCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
                            {{ $unreadNotifCount > 99 ? '99+' : $unreadNotifCount }}
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" style="width: 300px; max-height: 400px; overflow-y: auto;">
                    <li><h6 class="dropdown-header">Notifikasi Terbaru</h6></li>
                    @if(isset($recentNotifs) && $recentNotifs->count() > 0)
                        @foreach($recentNotifs as $notif)
                            <li>
                                <a class="dropdown-item d-flex align-items-start py-2 border-bottom text-wrap" href="#">
                                    <div class="me-3 mt-1">
                                        @if($notif->jenis_notifikasi == 'qc')
                                            <i class="fas fa-flask text-primary"></i>
                                        @elseif($notif->jenis_notifikasi == 'kalibrasi')
                                            <i class="fas fa-tools text-warning"></i>
                                        @elseif($notif->jenis_notifikasi == 'stok')
                                            <i class="fas fa-box text-success"></i>
                                        @elseif($notif->jenis_notifikasi == 'sertifikasi')
                                            <i class="fas fa-certificate text-danger"></i>
                                        @else
                                            <i class="fas fa-bell text-secondary"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0" style="font-size: 0.85rem;">{{ \Illuminate\Support\Str::limit($notif->pesan, 80) }}</p>
                                        <small class="text-muted" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</small>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li><span class="dropdown-item text-center text-muted py-3">Tidak ada notifikasi baru</span></li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center text-primary fw-bold" href="{{ route('notifikasi.index') }}">Lihat Semua Notifikasi</a></li>
                </ul>
            </div>

            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" title="Profil" style="border-color: rgba(255,255,255,0.2);">
                    <i class="bi bi-person-circle me-1 text-white"></i> 
                    <div class="d-none d-sm-flex flex-column text-start ms-1 me-1 text-white" style="line-height: 1.2;">
                        <span class="fw-bold" style="font-size: 0.9rem;">{{ Auth::user()->personil->nama_personil ?? Auth::user()->username ?? 'Pengguna' }}</span>
                        <small style="font-size: 0.75rem; color: rgba(255,255,255,0.85);">{{ Auth::user()->role->nama_role ?? '-' }}</small>
                    </div>
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
            @endauth
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-toggled');
            if (window.innerWidth >= 992) {
                const isCollapsed = document.body.classList.contains('sidebar-toggled');
                localStorage.setItem('desktopSidebarState', isCollapsed ? 'collapsed' : 'expanded');
            }
        }
    </script>

    <script>
    function confirmDelete(button, customText) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: customText || 'Data akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-confirm-delete]');
        if (!btn) return;
        e.preventDefault();
        confirmDelete(btn, btn.dataset.confirmDelete || undefined);
    });
    </script>

    <script>
        $(document).ready(function() {
            $('.select2-alat, #selectAlat').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '-- Pilih Alat --',
                allowClear: true
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    @stack('scripts')
</body>
</html>