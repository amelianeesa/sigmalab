@extends('layouts.app')

@section('content')
<style>
    .dashboard-container {
        padding: 2px 20px !important;
    }

    .dashboard-card {
        padding: 10px !important;
    }
    .dashboard-card h2 {
        font-size: 1.75rem !important;
    }
    .dashboard-card p {
        font-size: 0.75rem !important;
    }

    .info-card .card-body {
        padding: 10px !important;
    }
    .info-card h5 {
        font-size: 1rem !important;
    }

    /* Merampingkan bagian tracking progress pengadaan */
    .tracking-card .card-body {
        padding: 10px !important;
    }
</style>

<div class="container-fluid dashboard-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold text-dark mb-1" style="font-size: 1.5rem;">Monitoring Center</h2>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Selamat datang, Anda login sebagai <strong class="text-primary">{{ $role }}</strong></p>
        </div>
    </div>

    @php
        $userRole = Auth::user()->role->nama_role ?? '';
        $isGaOrKoor = in_array($userRole, [\App\Enums\PeranPengguna::GA_OFFICER->value, 'GA', 'GA_OFFICER', 'Koordinator Lab', 'Koordinator Laboratorium']);
    @endphp

    @if($isGaOrKoor)
        @foreach($pengadaanAktif as $p)
            @php
                $tglBuat = \Carbon\Carbon::parse($p->created_at);
                $sekarang = \Carbon\Carbon::now();

                $diff = $tglBuat->diff($sekarang);
                $formatHariBerjalan = '';
                if ($diff->y > 0) { $formatHariBerjalan .= $diff->y . ' tahun '; }
                if ($diff->m > 0) { $formatHariBerjalan .= $diff->m . ' bulan '; }
                if ($diff->d > 0 || $formatHariBerjalan == '') { $formatHariBerjalan .= $diff->d . ' hari'; }

                $hariBerjalanAngka = round($tglBuat->diffInDays($sekarang));

                $totalHariTarget = ($p->target_tahun * 365) + ($p->target_bulan * 30) + $p->target_hari;
                if ($totalHariTarget <= 0) {
                    $totalHariTarget = 1; 
                }

                $setengahTarget = $totalHariTarget / 2;

                // 3. Kondisi Peringatan
                $isSetengahJalan = ($hariBerjalanAngka >= $setengahTarget && $hariBerjalanAngka < $totalHariTarget) && in_array($p->status, ['disetujui', 'menunggu_ga', 'diproses']);
                $isTerlambat = ($hariBerjalanAngka >= $totalHariTarget) && ($p->status != 'selesai');
            @endphp

            @if($isTerlambat)
                <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-2 py-2 small" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-lg me-2"></i>
                        <div>
                            <strong>PERINGATAN KETERLAMBATAN:</strong> Pengadaan <b>{{ $p->barang->nama_barang ?? 'Barang' }}[{{ $p->barang->kode_barang ?? 'Tanpa Kode' }}]</b> telah berjalan selama <b>{{ trim($formatHariBerjalan) }}</b> dan melewati batas target waktu! Segera selesaikan.
                        </div>
                    </div>
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif($isSetengahJalan)
                <div class="alert alert-warning alert-dismissible fade show shadow-sm mb-2 py-2 small" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock fa-lg me-2"></i>
                        <div>
                            <strong>PENGINGAT GA (½ Target):</strong> Pengadaan <b>{{ $p->barang->nama_barang ?? 'Barang' }}</b> telah mencapai separuh waktu target (berjalan <b>{{ trim($formatHariBerjalan) }}</b>) dan belum diproses. Harap segera ditindaklanjuti.
                        </div>
                    </div>
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        @endforeach
    @endif

    <!-- 4 KARTU STATISTIK UTAMA -->
    <div class="row g-3 mb-3">
        <!-- Card 1: Outlier -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm border-start border-4 border-danger">
                <div class="card-body p-2 px-3">
                    <p class="text-muted mb-0 text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">OUTLIER (OPEN)</p>
                    <h3 class="fw-bold text-dark mb-0" style="font-size: 1.4rem; line-height: 1.1;">{{ $outliers }}</h3>
                    <a href="{{ route('tindak-lanjut.index') }}" class="text-decoration-none text-danger fw-semibold d-inline-block" style="font-size: 0.72rem;">
                        Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card 2: Pengujian Aktif -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm border-start border-4 border-info">
                <div class="card-body p-2 px-3">
                    <p class="text-muted mb-0 text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">PENGUJIAN AKTIF</p>
                    <h3 class="fw-bold text-dark mb-0" style="font-size: 1.4rem; line-height: 1.1;">{{ $kegiatanBerjalan }}</h3>
                    <a href="{{ route('kegiatan.index') }}" class="text-decoration-none text-info fw-semibold d-inline-block" style="font-size: 0.72rem;">
                        Buka Modul QC <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card 3: Peralatan & Kalibrasi -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm border-start border-4 border-warning">
                <div class="card-body p-2 px-3">
                    <p class="text-muted mb-0 text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">PERALATAN & KALIBRASI</p>
                    <h3 class="fw-bold text-dark mb-0" style="font-size: 1.4rem; line-height: 1.1;">{{ $tenggatKalibrasi }}</h3>
                    <a href="{{ route('alat.index') }}" class="text-decoration-none text-warning fw-semibold d-inline-block" style="font-size: 0.72rem;">
                        Kelola Aset <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card 4: Sertifikasi Personil -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm border-start border-4 border-primary">
                <div class="card-body p-2 px-3">
                    <p class="text-muted mb-0 text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px;">SERTIFIKASI PERSONIL H-6</p>
                    <h3 class="fw-bold text-dark mb-0" style="font-size: 1.4rem; line-height: 1.1;">{{ $sertifikasiHampirHabis }}</h3>
                    <a href="{{ route('sdm.index') }}" class="text-decoration-none text-primary fw-semibold d-inline-block" style="font-size: 0.72rem;">
                        Cek Sertifikasi <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 3 KARTU INFORMASI BAWAH -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm info-card">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">
                                <i class="fas fa-box-open text-danger me-2"></i> Stok Kritis
                            </h6>
                            <span class="badge bg-danger rounded-pill" style="font-size: 0.7rem;">{{ $stokTipis }}</span>
                        </div>
                        <p class="text-muted small mb-3" style="font-size: 0.78rem;">Terdapat {{ $stokTipis }} item barang/reagen di bawah batas minimum.</p>
                    </div>
                    <div>
                        <a href="{{ route('barang.index') }}" class="btn btn-outline-danger btn-sm w-100 py-1" style="font-size: 0.78rem;">Cek Inventori</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm info-card">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">
                                <i class="fas fa-calendar-times text-warning me-2"></i> Akan Kedaluwarsa
                            </h6>
                            <span class="badge bg-warning text-dark rounded-pill" style="font-size: 0.7rem;">{{ $barangExp }}</span>
                        </div>
                        <p class="text-muted small mb-3" style="font-size: 0.78rem;">Terdapat {{ $barangExp }} bahan/reagen kedaluwarsa dalam 30 hari.</p>
                    </div>
                    <div>
                        <a href="{{ route('barang.index') }}" class="btn btn-outline-warning btn-sm w-100 py-1" style="font-size: 0.78rem;">Cek Expired Date</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm info-card">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">
                                <i class="fas fa-shopping-cart text-primary me-2"></i> Approval Pengadaan
                            </h6>
                            <span class="badge bg-primary rounded-pill" style="font-size: 0.7rem;">{{ $pengadaanPending }}</span>
                        </div>
                        <p class="text-muted small mb-3" style="font-size: 0.78rem;">Ada {{ $pengadaanPending }} pengajuan yang butuh persetujuan.</p>
                    </div>
                    <div>
                        <a href="{{ route('pengadaan.index') ?? '#' }}" class="btn btn-outline-primary btn-sm w-100 py-1" style="font-size: 0.78rem;">Proses Pengajuan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- TRACKING STATUS PENGADAAN -->
{{-- <div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 tracking-card">
            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">
                    <i class="fas fa-route text-primary me-2"></i>Tracking Status Pengadaan Barang
                </h5>
                
                <select id="selectTracking" class="form-select form-select-sm w-auto select2-alat">
                    <option value="">-- Pilih Barang/Bahan --</option>
                    @foreach($pengadaanAktif as $p)
                        <option value="track-{{ $p->permintaan_id }}" {{ $loop->first ? 'selected' : '' }}>
                            {{ $p->barang->nama_barang ?? 'Barang' }} ({{ $p->barang->kode_barang ?? 'Tanpa Kode' }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="card-body p-3">
                @forelse($pengadaanAktif as $p)
                    <div id="track-{{ $p->permintaan_id }}" class="tracking-item" style="display: none;">
                        @php
                           $isTerlambat = false;
                           if ($p->status != 'selesai') {
                               $batasWaktu = \Carbon\Carbon::parse($p->created_at)->addDays(30); 
                               if (\Carbon\Carbon::now()->isAfter($batasWaktu)) {
                                   $isTerlambat = true;
                               }
                           }
                       @endphp
                       <div id="track-{{ $p->permintaan_id }}" class="tracking-item" style="display: none;">
                        <!-- Info Singkat Barang -->
                        <div class="row mb-3">
                            <div class="col-md-12 border-bottom pb-2">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h6 class="fw-bold mb-0 text-primary" style="font-size: 0.95rem;">
                                        {{ $p->barang->nama_barang ?? '-' }} ({{ $p->barang->kode_barang ?? 'Tanpa Kode' }})
                                    </h6>
                                    @if($isTerlambat)
                                        <span class="badge bg-danger animate-pulse" style="font-size: 0.68rem;">
                                            <i class="fas fa-exclamation-triangle me-1"></i> Terlambat
                                        </span>
                                    @endif
                                </div>
                                <div class="text-muted" style="font-size: 0.81rem;">
                                    <span>Jumlah: <b class="text-dark">{{ (float) $p->jumlah_diminta }} {{ $p->barang->satuan ?? '' }}</b></span><br>
                                    <span>Target Waktu Pengadaan: <b class="text-dark">{{ $p->format_target_waktu ?? '-' }}</b></span><br>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar & Steps -->
                        <div class="row align-items-center px-2">
                            <div class="col-12">
                                <div class="d-flex justify-content-between text-center position-relative px-3 py-1">
                                    <!-- Garis Background Progress -->
                                    <div class="progress position-absolute w-100" style="height: 3px; top: 14px; z-index: 1; left: 0;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 
                                            @if($p->status == 'menunggu_koordinator' || $p->status == 'diajukan') 12% 
                                            @elseif($p->status == 'menunggu_ga') 37% 
                                            @elseif($p->status == 'disetujui') 62% 
                                            @elseif(in_array($p->status, ['diproses', 'diproses_po', 'pembelian'])) 87% 
                                            @elseif($p->status == 'selesai') 100% 
                                            @else 0% @endif">
                                        </div>
                                    </div>
    
                                    <!-- STEP 1 -->
                                    <div class="position-relative text-center" style="z-index: 2; flex: 1;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm bg-success text-white" style="width: 30px; height: 30px; font-size: 0.7rem;">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div class="mt-1">
                                            <span class="fw-bold d-block text-success" style="font-size:0.68rem;">1. Diajukan</span>
                                            <small class="text-muted d-block" style="font-size:0.6rem;">
                                                Oleh: {{ $p->pemohon ? $p->pemohon->username : '-' }}<br>
                                                {{ \Carbon\Carbon::parse($p->created_at)->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                    </div>
    
                                    <!-- STEP 2 -->
                                    <div class="position-relative text-center" style="z-index: 2; flex: 1;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm {{ in_array($p->status, ['menunggu_ga', 'disetujui', 'diproses', 'diproses_po', 'pembelian', 'selesai']) ? 'bg-success text-white' : 'bg-secondary text-white' }}" style="width: 30px; height: 30px; font-size: 0.7rem;">
                                            <i class="fas fa-user-check"></i>
                                        </div>
                                        <div class="mt-1">
                                            <span class="fw-bold d-block {{ in_array($p->status, ['menunggu_ga', 'disetujui', 'diproses', 'diproses_po', 'pembelian', 'selesai']) ? 'text-success' : '' }}" style="font-size:0.68rem;">2. Koordinator</span>
                                            <small class="text-muted d-block" style="font-size:0.6rem;">
                                                @if(in_array($p->status, ['menunggu_ga', 'disetujui', 'diproses', 'diproses_po', 'pembelian', 'selesai']))
                                                    <span class="text-success fw-semibold">Disetujui</span>
                                                @else
                                                    <span class="text-warning fw-semibold">Menunggu</span>
                                                @endif
                                                <br>{{ \Carbon\Carbon::parse($p->updated_at)->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                    </div>
    
                                    <!-- STEP 3 -->
                                    <div class="position-relative text-center" style="z-index: 2; flex: 1;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm {{ in_array($p->status, ['disetujui', 'diproses', 'diproses_po', 'pembelian', 'selesai']) ? 'bg-success text-white' : 'bg-secondary text-white' }}" style="width: 30px; height: 30px; font-size: 0.7rem;">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div class="mt-1">
                                            <span class="fw-bold d-block {{ in_array($p->status, ['disetujui', 'diproses', 'diproses_po', 'pembelian', 'selesai']) ? 'text-success' : '' }}" style="font-size:0.68rem;">3. GA Approval</span>
                                            <small class="text-muted d-block" style="font-size:0.6rem;">
                                                @if(in_array($p->status, ['disetujui', 'diproses', 'diproses_po', 'pembelian', 'selesai']))
                                                    <span class="text-success fw-semibold">Disetujui</span>
                                                @else
                                                    Menunggu
                                                @endif
                                                <br>{{ \Carbon\Carbon::parse($p->updated_at)->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                    </div>
    
                                    <!-- STEP 4 -->
                                    <div class="position-relative text-center" style="z-index: 2; flex: 1;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm {{ in_array($p->status, ['diproses', 'diproses_po', 'pembelian', 'selesai']) ? 'bg-success text-white' : 'bg-secondary text-white' }}" style="width: 30px; height: 30px; font-size: 0.7rem;">
                                            <i class="fas fa-box-open"></i>
                                        </div>
                                        <div class="mt-1">
                                            <span class="fw-bold d-block {{ in_array($p->status, ['diproses', 'diproses_po', 'pembelian', 'selesai']) ? 'text-success' : '' }}" style="font-size:0.68rem;">4. Diproses (PO)</span>
                                            <small class="text-muted d-block" style="font-size:0.6rem;">
                                                @if(in_array($p->status, ['diproses', 'diproses_po', 'pembelian', 'selesai']))
                                                    <span class="fw-semibold">Sedang Diproses</span>
                                                @else
                                                    Belum
                                                @endif
                                                <br>{{ \Carbon\Carbon::parse($p->updated_at)->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                    </div>
    
                                    <!-- STEP 5 -->
                                    <div class="position-relative text-center" style="z-index: 2; flex: 1;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm {{ $p->status == 'selesai' ? 'bg-success text-white' : 'bg-secondary text-white' }}" style="width: 30px; height: 30px; font-size: 0.7rem;">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="mt-1">
                                            <span class="fw-bold d-block {{ $p->status == 'selesai' ? 'text-success' : '' }}" style="font-size:0.68rem;">5. Diterima</span>
                                            <small class="text-muted d-block" style="font-size:0.6rem;">
                                                @if($p->status == 'selesai')
                                                    Selesai
                                                @else
                                                    Menunggu Tiba
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-2 text-muted">
                        <i class="fas fa-check-circle fa-2x mb-1 text-success"></i>
                        <p class="mb-0 small">Tidak ada pengadaan barang yang berjalan saat ini.</p>
                    </div>
                @endforelse

                <div id="trackingPlaceholder" class="text-center py-2 text-muted" style="display: none;">
                    <i class="fas fa-hand-pointer fa-lg mb-1 text-primary"></i>
                    <p class="mb-0 small">Silakan pilih salah satu barang di menu dropdown atas untuk melihat rincian alur tracking prosesnya.</p>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<!-- TRACKING STATUS PENGADAAN -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 tracking-card">
            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">
                    <i class="fas fa-route text-primary me-2"></i>Tracking Status Pengadaan Barang
                </h5>
                
                <select id="selectTracking" class="form-select form-select-sm w-auto">
                    <option value="">-- Pilih Barang/Bahan --</option>
                    @foreach($pengadaanAktif as $p)
                        <option value="track-{{ $p->permintaan_id }}" {{ $loop->first ? 'selected' : '' }}>
                            {{ $p->barang->nama_barang ?? 'Barang' }} ({{ $p->barang->kode_barang ?? 'Tanpa Kode' }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="card-body p-3">
                @forelse($pengadaanAktif as $p)
                    @php
                        // Logika Pengecekan Terlambat / Overdue
                        $isTerlambat = false;
                        if ($p->status != 'selesai') {
                            $batasWaktu = \Carbon\Carbon::parse($p->created_at)->addDays(30); 
                            if (\Carbon\Carbon::now()->isAfter($batasWaktu)) {
                                $isTerlambat = true;
                            }
                        }
                    @endphp

                    <div id="track-{{ $p->permintaan_id }}" class="tracking-item" style="display: none;">
                        <!-- Info Singkat Barang -->
                        <div class="row mb-3">
                            <div class="col-md-12 border-bottom pb-2">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                    <h6 class="fw-bold mb-0 text-primary" style="font-size: 0.95rem;">
                                        {{ $p->barang->nama_barang ?? '-' }} ({{ $p->barang->kode_barang ?? 'Tanpa Kode' }})
                                    </h6>
                                    
                                    <!-- BADGE WARNING TERLAMBAT -->
                                    @if($isTerlambat)
                                        <span class="badge bg-danger animate-pulse" style="font-size: 0.68rem;">
                                            <i class="fas fa-exclamation-triangle me-1"></i> Terlambat / Overdue
                                        </span>
                                    @endif
                                </div>
                                <div class="text-muted" style="font-size: 0.81rem;">
                                    <span>Jumlah: <b class="text-dark">{{ (float) $p->jumlah_diminta }} {{ $p->barang->satuan ?? '' }}</b></span><br>
                                    <span>Target Waktu Pengadaan: <b class="{{ $isTerlambat ? 'text-danger' : 'text-dark' }}">{{ $p->format_target_waktu ?? '-' }}</b></span><br>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar & Steps -->
                        <div class="row align-items-center px-2">
                            <div class="col-12">
                                <div class="d-flex justify-content-between text-center position-relative px-3 py-1">
                                    <!-- Garis Background Progress -->
                                    <div class="progress position-absolute w-100" style="height: 3px; top: 14px; z-index: 1; left: 0;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 
                                            @if($p->status == 'menunggu_koordinator' || $p->status == 'diajukan') 12% 
                                            @elseif($p->status == 'menunggu_ga') 37% 
                                            @elseif($p->status == 'disetujui') 62% 
                                            @elseif(in_array($p->status, ['diproses', 'diproses_po', 'pembelian'])) 87% 
                                            @elseif($p->status == 'selesai') 100% 
                                            @else 0% @endif">
                                        </div>
                                    </div>
    
                                    <!-- STEP 1 -->
                                    <div class="position-relative text-center" style="z-index: 2; flex: 1;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm bg-success text-white" style="width: 30px; height: 30px; font-size: 0.7rem;">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div class="mt-1">
                                            <span class="fw-bold d-block text-success" style="font-size:0.68rem;">1. Diajukan</span>
                                            <small class="text-muted d-block" style="font-size:0.6rem;">
                                                Oleh: {{ $p->pemohon ? $p->pemohon->username : '-' }}<br>
                                                {{ \Carbon\Carbon::parse($p->created_at)->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                    </div>
    
                                    <!-- STEP 2 -->
                                    <div class="position-relative text-center" style="z-index: 2; flex: 1;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm {{ in_array($p->status, ['menunggu_ga', 'disetujui', 'diproses', 'diproses_po', 'pembelian', 'selesai']) ? 'bg-success text-white' : 'bg-secondary text-white' }}" style="width: 30px; height: 30px; font-size: 0.7rem;">
                                            <i class="fas fa-user-check"></i>
                                        </div>
                                        <div class="mt-1">
                                            <span class="fw-bold d-block {{ in_array($p->status, ['menunggu_ga', 'disetujui', 'diproses', 'diproses_po', 'pembelian', 'selesai']) ? 'text-success' : '' }}" style="font-size:0.68rem;">2. Koordinator</span>
                                            <small class="text-muted d-block" style="font-size:0.6rem;">
                                                @if(in_array($p->status, ['menunggu_ga', 'disetujui', 'diproses', 'diproses_po', 'pembelian', 'selesai']))
                                                    <span class="text-success fw-semibold">Disetujui</span>
                                                @else
                                                    <span class="text-warning fw-semibold">Menunggu</span>
                                                @endif
                                                <br>{{ \Carbon\Carbon::parse($p->updated_at)->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                    </div>
    
                                    <!-- STEP 3 -->
                                    <div class="position-relative text-center" style="z-index: 2; flex: 1;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm {{ in_array($p->status, ['disetujui', 'diproses', 'diproses_po', 'pembelian', 'selesai']) ? 'bg-success text-white' : 'bg-secondary text-white' }}" style="width: 30px; height: 30px; font-size: 0.7rem;">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div class="mt-1">
                                            <span class="fw-bold d-block {{ in_array($p->status, ['disetujui', 'diproses', 'diproses_po', 'pembelian', 'selesai']) ? 'text-success' : '' }}" style="font-size:0.68rem;">3. GA Approval</span>
                                            <small class="text-muted d-block" style="font-size:0.6rem;">
                                                @if(in_array($p->status, ['disetujui', 'diproses', 'diproses_po', 'pembelian', 'selesai']))
                                                    <span class="text-success fw-semibold">Disetujui</span>
                                                @else
                                                    Menunggu
                                                @endif
                                                <br>{{ \Carbon\Carbon::parse($p->updated_at)->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                    </div>
    
                                    <!-- STEP 4 (MERAH JIKA TERLAMBAT) -->
                                    <div class="position-relative text-center" style="z-index: 2; flex: 1;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm {{ in_array($p->status, ['diproses', 'diproses_po', 'pembelian', 'selesai']) ? ($isTerlambat && $p->status != 'selesai' ? 'bg-danger text-white' : 'bg-success text-white') : 'bg-secondary text-white' }}" style="width: 30px; height: 30px; font-size: 0.7rem;">
                                            <i class="fas fa-box-open"></i>
                                        </div>
                                        <div class="mt-1">
                                            <span class="fw-bold d-block" style="font-size:0.68rem;">4. Diproses (PO)</span>
                                            <small class="text-muted d-block" style="font-size:0.6rem;">
                                                @if(in_array($p->status, ['diproses', 'diproses_po', 'pembelian', 'selesai']))
                                                    <span class="{{ $isTerlambat && $p->status != 'selesai' ? 'text-danger fw-semibold' : 'fw-semibold' }}">
                                                        {{ $isTerlambat && $p->status != 'selesai' ? 'Terlambat' : 'Sedang Diproses' }}
                                                    </span>
                                                @else
                                                    Belum
                                                @endif
                                                <br>{{ \Carbon\Carbon::parse($p->updated_at)->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                    </div>
    
                                    <!-- STEP 5 -->
                                    <div class="position-relative text-center" style="z-index: 2; flex: 1;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm {{ $p->status == 'selesai' ? 'bg-success text-white' : 'bg-secondary text-white' }}" style="width: 30px; height: 30px; font-size: 0.7rem;">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="mt-1">
                                            <span class="fw-bold d-block {{ $p->status == 'selesai' ? 'text-success' : '' }}" style="font-size:0.68rem;">5. Diterima</span>
                                            <small class="text-muted d-block" style="font-size:0.6rem;">
                                                @if($p->status == 'selesai')
                                                    Selesai
                                                @else
                                                    Menunggu Tiba
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-2 text-muted">
                        <i class="fas fa-check-circle fa-2x mb-1 text-success"></i>
                        <p class="mb-0 small">Tidak ada pengadaan barang yang berjalan saat ini.</p>
                    </div>
                @endforelse

                <div id="trackingPlaceholder" class="text-center py-2 text-muted" style="display: none;">
                    <i class="fas fa-hand-pointer fa-lg mb-1 text-primary"></i>
                    <p class="mb-0 small">Silakan pilih salah satu barang di menu dropdown atas untuk melihat rincian alur tracking prosesnya.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const select = $('#selectTracking');
        const placeholder = document.getElementById('trackingPlaceholder');

        function updateTracking() {
            document.querySelectorAll('.tracking-item').forEach(el => el.style.display = 'none');
            
            const selectedId = select.val();
            if (selectedId) {
                if (placeholder) placeholder.style.display = 'none';
                const targetEl = document.getElementById(selectedId);
                if (targetEl) targetEl.style.display = 'block';
            } else {
                if (placeholder) placeholder.style.display = 'block';
            }
        }

        if (select.length) {
            select.select2({
                theme: 'bootstrap-5',
                width: 'resolve',
                dropdownParent: select.parent(),
                minimumResultsForSearch: Infinity,
                allowClear: false
            });

            updateTracking();
            select.on('change', updateTracking);
        }
    });
</script>
@endpush
</div>
@endsection