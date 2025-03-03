<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartuturanRule extends Model
{
    protected $fillable = [
        'ego_gender',
        'relative_gender',
        'relationship_pattern_id',
        'partuturan_term_id',
    ];

    public function relationshipPattern(): BelongsTo
    {
        return $this->belongsTo(RelationshipPattern::class);
    }

    public function partuturanTerm(): BelongsTo
    {
        return $this->belongsTo(PartuturanTerm::class);
    }

    // Helper method to find the applicable rule
    public static function findApplicableRule(string $pattern, string $egoGender, string $relativeGender)
    {
        $relationshipPattern = RelationshipPattern::where('pattern', $pattern)->first();
        
        if (!$relationshipPattern) {
            return null;
        }
        
        return self::where('relationship_pattern_id', $relationshipPattern->id)
            ->where('ego_gender', $egoGender)
            ->where('relative_gender', $relativeGender)
            ->first();
    }
}
