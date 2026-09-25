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

        $saldoAkhirSql = '(COALESCE(saldo_awal, 0) + COALESCE(penerimaan, 0) - COALESCE(pengeluaran, 0))';

        $barangHabisCount = (clone $query)
            ->whereRaw("{$saldoAkhirSql} <= 0")
            ->count();

        $barangMenipisCount = (clone $query)
            ->whereRaw("{$saldoAkhirSql} > 0")
            ->whereRaw("{$saldoAkhirSql} <= COALESCE(minimal_stok, 0)")
            ->count();

        $barang = $query->latest()->paginate(10)->withQueryString();

        return view('barang.index', compact(
            'barang',
            'search',
            'filterKondisi',
            'barangHabisCount',
            'barangMenipisCount'
        ));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(\App\Http\Requests\BarangRequest $request)
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

        $barang = Barang::create($data);

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

    public function update(\App\Http\Requests\BarangRequest $request, $id)
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

        $roleName = Auth::user()->role->nama_role ?? '';
        $isAuthorizedForPricing = in_array($roleName, [
            'HR', 
            'Admin Aplikasi',
            'Koordinator Laboratorium',
            'Analis Lab'
        ]);
        
        if (!$isAuthorizedForPricing) {
            $data['harga_rata'] = $barang->harga_rata;
        }

        $saldoAwal = $data['saldo_awal'] ?? 0;
        $penerimaan = $data['penerimaan'] ?? 0;
        $pengeluaranBaru = $data['pengeluaran'] ?? 0;

        \Illuminate\Support\Facades\DB::transaction(function () use ($barang, $data, $saldoAwal, $penerimaan, $pengeluaranBaru) {
            
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

            $barang->nama_barang = $data['nama_barang'];
            $barang->satuan = $data['satuan'];
            $barang->kode_barang = $data['kode_barang'];
            $barang->minimal_stok = $data['minimal_stok'] ?? $barang->minimal_stok;
            $barang->saldo_awal = $saldoAwal;
            $barang->penerimaan = $penerimaan;
            $barang->kondisi = $data['kondisi'];
            
            $barang->saldo_akhir = ($barang->saldo_awal + $barang->penerimaan) - $barang->pengeluaran;

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
            
            $barang->pengeluaran += $jumlahKeluarBaru;
            $barang->saldo_akhir = ($barang->saldo_awal + $barang->penerimaan) - $barang->pengeluaran;
            $barang->save();

            $sisaPengeluaran = $jumlahKeluarBaru;

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

            $nearestActiveBatch = TransaksiBarang::where('barang_id', $barang->barang_id)
                ->whereNotNull('tgl_exp')
                ->select('tgl_exp', \Illuminate\Support\Facades\DB::raw('SUM(jumlah_penerimaan) - SUM(jumlah_pengeluaran) as sisa_stok'))
                ->groupBy('tgl_exp')
                ->having('sisa_stok', '>', 0)
                ->orderBy('tgl_exp', 'asc')
                ->first();

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
        $user = Auth::user();
        $cetakOleh = $user->username ?? ($user->nama ?? ($user->name ?? 'System PT Sucofindo'));

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('barang.cetak-periode', compact('barang', 'bulan', 'tahun', 'cetakOleh'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream('Laporan_Inventori_Bahan_' . $bulan . '_' . $tahun . '.pdf');
    }
}