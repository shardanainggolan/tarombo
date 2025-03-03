<?php

namespace App\Services;

use App\Models\Person;
use App\Models\FamilyGroup;
use App\Models\Marriage;
use App\Models\ParentChild;
use Illuminate\Support\Facades\Storage;

class FamilyTreeService
{
    /**
     * Generate comprehensive family tree data
     */
    public function generateTree(
        Person $centralPerson, 
        int $generationsUp = 2, 
        int $generationsDown = 2, 
        bool $includeMarriages = true, 
        bool $includeSiblings = true
    ) {
        // Initialize data structures
        $nodes = [];
        $relationships = [];
        $processedIds = [];
        
        // Process central person
        $nodes[] = $this->formatPerson($centralPerson);
        $processedIds[] = $centralPerson->id;
        
        // Process ancestors (upstream)
        if ($generationsUp > 0) {
            $this->processAncestors($centralPerson, $nodes, $relationships, $processedIds, $generationsUp);
        }
        
        // Process descendants (downstream)
        if ($generationsDown > 0) {
            $this->processDescendants($centralPerson, $nodes, $relationships, $processedIds, $generationsDown);
        }
        
        // Process siblings if requested
        if ($includeSiblings) {
            $this->processSiblings($centralPerson, $nodes, $relationships, $processedIds);
        }
        
        // Process marriages if requested
        if ($includeMarriages) {
            $this->processMarriages($centralPerson, $nodes, $relationships, $processedIds);
        }
        
        return [
            'nodes' => $nodes,
            'relationships' => $relationships,
        ];
    }
    
    /**
     * Generate simplified graph data for visualization
     */
    public function generateGraph(Person $centralPerson, int $generationsUp = 1, int $generationsDown = 1)
    {
        $nodes = [];
        $edges = [];
        $processedIds = [];
        
        // Add central person as the focal point
        $nodes[] = [
            'id' => $centralPerson->id,
            'label' => $centralPerson->first_name,
            'data' => [
                'full_name' => $centralPerson->first_name . ' ' . $centralPerson->last_name,
                'gender' => $centralPerson->gender,
                'marga' => $centralPerson->marga->name,
                'is_central' => true,
            ]
        ];
        $processedIds[] = $centralPerson->id;
        
        // Process ancestors for graph
        if ($generationsUp > 0) {
            $this->processAncestorsGraph($centralPerson, $nodes, $edges, $processedIds, $generationsUp);
        }
        
        // Process descendants for graph
        if ($generationsDown > 0) {
            $this->processDescendantsGraph($centralPerson, $nodes, $edges, $processedIds, $generationsDown);
        }
        
        return [
            'nodes' => $nodes,
            'edges' => $edges,
        ];
    }
    
    /**
     * Format family group for API response
     */
    public function formatFamilyGroup(FamilyGroup $familyGroup)
    {
        $father = $familyGroup->father;
        $mother = $familyGroup->mother;
        
        // Get children in proper display order (males first)
        $children = $familyGroup->members()
            ->with('person')
            ->orderBy('display_order')
            ->get()
            ->filter(function($member) use ($father, $mother) {
                $personId = $member->person->id;
                return (!$father || $personId != $father->id) && 
                       (!$mother || $personId != $mother->id);
            })
            ->map(function($member) {
                return $this->formatPerson($member->person);
            });
        
        return [
            'id' => $familyGroup->id,
            'father' => $father ? $this->formatPerson($father) : null,
            'mother' => $mother ? $this->formatPerson($mother) : null,
            'children' => $children,
            'notes' => $familyGroup->notes,
        ];
    }
    
    /**
     * Format person for API response
     */
    private function formatPerson(Person $person)
    {
        return [
            'id' => $person->id,
            'first_name' => $person->first_name,
            'last_name' => $person->last_name,
            'gender' => $person->gender,
            'birth_date' => $person->birth_date ? $person->birth_date->format('Y-m-d') : null,
            'death_date' => $person->death_date ? $person->death_date->format('Y-m-d') : null,
            'marga_id' => $person->marga_id,
            'marga_name' => $person->marga->name,
            'photo_url' => $person->photo_url ? url(Storage::url($person->photo_url)) : null,
        ];
    }
    
    /**
     * Process ancestors for the family tree
     */
    private function processAncestors($person, &$nodes, &$relationships, &$processedIds, $generations, $currentGen = 1)
    {
        if ($generations <= 0) return;

        // Get parents
        $parents = $person->parents()->with('marga')->get();
        
        foreach ($parents as $parent) {
            // Add parent if not already processed
            if (!in_array($parent->id, $processedIds)) {
                $nodes[] = $this->formatPerson($parent);
                $processedIds[] = $parent->id;
            }
            
            // Add relationship
            $relationships[] = [
                'from' => $parent->id,
                'to' => $person->id,
                'type' => $parent->gender === 'male' ? 'father' : 'mother',
            ];
            
            // Process parent's ancestors recursively with one less generation
            $this->processAncestors($parent, $nodes, $relationships, $processedIds, $generations - 1, $currentGen + 1);
            
            // Process parent's spouses (other than the one that led to this person)
            if ($currentGen == 1 || $currentGen == 2) { // Limit spouse processing to immediate parents and grandparents
                $this->processParentSpouses($parent, $person, $nodes, $relationships, $processedIds);
            }
        }
    }
    
