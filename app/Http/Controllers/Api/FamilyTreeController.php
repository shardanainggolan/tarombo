<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Individual;

class FamilyTreeController extends Controller
{
    public function getTree($individualId)
    {
        $individual = Individual::with(['relationships.relatedIndividual', 'marriages.husband', 'marriages.wife'])->findOrFail($individualId);

        // Struktur data untuk pohon keluarga
        $treeData = $this->buildTree($individual);

        return response()->json($treeData);
    }

    private function buildTree($individual)
    {
        $node = [
            'name' => $individual->first_name . ' ' . $individual->last_name,
            'gender' => $individual->gender,
            'id' => $individual->id,
            'children' => [],
            'spouses' => [],
        ];

        // Tambahkan pasangan (spouses)
        foreach ($individual->marriages as $marriage) {
            $spouse = $individual->gender === 'male' ? $marriage->wife : $marriage->husband;
            $node['spouses'][] = [
                'name' => $spouse->first_name . ' ' . $spouse->last_name,
                'gender' => $spouse->gender,
                'id' => $spouse->id,
            ];
        }

        // Tambahkan anak-anak
        $children = $individual->relationships()
            ->whereIn('relationship_type', ['son', 'daughter'])
            ->with('relatedIndividual')
            ->get();

        foreach ($children as $child) {
            $node['children'][] = $this->buildTree($child->relatedIndividual);
        }

        return $node;
    }
}
