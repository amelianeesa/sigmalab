<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('personil', 'role');

        return view('profil.index', compact('user'));
    }

    public function updateEmail(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email' => [
                'required',
                'email:rfc',
                'max:255',
                Rule::unique($user->getTable(), 'email')->ignore($user->getKey(), $user->getKeyName()),
            ],
            'password_konfirmasi' => 'required|string',
        ], [
            'email.required' => 'Email baru wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email tersebut sudah digunakan akun lain.',
            'password_konfirmasi.required' => 'Password saat ini wajib diisi untuk konfirmasi.',
        ]);

        if (!Hash::check($request->password_konfirmasi, $user->password)) {
            return back()
                ->withErrors(['password_konfirmasi' => 'Password saat ini tidak sesuai.'])
                ->withInput($request->only('email'));
        }

        $emailBaru = strtolower(trim($request->email));
        $emailLama = $user->email;

        if ($emailBaru === strtolower($emailLama)) {
            return back()
                ->withErrors(['email' => 'Email baru sama dengan email saat ini.'])
                ->withInput($request->only('email'));
        }

        $user->update(['email' => $emailBaru]);

        activity()
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties(['email_lama' => $emailLama, 'email_baru' => $emailBaru])
            ->log('Mengubah email akun');

        return redirect()->route('profil.index')->with('success', 'Email berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password_baru),
            'must_change_password' => false,
        ]);

        return redirect()->route('profil.index')->with('success', 'Password berhasil diperbarui.');
    }
}