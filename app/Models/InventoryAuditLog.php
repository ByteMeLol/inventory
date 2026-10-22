<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryAuditLog extends Model
{
    protected $fillable = [
        'company_id', 'inventory_id', 'user_id', 'action', 'quantity',
        'before_state', 'after_state', 'metadata',
    ];

    protected $casts = [
        'quantity' => 'decimal:6',
        'before_state' => 'array',
        'after_state' => 'array',
        'metadata' => 'array',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
