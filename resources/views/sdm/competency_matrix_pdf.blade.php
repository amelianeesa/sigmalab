<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Competency Matrix</title>
    <style>
        @page { margin: 24px 28px; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1f2937;
            font-size: 11px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .header-table td {
            vertical-align: top;
        }
        .header-table .title-cell h1 {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin: 0;
            text-transform: uppercase;
        }
        .header-table .logo-cell {
            width: 70px;
            text-align: right;
        }
        .header-table .logo-cell img {
            width: 60px;
        }

        table.meta {
            width: 100%;
            margin-bottom: 14px;
            font-size: 10.5px;
            color: #1f2937;
        }
        table.meta td {
            padding: 2px 0;
        }
        table.meta td.label {
            width: 110px;
            font-weight: normal;
        }
        table.meta td.sep {
            width: 12px;
        }

        table.matrix {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.matrix th, table.matrix td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 10px;
        }
        table.matrix thead th {
            background-color: #e9e9e9;
            color: #1f2937;
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
        }
        table.matrix td.nama {
            font-weight: bold;
        }
        table.matrix td.jabatan {
            font-weight: normal;
            color: #6b7280;
            font-size: 9px;
        }
        table.matrix td.sel {
            text-align: center;
        }

        table.footer-table {
            width: 100%;
            border-top: 1px solid #d1d5db;
            padding-top: 6px;
            margin-top: 10px;
            font-size: 9px;
            color: #6b7280;
        }
        table.footer-table td {
            padding: 0;
        }
        table.footer-table .footer-left {
            text-align: left;
        }
        table.footer-table .footer-center {
            text-align: center;
        }
        table.footer-table .footer-right {
            text-align: right;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="title-cell">
                <h1>Competency Matrix</h1>
            </td>
            <td class="logo-cell">
                <img src="{{ public_path('images/Logo_Suco_Nobg.png') }}" alt="Logo">
            </td>
        </tr>
    </table>

    <table class="meta">
        <tr>
            <td class="label">Unit Kerja</td>
            <td class="sep">:</td>
            <td>Cabang Cilacap</td>
        </tr>
        <tr>
            <td class="label">Kategori</td>
            <td class="sep">:</td>
            <td>{{ $kategori ? ($kategoriOptions[$kategori] ?? $kategori) : 'Seluruh Kategori' }}</td>
        </tr>
        <tr>
            <td class="label">Sertifikasi</td>
            <td class="sep">:</td>
            <td>{{ $jenisSertifikasi }}</td>
        </tr>
    </table>

    <table class="matrix">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 160px;">Nama Personil</th>
                <th>Status Sertifikasi</th>
                <th>Berlaku Sampai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($matrix as $index => $row)
                <tr>
                    <td class="sel">{{ $index + 1 }}</td>
                    <td>
                        <div class="nama">{{ $row['personil']->nama }}</div>
                        <div class="jabatan">{{ $row['personil']->jabatan }}</div>
                    </td>
                    <td class="sel">{{ $row['status']['label'] ?? 'Belum Pernah' }}</td>
                    <td class="sel">{{ $row['kompetensi']?->tanggal_berakhir?->format('d-m-Y') ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Belum ada data personil aktif yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-table">
        <tr>
            <td class="footer-left">FOR/SCI-SDM/01</td>
            <td class="footer-center">Dicetak: {{ $tanggalCetak }}</td>
            <td class="footer-right">Total: {{ count($matrix) }} personil</td>
        </tr>
    </table>

</body>
</html>