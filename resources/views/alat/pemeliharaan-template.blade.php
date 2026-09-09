<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Pemeliharaan - {{ $alat->kode_alat }}</title>
    <style>
        @page { size: A4 portrait; margin: 10mm; }
        body { font-family: 'Arial', sans-serif; font-size: 9pt; color: #000; }
        .header { width: 100%; border-bottom: 2px solid #1f4e78; padding-bottom: 8px; margin-bottom: 15px; }
        .header table { width: 100%; border-collapse: collapse; }
        .title { font-size: 14pt; font-weight: bold; color: #333; }
        .logo { width: 130px; }
        
        .info-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        .label { font-weight: bold; width: 25%; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        table.data-table th, table.data-table td { border: 1px solid #000; padding: 3px 2px; text-align: center; font-size: 8.5pt; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td><div class="title">KARTU PEMELIHARAAN PERALATAN</div></td>
                <td style="text-align: right;">
                    <img src="{{ public_path('images/Logo_Suco_Nobg.png') }}" class="logo">
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table">
        <tr><td class="label">Nama / Kode Peralatan</td><td>: {{ $alat->nama_alat }} / {{ $alat->kode_alat }}</td></tr>
        <tr><td class="label">Merk/No. Serial</td><td>: {{ $alat->merk_tipe ?? '-' }} / {{ $alat->no_seri ?? '-' }}</td></tr>
        <tr><td class="label">No. Inventaris</td><td>: {{ $alat->no_inventaris ?? '-' }}</td></tr>
        <tr><td class="label">Unit Kerja Pemilik</td><td>: {{ $alat->unit_kerja_pemilik ?? '-' }}</td></tr>
        <tr>
            <td class="label align-top">Jenis Pemeliharaan</td>
            <td>
                <div style="width: 100%;">
                    @php
                        $totalItems = $alat->itemPemeliharaan->count();
                        $splitLimit = ceil($totalItems / 2);
                    @endphp
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 50%; vertical-align: top; border: none; padding: 0;">
                                @foreach($alat->itemPemeliharaan->take($splitLimit) as $item)
                                    <div>: {{ $item->nomor_urut }}. {{ $item->nama_pemeliharaan }}</div>
                                @endforeach
                            </td>
                            <td style="width: 50%; vertical-align: top; border: none; padding: 0;">
                                @foreach($alat->itemPemeliharaan->skip($splitLimit) as $item)
                                    <div> {{ $item->nomor_urut }}. {{ $item->nama_pemeliharaan }}</div>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr><td class="label">BULAN / TAHUN</td><td>: {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} / {{ $tahun }}</td></tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 35px;">Tanggal</th>
                @php $jumlahKolom = max(1, $totalItems); @endphp
                <th colspan="{{ $jumlahKolom }}">Jenis Pemeriksaan / Status *)</th>
                <th rowspan="2" style="width: 140px;">Tindakan</th>
                <th rowspan="2" style="width: 90px;">Petugas</th>
            </tr>
            <tr>
                @if($totalItems > 0)
                    @foreach($alat->itemPemeliharaan as $item)
                        <th style="width: 25px;">{{ $item->nomor_urut }}</th>
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
                    // Ambil log berdasarkan tanggal dan item jika ada
                @endphp
                <tr>
                    <td>{{ $d }}</td>
                    @if($totalItems > 0)
                        @foreach($alat->itemPemeliharaan as $currentItem)
                            @php
                                $key = $currentItem->item_id . '_' . $d;
                                $isChecked = isset($logs[$key]) && $logs[$key]->status == 1;
                            @endphp
                            <td>{{ $isChecked ? 'v' : '' }}</td>
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
</body>
</html>