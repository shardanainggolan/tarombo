<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\PartuturanTerm;
use App\Models\PartuturanCategory;
use App\Services\PartuturanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartuturanController extends Controller
{
    protected $partuturanService;
    
    public function __construct(PartuturanService $partuturanService)
    {
        $this->partuturanService = $partuturanService;
    }
    
    /**
     * Get partuturan term between two people
     */
    public function getRelationship(Request $request)
    {
        $request->validate([
            'from_person_id' => 'required|exists:people,id',
            'to_person_id' => 'required|exists:people,id|different:from_person_id',
        ]);
        
        $fromPerson = Person::findOrFail($request->from_person_id);
        $toPerson = Person::findOrFail($request->to_person_id);
        
        $result = $this->partuturanService->resolvePartuturanTerm($fromPerson, $toPerson);
        
        return response()->json([
            'success' => true,
            'data' => [
                'from_person' => $this->formatPersonBasic($fromPerson),
                'to_person' => $this->formatPersonBasic($toPerson),
                'term' => $result['term'] ?? null,
                'category' => $result['category'] ?? null,
                'description' => $result['description'] ?? null,
                'relationship_path' => $result['path'] ?? null,
            ]
        ]);
    }
    
    /**
     * Get all partuturan terms for a person relative to authenticated user
     */
    public function getAllRelationships(Request $request)
    {
        $request->validate([
            'ego_person_id' => 'nullable|exists:people,id',
            'search' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:partuturan_categories,id',
        ]);
        
        $egoPersonId = $request->input('ego_person_id', Auth::user()->person_id);
        $egoPerson = Person::findOrFail($egoPersonId);
        
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        
        $relations = $this->partuturanService->getAllRelationships($egoPerson, $search, $categoryId);
        
        return response()->json([
            'success' => true,
            'data' => $relations,
            'meta' => [
                'ego_person_id' => $egoPerson->id,
                'total' => count($relations),
            ]
        ]);
    }
    
    /**
     * Get all partuturan categories and terms for reference
     */
    public function getTermsDirectory()
    {
        $categories = PartuturanCategory::with('terms')->get()->map(function($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'terms' => $category->terms->map(function($term) {
                    return [
                        'id' => $term->id,
                        'term' => $term->term,
                        'description' => $term->description,
                    ];
                })
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }
    
    private function formatPersonBasic($person)
    {
        return [
            'id' => $person->id,
            'name' => $person->first_name . ' ' . $person->last_name,
            'gender' => $person->gender,
            'marga' => $person->marga->name,
        ];
    }
}
