<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $tahun }}_{{ $bulan }}_Rekap Pencatatan Monitoring Suhu & Kelembaban</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm 12mm 10mm 12mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            margin-bottom: 5px;
            border-bottom: 2px solid #000;
            padding-bottom: 4px;
        }
        .title {
            font-size: 15px;
            font-weight: bold;
            line-height: 1.2;
            margin: 0;
        }
        .info-table {
            width: 100%;
            margin-bottom: 6px;
            font-size: 11px;
        }
        .info-table td {
            padding: 1.5px 0;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            margin-bottom: 5px;
        }
        .main-table th, .main-table td {
            border: 1px solid #000;
            padding: 3.5px 2px;
            font-size: 10px;
        }
        .main-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 10.5px;
        }
        .notes-section {
            font-size: 9.5px;
            margin-top: 4px;
            line-height: 1.25;
        }
        .notes-section ol {
            margin: 2px 0 0 15px;
            padding: 0;
        }
        .footer-info {
            position: fixed;
            bottom: -6mm;
            left: 0;
            right: 0;
            font-size: 9.5px;
            color: #333;
            border-top: 1px solid #aaa;
            padding-top: 3px;
        }
    </style>
</head>
<body>
    @php
        $idAlat = $alat?->alat_id ?? $alat?->id ?? null;
        $minTemp = null; $maxTemp = null;
        $minHum  = null; $maxHum  = null;

        if ($idAlat) {
            $titikList = \App\Models\TitikKalibrasi::where('alat_id', $idAlat)->get();
            $minTemp = $titikList->where('kategori', 'temperature')->min('equipment_reading');
            $maxTemp = $titikList->where('kategori', 'temperature')->max('equipment_reading');
            $minHum  = $titikList->where('kategori', 'humidity')->min('equipment_reading');
            $maxHum  = $titikList->where('kategori', 'humidity')->max('equipment_reading');
        }
    @endphp

    <table class="header-table">
        <tr>
            <td style="vertical-align: bottom;">
                <div class="title">PENCATATAN MONITORING SUHU DAN KELEMBABAN UDARA</div>
            </td>
            <td align="right" style="vertical-align: bottom;">
                <img src="{{ public_path('images/Logo_Suco_Nobg.png') }}" alt="Logo Sucofindo" style="height: 38px; width: auto; display: block;">
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td style="width: 110px;"><strong>Bulan / Tahun</strong></td>
            <td style="width: 8px;">:</td>
            <td>{{ $bulan }} / {{ $tahun }}</td>
        </tr>
        <tr>
            <td><strong>Nama Ruangan</strong></td>
            <td>:</td>
            <td>{{ $ruangan }}</td>
        </tr>
        <tr>
            <td><strong>Alat</strong></td>
            <td>:</td>
            <td>{{ $alat?->nama_alat ?? '-' }} ({{ $alat?->kode_alat ?? '-' }})</td>
        </tr>
        <tr>
            <td><strong>Persyaratan</strong></td>
            <td>:</td>
            <td>Suhu {{ $persyaratanSuhu }} / Kelembaban {{ $persyaratanKelembaban }}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th rowspan="3" style="width: 30px;">Tanggal</th>
                <th rowspan="2" colspan="2" style="width: 75px;">Waktu Pencatatan</th>
                <th colspan="8">Hasil Pengukuran</th>
                <th colspan="2" rowspan="2" style="width: 50px;">Status</th>
                <th colspan="2" rowspan="2" style="width: 100px;">Paraf</th>
            </tr>
            <tr>
                <th colspan="4">Suhu (°C)</th>
                <th colspan="4">Kelembaban (%)</th>
            </tr>
            <tr>
                <th style="width: 38px;">Pagi</th>
                <th style="width: 38px;">Sore</th>
                <th>Pembacaan 1</th>
                <th>Koreksi 1</th>
                <th>Pembacaan 2</th>
                <th>Koreksi 2</th>
                <th>Pembacaan 1</th>
                <th>Koreksi 1</th>
                <th>Pembacaan 2</th>
                <th>Koreksi 2</th>
                <th style="width: 25px;">Diterima</th>
                <th style="width: 25px;">Ditolak</th>
                <th style="width: 48px;">1</th>
                <th style="width: 48px;">2</th>
            </tr>
        </thead>
        <tbody>
            @for($tgl = 1; $tgl <= 31; $tgl++)
                @php 
                    $row = $monitoringData[$tgl] ?? null; 

                    $isS1Out = ($minTemp !== null && $row?->suhu_pembacaan_1 !== null && ($row->suhu_pembacaan_1 < $minTemp || $row->suhu_pembacaan_1 > $maxTemp));
                    $isS2Out = ($minTemp !== null && $row?->suhu_pembacaan_2 !== null && ($row->suhu_pembacaan_2 < $minTemp || $row->suhu_pembacaan_2 > $maxTemp));
                    $isH1Out = ($minHum !== null && $row?->kelembaban_pembacaan_1 !== null && ($row->kelembaban_pembacaan_1 < $minHum || $row->kelembaban_pembacaan_1 > $maxHum));
                    $isH2Out = ($minHum !== null && $row?->kelembaban_pembacaan_2 !== null && ($row->kelembaban_pembacaan_2 < $minHum || $row->kelembaban_pembacaan_2 > $maxHum));
                @endphp
                <tr>
                    <td><strong>{{ $tgl }}</strong></td>
                    <td>{{ $row?->waktu_1 ?? '' }}</td>
                    <td>{{ $row?->waktu_2 ?? '' }}</td>

                    <td style="{{ $isS1Out ? 'color: red; font-weight: bold;' : '' }}">{{ $row?->suhu_pembacaan_1 ?? '' }}</td>
                    <td>{{ $isS1Out ? '-' : ($row?->suhu_terkoreksi_1 ?? '') }}</td>
                    
                    <td style="{{ $isS2Out ? 'color: red; font-weight: bold;' : '' }}">{{ $row?->suhu_pembacaan_2 ?? '' }}</td>
                    <td>{{ $isS2Out ? '-' : ($row?->suhu_terkoreksi_2 ?? '') }}</td>

                    <td style="{{ $isH1Out ? 'color: red; font-weight: bold;' : '' }}">{{ $row?->kelembaban_pembacaan_1 ?? '' }}</td>
                    <td>{{ $isH1Out ? '-' : ($row?->kelembaban_terkoreksi_1 ?? '') }}</td>

                    <td style="{{ $isH2Out ? 'color: red; font-weight: bold;' : '' }}">{{ $row?->kelembaban_pembacaan_2 ?? '' }}</td>
                    <td>{{ $isH2Out ? '-' : ($row?->kelembaban_terkoreksi_2 ?? '') }}</td>
                    
                    <!-- Status Diterima / Ditolak -->
                    <td>{{ ($row?->status == 'Diterima') ? 'V' : '' }}</td>
                    <td>{{ ($row?->status == 'Ditolak') ? 'V' : '' }}</td>

                    <!-- Paraf 1 & 2 -->
                    <td style="font-size: 9.5px;">{{ $row?->paraf_1 ?? '' }}</td>
                    <td style="font-size: 9.5px;">{{ $row?->paraf_2 ?? '' }}</td>
                </tr>
            @endfor
        </tbody>
    </table>

    <!-- Bagian Catatan di Bawah Tabel -->
    <div class="notes-section">
        <div>*) dipersyaratkan pada Daftar Ruangan Pengujian (FOR/COAL-OPS/199) ditetapkan oleh Manajer Teknis</div>
        <div><strong>Catatan:</strong></div>
        <ol>
            <li>Pemantauan dilakukan setiap hari pada pagi (pukul 08.00 – 09.00), sore (pukul 13.00 – 14.00) dan malam (pukul 20.00 – 21.00), catat sesuai dengan aktivitas di ruangan.</li>
            <li>Pastikan hasil pengukuran terkoreksi dari ruangan sesuai dengan yang dipersyaratkan, bila tidak maka laporkan ke Koordinator Laboratorium.</li>
        </ol>
    </div>

    <!-- Footer Informasi Cetak -->
    <div class="footer-info">
        <table width="100%" style="border: none; background: transparent; font-size: 9px;">
            <tr>
                <td style="text-align: left; border: none; padding: 0; width: 50%;">
                    Dicetak oleh: <strong>{{ $namaUserCetak ?? 'System' }}</strong>
                </td>
                <td style="text-align: right; border: none; padding: 0; width: 50%;">
                    Waktu Cetak: {{ \Carbon\Carbon::now('Asia/Jakarta')->format('d-m-Y H:i:s') }} WIB
                </td>
            </tr>
        </table>
    </div>
</body>
</html>