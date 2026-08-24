<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterKondisi = $request->input('filter_kondisi');

        $query = Barang::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'LIKE', "%{$search}%")
                  ->orWhere('kode_barang', 'LIKE', "%{$search}%")
                  ->orWhere('satuan', 'LIKE', "%{$search}%");
            });
        }

        if ($filterKondisi) {
            $query->where('kondisi', $filterKondisi);
        }

        // UBAH DARI $query->get(); MENJADI DIURUTKAN DARI YANG TERBARU
        $barang = $query->latest()->get(); 
        // Atau bisa juga menggunakan: $query->orderBy('barang_id', 'desc')->get();

        return view('barang.index', compact('barang', 'search', 'filterKondisi'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(\App\Http\Requests\BarangRequest $request)
    {
        $data = $request->only(['nama_barang', 'satuan', 'kode_barang', 'minimal_stok', 'saldo_awal', 'penerimaan', 'pengeluaran', 'harga_rata', 'kondisi', 'tgl_exp']);

        $saldoAwal = $data['saldo_awal'] ?? 0;
        $penerimaan = $data['penerimaan'] ?? 0;
        $pengeluaran = $data['pengeluaran'] ?? 0;

        $data['saldo_awal'] = $saldoAwal;
        $data['penerimaan'] = $penerimaan;
        $data['pengeluaran'] = $pengeluaran;
        $data['saldo_akhir'] = ($saldoAwal + $penerimaan) - $pengeluaran;

        Barang::create($data);

        return redirect()->route('barang.index')->with('success', 'Data barang persediaan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    public function update(\App\Http\Requests\BarangRequest $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $data = $request->except('kode_barang');

        $saldoAwal = $data['saldo_awal'] ?? 0;
        $penerimaan = $data['penerimaan'] ?? 0;
        $pengeluaran = $data['pengeluaran'] ?? 0;

        $data['saldo_awal'] = $saldoAwal;
        $data['penerimaan'] = $penerimaan;
        $data['pengeluaran'] = $pengeluaran;
        $data['saldo_akhir'] = ($saldoAwal + $penerimaan) - $pengeluaran;

        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'Data barang persediaan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        if ($barang->transaksiBarang()->count() > 0 || $barang->permintaanPengadaan()->count() > 0) {
            return redirect()->route('barang.index')
                ->with('error', 'Data barang tidak bisa dihapus karena masih memiliki riwayat transaksi atau permintaan pengadaan!');
        }

        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Data barang persediaan berhasil dihapus');
    }

    public function printPeriode(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $query = Barang::query();

        if ($bulan && $tahun) {
            $query->whereYear('created_at', $tahun)->whereMonth('created_at', $bulan);
        }
        
        $barang = $query->latest()->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('barang.cetak-periode', compact('barang', 'bulan', 'tahun'));
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Laporan_Inventori_Bahan_' . $bulan . '_' . $tahun . '.pdf');
    }
}