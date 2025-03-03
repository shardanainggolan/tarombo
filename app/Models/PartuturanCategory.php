<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartuturanCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function terms(): HasMany
    {
        return $this->hasMany(PartuturanTerm::class, 'category_id');
    }
}
