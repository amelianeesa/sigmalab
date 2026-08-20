<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Alat - SIGMA-LAB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header-section {
            background-color: #0d6efd;
            color: white;
            padding: 2rem 0;
            text-align: center;
        }
        .card-custom {
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1);
            border-radius: 0.5rem;
            margin-top: -30px;
        }
        .table-info-alat td {
            padding: 10px;
        }
    </style>
</head>
<body>

    <div class="header-section">
        <div class="container">
            <h2 class="mb-0"><i class="bi bi-upc-scan"></i> Identitas Peralatan</h2>
            <p class="mb-0">Laboratorium Pengujian</p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                @php
                    // Kalibrasi terakhir dipakai untuk status; controller sudah order by tgl_kalibrasi desc
                    $kalibrasiTerakhir = $alat->riwayatKalibrasi->first();
                    $statusBadge = 'bg-secondary';
                    $statusText = 'Belum Ada Kalibrasi';

                    if ($kalibrasiTerakhir && $kalibrasiTerakhir->tgl_akhir) {
                        $tglAkhir = \Carbon\Carbon::parse($kalibrasiTerakhir->tgl_akhir);
                        $sisaHari = now()->startOfDay()->diffInDays($tglAkhir, false);

                        if ($sisaHari < 0) {
                            $statusBadge = 'bg-danger';
                            $statusText = 'Kalibrasi Jatuh Tempo';
                        } elseif ($sisaHari <= 30) {
                            $statusBadge = 'bg-warning text-dark';
                            $statusText = 'Mendekati Jatuh Tempo (' . $sisaHari . ' hari)';
                        } else {
                            $statusBadge = 'bg-success';
                            $statusText = 'Aktif (Berlaku s/d ' . $tglAkhir->format('d/m/Y') . ')';
                        }
                    }

                    // Riwayat lengkap diurutkan dari kalibrasi pertama -> terakhir
                    $riwayatUrut = $alat->riwayatKalibrasi->sortBy('tgl_kalibrasi')->values();
                @endphp

                <div class="card card-custom mb-4">
                    <div class="card-body p-4">
                        <h4 class="card-title text-primary border-bottom pb-2 mb-3">Informasi Utama</h4>
                        <table class="table table-borderless table-info-alat mb-0">
                            <tr>
                                <td style="width: 150px;" class="fw-bold text-muted">Nama Alat</td>
                                <td class="fs-5 fw-bold">{{ $alat->nama_alat }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Kode Alat</td>
                                <td><span class="badge bg-secondary fs-6">{{ $alat->kode_alat }}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Merk / Tipe</td>
                                <td>{{ $alat->merk_tipe ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Nomor Seri</td>
                                <td>{{ $alat->no_seri ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Unit Kerja Pemilik</td>
                                <td>{{ $alat->unit_kerja_pemilik ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Status</td>
                                <td>
                                    <span class="badge {{ $statusBadge }} fs-6">{{ $statusText }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Tombol agar QR bisa langsung dipakai untuk isi form kalibrasi baru --}}
                <div class="d-grid mb-4">
                    @auth
                        <a href="{{ route('alat.input-kalibrasi', $alat->alat_id) }}" class="btn btn-primary btn-lg shadow-sm">
                            <i class="bi bi-clipboard2-plus me-1"></i> Isi / Update Data Kalibrasi Alat Ini
                        </a>
                    @else
                        <a href="{{ route('login') }}?redirect={{ route('alat.input-kalibrasi', $alat->alat_id) }}" class="btn btn-primary btn-lg shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login untuk Isi Data Kalibrasi
                        </a>
                    @endauth
                </div>

                <div class="card card-custom mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title text-secondary border-bottom pb-2 mb-3"><i class="bi bi-clock-history"></i> Riwayat Kalibrasi Lengkap</h5>

                        @if($riwayatUrut->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped text-center align-middle" style="font-size: 0.85rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Urutan</th>
                                            <th>Jenis</th>
                                            <th>Tanggal Kalibrasi s/d Akhir</th>
                                            <th>Lembaga & No. Sertifikat</th>
                                            <th>Signifikan</th>
                                            <th>Catatan / Evaluasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($riwayatUrut as $riwayat)
                                        <tr>
                                            <td class="fw-bold text-primary">Kalibrasi ke-{{ $loop->iteration }}</td>
                                            <td><span class="badge bg-info text-dark">{{ ucfirst($riwayat->jenis_kalibrasi) }}</span></td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($riwayat->tgl_kalibrasi)->format('d/m/Y') }} <br>
                                                <small class="text-muted">s/d {{ $riwayat->tgl_akhir ? \Carbon\Carbon::parse($riwayat->tgl_akhir)->format('d/m/Y') : '-' }}</small>
                                            </td>
                                            <td class="text-start">
                                                <strong>{{ $riwayat->lembaga_kalibrasi ?? '-' }}</strong><br>
                                                <small class="text-muted">Sertifikat: {{ $riwayat->no_sertifikat ?? '-' }}</small>
                                                @if(!empty($riwayat->file_sertifikat))
                                                    <div class="mt-1">
                                                        <a href="{{ asset('storage/' . $riwayat->file_sertifikat) }}" target="_blank" class="btn btn-outline-primary btn-sm py-0 px-1" style="font-size: 0.7rem;" title="Lihat Sertifikat">
                                                            <i class="bi bi-file-earmark-pdf"></i> Lihat
                                                        </a>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $riwayat->signifikan == 'ya' ? 'success' : 'secondary' }}">
                                                    {{ strtoupper($riwayat->signifikan ?? '-') }}
                                                </span>
                                            </td>
                                            <td class="text-start">{{ $riwayat->catatan_evaluasi ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-secondary text-center">
                                Belum ada riwayat kalibrasi tercatat
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card card-custom">
                    <div class="card-body p-4">
                        <h5 class="card-title text-secondary border-bottom pb-2 mb-3"><i class="bi bi-check2-square"></i> Item Pemeliharaan</h5>
                        @if($alat->itemPemeliharaan && $alat->itemPemeliharaan->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($alat->itemPemeliharaan as $item)
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="bi bi-record-circle text-primary me-2"></i>
                                        {{ $item->nama_pemeliharaan }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">Belum ada daftar item pemeliharaan untuk alat ini</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>