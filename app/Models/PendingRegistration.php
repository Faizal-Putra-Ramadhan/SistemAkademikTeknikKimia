<?php
// app/Models/PendingRegistration.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PendingRegistration extends Model
{
    protected $fillable = [
        'nama',
        'phone',
        'email',
        'password',
        'verification_token',
        'token_expires_at',
        'is_verified'
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'is_verified' => 'boolean',
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
        return $this->verification_token === $token && !$this->isTokenExpired();
    }
}