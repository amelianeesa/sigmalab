<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\TransaksiBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        
        $data = $request->only(['nama_barang', 'satuan', 'kode_barang', 'minimal_stok', 'saldo_awal', 'penerimaan', 'pengeluaran', 'harga_rata', 'kondisi', 'tgl_exp']);

        $saldoAwal = $data['saldo_awal'] ?? 0;
        $penerimaan = $data['penerimaan'] ?? 0;
        $pengeluaran = $data['pengeluaran'] ?? 0;

        $data['saldo_awal'] = $saldoAwal;
        $data['penerimaan'] = $penerimaan;
        $data['pengeluaran'] = $pengeluaran;
        $data['saldo_akhir'] = ($saldoAwal + $penerimaan) - $pengeluaran;

        // Simpan master barang
        $barang = Barang::create($data);

        // Catat sebagai batch awal ke tabel transaksi_barang
        $totalMasuk = $saldoAwal + $penerimaan;
        if ($totalMasuk > 0 && !empty($request->tgl_exp)) {
            TransaksiBarang::create([
                'barang_id' => $barang->barang_id,
                'jumlah_penerimaan' => $totalMasuk,
                'harga' => $data['harga_rata'] ?? 0,
                'tgl_exp' => $request->tgl_exp,
            ]);
        }

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
            'kode_barang' => 'required|string|max:50|unique:barang,kode_barang,' . $barang->barang_id . ',barang_id',
            'minimal_stok' => 'nullable|numeric',
            'saldo_awal' => 'nullable|numeric',
            'penerimaan' => 'nullable|numeric',
            'pengeluaran' => 'nullable|numeric', 
            'harga_rata' => 'nullable|numeric',
            'kondisi' => 'required|in:baik,rusak',
            'tgl_exp' => 'nullable|date',
        ]);

        $data = $request->all();

        // Hanya HR dan Admin Aplikasi yang dapat edit harga
        $roleName = Auth::user()->role->nama_role ?? '';
        $isAuthorizedForPricing = in_array($roleName, [
            \App\Enums\PeranPengguna::HR_GA_OFFICER->value, 
            \App\Enums\PeranPengguna::ADMIN_APLIKASI->value
        ]);
        if (!$isAuthorizedForPricing) {
            $data['harga_rata'] = $barang->harga_rata;
        }

        $saldoAwal = $data['saldo_awal'] ?? 0;
        $penerimaan = $data['penerimaan'] ?? 0;
        
        // Ambil nilai pengeluaran baru yang diketik user di form
        $pengeluaranBaru = $data['pengeluaran'] ?? 0;

        \Illuminate\Support\Facades\DB::transaction(function () use ($barang, $data, $saldoAwal, $penerimaan, $pengeluaranBaru) {
            
            // Jika ada pengeluaran baru, akumulasikan dan potong stok batch (FEFO)
            if ($pengeluaranBaru > 0) {
                $barang->pengeluaran += $pengeluaranBaru;

                $sisaPengeluaran = $pengeluaranBaru;
                $batches = TransaksiBarang::where('barang_id', $barang->barang_id)
                    ->where('jumlah_penerimaan', '>', 0)
                    ->orderBy('tgl_exp', 'asc')
                    ->get();

                foreach ($batches as $batch) {
                    if ($sisaPengeluaran <= 0) break;

                    $sudahKeluarDiBatch = TransaksiBarang::where('barang_id', $barang->barang_id)
                        ->where('tgl_exp', $batch->tgl_exp)
                        ->sum('jumlah_pengeluaran');

                    $sisaDiBatch = $batch->jumlah_penerimaan - $sudahKeluarDiBatch;

                    if ($sisaDiBatch > 0) {
                        if ($sisaPengeluaran >= $sisaDiBatch) {
                            $ambilDariBatch = $sisaDiBatch;
                            $sisaPengeluaran -= $sisaDiBatch;
                        } else {
                            $ambilDariBatch = $sisaPengeluaran;
                            $sisaPengeluaran = 0;
                        }

                        TransaksiBarang::create([
                            'barang_id' => $barang->barang_id,
                            'jumlah_penerimaan' => 0,
                            'jumlah_pengeluaran' => $ambilDariBatch,
                            'harga' => $barang->harga_rata ?? 0,
                            'tgl_exp' => $batch->tgl_exp,
                        ]);
                    }
                }
            }

            // Update master data barang lainnya
            $barang->nama_barang = $data['nama_barang'];
            $barang->satuan = $data['satuan'];
            $barang->kode_barang = $data['kode_barang'];
            $barang->minimal_stok = $data['minimal_stok'] ?? $barang->minimal_stok;
            $barang->saldo_awal = $saldoAwal;
            $barang->penerimaan = $penerimaan;
            $barang->kondisi = $data['kondisi'];
            
            // Hitung saldo akhir otomatis
            $barang->saldo_akhir = ($barang->saldo_awal + $barang->penerimaan) - $barang->pengeluaran;

            // Update tgl_exp utama di tabel barang (ambil batch aktif terdekat yang masih ada sisa stok)
            $nearestActiveBatch = TransaksiBarang::where('barang_id', $barang->barang_id)
                ->whereNotNull('tgl_exp')
                ->select('tgl_exp', \Illuminate\Support\Facades\DB::raw('SUM(jumlah_penerimaan) - SUM(jumlah_pengeluaran) as sisa_stok'))
                ->groupBy('tgl_exp')
                ->having('sisa_stok', '>', 0)
                ->orderBy('tgl_exp', 'asc')
                ->first();

            $barang->tgl_exp = $nearestActiveBatch ? $nearestActiveBatch->tgl_exp : ($data['tgl_exp'] ?? $barang->tgl_exp);
            $barang->save();
        });

        return redirect()->route('barang.index')->with('success', 'Data barang persediaan berhasil diperbarui dan stok batch terpotong otomatis.');
    }

    public function storePengeluaran(Request $request, $id)
    {
        $request->validate([
            'jumlah_pengeluaran' => 'required|numeric|min:0.1',
        ]);

        $barang = Barang::findOrFail($id);
        $jumlahKeluarBaru = $request->jumlah_pengeluaran;

        \Illuminate\Support\Facades\DB::transaction(function () use ($barang, $jumlahKeluarBaru) {
            
            // 1. Akumulasikan total pengeluaran dan perbarui saldo akhir di tabel utama barang
            $barang->pengeluaran += $jumlahKeluarBaru;
            $barang->saldo_akhir = ($barang->saldo_awal + $barang->penerimaan) - $barang->pengeluaran;
            $barang->save();

            // 2. Alokasikan pengeluaran secara FEFO ke tabel transaksi_barang berdasarkan tgl_exp tercepat
            $sisaPengeluaran = $jumlahKeluarBaru;

            $batches = TransaksiBarang::where('barang_id', $barang->barang_id)
                ->where('jumlah_penerimaan', '>', 0)
                ->orderBy('tgl_exp', 'asc')
                ->get();

            foreach ($batches as $batch) {
                if ($sisaPengeluaran <= 0) break;

                // Hitung sisa stok bersih di batch ini
                $sudahKeluarDiBatch = TransaksiBarang::where('barang_id', $barang->barang_id)
                    ->where('tgl_exp', $batch->tgl_exp)
                    ->sum('jumlah_pengeluaran');

                $sisaDiBatch = $batch->jumlah_penerimaan - $sudahKeluarDiBatch;

                if ($sisaDiBatch > 0) {
                    if ($sisaPengeluaran >= $sisaDiBatch) {
                        $ambilDariBatch = $sisaDiBatch;
                        $sisaPengeluaran -= $sisaDiBatch;
                    } else {
                        $ambilDariBatch = $sisaPengeluaran;
                        $sisaPengeluaran = 0;
                    }

                    // Catat pengeluaran terikat pada tanggal expired batch tersebut
                    TransaksiBarang::create([
                        'barang_id' => $barang->barang_id,
                        'jumlah_penerimaan' => 0,
                        'jumlah_pengeluaran' => $ambilDariBatch,
                        'harga' => $barang->harga_rata ?? 0,
                        'tgl_exp' => $batch->tgl_exp,
                    ]);
                }
            }

            // 3. Otomatis perbarui tgl_exp utama di tabel barang dengan mencari batch aktif yang SISA STOKNYA MASIH ADA (> 0)
            $nearestActiveBatch = TransaksiBarang::where('barang_id', $barang->barang_id)
                ->whereNotNull('tgl_exp')
                ->select('tgl_exp', \Illuminate\Support\Facades\DB::raw('SUM(jumlah_penerimaan) - SUM(jumlah_pengeluaran) as sisa_stok'))
                ->groupBy('tgl_exp')
                ->having('sisa_stok', '>', 0)
                ->orderBy('tgl_exp', 'asc')
                ->first();

            // Tanggal expired utama bergeser otomatis ke sisa batch berikutnya
            $barang->tgl_exp = $nearestActiveBatch ? $nearestActiveBatch->tgl_exp : null;
            $barang->save();
        });

        return back()->with('success', 'Pengeluaran berhasil dicatat dan tanggal expired otomatis memperbarui diri!');
    }    

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $saldoAkhir = ($barang->saldo_awal + $barang->penerimaan) - $barang->pengeluaran;

        if ($saldoAkhir > 0) {
            return redirect()->route('barang.index')
                ->with('error', 'Data barang tidak bisa dihapus karena sisa stok masih ada (' . $saldoAkhir . ' ' . $barang->satuan . ').');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($barang) {
            $barang->transaksiBarang()->delete();
            $barang->permintaanPengadaan()->delete();
            $barang->delete();
        });

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil dihapus');
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