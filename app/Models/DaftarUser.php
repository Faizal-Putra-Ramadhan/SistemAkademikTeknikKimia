<?php
// app/Models/DaftarUser.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class DaftarUser extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'daftar_users';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'Nama',
        'Phone',
        'Email',
        'UserID',
        'Password',
        'Role_User',
        'foto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'Password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Override password accessor untuk Laravel Auth
     * Karena di database field password bernama 'Password' bukan 'password'
     */
    public function getAuthPassword()
    {
        return $this->Password;
    }

    /**
     * Override identifier untuk Laravel Auth
     * Menggunakan UserID sebagai username
     */
    public function getAuthIdentifierName()
    {
        return 'UserID';
    }

    /**
     * Get the identifier that will be stored in the session.
     */
    public function getAuthIdentifier()
    {
        return $this->UserID;
    }

    /**
     * Check if user has specific role
     */
    public function hasRole($role)
    {
        return strtolower($this->Role_User) === strtolower($role);
    }

    /**
     * Check if user is Admin
     */
    public function isAdmin()
    {
        return $this->hasRole('Admin');
    }

    public function isSafetyOfficer()
    {
        return $this->hasRole('Safety Officer');
    }

    public function isKepalaLaboratorium()
    {
        return $this->hasRole('Kepala Laboratorium');
    }

    /**
     * Check if user is Dosen
     */
    public function isDosen()
    {
        return $this->hasRole('Dosen');
    }

    /**
     * Check if user is Mahasiswa
     */
    public function isMahasiswa()
    {
        return $this->hasRole('Mahasiswa');
    }

    /**
     * Check if user is Laboran
     */
    public function isLaboran()
    {
        return $this->hasRole('Laboran');
    }

    /**
     * Get user's full photo URL
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        
        // Return default avatar if no photo
        return asset('images/default-avatar.png');
    }

    /**
     * Relationship dengan tabel laboran (jika user adalah laboran)
     */
    public function laboranProfile()
    {
        return $this->hasOne(DaftarLaboranLaboratorium::class, 'UserID', 'UserID');
    }

    /**
     * Get user's display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->Nama;
    }

    /**
     * Scope untuk filter berdasarkan role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('Role_User', $role);
    }

    /**
     * Scope untuk filter Laboran
     */
    public function scopeLaboran($query)
    {
        return $query->where('Role_User', 'Laboran');
    }

    /**
     * Scope untuk filter Dosen
     */
    public function scopeDosen($query)
    {
        return $query->where('Role_User', 'Dosen');
    }

    /**
     * Scope untuk filter Mahasiswa
     */
    public function scopeMahasiswa($query)
    {
        return $query->where('Role_User', 'Mahasiswa');
    }

    public function scopeSafetyOfficer($query)
    {
        return $query->where('Role_User', 'Safety Officer');
    }

    public function scopeKepalaLaboratorium($query)
    {
        return $query->where('Role_User', 'Kepala Laboratorium');
    }

    /**
     * Scope untuk filter Admin
     */
    public function scopeAdmin($query)
    {
        return $query->where('Role_User', 'Admin');
    }
}