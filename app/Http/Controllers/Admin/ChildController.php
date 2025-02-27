<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\Marriage;
use App\Models\Person;

class ChildController extends Controller
{
    public function index()
    {
        $children = Child::with(['marriage.husband.user', 'marriage.wife.user', 'person.user'])
            ->orderBy('marriage_id')
            ->orderBy('display_order')
            ->get();

        return view('admin.pages.children.index', compact('children'));
    }

    public function create()
    {
        $marriages = Marriage::all();

        $males = Person::where('gender', 'male')
                ->doesntHave('children')
                ->get();
                
        $females = Person::where('gender', 'female')
            ->doesntHave('children')
            ->get();

        $people = $males->merge($females);

        return view('admin.pages.children.create', compact(
            'marriages',
            'people',
            'males',
            'females'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'marriage_id' => 'required|exists:marriages,id',
            'child_id' => 'required|exists:people,id|unique:children,child_id',
        ]);

        // Dapatkan urutan terakhir
        $lastOrder = Child::where('marriage_id', $data['marriage_id'])
            ->max('display_order') ?? 0;

        $data['display_order'] = $lastOrder + 1;

        Child::create($data);

        // Update status boru jika anak perempuan
        $child = Person::find($data['child_id']);
        if ($child->gender === 'female') {
            $child->update(['is_boru_line' => true]);
        }

        return redirect()->route('admin.children.index')
            ->with('success', 'Data anak berhasil ditambahkan');
    }

    public function edit(Child $child)
    {
        $marriages = Marriage::all();
        $people = Person::doesntHave('children')
            ->orWhere('id', $child->child_id)
            ->get();

        return view('admin.pages.children.edit', compact(
            'child',
            'marriages',
            'people'
        ));
    }

    public function update(Request $request, Child $child)
    {
        $data = $request->validate([
            'marriage_id' => 'required|exists:marriages,id',
            'child_id' => 'required|exists:people,id|unique:children,child_id,'.$child->id,
        ]);

        // Jika pindah pernikahan, update display_order
        if ($child->marriage_id != $data['marriage_id']) {
            $lastOrder = Child::where('marriage_id', $data['marriage_id'])
                ->max('display_order') ?? 0;
            $data['display_order'] = $lastOrder + 1;
        }

        $child->update($data);

        return redirect()->route('admin.children.index')
            ->with('success', 'Data anak berhasil diperbarui');
    }

    public function destroy(Child $child)
    {
        $child->delete();

        // Update urutan anak lain
        Child::where('marriage_id', $child->marriage_id)
            ->where('display_order', '>', $child->display_order)
            ->decrement('display_order');

        return redirect()->route('admin.children.index')
            ->with('success', 'Data anak berhasil dihapus');
    }
}
