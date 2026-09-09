<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Inhouse Control - {{ $selectedParameter->nama_parameter }}</title>
    <style>
        body { font-family: sans-serif; font-size: 7px; }
        h2, h3, h4 { text-align: center; margin: 3px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 2px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-danger { color: red; }
        .text-warning { color: orange; }
        .fw-bold { font-weight: bold; }
        .bg-light { background-color: #f9f9f9; }
        .info-table { width: 100%; margin-top: 10px; margin-bottom: 10px; border: none; font-size: 9px; }
        .info-table td { border: none; text-align: left; padding: 2px; }
    </style>
</head>
<body>

    <h2>LAPORAN CONTROL CHART INHOUSE</h2>
    <h3>PARAMETER: {{ strtoupper($selectedParameter->nama_parameter) }}</h3>
    @if(request('tanggal_mulai') || request('tanggal_akhir'))
        <h4>Periode: {{ request('tanggal_mulai') ?? '-' }} s/d {{ request('tanggal_akhir') ?? '-' }}</h4>
    @endif

    <table class="info-table">
        <tr>
            <td width="15%"><strong>Satuan</strong></td>
            <td width="35%">: {{ $selectedParameter->satuan }}</td>
            <td width="15%"><strong>Toleransi Duplo</strong></td>
            <td width="35%">: {{ $selectedParameter->toleransi_duplo ?? 0.5 }} % (Abs. Diff)</td>
        </tr>
    </table>

    <!-- RAW DATA -->
    <h4>1. RAW DATA LOGGER</h4>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Tgl</th>
                <th rowspan="2">Ke-</th>
                <th rowspan="2">Sampel</th>
                <th colspan="4">DISH 1</th>
                <th colspan="4">DISH 2</th>
                <th colspan="3">Abs Diff</th>
                <th colspan="3">Average (% db)</th>
                <th rowspan="2">Sts</th>
            </tr>
            <tr>
                <th>Wt (mg)</th><th>C %</th><th>H %</th><th>N %</th>
                <th>Wt (mg)</th><th>C %</th><th>H %</th><th>N %</th>
                <th>C</th><th>H</th><th>N</th>
                <th>C</th><th>H</th><th>N</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasilList as $index => $hasil)
                @php
                    $dm = is_array($hasil->data_mentah) ? $hasil->data_mentah : json_decode($hasil->data_mentah, true);
                @endphp
                <tr>
                    <td>{{ $hasil->created_at->format('d/m/y') }}</td>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $hasil->kegiatan ? $hasil->kegiatan->kode_sampel : '-' }}</td>
                    
                    <td>{{ isset($dm['Weight_D1']) ? number_format($dm['Weight_D1'], 2) : '-' }}</td>
                    <td>{{ isset($dm['C_D1']) ? number_format($dm['C_D1'], 2) : '-' }}</td>
                    <td>{{ isset($dm['H_D1']) ? number_format($dm['H_D1'], 2) : '-' }}</td>
                    <td>{{ isset($dm['N_D1']) ? number_format($dm['N_D1'], 2) : '-' }}</td>
                    
                    <td>{{ isset($dm['Weight_D2']) ? number_format($dm['Weight_D2'], 2) : '-' }}</td>
                    <td>{{ isset($dm['C_D2']) ? number_format($dm['C_D2'], 2) : '-' }}</td>
                    <td>{{ isset($dm['H_D2']) ? number_format($dm['H_D2'], 2) : '-' }}</td>
                    <td>{{ isset($dm['N_D2']) ? number_format($dm['N_D2'], 2) : '-' }}</td>
                    
                    <td>{{ isset($dm['Abs_C']) ? number_format($dm['Abs_C'], 2) : '-' }}</td>
                    <td>{{ isset($dm['Abs_H']) ? number_format($dm['Abs_H'], 2) : '-' }}</td>
                    <td>{{ isset($dm['Abs_N']) ? number_format($dm['Abs_N'], 2) : '-' }}</td>
                    
                    <td class="fw-bold">{{ isset($dm['Avg_C']) ? number_format($dm['Avg_C'], 2) : '-' }}</td>
                    <td class="fw-bold">{{ isset($dm['Avg_H']) ? number_format($dm['Avg_H'], 2) : '-' }}</td>
                    <td class="fw-bold">{{ isset($dm['Avg_N']) ? number_format($dm['Avg_N'], 2) : '-' }}</td>
                    
                    <td>{{ $hasil->status_berketerimaan === 'gagal_duplo' ? 'NO' : 'YES' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>



    <!-- Ttd -->
    <table class="info-table" style="margin-top: 30px; font-size: 10px;">
        <tr>
            <td width="70%"></td>
            <td width="30%" style="text-align: center;">
                <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
                <p>Mengetahui,</p>
                <br><br><br>
                <p><strong>( ______________________ )</strong></p>
                <p>Manajer Mutu / Lab</p>
            </td>
        </tr>
    </table>

</body>
</html>
