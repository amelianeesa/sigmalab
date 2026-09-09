<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inhouse Control - {{ $selectedParameter->nama_parameter }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 5px 0 0; color: #555; }
        .summary-box { margin-bottom: 20px; border: 1px solid #ccc; padding: 10px; border-radius: 5px; }
        .summary-box table { width: 100%; }
        .summary-box td { padding: 5px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px; text-align: center; }
        table.data th { background-color: #f0f0f0; }
        .text-danger { color: red; }
        .text-success { color: green; }
        .text-warning { color: orange; }
        .font-weight-bold { font-weight: bold; }
        .strikethrough { text-decoration: line-through; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN INHOUSE CONTROL (CONTROL CHART)</h1>
        <p>Parameter Uji: <strong>{{ $selectedParameter->nama_parameter }}</strong> ({{ $selectedParameter->satuan }})</p>
        <p>Periode: 
            {{ $request->filled('tanggal_mulai') ? \Carbon\Carbon::parse($request->tanggal_mulai)->format('d M Y') : 'Awal' }} 
            s/d 
            {{ $request->filled('tanggal_akhir') ? \Carbon\Carbon::parse($request->tanggal_akhir)->format('d M Y') : 'Sekarang' }}
        </p>
    </div>

    <div class="summary-box">
        <strong>Ringkasan Statistik Batas Kendali (Control Limits)</strong>
        <table>
            <tr>
                <td><strong>Mean (\u03bc):</strong> {{ number_format($stats['mean'], 4) }}</td>
                <td><strong>SD (\u03c3):</strong> {{ number_format($stats['sd'], 4) }}</td>
                <td><strong>UCL (+3\u03c3):</strong> <span class="text-danger">{{ number_format($stats['plus3sd'], 4) }}</span></td>
                <td><strong>LCL (-3\u03c3):</strong> <span class="text-danger">{{ number_format($stats['minus3sd'], 4) }}</span></td>
            </tr>
            <tr>
                <td><strong>UWL (+2\u03c3):</strong> <span class="text-warning">{{ number_format($stats['plus2sd'], 4) }}</span></td>
                <td><strong>LWL (-2\u03c3):</strong> <span class="text-warning">{{ number_format($stats['minus2sd'], 4) }}</span></td>
                <td><strong>+1\u03c3:</strong> <span class="text-success">{{ number_format($stats['plus1sd'], 4) }}</span></td>
                <td><strong>-1\u03c3:</strong> <span class="text-success">{{ number_format($stats['minus1sd'], 4) }}</span></td>
            </tr>
        </table>
    </div>

    @if(isset($chartImage) && !empty($chartImage))
    <div style="margin-bottom: 20px; text-align: center;">
        <img src="{{ $chartImage }}" alt="Control Chart" style="max-width: 100%; max-height: 400px; border: 1px solid #ccc;">
    </div>
    @endif

    <table class="data" style="font-size: 10px;">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Tanggal</th>
                <th rowspan="2">Kode Sampel</th>
                <th rowspan="2">Dish No.</th>
                <th>M1</th>
                <th>M2</th>
                <th>A</th>
                <th>M3</th>
                <th>B</th>
                <th rowspan="2">M%</th>
                <th colspan="2">Absolute Diff.</th>
                <th rowspan="2">Average M%</th>
                <th rowspan="2">Status</th>
            </tr>
            <tr>
                <th colspan="5">Manual &amp; Auto</th>
                <th>Value</th>
                <th>Eval (<= 0.10)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hasilList as $index => $hasil)
                @php
                    $dm = is_array($hasil->data_mentah) ? $hasil->data_mentah : json_decode($hasil->data_mentah, true);
                    $M1_1 = $dm['M1_D1'] ?? '-';
                    $A_1  = $dm['A_D1'] ?? '-';
                    $M3_1 = $dm['M3_D1'] ?? '-';
                    $M2_1 = $dm['M2_D1_Result'] ?? (is_numeric($M1_1) && is_numeric($A_1) ? number_format($M1_1 + $A_1, 4) : '-');
                    $B1   = is_numeric($M1_1) && is_numeric($M3_1) ? number_format($M3_1 - $M1_1, 4) : '-';
                    $M_1  = $dm['M_D1_Result'] ?? '-';
                    
                    $M1_2 = $dm['M1_D2'] ?? '-';
                    $A_2  = $dm['A_D2'] ?? '-';
                    $M3_2 = $dm['M3_D2'] ?? '-';
                    $M2_2 = $dm['M2_D2_Result'] ?? (is_numeric($M1_2) && is_numeric($A_2) ? number_format($M1_2 + $A_2, 4) : '-');
                    $B2   = is_numeric($M1_2) && is_numeric($M3_2) ? number_format($M3_2 - $M1_2, 4) : '-';
                    $M_2  = $dm['M_D2_Result'] ?? '-';
                    
                    $absDiff = $dm['Absolute_Diff'] ?? '-';
                    $avgM = is_numeric($M_1) && is_numeric($M_2) ? ($M_1 + $M_2) / 2 : 0;
                    $tolDin = 0.09 + (0.1 * $avgM);
                    $isYes = is_numeric($absDiff) && $absDiff <= $tolDin;
                @endphp
                <tr>
                    <td rowspan="2">{{ $index + 1 }}</td>
                    <td rowspan="2">{{ $hasil->created_at->format('d/m/Y') }}</td>
                    <td rowspan="2">{{ $hasil->kegiatan ? $hasil->kegiatan->kode_sampel : '-' }}</td>
                    
                    <td>Dish 1</td>
                    <td>{{ $M1_1 }}</td>
                    <td>{{ $M2_1 }}</td>
                    <td>{{ $A_1 }}</td>
                    <td>{{ $M3_1 }}</td>
                    <td>{{ $B1 }}</td>
                    <td>{{ is_numeric($M_1) ? number_format($M_1, 4) : $M_1 }}</td>
                    
                    <td rowspan="2">{{ is_numeric($absDiff) ? number_format($absDiff, 4) : $absDiff }}</td>
                    <td rowspan="2">
                        @if(is_numeric($absDiff))
                            @if($isYes) <span class="text-success">YES</span> @else <span class="text-danger">NO</span> @endif
                        @else
                            -
                        @endif
                    </td>
                    <td rowspan="2">
                        @if($hasil->status_berketerimaan === 'gagal_duplo')
                            <span class="text-danger strikethrough">{{ number_format($hasil->nilai_hasil, 4) }}</span>
                        @else
                            <strong>{{ number_format($hasil->nilai_hasil, 4) }}</strong>
                        @endif
                    </td>
                    <td rowspan="2">
                        @if($hasil->status_berketerimaan === 'gagal_duplo')
                            <span class="text-danger">GAGAL DUPLO</span>
                        @elseif($hasil->status_berketerimaan === 'outlier')
                            <span class="text-danger">OUTLIER ({{ $hasil->kode_aturan_dilanggar }})</span>
                        @else
                            <span class="text-success">INLIER</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Dish 2</td>
                    <td>{{ $M1_2 }}</td>
                    <td>{{ $M2_2 }}</td>
                    <td>{{ $A_2 }}</td>
                    <td>{{ $M3_2 }}</td>
                    <td>{{ $B2 }}</td>
                    <td>{{ is_numeric($M_2) ? number_format($M_2, 4) : $M_2 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14">Tidak ada data hasil uji pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right; font-size: 10px; color: #777;">
        Dicetak oleh Sistem SigmaLab pada {{ date('d/m/Y H:i:s') }}
    </div>

</body>
</html>
