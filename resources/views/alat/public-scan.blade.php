<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Alat - SIGMA-LAB</title>
    <!-- CSS Bootstrap dari CDN (sesuai standar aplikasi) -->
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
                                <td class="fw-bold text-muted">Lokasi</td>
                                <td>{{ $alat->lokasi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Status</td>
                                <td>
                                    @php
                                        // Cari kalibrasi terakhir
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
                                    @endphp
                                    <span class="badge {{ $statusBadge }} fs-6">{{ $statusText }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card card-custom mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title text-secondary border-bottom pb-2 mb-3"><i class="bi bi-clock-history"></i> Riwayat Kalibrasi Terbaru</h5>
                        @if($kalibrasiTerakhir)
                            <div class="alert alert-info bg-light border-info">
                                <div class="row">
                                    <div class="col-sm-6 mb-2">
                                        <small class="text-muted d-block">Nomor Sertifikat</small>
                                        <strong>{{ $kalibrasiTerakhir->no_sertifikat }}</strong>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <small class="text-muted d-block">Lembaga Kalibrasi</small>
                                        <strong>{{ $kalibrasiTerakhir->lembaga_kalibrasi }}</strong>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <small class="text-muted d-block">Tanggal Kalibrasi</small>
                                        <strong>{{ \Carbon\Carbon::parse($kalibrasiTerakhir->tgl_kalibrasi)->format('d F Y') }}</strong>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <small class="text-muted d-block">Berlaku Sampai</small>
                                        <strong class="{{ (isset($sisaHari) && $sisaHari < 0) ? 'text-danger' : 'text-success' }}">{{ \Carbon\Carbon::parse($kalibrasiTerakhir->tgl_akhir)->format('d F Y') }}</strong>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-secondary text-center">
                                Belum ada riwayat kalibrasi tercatat.
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
                            <p class="text-muted mb-0">Belum ada daftar item pemeliharaan untuk alat ini.</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
