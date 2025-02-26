<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Relation;
use App\Models\FamilyMember;
use App\Models\RelationType;

class RelationController extends Controller
{
    // Menampilkan daftar hubungan adat
    public function index()
    {
        $relations = Relation::with(['user', 'relationType'])->get(); // Mengambil data hubungan adat dengan anggota keluarga dan jenis hubungan
        return view('admin.pages.relations.index', compact('relations'));
    }

    // Menampilkan form untuk menambah hubungan adat
    public function create()
    {
        $familyMembers = FamilyMember::all(); // Mengambil semua anggota keluarga untuk referensi
        $relationTypes = RelationType::all(); // Mengambil semua jenis hubungan adat
        return view('admin.pages.relations.create', compact('familyMembers', 'relationTypes'));
    }

    // Menyimpan hubungan adat baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:family_members,id',
            'relation_type_id' => 'required|exists:relation_types,id',
            'relation_description' => 'nullable|string',
        ]);

        Relation::create([
            'user_id' => $request->user_id,
            'relation_type_id' => $request->relation_type_id,
            'relation_description' => $request->relation_description,
        ]);

        return redirect()->route('admin.relations.index')->with('success', 'Hubungan adat berhasil ditambahkan');
    }

    // Menampilkan form untuk mengedit hubungan adat
    public function edit($id)
    {
        $relation = Relation::findOrFail($id);
        $familyMembers = FamilyMember::all(); // Mengambil semua anggota keluarga untuk referensi
        $relationTypes = RelationType::all(); // Mengambil semua jenis hubungan adat
        return view('admin.pages.relations.edit', compact('relation', 'familyMembers', 'relationTypes'));
    }

    // Mengupdate hubungan adat
    public function update(Request $request, $id)
    {
        $relation = Relation::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:family_members,id',
            'relation_type_id' => 'required|exists:relation_types,id',
            'relation_description' => 'nullable|string',
        ]);

        $relation->update([
            'user_id' => $request->user_id,
            'relation_type_id' => $request->relation_type_id,
            'relation_description' => $request->relation_description,
        ]);

        return redirect()->route('admin.relations.index')->with('success', 'Hubungan adat berhasil diperbarui');
    }

    // Menghapus hubungan adat
    public function destroy($id)
    {
        $relation = Relation::findOrFail($id);
        $relation->delete();

        return redirect()->route('admin.relations.index')->with('success', 'Hubungan adat berhasil dihapus');
    }
}
