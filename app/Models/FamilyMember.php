<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    protected $fillable = [
        'user_id', 'marga', 'father_id', 'mother_id', 'spouse_id', 'gender', 'position_in_family', 'marriage_count', 'birth_date',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Ayah
    public function father()
    {
        return $this->belongsTo(FamilyMember::class, 'father_id');
    }

    // Relasi ke Ibu
    public function mother()
    {
        return $this->belongsTo(FamilyMember::class, 'mother_id');
    }

    // Relasi ke Pasangan
    public function spouse()
    {
        return $this->belongsTo(FamilyMember::class, 'spouse_id');
    }

    // Relasi ke Anak-anak
    public function children()
    {
        return $this->hasMany(FamilyMember::class, 'father_id');
    }

    // Relasi ke Saudaranya
    public function siblings()
    {
        return $this->hasMany(FamilyMember::class, 'mother_id');
    }
}
