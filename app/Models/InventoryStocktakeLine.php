<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryStocktakeLine extends Model
{
    protected $fillable = ['stocktake_id', 'inventory_id', 'expected_quantity', 'counted_quantity', 'variance', 'reason'];
    protected $casts = ['expected_quantity' => 'decimal:6', 'counted_quantity' => 'decimal:6', 'variance' => 'decimal:6'];
}
