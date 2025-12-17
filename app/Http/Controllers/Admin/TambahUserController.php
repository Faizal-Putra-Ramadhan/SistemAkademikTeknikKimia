<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\DaftarUser;
use Hash;
use Illuminate\Http\Request;

class TambahUserController extends Controller
{
    public function index()
    {
        return view('admin.tambah-user.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:daftar_users,Email',
            'Phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:Admin,Dosen,Tendik,Mahasiswa,Safety Officer,Kepala Laboratorium,Laboran'
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'phone.required' => 'Nomor telepon wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih'
        ]);

        try {
            $userId = $this->generateUserId($request->role);

            $user = DaftarUser::create([
                'Nama' => $request->nama,
                'Email' => $request->email,
                'Phone' => $request->Phone,
                'UserID' => $userId,
                'Password' => Hash::make($request->password),
                'Role_User' => $request->role
            ]);

            // LOG AKTIVITAS OTOMATIS
            ActivityLog::create([
                'user_name'    => 'Administrator', // nanti diganti auth()->user()->name
                'action'       => 'Mendaftarkan User Baru',
                'description'  => "{$user->Nama} ({$user->Role_User}) - UserID: {$userId}",
                'ip_address'   => request()->ip(),
            ]);

            return redirect()->route('admin.tambah-user.index')
                ->with('success', "User berhasil didaftarkan! User ID: {$userId}");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mendaftarkan user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Generate User ID berdasarkan role
     * Format:
     * - Admin: ADM-XXXXXX
     * - Dosen: DSN-XXXXXX
     * - Tendik: TDK-XXXXXX
     * - Mahasiswa: MHS-XXXXXX
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
            case 'Laboran':
                $prefix = 'LBR';
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
