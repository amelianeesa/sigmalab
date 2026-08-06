<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Personil;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$fieldType => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            if ($user->status_aktif == 1) {
                $request->session()->regenerate();
                return redirect()->intended('/dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['username' => 'Akun Anda non-aktif. Hubungi Admin.']);
            }
        }

        return back()->withErrors(['username' => 'Username/Email atau Password salah.']);
    }

    public function showRegister()
    {
        $roles = Role::all();
        $personil = Personil::all();
        return view('auth.register', compact('roles', 'personil'));
    }

    public function processRegister(Request $request)
    {
        $request->validate([
            'username'    => 'required|string|max:50|unique:users',
            'email'       => 'required|string|email|max:100|unique:users',
            'password'    => 'required|string|min:6',
            'role_id'     => 'required|exists:roles,roles_id',
            'personil_id' => 'nullable|exists:personil,personil_id', // Ditambahkan validasi aman
        ]);

        User::create([
            'personil_id' => !empty($request->personil_id) ? $request->personil_id : null,
            'username'    => $request->username,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role_id'     => $request->role_id,
            'status_aktif'=> 1
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}