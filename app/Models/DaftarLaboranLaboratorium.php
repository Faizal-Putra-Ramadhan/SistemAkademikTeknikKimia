<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarLaboranLaboratorium extends Model
{
    use HasFactory;

    protected $table = 'daftar_laboran_laboratoriums';

    protected $fillable = ['Laboratorium', 'Nama_Laboran', 'UserID', 'Phone', 'Email', 'Role_User'];

    // Many-to-many relationship dengan DaftarLab melalui tabel pivot
    public function laboratoriums()
    {
        return $this->belongsToMany(DaftarLab::class, 'laboran_laboratorium', 'user_id', 'daftar_lab_id', 'UserID', 'id');
    }

    // Backward compatibility: relasi lama (jika masih digunakan)
    public function daftarLab()
    {
        return $this->belongsTo(DaftarLab::class, 'Laboratorium', 'Nama_Laboratorium');
    }

    public function lab()
    {
        return $this->belongsTo(DaftarLab::class, 'daftar_lab_id');
    }
}
