<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\LabType;
use App\Models\DaftarLab;
use App\Models\RiskAssessment;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureStockGroupAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (! $user) {
            return $next($request);
        }

        // Only enforce for mahasiswa
        if (! $user->hasRole('Mahasiswa')) {
            return $next($request);
        }

        $labId = $request->route('id') ?? $request->route('lab_id');
        $riskAssessmentId = $request->input('risk_assessment_id');

        if (! $labId || ! $riskAssessmentId) {
            return $next($request);
        }

        $lab = DaftarLab::find($labId);
        $riskAssessment = RiskAssessment::with('daftarLab')->find($riskAssessmentId);

        if (! $lab || ! $riskAssessment || (int) $riskAssessment->user_id !== (int) $user->id) {
            return back()->with('error', 'Risk Assessment atau laboratorium tidak valid.');
        }

        if (! $riskAssessment->daftarLab || $riskAssessment->daftarLab->lab_type !== LabType::Penelitian->value) {
            return back()->with('error', 'Risk Assessment hanya boleh menggunakan lab penelitian.');
        }

        if ((int) $lab->stock_group_id !== (int) $riskAssessment->daftarLab->stock_group_id) {
            return back()->with('error', 'Risk Assessment hanya berlaku untuk grup stok laboratorium yang sama.');
        }

        return $next($request);
    }
}
