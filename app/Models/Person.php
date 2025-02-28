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
        'is_boru_line' => 'boolean',
        'position' => 'array'
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

    public function getMargaAncestors()
    {
        $ancestors = collect();
        $current = $this;
        
        while ($current->father) {
            $ancestors->push($current->father);
            $current = $current->father;
        }
        
        return $ancestors;
    }

    public function getMargaGenerationDistance(Person $other)
    {
        $myAncestors = $this->getMargaAncestors();
        $otherAncestors = $other->getMargaAncestors();
        
        $common = $myAncestors->intersect($otherAncestors)->first();
        
        return [
            'generation' => $myAncestors->search($common) - $otherAncestors->search($common),
            'is_direct' => !!$common
        ];
    }

    public function getFamilyTreeAttribute()
    {
        return [
            'ancestors' => $this->getAncestors(),
            'descendants' => $this->getDescendants(),
            'siblings' => $this->getFullSiblings(),
            'inlaws' => $this->getInLaws()
        ];
    }

    public function getAncestors($maxDepth = 5)
    {
        return $this->traverseFamily('parents', $maxDepth);
    }

    public function getDescendants($maxDepth = 3)
    {
        return $this->traverseFamily('children', $maxDepth);
    }

    private function traverseFamily($direction, $maxDepth, $currentDepth = 0, $results = [])
    {
        if ($currentDepth >= $maxDepth) return $results;

        $relation = $this->{$direction};
        foreach ($relation as $relative) {
            $results[] = [
                'person' => $relative,
                'depth' => $currentDepth,
                'path' => $direction
            ];
            $results = $relative->traverseFamily(
                $direction, 
                $maxDepth, 
                $currentDepth + 1, 
                $results
            );
        }
        return $results;
    }

    public function getMargaDistance(Person $other)
    {
        $myLine = $this->getAncestorsLine();
        $otherLine = $other->getAncestorsLine();
        
        $commonAncestor = $myLine->intersect($otherLine)->first();
        
        return [
            'distance' => $myLine->search($commonAncestor) - $otherLine->search($commonAncestor),
            'is_same_line' => !is_null($commonAncestor)
        ];
    }

    public function getHierarchyAttribute()
    {
        return [
            'lineage' => $this->getLineage(),
            'generation' => $this->getGenerationLevel(),
            'marga_path' => $this->getMargaPath(),
            'sibling_position' => $this->getSiblingPosition()
        ];
    }

    private function getLineage()
    {
        $lineage = [];
        $current = $this;
        
        while($current->father) {
            array_unshift($lineage, [
                'id' => $current->father_id,
                'marga' => $current->father->marga,
                'gender' => 'male'
            ]);
            $current = $current->father;
        }
        
        return $lineage;
    }

    private function getGenerationLevel()
    {
        $level = 0;
        $current = $this;
        
        while($current->father) {
            $level++;
            $current = $current->father;
        }
        
        return $level;
    }
}
