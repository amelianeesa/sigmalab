<div style="max-width:600px;margin:0 auto;font-family:Arial,sans-serif;background:#f5f7fa;padding:20px 0;">
    <div style="background:#ffffff;border-radius:8px;overflow:hidden;border:1px solid #e5e7eb;">
        <div style="background:#0d3b66;padding:20px 24px;">
            <h2 style="margin:0;color:#ffffff;font-size:1.25rem;">SIGMA-LAB</h2>
            <p style="margin:4px 0 0;color:#cfe0f0;font-size:0.85rem;">Sistem Integrated General Management Analytics of Lab</p>
        </div>

        <div style="padding:24px;">
            <p style="font-size:1rem;color:#dc3545;font-weight:bold;margin-top:0;">
                🔑 Permintaan Reset Password
            </p>

            <p>Halo, <strong>{{ $namaUser }}</strong>!</p>

            <p>Kami menerima permintaan untuk mereset password akun Anda. Klik tombol di bawah ini untuk membuat password baru:</p>

            <p style="text-align:center;margin:24px 0;">
                <a href="{{ $resetUrl }}" style="background:#0d3b66;color:#ffffff;padding:12px 28px;border-radius:6px;text-decoration:none;font-weight:bold;display:inline-block;">
                    Reset Password
                </a>
            </p>

            <table style="width:100%;border-collapse:collapse;margin:16px 0;">
                <tr>
                    <td style="padding:8px;border:1px solid #e5e7eb;background:#f8fafc;width:160px;">Akun</td>
                    <td style="padding:8px;border:1px solid #e5e7eb;">{{ $namaUser }}</td>
                </tr>
                <tr>
                    <td style="padding:8px;border:1px solid #e5e7eb;background:#f8fafc;">Email</td>
                    <td style="padding:8px;border:1px solid #e5e7eb;">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td style="padding:8px;border:1px solid #e5e7eb;background:#f8fafc;">Berlaku Hingga</td>
                    <td style="padding:8px;border:1px solid #e5e7eb;color:#dc3545;font-weight:bold;">60 menit dari sekarang</td>
                </tr>
            </table>

            <p style="background:#fff3cd;padding:12px;border-radius:6px;font-size:0.9rem;">
                <strong>Penting:</strong> Jika Anda tidak merasa meminta reset password, abaikan email ini. Password Anda tidak akan berubah.
            </p>

            <p style="font-size:0.85rem;color:#6b7280;">
                Jika tombol tidak berfungsi, salin dan tempel link berikut ke browser Anda:<br>
                <a href="{{ $resetUrl }}" style="color:#0d4c76;word-break:break-all;">{{ $resetUrl }}</a>
            </p>
        </div>
    </div>
</div>