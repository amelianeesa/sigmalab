<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10pt; color: #333; margin: 15px 20px; }
        
        /* Header & Logo Fix */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        .header-table td { vertical-align: middle; border: none; padding: 0; }
        
        /* Menggunakan lebar fix agar DomPDF merender logo dengan sempurna tanpa terpotong */
        .logo { width: 140px; display: block; margin-left: auto; }
        
        h2 { color: #004a99; margin: 0 0 2px 0; font-size: 15pt; }
        p { margin: 0; color: #555; font-size: 10pt; }
        .divider { border-bottom: 2px solid #004a99; margin-bottom: 15px; margin-top: 5px; }

        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { padding: 4px 0; border: none; font-size: 10pt; }
        .label { font-weight: bold; width: 150px; }

        /* Tabel Data Riwayat dengan Kolom Diperluas Maksimal */
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th, table.data-table td { 
            border: 1px solid #777; 
            padding: 8px 6px; 
            text-align: center; 
            font-size: 9.5pt;
            vertical-align: middle;
        }
        table.data-table th { background-color: #f2f2f2; font-weight: bold; }
        .text-left { text-align: left !important; padding-left: 8px !important; }
    </style>
</head>
<body>

    <!-- Header Laporan & Logo -->
    <table class="header-table">
        <tr>
            <td style="width: 55%;">
                <h2>SIGMA-LAB PT SUCOFINDO</h2>
                <p>Laporan Kalibrasi Alat</p>
            </td>
            <td style="width: 45%; text-align: right;">
                <?php
                    $path = public_path('images/Logo_Suco_Nobg.png');
                    if (file_exists($path)) {
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    } else {
                        $logoBase64 = '';
                    }
                ?>
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" class="logo" alt="Logo PT Sucofindo">
                @endif
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Informasi Detail Alat -->
    <table class="info-table">
        <tr><td class="label">Nama Alat</td><td>: {{ $alat->nama_alat }}</td></tr>
        <tr><td class="label">Kode Alat</td><td>: {{ $alat->kode_alat }}</td></tr>
        <tr><td class="label">Merk / Tipe</td><td>: {{ $alat->merk_tipe ?? '-' }}</td></tr>
        <tr><td class="label">Nomor Seri</td><td>: {{ $alat->no_seri ?? '-' }}</td></tr>
        <tr><td class="label">Unit Kerja Pemilik</td><td>: {{ $alat->unit_kerja_pemilik ?? '-' }}</td></tr>
    </table>

    <!-- Tabel Riwayat Kalibrasi -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 10%;">Urutan</th>
                <th style="width: 8%;">Jenis Kalibrasi</th>
                <th style="width: 17%;">Tanggal Kalibrasi</th>
                <th style="width: 22%;">Lembaga & Sertifikat</th>
                <th style="width: 20%;">Range & Koreksi</th>
                <th style="width: 8%;">Signifikan</th>
                <th style="width: 15%;">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alat->riwayatKalibrasi as $index => $r)
            <tr>
                <td style="font-weight: bold; color: #004a99;">Kalibrasi ke-{{ $index + 1 }}</td>
                <td>{{ ucfirst($r->jenis_kalibrasi) }}</td>
                <td>{{ \Carbon\Carbon::parse($r->tgl_kalibrasi)->format('d-m-Y') }}<br>s/d<br>{{ \Carbon\Carbon::parse($r->tgl_akhir)->format('d-m-Y') }}</td>
                <td class="text-left">
                    <strong>Lembaga:</strong> {{ $r->lembaga_kalibrasi }}<br>
                    <strong>Sertifikat:</strong> {{ $r->no_sertifikat }}
                </td>
                <td class="text-left">
                    <strong>Range:</strong> {{ $r->range_kapasitas ?? '-' }}<br>
                    <strong>Koreksi:</strong> {{ $r->faktor_koreksi ?? '-' }}
                </td>
                <td>{{ strtoupper($r->signifikan) }}</td>
                <td>{{ $r->catatan_evaluasi ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>