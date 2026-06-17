<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DaftarLab extends Model
{
    protected $table = 'daftar_labs';

    use HasFactory;

    protected $fillable = [
        'Nama_Laboratorium',
        'floor',
        'lab_type',
        'stock_group_id',
        'Kepala_Labolatorium',
        'Admin_Laboratorium',
        'Safety_Officer',
        'email_lab',
    ];

    public function stockGroup(): BelongsTo
    {
        return $this->belongsTo(StockGroup::class , 'stock_group_id');
    }

    public function alatLabs()
    {
        return $this->hasMany(AlatLab::class , 'stock_group_id', 'stock_group_id')
            ->where(function ($query) {
            $query->whereNull('daftar_lab_id')
                ->orWhere('daftar_lab_id', $this->id);
        });
    }

    // app/Models/DaftarLab.php
    public function peminjamanRuangans()
    {
        return $this->hasMany(PeminjamanRuangan::class , 'daftar_lab_id');
    }

    // Many-to-many relationship dengan DaftarLaboranLaboratorium
    public function laborans()
    {
        return $this->belongsToMany(DaftarLaboranLaboratorium::class , 'laboran_laboratorium', 'daftar_lab_id', 'user_id', 'id', 'UserID');
    }

    // Backward compatibility: relasi lama (jika masih digunakan)
    public function laboransOld(): HasMany
    {
        // Berdasarkan controller Anda, relasi menggunakan kolom 'Laboratorium' (Nama Lab)
        // sebagai kunci penghubung, bukan ID.
        return $this->hasMany(DaftarLaboranLaboratorium::class , 'Laboratorium', 'Nama_Laboratorium');
    }

    public function pengajuanPenelitians()
    {
        return $this->hasMany(PengajuanPenelitian::class , 'daftar_lab_id');
    }

    public function scopePenelitian($query)
    {
        return $query->where('lab_type', 'penelitian');
    }

    public function scopePendidikan($query)
    {
        return $query->where('lab_type', 'pendidikan');
    }
}
