<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaPernyataanMahasiswa extends Model
{
    protected $table = 'ra_pernyataan_mahasiswas';

    protected $fillable = [
        'risk_assessment_id',
        'tekanan_tinggi',
        'suhu_tinggi',
        'nyala_api',
        'peralatan_berputar',
    ];

    protected $casts = [
        'tekanan_tinggi' => 'integer',
        'suhu_tinggi' => 'integer',
        'nyala_api' => 'integer',
        'peralatan_berputar' => 'integer',
    ];

    public function riskAssessment(): BelongsTo
    {
        return $this->belongsTo(RiskAssessment::class, 'risk_assessment_id');
    }
}
