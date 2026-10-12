<div style="background:#f4f4f4;padding:24px 0;">
    <div style="max-width:600px;margin:0 auto;font-family:Arial,Helvetica,sans-serif;background:#ffffff;border:1px solid #e0e0e0;border-radius:8px;overflow:hidden;color:#222222;">

        <div style="background:#1e3a5f;padding:24px;">
            <h2 style="margin:0;color:#ffffff;font-size:22px;font-weight:bold;">SIGMA-LAB</h2>
            <p style="margin:6px 0 0;color:#ffffff;font-size:14px;">Sistem Integrated General Management Analytics of Lab</p>
        </div>

        <div style="padding:28px 24px;font-size:14px;line-height:1.6;">
            <p style="margin:0 0 20px;font-size:16px;font-weight:bold;color:#c0392b;">
            Permintaan Reset Password
            </p>

            <p style="margin:0 0 12px;">Halo, {{ $namaUser }}!</p>

            <p style="margin:0 0 16px;">
                Kami menerima permintaan untuk mereset password akun Anda. Berikut detail permintaannya:
            </p>

            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="width:100%;border-collapse:collapse;margin:0 0 20px;">
                <tr>
                    <td style="width:40%;padding:10px;border:1px solid #dddddd;background:#f9f9f9;font-weight:bold;">Akun</td>
                    <td style="padding:10px;border:1px solid #dddddd;">{{ $namaUser }}</td>
                </tr>
                <tr>
                    <td style="width:40%;padding:10px;border:1px solid #dddddd;background:#f9f9f9;font-weight:bold;">Email</td>
                    <td style="padding:10px;border:1px solid #dddddd;word-break:break-all;">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td style="width:40%;padding:10px;border:1px solid #dddddd;background:#f9f9f9;font-weight:bold;">Berlaku Hingga</td>
                    <td style="padding:10px;border:1px solid #dddddd;color:#c0392b;font-weight:bold;">60 menit dari sekarang</td>
                </tr>
            </table>

            <p style="margin:0 0 24px;">Klik tombol di bawah ini untuk membuat password baru:</p>

            <p style="text-align:center;margin:0 0 24px;">
                <a href="{{ $resetUrl }}" style="display:inline-block;background:#1e3a5f;color:#ffffff;text-decoration:none;font-weight:bold;padding:12px 32px;border-radius:6px;">
                    Reset Password
                </a>
            </p>

            <p style="margin:0 0 20px;background:#fff3cd;border-left:4px solid #f0ad4e;padding:12px 14px;border-radius:6px;font-size:13px;">
                <strong>Penting:</strong> Jika Anda tidak merasa meminta reset password, abaikan email ini. Password Anda tidak akan berubah.
            </p>

            <p style="margin:0 0 20px;font-size:12px;color:#666666;">
                Jika tombol tidak berfungsi, salin dan tempel link berikut ke browser Anda:<br>
                <a href="{{ $resetUrl }}" style="color:#1e3a5f;word-break:break-all;">{{ $resetUrl }}</a>
            </p>

            <p style="margin:0;font-size:13px;color:#888888;">
                Email ini dikirim otomatis oleh sistem SIGMA-LAB. Mohon tidak membalas email ini.
            </p>
        </div>
    </div>
</div>