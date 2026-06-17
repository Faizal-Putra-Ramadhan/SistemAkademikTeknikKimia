<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BebasLabApproval extends Model
{
    protected $table = 'bebas_lab_approvals';

    protected $fillable = [
        'bebas_lab_request_id',
        'daftar_lab_id',
        'laboran_user_id',
        'laboran_nama',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(BebasLabRequest::class, 'bebas_lab_request_id');
    }

    public function lab(): BelongsTo
    {
        return $this->belongsTo(DaftarLab::class, 'daftar_lab_id');
    }
}
