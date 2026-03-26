<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class IssueVoucher extends Model
{
    protected $fillable = [
        'employee_id',
        'issued_by',
        'issue_date',
        'purpose',
        'expected_return_date',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expected_return_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(IssueItem::class);
    }
}
