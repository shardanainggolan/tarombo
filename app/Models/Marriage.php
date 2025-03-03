<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marriage extends Model
{
    protected $fillable = [
        'husband_id',
        'wife_id',
        'marriage_date',
        'divorce_date',
        'is_current',
        'marriage_order',
        'notes',
    ];

    protected $casts = [
        'marriage_date' => 'date',
        'divorce_date' => 'date',
        'is_current' => 'boolean',
    ];

    public function husband(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'husband_id');
    }

    public function wife(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'wife_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ParentChild::class);
    }

    // Get all children from this marriage
    public function getChildrenAttribute()
    {
        return Person::whereHas('parents', function ($query) {
            $query->where('parent_child.marriage_id', $this->id);
        })->get();
    }
}
