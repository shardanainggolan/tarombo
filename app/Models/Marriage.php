<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marriage extends Model
{
    protected $fillable = [
        'husband_id', 'wife_id', 'marriage_date', 'status'
    ];

    protected $casts = [
        'marriage_date' => 'date'
    ];

    public function husband()
    {
        return $this->belongsTo(Individual::class, 'husband_id');
    }

    public function wife()
    {
        return $this->belongsTo(Individual::class, 'wife_id');
    }

    public function individuals()
    {
        return $this->hasMany(Individual::class);
    }
}
