<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { padding: 20px; border: 1px solid #ddd; border-radius: 5px; background: #f9f9f9; max-width: 600px; margin: auto; }
        .header { font-size: 16px; font-weight: bold; margin-bottom: 15px; padding: 10px; border-radius: 4px; background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .details { background: #fff; padding: 15px; border-radius: 4px; margin-top: 10px; border: 1px solid #eee; }
        .details p { margin: 8px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
             PENGINGAT (SETENGAH TARGET WAKTU)
        </div>
        <p>Halo Tim GA,</p>
        <p>Pengadaan barang berikut telah mencapai separuh waktu target dan belum diproses ke tahap pembelian:</p>

        <div class="details">
            <p><strong>Nama Barang:</strong> {{ $pengadaan->barang->nama_barang ?? '-' }} [{{ $pengadaan->barang->kode_barang ?? 'Tanpa Kode' }}]</p>
            <p><strong>Jumlah:</strong> {{ (float) $pengadaan->jumlah_diminta }} {{ $pengadaan->barang->satuan ?? '' }}</p>
            <p><strong>Diajukan Oleh:</strong> {{ $pengadaan->pemohon->username ?? '-' }}</p>
            <p><strong>Waktu Berjalan:</strong> {{ trim($formatHariBerjalan) }}</p>
            <p><strong>Status Saat Ini:</strong> <span style="text-transform: uppercase; font-weight: bold;">{{ str_replace('_', ' ', $pengadaan->status) }}</span></p>
        </div>

        <p style="margin-top: 20px; font-size: 13px; color: #666;">Mohon segera log in ke aplikasi <strong>SIGMA-LAB</strong> untuk segera memproses pengadaan ini.</p>
        <p style="font-size: 13px; color: #666;">Terima kasih.</p>
    </div>
</body>
</html>