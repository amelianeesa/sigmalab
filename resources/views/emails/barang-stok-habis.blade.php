<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Peringatan Stok Barang SIGMA-LAB</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 20px;">
    <div style="max-width: 600px; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); margin: 0 auto;">
        
        <h2 style="color: #d9534f; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-top: 0;">
            ⚠️ Peringatan Inventori SIGMA-LAB
        </h2>
        
        <p style="color: #333; font-size: 15px;">Halo, Admin / Analis Lab,</p>
        
        <p style="color: #555; font-size: 15px; line-height: 1.5;">
            Sistem mendapati adanya barang di inventori laboratorium yang memerlukan pengecekan atau pengadaan ulang segera karena status stok yang kritis:
        </p>

        <div style="background-color: #fdf7f7; border-left: 4px solid #d9534f; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <p style="margin: 5px 0; color: #333;"><strong>Nama Barang:</strong> {{ $barang->nama_barang }}</p>
            <p style="margin: 5px 0; color: #333;"><strong>Kode Barang:</strong> {{ $barang->kode_barang }}</p>
            <p style="margin: 5px 0; color: #333;"><strong>Satuan:</strong> {{ $barang->satuan }}</p>
            <p style="margin: 5px 0; color: #d9534f;"><strong>Sisa Stok (Saldo Akhir):</strong> {{ number_format($barang->saldo_akhir, 0, ',', '.') }}</p>
            <p style="margin: 5px 0; color: #555;"><strong>Minimal Stok:</strong> {{ number_format($barang->minimal_stok, 0, ',', '.') }}</p>
            @if($barang->tgl_exp)
                <p style="margin: 5px 0; color: #e67e22;"><strong>Tanggal Expired:</strong> {{ \Carbon\Carbon::parse($barang->tgl_exp)->format('d-m-Y') }}</p>
            @endif
        </div>


        {{-- <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;"> --}}
        <div style="text-align: center; margin-top: 20px;">
            <p style="color: #999; font-size: 12px; text-align: center;">
                Email otomatis ini dikirim oleh sistem pemantauan PT Sucofindo - SIGMA-LAB.
            </p>
        </div>    
    </div>
</body>
</html>