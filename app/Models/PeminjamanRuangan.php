<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanRuangan extends Model
{
    protected $table = 'peminjaman_ruangans';

    protected $fillable = [
        'user_id',
        'user_nama',
        'daftar_lab_id',
        'tanggal',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'keperluan',
        'status',
        'laboran_id',
        'persetujuan_laboran',
        'catatan_laboran',
        'tanggal_persetujuan_laboran',
        'kepala_lab_id',
        'persetujuan_kepala_lab',
        'catatan_kepala_lab',
        'tanggal_persetujuan_kepala_lab',
        'kaprodi_id',
        'notifikasi_kaprodi',
        'tanggal_notifikasi_kaprodi',
        'tanggal_kembali',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_selesai' => 'date',
        'persetujuan_laboran' => 'boolean',
        'persetujuan_kepala_lab' => 'boolean',
        'tanggal_persetujuan_laboran' => 'datetime',
        'tanggal_persetujuan_kepala_lab' => 'datetime',
        'notifikasi_kaprodi' => 'boolean',
        'tanggal_notifikasi_kaprodi' => 'datetime',
        'tanggal_kembali' => 'datetime',
    ];

    public function daftarLab()
    {
        return $this->belongsTo(DaftarLab::class , 'daftar_lab_id');
    }

    public function laboran()
    {
        return $this->belongsTo(DaftarUser::class , 'laboran_id');
    }

    public function kepalaLab()
    {
        return $this->belongsTo(DaftarUser::class , 'kepala_lab_id');
    }

    public function kaprodi()
    {
        return $this->belongsTo(DaftarUser::class , 'kaprodi_id');
    }

    // Helper untuk status badge (Digunakan di berbagai dashboard)
    public function getStatusLabel()
    {
        switch ($this->status) {
            case 'menunggu':
                return 'Menunggu Laboran';
            case 'disetujui_laboran':
            case 'menunggu_kepala_lab':
                return 'Menunggu Kepala Lab';
            case 'menunggu_kaprodi':
                return 'Menunggu Kaprodi';
            case 'disetujui':
            case 'disetujui_final':
                return 'Disetujui';
            case 'dikembalikan':
                return 'Selesai';
            case 'ditolak':
                return 'Ditolak';
            default:
                return ucwords(str_replace('_', ' ', $this->status));
        }
    }

    public function getStatusColor()
    {
        switch ($this->status) {
            case 'menunggu':
                return 'warning';
            case 'disetujui_laboran':
            case 'menunggu_kepala_lab':
                return 'info';
            case 'menunggu_kaprodi':
                return 'primary';
            case 'disetujui':
            case 'disetujui_final':
                return 'success';
            case 'dikembalikan':
                return 'secondary';
            case 'ditolak':
                return 'danger';
            default:
                return 'secondary';
        }
    }
}
