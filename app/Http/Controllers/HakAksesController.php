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
            $upserts = [];
            $deleteQueries = DB::table('hak_akses');
            $hasDeletes = false;

            foreach ($data as $roleId => $modules) {
                foreach ($modules as $modulId => $levelAkses) {
                    if ($levelAkses !== 'none') {
                        $upserts[] = [
                            'role_id' => $roleId,
                            'modul_id' => $modulId,
                            'level_akses' => $levelAkses,
                        ];
                    } else {
                        if (!$hasDeletes) {
                            $deleteQueries->where(function ($q) use ($roleId, $modulId) {
                                $q->where('role_id', $roleId)->where('modul_id', $modulId);
                            });
                            $hasDeletes = true;
                        } else {
                            $deleteQueries->orWhere(function ($q) use ($roleId, $modulId) {
                                $q->where('role_id', $roleId)->where('modul_id', $modulId);
                            });
                        }
                    }
                }
            }

            if (!empty($upserts)) {
                \App\Models\HakAkses::upsert($upserts, ['role_id', 'modul_id'], ['level_akses']);
            }

            if ($hasDeletes) {
                $deleteQueries->delete();
            }
        });

        activity()
            ->causedBy(Auth::user())
            ->event('updated')
            ->log('Memperbarui matriks hak akses seluruh Role');

        \Illuminate\Support\Facades\Cache::forget('hak_akses_matrix');

        return redirect()->route('hak-akses.index')->with('success', 'Konfigurasi hak akses berhasil diperbarui.');
    }
}
