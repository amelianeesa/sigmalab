<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Harga Barang/Bahan Belum Diisi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #dcdcdc;
        }
        .header {
            background: #1b3a5c; /* Disesuaikan dengan warna brand navy aplikasi */
            padding: 24px 20px;
            color: #ffffff;
            text-align: center;
        }
        .header h2 {
            margin: 0;
            font-size: 20px;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 6px 0 0;
            font-size: 12px;
            color: #dbeafe;
        }
        .content {
            padding: 24px 20px;
            color: #333333;
        }
        .content h3 {
            color: #1b3a5c;
            margin-top: 0;
            font-size: 16px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
            font-size: 13px;
        }
        .data-table th, .data-table td {
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            text-align: left;
            vertical-align: middle;
        }
        .data-table th {
            background: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
            text-align: center;
        }
        .data-table td.center {
            text-align: center;
        }
        .data-table td.name {
            color: #1b3a5c;
            font-weight: bold;
        }
        .note {
            font-size: 13px;
            color: #475569;
            line-height: 1.5;
            margin-top: 16px;
        }
        .btn-wrap {
            text-align: center;
            margin: 24px 0 10px;
        }
        .btn {
            display: inline-block;
            background: #1b3a5c;
            color: #ffffff !important;
            padding: 11px 22px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .footer {
            border-top: 1px solid #e2e8f0;
            margin-top: 24px;
            padding-top: 16px;
            text-align: center;
        }
        .footer p {
            margin: 0;
            font-size: 11px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h2>SIGMA-LAB</h2>
            <p>Sistem Integrated General Management Analytics of Lab</p>
        </div>
        <div class="content">
            <h3>Peringatan: Harga Barang/Bahan Belum Diisi</h3>
            <p>Halo,</p>
            <p>Berikut adalah daftar barang/bahan inventori yang saat ini belum memiliki harga rata-rata tertimbang:</p>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">No.</th>
                        <th style="width: 25%;">Kode Barang</th>
                        <th style="width: 45%;">Nama Barang</th>
                        <th style="width: 20%;">Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barang as $index => $item)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td class="center"><code>{{ $item->kode_barang }}</code></td>
                        <td class="name">{{ $item->nama_barang }}</td>
                        <td class="center">{{ $item->satuan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="note">Mohon kepada bagian GA untuk segera melengkapi harga barang/bahan tersebut melalui sistem agar nilai persediaan dapat dihitung dengan benar.</p>

            <p class="btn-wrap">
                <!-- Diarahkan ke route indeks data barang agar tidak 404 -->
                <a href="{{ route('barang.index') }}" class="btn">Buka Data Barang Sekarang</a>
            </p>

            <div class="footer">
                <p>Email dikirim otomatis oleh sistem SIGMA-LAB.</p>
            </div>
        </div>
    </div>
</body>
</html>