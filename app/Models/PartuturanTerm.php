<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartuturanTerm extends Model
{
    protected $fillable = [
        'term',
        'category_id',
        'description',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PartuturanCategory::class, 'category_id');
    }

    public function rules(): HasMany
    {
        return $this->hasMany(PartuturanRule::class);
    }

    public function cachedRelationships(): HasMany
    {
        return $this->hasMany(CachedRelationship::class);
    }
}
