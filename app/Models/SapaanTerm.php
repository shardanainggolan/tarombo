<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SapaanTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'term',
        'description',
        'marga_id',
    ];

    /**
     * Get the marga this sapaan term might be specific to.
     */
    public function marga()
    {
        return $this->belongsTo(Marga::class);
    }

    /**
     * Get the sapaan rules that use this term.
     */
    public function sapaanRules()
    {
        return $this->hasMany(SapaanRule::class);
    }
}

