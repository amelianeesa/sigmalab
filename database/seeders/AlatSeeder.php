<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Sulfur Analyzer
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC1204-10001'],
            [
                'nama_alat' => 'Sulfur Analyzer',
                'merk_tipe' => 'Labfit CS 1232',
                'no_seri' => '17050068',
                'warna' => 'WHITE',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_2 = DB::table('alat')->where('kode_alat', 'CLC1204-10001')->first();
        if ($alat_2) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_2->alat_id, 'no_sertifikat' => '20059/ENBPAQ'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => '20059/ENBPAQ',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-23',
                    'tgl_akhir' => '2024-05-23',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 - 1400 °C',
                    'faktor_koreksi' => '32 °C',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 2. Calorimeter
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC1206-10001'],
            [
                'nama_alat' => 'Calorimeter',
                'merk_tipe' => 'Parr 6200',
                'no_seri' => '6220-1706-73322',
                'warna' => 'WHITE',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_3 = DB::table('alat')->where('kode_alat', 'CLC1206-10001')->first();
        if ($alat_3) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_3->alat_id, 'no_sertifikat' => 'INTERNAL'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'INTERNAL',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2024-01-16',
                    'tgl_akhir' => '2024-11-05',
                    'lembaga_kalibrasi' => '-',
                    'range_kapasitas' => '0 -2400 EE Value',
                    'faktor_koreksi' => '< 0.17 RSD',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 3. MFS/1 ASTM Oven
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC1156-10002'],
            [
                'nama_alat' => 'MFS/1 ASTM Oven',
                'merk_tipe' => 'Carbolite MFS/1 ASTM',
                'no_seri' => '21-802034',
                'warna' => 'GREY',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_4 = DB::table('alat')->where('kode_alat', 'CLC1156-10002')->first();
        if ($alat_4) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_4->alat_id, 'no_sertifikat' => '20066/ENBPAQ'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => '20066/ENBPAQ',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-23',
                    'tgl_akhir' => '2024-05-23',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 - 210 °C',
                    'faktor_koreksi' => '± 0,4  °C',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 4. MFS/1 ASTM Oven (CLC3208-10001)
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC3208-10001'],
            [
                'nama_alat' => 'MFS/1 ASTM Oven',
                'merk_tipe' => 'Carbolite MFS/1 ASTM',
                'no_seri' => '22-300313',
                'warna' => 'GREY',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'idle',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_5 = DB::table('alat')->where('kode_alat', 'CLC3208-10001')->first();
        if ($alat_5) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_5->alat_id, 'no_sertifikat' => 'NOT_AVAILABLE'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => 'NOT_AVAILABLE',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-23',
                    'tgl_akhir' => '2024-05-23',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => null,
                    'faktor_koreksi' => null,
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 5. VM Furnace ASTM
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC1152-10002'],
            [
                'nama_alat' => 'VM Furnace ASTM',
                'merk_tipe' => 'Carbolite ASTM/1',
                'no_seri' => '21-702881',
                'warna' => 'GREY',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_6 = DB::table('alat')->where('kode_alat', 'CLC1152-10002')->first();
        if ($alat_6) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_6->alat_id, 'no_sertifikat' => '20072/ENBPAQ'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => '20072/ENBPAQ',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-23',
                    'tgl_akhir' => '2024-05-23',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 - 1000 °C',
                    'faktor_koreksi' => '-7  °C',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 6. Ash Furnace
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC1152-10001'],
            [
                'nama_alat' => 'Ash Furnace',
                'merk_tipe' => 'Carbolite AAF 1100',
                'no_seri' => '21-802651',
                'warna' => 'GREY',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_7 = DB::table('alat')->where('kode_alat', 'CLC1152-10001')->first();
        if ($alat_7) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_7->alat_id, 'no_sertifikat' => '20076/ENBPAQ'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => '20076/ENBPAQ',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-23',
                    'tgl_akhir' => '2024-05-23',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 - 1100 °C',
                    'faktor_koreksi' => '0  °C & -7 °C',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 7. Ash Furnace (CLC3212-10001)
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC3212-10001'],
            [
                'nama_alat' => 'Ash Furnace',
                'merk_tipe' => 'Carbolite AAF 1100',
                'no_seri' => '22-300317',
                'warna' => 'GREY',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'idle',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_8 = DB::table('alat')->where('kode_alat', 'CLC3212-10001')->first();
        if ($alat_8) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_8->alat_id, 'no_sertifikat' => 'NOT_AVAILABLE'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => 'NOT_AVAILABLE',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-23',
                    'tgl_akhir' => '2024-05-23',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => null,
                    'faktor_koreksi' => null,
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 8. CHN Analyzer
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC1287-10001'],
            [
                'nama_alat' => 'CHN Analyzer',
                'merk_tipe' => 'ELEMENTAR/ Vario Macro Cube',
                'no_seri' => '20191064',
                'warna' => 'GREY',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_9 = DB::table('alat')->where('kode_alat', 'CLC1287-10001')->first();
        if ($alat_9) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_9->alat_id, 'no_sertifikat' => 'INTERNAL'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'INTERNAL',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-10-16',
                    'tgl_akhir' => '2024-10-16',
                    'lembaga_kalibrasi' => '-',
                    'range_kapasitas' => null,
                    'faktor_koreksi' => null,
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 9. Analytical Balance
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLG0101-10001'],
            [
                'nama_alat' => 'Analytical Balance',
                'merk_tipe' => 'AND/GR-200',
                'no_seri' => '14249947',
                'warna' => 'WHITE',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_10 = DB::table('alat')->where('kode_alat', 'CLG0101-10001')->first();
        if ($alat_10) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_10->alat_id, 'no_sertifikat' => '20093/ENBPAQ'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => '20093/ENBPAQ',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-23',
                    'tgl_akhir' => '2024-05-23',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 - 210 g',
                    'faktor_koreksi' => '-0.0001 g',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 10. Analytical Balance
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC0101-10003'],
            [
                'nama_alat' => 'Analytical Balance',
                'merk_tipe' => 'AND/GR-200',
                'no_seri' => '14259708',
                'warna' => 'WHITE',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_11 = DB::table('alat')->where('kode_alat', 'CLC0101-10003')->first();
        if ($alat_11) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_11->alat_id, 'no_sertifikat' => '20340/ENBPAQ'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => '20340/ENBPAQ',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-23',
                    'tgl_akhir' => '2024-05-23',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 - 210 g',
                    'faktor_koreksi' => '0.0000 g',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 11. Thermohygrometer 1
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => '1'],
            [
                'nama_alat' => 'Thermohygrometer 1',
                'merk_tipe' => 'Krisbow/KW06.797',
                'no_seri' => '1',
                'warna' => 'SILVER&BLACK',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_12 = DB::table('alat')->where('kode_alat', '1')->first();
        if ($alat_12) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_12->alat_id, 'no_sertifikat' => '04517/ENBPAR'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => '04517/ENBPAR',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2024-01-22',
                    'tgl_akhir' => '2025-01-22',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '-10 - 60 °C / 20 - 95 %',
                    'faktor_koreksi' => '-0.7 °C / - 6 %',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 12. Thermohygrometer 2
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => '2'],
            [
                'nama_alat' => 'Thermohygrometer 2',
                'merk_tipe' => 'Krisbow/KW06.797',
                'no_seri' => '2',
                'warna' => 'SILVER&BLACK',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_13 = DB::table('alat')->where('kode_alat', '2')->first();
        if ($alat_13) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_13->alat_id, 'no_sertifikat' => '04521/ENBPAR'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => '04521/ENBPAR',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2024-01-22',
                    'tgl_akhir' => '2025-01-22',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '-10 - 60 °C / 20 - 95 %',
                    'faktor_koreksi' => '-0.2 °C / - 14%',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 13. Thermohygrometer 3
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => '3'],
            [
                'nama_alat' => 'Thermohygrometer 3',
                'merk_tipe' => 'Krisbow/KW06.797',
                'no_seri' => '3',
                'warna' => 'SILVER&BLACK',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_14 = DB::table('alat')->where('kode_alat', '3')->first();
        if ($alat_14) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_14->alat_id, 'no_sertifikat' => '20110/ENBPAQ'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => '20110/ENBPAQ',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-23',
                    'tgl_akhir' => '2024-05-23',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '-10 - 50 °C / 30 - 70 %',
                    'faktor_koreksi' => '-0.1°C / - 12 %',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 14. Thermohygrometer 4
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => '4'],
            [
                'nama_alat' => 'Thermohygrometer 4',
                'merk_tipe' => 'Krisbow/KW06.797',
                'no_seri' => '4',
                'warna' => 'SILVER&BLACK',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_15 = DB::table('alat')->where('kode_alat', '4')->first();
        if ($alat_15) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_15->alat_id, 'no_sertifikat' => '20114/ENBPAQ'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => '20114/ENBPAQ',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-23',
                    'tgl_akhir' => '2024-05-23',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '-10 - 50 °C / 30 - 70 %',
                    'faktor_koreksi' => '-0.4 °C / - 11 %',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 15. OVEN
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLP/PREP/012'],
            [
                'nama_alat' => 'OVEN',
                'merk_tipe' => 'MEMMERT',
                'no_seri' => 'F499.0458',
                'warna' => 'GREY',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_16 = DB::table('alat')->where('kode_alat', 'CLP/PREP/012')->first();
        if ($alat_16) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_16->alat_id, 'no_sertifikat' => '20143/ENBPAQ'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => '20143/ENBPAQ',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-23',
                    'tgl_akhir' => '2024-05-23',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '30 - 230 °C',
                    'faktor_koreksi' => '3 °C',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 16. BIG OVEN
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLP/PREP/003'],
            [
                'nama_alat' => 'BIG OVEN',
                'merk_tipe' => '-',
                'no_seri' => 'Y90L-4-1',
                'warna' => 'BLUE',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_17 = DB::table('alat')->where('kode_alat', 'CLP/PREP/003')->first();
        if ($alat_17) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_17->alat_id, 'no_sertifikat' => '20121/ENBPAQ'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => '20121/ENBPAQ',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-23',
                    'tgl_akhir' => '2024-05-23',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 - 40 °C',
                    'faktor_koreksi' => '± 1,5  °C',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 17. TOP LOADING BALANCE
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC0102-10001'],
            [
                'nama_alat' => 'TOP LOADING BALANCE',
                'merk_tipe' => 'AND/GF-2000',
                'no_seri' => 'T0383336',
                'warna' => 'GREY',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_18 = DB::table('alat')->where('kode_alat', 'CLC0102-10001')->first();
        if ($alat_18) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_18->alat_id, 'no_sertifikat' => '20132/ENBPAQ'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => '20132/ENBPAQ',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-23',
                    'tgl_akhir' => '2024-05-23',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 - 2100 g',
                    'faktor_koreksi' => '0.01 g',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 18. TOP LOADING BALANCE
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC0102-10003'],
            [
                'nama_alat' => 'TOP LOADING BALANCE',
                'merk_tipe' => 'AND/GF-4000',
                'no_seri' => 'T2600494',
                'warna' => 'GREY',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_19 = DB::table('alat')->where('kode_alat', 'CLC0102-10003')->first();
        if ($alat_19) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_19->alat_id, 'no_sertifikat' => '20136/ENBPAQ'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => '20136/ENBPAQ',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-23',
                    'tgl_akhir' => '2024-05-23',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 - 4100 g',
                    'faktor_koreksi' => '-0.02 g',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 19. TOP LOADING BALANCE
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLP/PREP/021'],
            [
                'nama_alat' => 'TOP LOADING BALANCE',
                'merk_tipe' => 'AND/HW-100 KGL',
                'no_seri' => null,
                'warna' => 'GREY',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_20 = DB::table('alat')->where('kode_alat', 'CLP/PREP/021')->first();
        if ($alat_20) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_20->alat_id, 'no_sertifikat' => '20139/ENBPAQ'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => '20139/ENBPAQ',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-23',
                    'tgl_akhir' => '2024-05-23',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 - 100 Kg',
                    'faktor_koreksi' => '-0.01 kg',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 20. HGI MACHINE
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLP/PREP/009'],
            [
                'nama_alat' => 'HGI MACHINE',
                'merk_tipe' => 'NMRF 040',
                'no_seri' => '00001',
                'warna' => 'BLUE',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_21 = DB::table('alat')->where('kode_alat', 'CLP/PREP/009')->first();
        if ($alat_21) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_21->alat_id, 'no_sertifikat' => '20148/ENBPAQ'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => '20148/ENBPAQ',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-23',
                    'tgl_akhir' => '2024-05-23',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '30-60',
                    'faktor_koreksi' => '0.01',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 21. Hammer Crusher
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLP/PREP/001'],
            [
                'nama_alat' => 'Hammer Crusher',
                'merk_tipe' => null,
                'no_seri' => null,
                'warna' => 'BLUE',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_22 = DB::table('alat')->where('kode_alat', 'CLP/PREP/001')->first();
        if ($alat_22) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_22->alat_id, 'no_sertifikat' => 'VERIFIKASI'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'VERIFIKASI',
                    'interval_kalibrasi' => '3 Bulan',
                    'tgl_kalibrasi' => '2024-03-30',
                    'tgl_akhir' => '2023-06-30',
                    'lembaga_kalibrasi' => '--',
                    'range_kapasitas' => '50 Kg',
                    'faktor_koreksi' => '-',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 22. RSD besar
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLP/PREP/002'],
            [
                'nama_alat' => 'RSD besar',
                'merk_tipe' => null,
                'no_seri' => null,
                'warna' => 'GREY',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_23 = DB::table('alat')->where('kode_alat', 'CLP/PREP/002')->first();
        if ($alat_23) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_23->alat_id, 'no_sertifikat' => 'VERIFIKASI'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'VERIFIKASI',
                    'interval_kalibrasi' => '3 Bulan',
                    'tgl_kalibrasi' => '2024-03-30',
                    'tgl_akhir' => '2023-06-30',
                    'lembaga_kalibrasi' => '--',
                    'range_kapasitas' => '50 Kg',
                    'faktor_koreksi' => '-',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 23. RSD kecil
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLP/PREP/005'],
            [
                'nama_alat' => 'RSD kecil',
                'merk_tipe' => null,
                'no_seri' => null,
                'warna' => 'BLUE',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_24 = DB::table('alat')->where('kode_alat', 'CLP/PREP/005')->first();
        if ($alat_24) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_24->alat_id, 'no_sertifikat' => 'VERIFIKASI'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'VERIFIKASI',
                    'interval_kalibrasi' => '3 Bulan',
                    'tgl_kalibrasi' => '2024-03-30',
                    'tgl_akhir' => '2023-06-30',
                    'lembaga_kalibrasi' => '--',
                    'range_kapasitas' => '25 Kg',
                    'faktor_koreksi' => '-',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 24. RSD botol
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLP/PREP/013'],
            [
                'nama_alat' => 'RSD botol',
                'merk_tipe' => null,
                'no_seri' => null,
                'warna' => 'SILVER',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_25 = DB::table('alat')->where('kode_alat', 'CLP/PREP/013')->first();
        if ($alat_25) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_25->alat_id, 'no_sertifikat' => 'VERIFIKASI'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'VERIFIKASI',
                    'interval_kalibrasi' => '3 Bulan',
                    'tgl_kalibrasi' => '2024-03-30',
                    'tgl_akhir' => '2023-06-30',
                    'lembaga_kalibrasi' => '--',
                    'range_kapasitas' => '5 Kg',
                    'faktor_koreksi' => '-',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 25. Raymond Mill
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLP/PREP/006'],
            [
                'nama_alat' => 'Raymond Mill',
                'merk_tipe' => null,
                'no_seri' => null,
                'warna' => 'BLUE',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_26 = DB::table('alat')->where('kode_alat', 'CLP/PREP/006')->first();
        if ($alat_26) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_26->alat_id, 'no_sertifikat' => 'VERIFIKASI'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'VERIFIKASI',
                    'interval_kalibrasi' => '3 Bulan',
                    'tgl_kalibrasi' => '2024-03-30',
                    'tgl_akhir' => '2023-06-30',
                    'lembaga_kalibrasi' => '--',
                    'range_kapasitas' => '5 Kg',
                    'faktor_koreksi' => '-',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 26. Test Sieve 50 mm
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLP/PREP/014'],
            [
                'nama_alat' => 'Test Sieve 50 mm',
                'merk_tipe' => 'Retch',
                'no_seri' => '7042221',
                'warna' => 'SILVER',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_27 = DB::table('alat')->where('kode_alat', 'CLP/PREP/014')->first();
        if ($alat_27) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_27->alat_id, 'no_sertifikat' => 'B05232051'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => 'B05232051',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-26',
                    'tgl_akhir' => '2024-05-26',
                    'lembaga_kalibrasi' => 'CALTESYS',
                    'range_kapasitas' => '50 mm',
                    'faktor_koreksi' => '-0.0136 mm',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 27. Test Sieve 4,75 mm
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLP/PREP/015'],
            [
                'nama_alat' => 'Test Sieve 4,75 mm',
                'merk_tipe' => 'Retch',
                'no_seri' => '02010815',
                'warna' => 'SILVER',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_28 = DB::table('alat')->where('kode_alat', 'CLP/PREP/015')->first();
        if ($alat_28) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_28->alat_id, 'no_sertifikat' => 'B05232106'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => 'B05232106',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-26',
                    'tgl_akhir' => '2024-05-26',
                    'lembaga_kalibrasi' => 'CALTESYS',
                    'range_kapasitas' => '4,75 mm',
                    'faktor_koreksi' => '-0.1091 mm',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 28. Test Sieve 2,36 mm
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLP/PREP/016'],
            [
                'nama_alat' => 'Test Sieve 2,36 mm',
                'merk_tipe' => 'Retch',
                'no_seri' => '02008651',
                'warna' => 'SILVER',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_29 = DB::table('alat')->where('kode_alat', 'CLP/PREP/016')->first();
        if ($alat_29) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_29->alat_id, 'no_sertifikat' => 'B05232165'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => 'B05232165',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-26',
                    'tgl_akhir' => '2024-05-26',
                    'lembaga_kalibrasi' => 'CALTESYS',
                    'range_kapasitas' => '2,36 mm',
                    'faktor_koreksi' => '-0.004 mm',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 29. Test Sieve 1,18 mm
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLP/PREP/017'],
            [
                'nama_alat' => 'Test Sieve 1,18 mm',
                'merk_tipe' => 'Endecotts',
                'no_seri' => '7143217',
                'warna' => 'SILVER',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_30 = DB::table('alat')->where('kode_alat', 'CLP/PREP/017')->first();
        if ($alat_30) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_30->alat_id, 'no_sertifikat' => 'B05232267'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => 'B05232267',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-26',
                    'tgl_akhir' => '2024-05-26',
                    'lembaga_kalibrasi' => 'CALTESYS',
                    'range_kapasitas' => '1,18 mm',
                    'faktor_koreksi' => '-0.0069 mm',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 30. Test Sieve 600 mikron
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLP/PREP/018'],
            [
                'nama_alat' => 'Test Sieve 600 mikron',
                'merk_tipe' => 'Endecotts',
                'no_seri' => '7142167',
                'warna' => 'SILVER',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_31 = DB::table('alat')->where('kode_alat', 'CLP/PREP/018')->first();
        if ($alat_31) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_31->alat_id, 'no_sertifikat' => 'B05232268'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => 'B05232268',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-26',
                    'tgl_akhir' => '2024-05-26',
                    'lembaga_kalibrasi' => 'CALTESYS',
                    'range_kapasitas' => '600 mikron',
                    'faktor_koreksi' => '1.8 μm',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 31. Test Sieve 250 mikron
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLP/PREP/019'],
            [
                'nama_alat' => 'Test Sieve 250 mikron',
                'merk_tipe' => 'Retch',
                'no_seri' => '18007236',
                'warna' => 'SILVER',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_32 = DB::table('alat')->where('kode_alat', 'CLP/PREP/019')->first();
        if ($alat_32) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_32->alat_id, 'no_sertifikat' => 'B05232164'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => 'B05232164',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-26',
                    'tgl_akhir' => '2024-05-26',
                    'lembaga_kalibrasi' => 'CALTESYS',
                    'range_kapasitas' => '250 mikron',
                    'faktor_koreksi' => '6.2 μm',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 32. Test Sieve 75 mikron
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLP/PREP/020'],
            [
                'nama_alat' => 'Test Sieve 75 mikron',
                'merk_tipe' => 'Retch',
                'no_seri' => '22203105',
                'warna' => 'SILVER',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_33 = DB::table('alat')->where('kode_alat', 'CLP/PREP/020')->first();
        if ($alat_33) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_33->alat_id, 'no_sertifikat' => 'B05232200'],
                [
                    'jenis_kalibrasi' => 'eksternal',
                    'no_sertifikat' => 'B05232200',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2023-05-26',
                    'tgl_akhir' => '2024-05-26',
                    'lembaga_kalibrasi' => 'CALTESYS',
                    'range_kapasitas' => '75 mikron',
                    'faktor_koreksi' => '0.2 μm',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 33. DEPTH TAPE
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'ALT-34'],
            [
                'nama_alat' => 'DEPTH TAPE',
                'merk_tipe' => 'RICHTER/ LONG TAPE',
                'no_seri' => '1A,HC,HC34',
                'warna' => 'SILVER',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_34 = DB::table('alat')->where('kode_alat', 'ALT-34')->first();
        if ($alat_34) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_34->alat_id, 'no_sertifikat' => 'E26801/ENBPAS'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'E26801/ENBPAS',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2025-07-28',
                    'tgl_akhir' => '2026-07-28',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 ~ 20000  mm',
                    'faktor_koreksi' => '1mm',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 34. CALIPER
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'ALT-35'],
            [
                'nama_alat' => 'CALIPER',
                'merk_tipe' => 'KRISBOW',
                'no_seri' => '-/1',
                'warna' => 'HITAM',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_35 = DB::table('alat')->where('kode_alat', 'ALT-35')->first();
        if ($alat_35) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_35->alat_id, 'no_sertifikat' => 'E26808/ENBPAS'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'E26808/ENBPAS',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2025-07-28',
                    'tgl_akhir' => '2026-07-28',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 - 150 mm',
                    'faktor_koreksi' => '0.05 mm',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 35. CALIPER
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'ALT-36'],
            [
                'nama_alat' => 'CALIPER',
                'merk_tipe' => 'KRISBOW',
                'no_seri' => '-/2',
                'warna' => 'HITAM',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_36 = DB::table('alat')->where('kode_alat', 'ALT-36')->first();
        if ($alat_36) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_36->alat_id, 'no_sertifikat' => 'E26818/ENBPAS'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'E26818/ENBPAS',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2025-07-28',
                    'tgl_akhir' => '2026-07-28',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 - 150 mm',
                    'faktor_koreksi' => '0.05 mm',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 36. ROLL METER
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'ALT-37'],
            [
                'nama_alat' => 'ROLL METER',
                'merk_tipe' => '-',
                'no_seri' => 'SD21.3.18',
                'warna' => 'HITAM',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_37 = DB::table('alat')->where('kode_alat', 'ALT-37')->first();
        if ($alat_37) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_37->alat_id, 'no_sertifikat' => 'E26828/ENBPAS'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'E26828/ENBPAS',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2025-07-24',
                    'tgl_akhir' => '2026-07-24',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 - 5000 mm',
                    'faktor_koreksi' => '1 mm',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 37. THERMAL IMAGER
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC0379-10001'],
            [
                'nama_alat' => 'THERMAL IMAGER',
                'merk_tipe' => 'FLUKE TiS50',
                'no_seri' => '17080364',
                'warna' => 'ORANGE',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_38 = DB::table('alat')->where('kode_alat', 'CLC0379-10001')->first();
        if ($alat_38) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_38->alat_id, 'no_sertifikat' => 'E28069/ENBPAS'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'E28069/ENBPAS',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2025-07-24',
                    'tgl_akhir' => '2026-07-24',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '-20 ~ 450 O C',
                    'faktor_koreksi' => '0.1 C',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 38. EARTH TESTER
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC2001-10001'],
            [
                'nama_alat' => 'EARTH TESTER',
                'merk_tipe' => 'KYORITSU 4200',
                'no_seri' => '8288271',
                'warna' => 'HITAM',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_39 = DB::table('alat')->where('kode_alat', 'CLC2001-10001')->first();
        if ($alat_39) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_39->alat_id, 'no_sertifikat' => 'E16460/ENBPAT'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'E16460/ENBPAT',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2026-04-20',
                    'tgl_akhir' => '2027-04-20',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0.0087 s.d 999.0451 ohm',
                    'faktor_koreksi' => '- 0.05 ohm',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 39. EARTH TESTER
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC0046-10001'],
            [
                'nama_alat' => 'EARTH TESTER',
                'merk_tipe' => 'KYORITSU KEW 4105 A',
                'no_seri' => 'w8237811',
                'warna' => 'HITAM',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_40 = DB::table('alat')->where('kode_alat', 'CLC0046-10001')->first();
        if ($alat_40) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_40->alat_id, 'no_sertifikat' => 'E15237/ENBPAT'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'E15237/ENBPAT',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2026-04-20',
                    'tgl_akhir' => '2027-04-20',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0.0087 s.d 999.0451 ohm',
                    'faktor_koreksi' => '- 0.05 ohm',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 40. INSULATION RESISTANCE TESTER
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC2002-10003'],
            [
                'nama_alat' => 'INSULATION RESISTANCE TESTER',
                'merk_tipe' => 'FLUKE 1555',
                'no_seri' => '65840030',
                'warna' => 'HITAM',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_41 = DB::table('alat')->where('kode_alat', 'CLC2002-10003')->first();
        if ($alat_41) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_41->alat_id, 'no_sertifikat' => 'E17552/ENBPAT'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'E17552/ENBPAT',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2026-04-20',
                    'tgl_akhir' => '2027-04-20',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '94.9538 s.d 9.5160',
                    'faktor_koreksi' => '-`.3 ohm',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 41. SOUND LEVEL METER
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC2002-10001'],
            [
                'nama_alat' => 'SOUND LEVEL METER',
                'merk_tipe' => 'TENMARS TM-101',
                'no_seri' => '120300136',
                'warna' => 'PUTIH',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_42 = DB::table('alat')->where('kode_alat', 'CLC2002-10001')->first();
        if ($alat_42) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_42->alat_id, 'no_sertifikat' => 'E15239/ENBPAT'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'E15239/ENBPAT',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2026-04-20',
                    'tgl_akhir' => '2027-04-20',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '30~130 dB',
                    'faktor_koreksi' => '00:01:00',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 42. THICKNESS GAUGE
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC0360-10007'],
            [
                'nama_alat' => 'THICKNESS GAUGE',
                'merk_tipe' => 'GE INSPECTION TECHNOLOGIES DM5E',
                'no_seri' => '1701204',
                'warna' => 'ORANGE',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_43 = DB::table('alat')->where('kode_alat', 'CLC0360-10007')->first();
        if ($alat_43) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_43->alat_id, 'no_sertifikat' => 'E10923/ENBPAT'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'E10923/ENBPAT',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2026-04-20',
                    'tgl_akhir' => '2027-04-20',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 ~ 254',
                    'faktor_koreksi' => '00:01:00',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 43. THICKNESS GAUGE
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC0360-10008'],
            [
                'nama_alat' => 'THICKNESS GAUGE',
                'merk_tipe' => 'GE INSPECTION TECHNOLOGIES DM5E',
                'no_seri' => '1701495',
                'warna' => 'ORANGE',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_44 = DB::table('alat')->where('kode_alat', 'CLC0360-10008')->first();
        if ($alat_44) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_44->alat_id, 'no_sertifikat' => 'E10931/ENBPAT'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'E10931/ENBPAT',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2026-04-20',
                    'tgl_akhir' => '2027-04-20',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 ~ 254',
                    'faktor_koreksi' => '00:01:00',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 44. THICKNESS GAUGE
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC0360-10009'],
            [
                'nama_alat' => 'THICKNESS GAUGE',
                'merk_tipe' => 'GE INSPECTION TECHNOLOGIES DM5E',
                'no_seri' => '1701405',
                'warna' => 'ORANGE',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_45 = DB::table('alat')->where('kode_alat', 'CLC0360-10009')->first();
        if ($alat_45) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_45->alat_id, 'no_sertifikat' => 'E10919/ENBPAT'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'E10919/ENBPAT',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2026-04-20',
                    'tgl_akhir' => '2027-04-20',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 ~ 254',
                    'faktor_koreksi' => '00:01:00',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

        // 45. THICKNESS GAUGE
        DB::table('alat')->updateOrInsert(
            ['kode_alat' => 'CLC0360-10010'],
            [
                'nama_alat' => 'THICKNESS GAUGE',
                'merk_tipe' => 'GE INSPECTION TECHNOLOGIES DM5E',
                'no_seri' => '20C021PK',
                'warna' => 'ORANGE',
                'ukuran' => null,
                'kondisi_barang' => 'baik',
                'status_barang' => 'terpakai',
                'unit_kerja_pemilik' => 'SCI CILACAP',
                'workspace' => 'LAB',
                'nonaktif' => 0,
                'dapat_dipinjam' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        
        $alat_46 = DB::table('alat')->where('kode_alat', 'CLC0360-10010')->first();
        if ($alat_46) {
            DB::table('riwayat_kalibrasi')->updateOrInsert(
                ['alat_id' => $alat_46->alat_id, 'no_sertifikat' => 'E10938/ENBPAT'],
                [
                    'jenis_kalibrasi' => 'internal',
                    'no_sertifikat' => 'E10938/ENBPAT',
                    'interval_kalibrasi' => '1 TAHUN',
                    'tgl_kalibrasi' => '2026-04-20',
                    'tgl_akhir' => '2027-04-20',
                    'lembaga_kalibrasi' => 'SCI LAB CIBITUNG',
                    'range_kapasitas' => '0 ~ 254',
                    'faktor_koreksi' => '00:01:00',
                    'signifikan' => 'ya',
                    'created_at' => now(),
                ]
            );
        }

    }
}