    /**
     * Process parent's other spouses
     */
    private function processParentSpouses($parent, $child, &$nodes, &$relationships, &$processedIds)
    {
        $spouseRelation = $parent->gender === 'male' ? 'wife' : 'husband';
        $spouseGender = $parent->gender === 'male' ? 'female' : 'male';
        
        $marriages = $parent->gender === 'male' 
            ? $parent->marriagesAsHusband()->with('wife.marga')->get()
            : $parent->marriagesAsWife()->with('husband.marga')->get();
        
        foreach ($marriages as $marriage) {
            $spouse = $parent->gender === 'male' ? $marriage->wife : $marriage->husband;
            
            // Skip if this is the child's other parent
            if ($child->parents()->where('id', $spouse->id)->exists()) {
                continue;
            }
            
            // Add spouse if not already processed
            if (!in_array($spouse->id, $processedIds)) {
                $nodes[] = $this->formatPerson($spouse);
                $processedIds[] = $spouse->id;
            }
            
            // Add marriage relationship
            $relationships[] = [
                'from' => $parent->id,
                'to' => $spouse->id,
                'type' => 'spouse',
                'marriage_date' => $marriage->marriage_date ? $marriage->marriage_date->format('Y-m-d') : null,
                'is_current' => $marriage->is_current,
            ];
        }
    }
    
    /**
     * Process descendants for the family tree
     */
    private function processDescendants($person, &$nodes, &$relationships, &$processedIds, $generations, $initialGenerations = null)
    {
        if ($generations <= 0) return;
        
        // Set initial generations on first call
        if ($initialGenerations === null) {
            $initialGenerations = $generations;
        }
        
        // Female lineage limitation - stop after first generation for females
        if ($person->gender === 'female' && $generations < $initialGenerations) {
            return;
        }
        
        // Get children, respecting Batak male-first ordering
        $children = [];
        
        if ($person->gender === 'male') {
            // For males, use family groups to get properly ordered children
            $familyGroups = FamilyGroup::where('father_id', $person->id)->get();
            
            foreach ($familyGroups as $group) {
                $familyChildren = $group->members()
                    ->with('person.marga')
                    ->orderBy('display_order')
                    ->get()
                    ->map(function($member) use ($group) {
                        return $member->person;
                    })
                    ->filter(function($child) use ($person, $group) {
                        // Exclude parents from children list
                        return $child->id != $person->id && 
                               (!$group->mother_id || $child->id != $group->mother_id);
                    });
                
                $children = array_merge($children, $familyChildren->all());
            }
        } else {
            // For females, get children directly
            $children = $person->children()->with('marga')->get()->all();
        }
        
        foreach ($children as $child) {
            // Add child if not already processed
            if (!in_array($child->id, $processedIds)) {
                $nodes[] = $this->formatPerson($child);
                $processedIds[] = $child->id;
            }
            
            // Add relationship
            $relationships[] = [
                'from' => $person->id,
                'to' => $child->id,
                'type' => $person->gender === 'male' ? 'father' : 'mother',
            ];
            
            // Process child's descendants recursively with one less generation
            $this->processDescendants($child, $nodes, $relationships, $processedIds, $generations - 1, $initialGenerations);
            
            // Add child's spouse if we're at the first or second generation
            if ($generations >= $initialGenerations - 1) {
                $this->processSpouses($child, $nodes, $relationships, $processedIds);
            }
        }
    }
    
    /**
     * Process siblings for the family tree
     */
    private function processSiblings($person, &$nodes, &$relationships, &$processedIds)
    {
        // Get parent IDs
        $parentIds = $person->parents()->pluck('id')->toArray();
        
        if (empty($parentIds)) {
            return;
        }
        
        // Get siblings (people who share at least one parent with the person)
        $siblings = Person::whereHas('parents', function($query) use ($parentIds) {
                $query->whereIn('parent_id', $parentIds);
            })
            ->where('id', '!=', $person->id)
            ->with('marga')
            ->get();
        
        foreach ($siblings as $sibling) {
            // Add sibling if not already processed
            if (!in_array($sibling->id, $processedIds)) {
                $nodes[] = $this->formatPerson($sibling);
                $processedIds[] = $sibling->id;
            }
            
            // Find common parents
            $siblingParentIds = $sibling->parents()->pluck('id')->toArray();
            $commonParentIds = array_intersect($parentIds, $siblingParentIds);
            
            foreach ($commonParentIds as $parentId) {
                $parent = Person::find($parentId);
                
                // Add relationship between parent and sibling
                $relationships[] = [
                    'from' => $parentId,
                    'to' => $sibling->id,
                    'type' => $parent->gender === 'male' ? 'father' : 'mother',
                ];
            }
        }
    }
    
