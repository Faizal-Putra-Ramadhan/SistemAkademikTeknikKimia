<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $roles): mixed
    {
        // Jika belum login, redirect ke login
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Support multiple roles separated by pipe (|)
        $allowedRoles = explode('|', $roles);

        // Check if user has any of the allowed roles
        $hasAccess = false;
        foreach ($allowedRoles as $role) {
            $role = trim($role);
            if ($user->hasRole($role)) {
                $hasAccess = true;
                break;
            }
        }

        // Jika user memiliki salah satu role yang diizinkan, lanjutkan request
        if ($hasAccess) {
            return $next($request);
        }

        // Jika role tidak sesuai, tampilkan error 403
        abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
    }
}
