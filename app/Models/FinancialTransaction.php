<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FinancialTransaction extends Model
{
    protected $fillable = ['company_id', 'finance_category_id', 'source_type', 'source_id', 'type', 'payment_method', 'amount', 'transaction_date', 'reference', 'status', 'description'];

    protected $casts = ['amount' => 'decimal:2', 'transaction_date' => 'date'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FinanceCategory::class, 'finance_category_id');
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }
}
