<div style="max-width:600px;margin:0 auto;font-family:Arial,sans-serif;">
    <div style="background:#0d3b66;color:#fff;padding:20px;">
        <h3 style="margin:0;">SIGMA-LAB — PT Sucofindo Cilacap</h3>
    </div>

    <div style="padding:24px;border:1px solid #eee;border-top:none;">
        <p>Halo, <strong>{{ $namaPersonil }}</strong>!</p>

        <p>Akun Anda pada sistem SIGMA-LAB telah dibuat oleh HR. Berikut info login Anda:</p>

        <table style="background:#f5f7fa;border-radius:8px;padding:12px;width:100%;margin:16px 0;">
            <tr><td style="padding:6px;">Username</td><td><strong>{{ $username }}</strong></td></tr>
            <tr><td style="padding:6px;">Email</td><td><strong>{{ $email }}</strong></td></tr>
            <tr><td style="padding:6px;">Password Sementara</td><td><code>{{ $passwordSementara }}</code></td></tr>
        </table>

        <p style="background:#fff3cd;padding:12px;border-radius:6px;">
            <strong>Penting:</strong> Password di atas hanya berlaku untuk login pertama. Anda akan diminta membuat password baru saat pertama kali masuk ke sistem.
        </p>

        <p>Silakan login melalui halaman berikut:<br>
        <a href="{{ route('login') }}">{{ route('login') }}</a></p>
    </div>
</div>