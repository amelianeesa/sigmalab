<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { padding: 20px; border: 1px solid #ddd; border-radius: 5px; background: #f9f9f9; max-width: 600px; margin: auto; }
        .header { font-size: 16px; font-weight: bold; margin-bottom: 15px; padding: 10px; border-radius: 4px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .details { background: #fff; padding: 15px; border-radius: 4px; margin-top: 10px; border: 1px solid #eee; }
        .details p { margin: 8px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            PERINGATAN KETERLAMBATAN PENGADAAN BARANG
        </div>
        <p>Halo Tim GA dan Koordinator Lab,</p>
        <p>Pengadaan barang berikut telah melewati batas waktu target yang ditentukan dan belum selesai:</p>

        <div class="details">
            <p><strong>Nama Barang:</strong> {{ $pengadaan->barang->nama_barang ?? '-' }} [{{ $pengadaan->barang->kode_barang ?? 'Tanpa Kode' }}]</p>
            <p><strong>Jumlah:</strong> {{ (float) $pengadaan->jumlah_diminta }} {{ $pengadaan->barang->satuan ?? '' }}</p>
            <p><strong>Diajukan Oleh:</strong> {{ $pengadaan->pemohon->username ?? '-' }}</p>
            <p><strong>Waktu Berjalan:</strong> {{ trim($formatHariBerjalan) }}</p>
            <p><strong>Status Saat Ini:</strong> <span style="text-transform: uppercase; font-weight: bold;">{{ str_replace('_', ' ', $pengadaan->status) }}</span></p>
        </div>

        <p style="margin-top: 20px; font-size: 13px; color: #666;">Mohon segera log in ke aplikasi <strong>SIGMA-LAB</strong> untuk mengambil tindakan cepat.</p>
        <p style="font-size: 13px; color: #666;">Terima kasih.</p>
    </div>
</body>
</html>