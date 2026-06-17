<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\DaftarUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleSwitchController extends Controller
{
    /**
     * Switch ke role/akun lain yang ter-link
     */
    public function switch (Request $request, $targetUserId)
    {
        $currentUser = Auth::user();
        $targetUser = DaftarUser::findOrFail($targetUserId);

        // Validasi bahwa target user adalah linked account
        if (!$this->isValidLinkedAccount($currentUser, $targetUser)) {
            return redirect()->back()->with('error', 'Akun target tidak ter-link dengan akun Anda.');
        }

        // Simpan original_user_id yang sudah ada (jika sedang dalam switched state)
        $existingOriginalId = $request->session()->get('original_user_id');

        // Regenerate session to prevent fixation
        $request->session()->regenerate();

        // Set session untuk role switching
        $request->session()->put('role_switch_id', $targetUser->id);
        // Preserve original user ID jika sudah dalam switched state (nested switch)
        $request->session()->put('original_user_id', $existingOriginalId ?? $currentUser->id);

        // Log aktivitas
        ActivityLog::create([
            'user_name' => $currentUser->Nama,
            'action' => 'Switch Role',
            'description' => "Switch dari {$currentUser->Role_User} ke {$targetUser->Role_User}",
            'ip_address' => $request->ip(),
        ]);

        // Redirect ke dashboard sesuai role baru
        return $this->redirectToDashboard($targetUser);
    }

    /**
     * Kembali ke akun original
     */
    public function switchBack(Request $request)
    {
        $currentUser = Auth::user();
        $originalUserId = $request->session()->get('original_user_id');

        if (!$originalUserId) {
            return redirect()->back()->with('error', 'Tidak ada akun original untuk kembali.');
        }

        $originalUser = DaftarUser::find($originalUserId);

        if (!$originalUser) {
            $request->session()->forget(['role_switch_id', 'original_user_id']);

            return redirect()->route('login')->with('error', 'Akun original tidak ditemukan.');
        }

        // Log aktivitas
        ActivityLog::create([
            'user_name' => $currentUser->Nama,
            'action' => 'Switch Back',
            'description' => "Kembali dari {$currentUser->Role_User} ke {$originalUser->Role_User}",
            'ip_address' => $request->ip(),
        ]);

        // Hapus session switching
        $request->session()->forget(['role_switch_id', 'original_user_id']);

        // Regenerate session to prevent fixation
        $request->session()->regenerate();

        // Refresh halaman setelah kembali ke original
        return $this->redirectToDashboard($originalUser);
    }

    /**
     * Check apakah target user adalah linked account yang valid
     */
    private function isValidLinkedAccount($currentUser, $targetUser): bool
    {
        $allLinkedAccounts = $currentUser->getAllLinkedAccounts();

        return $allLinkedAccounts->contains('id', $targetUser->id);
    }

    /**
     * Redirect ke dashboard sesuai role
     */
    private function redirectToDashboard($user)
    {
        return match ($user->Role_User) {
                'Admin' => redirect()->route('admin.dashboard')->with('success', "Berhasil switch ke akun {$user->Role_User}"),
                'Dosen' => redirect()->route('dosen.dashboard')->with('success', "Berhasil switch ke akun {$user->Role_User}"),
                'Mahasiswa' => redirect()->route('mahasiswa.dashboard')->with('success', "Berhasil switch ke akun {$user->Role_User}"),
                'Laboran' => redirect()->route('laboran.dashboard')->with('success', "Berhasil switch ke akun {$user->Role_User}"),
                'Kaprodi' => redirect()->route('kaprodi.dashboard')->with('success', "Berhasil switch ke akun {$user->Role_User}"),
                'Safety Officer' => redirect()->route('safety-officer.dashboard')->with('success', "Berhasil switch ke akun {$user->Role_User}"),
                'Kepala Laboratorium' => redirect()->route('kepala-lab.dashboard')->with('success', "Berhasil switch ke akun {$user->Role_User}"),
                'Peneliti Eksternal' => redirect()->route('peneliti-eksternal.dashboard')->with('success', "Berhasil switch ke akun {$user->Role_User}"),
                default => redirect('/')->with('success', "Berhasil switch ke akun {$user->Role_User}"),
            };
    }

    /**
     * Tampilkan halaman manage linked accounts (untuk primary account)
     */
    public function manageLinkedAccounts()
    {
        $user = Auth::user();

        // Hanya primary account yang bisa manage
        if (!$user->isPrimaryAccount()) {
            return redirect()->back()->with('error', 'Hanya akun utama yang dapat mengelola linked accounts.');
        }

        $linkedAccounts = $user->getAllLinkedAccounts();

        return view('profile.linked-accounts', compact('linkedAccounts'));
    }

    /**
     * Link akun baru (hanya untuk admin atau primary account)
     */
    public function linkNewAccount(Request $request)
    {
        $request->validate([
            'target_email' => 'required|email|exists:daftar_users,Email',
            'current_password' => 'required|current_password',
        ]);

        $currentUser = Auth::user();
        $targetUser = DaftarUser::where('Email', $request->target_email)->first();

        // Validasi
        if (!$currentUser->isPrimaryAccount()) {
            return back()->with('error', 'Hanya akun utama yang dapat me-link akun lain.');
        }

        // Batasi linking: hanya Admin atau user dengan nomor_identitas yang sama
        if (!$currentUser->isAdmin() && $currentUser->nomor_identitas !== $targetUser->nomor_identitas) {
            return back()->with('error', 'Tidak diizinkan me-link akun dengan identitas berbeda.');
        }

        if ($targetUser->parent_user_id) {
            return back()->with('error', 'Akun target sudah ter-link dengan akun lain.');
        }

        if ($targetUser->id === $currentUser->id) {
            return back()->with('error', 'Tidak dapat me-link akun sendiri.');
        }

        // Link account
        try {
            $currentUser->linkAccount($targetUser);

            ActivityLog::create([
                'user_name' => $currentUser->Nama,
                'action' => 'Link Account',
                'description' => "Me-link akun {$targetUser->Nama} ({$targetUser->Role_User})",
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', "Berhasil me-link akun {$targetUser->Nama}");
        }
        catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Unlink akun
     */
    public function unlinkAccount(Request $request, $userId)
    {
        $currentUser = Auth::user();
        $targetUser = DaftarUser::findOrFail($userId);

        // Validasi hanya primary account yang bisa unlink
        if (!$currentUser->isPrimaryAccount()) {
            return back()->with('error', 'Hanya akun utama yang dapat unlink akun.');
        }

        // Validasi target adalah child dari current user
        if ($targetUser->parent_user_id !== $currentUser->id) {
            return back()->with('error', 'Akun ini tidak ter-link dengan akun Anda.');
        }

        try {
            $targetUser->unlinkAccount();

            ActivityLog::create([
                'user_name' => $currentUser->Nama,
                'action' => 'Unlink Account',
                'description' => "Unlink akun {$targetUser->Nama} ({$targetUser->Role_User})",
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', "Berhasil unlink akun {$targetUser->Nama}");
        }
        catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
