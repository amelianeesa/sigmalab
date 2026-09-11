<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inhouse Control - Bias Test VM</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 16px; }
        .header p { margin: 5px 0 0; color: #555; }
        
        .t-test-box { margin-bottom: 15px; border: 1px solid #ccc; padding: 10px; border-radius: 5px; }
        .t-test-box table { width: 100%; border-collapse: collapse; }
        .t-test-box td { padding: 4px; }
        
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th, table.data td { border: 1px solid #000; padding: 4px; text-align: center; }
        table.data th { background-color: #f0f0f0; }
        
        .text-danger { color: red; }
        .text-success { color: green; }
        .font-weight-bold { font-weight: bold; }
        
        .conclusion { margin-top: 10px; text-align: center; font-size: 12px; font-weight: bold; padding: 8px; border: 1px solid #ccc; background-color: #eee; }
        .conclusion.no-bias { border-color: green; color: green; background-color: #e6ffe6; }
        .conclusion.bias { border-color: red; color: red; background-color: #ffe6e6; }
        
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN INHOUSE CONTROL (BIAS TEST)</h1>
        <p>Parameter Uji: <strong>Bias Test VM (Pt)</strong></p>
        <p>Periode: 
            {{ $request->filled('tanggal_mulai') ? \Carbon\Carbon::parse($request->tanggal_mulai)->format('d M Y') : 'Awal' }} 
            s/d 
            {{ $request->filled('tanggal_akhir') ? \Carbon\Carbon::parse($request->tanggal_akhir)->format('d M Y') : 'Sekarang' }}
        </p>
    </div>

    @forelse($hasilList as $idx => $hasil)
        @php
            $dm = is_array($hasil->data_mentah) ? $hasil->data_mentah : json_decode($hasil->data_mentah, true);
            $totalReps = $dm['total_reps'] ?? 0;
            $imValue = $dm['IM'] ?? '-';
            $tTest = $dm['t_test'] ?? null;
        @endphp
        
        <div style="margin-bottom: 10px;">
            <strong>Tanggal:</strong> {{ $hasil->created_at->format('d/m/Y') }} | 
            <strong>Kode Sampel:</strong> {{ $hasil->kegiatan->kode_sampel ?? '-' }} |
            <strong>Analis:</strong> {{ $hasil->penginput ? $hasil->penginput->nama : '-' }}
        </div>

        <table class="data">
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th>M1</th>
                    <th>M2</th>
                    <th>M2-M1</th>
                    <th>M3</th>
                    <th>M2-M3</th>
                    <th rowspan="2">LOSS%</th>
                    <th rowspan="2">IM</th>
                    <th rowspan="2">VM%</th>
                    <th colspan="2">Absolute Difference</th>
                    <th rowspan="2">Avg %adb</th>
                    <th rowspan="2">Avg %db</th>
                </tr>
                <tr>
                    <th colspan="5">Manual & Auto</th>
                    <th>Value</th>
                    <th>Eval (< 1.00)</th>
                </tr>
            </thead>
            <tbody>
                @for($r = 1; $r <= $totalReps; $r++)
                    @php
                        $M1 = $dm["M1_R{$r}"] ?? '-';
                        $M2M1 = $dm["M2M1_R{$r}"] ?? '-';
                        $M3 = $dm["M3_R{$r}"] ?? '-';
                        $M2 = $dm["M2_R{$r}_Result"] ?? '-';
                        $M2M3 = $dm["M2M3_R{$r}_Result"] ?? '-';
                        $Loss = $dm["Loss_R{$r}_Result"] ?? '-';
                        $VM = $dm["VM_R{$r}_Result"] ?? '-';
                        
                        $isOdd = ($r % 2 == 1);
                        $pairIdx = intdiv($r - 1, 2) + 1;
                        $absDiff = $dm["Pair{$pairIdx}_AbsDiff"] ?? '-';
                        $pairEval = $dm["Pair{$pairIdx}_Eval"] ?? '-';
                        $avgAdb = $dm["Pair{$pairIdx}_AvgAdb"] ?? '-';
                        $avgDb = $dm["Pair{$pairIdx}_AvgDb"] ?? '-';
                    @endphp
                    <tr>
                        <td class="font-weight-bold">{{ $r }}</td>
                        <td>{{ $M1 }}</td>
                        <td style="background-color: #fafafa;">{{ is_numeric($M2) ? number_format($M2, 4) : $M2 }}</td>
                        <td>{{ $M2M1 }}</td>
                        <td>{{ $M3 }}</td>
                        <td style="background-color: #fafafa;">{{ is_numeric($M2M3) ? number_format($M2M3, 4) : $M2M3 }}</td>
                        <td>{{ is_numeric($Loss) ? number_format($Loss, 4) : $Loss }}</td>
                        
                        @if($r == 1)
                            <td rowspan="{{ $totalReps }}" class="font-weight-bold">{{ is_numeric($imValue) ? number_format($imValue, 4) : $imValue }}</td>
                        @endif
                        
                        <td class="font-weight-bold">{{ is_numeric($VM) ? number_format($VM, 4) : $VM }}</td>
                        
                        @if($isOdd && ($r + 1) <= $totalReps)
                            <td rowspan="2">{{ is_numeric($absDiff) ? number_format($absDiff, 4) : $absDiff }}</td>
                            <td rowspan="2" class="{{ $pairEval === 'YES' ? 'text-success' : 'text-danger' }}">{{ $pairEval }}</td>
                            <td rowspan="2">{{ is_numeric($avgAdb) ? number_format($avgAdb, 4) : $avgAdb }}</td>
                            <td rowspan="2" class="font-weight-bold text-success">{{ is_numeric($avgDb) ? number_format($avgDb, 2) : $avgDb }}</td>
                        @elseif($isOdd && ($r + 1) > $totalReps)
                            <td colspan="4" class="text-muted">-</td>
                        @endif
                    </tr>
                @endfor
            </tbody>
        </table>

        @if($tTest)
            <div class="t-test-box" style="margin-top: 15px;">
                <strong>Kesimpulan Statistik (T-Test)</strong>
                <table style="margin-top: 10px;">
                    <tr>
                        <td><strong>Jumlah Pasangan (n):</strong> {{ $tTest['n'] }}</td>
                        <td><strong>Derajat Kebebasan (df):</strong> {{ $tTest['df'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Mean Average %db:</strong> {{ number_format($tTest['mean'], 4) }}</td>
                        <td><strong>Tingkat Kepercayaan (α):</strong> {{ $tTest['alpha'] }} (two-tailed)</td>
                    </tr>
                    <tr>
                        <td><strong>Standar Deviasi (SD):</strong> {{ number_format($tTest['sd'], 4) }}</td>
                        <td><strong>t-hitung:</strong> <span class="{{ $tTest['t_hitung'] < $tTest['t_tabel'] ? 'text-success' : 'text-danger' }} font-weight-bold">{{ number_format($tTest['t_hitung'], 4) }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Reference Value:</strong> {{ number_format($tTest['reference'], 4) }}</td>
                        <td><strong>t-tabel:</strong> {{ number_format($tTest['t_tabel'], 4) }}</td>
                    </tr>
                </table>
                
                <div class="conclusion {{ $tTest['conclusion'] === 'Tidak Ada Bias' ? 'no-bias' : 'bias' }}">
                    @if($tTest['conclusion'] === 'Tidak Ada Bias')
                        TIDAK ADA BIAS: t-hitung ({{ number_format($tTest['t_hitung'], 4) }}) < t-tabel ({{ number_format($tTest['t_tabel'], 4) }})
                    @else
                        ADA BIAS: t-hitung ({{ number_format($tTest['t_hitung'], 4) }}) >= t-tabel ({{ number_format($tTest['t_tabel'], 4) }})
                    @endif
                </div>
            </div>
        @endif

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif

    @empty
        <p style="text-align: center;">Tidak ada data hasil uji pada periode ini.</p>
    @endforelse

    <div style="margin-top: 30px; text-align: right; font-size: 9px; color: #777;">
        Dicetak oleh Sistem SigmaLab pada {{ date('d/m/Y H:i:s') }}
    </div>

</body>
</html>
