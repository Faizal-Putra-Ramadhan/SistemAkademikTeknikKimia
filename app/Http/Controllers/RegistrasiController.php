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
     * Validate UAD email for Teknik Kimia students
     */
    private function validateUADEmail($email)
    {
        // Pattern: YYXXXPPNNN@webmail.uad.ac.id
        // YY = Angkatan (2 digit)
        // XXX = Jenis mahasiswa (000 = reguler)
        // PP = Kode Prodi (18 = Teknik Kimia)
        // NNN = Nomor mahasiswa (3 digit)
        
        $pattern = '/^(\d{2})(\d{3})(18)(\d{3})@webmail\.uad\.ac\.id$/';
        
        if (!preg_match($pattern, $email, $matches)) {
            return [
                'valid' => false,
                'message' => 'Email harus menggunakan format email UAD Teknik Kimia'
            ];
        }
        
        $angkatan = $matches[1];
        $jenisMahasiswa = $matches[2];
        $kodeProdi = $matches[3];
        $nomorMahasiswa = $matches[4];
        
        // Validasi kode prodi harus 18 (Teknik Kimia)
        if ($kodeProdi !== '18') {
            return [
                'valid' => false,
                'message' => 'Email bukan untuk Prodi Teknik Kimia. Kode prodi harus 18.'
            ];
        }
        
        // Validasi angkatan (harus >= 20 dan <= tahun sekarang)
        $currentYear = (int)date('y');
        $angkatanInt = (int)$angkatan;
        
        if ($angkatanInt < 20 || $angkatanInt > $currentYear) {
            return [
                'valid' => false,
                'message' => 'Angkatan tidak valid. Email harus untuk mahasiswa angkatan 2020 atau lebih baru.'
            ];
        }
        
        return [
            'valid' => true,
            'angkatan' => '20' . $angkatan,
            'jenis_mahasiswa' => $jenisMahasiswa,
            'kode_prodi' => $kodeProdi,
            'nomor_mahasiswa' => $nomorMahasiswa
        ];
    }

    /**
     * Store pending registration and send verification email
     */
    public function store(Request $request)
    {
        // Validasi input dasar
        $request->validate([
            'nama' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => [
                'required',
                'email',
            ],
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 8 karakter'
        ]);

        // Validasi email UAD Teknik Kimia
        $emailValidation = $this->validateUADEmail($request->email);
        
        if (!$emailValidation['valid']) {
            return back()
                ->withInput()
                ->withErrors(['email' => $emailValidation['message']]);
        }

        // Cek apakah email sudah terdaftar
        if (DaftarUser::where('Email', $request->email)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Email sudah terdaftar. Silakan login atau gunakan email lain.']);
        }

        if (PendingRegistration::where('email', $request->email)->where('is_verified', false)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Email sedang menunggu verifikasi. Silakan cek inbox Anda atau kirim ulang email verifikasi.']);
        }

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

        // Validasi ulang email (double check)
        $emailValidation = $this->validateUADEmail($pending->email);
        if (!$emailValidation['valid']) {
            $pending->delete();
            return redirect()->route('registrasi')
                ->with('error', 'Email tidak valid untuk registrasi.');
        }

        // Generate UserID untuk Mahasiswa dengan format berdasarkan email
        $userID = $this->generateMahasiswaUserID($pending->email, $emailValidation);

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
                'description' => "Mahasiswa baru: {$pending->nama} - UserID: {$userID} - Angkatan: {$emailValidation['angkatan']}",
                'ip_address' => request()->ip()
            ]);

            // Redirect ke halaman sukses dengan UserID
            return redirect()->route('registrasi.success')
                ->with('userID', $userID)
                ->with('nama', $pending->nama)
                ->with('angkatan', $emailValidation['angkatan'])
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
            'email' => 'required|email'
        ]);

        // Validasi email UAD Teknik Kimia
        $emailValidation = $this->validateUADEmail($request->email);
        
        if (!$emailValidation['valid']) {
            return back()->withErrors(['email' => $emailValidation['message']]);
        }

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
     * Generate unique UserID for Mahasiswa based on email
     */
    private function generateMahasiswaUserID($email, $emailData)
    {
        // Format: TEKIM-ANGKATAN-NOMORURUT
        // Contoh: TEKIM-23-199
        $angkatan = $emailData['angkatan'];
        $nomorMahasiswa = $emailData['nomor_mahasiswa'];
        
        $userID = "TEKIM-{$angkatan}-{$nomorMahasiswa}";
        
        // Jika sudah ada, tambahkan suffix
        $suffix = 1;
        $originalUserID = $userID;
        while (DaftarUser::where('UserID', $userID)->exists()) {
            $userID = $originalUserID . '-' . $suffix;
            $suffix++;
        }

        return $userID;
    }
}