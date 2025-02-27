<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FamilyMember;
use App\Models\User;

class FamilyMemberController extends Controller
{
    // Menampilkan daftar anggota keluarga
    public function index()
    {
        $familyMembers = FamilyMember::with(['father', 'mother', 'spouse'])->get();
        return view('admin.pages.family_members.index', compact('familyMembers'));
    }

    // Menampilkan form untuk menambah anggota keluarga
    public function create()
    {
        $users = User::all(); // Ambil semua pengguna untuk referensi
        $familyMembers = FamilyMember::with(['father', 'mother', 'spouse'])->get();
        return view('admin.pages.family_members.create', compact('users', 'familyMembers'));
    }

    // Menyimpan anggota keluarga baru
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'marga' => 'required|string|max:255',
            'father_id' => 'nullable|exists:family_members,id',
            'mother_id' => 'nullable|exists:family_members,id',
            'spouse_id' => 'nullable|exists:family_members,id',
            'gender' => 'required|in:male,female',
            'position_in_family' => 'required|in:son,daughter,husband,wife,brother,sister',
            'birth_date' => 'required|date',
        ]);

        FamilyMember::create($request->all());

        return redirect()->route('admin.family_members.index')->with('success', 'Anggota keluarga berhasil ditambahkan');
    }

    // Menampilkan form untuk mengedit anggota keluarga
    public function edit($id)
    {
        $familyMember = FamilyMember::findOrFail($id);
        $users = User::all(); // Ambil semua pengguna untuk referensi
        return view('admin.pages.family_members.edit', compact('familyMember', 'users'));
    }

    // Mengupdate anggota keluarga
    public function update(Request $request, $id)
    {
        $familyMember = FamilyMember::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'marga' => 'required|string|max:255',
            'father_id' => 'nullable|exists:family_members,id',
            'mother_id' => 'nullable|exists:family_members,id',
            'spouse_id' => 'nullable|exists:family_members,id',
            'gender' => 'required|in:male,female',
            'position_in_family' => 'required|in:son,daughter,husband,wife,brother,sister',
            'birth_date' => 'required|date',
        ]);

        $familyMember->update($request->all());

        return redirect()->route('admin.family_members.index')->with('success', 'Anggota keluarga berhasil diperbarui');
    }

    // Menghapus anggota keluarga
    public function destroy($id)
    {
        $familyMember = FamilyMember::findOrFail($id);
        $familyMember->delete();

        return redirect()->route('admin.family_members.index')->with('success', 'Anggota keluarga berhasil dihapus');
    }
}
