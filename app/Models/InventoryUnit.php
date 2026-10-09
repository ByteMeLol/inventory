<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryUnit extends Model
{
    protected $fillable = ['company_id', 'name', 'symbol', 'conversion_factor', 'is_active'];

    protected $casts = ['conversion_factor' => 'decimal:6', 'is_active' => 'boolean'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class, 'unit_id');
    }
}
