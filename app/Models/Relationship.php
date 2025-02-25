<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    protected $fillable = [
        'individual_id', 'related_individual_id', 'relationship_type'
    ];

    public function individual()
    {
        return $this->belongsTo(Individual::class);
    }

    public function relatedIndividual()
    {
        return $this->belongsTo(Individual::class, 'related_individual_id');
    }
}
