<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelationType extends Model
{
    protected $fillable = [
        'relation_name', 'description'
    ];

    // Relasi ke Relation
    public function relations()
    {
        return $this->hasMany(Relation::class, 'relation_type_id');
    }
}
