<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
    
    return view('auth.login', compact('pengumuman'));
        
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

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            $user = Auth::user();
            
            return match($user->Role_User) {
                'Admin' => redirect()->intended('/'),
                'Dosen' => redirect()->intended(route('dosen.dashboard')),
                'Mahasiswa' => redirect()->intended(route('mahasiswa.dashboard')),
                'Laboran' => redirect()->intended(route('laboran.dashboard')),
                default => redirect()->intended('/'),
            };
        }

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
    
    return match($user->Role_User) {
        'Admin' => redirect()->route('admin.dashboard'),
        'Dosen' => redirect()->route('dosen.dashboard'),
        'Mahasiswa' => redirect()->route('mahasiswa.dashboard'),
        'Laboran' => redirect()->route('laboran.dashboard'),
        default => redirect()->route('home'),
    };
}
}