<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditlogArchive;

class AuditLogArchiveController extends Controller
{
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
}