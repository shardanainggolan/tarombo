<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\FamilyGroup;
use App\Services\PartuturanService;
use App\Services\FamilyTreeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyTreeController extends Controller
{
    protected $familyTreeService;
    protected $partuturanService;
    
    public function __construct(FamilyTreeService $familyTreeService, PartuturanService $partuturanService)
    {
        $this->familyTreeService = $familyTreeService;
        $this->partuturanService = $partuturanService;
    }
    
    /**
     * Get family tree data centered on the authenticated user or specified person
     */
    public function getTree(Request $request)
    {
        $request->validate([
            'person_id' => 'nullable|exists:people,id',
            'generations_up' => 'nullable|integer|min:0|max:10', // Increased max
            'generations_down' => 'nullable|integer|min:0|max:10', // Increased max
            'include_marriages' => 'nullable|boolean',
            'include_siblings' => 'nullable|boolean',
        ]);
        
        // Default to authenticated user if no person_id provided
        $personId = $request->input('person_id', Auth::user()->person_id);
        $person = Person::findOrFail($personId);
        
        // Set parameters with sensible defaults
        $generationsUp = $request->input('generations_up', 5);     // Increased default
        $generationsDown = $request->input('generations_down', 5); // Increased default
        $includeMarriages = $request->input('include_marriages', true);
        $includeSiblings = $request->input('include_siblings', true);
        
        // Generate tree data
        $treeData = $this->familyTreeService->generateTree(
            $person,
            $generationsUp,
            $generationsDown,
            $includeMarriages,
            $includeSiblings
        );
        
        return response()->json([
            'success' => true,
            'data' => $treeData,
            'meta' => [
                'central_person_id' => $person->id,
                'generations_up' => $generationsUp,
                'generations_down' => $generationsDown,
                'include_marriages' => $includeMarriages,
                'include_siblings' => $includeSiblings,
            ]
        ]);
    }
    
    /**
     * Get a simplified graph representation for visualization
     */
    public function getGraph(Request $request)
    {
        $request->validate([
            'person_id' => 'nullable|exists:people,id',
            'generations_up' => 'nullable|integer|min:0|max:10',
            'generations_down' => 'nullable|integer|min:0|max:10',
        ]);
        
        $personId = $request->input('person_id', Auth::user()->person_id);
        $person = Person::findOrFail($personId);
        
        $generationsUp = $request->input('generations_up', 1);
        $generationsDown = $request->input('generations_down', 1);
        
        $graphData = $this->familyTreeService->generateGraph(
            $person,
            $generationsUp,
            $generationsDown
        );
        
        return response()->json([
            'success' => true,
            'data' => $graphData,
            'meta' => [
                'central_person_id' => $person->id,
                'generations_up' => $generationsUp,
                'generations_down' => $generationsDown,
            ]
        ]);
    }
    
    /**
     * Get family group structure (parents and children)
     */
    public function getFamilyGroup(Request $request)
    {
        $request->validate([
            'person_id' => 'required|exists:people,id',
        ]);
        
        $person = Person::findOrFail($request->person_id);
        
        // Get family groups based on person gender
        if ($person->gender === 'male') {
            $familyGroups = FamilyGroup::with(['mother', 'members.person'])
                ->where('father_id', $person->id)
                ->get();
        } else {
            $familyGroups = FamilyGroup::with(['father', 'members.person'])
                ->where('mother_id', $person->id)
                ->get();
        }
        
        $formattedGroups = $familyGroups->map(function ($group) {
            return $this->familyTreeService->formatFamilyGroup($group);
        });
        
        return response()->json([
            'success' => true,
            'data' => $formattedGroups,
        ]);
    }
}
