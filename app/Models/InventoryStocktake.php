<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryStocktake extends Model
{
    protected $fillable = ['company_id', 'warehouse_location_id', 'reference_number', 'status', 'created_by', 'approved_by', 'approved_at'];
    protected $casts = ['approved_at' => 'datetime'];
    public function lines(): HasMany { return $this->hasMany(InventoryStocktakeLine::class, 'stocktake_id'); }
    public function approvals(): HasMany { return $this->hasMany(InventoryApproval::class, 'approvable_id')->where('approvable_type', self::class); }
}
