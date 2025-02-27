<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\User;

class PersonController extends Controller
{
    public function index()
    {
        $persons = Person::with(['father', 'mother', 'user'])
            ->orderBy('marga')
            ->orderBy('birth_date')
            ->get();

        return view('admin.pages.people.index', compact('persons'));
    }

    public function create()
    {
        $users = User::doesntHave('person')->get();
        $fathers = Person::where('gender', 'male')->get();
        $mothers = Person::where('gender', 'female')->get();

        return view('admin.pages.people.create', compact(
            'users', 
            'fathers', 
            'mothers'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id|unique:people,user_id',
            'father_id' => 'nullable|exists:people,id',
            'mother_id' => 'nullable|exists:people,id',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
        ]);

        // Auto set marga
        $data['marga'] = $this->determineMarga($data['father_id'] ?? null);
        $data['is_boru_line'] = $this->checkBoruLine($data['mother_id'] ?? null);

        Person::create($data);

        return redirect()->route('admin.people.index')
            ->with('success', 'Anggota keluarga berhasil ditambahkan');
    }

    public function edit(Person $person)
    {
        $users = User::doesntHave('person')->orWhere('id', $person->user_id)->get();
        $fathers = Person::where('gender', 'male')->get();
        $mothers = Person::where('gender', 'female')->get();

        return view('admin.pages.people.edit', compact(
            'person',
            'users',
            'fathers',
            'mothers'
        ));
    }

    public function update(Request $request, Person $person)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id|unique:people,user_id,'.$person->id,
            'father_id' => 'nullable|exists:people,id',
            'mother_id' => 'nullable|exists:people,id',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
        ]);

        $data['marga'] = $this->determineMarga($data['father_id'] ?? null);
        $data['is_boru_line'] = $this->checkBoruLine($data['mother_id'] ?? null);

        $person->update($data);

        return redirect()->route('admin.people.index')
            ->with('success', 'Data anggota keluarga berhasil diperbarui');
    }

    public function destroy(Person $person)
    {
        // Prevent delete if has relationships
        if($person->children()->exists() || $person->marriages()->exists()) {
            return back()->withErrors([
                'message' => 'Tidak dapat menghapus anggota yang memiliki relasi keluarga'
            ]);
        }

        $person->delete();
        return redirect()->route('admin.people.index')
            ->with('success', 'Anggota keluarga berhasil dihapus');
    }

    private function determineMarga($fatherId): string
    {
        if($fatherId) {
            return Person::find($fatherId)->marga;
        }
        return 'Nainggolan'; // Default marga
    }

    private function checkBoruLine($motherId): bool
    {
        if(!$motherId) return false;
        
        $mother = Person::find($motherId);
        return $mother->gender === 'female' && $mother->is_boru_line;
    }
}
