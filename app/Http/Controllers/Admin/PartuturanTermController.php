<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartuturanTerm;
use App\Models\PartuturanCategory;
use App\Models\PartuturanRule;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PartuturanTermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PartuturanTerm::with('category')->withCount('rules')->latest();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category_name', function ($row) {
                    return $row->category->name;
                })
                ->addColumn('rules_count', function ($row) {
                    return $row->rules_count;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <div class="d-flex align-items-center">
                            <a href="' . route('admin.partuturan-terms.edit', $row->id) . '" class="btn btn-icon btn-label-info waves-effect me-2">
                                <i class="ti ti-pencil"></i>
                            </a>
                            <button type="button" id="' . $row->id . '" class="delete-record btn btn-icon btn-label-danger waves-effect">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('admin.pages.partuturan-terms.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories = PartuturanCategory::orderBy('name')->get();
        $preSelectedCategory = null;
        
        if ($request->has('category_id')) {
            $preSelectedCategory = PartuturanCategory::findOrFail($request->category_id);
        }
        
        return view('admin.pages.partuturan-terms.create', compact('categories', 'preSelectedCategory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'term' => 'required|string|max:255',
            'category_id' => 'required|exists:partuturan_categories,id',
            'description' => 'nullable|string'
        ]);

        $term = PartuturanTerm::create($request->all());

        if ($request->has('redirect_to_rules') && $request->redirect_to_rules) {
            return redirect()->route('admin.partuturan-rules.create', ['term_id' => $term->id])
                ->with('success', 'Istilah partuturan berhasil ditambahkan. Silahkan tambahkan aturan untuk istilah ini.');
        }

        return redirect()->route('admin.partuturan-terms.index')
            ->with('success', 'Istilah partuturan berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PartuturanTerm $partuturanTerm)
    {
        $categories = PartuturanCategory::orderBy('name')->get();
        $partuturanTerm->load('rules.relationshipPattern');
        
        return view('admin.pages.partuturan-terms.edit', compact('partuturanTerm', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PartuturanTerm $partuturanTerm)
    {
        $request->validate([
            'term' => 'required|string|max:255',
            'category_id' => 'required|exists:partuturan_categories,id',
            'description' => 'nullable|string'
        ]);

        $partuturanTerm->update($request->all());

        return redirect()->route('admin.partuturan-terms.index')
            ->with('success', 'Istilah partuturan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PartuturanTerm $partuturanTerm)
    {
        try {
            // Check for associated rules
            if ($partuturanTerm->rules->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Istilah tidak dapat dihapus karena memiliki ' . $partuturanTerm->rules->count() . ' aturan terkait.'
                ], 422);
            }
            
            // Check for cached relationships using this term
            $cachedCount = $partuturanTerm->cachedRelationships()->count();
            if ($cachedCount > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Istilah tidak dapat dihapus karena digunakan dalam ' . $cachedCount . ' relasi yang tersimpan.'
                ], 422);
            }
            
            $partuturanTerm->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Istilah partuturan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus istilah. ' . $e->getMessage()
            ], 422);
        }
    }
}
