<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marga;
use Illuminate\Http\Request;

class MargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Logic to display a list of margas
        $margas = Marga::paginate(10); // Example: Paginate results
        return view('admin.pages.margas.index', compact('margas'));
        // return response()->json($margas); // Placeholder for now
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.margas.create');
        // return response()->json(['message' => 'Show form to create marga']); // Placeholder
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:margas|max:255',
            'description' => 'nullable|string',
            'origin_story_link' => 'nullable|url|max:255',
        ]);

        $marga = Marga::create($validatedData);
        return redirect()->route('admin.margas.index')->with('success', 'Marga created successfully.');
        // return response()->json($marga, 201); // Placeholder
    }

    /**
     * Display the specified resource.
     */
    public function show(Marga $marga)
    {
        return view('admin.pages.margas.show', compact('marga'));
        // return response()->json($marga); // Placeholder
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marga $marga)
    {
        return view('admin.pages.margas.edit', compact('marga'));
        // return response()->json($marga); // Placeholder
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marga $marga)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:margas,name,' . $marga->id,
            'description' => 'nullable|string',
            'origin_story_link' => 'nullable|url|max:255',
        ]);

        $marga->update($validatedData);
        return redirect()->route('admin.margas.index')->with('success', 'Marga updated successfully.');
        // return response()->json($marga); // Placeholder
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marga $marga)
    {
        $marga->delete();
        return redirect()->route('admin.margas.index')->with('success', 'Marga deleted successfully.');
        // return response()->json(null, 204); // Placeholder
    }
}

