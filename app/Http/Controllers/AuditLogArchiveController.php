<?php

namespace App\Http\Controllers;

use App\Models\AuditlogArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogArchiveController extends Controller
{
    private function bolehHapus()
    {
        return (Auth::user()->role->nama_role ?? '') === 'Admin Aplikasi';
    }

    public function index(Request $request)
    {
        $query = AuditlogArchive::with('causer')->latest();

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('subject_type', 'like', "%{$search}%")
                  ->orWhere('properties', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(15);

        $years = AuditlogArchive::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('audit-log.archive', compact('logs', 'years'));
    }

    public function show($id)
    {
        $log = AuditlogArchive::with(['causer.personil', 'causer.role'])->findOrFail($id);

        return view('audit-log.archive-show', compact('log'));
    }

    public function destroyBulk(Request $request)
    {
        abort_unless($this->bolehHapus(), 403);

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $jumlah = AuditlogArchive::whereIn('id', $request->ids)->delete();

        return redirect()->route('audit-log.archive')->with('success', $jumlah . ' log arsip yang dipilih berhasil dihapus.');
    }

    public function destroyAll()
    {
        abort_unless($this->bolehHapus(), 403);

        $jumlah = AuditlogArchive::query()->delete();

        return redirect()->route('audit-log.archive')->with('success', $jumlah . ' log arsip berhasil dihapus.');
    }
}