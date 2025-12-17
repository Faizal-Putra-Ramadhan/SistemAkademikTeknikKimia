<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// ============================================
// Model: RaBahanKimia.php
// ============================================
class RaBahanKimia extends Model
{
    protected $table = 'ra_bahan_kimias';

    protected $fillable = [
        'risk_assessment_id',
        'nama_bahan',
        'explosive',
        'flammable',
        'toxic',
        'corrosive',
        'irritant',
        'oxidizing',
        'lain_lain',
        'msds_file',
    ];

    protected $casts = [
        'explosive' => 'boolean',
        'flammable' => 'boolean',
        'toxic' => 'boolean',
        'corrosive' => 'boolean',
        'irritant' => 'boolean',
        'oxidizing' => 'boolean',
    ];

    public function riskAssessment(): BelongsTo
    {
        return $this->belongsTo(RiskAssessment::class, 'risk_assessment_id');
    }

    public function getSifatBahanArray(): array
    {
        $sifat = [];
        if ($this->explosive) $sifat[] = 'Explosive';
        if ($this->flammable) $sifat[] = 'Flammable';
        if ($this->toxic) $sifat[] = 'Toxic';
        if ($this->corrosive) $sifat[] = 'Corrosive';
        if ($this->irritant) $sifat[] = 'Irritant';
        if ($this->oxidizing) $sifat[] = 'Oxidizing';
        if ($this->lain_lain) $sifat[] = 'Lain-lain: ' . $this->lain_lain;
        
        return $sifat;
    }
}

// ============================================
// Model: RaKategoriHazardBahan.php
// ============================================
class RaKategoriHazardBahan extends Model
{
    protected $table = 'ra_kategori_hazard_bahan';

    protected $fillable = [
        'risk_assessment_id',
        'kategori',
    ];

    public function riskAssessment(): BelongsTo
    {
        return $this->belongsTo(RiskAssessment::class, 'risk_assessment_id');
    }

    public function getKategoriLabel(): string
    {
        return match($this->kategori) {
            'sangat_hazardous' => 'Sangat Hazardous',
            'hazardous' => 'Hazardous',
            'moderat' => 'Moderat',
            'tidak_hazardous' => 'Tidak Hazardous',
            default => 'Tidak Diketahui',
        };
    }
}

// ============================================
// Model: RaPeralatanOperasi.php
// ============================================
class RaPeralatanOperasi extends Model
{
    protected $table = 'ra_peralatan_operasis';

    protected $fillable = [
        'risk_assessment_id',
        'tekanan_tinggi',
        'suhu_tinggi',
        'nyala_api',
        'peralatan_berputar',
        'temperatur_maksimum',
        'tekanan_maksimum',
        'kategori_hazard',
    ];

    protected $casts = [
        'tekanan_tinggi' => 'boolean',
        'suhu_tinggi' => 'boolean',
        'nyala_api' => 'boolean',
        'peralatan_berputar' => 'boolean',
        'temperatur_maksimum' => 'decimal:2',
        'tekanan_maksimum' => 'decimal:2',
    ];

    public function riskAssessment(): BelongsTo
    {
        return $this->belongsTo(RiskAssessment::class, 'risk_assessment_id');
    }

    public function getKondisiOperasiArray(): array
    {
        $kondisi = [];
        if ($this->tekanan_tinggi) $kondisi[] = 'Tekanan Tinggi';
        if ($this->suhu_tinggi) $kondisi[] = 'Suhu Tinggi';
        if ($this->nyala_api) $kondisi[] = 'Nyala Api';
        if ($this->peralatan_berputar) $kondisi[] = 'Peralatan Berputar';
        
        return $kondisi;
    }

    public function getKategoriHazardLabel(): ?string
    {
        if (!$this->kategori_hazard) return null;

        return match($this->kategori_hazard) {
            'sangat_hazardous' => 'Sangat Hazardous',
            'hazardous' => 'Hazardous',
            'moderat' => 'Moderat',
            'tidak_hazardous' => 'Tidak Hazardous',
            default => 'Tidak Diketahui',
        };
    }
}

// ============================================
// Model: RaPelakuKerja.php
// ============================================
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
        'menyadari_faktor_manusia' => 'boolean',
        'memahami_bahaya_diri' => 'boolean',
        'memahami_bahaya_orang_lain' => 'boolean',
        'memahami_bahaya_lingkungan' => 'boolean',
        'memahami_bahaya_peralatan' => 'boolean',
        'paham_tindakan_kecelakaan' => 'boolean',
    ];

    public function riskAssessment(): BelongsTo
    {
        return $this->belongsTo(RiskAssessment::class, 'risk_assessment_id');
    }

    public function getPenilaianKeterampilanLabel(): ?string
    {
        if (!$this->penilaian_keterampilan) return null;

        return match($this->penilaian_keterampilan) {
            'ceroboh' => 'Ceroboh',
            'kurang_terampil' => 'Kurang Terampil',
            'cukup_terampil' => 'Cukup Terampil',
            'sangat_terampil' => 'Sangat Terampil',
            default => 'Tidak Diketahui',
        };
    }

    public function isSemuaPemahamanTerpenuhi(): bool
    {
        return $this->menyadari_faktor_manusia
            && $this->memahami_bahaya_diri
            && $this->memahami_bahaya_orang_lain
            && $this->memahami_bahaya_lingkungan
            && $this->memahami_bahaya_peralatan
            && $this->paham_tindakan_kecelakaan;
    }
}

// ============================================
// Model: RaPernyataanMahasiswa.php
// ============================================
class RaPernyataanMahasiswa extends Model
{
    protected $table = 'ra_pernyataan_mahasiswas';

    protected $fillable = [
        'risk_assessment_id',
        'setuju_bertanggung_jawab',
        'tanda_tangan',
        'tanggal_pernyataan',
    ];

    protected $casts = [
        'setuju_bertanggung_jawab' => 'boolean',
        'tanggal_pernyataan' => 'datetime',
    ];

    public function riskAssessment(): BelongsTo
    {
        return $this->belongsTo(RiskAssessment::class, 'risk_assessment_id');
    }

    public function hasTandaTangan(): bool
    {
        return !empty($this->tanda_tangan);
    }
}