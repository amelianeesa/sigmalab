<?php

namespace App\Http\Controllers;

use App\Models\HasilUji;
use App\Models\Kegiatan;
use App\Models\ParameterUji;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class HasilUjiController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', HasilUji::class);

        $filterKegiatan = $request->input('filter_kegiatan');
        $filterStatus = $request->input('filter_status');

        $query = HasilUji::with(['kegiatan', 'parameterUji', 'penginput']);

        if ($filterKegiatan) {
            $query->where('kegiatan_id', $filterKegiatan);
        }

        if ($filterStatus) {
            $query->where('status_berketerimaan', $filterStatus);
        }

        $hasilUjiList = $query->latest('created_at')->paginate(10);
        $kegiatanList = Kegiatan::all();

        return view('hasil-uji.index', compact('hasilUjiList', 'kegiatanList', 'filterKegiatan', 'filterStatus'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', HasilUji::class);

        $kegiatanList = Kegiatan::where('status_kegiatan', '!=', 'dibatalkan')->get();
        $parameterList = ParameterUji::where('status_aktif', true)->get();

        $selectedKegiatan = $request->input('kegiatan_id');

        return view('hasil-uji.create', compact('kegiatanList', 'parameterList', 'selectedKegiatan'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', HasilUji::class);

        $validated = $request->validate([
            'kegiatan_id' => 'required|exists:kegiatan,kegiatan_id',
            'parameter_uji_id' => 'required|exists:parameter_uji,parameter_uji_id',
            'nilai_hasil' => 'nullable|numeric',
            'variabel' => 'nullable|array',
            'variabel.*' => 'numeric',
        ]);

        $kegiatan = Kegiatan::findOrFail($validated['kegiatan_id']);
        if (in_array($kegiatan->status_kegiatan, ['selesai', 'dibatalkan'])) {
            return back()->with('error', 'Tidak dapat menambahkan hasil uji karena kegiatan sudah selesai atau dibatalkan.');
        }

        $parameter = ParameterUji::findOrFail($validated['parameter_uji_id']);
        $nilaiHasil = null;

        if (!empty($validated['variabel']) && !empty($parameter->rumus_kalkulasi)) {
            try {
                $expressionLanguage = new \Symfony\Component\ExpressionLanguage\ExpressionLanguage();
                $nilaiHasil = $expressionLanguage->evaluate($parameter->rumus_kalkulasi, $validated['variabel']);
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal mengkalkulasi rumus QC: ' . $e->getMessage());
            }
        } else {
            if (!isset($validated['nilai_hasil'])) {
                return back()->with('error', 'Nilai hasil wajib diisi jika tidak menggunakan rumus.');
            }
            $nilaiHasil = (float) $validated['nilai_hasil'];
        }

        if ($nilaiHasil >= $parameter->batas_bawah && $nilaiHasil <= $parameter->batas_atas) {
            $statusBerketerimaan = 'inlier';
        } else {
            $statusBerketerimaan = 'outlier';
        }

        HasilUji::create([
            'kegiatan_id' => $validated['kegiatan_id'],
            'parameter_uji_id' => $validated['parameter_uji_id'],
            'nilai_hasil' => $nilaiHasil,
            'status_berketerimaan' => $statusBerketerimaan,
            'diinput_oleh' => Auth::id(),
            'created_at' => now(),
        ]);

        $message = "Hasil uji berhasil disimpan. Status: " . strtoupper($statusBerketerimaan);
        if ($statusBerketerimaan === 'outlier') {
            $message .= " — Nilai di luar batas ({$parameter->batas_bawah} - {$parameter->batas_atas}). Perlu tindak lanjut.";
        }

        return redirect()->route('kegiatan.show', $validated['kegiatan_id'])->with('success', $message);
    }

    public function show($id)
    {
        $hasilUji = HasilUji::findOrFail($id);
        $this->authorize('view', $hasilUji);

        $hasilUji->load(['kegiatan', 'parameterUji', 'penginput', 'tindakLanjut.penindaklanjut']);

        return view('hasil-uji.show', compact('hasilUji'));
    }

}
