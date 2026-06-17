<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AccountCredentialsMail;
use App\Models\ActivityLog;
use App\Models\DaftarUser;
use App\Models\Role;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TambahUserController extends Controller
{
    public function index()
    {
        // Ambil semua user yang bisa dijadikan parent (primary accounts)
        $potentialParents = DaftarUser::where('is_primary', true)
            ->orWhereNull('parent_user_id')
            ->orderBy('Nama')
            ->get();

        $roles = Role::all();

        return view('admin.tambah-user.index', compact('potentialParents', 'roles'));
    }

    public function store(Request $request)
    {
        // DEFENSIVE: Validasi dengan aturan ketat
        $request->validate([
            'nama' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z\s\.\,\-\']+$/u', // Hanya huruf, spasi, dan karakter nama umum
            ],
            'email' => [
                'required',
                'email:rfc,dns', // Validate DNS record
                'unique:daftar_users,Email',
                'max:255',
            ],
            'Phone' => [
                'required',
                'string',
                'regex:/^[0-9\+\-\(\)\s]{8,20}$/', // Format nomor telepon
                'min:8',
                'max:20',
            ],
            'password' => [
                'required',
                'string',
                'min:8', // Minimal 8 karakter
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*\d).+$/', // Harus ada huruf kecil dan angka
            ],
            'roles' => [
                'required',
                'array',
                'min:1',
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
                'regex:/^[a-zA-Z0-9\-]+$/', // Hanya alphanumeric dan dash
            ],
            'link_to_parent' => 'nullable|exists:daftar_users,id',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'nama.min' => 'Nama minimal 3 karakter',
            'nama.regex' => 'Nama hanya boleh mengandung huruf dan karakter nama yang valid',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar atau menunggu verifikasi',
            'Phone.required' => 'Nomor telepon wajib diisi',
            'Phone.regex' => 'Format nomor telepon tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.regex' => 'Password harus mengandung: 1 huruf kecil dan 1 angka',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'roles.required' => 'Minimal satu role wajib dipilih',
            'roles.array' => 'Roles harus berupa array',
            'roles.min' => 'Minimal satu role wajib dipilih',
            'roles.*.exists' => 'Role tidak valid',
            'primary_role.exists' => 'Primary role tidak valid',
            'nomor_identitas.max' => 'Nomor identitas maksimal 50 karakter',
            'nomor_identitas.regex' => 'Nomor identitas hanya boleh mengandung huruf, angka, dan dash',
            'link_to_parent.exists' => 'Parent account tidak valid',
        ]);

        // DEFENSIVE: Gunakan database transaction
        DB::beginTransaction();

        try {
            // DEFENSIVE: Sanitize input
            $sanitizedNama = strip_tags(trim($request->nama));
            $sanitizedEmail = strtolower(trim($request->email));
            $sanitizedPhone = preg_replace('/[^0-9\+\-\(\)\s]/', '', $request->Phone);
            $sanitizedNomorIdentitas = $request->nomor_identitas ? strip_tags(trim($request->nomor_identitas)) : null;
            $plainPassword = $request->password; // Simpan password plain untuk email (hanya untuk email)

            // Tentukan apakah ini primary account atau child account
            $isPrimary = ! $request->filled('link_to_parent');
            $parentUserId = $request->link_to_parent;

            // Get primary role (first role if not specified)
            $primaryRole = $request->primary_role ?? $request->roles[0];

            // Generate User ID based on primary role
            $userId = $this->generateUserId($primaryRole);

            // Langsung buat user di daftar_users
            $newUser = DaftarUser::create([
                'UserID' => $userId,
                'Nama' => $sanitizedNama,
                'Email' => $sanitizedEmail,
                'Phone' => $sanitizedPhone,
                'Password' => Hash::make($plainPassword), // Hash password
                'Role_User' => $primaryRole, // Set primary role for backward compatibility
                'Nomor_Identitas' => $sanitizedNomorIdentitas,
                'is_primary' => $isPrimary,
                'parent_user_id' => $parentUserId,
                'status' => 'aktif', // Status langsung aktif
            ]);

            // Sync roles to user_roles table
            $newUser->syncRoles($request->roles, $primaryRole);

            // Jika salah satu role adalah Laboran, otomatis buat entry di daftar_laboran_laboratoriums
            $laboranRoles = ['Laboran', 'Koordinator Laboran', 'Asisten Laboran'];
            $selectedLaboranRole = collect($request->roles)->first(fn ($r) => in_array($r, $laboranRoles));
            if ($selectedLaboranRole) {
                \App\Models\DaftarLaboranLaboratorium::create([
                    'Laboratorium' => null,
                    'Nama_Laboran' => $sanitizedNama,
                    'UserID' => $userId,
                    'Phone' => $sanitizedPhone,
                    'Email' => $sanitizedEmail,
                    'Role_User' => $selectedLaboranRole,
                ]);
            }

            // Kirim email dengan informasi akun
            Mail::send(new AccountCredentialsMail($sanitizedNama, $sanitizedEmail, $plainPassword, $userId));

            // Log aktivitas
            $rolesStr = implode(', ', $request->roles);
            ActivityLog::create([
                'user_name' => auth()->user()->Nama ?? 'Admin',
                'action' => 'Daftarkan User Baru',
                'description' => "{$sanitizedNama} ({$sanitizedEmail}) dengan roles: {$rolesStr} - Email informasi akun dikirim",
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            $message = "User {$sanitizedNama} berhasil didaftarkan. Informasi akun telah dikirim ke {$sanitizedEmail}.";

            return redirect()->route('admin.tambah-user.index')
                ->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to create pending registration', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user' => auth()->user()->UserID ?? 'unknown',
                'ip' => request()->ip(),
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mendaftarkan user. Silakan coba lagi atau hubungi administrator.')
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }

    /**
     * Generate User ID berdasarkan role
     */
    private function generateUserId($role)
    {
        $prefix = '';

        switch ($role) {
            case 'Admin':
                $prefix = 'ADM';
                break;
            case 'Dosen':
                $prefix = 'DSN';
                break;
            case 'Safety Officer':
                $prefix = 'SO';
                break;
            case 'Kepala Laboratorium':
                $prefix = 'KL';
                break;
            case 'Mahasiswa':
                $prefix = 'MHS';
                break;
            case 'Peneliti Eksternal':
                $prefix = 'PEX';
                break;
            case 'Laboran':
                $prefix = 'LBR';
                break;
            case 'Kaprodi':
                $prefix = 'KP';
                break;
            default:
                $prefix = 'USR';
        }

        // Generate unique ID dengan timestamp + random
        $timestamp = now()->format('ymd'); // Format: YYMMDD
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $userId = "{$prefix}-{$timestamp}{$random}";

        // Cek apakah UserID sudah ada, jika ya generate ulang
        while (DaftarUser::where('UserID', $userId)->exists()) {
            $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $userId = "{$prefix}-{$timestamp}{$random}";
        }

        return $userId;
    }
}
