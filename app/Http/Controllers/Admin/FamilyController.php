<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Marga;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Logic to display a list of families, perhaps for the authenticated user or all if admin
        // For simplicity, let's assume we list families managed by the authenticated user
        $families = Family::where('user_id', Auth::id())->paginate(10);
        return view('admin.pages.families.index', compact('families'));
        // return response()->json($families); // Placeholder
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $margas = Marga::all(); // For dropdown selection
        return view('admin.pages.families.create', compact('margas'));
        // return response()->json(['message' => 'Show form to create family', 'margas' => $margas]); // Placeholder
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'family_name' => 'required|string|max:255',
            'marga_id' => 'nullable|exists:margas,id',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        $validatedData['user_id'] = Auth::id();
        if ($request->has('is_public')) {
            $validatedData['is_public'] = $request->boolean('is_public');
        } else {
            $validatedData['is_public'] = false;
        }

        $family = Family::create($validatedData);
        return redirect()->route('admin.families.index')->with('success', 'Family created successfully.');
        // return response()->json($family, 201); // Placeholder
    }

    /**
     * Display the specified resource.
     */
    public function show(Family $family)
    {
        // Ensure the user is authorized to see this family (e.g., it's their own or public)
        if ($family->user_id !== Auth::id() && !$family->is_public) {
            // abort(403, 'Unauthorized action.');
            return response()->json(['message' => 'Unauthorized'], 403); // Placeholder
        }
        $family->load(['familyMembers', 'marga', 'user']); // Eager load relations
        return view('admin.pages.families.show', compact('family'));
        // return response()->json($family); // Placeholder
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Family $family)
    {
        if ($family->user_id !== Auth::id()) {
            // abort(403, 'Unauthorized action.');
             return response()->json(['message' => 'Unauthorized'], 403); // Placeholder
        }
        $margas = Marga::all();
        return view('admin.pages.families.edit', compact('family', 'margas'));
        // return response()->json(['family' => $family, 'margas' => $margas]); // Placeholder
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Family $family)
    {
        if ($family->user_id !== Auth::id()) {
            // abort(403, 'Unauthorized action.');
            return response()->json(['message' => 'Unauthorized'], 403); // Placeholder
        }

        $validatedData = $request->validate([
            'family_name' => 'required|string|max:255',
            'marga_id' => 'nullable|exists:margas,id',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ]);
        
        if ($request->has('is_public')) {
            $validatedData['is_public'] = $request->boolean('is_public');
        } else {
            $validatedData['is_public'] = $family->is_public; // Keep current if not provided
        }

        $family->update($validatedData);
        return redirect()->route('admin.families.index')->with('success', 'Family updated successfully.');
        // return response()->json($family); // Placeholder
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Family $family)
    {
        if ($family->user_id !== Auth::id()) {
            // abort(403, 'Unauthorized action.');
            return response()->json(['message' => 'Unauthorized'], 403); // Placeholder
        }

        $family->delete();
        return redirect()->route('admin.families.index')->with('success', 'Family deleted successfully.');
        // return response()->json(null, 204); // Placeholder
    }
}

