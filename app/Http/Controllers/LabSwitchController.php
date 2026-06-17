<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\DaftarLab;
use App\Models\DaftarLaboranLaboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabSwitchController extends Controller
{
    /**
     * Switch ke laboratorium lain yang dikelola oleh laboran
     */
    public function switch(Request $request, $labId)
    {
        $user = Auth::user();

        // Ambil data laboran
        $laboran = DaftarLaboranLaboratorium::with('laboratoriums')->where('UserID', $user->UserID)->first();

        if (! $laboran) {
            return redirect()->back()->with('error', 'Data laboran tidak ditemukan.');
        }

        // Validasi bahwa lab yang dipilih adalah lab yang dikelola oleh laboran ini
        $lab = DaftarLab::findOrFail($labId);
        $isAuthorized = $laboran->laboratoriums->contains('id', $labId);

        if (! $isAuthorized) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke laboratorium ini.');
        }

        // Simpan lab aktif ke session (gunakan session() helper untuk memastikan tersimpan)
        session(['active_lab_id' => $labId]);
        session(['active_lab_name' => $lab->Nama_Laboratorium]);

        // Juga simpan menggunakan request session untuk memastikan
        $request->session()->put('active_lab_id', $labId);
        $request->session()->put('active_lab_name', $lab->Nama_Laboratorium);

        // Regenerate session untuk memastikan perubahan tersimpan
        $request->session()->regenerate();

        // Debug: Log untuk memastikan session tersimpan
        \Log::info('Lab Switch - Session saved', [
            'user_id' => $user->UserID,
            'lab_id' => $labId,
            'lab_name' => $lab->Nama_Laboratorium,
            'session_active_lab_id' => session('active_lab_id'),
        ]);

        // Log aktivitas
        ActivityLog::create([
            'user_name' => $user->Nama,
            'action' => 'Switch Laboratorium',
            'description' => "Switch ke laboratorium: {$lab->Nama_Laboratorium}",
            'ip_address' => $request->ip(),
        ]);

        // Redirect ke dashboard dengan lab yang dipilih
        // Gunakan route dengan parameter untuk memastikan URL benar
        return redirect()->route('laboran.dashboard', ['id' => $labId])
            ->with('success', "Berhasil switch ke laboratorium: {$lab->Nama_Laboratorium}");
    }

    /**
     * Helper: Ambil lab aktif dari session atau fallback ke lab pertama
     */
    public static function getActiveLab($user)
    {
        $laboran = DaftarLaboranLaboratorium::with('laboratoriums')->where('UserID', $user->UserID)->first();

        if (! $laboran || $laboran->laboratoriums->isEmpty()) {
            return null;
        }

        // Cek session untuk lab aktif
        $activeLabId = session('active_lab_id');

        if ($activeLabId) {
            $activeLab = $laboran->laboratoriums->firstWhere('id', $activeLabId);
            if ($activeLab) {
                return $activeLab;
            }
        }

        // Fallback ke lab pertama
        return $laboran->laboratoriums->first();
    }

    /**
     * Helper: Ambil semua lab yang dikelola laboran
     */
    public static function getLabsForLaboran($user)
    {
        $laboran = DaftarLaboranLaboratorium::with('laboratoriums')->where('UserID', $user->UserID)->first();

        if (! $laboran) {
            return collect();
        }

        return $laboran->laboratoriums;
    }
}
