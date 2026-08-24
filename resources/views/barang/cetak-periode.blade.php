<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventori Bahan</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-start { text-align: left; }
        .fw-bold { font-weight: bold; }
        .header-title { font-size: 14pt; font-weight: bold; text-align: center; margin-bottom: 5px; }
        .header-subtitle { font-size: 11pt; text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header-title">LAPORAN INVENTORI BARANG PERSEDIAAN</div>
    <div class="header-subtitle">
        Periode: {{ date('F', mktime(0, 0, 0, $bulan, 10)) }} {{ $tahun }}
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 30px;">No.</th>
                <th rowspan="2">Nama Barang</th>
                <th rowspan="2">Satuan</th>
                <th rowspan="2">Kode Barang</th>
                <th rowspan="2">Saldo Awal</th>
                <th colspan="2">Jumlah</th>
                <th rowspan="2">Saldo Akhir</th>
                <th rowspan="2">Kondisi</th>
            </tr>
            <tr>
                <th>Masuk</th>
                <th>Keluar</th>
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
</body>
</html>
