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

        // Jika role tidak sesuai, redirect ke dashboard sesuai role user
        $dashboardRoutes = [
            'Admin'               => 'admin.dashboard',
            'Dosen'               => 'dosen.dashboard',
            'Kaprodi'             => 'kaprodi.dashboard',
            'Kepala Laboratorium'  => 'kepala-lab.dashboard',
            'Laboran'             => 'laboran.dashboard',
            'Mahasiswa'           => 'mahasiswa.dashboard',
            'Peneliti Eksternal'  => 'peneliti-eksternal.dashboard',
            'Safety Officer'      => 'safety-officer.dashboard',
        ];

        // Cari dashboard route berdasarkan role aktif user
        $activeRole = $user->active_role ?? $user->Role_User ?? null;
        $redirectRoute = $dashboardRoutes[$activeRole] ?? null;

        if ($redirectRoute && \Route::has($redirectRoute)) {
            return redirect()->route($redirectRoute)
                ->with('warning', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
        }

        // Fallback jika role tidak dikenal
        return redirect()->route('login')
            ->with('error', 'Akses ditolak. Silakan login dengan akun yang sesuai.');
    }
}
