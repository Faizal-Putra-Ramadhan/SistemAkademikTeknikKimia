<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaPelakuKerja extends Model
{
    protected $table = 'ra_pelaku_kerjas';

    protected $fillable = [
        'risk_assessment_id',
        'menyadari_faktor_manusia',
        'memahami_bahaya_diri',
        'memahami_bahaya_orang_lain',
        'memahami_bahaya_lingkungan',
        'memahami_bahaya_peralatan',
        'paham_tindakan_kecelakaan',
        'penilaian_keterampilan',
    ];

    protected $casts = [
        'menyadari_faktor_manusia' => 'integer',
        'memahami_bahaya_diri' => 'integer',
        'memahami_bahaya_orang_lain' => 'integer',
        'memahami_bahaya_lingkungan' => 'integer',
        'memahami_bahaya_peralatan' => 'integer',
        'paham_tindakan_kecelakaan' => 'integer',
    ];

    public function riskAssessment(): BelongsTo
    {
        return $this->belongsTo(RiskAssessment::class, 'risk_assessment_id');
    }
}
