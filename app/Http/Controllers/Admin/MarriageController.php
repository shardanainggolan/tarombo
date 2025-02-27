<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marriage;
use App\Models\Person;

class MarriageController extends Controller
{
    public function index()
    {
        $marriages = Marriage::with(['husband.user', 'wife.user'])
            ->latest()
            ->get();

        return view('admin.pages.marriages.index', compact('marriages'));
    }

    public function create()
    {
        $males = Person::where('gender', 'male')->get();
        $females = Person::where('gender', 'female')->get();

        return view('admin.pages.marriages.create', compact('males', 'females'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'husband_id' => 'required|exists:people,id',
            'wife_id' => 'required|exists:people,id|different:husband_id',
            'marriage_date' => 'required|date',
            'is_active' => 'boolean'
        ]);

        $husband = Person::find($data['husband_id']);
        $wife = Person::find($data['wife_id']);

        // Validasi marga
        if ($husband->marga === $wife->marga) {
            return back()->withErrors(['marga' => 'Pernikahan dalam marga yang sama dilarang!']);
        }

        // Non-aktifkan pernikahan sebelumnya jika ada
        if ($request->is_active) {
            Marriage::where('husband_id', $husband->id)
                ->orWhere('wife_id', $wife->id)
                ->update(['is_active' => false]);
        }

        Marriage::create($data);

        return redirect()->route('admin.marriages.index')
            ->with('success', 'Pernikahan berhasil ditambahkan');
    }

    public function edit(Marriage $marriage)
    {
        $males = Person::where('gender', 'male')->get();
        $females = Person::where('gender', 'female')->get();

        return view('admin.pages.marriages.edit', compact(
            'marriage',
            'males',
            'females'
        ));
    }

    public function update(Request $request, Marriage $marriage)
    {
        $data = $request->validate([
            'husband_id' => 'required|exists:people,id',
            'wife_id' => 'required|exists:people,id|different:husband_id',
            'marriage_date' => 'required|date',
            'divorce_date' => 'nullable|date|after:marriage_date',
            'is_active' => 'boolean'
        ]);

        // Validasi marga
        $husband = Person::find($data['husband_id']);
        $wife = Person::find($data['wife_id']);
        if ($husband->marga === $wife->marga) {
            return back()->withErrors(['marga' => 'Pernikahan dalam marga yang sama dilarang!']);
        }

        // Update status aktif
        if ($data['is_active']) {
            Marriage::where('id', '!=', $marriage->id)
                ->where(function($query) use ($data) {
                    $query->where('husband_id', $data['husband_id'])
                          ->orWhere('wife_id', $data['wife_id']);
                })
                ->update(['is_active' => false]);
        }

        $marriage->update($data);

        return redirect()->route('admin.marriages.index')
            ->with('success', 'Data pernikahan berhasil diperbarui');
    }

    public function destroy(Marriage $marriage)
    {
        if ($marriage->children()->exists()) {
            return back()->withErrors([
                'message' => 'Tidak dapat menghapus pernikahan yang memiliki anak'
            ]);
        }

        $marriage->delete();
        return redirect()->route('admin.marriages.index')
            ->with('success', 'Pernikahan berhasil dihapus');
    }
}
