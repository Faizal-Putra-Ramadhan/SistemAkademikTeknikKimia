<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanAlat extends Model
{
    protected $table = 'peminjaman_alats';

    protected $fillable = [
        'user_nama',
        'risk_assessment_id',
        'alat_lab_id',
        'daftar_lab_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'batas_waktu_peminjaman',
        'notifikasi_7_hari_terkirim',
        'tanggal_notifikasi_7_hari',
        'deadline_status',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'batas_waktu_peminjaman' => 'datetime',
        'notifikasi_7_hari_terkirim' => 'boolean',
        'tanggal_notifikasi_7_hari' => 'datetime',
    ];

    public function alatLab()
    {
        return $this->belongsTo(AlatLab::class, 'alat_lab_id');
    }

    public function daftarLab()
    {
        return $this->belongsTo(DaftarLab::class, 'daftar_lab_id'); // kalau nanti butuh
    }

    public function riskAssessment()
    {
        return $this->belongsTo(RiskAssessment::class, 'risk_assessment_id');
    }
}
