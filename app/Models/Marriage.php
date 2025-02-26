<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marriage extends Model
{
    protected $fillable = [
        'husband_id', 'wife_id', 'marriage_date', 'divorce_date',
    ];

    // Relasi ke Suami
    public function husband()
    {
        return $this->belongsTo(FamilyMember::class, 'husband_id');
    }

    // Relasi ke Istri
    public function wife()
    {
        return $this->belongsTo(FamilyMember::class, 'wife_id');
    }
}
