<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $tahun }}_{{ $bulan }}_Rekap Pencatatan Monitoring Suhu & Kelembaban</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm 15mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            margin-bottom: 8px;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            line-height: 1.3;
            margin: 0;
        }
        .info-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 12px;
        }
        .info-table td {
            padding: 2px 0;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        .main-table th, .main-table td {
            border: 1px solid #000;
            padding: 5px 4px;
            font-size: 11px;
        }
        .main-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body onload="window.print()">
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
                <img src="{{ asset('images/Logo_Suco_Nobg.png') }}" alt="Logo Sucofindo" style="height: 55px; width: auto; display: block;">
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td style="width: 120px;"><strong>Bulan / Tahun</strong></td>
            <td style="width: 10px;">:</td>
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
                <th rowspan="3" style="width: 35px;">Tanggal</th>
                <th rowspan="2" colspan="2" style="width: 90px;">Waktu Pencatatan</th>
                <th colspan="8">Hasil Pengukuran</th>
                <th colspan="2" rowspan="2" style="width: 70px;">Status</th>
                <th colspan="2" rowspan="2" style="width: 120px;">Paraf</th>
            </tr>
            <tr>
                <th colspan="4">Suhu (°C)</th>
                <th colspan="4">Kelembaban (%)</th>
            </tr>
            <tr>
                <th style="width: 45px;">Pagi</th>
                <th style="width: 45px;">Sore</th>
                <th>Pembacaan 1</th>
                <th>Koreksi 1</th>
                <th>Pembacaan 2</th>
                <th>Koreksi 2</th>
                <th>Pembacaan 1</th>
                <th>Koreksi 1</th>
                <th>Pembacaan 2</th>
                <th>Koreksi 2</th>
                <th style="width: 35px;">Diterima</th>
                <th style="width: 35px;">Ditolak</th>
                <th style="width: 60px;">1</th>
                <th style="width: 60px;">2</th>
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
                    <td>{{ ($row?->status == 'Diterima') ? '✓' : '' }}</td>
                    <td>{{ ($row?->status == 'Ditolak') ? '✓' : '' }}</td>

                    <!-- Paraf 1 & 2 -->
                    <td style="font-size: 10px;">{{ $row?->paraf_1 ?? '' }}</td>
                    <td style="font-size: 10px;">{{ $row?->paraf_2 ?? '' }}</td>
                </tr>
            @endfor
        </tbody>
    </table>
</body>
</html>