<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marriage;
use App\Models\FamilyMember;

class MarriageController extends Controller
{
    // Menampilkan daftar pernikahan
    public function index()
    {
        $marriages = Marriage::with(['husband', 'wife'])->get(); // Mengambil data pernikahan dengan suami dan istri
        return view('admin.pages.marriages.index', compact('marriages'));
    }

    // Menampilkan form untuk menambah pernikahan
    public function create()
    {
        $familyMembers = FamilyMember::all(); // Mengambil semua anggota keluarga untuk memilih suami dan istri
        return view('admin.pages.marriages.create', compact('familyMembers'));
    }

    // Menyimpan data pernikahan baru
    public function store(Request $request)
    {
        $request->validate([
            'husband_id' => 'required|exists:family_members,id',
            'wife_id' => 'required|exists:family_members,id',
            'marriage_date' => 'required|date',
            'divorce_date' => 'nullable|date',
        ]);

        Marriage::create([
            'husband_id' => $request->husband_id,
            'wife_id' => $request->wife_id,
            'marriage_date' => $request->marriage_date,
            'divorce_date' => $request->divorce_date,
        ]);

        return redirect()->route('admin.marriages.index')->with('success', 'Pernikahan berhasil ditambahkan');
    }

    // Menampilkan form untuk mengedit data pernikahan
    public function edit($id)
    {
        $marriage = Marriage::findOrFail($id);
        $familyMembers = FamilyMember::all(); // Mengambil semua anggota keluarga untuk memilih suami dan istri
        return view('admin.pages.marriages.edit', compact('marriage', 'familyMembers'));
    }

    // Mengupdate data pernikahan
    public function update(Request $request, $id)
    {
        $marriage = Marriage::findOrFail($id);

        $request->validate([
            'husband_id' => 'required|exists:family_members,id',
            'wife_id' => 'required|exists:family_members,id',
            'marriage_date' => 'required|date',
            'divorce_date' => 'nullable|date',
        ]);

        $marriage->update([
            'husband_id' => $request->husband_id,
            'wife_id' => $request->wife_id,
            'marriage_date' => $request->marriage_date,
            'divorce_date' => $request->divorce_date,
        ]);

        return redirect()->route('admin.marriages.index')->with('success', 'Pernikahan berhasil diperbarui');
    }

    // Menghapus data pernikahan
    public function destroy($id)
    {
        $marriage = Marriage::findOrFail($id);
        $marriage->delete();

        return redirect()->route('admin.marriages.index')->with('success', 'Pernikahan berhasil dihapus');
    }
}
