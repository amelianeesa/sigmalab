<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::query()
            ->where('users_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('notifikasi.index', compact('notifikasis'));
    }

    public function markAsRead($id)
    {
        Notifikasi::query()
            ->where('notifikasi_id', $id)
            ->where('users_id', Auth::id())
            ->update(['is_read' => true]);
            
        return back()->with('success', 'Notifikasi telah ditandai sudah dibaca.');
    }

    public function markAllAsRead()
    {
        Notifikasi::query()
            ->where('users_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }

    public function getUnreadCount()
    {
        $count = Notifikasi::query()
            ->where('users_id', Auth::id())
            ->where('is_read', false)
            ->count();
            
        return response()->json(['count' => $count]);
    }
}
