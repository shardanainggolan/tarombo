<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clan;

class ClanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        session(['activeMenu' => 'listClans']);
        session(['activeParentMenu' => 'clans']);
        session(['activeSubParentMenu' => '']);

        $clans = Clan::all();
        return view('admin.pages.clans.index', compact('clans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:clans']);
        Clan::create($request->all());
        
        return redirect()->route('clans.index')->with('success', 'Marga berhasil ditambahkan');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
