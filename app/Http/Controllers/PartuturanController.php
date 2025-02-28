<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Services\PartuturanService;
use Illuminate\Http\Request;

class PartuturanController extends Controller
{
    public function show(Person $person)
    {
        $service = new PartuturanService();
        $relationships = $service->generateRelationships($person);
        
        return view('partuturan.show', [
            'person' => $person,
            'relationships' => $relationships
        ]);
    }

    public function storeRelationships(Request $request)
    {
        $person = Person::findOrFail($request->person_id);
        
        foreach($request->relationships as $relationship) {
            UserRelationship::updateOrCreate([
                'person_id' => $person->id,
                'relative_id' => $relationship['relative_id']
            ], [
                'user_id' => auth()->id(),
                'partuturan_rule_id' => $relationship['rule_id']
            ]);
        }
        
        return redirect()->back()->with('success', 'Relasi berhasil disimpan');
    }
}
