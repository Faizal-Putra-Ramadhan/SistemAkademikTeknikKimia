<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockGroup extends Model
{
    protected $table = 'stock_groups';

    protected $fillable = [
        'floor',
        'lab_type',
    ];

    public function labs(): HasMany
    {
        return $this->hasMany(DaftarLab::class, 'stock_group_id');
    }

    public function alatLabs(): HasMany
    {
        return $this->hasMany(AlatLab::class, 'stock_group_id');
    }
}
