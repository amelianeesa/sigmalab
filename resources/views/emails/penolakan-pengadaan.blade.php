<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penolakan Pengadaan</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:20px;">
    <div style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:8px; overflow:hidden; border:1px solid #dcdcdc;">
        <div style="background:#c0392b; padding:24px 20px; color:#ffffff;">
            <h2 style="margin:0; font-size: 20px;">SIGMA-LAB</h2>
            <p style="margin:4px 0 0; font-size:13px; color:#f8d7da;">Sistem Integrated General Management Analytics of Lab</p>
        </div>
        <div style="padding:24px 20px; color:#333333;">
            <h3 style="color:#c0392b; margin-top:0;">Pemberitahuan: Pengajuan Pengadaan Ditolak</h3>
            <p>Halo Koordinator Lab,</p>
            <p>Mohon maaf, pengajuan pengadaan barang Anda telah ditolak oleh pihak berwenang dengan detail informasi berikut:</p>

            <table style="width:100%; border-collapse:collapse; margin:16px 0; font-size: 14px;">
                <tr>
                    <td style="padding:10px; border:1px solid #e2e8f0; background:#f8fafc; width:35%;"><b>Nama Barang</b></td>
                    <td style="padding:10px; border:1px solid #e2e8f0;">{{ $pengadaan->barang->nama_barang ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #e2e8f0; background:#f8fafc;"><b>Jumlah Diminta</b></td>
                    <td style="padding:10px; border:1px solid #e2e8f0;">{{ (float) $pengadaan->jumlah_diminta }} {{ $pengadaan->barang->satuan ?? '' }}</td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #e2e8f0; background:#f8fafc;"><b>Keterangan / Alasan</b></td>
                    <td style="padding:10px; border:1px solid #e2e8f0; color:#c0392b;"><b>{{ $pengadaan->catatan_approval }}</b></td>
                </tr>
            </table>

            <p style="font-size:13px; color:#555;">Silakan melakukan peninjauan ulang atau mengajukan perbaikan data jika diperlukan melalui sistem SIGMA-LAB.</p>

            <div style="border-top: 1px solid #e2e8f0; margin-top: 24px; padding-top: 16px; text-align: center;">
                <p style="margin:0; font-size:11px; color:#94a3b8;">Email dikirim otomatis oleh sistem SIGMA-LAB.</p>
            </div>
        </div>
    </div>
</body>
</html>