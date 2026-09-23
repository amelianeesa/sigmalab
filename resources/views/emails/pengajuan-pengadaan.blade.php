<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pengajuan Pengadaan Barang</title>
</head>
<body style="margin:0; padding:0; background-color:#f1f5f9; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background:#ffffff; border-radius:12px; overflow:hidden;">

                    <tr>
                        <td style="background:#1d4c7a; padding:24px 28px;">
                            <span style="color:#ffffff; font-size:20px; font-weight:bold; letter-spacing:0.5px;">SIGMA-LAB</span><br>
                            <span style="color:rgba(255,255,255,0.85); font-size:13px;">Sistem Integrated General Management Analytics of Lab</span>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:28px;">
                            <div style="display:inline-block; background:#dbeafe; color:#1d4ed8; font-size:12px; font-weight:bold; padding:4px 10px; border-radius:20px; margin-bottom:16px;">
                                PERSETUJUAN DIPERLUKAN
                            </div>

                            <p style="font-size:15px; color:#334155; line-height:1.6; margin:0 0 20px;">
                                Ada pengajuan pengadaan barang baru yang menunggu persetujuan GA:
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0; border-radius:8px; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#64748b; font-size:13px; width:40%;">Nama Barang</td>
                                    <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#0f172a; font-size:13px; font-weight:bold;">{{ $pengadaan->barang->nama_barang ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#64748b; font-size:13px;">Jumlah Diminta</td>
                                    <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#0f172a; font-size:13px;">{{ $pengadaan->jumlah_diminta }} {{ $pengadaan->barang->satuan ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#64748b; font-size:13px;">Diajukan Oleh</td>
                                    <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#0f172a; font-size:13px;">{{ $pengadaan->pemohon->username ?? $pengadaan->pemohon->personil->nama_personil ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#64748b; font-size:13px;">Tanggal Pengajuan</td>
                                    <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#0f172a; font-size:13px;">{{ \Carbon\Carbon::parse($pengadaan->tanggal_pengajuan)->format('d-m-Y') }}</td>
                                </tr>
                                @if($pengadaan->alasan)
                                <tr>
                                    <td style="padding:12px 16px; color:#64748b; font-size:13px;">Alasan</td>
                                    <td style="padding:12px 16px; color:#0f172a; font-size:13px;">{{ $pengadaan->alasan }}</td>
                                </tr>
                                @endif
                            </table>

                            <p style="font-size:14px; color:#334155; line-height:1.6; margin:0 0 24px;">
                                Mohon segera ditinjau dan diproses melalui SIGMA-LAB.
                            </p>

                            <a href="{{ url('/pengadaan') }}" style="display:inline-block; background:#2563eb; color:#ffffff; text-decoration:none; font-size:14px; font-weight:bold; padding:12px 24px; border-radius:8px;">
                                Tinjau Pengajuan
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:16px 28px; background:#f8fafc; border-top:1px solid #e2e8f0;">
                            <span style="font-size:11px; color:#94a3b8;">Email ini dikirim otomatis oleh sistem SIGMA-LAB. Mohon tidak membalas email ini.</span>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>