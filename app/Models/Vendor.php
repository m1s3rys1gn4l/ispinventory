<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'address',
        'notes',
    ];

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}
