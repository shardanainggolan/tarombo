<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartuturanRule;
use App\Models\PartuturanTerm;
use App\Models\RelationshipPattern;
use App\Models\CachedRelationship;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PartuturanRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PartuturanRule::with(['partuturanTerm', 'relationshipPattern'])->latest();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('term', function ($row) {
                    return $row->partuturanTerm->term;
                })
                ->addColumn('pattern', function ($row) {
                    return $this->formatPatternForDisplay($row->relationshipPattern->pattern);
                })
                ->addColumn('ego_gender_label', function ($row) {
                    return $row->ego_gender === 'male' ? 
                        '<span class="badge bg-primary">Laki-laki</span>' : 
                        '<span class="badge bg-danger">Perempuan</span>';
                })
                ->addColumn('relative_gender_label', function ($row) {
                    return $row->relative_gender === 'male' ? 
                        '<span class="badge bg-primary">Laki-laki</span>' : 
                        '<span class="badge bg-danger">Perempuan</span>';
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <div class="d-flex align-items-center">
                            <a href="' . route('admin.partuturan-rules.edit', $row->id) . '" class="btn btn-icon btn-label-info waves-effect me-2">
                                <i class="ti ti-pencil"></i>
                            </a>
                            <button type="button" id="' . $row->id . '" class="delete-record btn btn-icon btn-label-danger waves-effect">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'pattern', 'ego_gender_label', 'relative_gender_label'])
                ->make(true);
        }
        
        return view('admin.pages.partuturan-rules.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $terms = PartuturanTerm::with('category')->orderBy('term')->get();
        $patterns = RelationshipPattern::orderBy('pattern')->get();
        
        $preSelectedTerm = null;
        if ($request->has('term_id')) {
            $preSelectedTerm = PartuturanTerm::findOrFail($request->term_id);
        }
        
        $preSelectedPattern = null;
        if ($request->has('pattern_id')) {
            $preSelectedPattern = RelationshipPattern::findOrFail($request->pattern_id);
        }
        
        return view('admin.pages.partuturan-rules.create', compact(
            'terms', 
            'patterns', 
            'preSelectedTerm', 
            'preSelectedPattern'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'partuturan_term_id' => 'required|exists:partuturan_terms,id',
            'relationship_pattern_id' => 'required|exists:relationship_patterns,id',
            'ego_gender' => 'required|in:male,female',
            'relative_gender' => 'required|in:male,female'
        ]);

        // Check for existing rule with same criteria
        $exists = PartuturanRule::where('partuturan_term_id', $request->partuturan_term_id)
            ->where('relationship_pattern_id', $request->relationship_pattern_id)
            ->where('ego_gender', $request->ego_gender)
            ->where('relative_gender', $request->relative_gender)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['general' => 'Aturan dengan kombinasi ini sudah ada.'])->withInput();
        }

        PartuturanRule::create($request->all());

        // If checkbox to invalidate cache is checked, clear cached relationships
        if ($request->has('invalidate_cache') && $request->invalidate_cache) {
            $this->invalidateCachedRelationships($request->relationship_pattern_id);
        }

        return redirect()->route('admin.partuturan-rules.index')
            ->with('success', 'Aturan partuturan berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PartuturanRule $partuturanRule)
    {
        $terms = PartuturanTerm::with('category')->orderBy('term')->get();
        $patterns = RelationshipPattern::orderBy('pattern')->get();
        
        return view('admin.pages.partuturan-rules.edit', compact('partuturanRule', 'terms', 'patterns'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PartuturanRule $partuturanRule)
    {
        $request->validate([
            'partuturan_term_id' => 'required|exists:partuturan_terms,id',
            'relationship_pattern_id' => 'required|exists:relationship_patterns,id',
            'ego_gender' => 'required|in:male,female',
            'relative_gender' => 'required|in:male,female'
        ]);

        // Check for existing rule with same criteria, excluding this rule
        $exists = PartuturanRule::where('partuturan_term_id', $request->partuturan_term_id)
            ->where('relationship_pattern_id', $request->relationship_pattern_id)
            ->where('ego_gender', $request->ego_gender)
            ->where('relative_gender', $request->relative_gender)
            ->where('id', '!=', $partuturanRule->id)
            ->exists();
            
        if ($exists) {
            return back()->withErrors(['general' => 'Aturan dengan kombinasi ini sudah ada.'])->withInput();
        }

        $oldPatternId = $partuturanRule->relationship_pattern_id;
        $newPatternId = $request->relationship_pattern_id;
        
        $partuturanRule->update($request->all());

        // If checkbox to invalidate cache is checked or pattern changed, clear cached relationships
        if (($request->has('invalidate_cache') && $request->invalidate_cache) || 
            $oldPatternId != $newPatternId) {
            
            // Invalidate for both old and new pattern if changed
            if ($oldPatternId != $newPatternId) {
                $this->invalidateCachedRelationships($oldPatternId);
            }
            $this->invalidateCachedRelationships($newPatternId);
        }

        return redirect()->route('admin.partuturan-rules.index')
            ->with('success', 'Aturan partuturan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PartuturanRule $partuturanRule)
    {
        try {
            // Store pattern ID before deletion
            $patternId = $partuturanRule->relationship_pattern_id;
            
            $partuturanRule->delete();
            
            // Invalidate cached relationships
            $this->invalidateCachedRelationships($patternId);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Aturan partuturan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus aturan. ' . $e->getMessage()
            ], 422);
        }
    }
    
    /**
     * Clear cached relationships for a specific pattern
     */
    private function invalidateCachedRelationships($patternId)
    {
        CachedRelationship::where('relationship_pattern_id', $patternId)->delete();
    }
    
    /**
     * Format pattern for display with visual cues
     */
    private function formatPatternForDisplay($pattern)
    {
        $segments = explode('.', $pattern);
        $formatted = '';
        
        foreach ($segments as $index => $segment) {
            $segmentClass = $this->getSegmentColorClass($segment);
            $arrow = $index > 0 ? '<i class="ti ti-arrow-right mx-1"></i>' : '';
            $formatted .= $arrow . '<span class="badge ' . $segmentClass . ' me-1">' . $segment . '</span>';
        }
        
        return $formatted;
    }
    
    /**
     * Get color class for pattern segment
     */
    private function getSegmentColorClass($segment)
    {
        switch ($segment) {
            case 'father':
            case 'son':
            case 'brother':
            case 'husband':
                return 'bg-primary';
            case 'mother':
            case 'daughter':
            case 'sister':
            case 'wife':
                return 'bg-danger';
            case 'child':
            case 'parent':
            case 'sibling':
            case 'spouse':
                return 'bg-warning';
            default:
                return 'bg-secondary';
        }
    }
}
