<?php

namespace App\Http\Controllers;

use App\Support\Workspace;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function switch(Request $request)
    {
        $data = $request->validate(['workspace' => 'required|in:LAB,NON_LAB']);
        $user = $request->user();

        // Hanya user ber-workspace ALL yang boleh pindah.
        abort_unless(($user->workspace ?? Workspace::LAB) === Workspace::ALL, 403, 'Akun Anda tidak dapat berpindah workspace.');

        session(['workspace' => $data['workspace']]);

        activity('auth')->causedBy($user)->event('switch-workspace')
            ->log('Pindah workspace ke ' . Workspace::label($data['workspace']));

        return redirect()->route('dashboard')->with('success', 'Workspace diubah ke ' . Workspace::label($data['workspace']) . '.');
    }
}
