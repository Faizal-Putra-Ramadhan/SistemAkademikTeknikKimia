<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\DaftarUser;
use App\Models\PendingRegistration;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmailVerificationController extends Controller
{
    /**
     * Halaman untuk verifikasi email dengan token
     */
    public function verify($token)
    {
        $pending = PendingRegistration::where('verification_token', $token)->first();

        // Check apakah token ada dan valid
        if (! $pending) {
            return view('admin.email-verification.token-invalid', [
                'message' => 'Token verifikasi tidak ditemukan atau sudah dihapus.',
            ]);
        }

        // Check apakah token sudah expired
        if ($pending->isTokenExpired()) {
            return view('admin.email-verification.token-expired', [
                'pending' => $pending,
                'message' => 'Token verifikasi telah kadaluarsa. Silakan minta admin untuk mendaftarkan ulang.',
            ]);
        }

        // Check apakah sudah diverifikasi
        if ($pending->is_verified) {
            return view('admin.email-verification.already-verified', [
                'message' => 'Email ini sudah diverifikasi sebelumnya.',
            ]);
        }

        return view('admin.email-verification.verify', [
            'pending' => $pending,
            'token' => $token,
        ]);
    }

    /**
     * Confirm verifikasi email
     */
    public function confirm(Request $request, $token)
    {
        $pending = PendingRegistration::where('verification_token', $token)->first();

        // Validasi token
        if (! $pending) {
            return redirect()->route('admin.email-verification.token-invalid')
                ->with('error', 'Token verifikasi tidak ditemukan.');
        }

        if ($pending->isTokenExpired()) {
            return redirect()->route('admin.email-verification.token-expired', ['pending' => $pending->id])
                ->with('error', 'Token verifikasi telah kadaluarsa.');
        }

        if ($pending->is_verified) {
            return redirect()->route('admin.email-verification.already-verified')
                ->with('info', 'Email ini sudah diverifikasi sebelumnya.');
        }

        DB::beginTransaction();

        try {
            // Mark sebagai terverifikasi
            $pending->update(['is_verified' => true]);

            // Buat user baru dengan data dari pending registration
            $user = DaftarUser::create([
                'UserID' => $this->generateUserId($pending->role),
                'Nama' => $pending->nama,
                'Email' => $pending->email,
                'Phone' => $pending->phone,
                'Password' => $pending->password, // Password sudah di-hash di pending
                'Role_User' => $pending->role,
                'nomor_identitas' => $pending->nomor_identitas,
                'is_primary' => $pending->is_primary,
                'parent_user_id' => $pending->parent_user_id,
            ]);

            // Log aktivitas
            ActivityLog::create([
                'user_name' => auth()->user()->Nama ?? 'Admin',
                'action' => 'Email Terverifikasi dan User Ditambahkan',
                'description' => "User {$pending->nama} ({$user->UserID}) berhasil diverifikasi dan ditambahkan ke sistem",
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            return view('admin.email-verification.success', [
                'user' => $user,
                'message' => 'Email berhasil diverifikasi! User telah ditambahkan ke sistem.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to verify email and create user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'pending_id' => $pending->id,
                'ip' => request()->ip(),
            ]);

            return view('admin.email-verification.error', [
                'message' => 'Terjadi kesalahan saat memproses verifikasi. Silakan hubungi administrator.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Resend verification email (jika token expired)
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:pending_registrations,email',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak ditemukan dalam daftar tunggu',
        ]);

        $pending = PendingRegistration::where('email', $request->email)->first();

        // Check apakah sudah diverifikasi
        if ($pending->is_verified) {
            return redirect()->back()
                ->with('info', 'Email ini sudah diverifikasi sebelumnya.');
        }

        // Generate token baru
        $newToken = \Illuminate\Support\Str::random(64);
        $pending->update([
            'verification_token' => $newToken,
            'token_expires_at' => now()->addHours(24), // Token berlaku 24 jam
        ]);

        // Send email ulang
        try {
            \Illuminate\Support\Facades\Mail::send('emails.verification', [
                'nama' => $pending->nama,
                'verificationUrl' => route('admin.email-verification.verify', $newToken),
            ], function ($message) use ($pending) {
                $message->to($pending->email)
                    ->subject('Verifikasi Email - Sistem RegLab UAD (Resend)');
            });

            // Log aktivitas
            ActivityLog::create([
                'user_name' => auth()->user()->Nama ?? 'System',
                'action' => 'Kirim Ulang Email Verifikasi',
                'description' => "Email verifikasi untuk {$pending->nama} ({$pending->email}) dikirim ulang",
                'ip_address' => request()->ip(),
            ]);

            return redirect()->back()
                ->with('success', 'Email verifikasi telah dikirim ulang. Silakan cek email Anda.');

        } catch (\Exception $e) {
            Log::error('Failed to resend verification email', [
                'error' => $e->getMessage(),
                'email' => $pending->email,
                'ip' => request()->ip(),
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mengirim ulang email verifikasi. Silakan coba lagi.');
        }
    }

    /**
     * Cancel pending registration
     */
    public function cancel(Request $request, $id)
    {
        $pending = PendingRegistration::findOrFail($id);

        // Verifikasi bahwa hanya admin yang bisa membatalkan
        if (auth()->user()->Role_User !== 'Admin') {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk membatalkan pendaftaran.');
        }

        try {
            $email = $pending->email;
            $nama = $pending->nama;

            // Delete pending registration
            $pending->delete();

            // Log aktivitas
            ActivityLog::create([
                'user_name' => auth()->user()->Nama ?? 'Admin',
                'action' => 'Batalkan Pendaftaran Tertunda',
                'description' => "Pendaftaran untuk {$nama} ({$email}) dibatalkan",
                'ip_address' => request()->ip(),
            ]);

            return redirect()->back()
                ->with('success', "Pendaftaran untuk {$nama} telah dibatalkan.");

        } catch (\Exception $e) {
            Log::error('Failed to cancel pending registration', [
                'error' => $e->getMessage(),
                'pending_id' => $id,
                'ip' => request()->ip(),
            ]);

            return redirect()->back()
                ->with('error', 'Gagal membatalkan pendaftaran. Silakan coba lagi.');
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
            case 'Tendik':
                $prefix = 'TDK';
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
