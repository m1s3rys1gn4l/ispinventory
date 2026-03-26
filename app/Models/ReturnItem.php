<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    protected $fillable = [
        'issue_item_id',
        'return_date',
        'quantity',
        'condition',
        'notes',
    ];

    protected $casts = [
        'return_date' => 'date',
    ];

    public function issueItem(): BelongsTo
    {
        return $this->belongsTo(IssueItem::class);
    }
}
