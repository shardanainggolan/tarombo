<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Individual;
use App\Models\Clan;
use App\Models\Marriage;

class IndividualController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        session(['activeMenu' => 'listIndividual']);
        session(['activeParentMenu' => 'individual']);
        session(['activeSubParentMenu' => '']);

        $individuals = Individual::all();
        return view('admin.pages.individual.index', compact('individuals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clans = Clan::all();
        $marriages = Marriage::all();

        return view('admin.pages.individual.create', compact('clans', 'marriages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
            
        //     'description'   => 'nullable'
        // ]);

        Individual::create($request->all());
        return redirect()->route('admin.individual.index')->with('success', 'Orang berhasil ditambahkan.');
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
