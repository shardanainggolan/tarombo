<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'marga_id',
        'family_name',
        'description',
        'is_public',
    ];

    /**
     * Get the user who manages this family tree.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the marga associated with this family.
     */
    public function marga()
    {
        return $this->belongsTo(Marga::class);
    }

    /**
     * Get all members of this family.
     */
    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }
}

