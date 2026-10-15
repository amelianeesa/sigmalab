<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class AuditLogController extends Controller
{
    private function logAktif()
    {
        return Activity::where(function ($q) {
            $q->where('log_name', 'default')
              ->orWhere('log_name', 'auth')
              ->orWhere('subject_type', 'like', '%Alat%')
              ->orWhere('subject_type', 'like', '%RiwayatPerbaikanAlat%')
              ->orWhere('subject_type', 'like', '%SampelInhouse%')
              ->orWhere('subject_type', 'like', '%QcHarian%')
              ->orWhere('subject_type', 'like', '%Crm%')
              ->orWhereNull('subject_type');
        });
    }

    private function bolehHapus()
    {
        return (Auth::user()->role->nama_role ?? '') === 'Admin Aplikasi';
    }

    public function index(Request $request)
    {
        $query = $this->logAktif()->with('causer')->latest();

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('subject_type')) {
            $query->where('subject_type', 'like', '%' . $request->subject_type . '%');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('properties', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(15);

        return view('audit-log.index', compact('logs'));
    }

    public function show($id)
    {
        $log = Activity::with(['causer.personil', 'causer.role'])->findOrFail($id);

        $batchLogs = $log->batch_uuid
            ? Activity::where('batch_uuid', $log->batch_uuid)->with(['causer.personil', 'causer.role'])->get()
            : collect([$log]);

        return view('audit-log.show', compact('log', 'batchLogs'));
    }

    public function destroyBulk(Request $request)
    {
        abort_unless($this->bolehHapus(), 403);

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:activity_log,id',
        ]);

        $jumlah = $this->logAktif()->whereIn('id', $request->ids)->delete();

        return redirect()->route('audit-log.index')->with('success', $jumlah . ' log audit yang dipilih berhasil dihapus.');
    }

    public function destroyAll()
    {
        abort_unless($this->bolehHapus(), 403);

        $jumlah = $this->logAktif()->delete();

        return redirect()->route('audit-log.index')->with('success', $jumlah . ' riwayat audit log aktif berhasil dihapus.');
    }
}