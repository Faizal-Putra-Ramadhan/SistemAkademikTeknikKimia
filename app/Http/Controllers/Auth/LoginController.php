<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLoginForm()
    {
        // JANGAN redirect jika sudah login di showLoginForm
        // Biarkan user lihat form login
        $pengumuman = \App\Models\Pengumuman::where('status', 'publish')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Statistik dinamis (mendukung multi-role: 1 email bisa punya banyak role)
        $stats = [
            'mahasiswa' => \App\Models\DaftarUser::where(function ($q) {
                $q->where('Role_User', 'Mahasiswa')
                    ->orWhereHas('roles', fn ($r) => $r->where('name', 'Mahasiswa'));
            })->count(),
            'dosen' => \App\Models\DaftarUser::where(function ($q) {
                $q->where('Role_User', 'Dosen')
                    ->orWhereHas('roles', fn ($r) => $r->where('name', 'Dosen'));
            })->count(),
            'alat_lab' => \App\Models\AlatLab::count(),
            'lab_aktif' => \App\Models\DaftarLab::count(),
        ];

        return view('auth.login', compact('pengumuman', 'stats'));

    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        
        $throttleKey = 'login-attempts:' . strtolower($request->email);

        // 1. Cek apakah akun sedang terkunci
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Akun dikunci sementara. Silakan coba lagi dalam $seconds detik.",
            ])->withInput($request->only('email'));
        }

        // 2. Percobaan authentikasi
        if (Auth::attempt($credentials)) {
            // Login Berhasil
            \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);
            
            $request->session()->regenerate();

            // Redirect berdasarkan role
            $user = Auth::user();

            // Ambil primary role (jika ada) atau fallback ke Role_User lama
            $primaryRole = $user->primaryRole();
            $roleToUse = $primaryRole?->name ?? ($user->Role_User ?? 'Admin');

            return match ($roleToUse) {
                'Admin' => redirect()->intended('/'),
                'Dosen' => redirect()->intended(route('dosen.dashboard')),
                'Mahasiswa' => redirect()->intended(route('mahasiswa.dashboard')),
                'Laboran' => redirect()->intended(route('laboran.dashboard')),
                'Kaprodi' => redirect()->intended(route('kaprodi.dashboard')),
                'Safety Officer' => redirect()->intended(route('safety-officer.dashboard')),
                'Kepala Laboratorium' => redirect()->intended(route('kepala-lab.dashboard')),
                'Peneliti Eksternal' => redirect()->intended(route('peneliti-eksternal.dashboard')),
                default => redirect()->intended('/'),
            };
        }

        // 3. Login Gagal - Tambah hitungan percobaan
        \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 60); // Kunci selama 60 detik jika limit tercapai

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function redirectBasedOnRole()
    {
        $user = Auth::user();

        return match ($user->Role_User) {
            'Admin' => redirect()->route('admin.dashboard'),
            'Dosen' => redirect()->route('dosen.dashboard'),
            'Mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            'Laboran' => redirect()->route('laboran.dashboard'),
            default => redirect()->route('home'),
        };
    }
}
