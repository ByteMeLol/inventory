<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    protected $fillable = ['purchase_id', 'inventory_id', 'quantity', 'unit_cost', 'line_total'];

    protected $casts = ['quantity' => 'decimal:6', 'unit_cost' => 'decimal:6', 'line_total' => 'decimal:2'];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }
}
