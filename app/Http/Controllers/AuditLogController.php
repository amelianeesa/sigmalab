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

        $logs = $query->latest()->paginate(20);

        return view('audit-log.index', compact('logs'));
    }

    public function show($id)
    {
        $log = Activity::with(['causer.personil', 'causer.role'])->findOrFail($id);

        return view('audit-log.show', compact('log'));
    }
}
