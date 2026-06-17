<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HandleRoleSwitching
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            $request->session()->forget(['role_switch_id', 'original_user_id']);

            return $next($request);
        }

        // Jika ada session role_switch_id, gunakan akun tersebut
        if ($request->session()->has('role_switch_id')) {
            $switchedUserId = $request->session()->get('role_switch_id');

            // Validasi bahwa switched user adalah linked account dari current user
            $switchedUser = \App\Models\DaftarUser::find($switchedUserId);

            if ($switchedUser && $this->isValidLinkedAccount($user, $switchedUser)) {
                // Override auth user dengan switched user
                Auth::setUser($switchedUser);
            } else {
                // Jika tidak valid, hapus session
                $request->session()->forget(['role_switch_id', 'original_user_id']);
            }
        }

        return $next($request);
    }

    /**
     * Check apakah user yang di-switch adalah linked account yang valid
     */
    private function isValidLinkedAccount($originalUser, $switchedUser): bool
    {
        $allLinkedAccounts = $originalUser->getAllLinkedAccounts();

        return $allLinkedAccounts->contains('id', $switchedUser->id);
    }
}
