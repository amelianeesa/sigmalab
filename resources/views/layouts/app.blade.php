<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - SIGMALAB Sucofindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: {{ auth()->check() ? '260px' : '0' }};
        }
    </style>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
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
            @if(Auth::check() && Auth::user()->hasModulAccess('sdm'))
            <li class="{{ request()->is('sdm*') ? 'active' : '' }}">
                <a href="{{ route('sdm.index') }}"><i class="fas fa-users"></i> Personel & Kompetensi</a>
            </li>
            @endif

            {{-- 3. Proses dan Hasil Pengujian (QC) --}}
            @if(Auth::check() && (Auth::user()->hasModulAccess('parameter_uji') || Auth::user()->hasModulAccess('proses_hasil') || Auth::user()->hasModulAccess('tindak_lanjut') || Auth::user()->hasModulAccess('reporting')))
            <li class="{{ request()->is('parameter-uji*') || request()->is('kegiatan*') || request()->is('inhouse-control*') || request()->is('tindak-lanjut*') || request()->is('reporting*') ? 'active' : '' }}">
                <a href="{{ route('kegiatan.index') }}"><i class="fas fa-flask"></i> Verifikasi Mutu (QC)</a>
            </li>
            @endif

            {{-- 4. Inventori & Fasilitas --}}
            @if(Auth::check() && (Auth::user()->hasModulAccess('barang') || Auth::user()->hasModulAccess('pengadaan')))
            <li class="{{ request()->is('barang*') || request()->is('pengadaan*') ? 'active' : '' }}">
                <a href="{{ Auth::user()->hasModulAccess('barang') ? route('barang.index') : route('pengadaan.index') }}"><i class="fas fa-boxes"></i> Inventori & Fasilitas</a>
            </li>
            @endif

            {{-- 5. Audit Log --}}
            @if(Auth::check() && Auth::user()->hasModulAccess('audit_log'))
            <li class="{{ request()->is('audit-log*') ? 'active' : '' }}">
                <a href="{{ route('audit-log.index') }}"><i class="fas fa-history"></i> Audit Trail</a>
            </li>
            @endif

            {{-- 6. Pengaturan Sistem --}}
            @if(Auth::check() && Auth::user()->hasModulAccess('manajemen_pengguna'))
            <li class="{{ request()->is('hak-akses*') ? 'active' : '' }}">
                <a href="{{ route('hak-akses.index') }}"><i class="fas fa-user-shield"></i> Pengaturan Akses</a>
            </li>
            @endif
        </ul>
    </nav>

    <div class="top-navbar shadow-sm">
        <div class="d-flex align-items-center">
            <!-- Toggle ini hanya muncul di HP/Mobile (d-lg-none) -->
            <button class="btn text-white me-3 d-flex d-lg-none align-items-center justify-content-center p-1" onclick="toggleSidebar()" style="border:1px solid rgba(255,255,255,0.3); border-radius:6px; background:rgba(0,0,0,0.1); width:36px; height:36px;">
                <i class="fas fa-bars"></i>
            </button>
            <div>
                <span class="text-uppercase text-secondary fs-7 fw-bold d-block mb-1" style="font-size: 18px; letter-spacing: 1px;">SIGMA-LAB</span>
                <div class="mb-0 fw-bold d-none d-sm-block">Sistem Integrated General Management Analytics of Lab</div>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <div class="dropdown me-3">
                <a href="#" class="btn btn-warning position-relative rounded-circle p-2 d-flex align-items-center justify-content-center dropdown-toggle" style="width: 38px; height: 38px;" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell text-dark"></i>
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

    <!-- Global Delete Confirmation (SweetAlert2) -->
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
    // Delegated listener for buttons with data-confirm-delete attribute
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-confirm-delete]');
        if (!btn) return;
        e.preventDefault();
        confirmDelete(btn, btn.dataset.confirmDelete || undefined);
    });
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