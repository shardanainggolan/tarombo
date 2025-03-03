<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentChild extends Model
{
    protected $table = 'parent_child';

    protected $fillable = [
        'parent_id',
        'child_id',
        'marriage_id',
        'is_biological',
        'birth_order',
    ];

    protected $casts = [
        'is_biological' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'parent_id');
    }

    public function child(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'child_id');
    }

    public function marriage(): BelongsTo
    {
        return $this->belongsTo(Marriage::class);
    }
}
