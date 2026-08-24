<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\TransaksiBarang;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Mengurangi stok barang (pengeluaran) untuk suatu kegiatan.
     */
    public function deductStock(Barang $barang, float $jumlah, ?int $kegiatanId = null): void
    {
        if ($jumlah <= 0) return;

        DB::transaction(function () use ($barang, $jumlah, $kegiatanId) {
            $barangLock = Barang::where('barang_id', $barang->barang_id)->lockForUpdate()->first();
            
            if (!$barangLock) return;

            $barangLock->pengeluaran += $jumlah;
            $this->recalculateSaldo($barangLock);
            $barangLock->save();

            TransaksiBarang::create([
                'barang_id' => $barangLock->barang_id,
                'kegiatan_id' => $kegiatanId,
                'jumlah_pengeluaran' => $jumlah,
                'jumlah_penerimaan' => 0,
                'harga' => $barangLock->harga_rata ?? 0,
            ]);
        });
    }

    /**
     * Menambah stok barang (penerimaan) dari pengadaan.
     */
    public function addStock(Barang $barang, float $jumlah): void
    {
        if ($jumlah <= 0) return;

        DB::transaction(function () use ($barang, $jumlah) {
            $barangLock = Barang::where('barang_id', $barang->barang_id)->lockForUpdate()->first();
            
            if (!$barangLock) return;

            $barangLock->penerimaan += $jumlah;
            $this->recalculateSaldo($barangLock);
            $barangLock->save();

            TransaksiBarang::create([
                'barang_id' => $barangLock->barang_id,
                'jumlah_pengeluaran' => 0,
                'jumlah_penerimaan' => $jumlah,
                'harga' => $barangLock->harga_rata ?? 0,
            ]);
        });
    }

    /**
     * Mengembalikan (rollback) sebuah transaksi barang.
     * Dipanggil ketika Kegiatan diupdate/dihapus untuk mengembalikan stok.
     */
    public function rollbackTransaction(TransaksiBarang $transaksi): void
    {
        DB::transaction(function () use ($transaksi) {
            $barangLock = Barang::where('barang_id', $transaksi->barang_id)->lockForUpdate()->first();
            
            if ($barangLock) {
                if ($transaksi->jumlah_pengeluaran > 0) {
                    $barangLock->pengeluaran -= $transaksi->jumlah_pengeluaran;
                }
                if ($transaksi->jumlah_penerimaan > 0) {
                    $barangLock->penerimaan -= $transaksi->jumlah_penerimaan;
                }
                
                $this->recalculateSaldo($barangLock);
                $barangLock->save();
            }

            $transaksi->delete();
        });
    }

    /**
     * Hitung ulang saldo_akhir.
     */
    public function recalculateSaldo(Barang $barang): float
    {
        $barang->saldo_akhir = ($barang->saldo_awal + $barang->penerimaan) - $barang->pengeluaran;
        return $barang->saldo_akhir;
    }
}
