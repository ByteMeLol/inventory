<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //
    protected $fillable = [
        'customer_id',
        'company_id',
        'inventory_id',
        'invoice_number',
        'billing_terms',
        'payment_method',
        'payment_reference',
        'quantity',
        'unit_price',
        'discount_amount',
        'discount_reason',
        'discount_given_by',
        'item_summary',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function discountGivenBy()
    {
        return $this->belongsTo(User::class, 'discount_given_by');
    }
}
