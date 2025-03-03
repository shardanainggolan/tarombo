<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Marga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Person::with('marga')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('full_name', function ($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })
                ->addColumn('marga_name', function ($row) {
                    return $row->marga->name;
                })
                ->addColumn('gender_label', function ($row) {
                    return $row->gender == 'male' ? 'Laki-laki' : 'Perempuan';
                })
                ->addColumn('birth_date', function ($row) {
                    return $row->birth_date ? $row->birth_date->format('d M Y') : '-';
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <div class="d-flex align-items-center">
                            <a href="' . route('admin.people.show', $row->id) . '" class="btn btn-icon btn-label-primary waves-effect me-2">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="' . route('admin.people.edit', $row->id) . '" class="btn btn-icon btn-label-info waves-effect me-2">
                                <i class="ti ti-pencil"></i>
                            </a>
                            <button type="button" id="' . $row->id . '" class="delete-record btn btn-icon btn-label-danger waves-effect">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('admin.pages.people.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $margas = Marga::orderBy('name')->get();
        return view('admin.pages.people.create', compact('margas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'marga_id' => 'required|exists:margas,id',
            'gender' => 'required|in:male,female',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date|after_or_equal:birth_date',
            'photo' => 'nullable|image|max:2048',
            'notes' => 'nullable|string'
        ]);

        $data = $request->except('photo');
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '_' . str_replace(' ', '_', $photo->getClientOriginalName());
            $photo->storeAs('public/photos', $filename);
            $data['photo_url'] = 'photos/' . $filename;
        }

        Person::create($data);

        return redirect()->route('admin.people.index')
            ->with('success', 'Data orang berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        // Load all necessary relationships
        $person->load([
            'marga', 
            'parents.marga', 
            'children.marga', 
            'marriagesAsHusband.wife.marga', 
            'marriagesAsWife.husband.marga'
        ]);
        
        return view('admin.pages.people.show', compact('person'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Person $person)
    {
        $margas = Marga::orderBy('name')->get();
        return view('admin.pages.people.edit', compact('person', 'margas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'marga_id' => 'required|exists:margas,id',
            'gender' => 'required|in:male,female',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date|after_or_equal:birth_date',
            'photo' => 'nullable|image|max:2048',
            'notes' => 'nullable|string'
        ]);

        $data = $request->except(['photo', '_token', '_method']);
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($person->photo_url) {
                Storage::delete('public/' . $person->photo_url);
            }
            
            $photo = $request->file('photo');
            $filename = time() . '_' . str_replace(' ', '_', $photo->getClientOriginalName());
            $photo->storeAs('public/photos', $filename);
            $data['photo_url'] = 'photos/' . $filename;
        }

        // If gender changed, need to update family relationships
        if ($person->gender != $request->gender) {
            // Handle gender change logic
            // This could involve updating marriage records, etc.
        }

        $person->update($data);

        // Update family relationships display order if needed
        if ($person->gender == 'male') {
            foreach ($person->familyGroupsAsFather as $familyGroup) {
                $familyGroup->recalculateDisplayOrder();
            }
        }

        return redirect()->route('admin.people.index')
            ->with('success', 'Data orang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        try {
            // Check for relationships that prevent deletion
            $hasDependencies = false;
            $dependencyMessage = "Tidak dapat menghapus data karena memiliki relasi:";
            
            if ($person->children()->count() > 0) {
                $hasDependencies = true;
                $dependencyMessage .= " Anak ({$person->children()->count()})";
            }
            
            if ($person->marriagesAsHusband()->count() > 0 || $person->marriagesAsWife()->count() > 0) {
                $hasDependencies = true;
                $marriageCount = $person->marriagesAsHusband()->count() + $person->marriagesAsWife()->count();
                $dependencyMessage .= " Pernikahan ($marriageCount)";
            }
            
            if ($hasDependencies) {
                return response()->json([
                    'status' => 'error',
                    'message' => $dependencyMessage
                ], 422);
            }
            
            // Delete photo if exists
            if ($person->photo_url) {
                Storage::delete('public/' . $person->photo_url);
            }
            
            $person->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data orang berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data orang. ' . $e->getMessage()
            ], 422);
        }
    }
}
