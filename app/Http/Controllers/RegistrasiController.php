<?php
// app/Http/Controllers/RegistrasiController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendingRegistration;
use App\Models\DaftarUser;
use App\Models\ActivityLog;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RegistrasiController extends Controller
{
    /**
     * Show registration form
     */
    public function index()
    {
        return view('auth.registrasi');
    }

    /**
     * Store pending registration and send verification email
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => [
                'required',
                'email',
                'unique:daftar_users,Email',
                'unique:pending_registrations,email'
            ],
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'Email sudah terdaftar atau sedang menunggu verifikasi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 8 karakter'
        ]);

        // Generate verification token
        $verificationToken = Str::random(64);

        // Create pending registration
        $pending = PendingRegistration::create([
            'nama' => $request->nama,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification_token' => $verificationToken,
            'token_expires_at' => Carbon::now()->addHours(24), // Token berlaku 24 jam
            'is_verified' => false
        ]);

        // Generate verification URL
        $verificationUrl = route('registrasi.verify', ['token' => $verificationToken]);

        // Send verification email
        try {
            Mail::to($request->email)->send(new VerificationEmail($request->nama, $verificationUrl));
            
            return redirect()->route('registrasi.pending')
                ->with('email', $request->email)
                ->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi.');
        } catch (\Exception $e) {
            // Jika gagal kirim email, hapus pending registration
            $pending->delete();
            
            return back()
                ->withInput()
                ->with('error', 'Gagal mengirim email verifikasi. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Show pending verification page
     */
    public function pending()
    {
        if (!session('email')) {
            return redirect()->route('registrasi');
        }

        return view('auth.registrasi-pending');
    }

    /**
     * Verify email and create user
     */
    public function verify($token)
    {
        // Cari pending registration berdasarkan token
        $pending = PendingRegistration::where('verification_token', $token)->first();

        // Validasi token
        if (!$pending) {
            return redirect()->route('login')
                ->with('error', 'Token verifikasi tidak valid.');
        }

        if ($pending->is_verified) {
            return redirect()->route('login')
                ->with('error', 'Email sudah diverifikasi sebelumnya. Silakan login.');
        }

        if ($pending->isTokenExpired()) {
            return redirect()->route('registrasi')
                ->with('error', 'Token verifikasi sudah kadaluarsa. Silakan daftar ulang.');
        }

        // Generate UserID untuk Mahasiswa
        $userID = $this->generateMahasiswaUserID();

        // Create user di database utama
        try {
            $user = DaftarUser::create([
                'Nama' => $pending->nama,
                'Phone' => $pending->phone,
                'Email' => $pending->email,
                'UserID' => $userID,
                'Password' => $pending->password, // Already hashed
                'Role_User' => 'Mahasiswa',
                'foto' => null,
            ]);

            // Mark pending as verified
            $pending->update(['is_verified' => true]);

            // Log aktivitas
            ActivityLog::create([
                'user_name' => 'System',
                'action' => 'Registrasi Mahasiswa',
                'description' => "Mahasiswa baru: {$pending->nama} - UserID: {$userID}",
                'ip_address' => request()->ip()
            ]);

            // Redirect ke halaman sukses dengan UserID
            return redirect()->route('registrasi.success')
                ->with('userID', $userID)
                ->with('nama', $pending->nama)
                ->with('success', 'Email berhasil diverifikasi! Akun Anda telah dibuat.');

        } catch (\Exception $e) {
            return redirect()->route('registrasi')
                ->with('error', 'Terjadi kesalahan saat membuat akun. Silakan coba lagi.');
        }
    }

    /**
     * Show registration success page
     */
    public function success()
    {
        if (!session('userID')) {
            return redirect()->route('login');
        }

        return view('auth.registrasi-success');
    }

    /**
     * Resend verification email
     */
    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:pending_registrations,email'
        ]);

        $pending = PendingRegistration::where('email', $request->email)
            ->where('is_verified', false)
            ->first();

        if (!$pending) {
            return back()->with('error', 'Email tidak ditemukan atau sudah diverifikasi.');
        }

        // Update token dan expiry time
        $verificationToken = Str::random(64);
        $pending->update([
            'verification_token' => $verificationToken,
            'token_expires_at' => Carbon::now()->addHours(24)
        ]);

        // Generate verification URL
        $verificationUrl = route('registrasi.verify', ['token' => $verificationToken]);

        // Resend email
        try {
            Mail::to($pending->email)->send(new VerificationEmail($pending->nama, $verificationUrl));
            
            return back()->with('success', 'Email verifikasi telah dikirim ulang. Silakan cek inbox Anda.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim ulang email. Silakan coba lagi.');
        }
    }

    /**
     * Generate unique UserID for Mahasiswa
     */
    private function generateMahasiswaUserID()
    {
        do {
            // Format: MHS-YYMMDDXXXX
            $userID = 'MHS-' . date('ymd') . rand(1000, 9999);
        } while (DaftarUser::where('UserID', $userID)->exists());

        return $userID;
    }
}