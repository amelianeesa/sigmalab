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
        <strong>Ringkasan Statistik Batas Kendali (%db)</strong>
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

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode Sampel</th>
                <th>Avg %adb</th>
                <th>IM %</th>
                <th>Hasil / Nilai (%db)</th>
                <th>Z-Score</th>
                <th>Status / Westgard</th>
                <th>Analis</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hasilList as $index => $hasil)
                @php
                    $dm = is_array($hasil->data_mentah) ? $hasil->data_mentah : json_decode($hasil->data_mentah, true);
                    $imValue = $dm['IM'] ?? '-';
                    $avgAdb = $dm['Average_adb'] ?? '-';
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $hasil->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $hasil->kegiatan ? $hasil->kegiatan->kode_sampel : '-' }}</td>
                    <td>{{ is_numeric($avgAdb) ? number_format($avgAdb, 4) : '-' }}</td>
                    <td>{{ is_numeric($imValue) ? number_format($imValue, 4) : '-' }}</td>
                    <td>
                        @if($hasil->status_berketerimaan === 'gagal_duplo')
                            <span class="text-danger strikethrough">{{ number_format($hasil->nilai_hasil, 2) }}</span>
                        @elseif($hasil->status_berketerimaan === 'pending')
                            <span class="text-warning">PENDING (Tunggu IM)</span>
                        @else
                            <strong>{{ number_format($hasil->nilai_hasil, 2) }}</strong>
                        @endif
                    </td>
                    <td>
                        @if($hasil->status_berketerimaan === 'gagal_duplo' || $hasil->status_berketerimaan === 'pending')
                            -
                        @else
                            {{ $hasil->z_score !== null ? number_format($hasil->z_score, 2) : '-' }}
                        @endif
                    </td>
                    <td>
                        @if($hasil->status_berketerimaan === 'gagal_duplo')
                            <span class="text-danger">GAGAL DUPLO</span>
                        @elseif($hasil->status_berketerimaan === 'pending')
                            <span class="text-warning">PENDING</span>
                        @elseif($hasil->status_berketerimaan === 'outlier')
                            <span class="text-danger">OUTLIER ({{ $hasil->kode_aturan_dilanggar }})</span>
                        @else
                            <span class="text-success">INLIER</span>
                        @endif
                    </td>
                    <td>{{ $hasil->penginput ? $hasil->penginput->nama : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Tidak ada data hasil uji pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right; font-size: 10px; color: #777;">
        Dicetak oleh Sistem SigmaLab pada {{ date('d/m/Y H:i:s') }}
    </div>

</body>
</html>
