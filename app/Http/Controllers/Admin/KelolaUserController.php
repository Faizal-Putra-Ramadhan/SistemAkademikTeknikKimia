<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\DaftarLaboranLaboratorium;
use App\Models\DaftarUser;
use App\Models\Role;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class KelolaUserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = DaftarUser::query()->with('roles');

        // Filter by role (mendukung multi-role: cek Role_User atau tabel user_roles)
        if ($request->filled('role')) {
            $roleFilter = $request->role;
            $query->where(function ($q) use ($roleFilter) {
                $q->where('Role_User', $roleFilter)
                    ->orWhereHas('roles', fn ($r) => $r->where('name', $roleFilter));
            });
        }

        // Search by name, email, UserID, or nomor_identitas (UPDATED)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('Nama', 'like', "%{$search}%")
                    ->orWhere('Email', 'like', "%{$search}%")
                    ->orWhere('UserID', 'like', "%{$search}%")
                    ->orWhere('nomor_identitas', 'like', "%{$search}%"); // BARU: pencarian berdasarkan NIM/NIY
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
        $user = DaftarUser::with('roles')->findOrFail($id);
        $roles = Role::all();

        return view('admin.kelola-user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        // Defensive: Validate ID format
        if (! is_numeric($id) || $id <= 0) {
            Log::warning('Invalid user ID in update attempt', [
                'id' => $id,
                'ip' => $request->ip(),
                'user' => auth()->user()->UserID ?? 'unknown',
            ]);

            return redirect()->route('admin.kelola-user.index')
                ->with('error', 'ID user tidak valid.');
        }

        // Defensive: Find user with null check
        $user = DaftarUser::find($id);
        if (! $user) {
            Log::warning('User not found in update attempt', [
                'id' => $id,
                'ip' => $request->ip(),
            ]);

            return redirect()->route('admin.kelola-user.index')
                ->with('error', 'User tidak ditemukan.');
        }

        // Defensive: Enhanced validation with regex and security checks
        try {
            $validated = $request->validate([
                'nama' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-Z\s\'.,-]+$/u', // Only allow letters, spaces, and common name characters
                ],
                'email' => [
                    'required',
                    'email:rfc,dns',
                    'max:255',
                    'unique:daftar_users,Email,'.$id,
                ],
                'Phone' => [
                    'required',
                    'string',
                    'max:20',
                    'regex:/^[\d\s\+\-\(\)]+$/', // Only allow digits and phone characters
                ],
                'roles' => [
                    'required',
                    'array',
                ],
                'roles.*' => [
                    'required',
                    'exists:roles,name',
                ],
                'primary_role' => [
                    'nullable',
                    'exists:roles,name',
                ],
                'nomor_identitas' => [
                    'nullable',
                    'string',
                    'max:50',
                    'regex:/^[a-zA-Z0-9\-\.]+$/', // Alphanumeric with dash and dot only
                ],
            ], [
                'nama.required' => 'Nama wajib diisi',
                'nama.regex' => 'Nama hanya boleh mengandung huruf, spasi, dan tanda baca umum',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'Phone.required' => 'Nomor telepon wajib diisi',
                'Phone.regex' => 'Format nomor telepon tidak valid',
                'roles.required' => 'Minimal satu role wajib dipilih',
                'roles.array' => 'Roles harus berupa array',
                'roles.*.exists' => 'Role tidak valid',
                'primary_role.exists' => 'Primary role tidak valid',
                'nomor_identitas.max' => 'Nomor identitas maksimal 50 karakter',
                'nomor_identitas.regex' => 'Nomor identitas hanya boleh berisi huruf, angka, titik, dan tanda hubung',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }

        // Defensive: Sanitize input
        $sanitizedNama = trim(strip_tags($validated['nama']));
        $sanitizedEmail = strtolower(trim(strip_tags($validated['email'])));
        $sanitizedPhone = trim(strip_tags($validated['Phone']));
        $sanitizedNomorIdentitas = ! empty($validated['nomor_identitas'])
            ? trim(strip_tags($validated['nomor_identitas']))
            : null;

        // Get primary role (first role if not specified)
        $primaryRole = $validated['primary_role'] ?? $validated['roles'][0] ?? null;

        // Defensive: Check for actual changes to avoid unnecessary DB operations
        $user->load('roles'); // Reload roles relationship
        $currentRoles = $user->roleNames;
        $rolesChanged = array_diff($currentRoles, $validated['roles']) || array_diff($validated['roles'], $currentRoles);

        $hasChanges = $user->Nama !== $sanitizedNama
            || $user->Email !== $sanitizedEmail
            || $user->Phone !== $sanitizedPhone
            || $user->Role_User !== $primaryRole
            || $user->nomor_identitas !== $sanitizedNomorIdentitas
            || $rolesChanged;

        if (! $hasChanges) {
            return redirect()->route('admin.kelola-user.index')
                ->with('info', 'Tidak ada perubahan data.');
        }

        DB::beginTransaction();
        try {
            $oldData = $user->toArray();

            // Defensive: Update with sanitized data
            $user->update([
                'Nama' => $sanitizedNama,
                'Email' => $sanitizedEmail,
                'Phone' => $sanitizedPhone,
                'Role_User' => $primaryRole, // Keep for backward compatibility
                'nomor_identitas' => $sanitizedNomorIdentitas,
            ]);

            // Sync roles
            $user->syncRoles($validated['roles'], $primaryRole);

            // Reload user to get updated roles
            $user->refresh();
            $user->load('roles');

            // Defensive: Build detailed log description
            $changes = [];
            if ($oldData['Nama'] !== $sanitizedNama) {
                $changes[] = "Nama: {$oldData['Nama']} → {$sanitizedNama}";
            }
            if ($oldData['Email'] !== $sanitizedEmail) {
                $changes[] = "Email: {$oldData['Email']} → {$sanitizedEmail}";
            }

            // Log role changes
            $oldRoles = $currentRoles;
            $newRoles = $validated['roles'];
            if ($rolesChanged) {
                $oldRolesStr = implode(', ', $oldRoles);
                $newRolesStr = implode(', ', $newRoles);
                $changes[] = "Roles: {$oldRolesStr} → {$newRolesStr}";
            }

            if ($oldData['nomor_identitas'] !== $sanitizedNomorIdentitas) {
                $identitasLabel = in_array('Mahasiswa', $validated['roles']) ? 'NIM' : 'NIY';
                $oldIdentitas = $oldData['nomor_identitas'] ?? 'kosong';
                $newIdentitas = $sanitizedNomorIdentitas ?? 'kosong';
                $changes[] = "{$identitasLabel}: {$oldIdentitas} → {$newIdentitas}";
            }

            $logDescription = "Update user {$sanitizedNama} (UserID: {$user->UserID}). Perubahan: ".implode(', ', $changes);

            // Defensive: Null-safe activity logging
            $logUserName = auth()->check() && auth()->user()
                ? auth()->user()->Nama
                : 'Administrator';

            ActivityLog::create([
                'user_name' => $logUserName,
                'action' => 'Update Data User',
                'description' => $logDescription,
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            Log::info('User updated successfully', [
                'user_id' => $id,
                'updated_by' => $logUserName,
                'changes' => $changes,
            ]);

            return redirect()->route('admin.kelola-user.index')
                ->with('success', 'Data user berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update user', [
                'user_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Gagal memperbarui data user. Silakan coba lagi.')
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
        // Defensive: Validate ID format
        if (! is_numeric($id) || $id <= 0) {
            Log::warning('Invalid user ID in password reset attempt', [
                'id' => $id,
                'ip' => $request->ip(),
            ]);

            return redirect()->route('admin.kelola-user.index')
                ->with('error', 'ID user tidak valid.');
        }

        // Defensive: Find user with null check
        $user = DaftarUser::find($id);
        if (! $user) {
            Log::warning('User not found in password reset attempt', [
                'id' => $id,
                'ip' => $request->ip(),
            ]);

            return redirect()->route('admin.kelola-user.index')
                ->with('error', 'User tidak ditemukan.');
        }

        // Defensive: Prevent resetting own password via this method (should use profile)
        if (auth()->check() && auth()->user()->id == $id) {
            Log::warning('Admin attempted to reset own password via admin panel', [
                'user_id' => $id,
                'ip' => $request->ip(),
            ]);

            return redirect()->back()
                ->with('error', 'Gunakan menu profil untuk mengubah password Anda sendiri.');
        }

        // Defensive: Enhanced password validation
        try {
            $validated = $request->validate([
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&].+$/',
                ],
            ], [
                'password.required' => 'Password wajib diisi',
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
                'password.regex' => 'Password harus mengandung: 1 huruf kecil dan 1 angka',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator);
        }

        // Defensive: Check for common passwords
        $commonPasswords = [
            'password', '12345678', 'qwerty123', 'abc12345', 'Password1!',
            'Welcome1!', 'Admin123!', 'Letmein1!', 'Password123!',
        ];
        if (in_array($validated['password'], $commonPasswords)) {
            return redirect()->back()
                ->withErrors(['password' => 'Password terlalu umum. Pilih password yang lebih kuat.']);
        }

        DB::beginTransaction();
        try {
            // Defensive: Hash password securely
            $hashedPassword = Hash::make($validated['password']);

            $user->update([
                'Password' => $hashedPassword,
            ]);

            // Defensive: Null-safe activity logging
            $logUserName = auth()->check() && auth()->user()
                ? auth()->user()->Nama
                : 'Administrator';

            ActivityLog::create([
                'user_name' => $logUserName,
                'action' => 'Reset Password User',
                'description' => "Reset password user {$user->Nama} (UserID: {$user->UserID})",
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            Log::info('User password reset successfully', [
                'user_id' => $id,
                'reset_by' => $logUserName,
            ]);

            return redirect()->route('admin.kelola-user.index')
                ->with('success', 'Password user berhasil direset!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to reset user password', [
                'user_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mereset password. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        // Defensive: Validate ID format
        if (! is_numeric($id) || $id <= 0) {
            Log::warning('Invalid user ID in delete attempt', [
                'id' => $id,
                'ip' => request()->ip(),
            ]);

            return redirect()->route('admin.kelola-user.index')
                ->with('error', 'ID user tidak valid.');
        }

        // Defensive: Find user with null check
        $user = DaftarUser::find($id);
        if (! $user) {
            Log::warning('User not found in delete attempt', [
                'id' => $id,
                'ip' => request()->ip(),
            ]);

            return redirect()->route('admin.kelola-user.index')
                ->with('error', 'User tidak ditemukan.');
        }

        // Defensive: Prevent deleting own account
        if (auth()->check() && auth()->user()->id == $id) {
            Log::warning('Admin attempted to delete own account', [
                'user_id' => $id,
                'ip' => request()->ip(),
            ]);

            return redirect()->back()
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        // Defensive: Prevent deleting super admin or critical accounts
        if ($user->Role_User === 'Admin' && DaftarUser::where('Role_User', 'Admin')->count() <= 1) {
            Log::warning('Attempted to delete last admin account', [
                'user_id' => $id,
                'ip' => request()->ip(),
            ]);

            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus satu-satunya akun Admin!');
        }

        DB::beginTransaction();
        try {
            $userName = $user->Nama;
            $userID = $user->UserID;
            $userRole = $user->Role_User;

            // Defensive: Check for related data before deletion
            // You might want to add cascade delete or prevent deletion if user has data
            $relatedDataCount = 0;

            // Example checks (adjust based on your actual relationships):
            if (method_exists($user, 'peminjamanAlat')) {
                $relatedDataCount += $user->peminjamanAlat()->count();
            }
            if (method_exists($user, 'peminjamanRuangan')) {
                $relatedDataCount += $user->peminjamanRuangan()->count();
            }

            if ($relatedDataCount > 0) {
                Log::warning('Attempted to delete user with related data', [
                    'user_id' => $id,
                    'related_count' => $relatedDataCount,
                ]);

                return redirect()->back()
                    ->with('error', "User memiliki {$relatedDataCount} data terkait. Silakan hapus data terkait terlebih dahulu.");
            }

            // Cascade: hapus juga dari daftar_laboran_laboratoriums jika ada
            DaftarLaboranLaboratorium::where('UserID', $userID)->delete();

            $user->delete();

            // Defensive: Null-safe activity logging
            $logUserName = auth()->check() && auth()->user()
                ? auth()->user()->Nama
                : 'Administrator';

            ActivityLog::create([
                'user_name' => $logUserName,
                'action' => 'Hapus User',
                'description' => "Menghapus user {$userName} (UserID: {$userID}, Role: {$userRole})",
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            Log::info('User deleted successfully', [
                'deleted_user_id' => $userID,
                'deleted_user_name' => $userName,
                'deleted_by' => $logUserName,
            ]);

            return redirect()->route('admin.kelola-user.index')
                ->with('success', 'User berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete user', [
                'user_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menghapus user. Silakan coba lagi.');
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
                'user_name' => auth()->user()->Nama ?? 'Administrator',
                'action' => 'Toggle Status User',
                'description' => "Mengubah status user {$user->Nama} menjadi {$newStatus}",
                'ip_address' => request()->ip(),
            ]);

            return redirect()->back()
                ->with('success', "Status user berhasil diubah menjadi {$newStatus}!");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah status user: '.$e->getMessage());
        }
    }
}
