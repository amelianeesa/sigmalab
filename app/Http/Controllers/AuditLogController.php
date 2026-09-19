<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Pagination\LengthAwarePaginator;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer')
            ->where(function($q) {
                $q->where('log_name', 'default')
                  ->orWhere('subject_type', 'like', '%Alat%')
                  ->orWhere('subject_type', 'like', '%RiwayatPerbaikanAlat%');
            })
            ->latest();

        // Filter berdasarkan pencarian jika ada
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('properties', 'like', "%{$search}%");
            });
        }

        // Menggunakan LengthAwarePaginator agar pagination rapi
        $perPage = 15;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $total = $query->count();
        $results = $query->skip(($page - 1) * $perPage)->take($perPage)->get();
        
        $logs = new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);

        return view('audit-log.index', compact('logs'));
    }

    public function show($id)
    {
        $log = Activity::with(['causer.personil', 'causer.role'])->findOrFail($id);

        return view('audit-log.show', compact('log'));
    }
}