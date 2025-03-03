<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FamilyGroup extends Model
{
    protected $fillable = [
        'father_id',
        'mother_id',
        'notes',
    ];

    public function father(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'father_id');
    }

    public function mother(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'mother_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(FamilyMember::class)->orderBy('display_order');
    }

    // Helper method to get all children in display order
    public function getChildrenInDisplayOrder()
    {
        return $this->members()
            ->join('people', 'family_members.person_id', '=', 'people.id')
            ->where('people.id', '!=', $this->father_id)
            ->where('people.id', '!=', $this->mother_id)
            ->orderBy('family_members.display_order')
            ->select('people.*', 'family_members.display_order')
            ->get();
    }

    // Helper method to calculate display order based on Batak rules
    public function recalculateDisplayOrder()
    {
        // Get all children
        $children = Person::whereHas('parents', function ($query) {
            $query->where(function ($q) {
                $q->where('parent_id', $this->father_id)
                    ->orWhere('parent_id', $this->mother_id);
            });
        })->get();
        
        // Split by gender
        $maleChildren = $children->where('gender', 'male')->sortBy(function ($child) {
            // Get birth order from pivot
            return $child->parents->where('id', $this->father_id)
                ->first()->pivot->birth_order ?? 999;
        });
        
        $femaleChildren = $children->where('gender', 'female')->sortBy(function ($child) {
            // Get birth order from pivot
            return $child->parents->where('id', $this->father_id)
                ->first()->pivot->birth_order ?? 999;
        });
        
        // Delete existing family members (except parents)
        $this->members()
            ->whereNotIn('person_id', [$this->father_id, $this->mother_id])
            ->delete();
        
        // Create new members with correct display order
        $displayOrder = 1;
        
        // Add father if exists
        if ($this->father_id) {
            FamilyMember::updateOrCreate(
                ['family_group_id' => $this->id, 'person_id' => $this->father_id],
                ['display_order' => $displayOrder++]
            );
        }
        
        // Add mother if exists
        if ($this->mother_id) {
            FamilyMember::updateOrCreate(
                ['family_group_id' => $this->id, 'person_id' => $this->mother_id],
                ['display_order' => $displayOrder++]
            );
        }
        
        // Add male children first
        foreach ($maleChildren as $child) {
            FamilyMember::create([
                'family_group_id' => $this->id,
                'person_id' => $child->id,
                'display_order' => $displayOrder++
            ]);
        }
        
        // Then add female children
        foreach ($femaleChildren as $child) {
            FamilyMember::create([
                'family_group_id' => $this->id,
                'person_id' => $child->id,
                'display_order' => $displayOrder++
            ]);
        }
    }
}
