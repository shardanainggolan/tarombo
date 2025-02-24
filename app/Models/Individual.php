<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    protected $fillable = ['clan_id', 'first_name', 'last_name', 'gender', 'birth_date'];

    public function clan()
    {
        return $this->belongsTo(Clan::class);
    }
}
