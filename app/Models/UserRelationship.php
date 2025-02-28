<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRelationship extends Model
{
    protected $fillable = [
        'user_id',
        'person_id',
        'relative_id',
        'partuturan_rule_id'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function relative()
    {
        return $this->belongsTo(Person::class, 'relative_id');
    }

    public function rule()
    {
        return $this->belongsTo(PartuturanRule::class, 'partuturan_rule_id');
    }
}
