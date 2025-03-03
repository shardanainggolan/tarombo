<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CachedRelationship extends Model
{
    protected $fillable = [
        'from_person_id',
        'to_person_id',
        'relationship_pattern_id',
        'partuturan_term_id',
    ];

    public function fromPerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'from_person_id');
    }

    public function toPerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'to_person_id');
    }

    public function relationshipPattern(): BelongsTo
    {
        return $this->belongsTo(RelationshipPattern::class);
    }

    public function partuturanTerm(): BelongsTo
    {
        return $this->belongsTo(PartuturanTerm::class);
    }
}
