<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    //
    protected $fillable = [
        'company_id',
        'sku',
        'description',
        'category',
        'category_id',
        'unit_id',
        'barcode',
        'unit_price',
        'buying_price',
        'selling_price',
        'stock_count',
        'safety_threshold',
        'is_active',
    ];

    // custome table inventory instead of inventories
    protected $table = 'inventory';

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function stockBalances(): HasMany
    {
        return $this->hasMany(InventoryStockBalance::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(InventoryReservation::class);
    }

    public function categoryRelation()
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id');
    }

    public function unit()
    {
        return $this->belongsTo(InventoryUnit::class);
    }
}
