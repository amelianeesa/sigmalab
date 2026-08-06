<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIGMA LAB</title>
    <!-- Menggunakan Tailwind CSS untuk styling cepat dan rapi -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">

    <!-- Container Utama Dashboard -->
    <div class="min-h-screen p-6 lg:p-8 max-w-7xl mx-auto">
        
        <!-- Header Dashboard -->
        <header class="flex justify-between items-center mb-8 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-sm text-gray-500">Ringkasan operasional dan menu navigasi laboratorium hari ini</p>
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

        <!-- Barisan Kartu Statistik Atas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Statistik 1 -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">TOTAL ALAT</p>
                <h3 class="text-3xl font-extrabold text-gray-800">4</h3>
            </div>
            <!-- Statistik 2 -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">KALIBRASI KADALUARSA</p>
                <h3 class="text-3xl font-extrabold text-amber-600">3</h3>
            </div>
            <!-- Statistik 3 -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">PERSONIL PERLU SERTIFIKASI</p>
                <h3 class="text-3xl font-extrabold text-indigo-600">2</h3>
            </div>
            <!-- Statistik 4 -->
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">AKSI TERCATAT (AUDIT)</p>
                <h3 class="text-3xl font-extrabold text-gray-800">1</h3>
            </div>
        </div>

        <!-- Bagian Kotak Navigasi Menu Utama (Pengganti Peringatan) -->
        <div class="mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span>⛭</span> Menu Navigasi Modul Laboratorium
            </h2>
            
            <!-- Grid Tombol Modul -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                
                <!-- Modul 1: Aset & Kalibrasi -->
                <a href="#aset-kalibrasi" class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all group flex flex-col justify-between">
                    <div>
                        <div class="text-2xl mb-2">⛭</div>
                        <h3 class="font-bold text-gray-800 group-hover:text-indigo-600">Aset & Kalibrasi</h3>
                        <p class="text-xs text-gray-400 mt-1">Kelola data alat dan jadwal kalibrasi</p>
                    </div>
                    <span class="text-xs font-semibold text-indigo-600 mt-4 flex items-center gap-1">Buka Modul &rarr;</span>
                </a>

                <!-- Modul 2: QC & Parameter Uji -->
                <a href="#qc-parameter" class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all group flex flex-col justify-between">
                    <div>
                        <div class="text-2xl mb-2">▣</div>
                        <h3 class="font-bold text-gray-800 group-hover:text-indigo-600">QC & Parameter Uji</h3>
                        <p class="text-xs text-gray-400 mt-1">Kontrol kualitas dan parameter pengujian</p>
                    </div>
                    <span class="text-xs font-semibold text-indigo-600 mt-4 flex items-center gap-1">Buka Modul &rarr;</span>
                </a>

                <!-- Modul 3: Inventori Bahan -->
                <a href="#inventori" class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all group flex flex-col justify-between">
                    <div>
                        <div class="text-2xl mb-2">◔</div>
                        <h3 class="font-bold text-gray-800 group-hover:text-indigo-600">Inventori Bahan</h3>
                        <p class="text-xs text-gray-400 mt-1">Stok bahan kimia dan reagen lab</p>
                    </div>
                    <span class="text-xs font-semibold text-indigo-600 mt-4 flex items-center gap-1">Buka Modul &rarr;</span>
                </a>

                <!-- Modul 4: SDM (Bagianmu) -->
                <a href="{{ route('sdm.index') }}" class="bg-white p-6 rounded-xl shadow-sm border-2 border-indigo-600 hover:border-indigo-600 transition-all group flex flex-col justify-between md:col-span-2">
                    <div>
                        <div class="text-3xl mb-3 text-indigo-600">👤</div>
                        <h3 class="font-bold text-indigo-700 group-hover:text-indigo-800 text-lg">SDM</h3>
                        <p class="text-sm text-gray-500 mt-2">Kelola personil, kompetensi, & pelatihan</p>
                    </div>
                    <span class="text-sm font-semibold text-indigo-600 mt-4 flex items-center gap-1">Buka Modul &rarr;</span>
                </a>

                <!-- Modul 5: Audit Log -->
                <a href="#audit-log" class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 hover:border-indigo-500 hover:shadow-md transition-all group flex flex-col justify-between">
                    <div>
                        <div class="text-2xl mb-2">📋</div>
                        <h3 class="font-bold text-gray-800 group-hover:text-indigo-600">Audit Log</h3>
                        <p class="text-xs text-gray-400 mt-1">Rekam jejak aktivitas sistem lab</p>
                    </div>
                    <span class="text-xs font-semibold text-indigo-600 mt-4 flex items-center gap-1">Buka Modul &rarr;</span>
                </a>

            </div>
        </div>

        <!-- Contoh Tampilan Konten di Bawah (Aktivitas Terbaru) -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-2">Aktivitas Terbaru Sistem</h3>
            <p class="text-sm text-gray-400">Belum ada aktivitas tercatat pada hari ini.</p>
        </div>

    </div>

</body>
</html>