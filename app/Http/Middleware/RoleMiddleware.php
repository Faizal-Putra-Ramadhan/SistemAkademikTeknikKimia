<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        // Jika belum login, redirect ke login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Jika role sesuai, lanjutkan request
        if ($user->Role_User === $role) {
            return $next($request);
        }
        
        // Jika role tidak sesuai, tampilkan error 403
        abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
    }
}