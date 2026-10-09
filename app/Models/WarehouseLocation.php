<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarehouseLocation extends Model
{
    protected $fillable = ['warehouse_id', 'code', 'name', 'type', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function stockBalances(): HasMany
    {
        return $this->hasMany(InventoryStockBalance::class);
    }
}
