<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    protected $fillable = [
        'clan_id', 'marriage_id', 'first_name', 'last_name', 'gender', 'birth_date', 'death_date', 'is_alive'
    ];

    protected $casts = [
        'birth_date' => 'date'
    ];

    public function clan()
    {
        return $this->belongsTo(Clan::class);
    }

    public function marriage()
    {
        return $this->belongsTo(Marriage::class);
    }

    public function marriages()
    {
        return $this->hasMany(Marriage::class, 'husband_id')->orWhere('wife_id', $this->id);
    }

    public function relationships()
    {
        return $this->hasMany(Relationship::class);
    }

    public function relatedRelationships()
    {
        return $this->hasMany(Relationship::class, 'related_individual_id');
    }

    // Fungsi untuk mendapatkan semua keturunan (anak, cucu, dst.)
    public function getDescendants()
    {
        $descendants = collect();
        $children = $this->relationships()
            ->whereIn('relationship_type', ['son', 'daughter'])
            ->get();
        foreach ($children as $child) {
            $descendants->push($child->relatedIndividual);
            $descendants = $descendants->merge($child->relatedIndividual->getDescendants());
        }
        return $descendants;
    }
}
