<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan QC Laboratorium</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .badge-success {
            color: green;
            font-weight: bold;
        }
        .badge-danger {
            color: red;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Quality Control Laboratorium</h1>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal Input</th>
                <th width="20%">Kegiatan / Kode Sampel</th>
                <th width="20%">Parameter Uji</th>
                <th width="15%">Hasil Uji</th>
                <th width="15%">Batas Acuan</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasilUjiList as $hasil)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($hasil->created_at)->format('d/m/Y H:i') }}</td>
                    <td>
                        {{ $hasil->kegiatan ? $hasil->kegiatan->nama_kegiatan : '-' }}<br>
                        <small>({{ $hasil->kegiatan ? $hasil->kegiatan->kode_sampel : '-' }})</small>
                    </td>
                    <td>{{ $hasil->parameterUji ? $hasil->parameterUji->nama_parameter : '-' }}</td>
                    <td class="text-center">{{ $hasil->nilai_hasil }} {{ $hasil->parameterUji ? $hasil->parameterUji->satuan : '' }}</td>
                    <td class="text-center">{{ $hasil->parameterUji ? $hasil->parameterUji->batas_bawah . ' - ' . $hasil->parameterUji->batas_atas : '-' }}</td>
                    <td class="text-center">
                        @if($hasil->status_berketerimaan == 'inlier')
                            <span class="badge-success">Inlier</span>
                        @elseif($hasil->status_berketerimaan == 'outlier')
                            <span class="badge-danger">Outlier</span>
                        @else
                            {{ ucfirst($hasil->status_berketerimaan) }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Digenerate secara otomatis oleh Sistem Manajemen Laboratorium</p>
    </div>

</body>
</html>
