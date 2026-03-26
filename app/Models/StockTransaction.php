<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'transaction_type',
        'quantity',
        'transaction_date',
        'reference_type',
        'reference_id',
        'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
