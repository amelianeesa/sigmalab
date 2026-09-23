<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alat;
use App\Models\RiwayatPerbaikanAlat;
use App\Models\User;
use App\Mail\LaporanKerusakanMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PerbaikanAlatController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'tanggal_rusak' => 'required|date',
            'deskripsi_kerusakan' => 'required|string',
        ]);

        $alat = Alat::findOrFail($id);

        $perbaikan = RiwayatPerbaikanAlat::create([
            'alat_id' => $alat->alat_id,
            'tanggal_rusak' => $request->tanggal_rusak,
            'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
            'dilaporkan_oleh' => Auth::id(),
            'status_perbaikan' => 'Belum Diperbaiki'
        ]);

        $alat->update([
            'kondisi_barang' => 'perbaikan',
            'status_barang' => 'idle'
        ]);

        $namaAlat = $alat->nama_alat;
        $kodeAlat = $alat->kode_alat ?? '-';
        $pesanNotif = "Laporan Kerusakan Alat: Alat \"{$namaAlat}\" ({$kodeAlat}) dilaporkan rusak. Kendala: \"{$request->deskripsi_kerusakan}\".";

        $gaUsers = User::whereHas('role', function($q) {
            $q->where('nama_role', 'GA');
        })->get();

        $gaEmails = $gaUsers->pluck('email')->filter()->toArray();

        $kabidUsers = User::whereHas('role', function($q) {
            $q->whereIn('nama_role', [
                'Koordinator Laboratorium',
                'Kabid Inspeksi dan Solusi Perdagangan', 
                'Kabid Dukungan Bisnis'
            ]);
        })->get();

        $kabidEmails = $kabidUsers->pluck('email')->filter()->toArray();

        if (!empty($gaEmails)) {
            $primaryGaEmail = array_shift($gaEmails); 
            $allCcEmails = array_merge($gaEmails, $kabidEmails);

            try {
                if (!empty($allCcEmails)) {
                    Mail::to($primaryGaEmail)->cc($allCcEmails)->send(new LaporanKerusakanMail($perbaikan));
                } else {
                    Mail::to($primaryGaEmail)->send(new LaporanKerusakanMail($perbaikan));
                }
            } catch (\Exception $e) {
                
            }
        }

        $allTargetUsers = $gaUsers->merge($kabidUsers);

        foreach ($allTargetUsers as $targetUser) {
            $roleName = $targetUser->role->nama_role ?? '';
            $prefix = ($roleName === 'GA') ? '[TO] ' : '[CC] ';

            DB::table('notifikasi')->insert([
                'users_id' => $targetUser->users_id,
                'jenis_notifikasi' => 'qc',
                'pesan' => $prefix . $pesanNotif,
                'is_read' => 0,
                'created_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Laporan kerusakan berhasil dicatat & notifikasi email/web telah diteruskan ke GA dan Kabid terkait.');
    }

    public function update(Request $request, $id, $perbaikan_id)
    {
        $alat = Alat::findOrFail($id);
        
        $request->validate([
            'status_perbaikan' => 'required|string|in:Belum Diperbaiki,Dalam Perbaikan,Selesai,Tidak Bisa Diperbaiki',
            'tindakan_perbaikan' => 'nullable|string',
            'tanggal_selesai' => 'nullable|date',
        ]);

        $tanggalSelesaiBaru = $request->tanggal_selesai;

        if ($request->status_perbaikan === 'Selesai' || $request->status_perbaikan === 'Tidak Bisa Diperbaiki') {
            $roleNama = Auth::user()->role->nama_role ?? '';
            if ($roleNama !== 'Koordinator Laboratorium' && $roleNama !== \App\Enums\PeranPengguna::KOORDINATOR_LAB->value) {
                return redirect()->back()->withErrors(['message' => 'Hanya Koordinator Lab yang dapat memverifikasi penyelesaian perbaikan.']);
            }
            
            if (!$tanggalSelesaiBaru) {
                $tanggalSelesaiBaru = now();
            }
            
            if ($request->status_perbaikan === 'Selesai') {
                $alat->update([
                    'kondisi_barang' => 'baik',
                    'status_barang' => 'idle' 
                ]);
            } elseif ($request->status_perbaikan === 'Tidak Bisa Diperbaiki') {
                $alat->update([
                    'kondisi_barang' => 'rusak',
                    'status_barang' => 'idle'
                ]);
            }
        } elseif ($request->status_perbaikan === 'Dalam Perbaikan') {
            $alat->update([
                'kondisi_barang' => 'perbaikan',
                'status_barang' => 'idle'
            ]);
        }

        DB::table('riwayat_perbaikan_alat')
            ->where('riwayat_perbaikan_id', $perbaikan_id)
            ->update([
                'status_perbaikan' => $request->status_perbaikan,
                'tindakan_perbaikan' => $request->tindakan_perbaikan,
                'tanggal_selesai' => $tanggalSelesaiBaru,
                'diverifikasi_oleh' => in_array($request->status_perbaikan, ['Selesai', 'Tidak Bisa Diperbaiki']) ? Auth::id() : null,
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Status perbaikan berhasil diperbarui.');
    }
}