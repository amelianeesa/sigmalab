<?php

namespace App\Http\Controllers;

use App\Models\Personil;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KelolaUserController extends Controller
{ 
    public function index(Request $request)
    {
        $cari = trim((string) $request->input('cari'));
        $roleId = $request->input('role_id');
        $status = $request->input('status');

        $users = User::with(['personil', 'role'])
            ->when($cari, function ($query) use ($cari) {
                $query->where(function ($sub) use ($cari) {
                    $sub->where('username', 'like', '%' . $cari . '%')
                        ->orWhere('email', 'like', '%' . $cari . '%')
                        ->orWhereHas('personil', fn ($q) => $q->where('nama', 'like', '%' . $cari . '%'));
                });
            })
            ->when($roleId, fn ($query) => $query->where('role_id', $roleId))
            ->when($status === 'aktif', fn ($query) => $query->where('status_aktif', true))
            ->when($status === 'nonaktif', fn ($query) => $query->where('status_aktif', false))
            ->orderBy('username')
            ->paginate(10)
            ->withQueryString();

        $roles = Role::orderBy('nama_role')->get();

        $personilTanpaAkun = Personil::whereDoesntHave('user')
            ->where('status_aktif', true)
            ->orderBy('nama')
            ->get();

        return view('kelola-user.index', compact('users', 'roles', 'personilTanpaAkun', 'cari', 'roleId', 'status'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'personil_id' => 'nullable|exists:personil,personil_id|unique:users,personil_id',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,roles_id',
        ]);

        User::create([
            'personil_id' => $data['personil_id'] ?? null,
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
            'status_aktif' => true,
        ]);

        return redirect()->route('kelola-user.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'personil_id' => 'nullable|exists:personil,personil_id|unique:users,personil_id,' . $user->users_id . ',users_id',
            'username' => 'required|string|max:50|unique:users,username,' . $user->users_id . ',users_id',
            'email' => 'required|email|max:100|unique:users,email,' . $user->users_id . ',users_id',
            'password' => 'nullable|string|min:6',
            'role_id' => 'required|exists:roles,roles_id',
        ]);

        $user->personil_id = $data['personil_id'] ?? null;
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->role_id = $data['role_id'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('kelola-user.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        abort_if($user->users_id === auth()->id(), 403, 'Anda tidak dapat menghapus akun Anda sendiri.');

        $user->forceDelete();

        return redirect()->route('kelola-user.index')->with('success', 'Akun berhasil dihapus permanen.');
    }
}
