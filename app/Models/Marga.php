<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marga extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'origin_story_link',
    ];

    /**
     * Get the families associated with this marga.
     */
    public function families()
    {
        return $this->hasMany(Family::class);
    }

    /**
     * Get the sapaan terms specific to this marga.
     */
    public function sapaanTerms()
    {
        return $this->hasMany(SapaanTerm::class);
    }

    /**
     * Get the sapaan rules specific to this marga.
     */
    public function sapaanRules()
    {
        return $this->hasMany(SapaanRule::class);
    }
}

