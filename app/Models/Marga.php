<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marga extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function people(): HasMany
    {
        return $this->hasMany(Person::class);
    }
}
