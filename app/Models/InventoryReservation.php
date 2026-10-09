<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InventoryReservation extends Model
{
    protected $fillable = [
        'company_id', 'inventory_id', 'warehouse_location_id', 'reference_type',
        'reference_id', 'quantity', 'status', 'expires_at', 'created_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:6',
        'expires_at' => 'datetime',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'warehouse_location_id');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
