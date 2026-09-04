<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $tahun }}_{{ $bulan }}_Rekap Pencatatan Monitoring Suhu & Kelembaban</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 8px 12px;
        }
        .header-table {
            width: 100%;
            margin-bottom: 4px;
            border-bottom: 2px solid #000;
            padding-bottom: 4px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            line-height: 1.2;
            margin: 0;
            padding-top: 11px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 8px;
            font-size: 11px;
        }
        .info-table td {
            padding: 1px 0;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        .main-table th, .main-table td {
            border: 1px solid #000;
            padding: 3px 2px;
            font-size: 10px;
        }
        .main-table th {
            background-color: #f2f2f2;
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
                <div class="title">PENCATATAN MONITORING SUHU<br>DAN KELEMBABAN UDARA</div>
            </td>
            <td align="right" style="vertical-align: bottom;">
                <img src="{{ asset('images/Logo_Suco_Nobg.png') }}" alt="Logo Sucofindo" style="height: 60px; width: auto; display: block; margin-bottom: 0px;">
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td style="width: 120px;"><strong>Bulan/Tahun</strong></td>
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
                <th rowspan="3" style="width: 25px;">Tanggal</th>
                <th rowspan="2" colspan="2" style="width: 60px;">Waktu Pencatatan</th>
                <th colspan="8">Hasil Pengukuran</th>
                <th colspan="2" rowspan="2" style="width: 50px;">Paraf</th>
                <th rowspan="3" style="width: 90px;">Keterangan</th>
            </tr>
            <tr>
                <th colspan="4">Suhu (°C)</th>
                <th colspan="4">Kelembaban (%)</th>
            </tr>
            <tr>
                <th style="width: 30px;">Pagi</th>
                <th style="width: 30px;">Sore</th>
                <th>Pembacaan 1</th>
                <th>Terkoreksi 1</th>
                <th>Pembacaan 2</th>
                <th>Terkoreksi 2</th>
                <th>Pembacaan 1</th>
                <th>Terkoreksi 1</th>
                <th>Pembacaan 2</th>
                <th>Terkoreksi 2</th>
                <th style="width: 25px;">1</th>
                <th style="width: 25px;">2</th>
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
                    
                    <td>{{ $row?->paraf_1 ? '✓' : '' }}</td>
                    <td>{{ $row?->paraf_2 ? '✓' : '' }}</td>
                    <td style="text-align: left; padding-left: 5px;">{{ $row?->keterangan ?? '' }}</td>
                </tr>
            @endfor
        </tbody>
    </table>

</body>
</html>