<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'full_name',
        'nickname',
        'gender',
        'birth_date',
        'birth_place',
        'death_date',
        'death_place',
        'father_id',
        'mother_id',
        'order_in_siblings',
        'bio',
        'profile_picture_path',
        'user_account_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
    ];

    /**
     * Get the family this member belongs to.
     */
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * Get the father of this family member.
     */
    public function father()
    {
        return $this->belongsTo(FamilyMember::class, 'father_id');
    }

    /**
     * Get the mother of this family member.
     */
    public function mother()
    {
        return $this->belongsTo(FamilyMember::class, 'mother_id');
    }

    /**
     * Get the children of this family member (if male and has a spouse, or if female and has a spouse).
     */
    public function children()
    {
        if ($this->gender == 'male') {
            return $this->hasMany(FamilyMember::class, 'father_id');
        } elseif ($this->gender == 'female') {
            return $this->hasMany(FamilyMember::class, 'mother_id');
        }
        return $this->hasMany(FamilyMember::class, 'father_id')->orWhere('mother_id', $this->id); // Fallback, less precise
    }

    /**
     * Get the user account associated with this family member.
     */
    public function userAccount()
    {
        return $this->belongsTo(User::class, 'user_account_id');
    }

    /**
     * Get the spouses of this family member.
     * A member can have multiple spouses (e.g. if divorced and remarried, or polygamy if applicable by custom).
     */
    public function spouses()
    {
        return $this->belongsToMany(FamilyMember::class, 'spouses', 'family_member_id1', 'family_member_id2')
                    ->withTimestamps()->withPivot('marriage_date', 'marriage_location', 'status')
                    ->orWhere(function ($query) {
                        $query->where('family_member_id2', $this->id);
                    });
    }

    // Simpler way to get current spouse(s) if you only care about 'married' status
    public function currentSpouses()
    {
        return $this->spouses()->wherePivot('status', 'married');
    }
}
