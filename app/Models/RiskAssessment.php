<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RiskAssessment extends Model
{
    protected $table = 'risk_assessments';

    protected $fillable = [
        'user_id',
        'nama',
        'nim',
        'no_kontak',
        'alamat_kontak',
        'daftar_lab_id',
        'jenis_ra',
        'topik_judul',
        'dosen_pembimbing_id',
        'dosen_pembimbing_nama',
        'status',
        'kategori_resiko_dosen',
        'persetujuan_dosen',
        'catatan_dosen',
        'tanggal_persetujuan_dosen',
        'safety_officer_id',
        'safety_officer_nama',
        'jadwal_wawancara',
        'persetujuan_safety_officer',
        'catatan_safety_officer',
        'tanggal_persetujuan_safety_officer',
        'kepala_lab_id',
        'persetujuan_kepala_lab',
        'catatan_kepala_lab',
        'tanggal_persetujuan_kepala_lab',
    ];

    protected $casts = [
        'persetujuan_dosen' => 'boolean',
        'persetujuan_safety_officer' => 'boolean',
        'persetujuan_kepala_lab' => 'boolean',
        'jadwal_wawancara' => 'datetime',
        'tanggal_persetujuan_dosen' => 'datetime',
        'tanggal_persetujuan_safety_officer' => 'datetime',
        'tanggal_persetujuan_kepala_lab' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(DaftarUser::class, 'user_id');
    }

    public function daftarLab(): BelongsTo
    {
        return $this->belongsTo(DaftarLab::class, 'daftar_lab_id');
    }

    public function dosenPembimbing(): BelongsTo
    {
        return $this->belongsTo(DaftarUser::class, 'dosen_pembimbing_id');
    }

    public function safetyOfficer(): BelongsTo
    {
        return $this->belongsTo(DaftarUser::class, 'safety_officer_id');
    }

    public function kepalaLab(): BelongsTo
    {
        return $this->belongsTo(DaftarUser::class, 'kepala_lab_id');
    }

    public function bahanKimias(): HasMany
    {
        return $this->hasMany(RaBahanKimia::class, 'risk_assessment_id');
    }

    public function kategoriHazardBahan(): HasOne
    {
        return $this->hasOne(RaKategoriHazardBahan::class, 'risk_assessment_id');
    }

    public function peralatanOperasi(): HasOne
    {
        return $this->hasOne(RaPeralatanOperasi::class, 'risk_assessment_id');
    }

    public function pelakuKerja(): HasOne
    {
        return $this->hasOne(RaPelakuKerja::class, 'risk_assessment_id');
    }

    public function pernyataanMahasiswa(): HasOne
    {
        return $this->hasOne(RaPernyataanMahasiswa::class, 'risk_assessment_id');
    }

    // Scopes
    public function scopePenelitian($query)
    {
        return $query->where('jenis_ra', 'Penelitian');
    }

    public function scopePraktikum($query)
    {
        return $query->where('jenis_ra', 'Praktikum');
    }

    public function scopeMenungguPersetujuan($query)
    {
        return $query->whereIn('status', ['menunggu_dosen', 'menunggu_safety_officer', 'menunggu_kepala_lab']);
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    // Helper Methods
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isDisetujui(): bool
    {
        return $this->status === 'disetujui';
    }

    public function isDitolak(): bool
    {
        return $this->status === 'ditolak';
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'menunggu_dosen' => 'Menunggu Persetujuan Dosen',
            'menunggu_safety_officer' => 'Menunggu Safety Officer',
            'menunggu_kepala_lab' => 'Menunggu Kepala Lab',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            default => 'Tidak Diketahui',
        };
    }

    public function getKategoriResikoLabel(): ?string
    {
        if (!$this->kategori_resiko_dosen) return null;
        
        return match($this->kategori_resiko_dosen) {
            'tinggi' => 'Beresiko Tinggi',
            'sedang' => 'Beresiko Sedang',
            'rendah' => 'Beresiko Rendah',
            default => null,
        };
    }
}