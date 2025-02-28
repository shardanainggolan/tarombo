<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartuturanRule extends Model
{
    protected $fillable = [
        'relationship_code',
        'term',
        'description',
        'gender',
        'priority'
    ];
}
