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

        
        $laboran = DaftarLaboranLaboratorium::with('laboratoriums')->where('UserID', $user->UserID)->first();

        if (! $laboran) {
            return redirect()->back()->with('error', 'Data laboran tidak ditemukan.');
        }

        
        $lab = DaftarLab::findOrFail($labId);
        $isAuthorized = $laboran->laboratoriums->contains('id', $labId);

        if (! $isAuthorized) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke laboratorium ini.');
        }

        
        session(['active_lab_id' => $labId]);
        session(['active_lab_name' => $lab->Nama_Laboratorium]);

        
        $request->session()->put('active_lab_id', $labId);
        $request->session()->put('active_lab_name', $lab->Nama_Laboratorium);

        
        $request->session()->regenerate();

        
        \Log::info('Lab Switch - Session saved', [
            'user_id' => $user->UserID,
            'lab_id' => $labId,
            'lab_name' => $lab->Nama_Laboratorium,
            'session_active_lab_id' => session('active_lab_id'),
        ]);

        
        ActivityLog::create([
            'user_name' => $user->Nama,
            'action' => 'Switch Laboratorium',
            'description' => "Switch ke laboratorium: {$lab->Nama_Laboratorium}",
            'ip_address' => $request->ip(),
        ]);

        
        
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

        
        $activeLabId = session('active_lab_id');

        if ($activeLabId) {
            $activeLab = $laboran->laboratoriums->firstWhere('id', $activeLabId);
            if ($activeLab) {
                return $activeLab;
            }
        }

        
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
