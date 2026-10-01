<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Pemeliharaan - {{ $alat->kode_alat }}</title>
    <style>
        @page { 
            size: A4 portrait; 
            margin: 10mm; 
        }
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 9pt; 
            color: #000; 
            margin: 0;
        }
        .header { 
            width: 100%; 
            border-bottom: 2px solid #333; 
            padding-bottom: 6px; 
            margin-bottom: 5px; 
        }
        .header table { 
            width: 100%; 
            border-collapse: collapse;
        }
        .title { 
            font-size: 13pt; 
            font-weight: bold; 
            color: #333; 
            text-align: left;
        }
        .logo { 
            width: 110px; 
        }
        
        .info-table { 
            width: 100%; 
            margin-bottom: 8px; 
            border-collapse: collapse; 
        }
        .info-table td { 
            padding: 2px 0;
            vertical-align: top; 
        }
        .label { 
            font-weight: bold; 
            width: 24%; 
        }
        
        table.data-table {
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 5px; 
        }
        table.data-table th, 
        table.data-table td {
            border: 1px solid #000; 
            padding: 2px 2px; 
            text-align: center; 
            font-size: 8pt; 
        }
        th { 
            background-color: #f2f2f2;
        }

        .footer {
            width: 100%;
            position: absolute;
            bottom: -1mm;
            left: 0;
            right: 0;
            font-size: 8pt;
            border-top: 1px solid #999;
            padding-top: 4px;
        }
        .footer-left { 
            float: left; 
        }
        .footer-right { 
            float: right; 
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td style="text-align: left; vertical-align: middle;">
                    <div class="title">KARTU PEMELIHARAAN PERALATAN</div>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <img src="{{ public_path('images/Logo_Suco_Nobg.png') }}" class="logo">
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table">
        <tr><td class="label">Nama / Kode Peralatan</td><td>: {{ $alat->nama_alat }} / {{ $alat->kode_alat }}</td></tr>
        <tr><td class="label">Merk / No. Serial</td><td>: {{ $alat->merk_tipe ?? '-' }} / {{ $alat->no_seri ?? '-' }}</td></tr>
        <tr><td class="label">No. Inventaris</td><td>: {{ $alat->no_inventaris ?? '-' }}</td></tr>
        <tr><td class="label">Unit Kerja Pemilik</td><td>: {{ $alat->unit_kerja_pemilik ?? '-' }}</td></tr>
        <tr>
            <td class="label align-top">Jenis Pemeliharaan</td>
            <td>
                @php
                    $totalItems = $alat->itemPemeliharaan->count();
                @endphp
                @if($totalItems > 0)
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        @foreach($alat->itemPemeliharaan as $index => $item)
                            <tr>
                                <td style="width: 15px; border: none; padding: 0; vertical-align: top;">{{ $index === 0 ? ':' : '' }}</td>
                                <td style="border: none; padding: 0; vertical-align: top;">{{ $item->nomor_urut }}. {{ $item->nama_pemeliharaan }}</td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    : -
                @endif
            </td>
        </tr>
        <tr><td class="label">BULAN / TAHUN</td><td>: {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} / {{ $tahun }}</td></tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 30px;">Tanggal</th>
                @php $jumlahKolom = max(1, $totalItems); @endphp
                <th colspan="{{ $jumlahKolom }}">Jenis Pemeriksaan / Status</th>
                <th rowspan="2" style="width: 130px;">Tindakan</th>
                <th rowspan="2" style="width: 85px;">Petugas</th>
            </tr>
            <tr>
                @if($totalItems > 0)
                    @foreach($alat->itemPemeliharaan as $item)
                        <th style="width: 22px;">{{ $item->nomor_urut }}</th>
                    @endforeach
                @else
                    <th>-</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            @endphp
            @for($d = 1; $d <= $daysInMonth; $d++)
                @php
                    $dateStr = sprintf('%04d-%02d-%02d', $tahun, $bulan, $d);
                @endphp
                <tr>
                    <td>{{ $d }}</td>
                    @if($totalItems > 0)
                        @foreach($alat->itemPemeliharaan as $currentItem)
                            @php
                                $key = $currentItem->item_id . '_' . $d;
                                $isChecked = isset($logs[$key]) && (is_object($logs[$key]) ? $logs[$key]->status == 1 : $logs[$key] == 1);
                            @endphp
                            <td>{{ $isChecked ? 'V' : '' }}</td>
                        @endforeach
                    @else
                        <td>-</td>
                    @endif
                    <td>{{ isset($logs[$d]['tindakan']) ? $logs[$d]['tindakan'] : '' }}</td>
                    <td>{{ isset($logs[$d]['petugas']) ? $logs[$d]['petugas'] : '' }}</td>
                </tr>
            @endfor
        </tbody>
    </table>

    <!-- Footer di bagian bawah halaman -->
    <div class="footer clearfix">
        <div class="footer-left">
            Dicetak oleh: {{ $namaUser }}
        </div>
        <div class="footer-right">
            Waktu Cetak: {{ date('d-m-Y H:i:s') }} WIB
        </div>
    </div>
</body>
</html>