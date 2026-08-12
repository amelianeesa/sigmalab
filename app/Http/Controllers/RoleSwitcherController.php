<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RoleSwitcherController extends Controller
{
    public function switchRole(Request $request)
    {
        $roleName = $request->input('role_name');

        if (!$roleName) {
            Auth::logout();
            return back()->with('success', 'Logged out.');
        }

        $user = User::whereHas('role', function ($query) use ($roleName) {
            $query->where('nama_role', $roleName);
        })->first();

        if ($user) {
            Auth::login($user);
            return back()->with('success', "Role diubah menjadi: {$roleName}");
        }

        return back()->with('error', "User dengan role {$roleName} tidak ditemukan. Pastikan DummyDataSeeder sudah dijalankan.");
    }
}
