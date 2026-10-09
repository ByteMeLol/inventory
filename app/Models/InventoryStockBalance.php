<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryStockBalance extends Model
{
    protected $fillable = [
        'company_id', 'inventory_id', 'warehouse_location_id', 'on_hand', 'reserved',
    ];

    protected $casts = ['on_hand' => 'decimal:6', 'reserved' => 'decimal:6'];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'warehouse_location_id');
    }

    public function getAvailableAttribute(): string
    {
        return number_format((float) $this->on_hand - (float) $this->reserved, 6, '.', '');
    }
}
