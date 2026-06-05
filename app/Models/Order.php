<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'store_id',
        'items',
        'subtotal',
        'service_charge',
        'vat',
        'tip',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'refund_reason',
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'float',
        'service_charge' => 'float',
        'vat' => 'float',
        'tip' => 'float',
        'total' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
