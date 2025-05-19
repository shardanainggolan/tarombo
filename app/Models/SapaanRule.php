<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SapaanRule extends Model
{
    use HasFactory;

    protected $fillable = [
        "marga_id",
        "relationship_type",
        "gender_from",
        "gender_to",
        "sapaan_term_id",
        "priority",
        "description",
    ];

    /**
     * Get the marga this rule might be specific to.
     */
    public function marga()
    {
        return $this->belongsTo(Marga::class);
    }

    /**
     * Get the sapaan term used by this rule.
     */
    public function sapaanTerm()
    {
        return $this->belongsTo(SapaanTerm::class);
    }
}

