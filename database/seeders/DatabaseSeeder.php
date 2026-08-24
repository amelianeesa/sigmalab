<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            ModulSeeder::class,
            HakAksesSeeder::class,
        ]);

        User::factory()->create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'role_id' => 1,
            'status_aktif' => true,
        ]);

        DB::table('alat')->insert([
            [
                'kode_alat' => 'CLC1204-10001',
                'nama_alat' => 'Sulfur Analyzer',
                'merk_tipe' => 'Labfit CS 1232',
                'no_seri' => '17050068',
                'warna' => 'WHITE',
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_alat' => 'CLC1206-10001',
                'nama_alat' => 'Calorimeter',
                'merk_tipe' => 'Parr 6200',
                'no_seri' => '6220-1706-73322',
                'warna' => 'WHITE',
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_alat' => 'CLC1156-10002',
                'nama_alat' => 'MFS/1 ASTM Oven',
                'merk_tipe' => 'Carbolite MFS/1 ASTM',
                'no_seri' => '21-802034',
                'warna' => 'GREY',
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_alat' => 'CLC3208-10001',
                'nama_alat' => 'MFS/1 ASTM Oven',
                'merk_tipe' => 'Carbolite MFS/1 ASTM',
                'no_seri' => '22-300313',
                'warna' => 'GREY',
                'kondisi_barang' => 'baik',
                'status_barang' => 'idle',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('barang')->insert([
            [
                'nama_barang' => 'PLASTIK SEAL MERAH',
                'satuan' => 'Pieces',
                'kode_barang' => '1.12.0301',
                'minimal_stok' => '300.0000',
                'kondisi' => 'baik',
                'tgl_exp' => '2028-12-23',
                'harga_rata' => '6099.6000',
                'saldo_akhir' => '1000.0000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_barang' => 'Lock Container Seal',
                'satuan' => 'Pieces',
                'kode_barang' => '1.12.0005',
                'minimal_stok' => '50.0000',
                'kondisi' => 'baik',
                'tgl_exp' => null,
                'harga_rata' => '6399.0000',
                'saldo_akhir' => '0.0000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_barang' => 'Certificate SCI 2023 A',
                'satuan' => 'Lembar',
                'kode_barang' => '2.21.1083',
                'minimal_stok' => '200.0000',
                'kondisi' => 'baik',
                'tgl_exp' => null,
                'harga_rata' => '3688.5000',
                'saldo_akhir' => '100.0000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_barang' => 'Certificate SCI 2023 AC',
                'satuan' => 'Lembar',
                'kode_barang' => '2.21.1082',
                'minimal_stok' => '200.0000',
                'kondisi' => 'baik',
                'tgl_exp' => null,
                'harga_rata' => '2122.9100',
                'saldo_akhir' => '100.0000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_barang' => 'Certificate SCI 2023 P',
                'satuan' => 'Lembar',
                'kode_barang' => '2.21.1084',
                'minimal_stok' => '200.0000',
                'kondisi' => 'baik',
                'tgl_exp' => null,
                'harga_rata' => '2787.5300',
                'saldo_akhir' => '300.0000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_barang' => 'Certificate SCI 2023 PC',
                'satuan' => 'Lembar',
                'kode_barang' => '2.21.1085',
                'minimal_stok' => '200.0000',
                'kondisi' => 'baik',
                'tgl_exp' => null,
                'harga_rata' => '2544.7600',
                'saldo_akhir' => '200.0000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_barang' => 'Certificate SCI 2023 L',
                'satuan' => 'Lembar',
                'kode_barang' => '2.21.1086',
                'minimal_stok' => '200.0000',
                'kondisi' => 'baik',
                'tgl_exp' => null,
                'harga_rata' => '1554.0000',
                'saldo_akhir' => '200.0000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_barang' => 'Certificate SCI 2023 LC',
                'satuan' => 'Lembar',
                'kode_barang' => '2.21.1087',
                'minimal_stok' => '200.0000',
                'kondisi' => 'baik',
                'tgl_exp' => null,
                'harga_rata' => '1285.0000',
                'saldo_akhir' => '0.0000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
