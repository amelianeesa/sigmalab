<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['causer.personil', 'causer.role']);

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('subject_type')) {
            $query->where('subject_type', 'like', '%' . $request->subject_type . '%');
        }

        $subquery = Activity::selectRaw('MAX(id) as id')
            ->groupByRaw('COALESCE(batch_uuid, CAST(id AS CHAR))');
            
        $query->whereIn('id', $subquery);

        $logs = $query->latest()->paginate(20);

        return view('audit-log.index', compact('logs'));
    }

    public function show($id)
    {
        $log = Activity::with(['causer.personil', 'causer.role'])->findOrFail($id);
        
        $batchLogs = collect([$log]);
        if ($log->batch_uuid) {
            $batchLogs = Activity::with(['causer.personil', 'causer.role'])
                ->where('batch_uuid', $log->batch_uuid)
                ->orderBy('id', 'asc')
                ->get();
        }

        return view('audit-log.show', compact('log', 'batchLogs'));
    }
}
