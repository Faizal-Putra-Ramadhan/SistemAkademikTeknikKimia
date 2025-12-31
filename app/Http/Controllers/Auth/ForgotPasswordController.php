<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\DaftarUser;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller
{
    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send reset password link to email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:daftar_users,Email'
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak terdaftar di sistem'
        ]);

        // Generate token
        $token = Str::random(64);
        
        // Simpan atau update token di database
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Kirim email
        try {
            $user = DaftarUser::where('Email', $request->email)->first();
            
            Mail::to($request->email)->send(new ResetPasswordMail([
                'token' => $token,
                'email' => $request->email,
                'name' => $user->Nama
            ]));

            return back()->with('success', 'Link reset password telah dikirim ke email Anda! Silakan cek email (termasuk folder spam).');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email. Pastikan konfigurasi email sudah benar.');
        }
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:daftar_users,Email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.exists' => 'Email tidak ditemukan',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok'
        ]);

        // Cek token validity
        $resetRecord = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()->with('error', 'Token reset password tidak valid atau sudah kadaluarsa.');
        }

        // Cek apakah token cocok
        if (!Hash::check($request->token, $resetRecord->token)) {
            return back()->with('error', 'Token reset password tidak valid.');
        }

        // Cek apakah token sudah kadaluarsa (lebih dari 1 jam)
        $tokenAge = now()->diffInMinutes($resetRecord->created_at);
        if ($tokenAge > 60) {
            return back()->with('error', 'Token reset password sudah kadaluarsa. Silakan request ulang.');
        }

        // Update password
        $user = DaftarUser::where('Email', $request->email)->first();
        $user->Password = Hash::make($request->password);
        $user->save();

        // Hapus token setelah digunakan
        \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return redirect()->route('login')->with('success', 'Password berhasil diubah! Silakan login dengan password baru Anda.');
    }
}