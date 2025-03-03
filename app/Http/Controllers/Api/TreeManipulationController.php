<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\ParentChild;
use App\Models\FamilyGroup;
use App\Services\FamilyTreeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TreeManipulationController extends Controller
{
    protected $familyTreeService;
    
    public function __construct(FamilyTreeService $familyTreeService)
    {
        $this->familyTreeService = $familyTreeService;
    }
    
    /**
     * Add child to person
     */
    public function addChild(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:people,id',
            'child_id' => 'required|exists:people,id|different:parent_id',
            'marriage_id' => 'nullable|exists:marriages,id',
            'is_biological' => 'required|boolean',
            'birth_order' => 'nullable|integer|min:1'
        ]);
        
        $parent = Person::findOrFail($request->parent_id);
        $child = Person::findOrFail($request->child_id);
        
        // Validate parent-child gender consistency for Batak culture
        if ($parent->gender === 'male' && $child->marga_id !== $parent->marga_id) {
            return response()->json([
                'success' => false,
                'message' => 'In Batak culture, children must have the same marga as their father.'
            ], 422);
        }
        
        // Check if relationship already exists
        $existingRelation = ParentChild::where('parent_id', $request->parent_id)
            ->where('child_id', $request->child_id)
            ->first();
            
        if ($existingRelation) {
            return response()->json([
                'success' => false,
                'message' => 'This parent-child relationship already exists.'
            ], 422);
        }
        
        // Get birth order
        $birthOrder = $request->birth_order;
        if (!$birthOrder) {
            $maxBirthOrder = ParentChild::where('parent_id', $request->parent_id)
                ->max('birth_order');
            $birthOrder = $maxBirthOrder ? $maxBirthOrder + 1 : 1;
        }
        
        try {
            DB::beginTransaction();
            
            // Create parent-child relationship
            $parentChild = ParentChild::create([
                'parent_id' => $request->parent_id,
                'child_id' => $request->child_id,
                'marriage_id' => $request->marriage_id,
                'is_biological' => $request->is_biological,
                'birth_order' => $birthOrder
            ]);
            
            // If parent is male, update family group
            if ($parent->gender === 'male') {
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
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Child added successfully',
                'data' => $parentChild
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add child: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reorder children display
     */
    public function reorderChildren(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:people,id',
            'child_ids' => 'required|array',
            'child_ids.*' => 'exists:people,id'
        ]);
        
        $parent = Person::findOrFail($request->parent_id);
        
        // Verify all children belong to this parent
        $parentChildrenIds = $parent->children()->pluck('id')->toArray();
        $requestedChildIds = $request->child_ids;
        
        $invalidChildIds = array_diff($requestedChildIds, $parentChildrenIds);
        if (!empty($invalidChildIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Some specified children do not belong to this parent.',
                'invalid_ids' => $invalidChildIds
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            // Update birth order based on new sequence
            foreach ($requestedChildIds as $index => $childId) {
                ParentChild::where('parent_id', $parent->id)
                    ->where('child_id', $childId)
                    ->update(['birth_order' => $index + 1]);
            }
            
            // If parent is male, update family group display order
            if ($parent->gender === 'male') {
                foreach ($parent->familyGroupsAsFather as $familyGroup) {
                    $familyGroup->recalculateDisplayOrder();
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Children reordered successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder children: ' . $e->getMessage()
            ], 500);
        }
    }
}
