<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Logbook Peminjaman Alat</title>
<style>
    @page { margin: 28px 32px; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #000; }

    .hdr { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    .hdr td { border: none; padding: 0; vertical-align: top; }
    .hdr .judul { font-size: 15px; font-weight: bold; }
    .hdr .logo { text-align: right; width: 130px; }
    .hdr .logo img { height: 42px; }

    .info { border-collapse: collapse; margin-bottom: 10px; }
    .info td { border: none; padding: 1px 0; font-size: 10px; }
    .info td.k { width: 70px; }
    .info td.t { width: 12px; }

    table.data { width: 100%; border-collapse: collapse; }
    table.data th, table.data td { border: 1px solid #000; padding: 4px 5px; vertical-align: middle; }
    table.data th { background: #e9ecef; text-align: center; font-weight: bold; }
    td.c { text-align: center; }

    .foot { width: 100%; border-collapse: collapse; margin-top: 8px; border-top: 1px solid #000; }
    .foot td { border: none; padding-top: 5px; font-size: 8px; color: #000; }

    .pb { page-break-after: always; }
</style>
</head>
<body>
@php
    $logoPath = public_path('images/Logo_Suco_Nobg.png');
    $logoSrc = file_exists($logoPath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
        : null;
@endphp

@foreach($bagian as $idx => $b)
    @php $alat = $b['alat']; $baris = $b['baris']; $total = max($baris->count(), 28); @endphp
    <div class="{{ !$loop->last ? 'pb' : '' }}">

        <table class="hdr">
            <tr>
                <td class="judul">LOG BOOK PEMINJAMAN {{ strtoupper($alat->nama_alat) }}</td>
                <td class="logo">@if($logoSrc)<img src="{{ $logoSrc }}" alt="Sucofindo">@endif</td>
            </tr>
        </table>

        <table class="info">
            <tr><td class="k">Nama Alat</td><td class="t">:</td><td>{{ $alat->nama_alat }}</td></tr>
            <tr><td class="k">No. Alat</td><td class="t">:</td><td>{{ $alat->kode_alat }}</td></tr>
        </table>

        <table class="data">
            <thead>
                <tr>
                    <th style="width:24px;">No</th>
                    <th style="width:62px;">Tanggal</th>
                    <th style="width:62px;">Tgl Kembali</th>
                    <th>Pekerjaan</th>
                    <th style="width:90px;">Petugas</th>
                    <th style="width:62px;">Paraf</th>
                    <th style="width:56px;">Acc</th>
                </tr>
            </thead>
            <tbody>
            @for($n = 0; $n < $total; $n++)
                @php $r = $baris[$n] ?? null; @endphp
                <tr style="height:24px;">
                    <td class="c">{{ $n + 1 }}</td>
                    <td class="c">{{ $r ? $r->tanggal->format('d M Y') : '' }}&nbsp;</td>
                    <td class="c">{{ $r && $r->tanggal_kembali ? $r->tanggal_kembali->format('d M Y') : '' }}&nbsp;</td>
                    <td>{{ $r->pekerjaan ?? '' }}&nbsp;</td>
                    <td>{{ $r->petugas ?? '' }}&nbsp;</td>
                    <td class="c">@if($r && $r->paraf)<img src="{{ $r->paraf }}" style="height:20px;">@else &nbsp; @endif</td>
                    <td class="c" style="font-size:8px;">
                        @if($r)
                            @if(in_array($r->status, ['disetujui', 'dikembalikan']))&#10003; {{ $r->penyetuju->personil->nama ?? $r->penyetuju->username ?? '' }}@else menunggu @endif
                        @else &nbsp; @endif
                    </td>
                </tr>
            @endfor
            </tbody>
        </table>

        <table class="foot">
            <tr>
                <td style="text-align:left; width:33%;">No. Alat: {{ $alat->kode_alat }}</td>
                <td style="text-align:center; width:34%;">Dicetak: {{ now()->format('d M Y, H:i') }} oleh {{ $dicetakOleh }}</td>
                <td style="text-align:right; width:33%;">Total: {{ $baris->count() }} catatan</td>
            </tr>
        </table>
    </div>
@endforeach
</body>
</html>