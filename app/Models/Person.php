<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    protected $table = 'people';
    
    protected $fillable = [
        'user_id',
        'father_id',
        'mother_id',
        'marga',
        'gender',
        'birth_date',
        'death_date',
        'is_boru_line'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
        'is_boru_line' => 'boolean'
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function father(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'father_id');
    }

    public function mother(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'mother_id');
    }

    public function marriages(): HasMany
    {
        return $this->hasMany(Marriage::class, 'husband_id')
            ->orWhere('wife_id', $this->id);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Child::class, 'child_id');
    }

    // Helper
    public function getCurrentSpouseAttribute()
    {
        return $this->marriages()
            ->where('is_active', true)
            ->first()
            ?->spouse();
    }
}
