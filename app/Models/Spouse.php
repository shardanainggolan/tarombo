<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Spouse extends Pivot // Important: Use Pivot for many-to-many intermediate table models
{
    use HasFactory;

    protected $table = 'spouses'; // Explicitly define the table name

    public $incrementing = true; // Since 'id' is the primary key and auto-incrementing

    protected $fillable = [
        'family_member_id1',
        'family_member_id2',
        'marriage_date',
        'marriage_location',
        'status',
    ];

    protected $casts = [
        'marriage_date' => 'date',
    ];

    /**
     * Get the first family member in the spouse relationship.
     */
    public function familyMember1()
    {
        return $this->belongsTo(FamilyMember::class, 'family_member_id1');
    }

    /**
     * Get the second family member in the spouse relationship.
     */
    public function familyMember2()
    {
        return $this->belongsTo(FamilyMember::class, 'family_member_id2');
    }
}
