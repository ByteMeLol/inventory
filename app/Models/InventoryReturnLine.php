<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryReturnLine extends Model
{
    protected $fillable = ['company_id', 'inventory_id', 'warehouse_location_id', 'direction', 'reference_number', 'customer_id', 'supplier_id', 'quantity', 'unit_cost', 'reason', 'created_by'];
    protected $casts = ['quantity' => 'decimal:6', 'unit_cost' => 'decimal:6'];
    public function inventory(): BelongsTo { return $this->belongsTo(Inventory::class); }
}
