<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Induk Dokumen</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 11px;
            color: #000;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .header-table td {
            vertical-align: middle;
            padding: 0;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 12px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 14px;
        }
        .meta-table td {
            padding: 1px 0;
            font-size: 11px;
        }
        .meta-label {
            width: 110px;
        }
        table.doc-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.doc-table th, table.doc-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            font-size: 10.5px;
            vertical-align: middle;
        }
        table.doc-table th {
            background-color: #e9e9e9;
            text-align: center;
            font-weight: bold;
        }
        .col-no { width: 4%; text-align: center; }
        .col-nomor { width: 15%; }
        .col-nama { width: 37%; }
        .col-revisi { width: 8%; text-align: center; }
        .col-tanggal { width: 13%; text-align: center; }
        .col-penerbit { width: 23%; }
        .footer-table {
            width: 100%;
            margin-top: 18px;
            font-size: 10px;
            border-top: 1px solid #000;
            padding-top: 6px;
        }
        .footer-table td {
            padding: 0;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 75%;">
                <div class="title">DAFTAR INDUK DOKUMEN</div>
            </td>
            <td style="width: 25%; text-align: right;">
                <img src="{{ $logoBase64 }}" style="height: 45px;">
            </td>
        </tr>
    </table>

    <table class="meta-table">
        <tr>
            <td class="meta-label">Unit Kerja</td>
            <td style="width: 10px;">:</td>
            <td>Cabang Cilacap</td>
        </tr>
        <tr>
            <td class="meta-label">Jenis Dokumen</td>
            <td>:</td>
            <td>{{ $category->nama_kategori ?? 'Seluruh Kategori' }}</td>
        </tr>
    </table>

    <table class="doc-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-nomor">Nomor Dokumen</th>
                <th class="col-nama">Nama Dokumen</th>
                <th class="col-revisi">Revisi</th>
                <th class="col-tanggal">Tanggal Berlaku</th>
                <th class="col-penerbit">Penerbit Dokumen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($documents as $index => $document)
                @php
                    $latestVersion = $document->versions->sortByDesc('revisi_ke')->first();
                @endphp
                <tr>
                    <td class="col-no">{{ $index + 1 }}</td>
                    <td>{{ $document->nomor_dokumen ?? '-' }}</td>
                    <td>{{ $document->judul }}</td>
                    <td class="col-revisi">{{ $latestVersion?->version_number ?? '00' }}</td>
                    <td class="col-tanggal">{{ $latestVersion?->tanggal_berlaku?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $document->penerbit_dokumen ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada dokumen pada kategori ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-table">
        <tr>
            <td style="width: 33%;">FOR/SCI-QA/01</td>
            <td style="width: 34%;" class="text-center">Dicetak: {{ $tanggalCetak }}</td>
            <td style="width: 33%;" class="text-right">Total: {{ $documents->count() }} dokumen</td>
        </tr>
    </table>

</body>
</html>