<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'unit',
        'min_stock',
        'track_serial',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'track_serial' => 'boolean',
        'is_active' => 'boolean',
        'min_stock' => 'float',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockTransactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function currentStock(): float
    {
        return (float) $this->stockTransactions()
            ->selectRaw("COALESCE(SUM(CASE WHEN transaction_type IN ('IN', 'RETURN', 'ADJUSTMENT') THEN quantity ELSE -quantity END), 0) as balance")
            ->value('balance');
    }

    public static function currentStockById(int $productId): float
    {
        return (float) StockTransaction::query()
            ->where('product_id', $productId)
            ->selectRaw("COALESCE(SUM(CASE WHEN transaction_type IN ('IN', 'RETURN', 'ADJUSTMENT') THEN quantity ELSE -quantity END), 0) as balance")
            ->value('balance');
    }
}
