<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if ($request->has('redirect')) {
            session(['url.intended' => $request->query('redirect')]);
        }
        
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
            if ($user->status_aktif == 1 && (!$user->personil || $user->personil->status_aktif == 1)) {
                $request->session()->regenerate();
                
                activity('auth')
                    ->causedBy($user)
                    ->event('login')
                    ->log('User logged in to the system');

                return redirect()->intended('/dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['username' => 'Akun atau Personil Anda non-aktif. Hubungi Admin.']);
            }
        }

        return back()->withErrors(['username' => 'Username/Email atau Password salah.']);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            activity('auth')
                ->causedBy(Auth::user())
                ->event('logout')
                ->log('User logged out of the system');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}