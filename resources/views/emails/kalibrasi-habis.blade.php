<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peringatan Masa Kalibrasi Alat Akan Habis</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:20px;">
    <div style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:8px; overflow:hidden; border:1px solid #dcdcdc; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">

        <!-- Header -->
        <div style="background:#1e3a5f; padding:24px 20px; color:#ffffff; text-align: left;">
            <h2 style="margin:0; font-size: 20px; font-weight: bold;">SIGMA-LAB</h2>
            <p style="margin:4px 0 0; font-size:13px; color: #cbd5e1;">Sistem Integrated General Management Analytics of Lab</p>
        </div>

        <!-- Body Content -->
        <div style="padding:24px 20px; color:#333333;">
            <h3 style="color:#c0392b; margin-top:0; font-size: 16px; border-bottom: 2px solid #f1f1f1; padding-bottom: 8px;">
                Peringatan: Masa Kalibrasi Alat Akan Habis
            </h3>

            <p style="margin-bottom: 8px;">Halo,</p>
            <p style="margin-top: 0; color: #555555; line-height: 1.5;">
                Berikut ini adalah data peralatan laboratorium yang masa kalibrasinya perlu mendapatkan perhatian:
            </p>

            <!-- Tabel Detail Alat -->
            <table style="width:100%; border-collapse:collapse; margin:16px 0; font-size: 14px;">
                <tr>
                    <td style="padding:10px 12px; border:1px solid #e2e8f0; background:#f8fafc; width: 35%;"><b>Nama Alat</b></td>
                    <td style="padding:10px 12px; border:1px solid #e2e8f0; color:#1e293b;"><b>{{ $kalibrasi->alat->nama_alat ?? '-' }}</b></td>
                </tr>
                <tr>
                    <td style="padding:10px 12px; border:1px solid #e2e8f0; background:#f8fafc;"><b>Kode Alat</b></td>
                    <td style="padding:10px 12px; border:1px solid #e2e8f0; color:#1e293b;">{{ $kalibrasi->alat->kode_alat ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 12px; border:1px solid #e2e8f0; background:#f8fafc;"><b>Jenis Kalibrasi</b></td>
                    <td style="padding:10px 12px; border:1px solid #e2e8f0; color:#1e293b;">{{ $kalibrasi->jenis_kalibrasi ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 12px; border:1px solid #e2e8f0; background:#f8fafc;"><b>No. Sertifikat</b></td>
                    <td style="padding:10px 12px; border:1px solid #e2e8f0; color:#1e293b;">{{ $kalibrasi->no_sertifikat ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 12px; border:1px solid #e2e8f0; background:#f8fafc;"><b>Tanggal Kalibrasi</b></td>
                    <td style="padding:10px 12px; border:1px solid #e2e8f0; color:#1e293b;">{{ $kalibrasi->tgl_kalibrasi ? \Carbon\Carbon::parse($kalibrasi->tgl_kalibrasi)->format('d-m-Y') : '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 12px; border:1px solid #e2e8f0; background:#f8fafc;"><b>Masa Berlaku Berakhir</b></td>
                    <td style="padding:10px 12px; border:1px solid #e2e8f0; color:#c0392b;"><b>{{ $kalibrasi->tgl_akhir ? \Carbon\Carbon::parse($kalibrasi->tgl_akhir)->format('d-m-Y') : '-' }}</b></td>
                </tr>
            </table>

            <!-- Kotak Pesan Dinamis Berdasarkan Fase Bulan -->
            @if(isset($kalibrasi->custom_pesan))
                <div style="background: #fffbeb; color: #92400e; padding: 14px 16px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #f59e0b; font-size: 13px; line-height: 1.6;">
                    <b>Tindakan yang Diperlukan:</b><br>
                    {{ $kalibrasi->custom_pesan }}
                </div>
            @endif

            <p style="margin-top:20px; font-size:13px; color:#64748b; line-height: 1.4;">
                Mohon segera lakukan penindakan sesuai arahan di atas agar status operasional laboratorium tetap terpelihara dengan baik.
            </p>

            <!-- Footer -->
            <div style="border-top: 1px solid #e2e8f0; margin-top: 24px; padding-top: 16px; text-align: center;">
                <p style="margin:0; font-size:11px; color:#94a3b8;">
                    Email ini dikirim otomatis oleh sistem <b>SIGMA-LAB</b>. Mohon tidak membalas email ini.
                </p>
            </div>
        </div>
    </div>
</body>
</html>