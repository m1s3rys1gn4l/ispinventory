<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'department',
        'phone',
        'status',
        'notes',
    ];

    public function issueVouchers(): HasMany
    {
        return $this->hasMany(IssueVoucher::class);
    }
}
