<?php

namespace App\Http\Controllers;

use App\Models\LibraryCategory;
use App\Models\LibraryDocument;
use App\Models\LibraryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        return $this->renderDocumentList($request, true);
    }

public function exportPdf(Request $request)
    {
        $documents = LibraryDocument::with(['category', 'versions'])
            ->where('is_active', true)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim((string) $request->input('search'));

                $query->where(function ($documentQuery) use ($search) {
                    $documentQuery->where('judul', 'like', "%{$search}%")
                        ->orWhere('nomor_dokumen', 'like', "%{$search}%")
                        ->orWhere('penerbit_dokumen', 'like', "%{$search}%")
                        ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('nama_kategori', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->orderBy('nomor_dokumen')
            ->get();

        $category = $request->filled('category_id')
            ? LibraryCategory::find($request->integer('category_id'))
            : null;

        $tanggalCetak = \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i');

        $logoPath = public_path('images/logo.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('library.daftar-induk-pdf', compact('documents', 'category', 'tanggalCetak', 'logoBase64'))
            ->setPaper('a4', 'portrait');

        $namaFile = 'Daftar Induk Dokumen' . ($category ? ' - ' . $category->nama_kategori : '') . ' - ' . \Carbon\Carbon::now('Asia/Jakarta')->format('d-m-Y') . '.pdf';

        return $pdf->download($namaFile);
    }

    private function renderDocumentList(Request $request, bool $isActive)
    {
        $documents = LibraryDocument::with(['category', 'versions'])
            ->where('is_active', $isActive)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim((string) $request->input('search'));

                $query->where(function ($documentQuery) use ($search) {
                    $documentQuery->where('judul', 'like', "%{$search}%")
                        ->orWhere('nomor_dokumen', 'like', "%{$search}%")
                        ->orWhere('penerbit_dokumen', 'like', "%{$search}%")
                        ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('nama_kategori', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = LibraryCategory::where('is_active', true)->orderBy('nama_kategori')->get();

        $showArchived = !$isActive;

        return view('library.index', compact('documents', 'categories', 'showArchived'));
    }

    public function create()
    {
        $categories = LibraryCategory::where('is_active', true)->orderBy('nama_kategori')->get();

        return view('library.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:library_categories,id',
            'judul' => 'required|string|max:255',
            'nomor_dokumen' => 'nullable|string|max:100',
            'penerbit_dokumen' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_berlaku' => 'required|date',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:20480',
        ]);

        $file = $request->file('file');
        $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('library', $filename, 'public');

        $document = LibraryDocument::create([
            'category_id' => $request->category_id,
            'judul' => $request->judul,
            'nomor_dokumen' => $request->nomor_dokumen,
            'penerbit_dokumen' => $request->penerbit_dokumen,
            'deskripsi' => $request->deskripsi,
            'file_path' => $path,
            'file_name' => $filename,
            'file_extension' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $document->versions()->create([
            'version_number' => '00',
            'revisi_ke' => 0,
            'judul' => $document->judul,
            'file_path' => $path,
            'file_name' => $filename,
            'catatan_revisi' => 'Dokumen baru',
            'tanggal_terbit' => $request->tanggal_berlaku,
            'tanggal_berlaku' => $request->tanggal_berlaku,
            'created_by' => auth()->id(),
        ]);

        LibraryLog::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'action' => 'upload',
            'keterangan' => 'Dokumen baru ditambahkan.',
        ]);

        return redirect()->route('library.index')->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function show($id)
    {
        $document = LibraryDocument::with(['category', 'versions.creator', 'logs.user'])
            ->where('is_active', true)
            ->findOrFail($id);

        return view('library.show', compact('document'));
    }

    public function edit($id)
    {
        $document = LibraryDocument::with('versions')->where('is_active', true)->findOrFail($id);
        $categories = LibraryCategory::where('is_active', true)->orderBy('nama_kategori')->get();
        $latestVersion = $document->versions->sortByDesc('revisi_ke')->first();

        return view('library.edit', compact('document', 'categories', 'latestVersion'));
    }

    public function update(Request $request, $id)
    {
        $document = LibraryDocument::where('is_active', true)->findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:library_categories,id',
            'judul' => 'required|string|max:255',
            'nomor_dokumen' => 'nullable|string|max:100',
            'penerbit_dokumen' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_berlaku' => 'required|date',
        ]);

        $document->update([
            'category_id' => $request->category_id,
            'judul' => $request->judul,
            'nomor_dokumen' => $request->nomor_dokumen,
            'penerbit_dokumen' => $request->penerbit_dokumen,
            'deskripsi' => $request->deskripsi,
            'updated_by' => auth()->id(),
        ]);

        $latestVersion = $document->versions()->orderByDesc('revisi_ke')->first();
        if ($latestVersion) {
            $latestVersion->update([
                'judul' => $document->judul,
                'tanggal_berlaku' => $request->tanggal_berlaku,
            ]);
        }

        LibraryLog::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'action' => 'edit',
            'keterangan' => 'Metadata dokumen diperbarui.',
        ]);

        return redirect()->route('library.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $document = LibraryDocument::where('is_active', true)->findOrFail($id);
        $document->update([
            'is_active' => false,
            'updated_by' => auth()->id(),
        ]);

        LibraryLog::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'action' => 'nonaktifkan',
            'keterangan' => 'Dokumen dinonaktifkan dan tetap disimpan untuk keperluan audit.',
        ]);

        return redirect()->route('library.index')->with('success', 'Dokumen berhasil dinonaktifkan.');
    }

    public function activate($id)
    {
        $document = LibraryDocument::where('is_active', false)->findOrFail($id);
        $document->update([
            'is_active' => true,
            'updated_by' => auth()->id(),
        ]);

        LibraryLog::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'action' => 'aktifkan_kembali',
            'keterangan' => 'Dokumen ditampilkan kembali pada daftar aktif.',
        ]);

        return redirect()->route('library.index')->with('success', 'Dokumen berhasil ditampilkan kembali.');
    }

    public function download($id)
    {
        $document = LibraryDocument::where('is_active', true)->findOrFail($id);

        return $this->downloadFile($document, $document->file_path, $document->file_name, 'Dokumen versi terbaru diunduh.');
    }

    public function downloadVersion($id, $versionId)
    {
        $document = LibraryDocument::where('is_active', true)->findOrFail($id);
        $version = $document->versions()->findOrFail($versionId);

        return $this->downloadFile($document, $version->file_path, $version->file_name, "Dokumen versi {$version->version_number} diunduh.");
    }

    public function createRevision($id)
    {
        $document = LibraryDocument::where('is_active', true)->findOrFail($id);

        return view('library.revision', compact('document'));
    }

    public function storeRevision(Request $request, $id)
    {
        $document = LibraryDocument::where('is_active', true)->findOrFail($id);

        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:20480',
            'catatan_revisi' => 'nullable|string',
            'tanggal_berlaku' => 'nullable|date',
        ]);

        $file = $request->file('file');
        $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('library', $filename, 'public');

        $nextRevision = ((int) $document->versions()->max('revisi_ke')) + 1;
        $versionNumber = str_pad((string) $nextRevision, 2, '0', STR_PAD_LEFT);

        $document->versions()->create([
            'version_number' => $versionNumber,
            'revisi_ke' => $nextRevision,
            'judul' => $document->judul,
            'file_path' => $path,
            'file_name' => $filename,
            'catatan_revisi' => $request->catatan_revisi,
            'tanggal_terbit' => $request->tanggal_berlaku ?? now()->toDateString(),
            'tanggal_berlaku' => $request->tanggal_berlaku ?? now()->toDateString(),
            'created_by' => auth()->id(),
        ]);

        $document->update([
            'file_path' => $path,
            'file_name' => $filename,
            'file_extension' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'updated_by' => auth()->id(),
        ]);

        LibraryLog::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'action' => 'revisi',
            'keterangan' => 'Versi dokumen baru ditambahkan.',
            'new_data' => ['version' => $versionNumber],
        ]);

        return redirect()->route('library.show', $document->id)->with('success', 'Versi dokumen baru berhasil ditambahkan.');
    }

    private function downloadFile(LibraryDocument $document, string $path, string $filename, string $keterangan)
    {
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        LibraryLog::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'action' => 'download',
            'keterangan' => $keterangan,
        ]);

        return Storage::disk('public')->download($path, $filename);
    }
}