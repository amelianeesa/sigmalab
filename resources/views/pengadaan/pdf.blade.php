<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengadaan Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .signature-section {
            width: 100%;
            margin-top: 50px;
        }
        .signature-left {
            float: left;
            width: 40%;
            text-align: center;
        }
        .signature-right {
            float: right;
            width: 40%;
            text-align: center;
        }
        .clear {
            clear: both;
        }
        .sign-space {
            height: 80px;
        }
        .fw-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN PENGADAAN BARANG / BAHAN</h2>
        <p>Periode: {{ \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') }} {{ $tahun }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Barang</th>
                <th width="15%">Tgl Pengajuan</th>
                <th width="15%">Pemohon</th>
                <th width="15%">Jumlah</th>
                <th width="15%">Status</th>
                <th width="15%">Penyetuju</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengadaans as $p)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $p->barang ? $p->barang->nama_barang : 'Barang Dihapus' }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d/m/Y') }}</td>
                <td>{{ $p->pemohon ? ($p->pemohon->personil->nama ?? $p->pemohon->username) : '-' }}</td>
                <td class="text-center">{{ (float) $p->jumlah_diminta }} {{ $p->barang ? $p->barang->satuan : '' }}</td>
                <td class="text-center">{{ ucfirst($p->status) }}</td>
                <td>{{ $p->penyetuju ? ($p->penyetuju->personil->nama ?? $p->penyetuju->username) : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data pengadaan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-left">
            <br>
            Disetujui Oleh,<br>
            <div class="sign-space"></div>
            <span class="fw-bold">{{ $kabidName }}</span><br>
            Kabid Dukungan Bisnis
        </div>
        <div class="signature-right">
            Cilacap, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
            Disiapkan Oleh,<br>
            <div class="sign-space"></div>
            <span class="fw-bold">{{ $hrgaName }}</span><br>
            HR & GA Officer 3
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>
