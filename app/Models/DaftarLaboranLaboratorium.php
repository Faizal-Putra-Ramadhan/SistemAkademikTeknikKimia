<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarLaboranLaboratorium extends Model
{
    use HasFactory;
    protected $table = 'daftar_laboran_laboratoriums';

    protected $fillable = ['Laboratorium', 'Nama_Laboran', 'UserID', 'Phone', 'Email', 'Role_User'];

    public function daftarLab()
    {
        return $this->belongsTo(DaftarLab::class, 'Laboratorium', 'Nama_Laboratorium');
    }
}
