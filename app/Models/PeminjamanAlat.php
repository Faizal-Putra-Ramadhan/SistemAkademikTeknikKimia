<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanAlat extends Model
{
    protected $table = 'peminjaman_alats';
    protected $fillable = ['user_nama', 'alat_lab_id', 'tanggal_pinjam', 'tanggal_kembali', 'status'];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date'
    ];

    public function alatLab()
    {
        return $this->belongsTo(AlatLab::class, 'alat_lab_id');
    }

    public function daftarLab()
    {
        return $this->belongsTo(DaftarLab::class, 'daftar_lab_id'); // kalau nanti butuh
    }

    
}