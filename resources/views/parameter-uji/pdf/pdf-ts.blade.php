<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Inhouse Control - {{ $selectedParameter->nama_parameter }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        h2, h3, h4 { text-align: center; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-danger { color: red; }
        .text-warning { color: orange; }
        .fw-bold { font-weight: bold; }
        .bg-light { background-color: #f9f9f9; }
        .info-table { width: 100%; margin-top: 20px; margin-bottom: 20px; border: none; }
        .info-table td { border: none; text-align: left; padding: 3px; }
        .chart-img { text-align: center; margin-top: 30px; }
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
            <td width="35%">: {{ $selectedParameter->toleransi_duplo ?? 0.05 }} (Abs. Diff)</td>
        </tr>
        <tr>
            <td><strong>Mean (\u03bc)</strong></td>
            <td>: {{ number_format($chartData['stats']['mean'], 4) }}</td>
            <td><strong>Standar Deviasi (\u03c3)</strong></td>
            <td>: {{ number_format($chartData['stats']['sd'], 4) }}</td>
        </tr>
        <tr>
            <td><strong>UCL (+3\u03c3)</strong></td>
            <td>: <span class="text-danger fw-bold">{{ number_format($chartData['stats']['plus3sd'], 4) }}</span></td>
            <td><strong>LCL (-3\u03c3)</strong></td>
            <td>: <span class="text-danger fw-bold">{{ number_format($chartData['stats']['minus3sd'], 4) }}</span></td>
        </tr>
    </table>

    <!-- RAW DATA -->
    <h4>1. RAW DATA LOGGER</h4>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Tanggal</th>
                <th rowspan="2">Uji Ke-</th>
                <th rowspan="2">Kode Sampel</th>
                <th colspan="2">DISH 1</th>
                <th colspan="2">DISH 2</th>
                <th rowspan="2">Abs. Diff</th>
                <th rowspan="2">Avg (adb)</th>
                <th rowspan="2">IM %</th>
                <th rowspan="2">Avg % (db)</th>
            </tr>
            <tr>
                <th>Massa (g)</th>
                <th>TS % (adb)</th>
                <th>Massa (g)</th>
                <th>TS % (adb)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasilList as $index => $hasil)
                @php
                    $dm = is_array($hasil->data_mentah) ? $hasil->data_mentah : json_decode($hasil->data_mentah, true);
                @endphp
                <tr>
                    <td>{{ $hasil->created_at->format('d/m/Y') }}</td>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $hasil->kegiatan ? $hasil->kegiatan->kode_sampel : '-' }}</td>
                    <td>{{ isset($dm['Massa_D1']) ? number_format($dm['Massa_D1'], 4) : '-' }}</td>
                    <td>{{ isset($dm['TS_adb_D1']) ? number_format($dm['TS_adb_D1'], 4) : '-' }}</td>
                    <td>{{ isset($dm['Massa_D2']) ? number_format($dm['Massa_D2'], 4) : '-' }}</td>
                    <td>{{ isset($dm['TS_adb_D2']) ? number_format($dm['TS_adb_D2'], 4) : '-' }}</td>
                    
                    <td>{{ isset($dm['Absolute_Diff']) ? number_format($dm['Absolute_Diff'], 4) : '-' }}</td>
                    <td>{{ isset($dm['Average_adb']) ? number_format($dm['Average_adb'], 4) : '-' }}</td>
                    <td>{{ isset($dm['IM']) && is_numeric($dm['IM']) ? number_format($dm['IM'], 4) : '-' }}</td>
                    
                    <td class="fw-bold">
                        @if($hasil->status_berketerimaan === 'pending')
                            PENDING
                        @elseif($hasil->status_berketerimaan === 'gagal_duplo')
                            <del>{{ number_format($hasil->nilai_hasil, 4) }}</del> (X)
                        @else
                            {{ number_format($hasil->nilai_hasil, 4) }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>

    <!-- EVALUASI DATA -->
    <h4>2. STATISTIK CONTROL CHART</h4>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Uji Ke-</th>
                <th>LCL (-3\u03c3)</th>
                <th>\u03bc - 1\u03c3</th>
                <th>Mean (\u03bc)</th>
                <th>\u03bc + 1\u03c3</th>
                <th>UCL (+3\u03c3)</th>
                <th>Nilai TS (%db)</th>
                <th>Status Validasi</th>
            </tr>
        </thead>
        <tbody>
            @php $validIndex = 1; @endphp
            @foreach($hasilList as $hasil)
                @if($hasil->status_berketerimaan !== 'gagal_duplo' && $hasil->status_berketerimaan !== 'pending')
                <tr>
                    <td>{{ $hasil->created_at->format('d/m/Y') }}</td>
                    <td>{{ $validIndex++ }}</td>
                    <td class="text-danger">{{ number_format($chartData['stats']['minus3sd'], 2) }}</td>
                    <td>{{ number_format($chartData['stats']['minus1sd'], 2) }}</td>
                    <td class="bg-light fw-bold">{{ number_format($chartData['stats']['mean'], 2) }}</td>
                    <td>{{ number_format($chartData['stats']['plus1sd'], 2) }}</td>
                    <td class="text-danger">{{ number_format($chartData['stats']['plus3sd'], 2) }}</td>
                    <td class="fw-bold">{{ number_format($hasil->nilai_hasil, 2) }}</td>
                    <td>
                        @if($hasil->status_berketerimaan === 'outlier')
                            OUTLIER ({{ $hasil->kode_aturan_dilanggar }})
                        @else
                            IN-CONTROL
                        @endif
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <!-- Ttd -->
    <table class="info-table" style="margin-top: 50px;">
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
