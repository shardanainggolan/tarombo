<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Person extends Model
{
    protected $table = 'people';

    protected $fillable = [
        'first_name',
        'last_name',
        'marga_id',
        'gender',
        'birth_date',
        'death_date',
        'photo_url',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
    ];

    // User account relationship
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    // Marga relationship
    public function marga(): BelongsTo
    {
        return $this->belongsTo(Marga::class);
    }

    // Marriage relationships
    public function marriagesAsHusband(): HasMany
    {
        return $this->hasMany(Marriage::class, 'husband_id');
    }

    public function marriagesAsWife(): HasMany
    {
        return $this->hasMany(Marriage::class, 'wife_id');
    }

    // Current spouse relationships with convenience methods
    public function currentSpouses()
    {
        $spouses = collect();

        if ($this->gender === 'male') {
            $spouses = $this->marriagesAsHusband()
                ->where('is_current', true)
                ->with('wife')
                ->get()
                ->pluck('wife');
        } else {
            $spouses = $this->marriagesAsWife()
                ->where('is_current', true)
                ->with('husband')
                ->get()
                ->pluck('husband');
        }

        return $spouses;
    }

    // Parent-child relationships
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(
            Person::class,
            'parent_child',
            'child_id',
            'parent_id'
        )->withPivot('marriage_id', 'is_biological', 'birth_order');
    }

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(
            Person::class,
            'parent_child',
            'parent_id',
            'child_id'
        )->withPivot('marriage_id', 'is_biological', 'birth_order');
    }

    // Family group relationships
    public function familyGroupsAsFather(): HasMany
    {
        return $this->hasMany(FamilyGroup::class, 'father_id');
    }

    public function familyGroupsAsMother(): HasMany
    {
        return $this->hasMany(FamilyGroup::class, 'mother_id');
    }

    public function familyMemberships(): HasMany
    {
        return $this->hasMany(FamilyMember::class);
    }

    // Relationship identification methods
    public function relationshipsFrom(): HasMany
    {
        return $this->hasMany(CachedRelationship::class, 'from_person_id');
    }

    public function relationshipsTo(): HasMany
    {
        return $this->hasMany(CachedRelationship::class, 'to_person_id');
    }

    // Helper methods for tree traversal
    public function father()
    {
        return $this->parents()->where('gender', 'male')->first();
    }

    public function mother()
    {
        return $this->parents()->where('gender', 'female')->first();
    }

    public function siblings()
    {
        $parents = $this->parents()->pluck('id');
        
        if ($parents->isEmpty()) {
            return collect();
        }
        
        return Person::whereHas('parents', function ($query) use ($parents) {
            $query->whereIn('parent_id', $parents);
        })->where('id', '!=', $this->id)->get();
    }

    // Partuturan lookup methods
    public function getPartuturanTermFor(Person $relative)
    {
        $cachedRelationship = CachedRelationship::where('from_person_id', $this->id)
            ->where('to_person_id', $relative->id)
            ->first();
            
        if ($cachedRelationship) {
            return $cachedRelationship->partuturanTerm;
        }
        
        // If relationship not cached, calculate it (would be implemented in a service)
        return null;
    }
}
