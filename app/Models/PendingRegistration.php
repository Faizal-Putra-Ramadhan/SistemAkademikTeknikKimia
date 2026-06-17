<?php

// app/Models/PendingRegistration.php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PendingRegistration extends Model
{
    protected $fillable = [
        'nama',
        'phone',
        'email',
        'password',
        'role',
        'nomor_identitas',
        'verification_token',
        'token_expires_at',
        'is_verified',
        'parent_user_id',
        'is_primary',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'is_verified' => 'boolean',
        'is_primary' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Check if token is expired
     */
    public function isTokenExpired()
    {
        return Carbon::now()->isAfter($this->token_expires_at);
    }

    /**
     * Check if token is valid
     */
    public function isTokenValid($token)
    {
        return $this->verification_token === $token && ! $this->isTokenExpired();
    }

    /**
     * Check if token masih valid (tidak kadaluarsa dan belum diverifikasi)
     */
    public function isTokenValidAndNotVerified()
    {
        return ! $this->is_verified && $this->token_expires_at > now();
    }
}
