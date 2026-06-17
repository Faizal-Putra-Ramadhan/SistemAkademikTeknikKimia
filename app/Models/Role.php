<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * Get users that have this role
     */
    public function users()
    {
        return $this->belongsToMany(DaftarUser::class, 'user_roles', 'role_id', 'user_id')
            ->withPivot('is_primary')
            ->withTimestamps();
    }
}
