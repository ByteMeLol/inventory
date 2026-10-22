<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryAlert extends Model
{
    protected $fillable = ['company_id', 'inventory_id', 'warehouse_location_id', 'type', 'status', 'message', 'resolved_at'];
    protected $casts = ['resolved_at' => 'datetime'];

    public function inventory(): BelongsTo { return $this->belongsTo(Inventory::class); }
    public function location(): BelongsTo { return $this->belongsTo(WarehouseLocation::class, 'warehouse_location_id'); }
}
