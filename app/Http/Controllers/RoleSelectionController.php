<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleSelectionController extends Controller
{
    /**
     * Ganti primary role untuk akun yang sama (multi-role dalam satu user).
     */
    public function switch (Request $request)
    {
        $request->validate([
            'role' => ['required', 'string'],
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $selectedRole = $request->input('role');

        // Pastikan role yang diminta memang dimiliki user
        if (!$user->hasRole($selectedRole)) {
            return back()->with('error', 'Anda tidak memiliki role tersebut.');
        }

        // Ambil semua role names milik user
        $allRoles = $user->roleNames;

        // Set primary role ke role yang dipilih
        $user->syncRoles($allRoles, $selectedRole);

        // Redirect ke dashboard sesuai role yang dipilih
        return match ($selectedRole) {
                'Admin' => redirect()->route('admin.dashboard')->with('success', "Berhasil mengganti role aktif ke {$selectedRole}."),
                'Dosen' => redirect()->route('dosen.dashboard')->with('success', "Berhasil mengganti role aktif ke {$selectedRole}."),
                'Mahasiswa' => redirect()->route('mahasiswa.dashboard')->with('success', "Berhasil mengganti role aktif ke {$selectedRole}."),
                'Laboran' => redirect()->route('laboran.dashboard')->with('success', "Berhasil mengganti role aktif ke {$selectedRole}."),
                'Kaprodi' => redirect()->route('kaprodi.dashboard')->with('success', "Berhasil mengganti role aktif ke {$selectedRole}."),
                'Safety Officer' => redirect()->route('safety-officer.dashboard')->with('success', "Berhasil mengganti role aktif ke {$selectedRole}."),
                'Kepala Laboratorium' => redirect()->route('kepala-lab.dashboard')->with('success', "Berhasil mengganti role aktif ke {$selectedRole}."),
                'Peneliti Eksternal' => redirect()->route('peneliti-eksternal.dashboard')->with('success', "Berhasil mengganti role aktif ke {$selectedRole}."),
                default => redirect('/')->with('success', "Berhasil mengganti role aktif ke {$selectedRole}."),
            };
    }
}