    /**
     * Process marriages for the family tree
     */
    private function processMarriages($person, &$nodes, &$relationships, &$processedIds)
    {
        if ($person->gender === 'male') {
            $marriages = $person->marriagesAsHusband()->with('wife.marga')->get();
            
            foreach ($marriages as $marriage) {
                $spouse = $marriage->wife;
                
                // Add spouse if not already processed
                if (!in_array($spouse->id, $processedIds)) {
                    $nodes[] = $this->formatPerson($spouse);
                    $processedIds[] = $spouse->id;
                }
                
                // Add marriage relationship
                $relationships[] = [
                    'from' => $person->id,
                    'to' => $spouse->id,
                    'type' => 'spouse',
                    'marriage_date' => $marriage->marriage_date ? $marriage->marriage_date->format('Y-m-d') : null,
                    'is_current' => $marriage->is_current,
                ];
            }
        } else {
            $marriages = $person->marriagesAsWife()->with('husband.marga')->get();
            
            foreach ($marriages as $marriage) {
                $spouse = $marriage->husband;
                
                // Add spouse if not already processed
                if (!in_array($spouse->id, $processedIds)) {
                    $nodes[] = $this->formatPerson($spouse);
                    $processedIds[] = $spouse->id;
                }
                
                // Add marriage relationship
                $relationships[] = [
                    'from' => $spouse->id,
                    'to' => $person->id,
                    'type' => 'spouse',
                    'marriage_date' => $marriage->marriage_date ? $marriage->marriage_date->format('Y-m-d') : null,
                    'is_current' => $marriage->is_current,
                ];
            }
        }
    }
    
    /**
     * Process ancestors for graph visualization
     */
    private function processAncestorsGraph($person, &$nodes, &$edges, &$processedIds, $generations)
    {
        if ($generations <= 0) return;
        
        // Get parents
        $parents = $person->parents()->with('marga')->get();
        
        foreach ($parents as $parent) {
            // Add parent if not already processed
            if (!in_array($parent->id, $processedIds)) {
                $nodes[] = [
                    'id' => $parent->id,
                    'label' => $parent->first_name,
                    'data' => [
                        'full_name' => $parent->first_name . ' ' . $parent->last_name,
                        'gender' => $parent->gender,
                        'marga' => $parent->marga->name,
                        'is_central' => false,
                    ]
                ];
                $processedIds[] = $parent->id;
            }
            
            // Add edge
            $edges[] = [
                'source' => $parent->id,
                'target' => $person->id,
                'label' => $parent->gender === 'male' ? 'father' : 'mother',
                'data' => [
                    'relationship_type' => 'parent-child'
                ]
            ];
            
            // Process parent's ancestors recursively with one less generation
            $this->processAncestorsGraph($parent, $nodes, $edges, $processedIds, $generations - 1);
        }
    }
    
    /**
     * Process descendants for graph visualization
     */
    private function processDescendantsGraph($person, &$nodes, &$edges, &$processedIds, $generations, $initialGenerations = null)
    {
        if ($generations <= 0) return;
        
        // Set initial generations on first call
        if ($initialGenerations === null) {
            $initialGenerations = $generations;
        }
        
        // Female lineage limitation - stop after first generation for females
        if ($person->gender === 'female' && $generations < $initialGenerations) {
            return;
        }
        
        // Get children with Batak ordering rules
        $children = [];
        
        if ($person->gender === 'male') {
            // For males, use family groups to get properly ordered children
            $familyGroups = FamilyGroup::where('father_id', $person->id)->get();
            
            foreach ($familyGroups as $group) {
                $familyChildren = $group->members()
                    ->with('person.marga')
                    ->orderBy('display_order')
                    ->get()
                    ->map(function($member) use ($group) {
                        return $member->person;
                    })
                    ->filter(function($child) use ($person, $group) {
                        return $child->id != $person->id && 
                               (!$group->mother_id || $child->id != $group->mother_id);
                    });
                
                $children = array_merge($children, $familyChildren->all());
            }
        } else {
            // For females, get children directly
            $children = $person->children()->with('marga')->get()->all();
        }
        
        foreach ($children as $child) {
            // Add child if not already processed
            if (!in_array($child->id, $processedIds)) {
                $nodes[] = [
                    'id' => $child->id,
                    'label' => $child->first_name,
                    'data' => [
                        'full_name' => $child->first_name . ' ' . $child->last_name,
                        'gender' => $child->gender,
                        'marga' => $child->marga->name,
                        'is_central' => false,
                    ]
                ];
                $processedIds[] = $child->id;
            }
            
            // Add edge
            $edges[] = [
                'source' => $person->id,
                'target' => $child->id,
                'label' => $person->gender === 'male' ? 'father' : 'mother',
                'data' => [
                    'relationship_type' => 'parent-child'
                ]
            ];
            
            // Process child's descendants recursively with one less generation
            $this->processDescendantsGraph($child, $nodes, $edges, $processedIds, $generations - 1, $initialGenerations);
        }
    }
}