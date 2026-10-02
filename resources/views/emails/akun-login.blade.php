<div style="background:#f4f4f4;padding:24px 0;">
    <div style="max-width:600px;margin:0 auto;font-family:Arial,Helvetica,sans-serif;background:#ffffff;border:1px solid #e0e0e0;border-radius:8px;overflow:hidden;color:#222222;">

        <div style="background:#1e3a5f;padding:24px;">
            <h2 style="margin:0;color:#ffffff;font-size:22px;font-weight:bold;">SIGMA-LAB</h2>
            <p style="margin:6px 0 0;color:#ffffff;font-size:14px;">Sistem Integrated General Management Analytics of Lab</p>
        </div>

        <div style="padding:28px 24px;font-size:14px;line-height:1.6;">
            <p style="margin:0 0 20px;font-size:16px;font-weight:bold;color:#1e3a5f;">
            Akun SIGMA-LAB Anda Telah Dibuat
            </p>

            <p style="margin:0 0 12px;">Halo, {{ $namaPersonil }}!</p>

            <p style="margin:0 0 16px;">
                Akun Anda pada sistem SIGMA-LAB telah dibuat oleh HR. Berikut informasi login Anda:
            </p>

            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="width:100%;border-collapse:collapse;margin:0 0 20px;">
                <tr>
                    <td style="width:40%;padding:10px;border:1px solid #dddddd;background:#f9f9f9;font-weight:bold;">Username</td>
                    <td style="padding:10px;border:1px solid #dddddd;">{{ $username }}</td>
                </tr>
                <tr>
                    <td style="width:40%;padding:10px;border:1px solid #dddddd;background:#f9f9f9;font-weight:bold;">Email</td>
                    <td style="padding:10px;border:1px solid #dddddd;word-break:break-all;">
                        <a href="mailto:{{ $email }}" style="color:#222222;text-decoration:none;">{{ $email }}</a>
                    </td>
                </tr>
                <tr>
                    <td style="width:40%;padding:10px;border:1px solid #dddddd;background:#f9f9f9;font-weight:bold;">Password Sementara</td>
                    <td style="padding:10px;border:1px solid #dddddd;color:#c0392b;font-weight:bold;font-family:'Courier New',Courier,monospace;font-size:15px;letter-spacing:1px;">{{ $passwordSementara }}</td>
                </tr>
            </table>

            <p style="margin:0 0 20px;background:#fff3cd;border-left:4px solid #f0ad4e;padding:12px 14px;border-radius:6px;font-size:13px;">
                <strong>Penting:</strong> Password di atas hanya berlaku untuk login pertama. Anda akan diminta membuat password baru saat pertama kali masuk ke sistem.
            </p>

            <p style="margin:0 0 16px;">Silakan login melalui tombol berikut:</p>

            <p style="text-align:center;margin:0 0 24px;">
                <a href="{{ route('login') }}" style="display:inline-block;background:#1e3a5f;color:#ffffff;text-decoration:none;font-weight:bold;padding:12px 32px;border-radius:6px;">
                    Login ke SIGMA-LAB
                </a>
            </p>

            <p style="margin:0 0 20px;font-size:12px;color:#666666;">
                Jika tombol tidak berfungsi, salin link ini ke browser Anda:<br>
                <a href="{{ route('login') }}" style="color:#1e3a5f;word-break:break-all;">{{ route('login') }}</a>
            </p>

            <p style="margin:0;font-size:13px;color:#888888;">
                Email ini dikirim otomatis oleh sistem SIGMA-LAB. Mohon tidak membalas email ini.
            </p>
        </div>
    </div>
</div>