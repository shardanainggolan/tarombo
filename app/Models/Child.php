<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Child extends Model
{
    protected $fillable = [
        'marriage_id',
        'child_id',
        'display_order'
    ];

    public function marriage(): BelongsTo
    {
        return $this->belongsTo(Marriage::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'child_id');
    }
}
