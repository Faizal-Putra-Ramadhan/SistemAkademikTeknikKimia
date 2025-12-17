<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanRuangan extends Model
{
    protected $table = 'peminjaman_ruangans';

    // INI YANG WAJIB ADA!
    protected $fillable = [
        'user_nama',
        'daftar_lab_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'keperluan',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function daftarLab()
{
    return $this->belongsTo(DaftarLab::class, 'daftar_lab_id');
}

}