<?php

namespace App\Services;

use App\Models\Person;
use App\Models\PartuturanRule;
use App\Models\PartuturanTerm;
use App\Models\RelationshipPattern;
use App\Models\CachedRelationship;
use App\Models\ParentChild;
use Illuminate\Support\Facades\Storage;

class PartuturanService
{
    /**
     * Resolve partuturan term between two people
     */
    public function resolvePartuturanTerm(Person $fromPerson, Person $toPerson)
    {
        // Check cache first
        $cachedRelationship = CachedRelationship::where('from_person_id', $fromPerson->id)
            ->where('to_person_id', $toPerson->id)
            ->with(['partuturanTerm', 'relationshipPattern'])
            ->first();
            
        if ($cachedRelationship) {
            return [
                'term' => $cachedRelationship->partuturanTerm->term,
                'category' => $cachedRelationship->partuturanTerm->category->name,
                'description' => $cachedRelationship->partuturanTerm->description,
                'path' => $cachedRelationship->relationshipPattern->pattern,
            ];
        }
        
        // Calculate relationship path
        $relationshipPath = $this->calculateRelationshipPath($fromPerson, $toPerson);
        
        if (!$relationshipPath) {
            return [
                'term' => null,
                'category' => null,
                'description' => null,
                'path' => null,
            ];
        }
        
        // Find matching pattern
        $pattern = RelationshipPattern::where('pattern', $relationshipPath)->first();
        
        if (!$pattern) {
            return [
                'term' => null,
                'category' => null,
                'description' => null,
                'path' => $relationshipPath,
            ];
        }
        
        // Find rule based on pattern and genders
        $rule = PartuturanRule::where('relationship_pattern_id', $pattern->id)
            ->where('ego_gender', $fromPerson->gender)
            ->where('relative_gender', $toPerson->gender)
            ->with('partuturanTerm.category')
            ->first();
            
        if (!$rule) {
            return [
                'term' => null,
                'category' => null,
                'description' => null,
                'path' => $relationshipPath,
            ];
        }
        
        // Cache this relationship for future use
        CachedRelationship::create([
            'from_person_id' => $fromPerson->id,
            'to_person_id' => $toPerson->id,
            'relationship_pattern_id' => $pattern->id,
            'partuturan_term_id' => $rule->partuturan_term_id,
        ]);
        
        return [
            'term' => $rule->partuturanTerm->term,
            'category' => $rule->partuturanTerm->category->name,
            'description' => $rule->partuturanTerm->description,
            'path' => $relationshipPath,
        ];
    }
    
