<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Marga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PersonController extends Controller
{
    /**
     * Get detailed information about a person
     */
    public function show(Request $request, $id)
    {
        $person = Person::with(['marga'])->findOrFail($id);
        
        $data = [
            'id' => $person->id,
            'first_name' => $person->first_name,
            'last_name' => $person->last_name,
            'full_name' => $person->first_name . ' ' . $person->last_name,
            'gender' => $person->gender,
            'birth_date' => $person->birth_date ? $person->birth_date->format('Y-m-d') : null,
            'death_date' => $person->death_date ? $person->death_date->format('Y-m-d') : null,
            'photo_url' => $person->photo_url ? url(Storage::url($person->photo_url)) : null,
            'marga' => [
                'id' => $person->marga->id,
                'name' => $person->marga->name,
            ],
            'is_living' => !$person->death_date,
            'notes' => $person->notes,
        ];
        
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
    
    /**
     * Search for people
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2|max:255',
            'marga_id' => 'nullable|exists:margas,id',
            'gender' => 'nullable|in:male,female',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);
        
        $query = $request->input('query');
        $margaId = $request->input('marga_id');
        $gender = $request->input('gender');
        $limit = $request->input('limit', 20);
        
        $people = Person::where(function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%");
            })
            ->when($margaId, function($q) use ($margaId) {
                return $q->where('marga_id', $margaId);
            })
            ->when($gender, function($q) use ($gender) {
                return $q->where('gender', $gender);
            })
            ->with('marga')
            ->limit($limit)
            ->get()
            ->map(function($person) {
                return [
                    'id' => $person->id,
                    'first_name' => $person->first_name,
                    'last_name' => $person->last_name,
                    'full_name' => $person->first_name . ' ' . $person->last_name,
                    'gender' => $person->gender,
                    'birth_date' => $person->birth_date ? $person->birth_date->format('Y-m-d') : null,
                    'marga' => $person->marga->name,
                    'photo_url' => $person->photo_url ? url(Storage::url($person->photo_url)) : null,
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $people,
            'meta' => [
                'query' => $query,
                'total' => count($people),
            ]
        ]);
    }
    
    /**
     * Get available margas for reference
     */
    public function getMargas()
    {
        $margas = Marga::orderBy('name')->get(['id', 'name', 'description']);
        
        return response()->json([
            'success' => true,
            'data' => $margas,
        ]);
    }
}
