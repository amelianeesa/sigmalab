<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\Modul;

class HakAksesController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $modules = Modul::all();

        // Get current hak akses
        $hakAksesData = DB::table('hak_akses')->get();
        $matrix = [];

        foreach ($hakAksesData as $ha) {
            $matrix[$ha->role_id][$ha->modul_id] = $ha->level_akses;
        }

        return view('hak-akses.index', compact('roles', 'modules', 'matrix'));
    }

    public function update(Request $request)
    {
        $data = $request->input('matrix', []);
        
        DB::transaction(function () use ($data) {
            // First delete all existing access rights to replace with the new ones
            DB::table('hak_akses')->delete();

            $inserts = [];
            foreach ($data as $roleId => $modules) {
                foreach ($modules as $modulId => $levelAkses) {
                    if ($levelAkses !== 'none') {
                        $inserts[] = [
                            'role_id' => $roleId,
                            'modul_id' => $modulId,
                            'level_akses' => $levelAkses,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            if (!empty($inserts)) {
                DB::table('hak_akses')->insert($inserts);
            }
        });

        // Log to spatie activity log manually since this is a DB facade operation
        activity()
            ->causedBy(Auth::user())
            ->event('updated')
            ->log('Memperbarui matriks hak akses seluruh Role');

        // Clear permission cache
        \Illuminate\Support\Facades\Cache::forget('hak_akses_matrix');

        return redirect()->route('hak-akses.index')->with('success', 'Konfigurasi hak akses berhasil diperbarui.');
    }
}
