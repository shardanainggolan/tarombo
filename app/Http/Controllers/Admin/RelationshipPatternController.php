<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RelationshipPattern;
use App\Models\PartuturanRule;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RelationshipPatternController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = RelationshipPattern::withCount('rules')->latest();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('formatted_pattern', function ($row) {
                    return $this->formatPatternForDisplay($row->pattern);
                })
                ->addColumn('rules_count', function ($row) {
                    return $row->rules_count;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <div class="d-flex align-items-center">
                            <a href="' . route('admin.relationship-patterns.edit', $row->id) . '" class="btn btn-icon btn-label-info waves-effect me-2">
                                <i class="ti ti-pencil"></i>
                            </a>
                            <button type="button" id="' . $row->id . '" class="delete-record btn btn-icon btn-label-danger waves-effect">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'formatted_pattern'])
                ->make(true);
        }
        
        return view('admin.pages.relationship-patterns.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.relationship-patterns.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pattern' => 'required|string|max:255|unique:relationship_patterns,pattern',
            'description' => 'nullable|string'
        ]);

        // Normalize pattern (remove spaces, ensure lowercase)
        $pattern = strtolower(str_replace(' ', '', $request->pattern));
        
        RelationshipPattern::create([
            'pattern' => $pattern,
            'description' => $request->description
        ]);

        return redirect()->route('admin.relationship-patterns.index')
            ->with('success', 'Pola hubungan berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RelationshipPattern $relationshipPattern)
    {
        $relationshipPattern->load('rules.partuturanTerm');
        return view('admin.pages.relationship-patterns.edit', compact('relationshipPattern'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RelationshipPattern $relationshipPattern)
    {
        $request->validate([
            'pattern' => 'required|string|max:255|unique:relationship_patterns,pattern,' . $relationshipPattern->id,
            'description' => 'nullable|string'
        ]);

        // Normalize pattern (remove spaces, ensure lowercase)
        $pattern = strtolower(str_replace(' ', '', $request->pattern));
        
        $relationshipPattern->update([
            'pattern' => $pattern,
            'description' => $request->description
        ]);

        return redirect()->route('admin.relationship-patterns.index')
            ->with('success', 'Pola hubungan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RelationshipPattern $relationshipPattern)
    {
        try {
            // Check for associated rules
            if ($relationshipPattern->rules->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pola hubungan tidak dapat dihapus karena memiliki ' . $relationshipPattern->rules->count() . ' aturan terkait.'
                ], 422);
            }
            
            // Check for cached relationships using this pattern
            $cachedCount = $relationshipPattern->cachedRelationships()->count();
            if ($cachedCount > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pola hubungan tidak dapat dihapus karena digunakan dalam ' . $cachedCount . ' relasi yang tersimpan.'
                ], 422);
            }
            
            $relationshipPattern->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Pola hubungan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus pola hubungan. ' . $e->getMessage()
            ], 422);
        }
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
