<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\RiwayatKalibrasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Models\ItemPemeliharaan;
use App\Models\LogPemeliharaan;
use App\Models\RiwayatPerbaikanAlat;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\KalibrasiExport;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Concerns\FromArray;

class AlatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterStatus = $request->input('filter_status');
        $filterKondisi = $request->input('filter_kondisi');

        $query = Alat::with(['riwayatKalibrasi', 'kegiatanAlat']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_alat', 'LIKE', "%{$search}%")
                  ->orWhere('kode_alat', 'LIKE', "%{$search}%")
                  ->orWhere('merk_tipe', 'LIKE', "%{$search}%");
            });
        }

        if ($filterKondisi) {
            $query->where('kondisi_barang', $filterKondisi);
        }

        $alatList = $query->latest()->get();

        if ($filterStatus) {
            $alatList = $alatList->filter(function($item) use ($filterStatus) {
                $kalibrasiTerakhir = $item->riwayatKalibrasi->sortByDesc('tgl_kalibrasi')->first();
                if (!$kalibrasiTerakhir || !$kalibrasiTerakhir->tgl_akhir) {
                    return false;
                }
                
                $tglAkhir = Carbon::parse($kalibrasiTerakhir->tgl_akhir);
                $sekarang = Carbon::now()->startOfDay();
                $sisaHari = $sekarang->diffInDays($tglAkhir, false);

                if ($filterStatus == 'kedaluarsa') {
                    return $sisaHari < 0;
                } elseif ($filterStatus == 'segera') {
                    return $sisaHari >= 0 && $sisaHari <= 30;
                } elseif ($filterStatus == 'aktif') {
                    return $sisaHari > 30;
                }
                return true;
            });
        }

        $alat = $alatList;

        return view('alat.index', compact('alat', 'search', 'filterStatus', 'filterKondisi'));
    }

    public function create()
    {
        return view('alat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_alat' => 'required|string|max:50|unique:alat,kode_alat',
            'nama_alat' => 'required|string|max:100',
            'merk_tipe' => 'nullable|string|max:100',
            'no_seri' => 'nullable|string|max:100',
            'warna' => 'nullable|string|max:30',
            'ukuran' => 'nullable|string|max:50',
            'kondisi_barang' => 'required|in:baik,rusak,perbaikan',
            'status_barang' => 'required|in:terpakai,idle',
            'unit_kerja_pemilik' => 'nullable|string|max:100',
            'no_sertifikat' => 'nullable|string|max:100',
            'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'interval_kalibrasi' => 'nullable|string|max:50',
            'tgl_kalibrasi' => 'nullable|date',
            'tgl_akhir' => 'nullable|date',
            'lembaga_kalibrasi' => 'nullable|string|max:150',
            'jenis_kalibrasi' => 'nullable|in:internal,eksternal',
            'range_kapasitas' => 'nullable|string|max:100',
            'faktor_koreksi' => 'nullable|string|max:100',
            'file_faktor_koreksi' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'signifikan' => 'nullable|in:ya,tidak',
            'catatan_evaluasi' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $alat = Alat::create([
                'kode_alat' => $request->kode_alat,
                'nama_alat' => $request->nama_alat,
                'merk_tipe' => $request->merk_tipe,
                'no_seri' => $request->no_seri,
                'warna' => $request->warna,
                'ukuran' => $request->ukuran,
                'kondisi_barang' => $request->kondisi_barang,
                'status_barang' => $request->status_barang,
                'unit_kerja_pemilik' => $request->unit_kerja_pemilik,
            ]);

            if ($request->filled('tgl_kalibrasi')) {
                $dataKalibrasi = [
                    'alat_id' => $alat->alat_id,
                    'jenis_kalibrasi' => $request->jenis_kalibrasi,
                    'no_sertifikat' => $request->no_sertifikat,
                    'interval_kalibrasi' => $request->interval_kalibrasi,
                    'tgl_kalibrasi' => $request->tgl_kalibrasi,
                    'tgl_akhir' => $request->tgl_akhir,
                    'lembaga_kalibrasi' => $request->lembaga_kalibrasi,
                    'range_kapasitas' => $request->range_kapasitas,
                    'faktor_koreksi' => $request->faktor_koreksi,
                    'signifikan' => $request->signifikan ?? 'tidak',
                    'catatan_evaluasi' => $request->catatan_evaluasi,
                ];
                if ($request->hasFile('file_sertifikat')) {
                    $path = $request->file('file_sertifikat')->store('sertifikat_kalibrasi', 'public');
                    $dataKalibrasi['file_sertifikat'] = $path;
                }
                if ($request->hasFile('file_faktor_koreksi')) {
                    $pathFaktor = $request->file('file_faktor_koreksi')->store('faktor_koreksi', 'public');
                    $dataKalibrasi['file_faktor_koreksi'] = $pathFaktor;
                }

                RiwayatKalibrasi::create($dataKalibrasi);
            }
        });

        return redirect()->route('alat.index')->with('success', 'Data alat beserta informasi kalibrasinya berhasil ditambahkan');
    }

    public function edit($id)
    {
        $alat = Alat::with(['riwayatKalibrasi' => function($query) {
            $query->latest('tgl_kalibrasi');
        }])->findOrFail($id);

        $kalibrasiTerakhir = $alat->riwayatKalibrasi->first();

        return view('alat.edit', compact('alat', 'kalibrasiTerakhir'));
    }

    public function update(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);

        $request->validate([
            'nama_alat' => 'required|string|max:100',
            'merk_tipe' => 'nullable|string|max:100',
            'no_seri' => 'nullable|string|max:100',
            'warna' => 'nullable|string|max:30',
            'ukuran' => 'nullable|string|max:50',
            'kondisi_barang' => 'required|in:baik,rusak,perbaikan',
            'status_barang' => 'required|in:terpakai,idle',
            'unit_kerja_pemilik' => 'nullable|string|max:100',
            'no_sertifikat' => 'nullable|string|max:100',
            'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'interval_kalibrasi' => 'nullable|string|max:50',
            'tgl_kalibrasi' => 'nullable|date',
            'tgl_akhir' => 'nullable|date',
            'lembaga_kalibrasi' => 'nullable|string|max:150',
            'jenis_kalibrasi' => 'nullable|in:internal,eksternal',
            'range_kapasitas' => 'nullable|string|max:100',
            'faktor_koreksi' => 'nullable|string|max:100',
            'file_faktor_koreksi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'signifikan' => 'nullable|in:ya,tidak',
            'catatan_evaluasi' => 'nullable|string',
        ]);

        $statusBarang = $request->status_barang;
        if (in_array($request->kondisi_barang, ['rusak', 'perbaikan'])) {
            $statusBarang = 'idle';
        }

        DB::transaction(function () use ($request, $alat, $statusBarang) {
            $alat->update([
                'nama_alat' => $request->nama_alat,
                'merk_tipe' => $request->merk_tipe,
                'no_seri' => $request->no_seri,
                'warna' => $request->warna,
                'ukuran' => $request->ukuran,
                'kondisi_barang' => $request->kondisi_barang,
                'status_barang' => $statusBarang,
                'unit_kerja_pemilik' => $request->unit_kerja_pemilik,
            ]);

            $kalibrasiTerakhir = $alat->riwayatKalibrasi()->latest('tgl_kalibrasi')->first();

            $dataKalibrasi = [
                'jenis_kalibrasi' => $request->jenis_kalibrasi,
                'no_sertifikat' => $request->no_sertifikat,
                'interval_kalibrasi' => $request->interval_kalibrasi,
                'tgl_kalibrasi' => $request->tgl_kalibrasi,
                'tgl_akhir' => $request->tgl_akhir,
                'lembaga_kalibrasi' => $request->lembaga_kalibrasi,
                'range_kapasitas' => $request->range_kapasitas,
                'faktor_koreksi' => $request->faktor_koreksi,
                'signifikan' => $request->signifikan ?? 'tidak',
                'catatan_evaluasi' => $request->catatan_evaluasi,
            ];

            if ($request->hasFile('file_sertifikat')) {
                $path = $request->file('file_sertifikat')->store('sertifikat_kalibrasi', 'public');
                $dataKalibrasi['file_sertifikat'] = $path;
            }
            
            if ($request->hasFile('file_faktor_koreksi')) {
                $pathFaktor = $request->file('file_faktor_koreksi')->store('faktor_koreksi', 'public');
                $dataKalibrasi['file_faktor_koreksi'] = $pathFaktor;
            }
        

            if ($request->filled('tgl_kalibrasi')) {
                if ($kalibrasiTerakhir) {
                    $kalibrasiTerakhir->update($dataKalibrasi);
                } else {
                    $dataKalibrasi['alat_id'] = $alat->alat_id;
                    RiwayatKalibrasi::create($dataKalibrasi);
                }
            }
        });

        return redirect()->route('alat.index')->with('success', 'Data alat dan informasi kalibrasi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $alat = Alat::with(['riwayatKalibrasi', 'kegiatanAlat'])->findOrFail($id);

        $alat->riwayatKalibrasi()->delete();
        $alat->kegiatanAlat()->delete();
        $alat->delete();

        return redirect()->route('alat.index')->with('success', 'Data alat berhasil dihapus');
    }

    public function show($id)
    {
        $alat = Alat::with(['riwayatKalibrasi', 'kegiatanAlat.kegiatan.personil', 'riwayatPerbaikan.pelapor', 'riwayatPerbaikan.verifikator'])->findOrFail($id);
        
        $sedangDiperbaiki = $alat->riwayatPerbaikan()->whereIn('status_perbaikan', ['Belum Diperbaiki', 'Dalam Perbaikan'])->first();

        return view('alat.show', compact('alat', 'sedangDiperbaiki'));
    }

    public function inputKalibrasi($id)
    {
        $alat = Alat::with(['riwayatKalibrasi' => function($query) {
            $query->orderBy('tgl_kalibrasi', 'asc');
        }])->findOrFail($id);

        return view('alat.input-kalibrasi', compact('alat'));
    }
    public function inputKalibrasiByKode($kode_alat)
{
    $alat = Alat::with(['riwayatKalibrasi' => function($query) {
        $query->orderBy('tgl_kalibrasi', 'desc');
    }, 'itemPemeliharaan'])->where('kode_alat', $kode_alat)->firstOrFail();

    return view('alat.input-kalibrasi', compact('alat'));
}

    public function storeInputKalibrasi(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu!');
        }

        $request->validate([
            'jenis_kalibrasi' => 'required|in:internal,eksternal',
            'no_sertifikat' => 'required|string|max:100',
            'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'interval_kalibrasi' => 'required|string|max:50',
            'tgl_kalibrasi' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_kalibrasi',
            'lembaga_kalibrasi' => 'required|string|max:150',
            'range_kapasitas' => 'nullable|string|max:100',
            'faktor_koreksi' => 'nullable|string|max:100',
            'signifikan' => 'required|in:ya,tidak',
            'catatan_evaluasi' => 'nullable|string',
        ]);

        $dataKalibrasi = [
            'alat_id' => $id,
            'jenis_kalibrasi' => $request->jenis_kalibrasi,
            'no_sertifikat' => $request->no_sertifikat,
            'interval_kalibrasi' => $request->interval_kalibrasi,
            'tgl_kalibrasi' => $request->tgl_kalibrasi,
            'tgl_akhir' => $request->tgl_akhir,
            'lembaga_kalibrasi' => $request->lembaga_kalibrasi,
            'range_kapasitas' => $request->range_kapasitas,
            'faktor_koreksi' => $request->faktor_koreksi,
            'signifikan' => $request->signifikan,
            'catatan_evaluasi' => $request->catatan_evaluasi,
        ];
        if ($request->hasFile('file_sertifikat')) {
            $path = $request->file('file_sertifikat')->store('sertifikat_kalibrasi', 'public');
            // dd($path);
            $dataKalibrasi['file_sertifikat'] = $path;
        }
        RiwayatKalibrasi::create($dataKalibrasi);

        return redirect()->route('alat.input-kalibrasi', $id)->with('success', 'Data riwayat kalibrasi baru berhasil ditambahkan!');
    }

    public function publicScan($kode_alat)
    {
        $alat = Alat::with(['riwayatKalibrasi' => function($query) {
            $query->orderBy('tgl_kalibrasi', 'desc');
        }, 'itemPemeliharaan'])->where('kode_alat', $kode_alat)->firstOrFail();

        return view('alat.public-scan', compact('alat'));
    }
    
    public function pemeliharaanBulanan(Request $request, $id)
    {
        $alat = Alat::with(['itemPemeliharaan' => function($query) {
            $query->orderBy('nomor_urut', 'asc');
        }])->findOrFail($id);

        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $namaPetugasLogin = Auth::user()?->personil?->nama ?? Auth::user()?->username;
        
        $logs = LogPemeliharaan::where('alat_id', $id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get()
            ->keyBy(function($item) {
                return $item->item_id . '_' . date('j', strtotime($item->tanggal));
            });

        return view('alat.pemeliharaan', compact('alat', 'bulan', 'tahun', 'logs', 'namaPetugasLogin'));
    }

    public function updateItemPemeliharaan(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.nomor_urut' => 'required|integer|min:1',
            'items.*.nama_pemeliharaan' => 'nullable|string|max:255',
        ]);

        $incomingItems = collect($request->items)->filter(function($item) {
            return !empty($item['nama_pemeliharaan']);
        });

        $incomingNomorUrut = $incomingItems->pluck('nomor_urut')->toArray();

        ItemPemeliharaan::where('alat_id', $id)
            ->whereNotIn('nomor_urut', $incomingNomorUrut)
            ->delete();

        foreach ($request->items as $item) {
            if (!empty($item['nama_pemeliharaan'])) {
                ItemPemeliharaan::updateOrCreate(
                    [
                        'alat_id'    => $id,
                        'nomor_urut' => $item['nomor_urut'],
                    ],[
                        'nama_pemeliharaan' => $item['nama_pemeliharaan'],
                        ]
                );
            }
        }

        return redirect()->route('alat.pemeliharaan', $id)
            ->with('success', 'Daftar jenis pemeliharaan berhasil diperbarui tanpa menghilangkan riwayat centang!');
    }

    public function updatePemeliharaanHarian(Request $request, $id)
    {
        $tanggal = $request->tanggal;

        if ($request->has('item_id')) {
            $request->validate([
                'item_id' => 'required|exists:item_pemeliharaan,item_id',
                'status' => 'required|boolean'
            ]);

            LogPemeliharaan::updateOrCreate(
                [
                    'alat_id' => $id,
                    'item_id' => $request->item_id,
                    'tanggal' => $tanggal,
                ],
                [
                    'status' => $request->status,
                    'petugas' => Auth::user()?->name ?? Auth::user()?->username ?? 'Petugas'
                ]
            );

            return response()->json(['success' => true, 'message' => 'Status pemeliharaan diperbarui.']);
        }

        if ($request->has('tindakan') || $request->has('petugas')) {
            LogPemeliharaan::where('alat_id', $id)
                ->whereDate('tanggal', $tanggal)
                ->update([
                    'tindakan' => $request->tindakan,
                    'petugas' => $request->petugas
                ]);

            return response()->json(['success' => true, 'message' => 'Tindakan & petugas diperbarui.']);
        }

        return response()->json(['success' => false, 'message' => 'Data tidak valid.'], 400);
    }


    public function editItemPemeliharaan($id)
    {
        $alat = Alat::with('itemPemeliharaan')->findOrFail($id);
        return view('alat.edit-item-pemeliharaan', compact('alat'));
    }

    public function parseSertifikat(Request $request)
    {
        $request->validate([
            'sertifikat' => 'required|file|mimes:pdf|max:5120'
        ]);

        $file = $request->file('sertifikat');
        
        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($file->getPathname());
            $text = $pdf->getText();

            $tglKalibrasi = null;
            $tglAkhir = null;
            $sertifikatOleh = null;
            
            if (preg_match('/(?:Tanggal Kalibrasi|Date of Calibration|Tgl[.\s]*Kalibrasi)\s*[:\-]?\s*([0-9]{1,2}[\/\-\s][a-zA-Z0-9]+[\/\-\s][0-9]{2,4})/i', $text, $matches)) {
                try {
                    $tglKalibrasi = Carbon::parse($matches[1])->format('Y-m-d');
                } catch (\Exception $e) {}
            }

            if (preg_match('/(?:Berlaku Hingga|Valid Until|Due Date|Jatuh Tempo)\s*[:\-]?\s*([0-9]{1,2}[\/\-\s][a-zA-Z0-9]+[\/\-\s][0-9]{2,4})/i', $text, $matches)) {
                try {
                    $tglAkhir = Carbon::parse($matches[1])->format('Y-m-d');
                } catch (\Exception $e) {}
            }
            
            if (preg_match('/(?:Dikalibrasi Oleh|Laboratorium Kalibrasi|Diterbitkan Oleh)\s*[:\-]?\s*([A-Za-z0-9\s.,\-&]+)(?:\n|\r)/i', $text, $matches)) {
                $sertifikatOleh = trim($matches[1]);
            }

            if (!$tglKalibrasi) {
                if (preg_match('/([0-9]{1,2}[\/\-\.][0-9]{1,2}[\/\-\.][0-9]{4})/', $text, $matches)) {
                   $tglKalibrasi = Carbon::parse(str_replace('.', '-', $matches[1]))->format('Y-m-d');
                } else {
                   $tglKalibrasi = Carbon::now()->format('Y-m-d'); 
                }
            }
            
            if (!$tglAkhir) {
                $tglAkhir = Carbon::parse($tglKalibrasi)->addYear()->format('Y-m-d');
            }

            if (!$sertifikatOleh) {
                $sertifikatOleh = "Lab Kalibrasi Eksternal Terakreditasi";
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'tgl_kalibrasi' => $tglKalibrasi,
                    'tgl_akhir' => $tglAkhir,
                    'sertifikat_oleh' => $sertifikatOleh
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses dokumen PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportPdf($id)
    {
        $alat = Alat::with('riwayatKalibrasi')->findOrFail($id);
        $pdf = Pdf::loadView('alat.laporan-template', compact('alat'))->setPaper('a4', 'portrait');
        
        return $pdf->download('Laporan_' . $alat->nama_alat . '_' . $alat->kode_alat . '.pdf');
        
    }
           
    public function exportExcel($id)
    {
        $alat = Alat::with('riwayatKalibrasi')->findOrFail($id);
        $fileName = 'Laporan_' . $alat->nama_alat . '_' . $alat->kode_alat . '.xlsx';

        return Excel::download(new class($alat) implements \Maatwebsite\Excel\Concerns\FromArray, WithStyles, WithColumnWidths, WithEvents {
            protected $alat;
            public function __construct($alat) { $this->alat = $alat; }
            
            public function array(): array {
                return [];
            }

            public function styles(Worksheet $sheet): ?array {
                $rowCount = max(1, $this->alat->riwayatKalibrasi->count());
                $startHeaderRow = 11;
                $endRow = $startHeaderRow + $rowCount;

                // 1. Bersihkan background & border area atas (baris 1-8) agar putih polos
                $sheet->getStyle('A1:G3')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFFFFF']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_NONE]],
                ]);

                // 2. Tambahkan garis pembatas (border bawah) di baris 9 melintang dari kolom A sampai G
                $sheet->getStyle('A3:G3')->applyFromArray([
                    'borders' => [
                        'bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 
                        'color' => ['argb' => 'FF1F4E78']]
                    ]
                ]);

                // 3. Styling Header Tabel (Baris 10)
                    $sheet->getStyle('A' . $startHeaderRow . ':G' . $startHeaderRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);
                
                // 4. Border & Alignment untuk Isi Data Tabel
                    $sheet->getStyle('A' . ($startHeaderRow + 1) . ':G' . $endRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['vertical' => 'center', 'horizontal' => 'center'],
                ]);
                
                // 5. Text Wrapping untuk kolom teks panjang
                $sheet->getStyle('D' . ($startHeaderRow + 1) . ':G' . $endRow)->getAlignment()->setWrapText(true);

                return [];
            }

            public function columnWidths(): array {
                return ['A' => 15, 'B' => 12, 'C' => 27, 'D' => 30, 'E' => 25, 'F' => 12, 'G' => 25];
            }

            public function registerEvents(): array {
                return [
                    AfterSheet::class => function(AfterSheet $event) {
                        $sheet = $event->sheet->getDelegate();
                        
                        // Header Utama
                        $sheet->setCellValue('A1', 'SIGMA-LAB PT SUCOFINDO');
                        $sheet->setCellValue('A2', 'Laporan Kalibrasi Alat');
                        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(22);
                        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(16);
                        
                        // Info Detail Alat
                        $sheet->setCellValue('A5', 'Nama Alat'); $sheet->setCellValue('B5', ': ' . $this->alat->nama_alat);
                        $sheet->setCellValue('A6', 'Kode Alat'); $sheet->setCellValue('B6', ': ' . $this->alat->kode_alat);
                        $sheet->setCellValue('A7', 'Merk / Tipe'); $sheet->setCellValue('B7', ': ' . ($this->alat->merk_tipe ?? '-'));
                        $sheet->setCellValue('A8', 'Nomor Seri'); $sheet->setCellValue('B8', ': ' . ($this->alat->no_seri ?? '-'));
                        $sheet->setCellValue('A9', 'Unit Kerja Pemilik'); $sheet->setCellValue('B9', ': ' . ($this->alat->unit_kerja_pemilik ?? '-'));
                        
                        // Rata kiri untuk teks informasi detail alat di baris 6 sampai 10
                        $sheet->getStyle('A5:B9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                        // Tulis Header Tabel Manual di Baris 10
                        $headers = ['Urutan', 'Jenis', 'Tanggal Kalibrasi s/d Akhir', 'Lembaga & Sertifikat', 'Range & Faktor Koreksi', 'Signifikan', 'Catatan / Evaluasi'];
                        foreach ($headers as $col => $value) {
                            $sheet->setCellValue(Coordinate::stringFromColumnIndex($col + 1) . '11', $value);
                        }
                        
                        // Tulis Data Manual Mulai Baris 13
                        $rowNum = 12;
                        foreach($this->alat->riwayatKalibrasi as $index => $row) {
                            $data = [
                                'Kalibrasi ke-' . ($index + 1),
                                ucfirst($row->jenis_kalibrasi),
                                \Carbon\Carbon::parse($row->tgl_kalibrasi)->format('d-m-Y') . ' s/d ' . \Carbon\Carbon::parse($row->tgl_akhir)->format('d-m-Y'),
                                "Lembaga: " . $row->lembaga_kalibrasi . "\nSertifikat: " . $row->no_sertifikat,
                                "Range: " . ($row->range_kapasitas ?? '-') . "\nKoreksi: " . ($row->faktor_koreksi ?? '-'),
                                strtoupper($row->signifikan),
                                $row->catatan_evaluasi ?? '-',
                            ];
                            foreach ($data as $col => $value) {
                                $sheet->setCellValue(Coordinate::stringFromColumnIndex($col + 1) . $rowNum, $value);
                            }
                            $rowNum++;
                        }
                        
                        // Logo Perusahaan dipindah ke kolom G
                        $drawing = new Drawing();
                        $drawing->setPath(public_path('images/Logo_Suco_Nobg.png'));
                        $drawing->setHeight(70);
                        $drawing->setCoordinates('G1'); // Posisikan di kolom G
                        $drawing->setOffsetX(35);
                        $drawing->setOffsetY(5);
                        $drawing->setWorksheet($sheet);

                        // Pengaturan Page Setup & Print
                        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                        $sheet->getPageSetup()->setFitToWidth(1);
                        $sheet->getPageSetup()->setFitToHeight(0);

                        // Sembunyikan Gridlines agar tampilan bersih seperti dokumen resmi
                        $sheet->setShowGridlines(false);
                    },
                ];
            }
        }, $fileName);
    }
     

    public function exportPemeliharaanPdf(Request $request, $id)
    {
        $alat = Alat::with('itemPemeliharaan')->findOrFail($id);
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        // Ambil log pemeliharaan sesuai periode
        $rawLogs = LogPemeliharaan::where('alat_id', $id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $logs = [];
        foreach($rawLogs as $log) {
            $day = (int) date('d', strtotime($log->tanggal));
            if(isset($log->item_id)) {
                $logs[$log->item_id . '_' . $day] = $log;
            }
            $logs[$day] = [
                'tindakan' => $log->tindakan,
                'petugas' => $log->petugas
            ];
        }

        $pdf = Pdf::loadView('alat.pemeliharaan-template', compact('alat', 'logs', 'bulan', 'tahun'));
        $pdf->setPaper('A4', 'portrait');

        $namaAlatSafe = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $alat->nama_alat);
        return $pdf->download('Kartu_Pemeliharaan_' . $namaAlatSafe . '_' . $alat->kode_alat . '_' . $bulan . '_' . $tahun . '.pdf');
    }

    public function exportPemeliharaanExcel(Request $request, $id)
    {
        $alat = Alat::with('itemPemeliharaan')->findOrFail($id);
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        
        $namaAlatSafe = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $alat->nama_alat);
        $fileName = 'Kartu_Pemeliharaan_' . $namaAlatSafe . '_' . $bulan . '_' . $tahun . '_' . time() . '.xlsx';

        return Excel::download(new class($alat, $bulan, $tahun) implements FromArray, WithStyles, WithColumnWidths, WithEvents {
            protected $alat, $bulan, $tahun;

            public function __construct($alat, $bulan, $tahun) {
                $this->alat = $alat;
                $this->bulan = $bulan;
                $this->tahun = $tahun;
            }
            
            public function array(): array {
                return [];
            }

            public function styles(Worksheet $sheet): ?array {
                $totalItems = max(1, $this->alat->itemPemeliharaan->count());
                $lastColIndex = 1 + $totalItems + 2; 
                $lastColChar = Coordinate::stringFromColumnIndex($lastColIndex);

                //Bersihkan background area atas
                $sheet->getStyle('A1:' . $lastColChar . '9')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFFFFF']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_NONE]],
                ]);

                //Garis pembatas utama
                $sheet->getStyle('A3:' . $lastColChar . '3')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['argb' => 'FF1F4E78']
                        ]
                    ]
                ]);

                // 3. Styling Header Tabel
                $headerStartRow = 11;
                $endRow = $headerStartRow + 1 + 31; 

                $sheet->getStyle('A' . $headerStartRow . ':' . $lastColChar . ($headerStartRow + 1))->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);

                //Border untuk seluruh sel data harian
                $sheet->getStyle('A' . ($headerStartRow + 2) . ':' . $lastColChar . $endRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['vertical' => 'center', 'horizontal' => 'center'],
                ]);

                return [];
            }

            public function columnWidths(): array {
                $widths = [
                    'A' => 11 // Kolom Tanggal
                ];
                
                foreach($this->alat->itemPemeliharaan as $index => $item) {
                    $colLetter = Coordinate::stringFromColumnIndex(2 + $index);
                    $widths[$colLetter] = 6; 
                }
                if($this->alat->itemPemeliharaan->count() == 0) {
                    $widths['B'] = 8;
                }

                $totalItems = max(1, $this->alat->itemPemeliharaan->count());
                $tindakanColIndex = 2 + $totalItems;
                $petugasColIndex = $tindakanColIndex + 1;

                $widths[Coordinate::stringFromColumnIndex($tindakanColIndex)] = 28; 
                $widths[Coordinate::stringFromColumnIndex($petugasColIndex)] = 22;  

                return $widths;
            }

            public function registerEvents(): array {
                return [
                    AfterSheet::class => function(AfterSheet $event) {
                        $sheet = $event->sheet->getDelegate();
                        
                        // Header Utama
                        $sheet->setCellValue('A1', 'KARTU PEMELIHARAAN PERALATAN');
                        $sheet->setCellValue('A2', 'SIGMA-LAB PT SUCOFINDO');
                        
                        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
                        
                        // Info Detail Alat
                        $sheet->mergeCells('A4:B4'); $sheet->setCellValue('A4', 'Nama / Kode Peralatan'); $sheet->setCellValue('C4', ': ' . $this->alat->nama_alat . ' / ' . $this->alat->kode_alat);
                        $sheet->mergeCells('A5:B5'); $sheet->setCellValue('A5', 'Merk/No. Serial'); $sheet->setCellValue('C5', ': ' . ($this->alat->merk_tipe ?? '-') . ' / ' . ($this->alat->no_seri ?? '-'));
                        $sheet->mergeCells('A6:B6'); $sheet->setCellValue('A6', 'No. Inventaris'); $sheet->setCellValue('C6', ': ' . ($this->alat->no_inventaris ?? '-'));
                        $sheet->mergeCells('A7:B7'); $sheet->setCellValue('A7', 'Unit Kerja Pemilik'); $sheet->setCellValue('C7', ': ' . ($this->alat->lokasi_alat ?? $this->alat->unit_kerja_pemilik ?? '-'));
                        
                        // Jenis Pemeliharaan
                        $sheet->mergeCells('A8:B8'); $sheet->setCellValue('A8', 'Jenis Pemeliharaan'); 
                        $totalItems = $this->alat->itemPemeliharaan->count();
                        if ($totalItems > 0) {
                            $textList = "";
                            foreach($this->alat->itemPemeliharaan as $item) {
                                $textList .= $item->nomor_urut . '. ' . $item->nama_pemeliharaan . '   ';
                            }
                            $sheet->mergeCells('C8:F8');
                            $sheet->setCellValue('C8', ': ' . trim($textList));
                            $sheet->getStyle('C8')->getAlignment()->setWrapText(true);
                        } else {
                            $sheet->setCellValue('C8', ': -');
                        }

                        $namaBulan = \DateTime::createFromFormat('!m', (int)$this->bulan)->format('F');
                        $sheet->mergeCells('A9:B9'); $sheet->setCellValue('A9', 'BULAN / TAHUN'); 
                        $sheet->setCellValue('C9', ': ' . $namaBulan . ' / ' . $this->tahun);

                        $sheet->getStyle('A4:C9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                        // header tabel
                        $sheet->mergeCells('A11:A12');
                        $sheet->setCellValue('A11', 'Tanggal');

                        $colCount = max(1, $totalItems);
                        $startColIndex = 2; 
                        $endColIndex = $startColIndex + $colCount - 1;
                        $endColLetter = Coordinate::stringFromColumnIndex($endColIndex);

                        if ($colCount > 1) {
                            $sheet->mergeCells('B11:' . $endColLetter . '11');
                        }
                        $sheet->setCellValue('B11', 'Jenis Pemeriksaan / Status *)');

                        if ($totalItems > 0) {
                            foreach($this->alat->itemPemeliharaan as $index => $item) {
                                $colLetter = Coordinate::stringFromColumnIndex($startColIndex + $index);
                                $sheet->setCellValue($colLetter . '12', $item->nomor_urut);
                            }
                        } else {
                            $sheet->setCellValue('B12', '1');
                        }

                        $tindakanColIndex = $endColIndex + 1;
                        $petugasColIndex = $endColIndex + 2;

                        $tindakanColLetter = Coordinate::stringFromColumnIndex($tindakanColIndex);
                        $petugasColLetter = Coordinate::stringFromColumnIndex($petugasColIndex);

                        $sheet->mergeCells($tindakanColLetter . '11:' . $tindakanColLetter . '12');
                        $sheet->setCellValue($tindakanColLetter . '11', 'Tindakan');

                        $sheet->mergeCells($petugasColLetter . '11:' . $petugasColLetter . '12');
                        $sheet->setCellValue($petugasColLetter . '11', 'Petugas');

                        $rawLogs = LogPemeliharaan::where('alat_id', $this->alat->alat_id)
                            ->whereMonth('tanggal', $this->bulan)
                            ->whereYear('tanggal', $this->tahun)
                            ->get();

                        $logs = [];
                        foreach($rawLogs as $log) {
                            $day = (int) date('d', strtotime($log->tanggal));
                            if(isset($log->item_id)) {
                                $logs[$log->item_id . '_' . $day] = $log->status;
                            }
                            $logs['tindakan_' . $day] = $log->tindakan;
                            $logs['petugas_' . $day] = $log->petugas;
                        }

                        $rowNum = 13;
                        for($d = 1; $d <= 31; $d++) {
                            $sheet->setCellValue('A' . $rowNum, $d);

                            if ($totalItems > 0) {
                                foreach($this->alat->itemPemeliharaan as $index => $currentItem) {
                                    $colLetter = Coordinate::stringFromColumnIndex($startColIndex + $index);
                                    $key = $currentItem->item_id . '_' . $d;
                                    $isChecked = isset($logs[$key]) && $logs[$key] == 1;
                                    
                                    $sheet->setCellValue($colLetter . $rowNum, $isChecked ? 'v' : '');
                                }
                            } else {
                                $sheet->setCellValue('B' . $rowNum, '');
                            }

                            $sheet->setCellValue($tindakanColLetter . $rowNum, $logs['tindakan_' . $d] ?? '');
                            $sheet->setCellValue($petugasColLetter . $rowNum, $logs['petugas_' . $d] ?? '');

                            $rowNum++;
                        }

                        // Logo
                        if (file_exists(public_path('images/Logo_Suco_Nobg.png'))) {
                            $drawing = new Drawing();
                            $drawing->setPath(public_path('images/Logo_Suco_Nobg.png'));
                            $drawing->setHeight(45);
                            $drawing->setCoordinates($petugasColLetter . '1'); 
                            $drawing->setOffsetX(45); 
                            $drawing->setOffsetY(5);
                            $drawing->setWorksheet($sheet);
                        }

                        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                        
                        $sheet->getPageSetup()->setFitToPage(true);
                        $sheet->getPageSetup()->setFitToWidth(1);
                        $sheet->getPageSetup()->setFitToHeight(1); // Memaksa seluruh isi tabel sampai tanggal 31 muat dalam 1 halaman penuh

                        $sheet->setShowGridlines(false);
                    },
                ];
            }
        }, $fileName);
    }

    public function storePerbaikan(Request $request, $id)
    {
        $request->validate([
            'tanggal_rusak' => 'required|date',
            'deskripsi_kerusakan' => 'required|string',
        ]);

        $alat = Alat::findOrFail($id);

        RiwayatPerbaikanAlat::create([
            'alat_id' => $alat-> alat_id,
            'tanggal_rusak' => $request->tanggal_rusak,
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'dilaporkan_oleh' => Auth::id(),
            'status_perbaikan' => 'Belum Diperbaiki'
        ]);

        $alat->update([
            'kondisi_barang' => 'perbaikan',
            'status_barang' => 'idle'
        ]);

        return redirect()->back()->with('success', 'Laporan kerusakan alat berhasil dicatat');
    }

    public function updatePerbaikan(Request $request, $id, $perbaikan_id)
    {
        $perbaikan = RiwayatPerbaikanAlat::findOrFail($perbaikan_id);
        $alat = Alat::findOrFail($id);
        
        $request->validate([
            'status_perbaikan' => 'required|string|in:Dalam Perbaikan,Selesai,Tidak Bisa Diperbaiki',
            'tindakan_perbaikan' => 'nullable|string',
            'tanggal_selesai' => 'nullable|date',
        ]);

        if ($request->status_perbaikan === 'Selesai' || $request->status_perbaikan === 'Tidak Bisa Diperbaiki') {
            if (Auth::user()->role->nama_role !== \App\Enums\PeranPengguna::KOORDINATOR_LAB->value) {
                return redirect()->back()->withErrors(['message' => 'Hanya Koordinator Lab yang dapat memverifikasi penyelesaian perbaikan.']);
            }
            $perbaikan->diverifikasi_oleh = Auth::id();
            if (!$request->tanggal_selesai) {
                $perbaikan->tanggal_selesai = now();
            }
            
            if ($request->status_perbaikan === 'Selesai') {
                $alat->update([
                    'kondisi_barang' => 'baik',
                    'status_barang' => 'idle' 
            ]);
            }elseif ($request->status_perbaikan === 'Tidak Bisa Diperbaiki') {
                $alat->update([
                    'kondisi_barang' => 'rusak',
                    'status_barang' => 'idle'
            ]);
            }
        }elseif ($request->status_perbaikan === 'Dalam Perbaikan') {
            $alat->update([
                'kondisi_barang' => 'perbaikan',
                'status_barang' => 'idle'
        ]);
        }

        $perbaikan->update([
            'status_perbaikan' => $request->status_perbaikan,
            'tindakan_perbaikan' => $request->tindakan_perbaikan,
            'tanggal_selesai' => $request->tanggal_selesai ?? $perbaikan->tanggal_selesai,
        ]);

        return redirect()->back()->with('success', 'Status perbaikan berhasil diperbarui');
    }
}