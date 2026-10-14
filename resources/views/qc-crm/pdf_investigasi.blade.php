<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lembar Investigasi CAPA - QC CRM</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; line-height: 1.5; color: #333; margin: 15px; }
        .header { text-align: center; border-bottom: 2px solid #1b3152; margin-bottom: 20px; padding-bottom: 10px; }
        .title { font-size: 16px; font-weight: bold; margin: 0; color: #1b3152; text-transform: uppercase; }
        .subtitle { font-size: 12px; margin: 3px 0 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ccc; padding: 7px 10px; vertical-align: top; }
        th { background-color: #f4f6f9; text-align: left; width: 32%; font-weight: bold; }
        .badge-danger { color: #dc3545; font-weight: bold; }
        .section-title { font-weight: bold; color: #1b3152; margin-top: 15px; margin-bottom: 5px; text-decoration: underline; }
        .content-box { border: 1px solid #999; padding: 10px; min-height: 60px; margin-bottom: 15px; background-color: #fafafa; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">LEMBAR INVESTIGASI OUTLIER & TINDAKAN PERBAIKAN (CAPA)</p>
        <p class="subtitle">PENGUJIAN HARIAN CERTIFIED REFERENCE MATERIAL (CRM)</p>
    </div>

    <table>
        <tr>
            <th>Tanggal Pengujian</th>
            <td>{{ \Carbon\Carbon::parse($qc->tanggal_uji)->format('d F Y') }}</td>
        </tr>
        <tr>
            <th>Nomor Lot / Botol CRM</th>
            <td>{{ $qc->crmKatalog->nomor_lot ?? '-' }} ({{ $qc->crmKatalog->nama_produk ?? '-' }})</td>
        </tr>
        <tr>
            <th>Parameter Uji</th>
            <td><strong>{{ strtoupper($qc->parameterUji->nama_parameter ?? '-') }}</strong> ({{ $qc->parameterUji->satuan ?? '-' }})</td>
        </tr>
        <tr>
            <th>Analis Penguji</th>
            <td>{{ $qc->analis->nama ?? '-' }}</td>
        </tr>
        <tr>
            <th>Nilai Sertifikat (True Value &plusmn; U)</th>
            <td>{{ number_format($qc->cert_value, 2) }} &plusmn; {{ number_format($qc->cert_u, 4) }}</td>
        </tr>
        <tr>
            <th>Hasil Uji Lab (Nilai Akhir)</th>
            <td class="badge-danger">{{ number_format($qc->nilai_akhir, 2) }} (OUTLIER)</td>
        </tr>
    </table>

    <div class="section-title">1. Kategori Akar Masalah (Root Cause Category):</div>
    <div class="content-box">
        <strong>{{ $qc->akar_masalah ?? 'Belum ditentukan' }}</strong>
    </div>

    <div class="section-title">2. Detail Temuan Investigasi (Investigation Findings):</div>
    <div class="content-box">
        {!! nl2br(e($qc->catatan_investigasi ?? '-')) !!}
    </div>

    <div class="section-title">3. Tindakan Perbaikan (Corrective Action):</div>
    <div class="content-box">
        {!! nl2br(e($qc->tindakan_perbaikan ?? '-')) !!}
    </div>

    <table style="margin-top: 35px; border: none;">
        <tr>
            <td style="border: none; text-align: center; width: 50%;">
                Dilaporkan Oleh (Analis),<br><br><br><br>
                ( <strong>{{ $qc->analis->nama ?? '..........................' }}</strong> )
            </td>
            <td style="border: none; text-align: center; width: 50%;">
                Diverifikasi Oleh (Penyelia / Manajer Mutu),<br><br><br><br>
                ( .................................................. )
            </td>
        </tr>
    </table>
</body>
</html>