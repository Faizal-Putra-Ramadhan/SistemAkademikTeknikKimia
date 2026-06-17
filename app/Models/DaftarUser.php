<?php

// app/Models/DaftarUser.php

namespace App\Models;

use App\Models\Traits\HasLinkedAccounts;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class DaftarUser extends Authenticatable
{
    use HasFactory, HasLinkedAccounts, Notifiable;

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
        'parent_user_id', // BARU
        'is_primary', // BARU
        'foto',
        'Nomor_Identitas',
        'status',
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
        'is_primary' => 'boolean', // BARU
    ];

    /**
     * Override password accessor untuk Laravel Auth
     * Karena di database field password bernama 'Password' bukan 'password'
     */
    public function getAuthPassword()
    {
        return $this->Password;
    }

    public function laborans()
    {
        return $this->hasMany(DaftarLaboranLaboratorium::class, 'UserID', 'UserID');
    }

    /**
     * Override identifier untuk Laravel Auth
     * Menggunakan UserID sebagai username
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the identifier that will be stored in the session.
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get user's roles (many-to-many relationship)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    /**
     * Get primary role (for backward compatibility)
     */
    public function primaryRole()
    {
        return $this->roles()->wherePivot('is_primary', true)->first();
    }

    /**
     * Check if user has specific role (supports both old Role_User and new roles)
     */
    public function hasRole($role)
    {
        // Check in new roles table
        $hasRole = $this->roles()->where('name', $role)->exists();

        // Fallback to old Role_User for backward compatibility
        if (! $hasRole && $this->Role_User) {
            $hasRole = strtolower($this->Role_User) === strtolower($role);
        }

        return $hasRole;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles)
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Assign role to user
     */
    public function assignRole($roleName, $isPrimary = false)
    {
        $role = Role::where('name', $roleName)->first();

        if (! $role) {
            return false;
        }

        // If setting as primary, unset other primary roles
        if ($isPrimary) {
            $this->roles()->updateExistingPivot(
                $this->roles()->pluck('id'),
                ['is_primary' => false]
            );
        }

        // Attach role if not already attached
        if (! $this->roles()->where('role_id', $role->id)->exists()) {
            $this->roles()->attach($role->id, ['is_primary' => $isPrimary]);
        } else {
            // Update is_primary if already exists
            $this->roles()->updateExistingPivot($role->id, ['is_primary' => $isPrimary]);
        }

        return true;
    }

    /**
     * Remove role from user
     */
    public function removeRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();

        if (! $role) {
            return false;
        }

        $this->roles()->detach($role->id);

        return true;
    }

    /**
     * Sync roles (replace all roles with given roles)
     */
    public function syncRoles(array $roleNames, $primaryRoleName = null)
    {
        $roleIds = Role::whereIn('name', $roleNames)->pluck('id');

        $syncData = [];
        foreach ($roleIds as $roleId) {
            $role = Role::find($roleId);
            $isPrimary = ($primaryRoleName && $role->name === $primaryRoleName);
            $syncData[$roleId] = ['is_primary' => $isPrimary];
        }

        $this->roles()->sync($syncData);

        // Update Role_User for backward compatibility
        if ($primaryRoleName) {
            $this->update(['Role_User' => $primaryRoleName]);
        } elseif ($roleIds->isNotEmpty()) {
            $firstRole = Role::find($roleIds->first());
            $this->update(['Role_User' => $firstRole->name]);
        }
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

    public function isKaprodi()
    {
        return $this->hasRole('Kaprodi');
    }

    /**
     * Check if user is Peneliti Eksternal
     */
    public function isPenelitiEksternal()
    {
        return $this->hasRole('Peneliti Eksternal');
    }

    /**
     * Get all role names as array
     */
    public function getRoleNamesAttribute()
    {
        $roles = $this->roles()->pluck('name')->toArray();

        // Include Role_User if not already in roles
        if ($this->Role_User && ! in_array($this->Role_User, $roles)) {
            $roles[] = $this->Role_User;
        }

        return array_unique($roles);
    }

    /**
     * Get user's full photo URL
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/'.$this->foto);
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
     * Scope: user yang punya role Laboran (Role_User atau many-to-many roles).
     */
    public function scopeWithLaboranRole($query)
    {
        return $query->where(function ($q) {
            $q->where('Role_User', 'Laboran')
                ->orWhereHas('roles', function ($q2) {
                    $q2->where('name', 'Laboran');
                }
                );
        });
    }

    /**
     * Scope untuk filter Dosen (hanya Role_User = Dosen)
     */
    public function scopeDosen($query)
    {
        return $query->where('Role_User', 'Dosen');
    }

    /**
     * Scope: user yang punya role Dosen (Role_User atau many-to-many roles).
     * Dipakai untuk dropdown dosen pembimbing agar user multi-role (Dosen + lain) ikut muncul.
     */
    public function scopeWithDosenRole($query)
    {
        return $query->where(function ($q) {
            $q->where('Role_User', 'Dosen')
                ->orWhereHas('roles', function ($q2) {
                    $q2->where('name', 'Dosen');
                }
                );
        });
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

    public function scopeKaprodi($query)
    {
        return $query->where('Role_User', 'Kaprodi');
    }

    /**
     * Scope untuk filter Peneliti Eksternal
     */
    public function scopePenelitiEksternal($query)
    {
        return $query->where('Role_User', 'Peneliti Eksternal');
    }

    /**
     * Scope: Find laborans for a specific lab.
     * Supports both old schema (Laboratorium column) and new schema (pivot table).
     */
    public function scopeLaboranForLab($query, $lab)
    {
        return $query->withLaboranRole()
            ->where(function ($q) use ($lab) {
                // New schema: pivot table laboran_laboratorium
                $q->whereHas('laborans', function ($q2) use ($lab) {
                    $q2->whereHas('laboratoriums', function ($q3) use ($lab) {
                        $q3->where('daftar_labs.id', $lab->id);
                    }
                    );
                }
                )
                            // Backward compatibility: old schema Laboratorium column
                    ->orWhereHas('laborans', function ($q2) use ($lab) {
                        $q2->where('Laboratorium', $lab->Nama_Laboratorium);
                    }
                    );
            });
    }

    /**
     * Get a robust email address for notifications.
     * Prioritizes: record email, laboran profile email, then main account email.
     */
    public function getNotificationEmail(): ?string
    {
        // 1. Check current record email
        if ($this->Email) {
            return $this->Email;
        }

        // 2. Check laboran profile email
        $laboranProfile = $this->laboranProfile;
        if ($laboranProfile && $laboranProfile->Email) {
            return $laboranProfile->Email;
        }

        // 3. Check main account email (linked account)
        $mainAccount = $this->getMainAccount();
        if ($mainAccount && $mainAccount !== $this && $mainAccount->Email) {
            return $mainAccount->Email;
        }

        return null;
    }
}
