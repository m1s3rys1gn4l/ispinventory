<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class IssueItem extends Model
{
    protected $fillable = [
        'issue_voucher_id',
        'product_id',
        'quantity',
    ];

    public function issueVoucher(): BelongsTo
    {
        return $this->belongsTo(IssueVoucher::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(ReturnItem::class);
    }

    public function remainingQty(): float
    {
        $returned = (float) $this->returns()->sum('quantity');
        return max(0, (float) $this->quantity - $returned);
    }
}
