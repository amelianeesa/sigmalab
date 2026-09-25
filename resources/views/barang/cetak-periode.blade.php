<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventori Bahan</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        body { 
            font-family: sans-serif; 
            font-size: 8pt; 
            color: #333;
            margin: 0;
        }

        .header { 
            width: 100%; 
            border-bottom: 2px solid #333; 
            padding-bottom: 4px; 
            padding-top: 1px;
            margin-bottom: 8px; 
        }
        .header table { 
            width: 100%; 
            border-collapse: collapse;
        }
        .title { 
            font-size: 11.5pt; 
            font-weight: bold; 
            color: #333; 
            text-align: left;
            text-transform: uppercase;
        }
        .header-subtitle { 
            font-size: 9pt; 
            font-weight: bold;
            margin-top: 2px;
            padding-bottom: 2px;
            color: #333;
        }
        .logo { 
            width: 110px; 
            padding-bottom: 14px;
        }

        table.data-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 0; 
        }
        table.data-table th, table.data-table td { 
            border: 1px solid #333; 
            padding: 4px 3px; 
            text-align: center; 
            word-wrap: break-word;
        }
        table.data-table th { 
            background-color: #f2f2f2; 
            font-size: 7.5pt;
        }
        .text-start { text-align: left; }
        .fw-bold { font-weight: bold; }

        .footer {
            width: 100%;
            position: absolute;
            bottom: -1mm;
            left: 0;
            right: 0;
            font-size: 7.5pt;
            border-top: 1px solid #999;
            padding-top: 4px;
        }
        .footer-left { float: left; }
        .footer-right { float: right; }
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
                    <div class="title">LAPORAN INVENTORI BARANG PERSEDIAAN</div>
                    <div class="header-subtitle">
                        Periode: {{ date('F', mktime(0, 0, 0, $bulan, 10)) }} {{ $tahun }}
                    </div>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <img src="{{ public_path('images/Logo_Suco_Nobg.png') }}" alt="SUCOFINDO" class="logo">
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 25px;">No.</th>
                <th rowspan="2" style="width: 110px;">Nama Barang</th>
                <th rowspan="2" style="width: 45px;">Satuan</th>
                <th rowspan="2" style="width: 60px;">Kode</th>
                <th rowspan="2" style="width: 45px;">Awal</th>
                <th colspan="2" style="width: 80px;">Jumlah</th>
                <th rowspan="2" style="width: 45px;">Akhir</th>
                <th rowspan="2" style="width: 50px;">Kondisi</th>
            </tr>
            <tr>
                <th style="width: 40px;">Masuk</th>
                <th style="width: 40px;">Keluar</th>
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

    <div class="footer clearfix">
        <div class="footer-left">
            Dicetak oleh: {{ $cetakOleh ?? 'System PT Sucofindo' }}
        </div>
        <div class="footer-right">
            Waktu Cetak: {{ date('d-m-Y H:i:s') }} WIB
        </div>
    </div>

</body>
</html>