<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peringatan Masa Kalibrasi Alat Akan Habis</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f4f4f4; padding:20px;">
    <div style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:8px; overflow:hidden; border:1px solid #e0e0e0;">

        <div style="background:#1e3a5f; padding:20px; color:#ffffff;">
            <h2 style="margin:0;">SIGMA-LAB</h2>
            <p style="margin:4px 0 0; font-size:14px;">Sistem Integrated General Management Analytics of Lab</p>
        </div>

        <div style="padding:24px;">
            <h3 style="color:#c0392b; margin-top:0;">⚠️ Peringatan: Masa Kalibrasi Alat Akan Habis</h3>

            <p>Halo,</p>
            <p>Berikut ini adalah data peralatan laboratorium yang akan segera berakhir masa kalibrasinya:</p>

            <table style="width:100%; border-collapse:collapse; margin:16px 0;">
                <tr>
                    <td style="padding:8px; border:1px solid #ddd; background:#f9f9f9;"><b>Nama Alat</b></td>
                    <td style="padding:8px; border:1px solid #ddd;">{{ $kalibrasi->alat->nama_alat ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px; border:1px solid #ddd; background:#f9f9f9;"><b>Kode Alat</b></td>
                    <td style="padding:8px; border:1px solid #ddd;">{{ $kalibrasi->alat->kode_alat ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px; border:1px solid #ddd; background:#f9f9f9;"><b>Jenis Kalibrasi</b></td>
                    <td style="padding:8px; border:1px solid #ddd;">{{ $kalibrasi->jenis_kalibrasi ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px; border:1px solid #ddd; background:#f9f9f9;"><b>No. Sertifikat</b></td>
                    <td style="padding:8px; border:1px solid #ddd;">{{ $kalibrasi->no_sertifikat ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px; border:1px solid #ddd; background:#f9f9f9;"><b>Tanggal Kalibrasi</b></td>
                    <td style="padding:8px; border:1px solid #ddd;">{{ $kalibrasi->tgl_kalibrasi ? \Carbon\Carbon::parse($kalibrasi->tgl_kalibrasi)->format('d-m-Y') : '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:8px; border:1px solid #ddd; background:#f9f9f9;"><b>Masa Berlaku Berakhir</b></td>
                    <td style="padding:8px; border:1px solid #ddd; color:#c0392b;"><b>{{ $kalibrasi->tgl_akhir ? $kalibrasi->tgl_akhir->format('d-m-Y') : '-' }}</b></td>
                </tr>
            </table>

            <p>Mohon segera lakukan penjadwalan kalibrasi ulang untuk alat tersebut sebelum tanggal berakhir agar status operasional laboratorium tetap terpelihara.</p>

            <p style="margin-top:24px; font-size:12px; color:#888;">
                Email ini dikirim otomatis oleh sistem SIGMA-LAB. Mohon tidak membalas email ini.
            </p>
        </div>
    </div>
</body>
</html>