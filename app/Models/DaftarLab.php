<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarLab extends Model
{
    protected $table = 'daftar_labs';
    use HasFactory;
    protected $fillable = ['Nama_Laboratorium', 'Kepala_Labolatorium', 'Admin_Laboratorium', 'Safety_Officer', 'email_lab'];
    public function alatLabs()
    {
        return $this->hasMany(AlatLab::class, 'daftar_lab_id');
    }

    // app/Models/DaftarLab.php
public function peminjamanRuangans()
{
    return $this->hasMany(PeminjamanRuangan::class, 'daftar_lab_id');
}
public function pengajuanPenelitians()
{
    return $this->hasMany(PengajuanPenelitian::class, 'daftar_lab_id');
}
}