    /**
     * Get all relationships for a person
     */
    public function getAllRelationships(Person $egoPerson, $search = null, $categoryId = null)
    {
        // Get all cached relationships
        $query = CachedRelationship::where('from_person_id', $egoPerson->id)
            ->with(['toPerson', 'partuturanTerm.category', 'relationshipPattern']);
            
        if ($categoryId) {
            $query->whereHas('partuturanTerm', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }
        
        $relationships = $query->get();
        
        // Format the results
        $result = $relationships->map(function($rel) {
            return [
                'person' => [
                    'id' => $rel->toPerson->id,
                    'first_name' => $rel->toPerson->first_name,
                    'last_name' => $rel->toPerson->last_name,
                    'gender' => $rel->toPerson->gender,
                    'marga' => $rel->toPerson->marga->name,
                    'photo_url' => $rel->toPerson->photo_url ? url(Storage::url($rel->toPerson->photo_url)) : null,
                ],
                'term' => $rel->partuturanTerm->term,
                'category' => $rel->partuturanTerm->category->name,
                'path' => $rel->relationshipPattern->pattern,
            ];
        });
        
        // Filter by search if provided
        if ($search) {
            $search = strtolower($search);
            $result = $result->filter(function($item) use ($search) {
                return str_contains(strtolower($item['person']['first_name']), $search) ||
                       str_contains(strtolower($item['person']['last_name']), $search) ||
                       str_contains(strtolower($item['term']), $search);
            })->values();
        }
        
        return $result;
    }
    
    /**
     * Calculate relationship path between two people
     */
    private function calculateRelationshipPath(Person $fromPerson, Person $toPerson)
    {
        // Check for special "Ito" relationship
        if ($this->isItoRelationship($fromPerson, $toPerson)) {
            if ($fromPerson->gender === 'female' && $toPerson->gender === 'male') {
                return 'brother.older';
            } else if ($fromPerson->gender === 'male' && $toPerson->gender === 'female') {
                return 'sister.younger';
            }
        }
        
        // Direct parent relationships
        if ($this->isDirectParent($fromPerson, $toPerson)) {
            return $toPerson->gender === 'male' ? 'father' : 'mother';
        }
        
        // Direct child relationships
        if ($this->isDirectChild($fromPerson, $toPerson)) {
            return $toPerson->gender === 'male' ? 'son' : 'daughter';
        }
        
        // Sibling relationships
        if ($this->areSiblings($fromPerson, $toPerson)) {
            if ($toPerson->gender === 'male') {
                if ($this->isOlder($toPerson, $fromPerson)) {
                    return 'brother.older';
                } else {
                    return 'brother.younger';
                }
            } else {
                if ($this->isOlder($toPerson, $fromPerson)) {
                    return 'sister.older';
                } else {
                    return 'sister.younger';
                }
            }
        }
        
        // Spouse relationship
        if ($this->areSpouses($fromPerson, $toPerson)) {
            return $toPerson->gender === 'male' ? 'husband' : 'wife';
        }
        
        // Uncle/Aunt relationships (parent's siblings)
        $parentSiblingPath = $this->getParentSiblingPath($fromPerson, $toPerson);
        if ($parentSiblingPath) {
            return $parentSiblingPath;
        }
        
        // Nephew/Niece relationships (sibling's children)
        $siblingsChildPath = $this->getSiblingsChildPath($fromPerson, $toPerson);
        if ($siblingsChildPath) {
            return $siblingsChildPath;
        }
        
        // Cousin relationships
        $cousinPath = $this->getCousinPath($fromPerson, $toPerson);
        if ($cousinPath) {
            return $cousinPath;
        }
        
        // In-law relationships
        $inLawPath = $this->getInLawPath($fromPerson, $toPerson);
        if ($inLawPath) {
            return $inLawPath;
        }
        
        // More distant relationships would be implemented here...
        
        // Default case - no clear relationship found
        return null;
    }
    
    /**
     * Check if special "Ito" relationship applies
     */
    public function isItoRelationship(Person $ego, Person $relative)
    {
        // Check if they have shared parents (siblings)
        $egoParentIds = $ego->parents()->pluck('id')->toArray();
        $relativeParentIds = $relative->parents()->pluck('id')->toArray();
        
        $sharedParents = array_intersect($egoParentIds, $relativeParentIds);
        if (empty($sharedParents)) {
            return false;
        }
        
        // Different genders
        if ($ego->gender === $relative->gender) {
            return false;
        }
        
        // Get birth order information
        $egoParentChild = ParentChild::where('child_id', $ego->id)
            ->whereIn('parent_id', $sharedParents)
            ->first();
            
        $relativeParentChild = ParentChild::where('child_id', $relative->id)
            ->whereIn('parent_id', $sharedParents)
            ->first();
            
        if (!$egoParentChild || !$relativeParentChild) {
            return false;
        }
        
        // Case 1: Female calling older male sibling
        if ($ego->gender === 'female' && $relative->gender === 'male' && 
            $relativeParentChild->birth_order < $egoParentChild->birth_order) {
            return true;
        }
        
        // Case 2: Male calling younger female sibling
        if ($ego->gender === 'male' && $relative->gender === 'female' && 
            $egoParentChild->birth_order < $relativeParentChild->birth_order) {
            return true;
        }
        
        return false;
    }

    /**
     * Check if person B is a direct parent of person A
     */
    private function isDirectParent(Person $personA, Person $personB)
    {
        return $personA->parents()->where('id', $personB->id)->exists();
    }
    
    /**
     * Check if person B is a direct child of person A
     */
    private function isDirectChild(Person $personA, Person $personB)
    {
        return $personB->parents()->where('id', $personA->id)->exists();
    }
    
    /**
     * Check if two people are siblings (share at least one parent)
     */
    private function areSiblings(Person $personA, Person $personB)
    {
        $personAParentIds = $personA->parents()->pluck('id')->toArray();
        $personBParentIds = $personB->parents()->pluck('id')->toArray();
        
        return !empty(array_intersect($personAParentIds, $personBParentIds));
    }
    
    /**
     * Check if person A is older than person B
     */
    private function isOlder(Person $personA, Person $personB)
    {
        // Check if they share parents
        $personAParentIds = $personA->parents()->pluck('id')->toArray();
        $personBParentIds = $personB->parents()->pluck('id')->toArray();
        $sharedParentIds = array_intersect($personAParentIds, $personBParentIds);
        
        if (empty($sharedParentIds)) {
            // If no shared parents, compare birth dates if available
            if ($personA->birth_date && $personB->birth_date) {
                return $personA->birth_date < $personB->birth_date;
            }
            return false;
        }
        
        // Get birth orders
        $parentId = reset($sharedParentIds);
        $personARelation = ParentChild::where('parent_id', $parentId)
            ->where('child_id', $personA->id)
            ->first();
            
        $personBRelation = ParentChild::where('parent_id', $parentId)
            ->where('child_id', $personB->id)
            ->first();
            
        if ($personARelation && $personBRelation) {
            return $personARelation->birth_order < $personBRelation->birth_order;
        }
        
        // Fallback to birth dates
        if ($personA->birth_date && $personB->birth_date) {
            return $personA->birth_date < $personB->birth_date;
        }
        
        return false;
    }
    
    /**
     * Check if two people are currently spouses
     */
    private function areSpouses(Person $personA, Person $personB)
    {
        if ($personA->gender === $personB->gender) {
            return false;
        }
        
        if ($personA->gender === 'male') {
            return $personA->marriagesAsHusband()
                ->where('wife_id', $personB->id)
                ->where('is_current', true)
                ->exists();
        } else {
            return $personA->marriagesAsWife()
                ->where('husband_id', $personB->id)
                ->where('is_current', true)
                ->exists();
        }
    }
    
    /**
     * Get relationship path for parent's siblings (aunts/uncles)
     */
    private function getParentSiblingPath(Person $fromPerson, Person $toPerson)
    {
        // Get person's parents
        $parentIds = $fromPerson->parents()->pluck('id')->toArray();
        
        foreach ($parentIds as $parentId) {
            $parent = Person::find($parentId);
            
            // Check if target person is a sibling of this parent
            $parentParentIds = $parent->parents()->pluck('id')->toArray();
            $toPersonParentIds = $toPerson->parents()->pluck('id')->toArray();
            
            if (!empty(array_intersect($parentParentIds, $toPersonParentIds))) {
                // Target is parent's sibling
                $prefix = $parent->gender === 'male' ? 'father' : 'mother';
                $suffix = $toPerson->gender === 'male' ? 'brother' : 'sister';
                
                return $prefix . '.' . $suffix;
            }
        }
        
        return null;
    }
    
    /**
     * Get relationship path for sibling's children (nephews/nieces)
     */
    private function getSiblingsChildPath(Person $fromPerson, Person $toPerson)
    {
        // Get person's siblings
        $fromPersonParentIds = $fromPerson->parents()->pluck('id')->toArray();
        
        $siblings = Person::whereHas('parents', function($query) use ($fromPersonParentIds) {
                $query->whereIn('parent_id', $fromPersonParentIds);
            })
            ->where('id', '!=', $fromPerson->id)
            ->get();
        
        foreach ($siblings as $sibling) {
            // Check if target person is a child of this sibling
            if ($toPerson->parents()->where('id', $sibling->id)->exists()) {
                // Target is sibling's child
                $prefix = $sibling->gender === 'male' ? 'brother' : 'sister';
                $suffix = $toPerson->gender === 'male' ? 'son' : 'daughter';
                
                return $prefix . '.' . $suffix;
            }
        }
        
        return null;
    }
    
    /**
     * Get relationship path for cousins
     */
    private function getCousinPath(Person $fromPerson, Person $toPerson)
    {
        // This would be a more complex implementation for cousin relationships
        // For brevity, I'm showing a simplified implementation for first cousins only
        
        // Get person's parents
        $fromPersonParents = $fromPerson->parents()->get();
        
        foreach ($fromPersonParents as $parent) {
            // Get parent's siblings
            $parentParentIds = $parent->parents()->pluck('id')->toArray();
            
            $parentSiblings = Person::whereHas('parents', function($query) use ($parentParentIds) {
                    $query->whereIn('parent_id', $parentParentIds);
                })
                ->where('id', '!=', $parent->id)
                ->get();
            
            foreach ($parentSiblings as $parentSibling) {
                // Check if target person is a child of parent's sibling
                if ($toPerson->parents()->where('id', $parentSibling->id)->exists()) {
                    // Target is a first cousin
                    $parentPrefix = $parent->gender === 'male' ? 'father' : 'mother';
                    $siblingType = $parentSibling->gender === 'male' ? 'brother' : 'sister';
                    $childType = $toPerson->gender === 'male' ? 'son' : 'daughter';
                    
                    return $parentPrefix . '.' . $siblingType . '.' . $childType;
                }
            }
        }
        
        return null;
    }
    
    /**
     * Get relationship path for in-laws
     */
    private function getInLawPath(Person $fromPerson, Person $toPerson)
    {
        // Spouse's parent (parent-in-law)
        $spouses = [];
        
        if ($fromPerson->gender === 'male') {
            $spouses = $fromPerson->marriagesAsHusband()
                ->where('is_current', true)
                ->with('wife')
                ->get()
                ->pluck('wife');
        } else {
            $spouses = $fromPerson->marriagesAsWife()
                ->where('is_current', true)
                ->with('husband')
                ->get()
                ->pluck('husband');
        }
        
        foreach ($spouses as $spouse) {
            if ($spouse->parents()->where('id', $toPerson->id)->exists()) {
                // Target is spouse's parent
                return 'spouse.' . ($toPerson->gender === 'male' ? 'father' : 'mother');
            }
        }
        
        // Spouse's sibling (brother/sister-in-law)
        foreach ($spouses as $spouse) {
            $spouseParentIds = $spouse->parents()->pluck('id')->toArray();
            $toPersonParentIds = $toPerson->parents()->pluck('id')->toArray();
            
            if (!empty(array_intersect($spouseParentIds, $toPersonParentIds)) && $toPerson->id !== $spouse->id) {
                // Target is spouse's sibling
                return 'spouse.' . ($toPerson->gender === 'male' ? 'brother' : 'sister');
            }
        }
        
        // Child's spouse (son/daughter-in-law)
        $children = $fromPerson->children()->get();
        
        foreach ($children as $child) {
            if ($child->gender === 'male') {
                $isSpouse = $child->marriagesAsHusband()
                    ->where('wife_id', $toPerson->id)
                    ->where('is_current', true)
                    ->exists();
                    
                if ($isSpouse) {
                    return ($child->gender === 'male' ? 'son' : 'daughter') . '.spouse';
                }
            } else {
                $isSpouse = $child->marriagesAsWife()
                    ->where('husband_id', $toPerson->id)
                    ->where('is_current', true)
                    ->exists();
                    
                if ($isSpouse) {
                    return ($child->gender === 'male' ? 'son' : 'daughter') . '.spouse';
                }
            }
        }
        
        return null;
    }
}