<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InventoryApproval extends Model
{
    protected $fillable = ['company_id', 'approvable_type', 'approvable_id', 'action', 'status', 'requested_by', 'reviewed_by', 'comment', 'reviewed_at'];
    protected $casts = ['reviewed_at' => 'datetime'];
    public function approvable(): MorphTo { return $this->morphTo(); }
}
