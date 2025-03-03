<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartuturanCategory;
use App\Models\PartuturanTerm;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PartuturanCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PartuturanCategory::withCount('terms')->latest();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('terms_count', function ($row) {
                    return $row->terms_count;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <div class="d-flex align-items-center">
                            <a href="' . route('admin.partuturan-categories.edit', $row->id) . '" class="btn btn-icon btn-label-info waves-effect me-2">
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
        
        return view('admin.pages.partuturan-categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.partuturan-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:partuturan_categories,name',
            'description' => 'nullable|string'
        ]);

        PartuturanCategory::create($request->all());

        return redirect()->route('admin.partuturan-categories.index')
            ->with('success', 'Kategori partuturan berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PartuturanCategory $partuturanCategory)
    {
        $partuturanCategory->load('terms');
        return view('admin.pages.partuturan-categories.edit', compact('partuturanCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PartuturanCategory $partuturanCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:partuturan_categories,name,' . $partuturanCategory->id,
            'description' => 'nullable|string'
        ]);

        $partuturanCategory->update($request->all());

        return redirect()->route('admin.partuturan-categories.index')
            ->with('success', 'Kategori partuturan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PartuturanCategory $partuturanCategory)
    {
        try {
            // Check for associated terms
            if ($partuturanCategory->terms->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kategori tidak dapat dihapus karena memiliki ' . $partuturanCategory->terms->count() . ' istilah partuturan terkait.'
                ], 422);
            }
            
            $partuturanCategory->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Kategori partuturan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus kategori. ' . $e->getMessage()
            ], 422);
        }
    }
}
