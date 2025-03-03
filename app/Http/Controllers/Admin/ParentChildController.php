<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Marriage;
use App\Models\ParentChild;
use App\Models\FamilyGroup;
use Illuminate\Http\Request;

class ParentChildController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $parent = null;
        if ($request->has('parent_id')) {
            $parent = Person::findOrFail($request->parent_id);
        }
        
        // Get potential children (people without this parent)
        $existingChildrenIds = [];
        if ($parent) {
            $existingChildrenIds = $parent->children->pluck('id')->toArray();
        }
        
        $potentialChildren = Person::whereNotIn('id', $existingChildrenIds)
            ->when($parent, function ($query) use ($parent) {
                // If parent is female, filter out people with different marga
                // This implements Batak patrilineal rules
                if ($parent->gender == 'female') {
                    return $query->whereNotIn('id', [$parent->id]);
                } else {
                    // For male parent, children should have same marga
                    return $query->where('marga_id', $parent->marga_id)
                        ->whereNotIn('id', [$parent->id]);
                }
            })
            ->orderBy('first_name')
            ->get();
            
        // Get marriages for birth association
        $marriages = [];
        if ($parent) {
            if ($parent->gender == 'male') {
                $marriages = $parent->marriagesAsHusband()->with('wife')->get();
            } else {
                $marriages = $parent->marriagesAsWife()->with('husband')->get();
            }
        }
        
        return view('admin.pages.parent-child.create', compact('parent', 'potentialChildren', 'marriages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:people,id',
            'child_ids' => 'required|array',
            'child_ids.*' => 'exists:people,id',
            'marriage_id' => 'nullable|exists:marriages,id',
            'is_biological' => 'required|boolean',
            'birth_order' => 'required|integer|min:1'
        ]);
        
        $parent = Person::findOrFail($request->parent_id);
        $currentChildCount = $parent->children()->count();
        
        // Process each selected child
        foreach ($request->child_ids as $childId) {
            $child = Person::findOrFail($childId);
            
            // Create parent-child relationship
            ParentChild::create([
                'parent_id' => $parent->id,
                'child_id' => $childId,
                'marriage_id' => $request->marriage_id,
                'is_biological' => $request->is_biological,
                'birth_order' => $request->birth_order + $currentChildCount
            ]);
            
            // If parent is male, also add to appropriate family group
            if ($parent->gender == 'male') {
                // Find or create family group
                $familyGroup = null;
                if ($request->marriage_id) {
                    $marriage = Marriage::findOrFail($request->marriage_id);
                    $familyGroup = FamilyGroup::firstOrCreate(
                        ['father_id' => $parent->id, 'mother_id' => $marriage->wife_id],
                        ['notes' => 'Auto-created from parent-child relationship']
                    );
                } else {
                    $familyGroup = FamilyGroup::firstOrCreate(
                        ['father_id' => $parent->id, 'mother_id' => null],
                        ['notes' => 'Auto-created from parent-child relationship']
                    );
                }
                
                // Recalculate display order to implement male-first rule
                $familyGroup->recalculateDisplayOrder();
            }
            
            // If parent is female, respect lineage limitation
            // No additional processing needed as we're already implementing
            // the limitation in the query for potential children
        }
        
        return redirect()->route('admin.people.show', $parent->id)
            ->with('success', 'Hubungan orang tua-anak berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified parent-child relationship.
     */
    public function edit(ParentChild $parentChild)
    {
        // dd($parentChild);
        $parent = $parentChild->parent;
        $child = $parentChild->child;
        
        // Get marriages for birth association
        $marriages = [];
        if ($parent->gender == 'male') {
            $marriages = $parent->marriagesAsHusband()->with('wife')->get();
        } else {
            $marriages = $parent->marriagesAsWife()->with('husband')->get();
        }
        
        return view('admin.pages.parent-child.edit', compact('parentChild', 'parent', 'child', 'marriages'));
    }

    /**
     * Update the specified parent-child relationship in storage.
     */
    public function update(Request $request, ParentChild $parentChild)
    {
        $request->validate([
            'marriage_id' => 'nullable|exists:marriages,id',
            'is_biological' => 'required|boolean',
            'birth_order' => 'required|integer|min:1'
        ]);
        
        $parent = $parentChild->parent;
        
        // Check if birth order has changed
        $birthOrderChanged = $parentChild->birth_order != $request->birth_order;
        
        // Update relationship
        $parentChild->update([
            'marriage_id' => $request->marriage_id,
            'is_biological' => $request->is_biological,
            'birth_order' => $request->birth_order
        ]);
        
        // If birth order changed, reorder siblings
        if ($birthOrderChanged) {
            $this->reorderSiblings($parent->id);
        }
        
        // Update family group display order if needed
        if ($parent->gender == 'male') {
            foreach ($parent->familyGroupsAsFather as $familyGroup) {
                $familyGroup->recalculateDisplayOrder();
            }
        }
        
        return redirect()->route('admin.people.show', $parent->id)
            ->with('success', 'Hubungan orang tua-anak berhasil diperbarui');
    }

    /**
     * Reorder siblings to maintain consistent birth order sequence.
     */
    private function reorderSiblings($parentId)
    {
        // Get all children of this parent ordered by birth order
        $relationships = ParentChild::where('parent_id', $parentId)
            ->orderBy('birth_order')
            ->get();
        
        // Reassign birth order to ensure sequential ordering without gaps
        $order = 1;
        foreach ($relationships as $rel) {
            if ($rel->birth_order != $order) {
                $rel->update(['birth_order' => $order]);
            }
            $order++;
        }
    }

    /**
     * AJAX endpoint to reorder children display.
     */
    public function reorderChildren(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:people,id',
            'child_ids' => 'required|array',
            'child_ids.*' => 'exists:people,id'
        ]);
        
        $parent = Person::findOrFail($request->parent_id);
        
        // Update birth order based on new sequence
        foreach ($request->child_ids as $index => $childId) {
            ParentChild::where('parent_id', $parent->id)
                ->where('child_id', $childId)
                ->update(['birth_order' => $index + 1]);
        }
        
        // Update family group display order
        if ($parent->gender == 'male') {
            foreach ($parent->familyGroupsAsFather as $familyGroup) {
                $familyGroup->recalculateDisplayOrder();
            }
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Urutan anak berhasil diperbarui'
        ]);
    }

    /**
     * Remove parent-child relationship
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:people,id',
            'child_id' => 'required|exists:people,id',
        ]);
        
        $relation = ParentChild::where('parent_id', $request->parent_id)
            ->where('child_id', $request->child_id)
            ->firstOrFail();
            
        $parent = Person::findOrFail($request->parent_id);
        
        try {
            $relation->delete();
            
            // Update family group display order if needed
            if ($parent->gender == 'male') {
                $familyGroups = $parent->familyGroupsAsFather;
                foreach ($familyGroups as $familyGroup) {
                    $familyGroup->recalculateDisplayOrder();
                }
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Hubungan orang tua-anak berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus hubungan. ' . $e->getMessage()
            ], 422);
        }
    }
}
