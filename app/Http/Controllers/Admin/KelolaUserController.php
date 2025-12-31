<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\DaftarUser;
use Hash;
use Illuminate\Http\Request;

class KelolaUserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = DaftarUser::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('Role_User', $request->role);
        }

        // Search by name, email, or UserID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Nama', 'like', "%{$search}%")
                  ->orWhere('Email', 'like', "%{$search}%")
                  ->orWhere('UserID', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.kelola-user.index', compact('users'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = DaftarUser::findOrFail($id);
        return view('admin.kelola-user.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = DaftarUser::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:daftar_users,Email,' . $id,
            'Phone' => 'required|string|max:20',
            'role' => 'required|in:Admin,Dosen,Tendik,Mahasiswa,Safety Officer,Kepala Laboratorium,Laboran'
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'Phone.required' => 'Nomor telepon wajib diisi',
            'role.required' => 'Role wajib dipilih'
        ]);

        try {
            $oldData = $user->toArray();
            
            $user->update([
                'Nama' => $request->nama,
                'Email' => $request->email,
                'Phone' => $request->Phone,
                'Role_User' => $request->role
            ]);

            // Log aktivitas
            ActivityLog::create([
                'user_name'    => auth()->user()->Nama ?? 'Administrator',
                'action'       => 'Update Data User',
                'description'  => "Update user {$user->Nama} (UserID: {$user->UserID})",
                'ip_address'   => request()->ip(),
            ]);

            return redirect()->route('admin.kelola-user.index')
                ->with('success', 'Data user berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show form for resetting password
     */
    public function showResetPassword($id)
    {
        $user = DaftarUser::findOrFail($id);
        return view('admin.kelola-user.reset-password', compact('user'));
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, $id)
    {
        $user = DaftarUser::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            $user->update([
                'Password' => Hash::make($request->password)
            ]);

            // Log aktivitas
            ActivityLog::create([
                'user_name'    => auth()->user()->Nama ?? 'Administrator',
                'action'       => 'Reset Password User',
                'description'  => "Reset password user {$user->Nama} (UserID: {$user->UserID})",
                'ip_address'   => request()->ip(),
            ]);

            return redirect()->route('admin.kelola-user.index')
                ->with('success', 'Password user berhasil direset!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mereset password: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        try {
            $user = DaftarUser::findOrFail($id);
            $userName = $user->Nama;
            $userID = $user->UserID;

            // Prevent deleting own account
            if (auth()->check() && auth()->user()->id == $id) {
                return redirect()->back()
                    ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
            }

            $user->delete();

            // Log aktivitas
            ActivityLog::create([
                'user_name'    => auth()->user()->Nama ?? 'Administrator',
                'action'       => 'Hapus User',
                'description'  => "Menghapus user {$userName} (UserID: {$userID})",
                'ip_address'   => request()->ip(),
            ]);

            return redirect()->route('admin.kelola-user.index')
                ->with('success', 'User berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus($id)
    {
        try {
            $user = DaftarUser::findOrFail($id);
            
            // Assuming you have a 'status' column in your database
            // If not, you'll need to add it via migration
            $newStatus = $user->status == 'active' ? 'inactive' : 'active';
            $user->update(['status' => $newStatus]);

            // Log aktivitas
            ActivityLog::create([
                'user_name'    => auth()->user()->Nama ?? 'Administrator',
                'action'       => 'Toggle Status User',
                'description'  => "Mengubah status user {$user->Nama} menjadi {$newStatus}",
                'ip_address'   => request()->ip(),
            ]);

            return redirect()->back()
                ->with('success', "Status user berhasil diubah menjadi {$newStatus}!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah status user: ' . $e->getMessage());
        }
    }
}