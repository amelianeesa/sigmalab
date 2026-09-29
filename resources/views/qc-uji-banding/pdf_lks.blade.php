<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lembar Ketidaksesuaian - {{ $program->nama_program }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
            text-align: left;
            width: 30%;
        }
        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
            margin-top: 15px;
        }
        .content-box {
            border: 1px solid #000;
            padding: 10px;
            min-height: 100px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">LEMBAR KETIDAKSESUAIAN (LKS) / CAPA</p>
        <p style="margin: 0;">LABORATORIUM PENGUJIAN MINERAL & BATUBARA</p>
    </div>

    <table>
        <tr>
            <th>Nama Program Uji Banding</th>
            <td>{{ $program->nama_program }}</td>
        </tr>
        <tr>
            <th>Penyelenggara (Vendor)</th>
            <td>{{ $program->penyelenggara }}</td>
        </tr>
        <tr>
            <th>Kode Sampel</th>
            <td>{{ $program->kode_sampel }}</td>
        </tr>
        <tr>
            <th>Parameter Uji</th>
            <td>{{ $parameter->parameterUji->nama_parameter }}</td>
        </tr>
        <tr>
            <th>Analis Penguji</th>
            <td>{{ $parameter->analis->nama ?? '-' }}</td>
        </tr>
        <tr>
            <th>Hasil Lab (Nilai Akhir)</th>
            <td>{{ $parameter->nilai_akhir }}</td>
        </tr>
        <tr>
            <th>Target Vendor / Z-Score</th>
            <td>{{ $parameter->target_vendor ?? '-' }} / Z: {{ $parameter->z_score ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">1. Akar Masalah (Root Cause Analysis):</div>
    <div class="content-box">
        {!! nl2br(e($parameter->akar_masalah)) !!}
    </div>

    <div class="section-title">2. Tindakan Perbaikan (Corrective Action):</div>
    <div class="content-box">
        {!! nl2br(e($parameter->tindakan_perbaikan)) !!}
    </div>

    <div class="section-title">3. Tindakan Pencegahan (Preventive Action):</div>
    <div class="content-box">
        {!! nl2br(e($parameter->tindakan_pencegahan ?? '-')) !!}
    </div>
    
    <table style="margin-top: 40px; border: none;">
        <tr>
            <td style="border: none; text-align: center; width: 50%;">
                Dibuat Oleh,<br><br><br><br>
                ( {{ $parameter->analis->nama ?? '..........................' }} )<br>
                Analis
            </td>
            <td style="border: none; text-align: center; width: 50%;">
                Mengetahui,<br><br><br><br>
                ( .......................... )<br>
                Manajer Mutu / Teknis
            </td>
        </tr>
    </table>
</body>
</html>
