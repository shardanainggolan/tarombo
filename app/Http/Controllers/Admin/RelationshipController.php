<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Individual;
use App\Models\Clan;
use App\Models\Marriage;
use App\Models\Relationship;

class RelationshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        session(['activeMenu' => 'listRelationships']);
        session(['activeParentMenu' => 'relationships']);
        session(['activeSubParentMenu' => '']);

        $relationships = Relationship::all();
        return view('admin.pages.relationships.index', compact('relationships'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clans = Clan::all();
        $marriages = Marriage::all();
        $individuals = Individual::all();

        return view('admin.pages.relationships.create', compact('individuals', 'clans', 'marriages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'description'   => 'nullable'
        // ]);

        Relationship::create($request->all());
        return redirect()->route('admin.relationships.index')->with('success', 'Hubungan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.pages.individual.edit', compact('clan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:clans,name,' . $clan->id,
            'description' => 'nullable'
        ]);

        $clan->update($request->all());
        return redirect()->route('admin.individual.index')->with('success', 'Marga berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $clan->delete();
        return redirect()->route('admin.individual.index')->with('success', 'Marga berhasil dihapus.');
    }
}
