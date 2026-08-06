
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personil; // Sesuaikan dengan model Anda
use App\Models\KompetensiPersonil;
use Illuminate\Support\Facades\Storage;

class SdmController extends Controller
{
    public function index()
    {
        $personil = Personil::where('status_aktif', true)->get();
        return view('sdm.index', compact('personil'));
    }

    public function create()
    {
        return view('sdm.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_induk' => 'required|unique:personil,no_induk',
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:100',
            'file_cv' => 'nullable|mimes:pdf|max:2048',
        ]);

        $cvName = null;
        if ($request->hasFile('file_cv')) {
            $cvName = time() . '_' . $request->file_cv->getClientOriginalName();
            $request->file_cv->storeAs('public/uploads/cv', $cvName);
        }

        Personil::create([
            'no_induk' => $request->no_induk,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'unit_kerja' => $request->unit_kerja,
            'file_cv' => $cvName,
            'status_aktif' => true,
        ]);

        return redirect()->route('sdm.index')->with('success', 'Data personil berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $personil = Personil::findOrFail($id);
        return view('sdm.edit', compact('personil'));
    }

    public function update(Request $request, $id)
    {
        $personil = Personil::findOrFail($id);

        $request->validate([
            'no_induk' => 'required|unique:personil,no_induk,' . $id . ',personil_id',
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:100',
            'file_cv' => 'nullable|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('file_cv')) {
            if ($personil->file_cv && Storage::exists('public/uploads/cv/' . $personil->file_cv)) {
                Storage::delete('public/uploads/cv/' . $personil->file_cv);
            }
            $cvName = time() . '_' . $request->file_cv->getClientOriginalName();
            $request->file_cv->storeAs('public/uploads/cv', $cvName);
            $personil->file_cv = $cvName;
        }

        $personil->update([
            'no_induk' => $request->no_induk,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'unit_kerja' => $request->unit_kerja,
        ]);

        return redirect()->route('sdm.index')->with('success', 'Data personil berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $personil = Personil::findOrFail($id);
        // Soft delete sesuai rancangan database
        $personil->update(['status_aktif' => false]);

        return redirect()->route('sdm.index')->with('success', 'Data personil dinonaktifkan (Soft Delete).');
    }

    public function kompetensiDetail($id)
    {
        $personil = Personil::with('kompetensi')->findOrFail($id); // Asumsi relasi 'kompetensi' ada di Model Personil
        return view('sdm.kompetensi_detail', compact('personil'));
    }

    public function showCv($id)
    {
        $personil = Personil::findOrFail($id);
        return view('sdm.cv_view', compact('personil'));
    }
}