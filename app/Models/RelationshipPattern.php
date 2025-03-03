<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RelationshipPattern extends Model
{
    protected $fillable = [
        'pattern',
        'description',
    ];

    public function rules(): HasMany
    {
        return $this->hasMany(PartuturanRule::class);
    }

    public function cachedRelationships(): HasMany
    {
        return $this->hasMany(CachedRelationship::class);
    }

    // Parse relationship path from pattern string
    public function getPathSegments(): array
    {
        return explode('.', $this->pattern);
    }
}
