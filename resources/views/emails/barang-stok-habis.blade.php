<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Peringatan Stok Barang SIGMA-LAB</title>
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
                            <div style="display:inline-block; background:#fee2e2; color:#b91c1c; font-size:12px; font-weight:bold; padding:4px 10px; border-radius:20px; margin-bottom:16px;">
                                ⚠️ STOK KRITIS
                            </div>

                            <p style="font-size:15px; color:#334155; line-height:1.6; margin:0 0 4px;">
                                Halo, Admin / Analis Lab,
                            </p>

                            <p style="font-size:14px; color:#555; line-height:1.6; margin:0 0 20px;">
                                Sistem mendapati adanya barang di inventori laboratorium yang memerlukan pengecekan atau pengadaan ulang segera karena status stok yang kritis:
                            </p>

                            @if(!empty($statusPesan))
                            <p style="font-size:13px; color:#b91c1c; font-weight:bold; margin:0 0 12px;">
                                {{ $statusPesan }}
                            </p>
                            @endif

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0; border-radius:8px; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#64748b; font-size:13px; width:45%;">Nama Barang</td>
                                    <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#0f172a; font-size:13px; font-weight:bold;">{{ $barang->nama_barang }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#64748b; font-size:13px;">Kode Barang</td>
                                    <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#0f172a; font-size:13px;">{{ $barang->kode_barang }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#64748b; font-size:13px;">Satuan</td>
                                    <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#0f172a; font-size:13px;">{{ $barang->satuan }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#64748b; font-size:13px;">Sisa Stok (Saldo Akhir)</td>
                                    <td style="padding:12px 16px; border-bottom:1px solid #e2e8f0; color:#b91c1c; font-size:13px; font-weight:bold;">{{ number_format($barang->saldo_akhir, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px; {{ $barang->tgl_exp ? 'border-bottom:1px solid #e2e8f0;' : '' }} color:#64748b; font-size:13px;">Minimal Stok</td>
                                    <td style="padding:12px 16px; {{ $barang->tgl_exp ? 'border-bottom:1px solid #e2e8f0;' : '' }} color:#0f172a; font-size:13px;">{{ number_format($barang->minimal_stok, 0, ',', '.') }}</td>
                                </tr>
                                @if($barang->tgl_exp)
                                <tr>
                                    <td style="padding:12px 16px; color:#64748b; font-size:13px;">Tanggal Expired</td>
                                    <td style="padding:12px 16px; color:#e67e22; font-size:13px; font-weight:bold;">{{ \Carbon\Carbon::parse($barang->tgl_exp)->format('d-m-Y') }}</td>
                                </tr>
                                @endif
                            </table>

                            <p style="font-size:14px; color:#334155; line-height:1.6; margin:0;">
                                Mohon segera ditindaklanjuti dengan mengajukan pengadaan melalui SIGMA-LAB.
                            </p>
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