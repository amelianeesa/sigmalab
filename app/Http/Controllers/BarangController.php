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

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'satuan' => 'required|string|max:20',
            'kode_barang' => 'required|string|max:50|unique:barang,kode_barang',
            'minimal_stok' => 'nullable|numeric',
            'saldo_awal' => 'nullable|numeric',
            'penerimaan' => 'nullable|numeric',
            'pengeluaran' => 'nullable|numeric',
            'harga_rata' => 'nullable|numeric',
            'kondisi' => 'required|in:baik,rusak',
            'tgl_exp' => 'nullable|date',
        ]);

        $data = $request->all();

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

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'satuan' => 'required|string|max:20',
            'minimal_stok' => 'nullable|numeric',
            'saldo_awal' => 'nullable|numeric',
            'penerimaan' => 'nullable|numeric',
            'pengeluaran' => 'nullable|numeric',
            'harga_rata' => 'nullable|numeric',
            'kondisi' => 'required|in:baik,rusak',
            'tgl_exp' => 'nullable|date',
        ]);

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
        
        $barang = $query->latest()->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('barang.cetak-periode', compact('barang', 'bulan', 'tahun'));
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Laporan_Inventori_Bahan_' . $bulan . '_' . $tahun . '.pdf');
    }
}