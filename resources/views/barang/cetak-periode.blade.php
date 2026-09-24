<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventori Bahan</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm 15mm 25mm 15mm;
        }

        body { 
            font-family: sans-serif; 
            font-size: 9.5pt; 
            color: #333;
            margin: 0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            border: none;
        }
        .header-table td {
            border: none;
            padding: 0 !important; 
            vertical-align: middle;
        }
        
        .report-title {
            font-size: 13pt;
            font-weight: bold;
            text-align: left !important;
            text-transform: uppercase;
            margin: 0;
            padding: 0;
        }
        
        .logo-container {
            text-align: right !important;
            padding: 0 !important;
        }
        .logo-img {
            height: 70px; 
        }
        .header-line {
            border: none;
            border-top: 2px solid #333;
            margin-top: 8px;
            margin-bottom: 10px;
        }

        .header-subtitle { 
            font-size: 10pt; 
            text-align: left !important; 
            font-weight: bold;
            margin-top: 0;
            margin-bottom: 15px;
            padding: 0 !important;
        }

        table.data-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 5px; 
        }
        table.data-table th, table.data-table td { 
            border: 1px solid #333; 
            padding: 6px; 
            text-align: center; 
        }
        table.data-table th { 
            background-color: #f2f2f2; 
        }
        .text-start { text-align: left; }
        .fw-bold { font-weight: bold; }

        .footer {
            width: 100%;
            position: fixed;
            bottom: -15mm;
            left: 0;
            right: 0;
            font-size: 8pt;
            border-top: 1px solid #999;
            padding-top: 5px;
        }
        .footer-left {
            float: left;
        }
        .footer-right {
            float: right;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 75%;">
                <div class="report-title">LAPORAN INVENTORI BARANG PERSEDIAAN</div>
            </td>
            <td class="logo-container" style="width: 25%;">
                <img src="{{ public_path('images/Logo_Suco_Nobg.png') }}" alt="SUCOFINDO" class="logo-img">
            </td>
        </tr>
    </table>
    <hr class="header-line">

    <div class="header-subtitle">
        Periode: {{ date('F', mktime(0, 0, 0, $bulan, 10)) }} {{ $tahun }}
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 40px;">No.</th>
                <th rowspan="2">Nama Barang</th>
                <th rowspan="2" style="width: 80px;">Satuan</th>
                <th rowspan="2" style="width: 100px;">Kode Barang</th>
                <th rowspan="2" style="width: 90px;">Saldo Awal</th>
                <th colspan="2">Jumlah</th>
                <th rowspan="2" style="width: 90px;">Saldo Akhir</th>
                <th rowspan="2" style="width: 90px;">Kondisi</th>
            </tr>
            <tr>
                <th style="width: 80px;">Masuk</th>
                <th style="width: 80px;">Keluar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barang as $index => $item)
            @php
                $saldoAwal = $item->saldo_awal ?? 0;
                $penerimaan = $item->penerimaan ?? 0;
                $pengeluaran = $item->pengeluaran ?? 0;
                $saldoAkhir = ($saldoAwal + $penerimaan) - $pengeluaran;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-start">{{ $item->nama_barang }}</td>
                <td>{{ $item->satuan }}</td>
                <td>{{ $item->kode_barang }}</td>
                <td>{{ number_format($saldoAwal, 0, ',', '.') }}</td>
                <td>{{ number_format($penerimaan, 0, ',', '.') }}</td>
                <td>{{ number_format($pengeluaran, 0, ',', '.') }}</td>
                <td class="fw-bold">{{ number_format($saldoAkhir, 0, ',', '.') }}</td>
                <td>{{ ucfirst($item->kondisi) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-left">
            Dicetak oleh: {{ Auth::user()->name ?? 'System' }}
        </div>
        <div class="footer-right">
            Waktu Cetak: {{ date('d-m-Y H:i:s') }} WIB
        </div>
    </div>

</body>
</html>