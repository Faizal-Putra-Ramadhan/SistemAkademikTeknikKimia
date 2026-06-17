<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlatLab extends Model
{
    protected $table = 'alat_labs';

    protected $fillable = [
        'daftar_lab_id',
        'stock_group_id',
        'nama_alat',
        'deskripsi',
        'jumlah_tersedia',
        'foto',
    ];

    // app/Models/AlatLab.php
    public function daftarLab()
    {
        return $this->belongsTo(DaftarLab::class, 'daftar_lab_id');
    }

    public function stockGroup()
    {
        return $this->belongsTo(StockGroup::class, 'stock_group_id');
    }

    public function peminjamanAlats()
    {
        return $this->hasMany(PeminjamanAlat::class, 'alat_lab_id');
    }
}
