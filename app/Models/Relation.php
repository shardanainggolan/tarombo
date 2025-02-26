<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    protected $fillable = [
        'user_id', 'relation_type_id', 'relation_description'
    ];

    // Relasi ke FamilyMember (Anggota Keluarga)
    public function user()
    {
        return $this->belongsTo(FamilyMember::class, 'user_id');
    }

    // Relasi ke RelationTypes (Jenis Hubungan)
    public function relationType()
    {
        return $this->belongsTo(RelationType::class, 'relation_type_id');
    }
}
