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
        .page-break { page-break-after: always; }
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
            <td width="35%">: {{ $selectedParameter->toleransi_duplo ?? 50.0 }} ℃ (Abs. Diff)</td>
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
                <th rowspan="2">Atmosfer</th>
                <th colspan="4">DISH 1 (℃)</th>
                <th colspan="4">DISH 2 (℃)</th>
                <th colspan="4">Abs Diff</th>
                <th colspan="4">Average (℃)</th>
                <th rowspan="2">Sts</th>
            </tr>
            <tr>
                <th>IDT</th><th>ST</th><th>HT</th><th>FT</th>
                <th>IDT</th><th>ST</th><th>HT</th><th>FT</th>
                <th>IDT</th><th>ST</th><th>HT</th><th>FT</th>
                <th>IDT</th><th>ST</th><th>HT</th><th>FT</th>
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
                    <td>{{ substr($dm['Atmosphere'] ?? '-', 0, 3) }}</td>
                    
                    <td>{{ isset($dm['IDT_D1']) ? number_format($dm['IDT_D1'], 1) : '-' }}</td>
                    <td>{{ isset($dm['ST_D1']) ? number_format($dm['ST_D1'], 1) : '-' }}</td>
                    <td>{{ isset($dm['HT_D1']) ? number_format($dm['HT_D1'], 1) : '-' }}</td>
                    <td>{{ isset($dm['FT_D1']) ? number_format($dm['FT_D1'], 1) : '-' }}</td>
                    
                    <td>{{ isset($dm['IDT_D2']) ? number_format($dm['IDT_D2'], 1) : '-' }}</td>
                    <td>{{ isset($dm['ST_D2']) ? number_format($dm['ST_D2'], 1) : '-' }}</td>
                    <td>{{ isset($dm['HT_D2']) ? number_format($dm['HT_D2'], 1) : '-' }}</td>
                    <td>{{ isset($dm['FT_D2']) ? number_format($dm['FT_D2'], 1) : '-' }}</td>
                    
                    <td>{{ isset($dm['Abs_IDT']) ? number_format($dm['Abs_IDT'], 1) : '-' }}</td>
                    <td>{{ isset($dm['Abs_ST']) ? number_format($dm['Abs_ST'], 1) : '-' }}</td>
                    <td>{{ isset($dm['Abs_HT']) ? number_format($dm['Abs_HT'], 1) : '-' }}</td>
                    <td>{{ isset($dm['Abs_FT']) ? number_format($dm['Abs_FT'], 1) : '-' }}</td>
                    
                    <td class="fw-bold">{{ isset($dm['Avg_IDT']) ? number_format($dm['Avg_IDT'], 1) : '-' }}</td>
                    <td class="fw-bold">{{ isset($dm['Avg_ST']) ? number_format($dm['Avg_ST'], 1) : '-' }}</td>
                    <td class="fw-bold">{{ isset($dm['Avg_HT']) ? number_format($dm['Avg_HT'], 1) : '-' }}</td>
                    <td class="fw-bold">{{ isset($dm['Avg_FT']) ? number_format($dm['Avg_FT'], 1) : '-' }}</td>
                    
                    <td>{{ $hasil->status_berketerimaan === 'gagal_duplo' ? 'NO' : 'YES' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>

    <!-- EVALUASI DATA -->
    <h4>2. STATISTIK CONTROL CHART (IDT, ST, HT, FT)</h4>
    <table style="width: 100%; border: none;">
        <tr>
            @php $subParams = ['IDT', 'ST', 'HT', 'FT']; @endphp
            @foreach($subParams as $sub)
            <td style="width: 25%; vertical-align: top; border: none; padding: 2px;">
                <h4 style="text-align: center; margin: 0;">{{ $sub }} (℃)</h4>
                <div style="font-size: 6px; text-align: center; margin-bottom: 2px;">
                    Mean: {{ number_format($stats['aft'][$sub]['mean'], 1) }} | SD: {{ number_format($stats['aft'][$sub]['sd'], 1) }}
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Ke-</th>
                            <th>LCL</th>
                            <th class="bg-light fw-bold">Mean</th>
                            <th>UCL</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $validIndex = 1; @endphp
                        @foreach($hasilList as $hasil)
                            @if($hasil->status_berketerimaan !== 'gagal_duplo')
                                @php
                                    $dm = is_array($hasil->data_mentah) ? $hasil->data_mentah : json_decode($hasil->data_mentah, true);
                                    $val = $dm["Avg_{$sub}"] ?? 0;
                                @endphp
                                <tr>
                                    <td>{{ $validIndex++ }}</td>
                                    <td class="text-danger">{{ number_format($stats['aft'][$sub]['minus3sd'], 1) }}</td>
                                    <td class="bg-light fw-bold">{{ number_format($stats['aft'][$sub]['mean'], 1) }}</td>
                                    <td class="text-danger">{{ number_format($stats['aft'][$sub]['plus3sd'], 1) }}</td>
                                    <td class="fw-bold">{{ number_format($val, 1) }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </td>
            @endforeach
        </tr>
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
