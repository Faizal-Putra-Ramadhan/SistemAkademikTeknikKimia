<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AktivitasMahasiswa extends Model
{
    protected $table = 'aktivitas_mahasiswas';

    protected $fillable = [
        'user_nama',
        'daftar_lab_id',
        'jenis_aktivitas',
        'keterangan',
        'waktu' // kita pakai kolom 'waktu' yang sudah ada
    ];

    // NONAKTIFKAN timestamps karena tidak ada created_at & updated_at
    public $timestamps = false;

    // Pastikan kolom waktu di-treat sebagai datetime
    protected $dates = ['waktu'];

    public function daftarLab()
    {
        return $this->belongsTo(DaftarLab::class, 'daftar_lab_id');
    }
}