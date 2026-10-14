<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangInventoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataBarang = [
            [
                'nama_barang' => 'PLASTIK SEAL MERAH',
                'satuan' => 'Pieces',
                'kode_barang' => '1.12.0301',
                'minimal_stok' => 300,
                'saldo_awal' => 1200,
                'penerimaan' => 0,
                'pengeluaran' => 200,
                'kondisi' => 'baik',
                'tgl_exp' => '2028-12-23',
                'harga_rata' => 6099.60,
                'saldo_akhir' => 1000,
                'workspace' => 'ALL',
            ],
            [
                'nama_barang' => 'Lock Container Seal',
                'satuan' => 'Pieces',
                'kode_barang' => '1.12.0005',
                'minimal_stok' => 50,
                'saldo_awal' => 0,
                'penerimaan' => 0,
                'pengeluaran' => 0,
                'kondisi' => 'baik',
                'tgl_exp' => null,
                'harga_rata' => 6399.00,
                'saldo_akhir' => 0,
                'workspace' => 'ALL',
            ],
            [
                'nama_barang' => 'Certificate SCI 2023 A',
                'satuan' => 'Lembar',
                'kode_barang' => '2.21.1083',
                'minimal_stok' => 200,
                'saldo_awal' => 100,
                'penerimaan' => 0,
                'pengeluaran' => 0,
                'kondisi' => 'baik',
                'tgl_exp' => null,
                'harga_rata' => 3688.50,
                'saldo_akhir' => 100,
                'workspace' => 'ALL',
            ],
            [
                'nama_barang' => 'Certificate SCI 2023 AC',
                'satuan' => 'Lembar',
                'kode_barang' => '2.21.1082',
                'minimal_stok' => 200,
                'saldo_awal' => 100,
                'penerimaan' => 0,
                'pengeluaran' => 0,
                'kondisi' => 'baik',
                'tgl_exp' => null,
                'harga_rata' => 2122.91,
                'saldo_akhir' => 100,
                'workspace' => 'ALL',
            ],
            [
                'nama_barang' => 'Certificate SCI 2023 P',
                'satuan' => 'Lembar',
                'kode_barang' => '2.21.1084',
                'minimal_stok' => 200,
                'saldo_awal' => 300,
                'penerimaan' => 0,
                'pengeluaran' => 0,
                'kondisi' => 'baik',
                'tgl_exp' => null,
                'harga_rata' => 2787.53,
                'saldo_akhir' => 300,
                'workspace' => 'ALL',
            ],
            [
                'nama_barang' => 'Certificate SCI 2023 PC',
                'satuan' => 'Lembar',
                'kode_barang' => '2.21.1085',
                'minimal_stok' => 200,
                'saldo_awal' => 200,
                'penerimaan' => 0,
                'pengeluaran' => 0,
                'kondisi' => 'baik',
                'tgl_exp' => null,
                'harga_rata' => 2544.76,
                'saldo_akhir' => 200,
                'workspace' => 'ALL',
            ],
            [
                'nama_barang' => 'Certificate SCI 2023 L',
                'satuan' => 'Lembar',
                'kode_barang' => '2.21.1086',
                'minimal_stok' => 200,
                'saldo_awal' => 200,
                'penerimaan' => 0,
                'pengeluaran' => 0,
                'kondisi' => 'baik',
                'tgl_exp' => null,
                'harga_rata' => 1554.00,
                'saldo_akhir' => 200,
                'workspace' => 'ALL',
            ],
            [
                'nama_barang' => 'Certificate SCI 2023 LC',
                'satuan' => 'Lembar',
                'kode_barang' => '2.21.1087',
                'minimal_stok' => 200,
                'saldo_awal' => 100,
                'penerimaan' => 0,
                'pengeluaran' => 100,
                'kondisi' => 'baik',
                'tgl_exp' => null,
                'harga_rata' => 1285.00,
                'saldo_akhir' => 0,
                'workspace' => 'ALL',
            ],
        ];

        foreach ($dataBarang as $item) {
            DB::table('barang')->updateOrInsert(
                ['kode_barang' => $item['kode_barang']],
                array_merge($item, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}