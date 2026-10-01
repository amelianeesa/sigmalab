<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function klikNotifikasi($id)
    {
        $notif = Notifikasi::query()
            ->where('notifikasi_id', $id)
            ->where('users_id', Auth::id())
            ->firstOrFail();
        
        if (!$notif->is_read) {
            $notif->update(['is_read' => true]);
        }

        if (!empty($notif->url)) {
            return redirect($notif->url);
        }

        $pesan = strtolower($notif->pesan);

        $tujuan = match ($notif->jenis_notifikasi) {
            'stok' => str_contains($pesan, 'pengajuan') || str_contains($pesan, 'pengadaan')
                ? route('pengadaan.index')
                : route('barang.index'),
            'qc' => route('verifikasi-mutu.index'),
            'kalibrasi', 'perbaikan' => route('alat.index'),
            'sertifikasi' => route('sdm.index'),
            default => route('notifikasi.index'),
        };

        return redirect($tujuan);
    }
}