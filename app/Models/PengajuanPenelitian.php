<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPenelitian extends Model
{
    protected $table = 'pengajuan_penelitians';

    protected $fillable = [
        'user_nama', 'daftar_lab_id', 'judul_penelitian', 'deskripsi',
        'tanggal_mulai', 'tanggal_selesai', 'dosen_pembimbing', 'status'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date'
    ];

    public function daftarLab()
    {
        return $this->belongsTo(DaftarLab::class, 'daftar_lab_id');
    }
}