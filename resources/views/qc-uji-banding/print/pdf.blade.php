<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Uji Banding - {{ $program->kode_sampel }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; line-height: 1.3; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #000; padding: 4px; }
        th { background-color: #f2f2f2; }
        .no-border, .no-border td { border: none; }
        .header { margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .logo { font-size: 20pt; font-weight: bold; color: #1e3a8a; }
        .mt-1 { margin-top: 10px; }
        .mb-1 { margin-bottom: 10px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <div class="header">
        <table class="no-border">
            <tr>
                <td width="70%">
                    <div class="logo">SigmaLab</div>
                    <div>Quality Control - Uji Banding Antar Laboratorium</div>
                </td>
                <td width="30%" class="text-right">
                    <div>Kode Dok: <strong>FR-QC-05</strong></div>
                    <div>Tanggal Cetak: <strong>{{ date('d M Y') }}</strong></div>
                </td>
            </tr>
        </table>
    </div>

    <div class="text-center font-bold" style="font-size: 14pt; margin-bottom: 15px;">
        DATA PENGUJIAN UJI BANDING
    </div>

    <table class="no-border mb-1" style="width: 50%;">
        <tr>
            <td width="40%"><strong>Nama Program</strong></td>
            <td>: {{ $program->nama_program }}</td>
        </tr>
        <tr>
            <td><strong>Penyelenggara</strong></td>
            <td>: {{ $program->penyelenggara }}</td>
        </tr>
        <tr>
            <td><strong>Kode Sampel</strong></td>
            <td>: {{ $program->kode_sampel }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Terima</strong></td>
            <td>: {{ $program->tanggal_terima->format('d M Y') }}</td>
        </tr>
    </table>

    <hr>

    @php
        $proximateParams = $program->parameters->filter(function($p) {
            return $p->parameterUji && $p->parameterUji->kategori_parameter === 'Proximate Analysis';
        });
    @endphp

    @if($proximateParams->count() > 0)
        <h3 class="mt-1">Modul Proximate Analysis</h3>
        
        @foreach($proximateParams as $param)
            @php 
                $code = $param->parameterUji->kode_parameter; 
                $dataMentah = $param->data_mentah;
                // Parse metadata
                $metadata = $dataMentah['metadata'] ?? [];
            @endphp
            
            <div style="margin-top: 20px;">
                <div class="font-bold" style="font-size: 11pt; margin-bottom: 5px;">{{ $param->parameterUji->nama_parameter }} ({{ $code }})</div>
                
                <!-- Metadata Pengujian -->
                <table class="no-border" style="width: 100%; margin-bottom: 5px; font-size: 9pt;">
                    <tr>
                        <td width="20%"><strong>Ref No:</strong> {{ $metadata['ref_no'] ?? '-' }}</td>
                        <td width="20%"><strong>Blnc ID:</strong> {{ $metadata['blnc_id'] ?? '-' }}</td>
                        <td width="20%"><strong>Time:</strong> {{ $metadata['time'] ?? '-' }}</td>
                        <td width="20%"><strong>Furnace ID:</strong> {{ $metadata['furnace_id'] ?? '-' }}</td>
                        <td width="20%"><strong>Std Method:</strong> {{ $metadata['std_method'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Uji:</strong> {{ $metadata['tanggal_uji'] ?? '-' }}</td>
                        <td><strong>Analis:</strong> {{ $param->analis ? $param->analis->nama : '-' }}</td>
                        <td colspan="3"><strong>Indicate T:</strong> {{ $metadata['indicate_t'] ?? '-' }}</td>
                    </tr>
                </table>
                
                <!-- Tabel Kalkulasi Utama -->
                <table class="text-center" style="font-size: 8pt;">
                    <thead>
                        <tr>
                            @if($code === 'IM')
                                <th>SAMPLE ID</th>
                                <th>DISH NO</th>
                                <th>M1</th>
                                <th>M2</th>
                                <th>M3</th>
                                <th>A</th>
                                <th>B</th>
                                <th>M%</th>
                                <th colspan="2">ABSOLUTE DIFFERENCE</th>
                                <th>AVERAGE %</th>
                            @elseif($code === 'ASH')
                                <th>SAMPLE ID</th>
                                <th>DISH NO</th>
                                <th>M1</th>
                                <th>M2</th>
                                <th>M2-M1</th>
                                <th>M3</th>
                                <th>M3-M1</th>
                                <th>ASH%</th>
                                <th colspan="2">ABSOLUTE DIFFERENCE</th>
                                <th>AVERAGE %adb</th>
                            @elseif($code === 'VM')
                                <th>SAMPLE ID</th>
                                <th>DISH NO</th>
                                <th>M1</th>
                                <th>M2</th>
                                <th>M2-M1</th>
                                <th>M3</th>
                                <th>M2-M3</th>
                                <th>%LOSS</th>
                                <th>%M adb</th>
                                <th>%VM</th>
                                <th colspan="2">ABSOLUTE DIFFERENCE</th>
                                <th>AVERAGE %adb</th>
                            @elseif($code === 'FC')
                                <th>KODE SAMPEL</th>
                                <th>IM %</th>
                                <th>ASH %</th>
                                <th>VM %</th>
                                <th>FC %</th>
                                <th colspan="2">ABSOLUTE DIFFERENCE</th>
                                <th>AVERAGE %adb</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($dataMentah['data']) && is_array($dataMentah['data']))
                            @foreach($dataMentah['data'] as $row)
                                <!-- Simplo -->
                                <tr>
                                    @if($code !== 'FC')
                                        <td rowspan="2">{{ $row['sample_id'] ?? '-' }}</td>
                                        <td>{{ $row['dish_1'] ?? 'S' }}</td>
                                        <td>{{ $row['mentah']['m1_1'] ?? '' }}</td>
                                        
                                        @if($code === 'IM')
                                            <td>{{ isset($row['mentah']['m1_1'], $row['mentah']['a_1']) ? (floatval($row['mentah']['m1_1']) + floatval($row['mentah']['a_1'])) : '' }}</td>
                                            <td>{{ isset($row['mentah']['m1_1'], $row['mentah']['b_1']) ? (floatval($row['mentah']['m1_1']) + floatval($row['mentah']['b_1'])) : '' }}</td>
                                            <td>{{ $row['mentah']['a_1'] ?? '' }}</td>
                                            <td>{{ $row['mentah']['b_1'] ?? '' }}</td>
                                        @elseif($code === 'ASH')
                                            <td>{{ isset($row['mentah']['m1_1'], $row['mentah']['m2m1_1']) ? (floatval($row['mentah']['m1_1']) + floatval($row['mentah']['m2m1_1'])) : '' }}</td>
                                            <td>{{ $row['mentah']['m2m1_1'] ?? '' }}</td>
                                            <td>{{ $row['mentah']['m3_1'] ?? '' }}</td>
                                            <td>{{ isset($row['mentah']['m3_1'], $row['mentah']['m1_1']) ? (floatval($row['mentah']['m3_1']) - floatval($row['mentah']['m1_1'])) : '' }}</td>
                                        @elseif($code === 'VM')
                                            <td>{{ isset($row['mentah']['m1_1'], $row['mentah']['m2m1_1']) ? (floatval($row['mentah']['m1_1']) + floatval($row['mentah']['m2m1_1'])) : '' }}</td>
                                            <td>{{ $row['mentah']['m2m1_1'] ?? '' }}</td>
                                            <td>{{ $row['mentah']['m3_1'] ?? '' }}</td>
                                            <td>{{ isset($row['mentah']['m2m1_1'], $row['mentah']['m3_1']) ? (floatval($row['mentah']['m1_1']) + floatval($row['mentah']['m2m1_1']) - floatval($row['mentah']['m3_1'])) : '' }}</td>
                                            <td>{{ isset($row['mentah']['loss_1']) ? $row['mentah']['loss_1'] : '' }}</td>
                                            <td>{{ isset($row['mentah']['m_adb_1']) ? $row['mentah']['m_adb_1'] : '' }}</td>
                                        @endif
                                        
                                        <td class="font-bold">{{ $row['d1'] ?? '' }}</td>
                                    @else
                                        <td rowspan="2">{{ $row['kode_sampel'] ?? '-' }}</td>
                                        <td>{{ $row['im_1'] ?? '' }}</td>
                                        <td>{{ $row['ash_1'] ?? '' }}</td>
                                        <td>{{ $row['vm_1'] ?? '' }}</td>
                                        <td class="font-bold">{{ $row['d1'] ?? '' }}</td>
                                    @endif
                                    
                                    <td rowspan="2">{{ $row['diff'] ?? '-' }}</td>
                                    <td rowspan="2">{{ $row['tol'] ?? '-' }}</td>
                                    <td rowspan="2" class="font-bold">{{ $row['avg'] ?? '-' }}</td>
                                </tr>
                                <!-- Duplo -->
                                <tr>
                                    @if($code !== 'FC')
                                        <td>{{ $row['dish_2'] ?? 'D' }}</td>
                                        <td>{{ $row['mentah']['m1_2'] ?? '' }}</td>
                                        
                                        @if($code === 'IM')
                                            <td>{{ isset($row['mentah']['m1_2'], $row['mentah']['a_2']) ? (floatval($row['mentah']['m1_2']) + floatval($row['mentah']['a_2'])) : '' }}</td>
                                            <td>{{ isset($row['mentah']['m1_2'], $row['mentah']['b_2']) ? (floatval($row['mentah']['m1_2']) + floatval($row['mentah']['b_2'])) : '' }}</td>
                                            <td>{{ $row['mentah']['a_2'] ?? '' }}</td>
                                            <td>{{ $row['mentah']['b_2'] ?? '' }}</td>
                                        @elseif($code === 'ASH')
                                            <td>{{ isset($row['mentah']['m1_2'], $row['mentah']['m2m1_2']) ? (floatval($row['mentah']['m1_2']) + floatval($row['mentah']['m2m1_2'])) : '' }}</td>
                                            <td>{{ $row['mentah']['m2m1_2'] ?? '' }}</td>
                                            <td>{{ $row['mentah']['m3_2'] ?? '' }}</td>
                                            <td>{{ isset($row['mentah']['m3_2'], $row['mentah']['m1_2']) ? (floatval($row['mentah']['m3_2']) - floatval($row['mentah']['m1_2'])) : '' }}</td>
                                        @elseif($code === 'VM')
                                            <td>{{ isset($row['mentah']['m1_2'], $row['mentah']['m2m1_2']) ? (floatval($row['mentah']['m1_2']) + floatval($row['mentah']['m2m1_2'])) : '' }}</td>
                                            <td>{{ $row['mentah']['m2m1_2'] ?? '' }}</td>
                                            <td>{{ $row['mentah']['m3_2'] ?? '' }}</td>
                                            <td>{{ isset($row['mentah']['m2m1_2'], $row['mentah']['m3_2']) ? (floatval($row['mentah']['m1_2']) + floatval($row['mentah']['m2m1_2']) - floatval($row['mentah']['m3_2'])) : '' }}</td>
                                            <td>{{ isset($row['mentah']['loss_2']) ? $row['mentah']['loss_2'] : '' }}</td>
                                            <td>{{ isset($row['mentah']['m_adb_2']) ? $row['mentah']['m_adb_2'] : '' }}</td>
                                        @endif
                                        
                                        <td class="font-bold">{{ $row['d2'] ?? '' }}</td>
                                    @else
                                        <td>{{ $row['im_2'] ?? '' }}</td>
                                        <td>{{ $row['ash_2'] ?? '' }}</td>
                                        <td>{{ $row['vm_2'] ?? '' }}</td>
                                        <td class="font-bold">{{ $row['d2'] ?? '' }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        @endforeach
    @endif

</body>
</html>
