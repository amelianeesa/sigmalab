<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

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

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('properties', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(15)->onEachSide(1);
        $logs->appends($request->query());

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
}