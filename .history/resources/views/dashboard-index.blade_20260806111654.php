<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utama - SIGMA LAB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Fungsi sederhana untuk simulasi berpindah tampilan saat menu diklik
        function bukaModul(namaModul) {
            if (namaModul === 'sdm') {
                document.getElementById('main-menu-view').classList.add('hidden');
                document.getElementById('sdm-submenu-view').classList.remove('hidden');
            }
        }
        function kembaliKeDashboard() {
            document.getElementById('sdm-submenu-view').classList.add('hidden');
            document.getElementById('main-menu-view').classList.remove('hidden');
        }
    </script>
    <style>
        :root{
            --sdm-500: #3c7ebd;
            --sdm-600: #2f6aa9;
            --sdm-700: #0c3a68;
            --sdm-50: #f4f7fb;
        }
        /* SDM helper classes to mirror styles used in SDM pages */
        .sdm-avatar{ background:var(--sdm-700); }
        .sdm-badge{ background: rgba(60,126,189,0.12); color: var(--sdm-700); }
        .sdm-border{ border-color: var(--sdm-500); }
        .sdm-tile{ transition: all .15s ease; }
        .sdm-tile:hover{ background: rgba(60,126,189,0.06); border-color: var(--sdm-600); box-shadow: 0 8px 20px rgba(15,35,59,0.06); }
        .sdm-link{ color:var(--sdm-500); font-weight:700; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">

    <!-- Container Utama -->
    <div class="min-h-screen p-6 lg:p-10 max-w-6xl mx-auto">
        <header class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full sdm-avatar flex items-center justify-center text-white font-bold">{{ strtoupper(substr(Auth::user()->name ?? 'U',0,1)) }}</div>
                <div>
                    <div class="text-sm text-slate-600">Masuk sebagai</div>
                    <div class="text-base font-semibold">{{ Auth::user()->name ?? 'Pengguna' }}</div>
                </div>
            </div>
            <div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
                </form>
            </div>
        </header>
        
        <!-- Header Sederhana -->
        <header class="flex justify-between items-center mb-10 bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">SIGMA LAB</h1>
                <p class="text-sm text-gray-500">Sistem Integrasi Laboratorium PT Sucofindo Cabang Cilacap</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="bg-indigo-600 text-white font-semibold w-10 h-10 rounded-full flex items-center justify-center shadow">
                    AD
                </div>
                <div>
                    <div class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        Admin Sistem 
                        <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded font-medium">ADMIN</span>
                    </div>
                    <p class="text-xs text-gray-400">Login sebagai: Admin</p>
                </div>
            </div>
        </header>

        <!-- VIEW 1: KOTAK MENU UTAMA (DASHBOARD) -->
        <div id="main-menu-view">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-1">Pilih Modul Laboratorium</h2>
                <p class="text-sm text-gray-500">Silakan klik salah satu kotak modul di bawah untuk mengelola data.</p>
            </div>

            <!-- Grid Kotak Fitur Utama -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- 1. Aset & Kalibrasi -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all cursor-pointer flex flex-col justify-between">
                    <div>
                        <div class="text-3xl mb-3">⛭</div>
                        <h3 class="text-lg font-bold text-gray-800">Aset & Kalibrasi</h3>
                        <p class="text-xs text-gray-500 mt-1">Kelola data alat laboratorium dan jadwal kalibrasi berkala.</p>
                    </div>
                    <span class="text-xs font-semibold text-indigo-600 mt-6 flex items-center gap-1">Buka Modul &rarr;</span>
                </div>

                <!-- 2. QC & Parameter Uji -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all cursor-pointer flex flex-col justify-between">
                    <div>
                        <div class="text-3xl mb-3">▣</div>
                        <h3 class="text-lg font-bold text-gray-800">QC & Parameter Uji</h3>
                        <p class="text-xs text-gray-500 mt-1">Kontrol kualitas pengujian dan daftar parameter uji lab.</p>
                    </div>
                    <span class="text-xs font-semibold text-indigo-600 mt-6 flex items-center gap-1">Buka Modul &rarr;</span>
                </div>

                <!-- 3. Inventori Bahan -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all cursor-pointer flex flex-col justify-between">
                    <div>
                        <div class="text-3xl mb-3">◔</div>
                        <h3 class="text-lg font-bold text-gray-800">Inventori Bahan</h3>
                        <p class="text-xs text-gray-500 mt-1">Pengelolaan stok bahan kimia, reagen, dan bahan pendukung.</p>
                    </div>
                    <span class="text-xs font-semibold text-indigo-600 mt-6 flex items-center gap-1">Buka Modul &rarr;</span>
                </div>

                <!-- 4. SDM (DIKLIK MENU INI) -->
                <div onclick="bukaModul('sdm')" class="bg-white p-6 rounded-2xl shadow-sm border border-2 border-indigo-500 shadow-md hover:bg-indigo-50/30 transition-all cursor-pointer flex flex-col justify-between">
                    <div>
                        <div class="text-3xl mb-3">👤</div>
                        <h3 class="text-lg font-bold text-indigo-700">SDM</h3>
                        <p class="text-xs text-gray-500 mt-1">Kelola data personil, kompetensi, sertifikasi, dan hak akses.</p>
                    </div>
                    <span class="text-xs font-semibold text-indigo-600 mt-6 flex items-center gap-1">Buka Modul &rarr;</span>
                </div>

                <!-- 5. Audit Log -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all cursor-pointer flex flex-col justify-between">
                    <div>
                        <div class="text-3xl mb-3">📋</div>
                        <h3 class="text-lg font-bold text-gray-800">Audit Log</h3>
                        <p class="text-xs text-gray-500 mt-1">Rekam jejak aktivitas sistem dan histori perubahan data.</p>
                    </div>
                    <span class="text-xs font-semibold text-indigo-600 mt-6 flex items-center gap-1">Buka Modul &rarr;</span>
                </div>

            </div>
        </div>


        <!-- VIEW 2: KETIKA KOTAK "SDM" DIKLIK (MUNCUL FITUR-FITUR SDM) -->
        <div id="sdm-submenu-view" class="hidden">
            <!-- Tombol Kembali -->
            <button onclick="kembaliKeDashboard()" class="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-200">
                &larr; Kembali ke Menu Utama
            </button>

            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-1">Modul Sumber Daya Manusia (SDM)</h2>
                <p class="text-sm text-gray-500">Pilih sub-fitur di bawah ini untuk mengelola data personil laboratorium.</p>
            </div>

            <!-- Grid Sub-Fitur SDM -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Sub-Fitur 1 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all cursor-pointer">
                    <div class="text-2xl mb-2">👥</div>
                    <h3 class="font-bold text-gray-800">Data Master Personil</h3>
                    <p class="text-xs text-gray-400 mt-1">Tambah, ubah, dan hapus profil pegawai & analis lab.</p>
                </div>

                <!-- Sub-Fitur 2 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all cursor-pointer">
                    <div class="text-2xl mb-2">📜</div>
                    <h3 class="font-bold text-gray-800">Kompetensi & Sertifikasi</h3>
                    <p class="text-xs text-gray-400 mt-1">Catat nomor sertifikat dan masa berlaku izin pengujian.</p>
                </div>

                <!-- Sub-Fitur 3 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all cursor-pointer">
                    <div class="text-2xl mb-2">🎓</div>
                    <h3 class="font-bold text-gray-800">Riwayat Pelatihan</h3>
                    <p class="text-xs text-gray-400 mt-1">Rekam jejak diklat dan pelatihan teknis pegawai.</p>
                </div>

                <!-- Sub-Fitur 4 -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all cursor-pointer">
                    <div class="text-2xl mb-2">🔐</div>
                    <h3 class="font-bold text-gray-800">Manajemen Pengguna & Role</h3>
                    <p class="text-xs text-gray-400 mt-1">Kelola akun login dan hak akses (RBAC) modul sistem.</p>
                </div>

            </div>
        </div>

    </div>

</body>
</html>