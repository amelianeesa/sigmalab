<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Uji Banding - {{ $program->kode_sampel }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .header h2, .header h3 {
            margin: 0;
            padding: 2px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .info-table td {
            border: none;
            padding: 4px;
        }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN HASIL UJI BANDING</h2>
        <h3>{{ $program->nama_program }}</h3>
    </div>

    <table class="info-table" style="width: 50%;">
        <tr>
            <td style="width: 130px;"><strong>Kode Sampel</strong></td>
            <td>: {{ $program->kode_sampel }}</td>
        </tr>
        <tr>
            <td><strong>Penyelenggara</strong></td>
            <td>: {{ $program->penyelenggara }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Terima</strong></td>
            <td>: {{ $program->tanggal_terima ? $program->tanggal_terima->format('d M Y') : '-' }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Parameter Uji</th>
                <th>Nilai Lab</th>
                <th>Target Vendor</th>
                <th>SDPA</th>
                <th>Z-Score</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($program->parameters as $index => $param)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $param->parameterUji->nama_parameter ?? '-' }}</td>
                <td class="text-center">{{ $param->nilai_akhir }}</td>
                <td class="text-center">{{ $param->target_vendor ?? '-' }}</td>
                <td class="text-center">{{ $param->sdpa ?? '-' }}</td>
                <td class="text-center">{{ $param->z_score ?? '-' }}</td>
                <td class="text-center">
                    @if($param->status_evaluasi === 'inlier')
                        Inlier
                    @elseif($param->status_evaluasi === 'warning')
                        Warning
                    @elseif($param->status_evaluasi === 'outlier')
                        Outlier
                    @else
                        Menunggu
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
