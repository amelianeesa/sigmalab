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
        .header {
            border-bottom: 2px solid #0b1f36;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .header table { width: 100%; border-collapse: collapse; }
        .header .logo-cell { width: 55px; vertical-align: middle; }
        .header .logo-cell img { width: 46px; }
        .header .company-cell { vertical-align: middle; padding-left: 10px; }
        .header .company-name {
            font-size: 13px;
            font-weight: bold;
            color: #0b1f36;
        }
        .header .company-unit {
            font-size: 9.5px;
            color: #6b7280;
        }
        .header h1 {
            font-size: 16px;
            margin: 10px 0 2px 0;
            color: #0b1f36;
        }
        .header .subtitle {
            font-size: 10.5px;
            color: #6b7280;
            margin: 0;
        }
        .meta {
            width: 100%;
            margin-bottom: 12px;
            font-size: 10px;
            color: #4b5563;
        }
        .meta td { padding: 1px 0; }
        .legend {
            margin-bottom: 12px;
        }
        .legend span {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 10px;
            font-size: 9.5px;
            font-weight: bold;
            margin-right: 6px;
            color: #fff;
        }
        .legend .aktif { background-color: #198754; }
        .legend .segera { background-color: #b8860b; }
        .legend .kedaluwarsa { background-color: #dc3545; }
        .legend .belum { background-color: #9ca3af; }

        table.matrix {
            width: 100%;
            border-collapse: collapse;
        }
        table.matrix th, table.matrix td {
            border: 1px solid #d1d5db;
            padding: 6px 7px;
            font-size: 10px;
        }
        table.matrix thead th {
            background-color: #0b1f36;
            color: #fff;
            text-align: left;
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
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 8px;
            font-size: 9px;
            font-weight: bold;
            color: #fff;
        }
        .badge.aktif { background-color: #198754; }
        .badge.segera { background-color: #b8860b; }
        .badge.kedaluwarsa { background-color: #dc3545; }
        .badge.belum { background-color: #9ca3af; }

        .footer-note {
            margin-top: 14px;
            font-size: 9px;
            color: #9ca3af;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('images/logo.png') }}" alt="Logo">
                </td>
                <td class="company-cell">
                    <div class="company-name">PT Sucofindo (Persero)</div>
                    <div class="company-unit">Cabang Cilacap</div>
                </td>
            </tr>
        </table>
        <h1>Competency Matrix | SIGMA-LAB</h1>
        <p class="subtitle">Ringkasan status sertifikasi &amp; pelatihan personil aktif.</p>
    </div>

    <table class="meta">
        <tr>
            <td style="width: 120px;"><strong>Kategori</strong></td>
            <td>: {{ $kategori ? ($kategoriOptions[$kategori] ?? $kategori) : 'Semua Kategori' }}</td>
        </tr>
        <tr>
            <td><strong>Dicetak pada</strong></td>
            <td>: {{ $tanggalCetak }}</td>
        </tr>
    </table>

    <div class="legend">
        <span class="aktif">Aktif</span>
        <span class="segera">Segera Berakhir (&le;60 hari)</span>
        <span class="kedaluwarsa">Kedaluwarsa</span>
        <span class="belum">Belum Pernah</span>
    </div>

    @if($jenisSertifikasiList->isEmpty())
        <p>Belum ada data sertifikasi yang tercatat untuk membentuk matriks kompetensi.</p>
    @else
        <table class="matrix">
            <thead>
                <tr>
                    <th style="width: 160px;">Nama Personil</th>
                    @foreach($jenisSertifikasiList as $jenis)
                        <th>{{ $jenis }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($matrix as $row)
                    <tr>
                        <td>
                            <div class="nama">{{ $row['personil']->nama }}</div>
                            <div class="jabatan">{{ $row['personil']->jabatan }}</div>
                        </td>
                        @foreach($jenisSertifikasiList as $jenis)
                            @php
                                $cell = $row['sel'][$jenis];
                                $cssClass = 'belum';
                                $label = 'Belum Pernah';
                                $keterangan = '';

                                if ($cell) {
                                    $label = $cell['status']['label'];
                                    $keterangan = $cell['kompetensi']->tanggal_berakhir?->format('d-m-Y') ?? 'Tidak Terbatas';

                                    $cssClass = match ($label) {
                                        'Aktif' => 'aktif',
                                        'Segera Berakhir' => 'segera',
                                        'Kedaluwarsa' => 'kedaluwarsa',
                                        default => 'belum',
                                    };
                                }
                            @endphp
                            <td class="sel">
                                <span class="badge {{ $cssClass }}">{{ $label }}</span>
                                @if($keterangan)
                                    <div style="font-size: 8px; color: #6b7280; margin-top: 2px;">s.d {{ $keterangan }}</div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $jenisSertifikasiList->count() + 1 }}">Belum ada data personil aktif yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <p class="footer-note">Dokumen ini dihasilkan otomatis oleh SIGMA-LAB — Sistem Integrasi Manajemen Laboratorium PT Sucofindo Cilacap.</p>

</body>
</html>