<?php

namespace App\Services;

use App\Models\Individual;
use App\Models\Relationship;

class PartuturanService
{
    public function getPanggilan(Individual $user, Individual $kerabat)
    {
        $relationship = Relationship::where('individual_id', $user->id)
            ->where('related_individual_id', $kerabat->id)
            ->first();

        if ($relationship) {
            switch ($relationship->relationship_type) {
                case 'father': return 'Amang';
                case 'mother': return 'Inang';
                case 'child': return $kerabat->gender === 'male' ? 'Anak Boru' : 'Anak Beru';
                // Tambahkan aturan lain sesuai adat Batak Toba
            }
        }
        return 'Kerabat';
    }
}