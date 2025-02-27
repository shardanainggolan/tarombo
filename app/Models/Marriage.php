<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Marriage extends Model
{
    protected $fillable = [
        'husband_id',
        'wife_id',
        'marriage_date',
        'divorce_date',
        'is_active'
    ];

    protected $casts = [
        'marriage_date' => 'date',
        'divorce_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function husband(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'husband_id');
    }

    public function wife(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'wife_id');
    }

    public function spouse(): BelongsTo
    {
        return $this->husband()->exists() ? 
            $this->husband() : 
            $this->wife();
    }
}
